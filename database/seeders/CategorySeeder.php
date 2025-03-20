<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Makanan Utama',
                'description' => 'Menu makanan utama yang mengenyangkan',
                'is_active' => true
            ],
            [
                'name' => 'Minuman',
                'description' => 'Berbagai pilihan minuman segar',
                'is_active' => true
            ],
            [
                'name' => 'Cemilan',
                'description' => 'Makanan ringan dan snack',
                'is_active' => true
            ],
            [
                'name' => 'Dessert',
                'description' => 'Menu penutup dan makanan manis',
                'is_active' => true
            ],
            [
                'name' => 'Paket Hemat',
                'description' => 'Kombinasi menu dengan harga spesial',
                'is_active' => true
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
} 