<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Storage;

class CategoryService extends BaseService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $repository)
    {
        parent::__construct($repository);
        $this->categoryRepository = $repository;
    }

    public function getAllCategories(): array
    {
        return [
            'success' => true,
            'data' => [
                'categories' => $this->categoryRepository->all()
            ]
        ];
    }

    public function getActiveCategories(): array
    {
        return [
            'success' => true,
            'data' => [
                'categories' => $this->categoryRepository->getActive()
            ]
        ];
    }

    public function getCategoryWithMenus($id): array
    {
        $category = $this->categoryRepository->findWithMenus($id);

        if (!$category) {
            return [
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ];
        }

        return [
            'success' => true,
            'data' => [
                'category' => $category
            ]
        ];
    }

    public function createCategory(array $data): array
    {
        // Handle image upload if exists
        if (isset($data['image']) && $data['image']) {
            $imagePath = $data['image']->store('categories', 'public');
            $data['image'] = $imagePath;
        }

        $category = $this->categoryRepository->create($data);

        return [
            'success' => true,
            'message' => 'Kategori berhasil dibuat',
            'data' => [
                'category' => $category
            ]
        ];
    }

    public function updateCategory($id, array $data): array
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return [
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ];
        }

        // Handle image upload if exists
        if (isset($data['image']) && $data['image']) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $imagePath = $data['image']->store('categories', 'public');
            $data['image'] = $imagePath;
        }

        if (!$this->categoryRepository->update($id, $data)) {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate kategori'
            ];
        }

        return [
            'success' => true,
            'message' => 'Kategori berhasil diupdate',
            'data' => [
                'category' => $this->categoryRepository->find($id)
            ]
        ];
    }

    public function deleteCategory($id): array
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return [
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ];
        }

        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        if (!$this->categoryRepository->delete($id)) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus kategori'
            ];
        }

        return [
            'success' => true,
            'message' => 'Kategori berhasil dihapus'
        ];
    }

    public function toggleActive($id): array
    {
        if (!$this->categoryRepository->toggleActive($id)) {
            return [
                'success' => false,
                'message' => 'Gagal mengubah status kategori'
            ];
        }

        return [
            'success' => true,
            'message' => 'Status kategori berhasil diubah',
            'data' => [
                'category' => $this->categoryRepository->find($id)
            ]
        ];
    }
} 