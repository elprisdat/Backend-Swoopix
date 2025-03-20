<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Store extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'id',
        'name',
        'description',
        'address',
        'phone',
        'email',
        'logo',
        'latitude',
        'longitude',
        'open_time',
        'close_time',
        'is_open'
    ];

    protected $casts = [
        'is_open' => 'boolean',
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6',
        'open_time' => 'datetime:H:i',
        'close_time' => 'datetime:H:i'
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
