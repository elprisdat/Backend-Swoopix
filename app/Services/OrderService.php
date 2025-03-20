<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\OrderItemRepository;
use App\Repositories\MenuRepository;
use App\Repositories\VoucherRepository;
use Illuminate\Support\Facades\DB;
use App\Services\TripayService;
use App\Services\FontteService;

class OrderService extends BaseService
{
    protected $orderRepository;
    protected $orderItemRepository;
    protected $menuRepository;
    protected $voucherRepository;
    protected $tripayService;
    protected $fontteService;

    public function __construct(
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository,
        MenuRepository $menuRepository,
        VoucherRepository $voucherRepository,
        TripayService $tripayService,
        FontteService $fontteService
    ) {
        parent::__construct($orderRepository);
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->menuRepository = $menuRepository;
        $this->voucherRepository = $voucherRepository;
        $this->tripayService = $tripayService;
        $this->fontteService = $fontteService;
    }

    public function getOrdersByUser($userId): array
    {
        return [
            'success' => true,
            'data' => [
                'orders' => $this->orderRepository->getOrdersByUser($userId)
            ]
        ];
    }

    public function getOrdersByStore($storeId): array
    {
        return [
            'success' => true,
            'data' => [
                'orders' => $this->orderRepository->getOrdersByStore($storeId)
            ]
        ];
    }

    public function getOrdersByStatus($status): array
    {
        return [
            'success' => true,
            'data' => [
                'orders' => $this->orderRepository->getOrdersByStatus($status)
            ]
        ];
    }

    public function getOrderDetail($id): array
    {
        $order = $this->orderRepository->getOrderDetail($id);

        if (!$order) {
            return [
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ];
        }

        return [
            'success' => true,
            'data' => [
                'order' => $order
            ]
        ];
    }

    public function createOrder(array $data): array
    {
        try {
            DB::beginTransaction();

            // Validate items stock and calculate total
            $totalPrice = 0;
            $items = [];
            foreach ($data['items'] as $item) {
                $menu = $this->menuRepository->find($item['menu_id']);
                if (!$menu || !$menu->is_available) {
                    throw new \Exception('Menu tidak tersedia: ' . $menu->name);
                }

                $subtotal = $menu->price * $item['quantity'];
                $totalPrice += $subtotal;

                $items[] = [
                    'menu_id' => $menu->id,
                    'quantity' => $item['quantity'],
                    'price' => $menu->price,
                    'subtotal' => $subtotal
                ];
            }

            // Apply voucher if exists
            $discountAmount = 0;
            if (isset($data['voucher_id'])) {
                $voucher = $this->voucherRepository->find($data['voucher_id']);
                if ($voucher && $voucher->isValid() && $totalPrice >= $voucher->min_order) {
                    $discountAmount = $voucher->calculateDiscount($totalPrice);
                }
            }

            // Create order
            $order = $this->orderRepository->create([
                'user_id' => $data['user_id'],
                'store_id' => $data['store_id'],
                'voucher_id' => $data['voucher_id'] ?? null,
                'total_price' => $totalPrice,
                'discount_amount' => $discountAmount ?? 0,
                'final_price' => $totalPrice - $discountAmount,
                'notes' => $data['notes'] ?? null,
                'status' => 'pending',
                'payment_status' => 'pending'
            ]);

            // Create order items
            foreach ($items as $item) {
                $this->orderItemRepository->create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            // Create payment using Tripay
            $payment = $this->tripayService->createTransaction($order, $data['payment_method']);

            // Send notifications
            $store = $order->store;
            $user = $order->user;

            // Send notification to store
            $this->fontteService->sendOrderNotification($store->phone, $order->toArray(), 'store');

            // Send notification to user
            $this->fontteService->sendOrderNotification($user->phone, $order->toArray(), 'user');

            DB::commit();

            return [
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'data' => [
                    'order' => $order->load(['items.menu', 'store', 'user']),
                    'payment' => $payment
                ]
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Gagal membuat pesanan: ' . $e->getMessage()
            ];
        }
    }

    public function updateOrderStatus($id, $status): array
    {
        if (!$this->orderRepository->updateStatus($id, $status)) {
            return [
                'success' => false,
                'message' => 'Gagal mengubah status pesanan'
            ];
        }

        return [
            'success' => true,
            'message' => 'Status pesanan berhasil diubah',
            'data' => [
                'order' => $this->orderRepository->getOrderDetail($id)
            ]
        ];
    }

    public function updatePaymentStatus($id, $status, $paymentData = []): array
    {
        if (!$this->orderRepository->updatePaymentStatus($id, $status, $paymentData)) {
            return [
                'success' => false,
                'message' => 'Gagal mengubah status pembayaran'
            ];
        }

        return [
            'success' => true,
            'message' => 'Status pembayaran berhasil diubah',
            'data' => [
                'order' => $this->orderRepository->getOrderDetail($id)
            ]
        ];
    }

    public function cancelOrder($id): array
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            return [
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ];
        }

        if ($order->status !== 'pending' || $order->payment_status === 'paid') {
            return [
                'success' => false,
                'message' => 'Pesanan tidak dapat dibatalkan'
            ];
        }

        if (!$this->orderRepository->updateStatus($id, 'cancelled')) {
            return [
                'success' => false,
                'message' => 'Gagal membatalkan pesanan'
            ];
        }

        return [
            'success' => true,
            'message' => 'Pesanan berhasil dibatalkan',
            'data' => [
                'order' => $this->orderRepository->getOrderDetail($id)
            ]
        ];
    }
} 