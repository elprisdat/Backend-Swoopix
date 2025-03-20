<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderItemController extends Controller
{
    public function update(Request $request, $orderId, $itemId)
    {
        try {
            $order = Order::findOrFail($orderId);
            $item = $order->items()->findOrFail($itemId);

            // Validate request
            $validator = Validator::make($request->all(), [
                'quantity' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if order can be modified
            if ($order->status !== 'pending' || $order->payment_status !== 'unpaid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak dapat diubah'
                ], 400);
            }

            // Update item quantity and subtotal
            $item->quantity = $request->quantity;
            $item->subtotal = $item->price * $request->quantity;
            $item->save();

            // Recalculate order totals
            $order->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil diupdate',
                'data' => [
                    'item' => $item,
                    'order' => $order
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($orderId, $itemId)
    {
        try {
            $order = Order::findOrFail($orderId);
            $item = $order->items()->findOrFail($itemId);

            // Check if order can be modified
            if ($order->status !== 'pending' || $order->payment_status !== 'unpaid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak dapat diubah'
                ], 400);
            }

            // Check if this is the last item
            if ($order->items()->count() <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus item terakhir'
                ], 400);
            }

            // Delete item
            $item->delete();

            // Recalculate order totals
            $order->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus',
                'data' => [
                    'order' => $order
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request, $orderId)
    {
        try {
            $order = Order::findOrFail($orderId);

            // Validate request
            $validator = Validator::make($request->all(), [
                'menu_id' => 'required|exists:menus,id',
                'quantity' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if order can be modified
            if ($order->status !== 'pending' || $order->payment_status !== 'unpaid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak dapat diubah'
                ], 400);
            }

            // Check if menu is from the same store
            $menu = Menu::findOrFail($request->menu_id);
            if ($menu->store_id !== $order->store_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu harus dari toko yang sama'
                ], 400);
            }

            // Create new item
            $item = $order->items()->create([
                'menu_id' => $request->menu_id,
                'quantity' => $request->quantity,
                'price' => $menu->price,
                'subtotal' => $menu->price * $request->quantity
            ]);

            // Recalculate order totals
            $order->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil ditambahkan',
                'data' => [
                    'item' => $item,
                    'order' => $order
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan item: ' . $e->getMessage()
            ], 500);
        }
    }
}