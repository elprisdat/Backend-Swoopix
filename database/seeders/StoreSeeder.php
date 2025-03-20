<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Models\Store;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $stores = [
            [
                'name' => 'Warung Makan Barokah',
                'address' => 'Jl. Raya No. 123, Jakarta Selatan',
                'phone' => '081234567890',
                'email' => 'barokah@example.com',
                'description' => 'Warung makan tradisional dengan masakan rumahan',
                'open_time' => '08:00:00',
                'close_time' => '21:00:00',
                'is_open' => true
            ],
            [
                'name' => 'Cafe Kekinian',
                'address' => 'Jl. Sudirman No. 45, Jakarta Pusat',
                'phone' => '087654321098',
                'email' => 'cafe@example.com',
                'description' => 'Cafe modern dengan suasana nyaman',
                'open_time' => '10:00:00',
                'close_time' => '22:00:00',
                'is_open' => true
            ],
            [
                'name' => 'Restoran Seafood Segar',
                'address' => 'Jl. Pantai Indah No. 67, Jakarta Utara',
                'phone' => '089876543210',
                'email' => 'seafood@example.com',
                'description' => 'Restoran seafood dengan bahan laut segar',
                'open_time' => '11:00:00',
                'close_time' => '23:00:00',
                'is_open' => true
            ]
        ];

        foreach ($stores as $store) {
            Store::create($store);
        }
    }
}