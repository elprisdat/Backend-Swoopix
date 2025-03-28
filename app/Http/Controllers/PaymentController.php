<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TripayService;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $tripayService;

    public function __construct(TripayService $tripayService)
    {
        $this->tripayService = $tripayService;
    }

    public function success(Request $request)
    {
        Log::info('Payment success callback received', $request->all());
        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil'
        ]);
    }

    public function failed(Request $request)
    {
        Log::info('Payment failed callback received', $request->all());
        return response()->json([
            'success' => false,
            'message' => 'Pembayaran gagal'
        ]);
    }

    public function callback(Request $request)
    {
        try {
            Log::info('Payment callback received', $request->all());

            // Validasi callback dari Tripay
            if (!$this->tripayService->validateCallback($request)) {
                throw new \Exception('Invalid callback signature');
            }

            $reference = $request->reference;
            $status = strtolower($request->status);

            // Update order status
            $order = Order::where('payment_token', $reference)->firstOrFail();
            
            switch ($status) {
                case 'paid':
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing',
                        'paid_at' => now()
                    ]);
                    break;
                case 'expired':
                    $order->update([
                        'payment_status' => 'expired',
                        'status' => 'cancelled'
                    ]);
                    break;
                case 'failed':
                    $order->update([
                        'payment_status' => 'failed',
                        'status' => 'cancelled'
                    ]);
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Callback processed successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing payment callback: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing callback: ' . $e->getMessage()
            ], 500);
        }
    }
} 