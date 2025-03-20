<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FontteService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.fonnte.api_key');
        $this->baseUrl = 'https://api.fonnte.com';
    }

    public function sendOTP(string $phone, string $otp): bool
    {
        try {
            $message = "Kode OTP Anda adalah: {$otp}\n\nKode ini berlaku selama 5 menit.\nJangan berikan kode ini kepada siapapun.";

            $response = Http::withHeaders([
                'Authorization' => $this->apiKey
            ])->post($this->baseUrl . '/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62', // Indonesia
                'delay' => '0',
                'typing' => true,
                'connectOnly' => true,
            ]);

            if ($response->successful() && $response->json('status')) {
                Log::info('OTP sent successfully', [
                    'phone' => $phone,
                    'response' => $response->json()
                ]);
                return true;
            }

            Log::error('Failed to send OTP', [
                'phone' => $phone,
                'response' => $response->json()
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Error sending OTP', [
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function sendOrderNotification(string $phone, array $orderData, string $type = 'store'): bool
    {
        try {
            $message = $type === 'store' ? 
                $this->formatStoreOrderMessage($orderData) : 
                $this->formatUserOrderMessage($orderData);

            $response = Http::withHeaders([
                'Authorization' => $this->apiKey
            ])->post($this->baseUrl . '/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
                'delay' => '0',
                'typing' => true,
                'connectOnly' => true,
            ]);

            if ($response->successful() && $response->json('status')) {
                Log::info('Order notification sent successfully', [
                    'phone' => $phone,
                    'order_id' => $orderData['id'],
                    'type' => $type,
                    'response' => $response->json()
                ]);
                return true;
            }

            Log::error('Failed to send order notification', [
                'phone' => $phone,
                'order_id' => $orderData['id'],
                'type' => $type,
                'response' => $response->json()
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Error sending order notification', [
                'phone' => $phone,
                'order_id' => $orderData['id'] ?? null,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    protected function formatStoreOrderMessage(array $order): string
    {
        $items = collect($order['items'])->map(function ($item) {
            return "- {$item['menu']['name']} ({$item['quantity']}x) @ Rp " . number_format($item['price'], 0, ',', '.');
        })->join("\n");

        return "🛍️ *Pesanan Baru #" . $order['id'] . "*\n\n" .
            "👤 *Pelanggan:* " . $order['user']['name'] . "\n" .
            "📱 *Telepon:* " . $order['user']['phone'] . "\n\n" .
            "*Detail Pesanan:*\n" . $items . "\n\n" .
            "💰 *Total:* Rp " . number_format($order['total_price'], 0, ',', '.') . "\n" .
            "🏷️ *Diskon:* Rp " . number_format($order['discount_amount'], 0, ',', '.') . "\n" .
            "💳 *Total Bayar:* Rp " . number_format($order['final_price'], 0, ',', '.') . "\n\n" .
            ($order['notes'] ? "📝 *Catatan:* " . $order['notes'] . "\n\n" : "") .
            "Status pesanan akan diperbarui setelah pembayaran berhasil.";
    }

    protected function formatUserOrderMessage(array $order): string
    {
        $items = collect($order['items'])->map(function ($item) {
            return "- {$item['menu']['name']} ({$item['quantity']}x)";
        })->join("\n");

        return "🛍️ *Pesanan #" . $order['id'] . " Berhasil Dibuat*\n\n" .
            "Terima kasih telah memesan di *" . $order['store']['name'] . "*!\n\n" .
            "*Detail Pesanan:*\n" . $items . "\n\n" .
            "💰 *Total Bayar:* Rp " . number_format($order['final_price'], 0, ',', '.') . "\n\n" .
            "Silakan lakukan pembayaran untuk memproses pesanan Anda.\n" .
            "Link pembayaran akan dikirim segera.";
    }
} 