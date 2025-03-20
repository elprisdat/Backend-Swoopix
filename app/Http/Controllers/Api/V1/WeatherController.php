<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function getCurrentWeather(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        $weather = $this->weatherService->getCurrentWeather(
            $request->latitude,
            $request->longitude
        );

        return response()->json([
            'success' => true,
            'data' => $weather
        ]);
    }

    public function getWeatherBasedRecommendations(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:0|max:50',
            'limit' => 'nullable|integer|min:1|max:50'
        ]);

        $weather = $this->weatherService->getCurrentWeather(
            $request->latitude,
            $request->longitude
        );

        if (!$weather['success']) {
            return response()->json($weather, 400);
        }

        $recommendations = $this->weatherService->getMenuRecommendations($weather);

        return response()->json([
            'success' => true,
            'data' => [
                'weather' => $weather['data'],
                'recommendations' => $recommendations
            ]
        ]);
    }
} 