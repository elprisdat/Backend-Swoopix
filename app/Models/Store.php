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

    // Mencari toko dalam radius tertentu (km)
    public static function findNearby($latitude, $longitude, $radius = 5)
    {
        // Menggunakan Haversine formula untuk menghitung jarak
        $haversine = "(
            6371 * acos(
                cos(radians($latitude)) 
                * cos(radians(latitude))
                * cos(radians(longitude) - radians($longitude))
                + sin(radians($latitude))
                * sin(radians(latitude))
            )
        )";

        return self::select('*')
            ->selectRaw("{$haversine} AS distance")
            ->whereRaw("{$haversine} < ?", [$radius])
            ->orderBy('distance')
            ->where('is_open', true)
            ->with(['menus' => function($query) {
                $query->where('is_available', true);
            }])
            ->get();
    }
}
