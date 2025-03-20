<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\FontteService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $fontteService;

    public function __construct(FontteService $fontteService)
    {
        $this->fontteService = $fontteService;
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'store_id' => 'required|string',
                'items' => 'required|array',
                'items.*.menu_id' => 'required|string',
                'items.*.quantity' => 'required|integer|min:1',
                'voucher_id' => 'nullable|string',
                'notes' => 'nullable|string'
            ]);

            // Create order logic here...
            $order = Order::create([
                'user_id' => $request->user()->id,
                'store_id' => $request->store_id,
                'voucher_id' => $request->voucher_id,
                'notes' => $request->notes
            ]);

            // Add order items...
            foreach ($request->items as $item) {
                $order->items()->create([
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);
            }

            // Calculate total, discount, etc...
            $order->calculateTotals();

            // Load relationships for notification
            $order->load(['store', 'items.menu', 'user']);

            // Send WhatsApp notification to store
            $this->fontteService->sendOrderNotification(
                $order->store->phone,
                $order->toArray(),
                'store'
            );

            // Send WhatsApp notification to user
            $this->fontteService->sendOrderNotification(
                $order->user->phone,
                $order->toArray(),
                'user'
            );

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'data' => [
                    'order' => $order
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Other methods...
} 