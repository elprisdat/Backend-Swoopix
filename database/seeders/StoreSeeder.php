<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $stores = [
            [
                'id' => Str::uuid(),
                'name' => 'Warung Makan Barokah',
                'address' => 'Jl. Raya No. 123, Jakarta Selatan',
                'phone' => '081234567890',
                'email' => 'barokah@example.com',
                'description' => 'Warung makan tradisional dengan masakan rumahan',
                'open_time' => '08:00:00',
                'close_time' => '21:00:00',
                'is_open' => true,
                'latitude' => -6.2088,
                'longitude' => 106.8456
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Cafe Kekinian',
                'address' => 'Jl. Sudirman No. 45, Jakarta Pusat',
                'phone' => '087654321098',
                'email' => 'cafe@example.com',
                'description' => 'Cafe modern dengan suasana nyaman',
                'open_time' => '10:00:00',
                'close_time' => '22:00:00',
                'is_open' => true,
                'latitude' => -6.2156,
                'longitude' => 106.8223
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Restoran Seafood Segar',
                'address' => 'Jl. Pantai Indah No. 67, Jakarta Utara',
                'phone' => '089876543210',
                'email' => 'seafood@example.com',
                'description' => 'Restoran seafood dengan bahan laut segar',
                'open_time' => '11:00:00',
                'close_time' => '23:00:00',
                'is_open' => true,
                'latitude' => -6.1212,
                'longitude' => 106.8845
            ]
        ];

        foreach ($stores as $store) {
            Store::create($store);
        }
    }
}