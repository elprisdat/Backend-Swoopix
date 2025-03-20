<?php

namespace App\Repositories;

use App\Models\Models\Order;

class OrderRepository extends BaseRepository
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function getOrdersByUser($userId)
    {
        return $this->model->with(['store', 'items.menu', 'payment'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getOrdersByStore($storeId)
    {
        return $this->model->with(['user', 'items.menu', 'payment'])
            ->where('store_id', $storeId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getOrdersByStatus($status)
    {
        return $this->model->with(['store', 'user', 'items.menu', 'payment'])
            ->byStatus($status)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function updateStatus($id, $status): bool
    {
        $order = $this->find($id);
        if (!$order) {
            return false;
        }

        return $order->update([
            'status' => $status,
            $status.'_at' => now()
        ]);
    }

    public function updatePaymentStatus($id, $status, $paymentData = []): bool
    {
        $order = $this->find($id);
        if (!$order) {
            return false;
        }

        $data = array_merge([
            'payment_status' => $status,
        ], $paymentData);

        if ($status === 'paid') {
            $data['paid_at'] = now();
        }

        return $order->update($data);
    }

    public function getOrderDetail($id)
    {
        return $this->model->with(['store', 'user', 'items.menu', 'payment', 'voucher'])
            ->find($id);
    }
} 