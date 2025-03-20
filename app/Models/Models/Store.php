<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'opening_hour',
        'closing_hour',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
        'opening_hour' => 'datetime:H:i',
        'closing_hour' => 'datetime:H:i',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeNearby($query, $latitude, $longitude, $radius = 5)
    {
        return $query->selectRaw('*, 
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
            cos(radians(longitude) - radians(?)) + 
            sin(radians(?)) * sin(radians(latitude)))) AS distance', 
            [$latitude, $longitude, $latitude])
            ->having('distance', '<=', $radius)
            ->orderBy('distance');
    }

    public function isOpen(): bool
    {
        $now = now();
        $openingTime = \Carbon\Carbon::createFromTimeString($this->opening_hour);
        $closingTime = \Carbon\Carbon::createFromTimeString($this->closing_hour);
        
        return $now->between($openingTime, $closingTime);
    }
}
