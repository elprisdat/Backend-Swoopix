<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'order_id',
        'reference_id',
        'payment_method',
        'amount',
        'payment_url',
        'status',
        'payment_details',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'json',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function markAsPaid(): self
    {
        $this->status = 'paid';
        $this->save();
        return $this;
    }

    public function markAsFailed(): self
    {
        $this->status = 'failed';
        $this->save();
        return $this;
    }

    public function markAsExpired(): self
    {
        $this->status = 'expired';
        $this->save();
        return $this;
    }
}
