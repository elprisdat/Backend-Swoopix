<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\StoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Store;

class StoreController extends Controller
{
    protected $storeService;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function index()
    {
        $result = $this->storeService->getAllStores();
        return response()->json($result);
    }

    public function open()
    {
        $result = $this->storeService->getOpenStores();
        return response()->json($result);
    }

    public function show($id)
    {
        $result = $this->storeService->getStoreDetail($id);
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
            'is_open' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->storeService->createStore($request->all());
        return response()->json($result, $result['success'] ? 201 : 400);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
            'is_open' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->storeService->updateStore($id, $request->all());
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function destroy($id)
    {
        $result = $this->storeService->deleteStore($id);
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function toggleOpen($id)
    {
        $result = $this->storeService->toggleOpen($id);
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

        $result = $this->storeService->searchStores($request->query);
        return response()->json($result);
    }

    public function nearby(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:0|max:50' // radius maksimal 50km
        ]);

        $stores = Store::findNearby(
            $request->latitude,
            $request->longitude,
            $request->radius ?? 5
        );

        return response()->json([
            'success' => true,
            'message' => 'Daftar toko terdekat berhasil didapatkan',
            'data' => [
                'stores' => $stores,
                'total' => $stores->count()
            ]
        ]);
    }
} 