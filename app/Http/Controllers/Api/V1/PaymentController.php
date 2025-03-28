<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\TripayService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $tripayService;

    public function __construct(TripayService $tripayService)
    {
        $this->tripayService = $tripayService;
    }

    public function getPaymentChannels()
    {
        try {
            $channels = $this->tripayService->getPaymentChannels();

            if (empty($channels)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada channel pembayaran yang tersedia'
                ], 404);
            }

            // Format channels sesuai dengan interface PaymentChannel di frontend
            $formattedChannels = array_map(function($channel) {
                return [
                    'code' => $channel['code'],
                    'name' => $channel['name'],
                    'icon_url' => $channel['icon_url'] ?? null,
                    'description' => $channel['description'] ?? null
                ];
            }, $channels);

            return response()->json([
                'success' => true,
                'data' => $formattedChannels
            ]);
        } catch (\Exception $e) {
            \Log::error('Payment channels error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan channel pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createPayment(Request $request, Order $order)
    {
        try {
            $request->validate([
                'payment_method' => 'required|string'
            ]);

            $payment = $this->tripayService->createTransaction($order, $request->payment_method);
            
            return response()->json([
                'success' => true,
                'message' => 'Payment URL berhasil dibuat',
                'data' => [
                    'payment' => $payment
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function handleCallback(Request $request)
    {
        try {
            $payment = $this->tripayService->verifyCallback($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Callback berhasil diproses',
                'data' => [
                    'payment' => $payment
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses callback: ' . $e->getMessage()
            ], 500);
        }
    }
} 