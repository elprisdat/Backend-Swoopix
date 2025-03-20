<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'minimum_purchase',
        'max_usage',
        'used_count',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_purchase' => 'decimal:2',
        'max_usage' => 'integer',
        'used_count' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isValid(): bool
    {
        $now = now();
        return $this->is_active &&
            $now->between($this->start_date, $this->end_date) &&
            ($this->max_usage === 0 || $this->used_count < $this->max_usage);
    }

    public function calculateDiscount($amount): float
    {
        if ($amount < $this->minimum_purchase) {
            return 0;
        }

        if ($this->discount_type === 'fixed') {
            return $this->discount_value;
        }

        return ($amount * $this->discount_value) / 100;
    }

    public function incrementUsage(): self
    {
        $this->increment('used_count');
        return $this;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where(function ($q) {
                $q->where('max_usage', 0)
                    ->orWhere('used_count', '<', 'max_usage');
            });
    }
}
