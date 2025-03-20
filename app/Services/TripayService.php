<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;

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
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey
        ])->get($this->baseUrl . '/merchant/payment-channel');

        if ($response->successful()) {
            return $response->json()['data'];
        }

        return [];
    }

    public function createTransaction(Order $order, string $method)
    {
        $merchantRef = 'INV-' . $order->id;
        $amount = $order->final_price;

        $data = [
            'method' => $method,
            'merchant_ref' => $merchantRef,
            'amount' => $amount,
            'customer_name' => $order->user->name,
            'customer_email' => $order->user->email,
            'customer_phone' => $order->user->phone,
            'order_items' => $this->formatOrderItems($order),
            'return_url' => route('payment.success'),
            'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
            'signature' => $this->generateSignature($merchantRef, $amount)
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey
        ])->post($this->baseUrl . '/transaction/create', $data);

        if ($response->successful()) {
            $result = $response->json();
            
            // Update atau buat payment record
            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'reference_id' => $result['data']['reference'],
                    'payment_method' => $method,
                    'amount' => $amount,
                    'payment_url' => $result['data']['checkout_url'],
                    'status' => 'pending',
                    'payment_details' => $result['data']
                ]
            );

            return $payment;
        }

        throw new \Exception('Gagal membuat transaksi: ' . $response->json()['message']);
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

    protected function generateSignature(string $merchantRef, float $amount): string
    {
        return hash_hmac(
            'sha256',
            $this->merchantCode . $merchantRef . $amount,
            $this->privateKey
        );
    }

    protected function formatOrderItems(Order $order): array
    {
        return $order->items->map(function ($item) {
            return [
                'name' => $item->menu->name,
                'price' => $item->price,
                'quantity' => $item->quantity
            ];
        })->toArray();
    }
}