<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $store = Store::first();
        $categories = Category::all();

        // Makanan Utama
        $mainDishes = [
            [
                'id' => Str::uuid(),
                'name' => 'Nasi Goreng Spesial',
                'description' => 'Nasi goreng dengan telur, ayam, dan sayuran',
                'price' => 25000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Mie Goreng Seafood',
                'description' => 'Mie goreng dengan udang, cumi, dan sayuran',
                'price' => 30000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Ayam Bakar',
                'description' => 'Ayam bakar bumbu rempah khas',
                'price' => 35000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Sate Ayam',
                'description' => 'Sate ayam dengan bumbu kacang',
                'price' => 30000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Rendang Sapi',
                'description' => 'Rendang sapi autentik Padang',
                'price' => 45000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Ikan Bakar',
                'description' => 'Ikan kembung bakar dengan sambal',
                'price' => 40000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Sop Buntut',
                'description' => 'Sop buntut sapi dengan kuah bening',
                'price' => 50000,
                'is_available' => true
            ]
        ];

        // Minuman
        $drinks = [
            [
                'id' => Str::uuid(),
                'name' => 'Es Teh Manis',
                'description' => 'Teh manis dingin segar',
                'price' => 5000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Jus Alpukat',
                'description' => 'Jus alpukat segar dengan susu',
                'price' => 15000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Lemon Tea',
                'description' => 'Teh lemon dingin',
                'price' => 10000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Es Kopi Susu',
                'description' => 'Kopi susu dengan gula aren',
                'price' => 18000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Smoothie Mangga',
                'description' => 'Smoothie mangga dengan yogurt',
                'price' => 20000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Green Tea Latte',
                'description' => 'Green tea dengan susu',
                'price' => 22000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Es Jeruk',
                'description' => 'Jeruk peras segar',
                'price' => 8000,
                'is_available' => true
            ]
        ];

        // Cemilan
        $snacks = [
            [
                'id' => Str::uuid(),
                'name' => 'Kentang Goreng',
                'description' => 'Kentang goreng crispy',
                'price' => 15000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Pisang Goreng',
                'description' => 'Pisang goreng crispy',
                'price' => 10000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Dimsum Ayam',
                'description' => 'Dimsum ayam kukus',
                'price' => 18000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Roti Bakar',
                'description' => 'Roti bakar dengan berbagai topping',
                'price' => 15000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Cireng',
                'description' => 'Cireng dengan bumbu rujak',
                'price' => 12000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Tahu Crispy',
                'description' => 'Tahu goreng crispy dengan sambal',
                'price' => 10000,
                'is_available' => true
            ]
        ];

        // Dessert
        $desserts = [
            [
                'id' => Str::uuid(),
                'name' => 'Es Krim',
                'description' => 'Es krim vanilla dengan topping',
                'price' => 12000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Pudding Coklat',
                'description' => 'Pudding coklat lembut',
                'price' => 8000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Es Campur',
                'description' => 'Es campur dengan berbagai topping',
                'price' => 15000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Pisang Bakar',
                'description' => 'Pisang bakar dengan keju dan coklat',
                'price' => 18000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Martabak Manis',
                'description' => 'Martabak manis dengan berbagai topping',
                'price' => 25000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Es Teler',
                'description' => 'Es teler dengan kelapa muda',
                'price' => 15000,
                'is_available' => true
            ]
        ];

        // Paket Hemat
        $packages = [
            [
                'id' => Str::uuid(),
                'name' => 'Paket Nasi Goreng',
                'description' => 'Nasi goreng + es teh manis',
                'price' => 28000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Paket Ayam Bakar',
                'description' => 'Ayam bakar + nasi + es teh manis',
                'price' => 38000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Paket Keluarga',
                'description' => 'Ayam bakar + nasi goreng + 4 es teh manis',
                'price' => 85000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Paket Mie Goreng',
                'description' => 'Mie goreng + es jeruk',
                'price' => 32000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Paket Hemat Sate',
                'description' => 'Sate ayam + nasi + es teh manis',
                'price' => 35000,
                'is_available' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Paket Berdua',
                'description' => '2 porsi nasi goreng + 2 es teh manis',
                'price' => 45000,
                'is_available' => true
            ]
        ];

        $allMenus = [
            0 => $mainDishes,
            1 => $drinks,
            2 => $snacks,
            3 => $desserts,
            4 => $packages
        ];

        foreach ($allMenus as $categoryIndex => $menus) {
            foreach ($menus as $menu) {
                Menu::create(array_merge($menu, [
                    'category_id' => $categories[$categoryIndex]->id,
                    'store_id' => $store->id
                ]));
            }
        }
    }
} 