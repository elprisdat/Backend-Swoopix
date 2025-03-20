<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Store extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'description',
        'latitude',
        'longitude',
        'open_time',
        'close_time',
        'is_open'
    ];

    protected $casts = [
        'is_open' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float'
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

    // Mendapatkan toko yang buka
    public static function getOpenStores()
    {
        return self::where('is_open', true)
            ->with(['menus' => function($query) {
                $query->where('is_available', true);
            }])
            ->get();
    }

    // Mendapatkan detail toko dengan menu yang tersedia
    public function getStoreWithAvailableMenus()
    {
        return $this->load(['menus' => function($query) {
            $query->where('is_available', true);
        }]);
    }
}
