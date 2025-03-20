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
            return response()->json([
                'success' => true,
                'data' => [
                    'payment_channels' => $channels
                ]
            ]);
        } catch (\Exception $e) {
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