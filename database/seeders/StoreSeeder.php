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
                'address' => 'Jl. Raya Darmo No. 123, Surabaya',
                'phone' => '081234567890',
                'email' => 'barokah@example.com',
                'description' => 'Warung makan tradisional dengan masakan rumahan',
                'open_time' => '08:00:00',
                'close_time' => '21:00:00',
                'is_open' => true,
                'latitude' => -7.2908,
                'longitude' => 112.7353
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Cafe Kekinian',
                'address' => 'Jl. Tunjungan No. 45, Surabaya',
                'phone' => '087654321098',
                'email' => 'cafe@example.com',
                'description' => 'Cafe modern dengan suasana nyaman',
                'open_time' => '10:00:00',
                'close_time' => '22:00:00',
                'is_open' => true,
                'latitude' => -7.2563,
                'longitude' => 112.7379
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Restoran Seafood Segar',
                'address' => 'Jl. Kenjeran No. 67, Surabaya',
                'phone' => '089876543210',
                'email' => 'seafood@example.com',
                'description' => 'Restoran seafood dengan bahan laut segar',
                'open_time' => '11:00:00',
                'close_time' => '23:00:00',
                'is_open' => true,
                'latitude' => -7.2324,
                'longitude' => 112.7891
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Sushi Master',
                'address' => 'Pakuwon Mall, Jl. Puncak Indah Lontar No. 2, Surabaya',
                'phone' => '081122334455',
                'email' => 'sushi@example.com',
                'description' => 'Restoran Jepang autentik dengan chef berpengalaman',
                'open_time' => '11:30:00',
                'close_time' => '22:30:00',
                'is_open' => true,
                'latitude' => -7.2821,
                'longitude' => 112.6768
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Padang Sederhana',
                'address' => 'Jl. Basuki Rahmat No. 12, Surabaya',
                'phone' => '082233445566',
                'email' => 'padang@example.com',
                'description' => 'Rumah makan Padang dengan rasa otentik',
                'open_time' => '07:00:00',
                'close_time' => '21:00:00',
                'is_open' => true,
                'latitude' => -7.2651,
                'longitude' => 112.7481
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Pizza Express',
                'address' => 'Tunjungan Plaza, Jl. Jend. Basuki Rachmat No.8-12, Surabaya',
                'phone' => '083344556677',
                'email' => 'pizza@example.com',
                'description' => 'Pizza Italia dengan bahan premium',
                'open_time' => '10:00:00',
                'close_time' => '23:00:00',
                'is_open' => true,
                'latitude' => -7.2570,
                'longitude' => 112.7379
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Healthy Bowl',
                'address' => 'Ciputra World, Jl. Mayjen Sungkono No.89, Surabaya',
                'phone' => '084455667788',
                'email' => 'healthy@example.com',
                'description' => 'Restoran sehat dengan menu organik',
                'open_time' => '07:00:00',
                'close_time' => '20:00:00',
                'is_open' => true,
                'latitude' => -7.2934,
                'longitude' => 112.7149
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Korean BBQ House',
                'address' => 'Galaxy Mall, Jl. Dharmahusada Indah Timur No.37, Surabaya',
                'phone' => '085566778899',
                'email' => 'kbbq@example.com',
                'description' => 'Restoran Korea dengan konsep all you can eat',
                'open_time' => '11:00:00',
                'close_time' => '23:00:00',
                'is_open' => true,
                'latitude' => -7.2747,
                'longitude' => 112.7824
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Dimsum Paradise',
                'address' => 'Grand City Mall, Jl. Walikota Mustajab No.1, Surabaya',
                'phone' => '086677889900',
                'email' => 'dimsum@example.com',
                'description' => 'Restoran dimsum dan chinese food',
                'open_time' => '08:00:00',
                'close_time' => '22:00:00',
                'is_open' => true,
                'latitude' => -7.2567,
                'longitude' => 112.7501
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Warung Tegal',
                'address' => 'Jl. Pahlawan No. 11, Surabaya',
                'phone' => '087788990011',
                'email' => 'warteg@example.com',
                'description' => 'Warung Tegal dengan masakan rumahan',
                'open_time' => '06:00:00',
                'close_time' => '22:00:00',
                'is_open' => true,
                'latitude' => -7.2458,
                'longitude' => 112.7378
            ]
        ];

        foreach ($stores as $store) {
            Store::create($store);
        }
    }
}