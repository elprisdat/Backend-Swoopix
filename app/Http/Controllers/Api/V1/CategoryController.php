<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $result = $this->categoryService->getAllCategories();
        return response()->json($result);
    }

    public function active()
    {
        $result = $this->categoryService->getActiveCategories();
        return response()->json($result);
    }

    public function show($id)
    {
        $result = $this->categoryService->getCategoryWithMenus($id);
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->categoryService->createCategory($request->all());
        return response()->json($result, $result['success'] ? 201 : 400);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->categoryService->updateCategory($id, $request->all());
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function destroy($id)
    {
        $result = $this->categoryService->deleteCategory($id);
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function toggleActive($id)
    {
        $result = $this->categoryService->toggleActive($id);
        return response()->json($result, $result['success'] ? 200 : 404);
    }
} 