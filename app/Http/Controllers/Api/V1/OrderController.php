<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use App\Services\TripayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $tripayService;

    public function __construct(TripayService $tripayService)
    {
        $this->tripayService = $tripayService;
    }

    public function store(Request $request)
    {
        try {
            // Validasi request
            $validated = $request->validate([
                'store_id' => 'required|string|exists:stores,id',
                'items' => 'required|array|min:1',
                'items.*.menu_id' => 'required|string|exists:menus,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.notes' => 'nullable|string',
                'payment_method' => 'required|string',
                'voucher_id' => 'nullable|string|exists:vouchers,id',
                'notes' => 'nullable|string'
            ]);

            Log::info('Memulai pembuatan pesanan', $validated);

            return DB::transaction(function () use ($validated, $request) {
                // Hitung total harga
                $totalAmount = 0;
                $orderItems = [];

                foreach ($validated['items'] as $item) {
                    $menu = Menu::where('id', $item['menu_id'])
                        ->where('is_available', true)
                        ->first();

                    if (!$menu) {
                        throw new \Exception("Menu dengan ID {$item['menu_id']} tidak tersedia");
                    }

                    $itemTotal = $menu->price * $item['quantity'];
                    $totalAmount += $itemTotal;

                    $orderItems[] = [
                        'menu_id' => $item['menu_id'],
                        'quantity' => $item['quantity'],
                        'price' => $menu->price,
                        'subtotal' => $itemTotal,
                        'notes' => $item['notes'] ?? null
                    ];
                }

                // Buat order
                $order = Order::create([
                    'user_id' => $request->user()->id,
                    'store_id' => $validated['store_id'],
                    'total_price' => $totalAmount,
                    'discount_amount' => 0, // Default 0 jika tidak ada voucher
                    'final_price' => $totalAmount, // Akan diupdate jika ada voucher
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                    'payment_method' => $validated['payment_method'],
                    'expired_at' => now()->addDay(), // Expired dalam 24 jam
                    'notes' => $validated['notes'] ?? null,
                    'voucher_id' => $validated['voucher_id'] ?? null,
                ]);

                // Simpan order items
                $order->items()->createMany($orderItems);

                // Buat payment menggunakan Tripay
                $payment = $this->tripayService->createTransaction($order, $validated['payment_method']);

                // Update payment details
                $order->update([
                    'payment_url' => $payment->payment_url ?? null,
                    'payment_token' => $payment->token ?? null
                ]);

                // Load relationships
                $order->load(['items.menu' => function($query) {
                    $query->select('id', 'name', 'description');
                }]);

                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dibuat',
                    'data' => [
                        'order' => [
                            'id' => $order->id,
                            'store_id' => $order->store_id,
                            'user_id' => $order->user_id,
                            'total_price' => $order->total_price,
                            'discount_amount' => $order->discount_amount,
                            'final_price' => $order->final_price,
                            'status' => $order->status,
                            'payment_status' => $order->payment_status,
                            'payment_method' => $order->payment_method,
                            'payment_url' => $order->payment_url,
                            'payment_token' => $order->payment_token,
                            'expired_at' => $order->expired_at,
                            'notes' => $order->notes,
                            'created_at' => $order->created_at,
                            'items' => $order->items->map(function($item) {
                                return [
                                    'id' => $item->id,
                                    'menu_id' => $item->menu_id,
                                    'quantity' => $item->quantity,
                                    'price' => $item->price,
                                    'subtotal' => $item->subtotal,
                                    'notes' => $item->notes,
                                    'menu' => [
                                        'name' => $item->menu->name,
                                        'description' => $item->menu->description
                                    ]
                                ];
                            })
                        ],
                        'payment' => [
                            'payment_method' => $payment->payment_method,
                            'payment_url' => $payment->payment_url,
                            'status' => $payment->status,
                            'reference' => $payment->reference,
                            'expired_time' => $payment->expired_time
                        ]
                    ]
                ], 201);
            });

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validasi gagal saat membuat pesanan', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Data pesanan tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error saat membuat pesanan', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            if (!$request->user()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Silakan login terlebih dahulu.'
                ], 401);
            }

            $orders = Order::with(['store', 'items.menu'])
                ->where('user_id', $request->user()->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Riwayat pesanan berhasil diambil',
                'data' => [
                    'orders' => $orders
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil riwayat pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Other methods...
} 