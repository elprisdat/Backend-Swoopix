<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\WeatherService;
use App\Services\StoreService;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    protected $weatherService;
    protected $storeService;

    public function __construct(WeatherService $weatherService, StoreService $storeService)
    {
        $this->weatherService = $weatherService;
        $this->storeService = $storeService;
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric'
        ]);

        // Get weather data
        $weather = $this->weatherService->getCurrentWeather(
            $request->latitude,
            $request->longitude
        );

        if (!$weather['success']) {
            return response()->json($weather, 400);
        }

        // Get nearby stores with recommended menus
        $nearbyStores = $this->storeService->getNearbyStores(
            $request->latitude,
            $request->longitude,
            5 // default radius 5km
        );

        // Get weather-based menu recommendations
        $recommendations = $this->weatherService->getMenuRecommendations($weather);

        return response()->json([
            'success' => true,
            'data' => [
                'weather' => $weather['data'],
                'nearby_stores' => $nearbyStores,
                'weather_based_recommendations' => $recommendations
            ]
        ]);
    }
} 