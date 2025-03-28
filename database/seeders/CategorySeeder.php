<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'id' => Str::uuid(),
                'name' => 'Makanan Utama',
                'description' => 'Menu makanan utama yang mengenyangkan',
                'is_active' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Minuman',
                'description' => 'Berbagai pilihan minuman segar',
                'is_active' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Cemilan',
                'description' => 'Makanan ringan dan snack',
                'is_active' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Dessert',
                'description' => 'Menu penutup dan makanan manis',
                'is_active' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Paket Hemat',
                'description' => 'Kombinasi menu dengan harga spesial',
                'is_active' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Masakan Nusantara',
                'description' => 'Berbagai masakan khas Indonesia',
                'is_active' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Masakan Oriental',
                'description' => 'Menu masakan Asia Timur dan Tenggara',
                'is_active' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Western Food',
                'description' => 'Hidangan ala barat',
                'is_active' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Seafood',
                'description' => 'Aneka hidangan laut',
                'is_active' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Vegetarian',
                'description' => 'Menu khusus vegetarian',
                'is_active' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Kopi dan Teh',
                'description' => 'Berbagai jenis kopi dan teh',
                'is_active' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Juice & Smoothies',
                'description' => 'Minuman segar dari buah dan sayur',
                'is_active' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Sarapan',
                'description' => 'Menu spesial untuk sarapan',
                'is_active' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Menu Sehat',
                'description' => 'Pilihan makanan sehat dan bergizi',
                'is_active' => true
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Menu Anak',
                'description' => 'Menu khusus untuk anak-anak',
                'is_active' => true
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
} 