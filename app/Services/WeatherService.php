<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $apiKey = config('services.openweather.api_key');
        if (!$apiKey) {
            throw new \RuntimeException('OpenWeather API key tidak ditemukan. Silakan tambahkan OPENWEATHER_API_KEY di file .env');
        }
        $this->apiKey = $apiKey;
        $this->baseUrl = 'https://api.openweathermap.org/data/2.5';
    }

    public function getCurrentWeather(float $lat, float $lon): array
    {
        $cacheKey = "weather_{$lat}_{$lon}";

        return Cache::remember($cacheKey, 1800, function () use ($lat, $lon) {
            $response = Http::get($this->baseUrl . '/weather', [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $this->apiKey,
                'units' => 'metric',
                'lang' => 'id'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'data' => [
                        'temp' => $data['main']['temp'],
                        'feels_like' => $data['main']['feels_like'],
                        'humidity' => $data['main']['humidity'],
                        'weather' => [
                            'id' => $data['weather'][0]['id'],
                            'main' => $data['weather'][0]['main'],
                            'description' => $data['weather'][0]['description']
                        ]
                    ]
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal mendapatkan data cuaca'
            ];
        });
    }

    public function getMenuRecommendations(array $weatherData): array
    {
        $temp = $weatherData['data']['temp'];
        $weather = $weatherData['data']['weather']['main'];

        $recommendations = [
            'categories' => [],
            'conditions' => []
        ];

        // Rekomendasi berdasarkan suhu
        if ($temp >= 30) {
            $recommendations['categories'][] = 1; // Minuman Dingin
            $recommendations['conditions'][] = 'Cuaca panas';
        } elseif ($temp <= 22) {
            $recommendations['categories'][] = 2; // Minuman Hangat
            $recommendations['conditions'][] = 'Cuaca dingin';
        }

        // Rekomendasi berdasarkan kondisi cuaca
        switch ($weather) {
            case 'Rain':
            case 'Drizzle':
                $recommendations['categories'][] = 3; // Makanan Hangat
                $recommendations['conditions'][] = 'Hujan';
                break;
            case 'Clear':
                $recommendations['categories'][] = 4; // Makanan Ringan
                $recommendations['conditions'][] = 'Cerah';
                break;
            case 'Clouds':
                $recommendations['categories'][] = 5; // Makanan Berat
                $recommendations['conditions'][] = 'Berawan';
                break;
        }

        return $recommendations;
    }
} 