<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class WeatherMenuPreferenceSeeder extends Seeder
{
    public function run(): void
    {
        $menus = Menu::all();
        $weatherConditions = ['rain', 'sunny', 'cloudy', 'hot', 'cold'];

        $preferences = [
            'rain' => [
                'Sop Buntut' => [90, 'Cocok untuk menghangatkan badan saat hujan'],
                'Mie Goreng Seafood' => [85, 'Hidangan hangat yang nikmat saat hujan'],
                'Green Tea Latte' => [80, 'Minuman hangat yang menenangkan'],
                'Dimsum Ayam' => [75, 'Camilan hangat yang pas untuk cuaca hujan'],
            ],
            'sunny' => [
                'Es Jeruk' => [95, 'Minuman segar untuk cuaca panas'],
                'Smoothie Mangga' => [90, 'Minuman dingin yang menyegarkan'],
                'Es Teler' => [85, 'Dessert dingin yang cocok untuk cuaca panas'],
                'Healthy Bowl' => [80, 'Menu ringan dan segar untuk cuaca cerah'],
            ],
            'cloudy' => [
                'Nasi Goreng Spesial' => [85, 'Menu klasik yang cocok untuk segala cuaca'],
                'Roti Bakar' => [80, 'Camilan hangat yang pas untuk cuaca mendung'],
                'Es Kopi Susu' => [75, 'Minuman yang memberikan energi di cuaca mendung'],
            ],
            'hot' => [
                'Es Teh Manis' => [95, 'Minuman dingin yang menyegarkan saat panas'],
                'Es Krim' => [90, 'Dessert dingin untuk menyejukkan'],
                'Jus Alpukat' => [85, 'Minuman dingin yang mengenyangkan'],
                'Es Campur' => [85, 'Dessert dingin dengan berbagai topping segar'],
            ],
            'cold' => [
                'Sate Ayam' => [90, 'Hidangan hangat dengan bumbu yang menghangatkan'],
                'Rendang Sapi' => [85, 'Masakan berbumbu yang menghangatkan badan'],
                'Green Tea Latte' => [80, 'Minuman hangat yang menenangkan'],
                'Pisang Bakar' => [75, 'Dessert hangat yang nikmat'],
            ]
        ];

        foreach ($preferences as $weather => $menuPreferences) {
            foreach ($menuPreferences as $menuName => $details) {
                $menu = $menus->where('name', $menuName)->first();
                if ($menu) {
                    DB::table('weather_menu_preferences')->insert([
                        'id' => Str::uuid(),
                        'weather_condition' => $weather,
                        'menu_id' => $menu->id,
                        'preference_score' => $details[0],
                        'recommendation_reason' => $details[1],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }
    }
} 