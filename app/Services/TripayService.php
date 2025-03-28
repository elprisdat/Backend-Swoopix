<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class TripayService
{
    protected string $apiKey;
    protected string $privateKey;
    protected string $merchantCode;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.tripay.api_key');
        $this->privateKey = config('services.tripay.private_key');
        $this->merchantCode = config('services.tripay.merchant_code');
        $this->baseUrl = config('services.tripay.sandbox') 
            ? 'https://tripay.co.id/api-sandbox'
            : 'https://tripay.co.id/api';
    }

    public function getPaymentChannels()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey
            ])->get($this->baseUrl . '/merchant/payment-channel');

            if (!$response->successful()) {
                \Log::error('Tripay API error: ' . $response->body());
                throw new \Exception($response->json()['message'] ?? 'Failed to get payment channels');
            }

            $data = $response->json();
            
            if (!isset($data['data']) || !is_array($data['data'])) {
                \Log::error('Invalid Tripay response format: ' . json_encode($data));
                throw new \Exception('Invalid response format from Tripay');
            }

            // Format data sesuai dengan yang dibutuhkan frontend
            return array_map(function($channel) {
                return [
                    'code' => $channel['code'],
                    'name' => $channel['name'],
                    'icon_url' => $channel['icon_url'] ?? null,
                    'description' => $channel['description'] ?? null
                ];
            }, $data['data']);
        } catch (\Exception $e) {
            \Log::error('Tripay getPaymentChannels error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createTransaction(Order $order, string $method)
    {
        try {
            // Format amount tanpa desimal
            $amount = number_format($order->final_price, 0, '', '');

            $data = [
                'method' => $method,
                'merchant_ref' => $order->id,
                'amount' => (int)$amount,
                'customer_name' => $order->user->name ?? 'Customer',
                'customer_email' => 'customer@example.com',
                'customer_phone' => $order->user->phone ?? '081234567890',
                'order_items' => $order->items->map(function ($item) {
                    return [
                        'name' => $item->menu->name,
                        'price' => (int)$item->price,
                        'quantity' => $item->quantity
                    ];
                })->toArray(),
                'return_url' => route('payment.success'),
                'expired_time' => $order->expired_at->timestamp,
            ];

            // Generate signature setelah data lengkap
            $data['signature'] = $this->generateSignature($order->id, $amount);

            Log::info('Creating Tripay transaction', $data);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey
            ])->post($this->baseUrl . '/transaction/create', $data);

            if (!$response->successful()) {
                Log::error('Tripay API error', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                    'request_data' => $data
                ]);
                throw new \Exception('Tripay API error: ' . ($response->json()['message'] ?? 'Unknown error'));
            }

            $result = $response->json();
            Log::info('Tripay transaction created', $result);

            return (object) [
                'payment_method' => $method,
                'payment_url' => $result['data']['checkout_url'] ?? null,
                'status' => 'pending',
                'reference' => $result['data']['reference'] ?? null,
                'token' => $result['data']['reference'] ?? null,
                'expired_time' => $order->expired_at->format('Y-m-d H:i:s')
            ];
        } catch (\Exception $e) {
            Log::error('Error creating Tripay transaction: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'payment_method' => $method,
                'amount' => $order->final_price
            ]);
            throw $e;
        }
    }

    public function verifyCallback(array $data)
    {
        $signature = $data['signature'];
        $eventData = json_decode($data['event'], true);
        
        // Generate signature untuk verifikasi
        $expectedSignature = hash_hmac('sha256', $eventData['merchant_ref'] . $eventData['status'], $this->privateKey);
        
        if ($signature !== $expectedSignature) {
            throw new \Exception('Invalid signature');
        }

        // Update status pembayaran
        $payment = Payment::where('reference_id', $eventData['reference'])->firstOrFail();
        
        switch ($eventData['status']) {
            case 'PAID':
                $payment->markAsPaid();
                break;
            case 'EXPIRED':
                $payment->markAsExpired();
                break;
            case 'FAILED':
                $payment->markAsFailed();
                break;
        }

        return $payment;
    }

    protected function generateSignature($merchantRef, $amount)
    {
        // Format amount ke string tanpa desimal
        $amount = number_format($amount, 0, '', '');
        
        // Gabungkan data sesuai format Tripay
        $signature = $this->merchantCode . $merchantRef . $amount;
        
        // Generate signature dengan private key
        return hash_hmac('sha256', $signature, $this->privateKey);
    }

    public function validateCallback(Request $request)
    {
        $callbackSignature = $request->header('X-Callback-Signature');
        $json = $request->getContent();

        $signature = hash_hmac('sha256', $json, $this->privateKey);

        return hash_equals($signature, $callbackSignature);
    }
}