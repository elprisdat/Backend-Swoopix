<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\WeatherService;
use App\Models\Menu;

class MenuController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function index()
    {
        $result = $this->menuService->getAllMenus();
        return response()->json($result);
    }

    public function available()
    {
        $result = $this->menuService->getAvailableMenus();
        return response()->json($result);
    }

    public function byCategory($categoryId)
    {
        $result = $this->menuService->getMenusByCategory($categoryId);
        return response()->json($result);
    }

    public function availableByCategory($categoryId)
    {
        $result = $this->menuService->getAvailableMenusByCategory($categoryId);
        return response()->json($result);
    }

    public function show($id)
    {
        $result = $this->menuService->getMenuDetail($id);
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_available' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->menuService->createMenu($request->all());
        return response()->json($result, $result['success'] ? 201 : 400);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_available' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->menuService->updateMenu($id, $request->all());
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function destroy($id)
    {
        $result = $this->menuService->deleteMenu($id);
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function toggleAvailable($id)
    {
        $result = $this->menuService->toggleAvailable($id);
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->menuService->searchMenus($request->query);
        return response()->json($result);
    }

    public function priceRange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'min' => 'nullable|numeric|min:0',
            'max' => 'nullable|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->menuService->getMenusByPriceRange(
            $request->min,
            $request->max
        );
        return response()->json($result);
    }

    public function weatherRecommendations(Request $request, WeatherService $weatherService)
    {
        try {
            $request->validate([
                'lat' => 'required|numeric',
                'lon' => 'required|numeric'
            ]);

            // Get current weather
            $weather = $weatherService->getCurrentWeather($request->lat, $request->lon);
            if (!$weather['success']) {
                return response()->json($weather, 400);
            }

            // Get menu recommendations
            $recommendations = $weatherService->getMenuRecommendations($weather);

            // Get menus from recommended categories
            $menus = Menu::whereIn('category_id', $recommendations['categories'])
                ->where('is_available', true)
                ->with('category')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'weather' => $weather['data'],
                    'conditions' => $recommendations['conditions'],
                    'menus' => $menus
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan rekomendasi: ' . $e->getMessage()
            ], 500);
        }
    }
} 