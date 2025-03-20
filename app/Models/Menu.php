<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Menu extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
        'image',
        'is_available',
        'category_id',
        'store_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopePriceRange($query, $min, $max)
    {
        return $query->when($min, function ($q) use ($min) {
            return $q->where('price', '>=', $min);
        })->when($max, function ($q) use ($max) {
            return $q->where('price', '<=', $max);
        });
    }
}
