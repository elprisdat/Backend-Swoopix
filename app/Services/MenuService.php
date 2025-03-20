<?php

namespace App\Services;

use App\Repositories\MenuRepository;
use Illuminate\Support\Facades\Storage;

class MenuService extends BaseService
{
    protected $menuRepository;

    public function __construct(MenuRepository $repository)
    {
        parent::__construct($repository);
        $this->menuRepository = $repository;
    }

    public function getAllMenus(): array
    {
        return [
            'success' => true,
            'data' => [
                'menus' => $this->menuRepository->all()
            ]
        ];
    }

    public function getAvailableMenus(): array
    {
        return [
            'success' => true,
            'data' => [
                'menus' => $this->menuRepository->getAvailable()
            ]
        ];
    }

    public function getMenusByCategory($categoryId): array
    {
        return [
            'success' => true,
            'data' => [
                'menus' => $this->menuRepository->getByCategoryId($categoryId)
            ]
        ];
    }

    public function getAvailableMenusByCategory($categoryId): array
    {
        return [
            'success' => true,
            'data' => [
                'menus' => $this->menuRepository->getAvailableByCategoryId($categoryId)
            ]
        ];
    }

    public function getMenuDetail($id): array
    {
        $menu = $this->menuRepository->findWithCategory($id);

        if (!$menu) {
            return [
                'success' => false,
                'message' => 'Menu tidak ditemukan'
            ];
        }

        return [
            'success' => true,
            'data' => [
                'menu' => $menu
            ]
        ];
    }

    public function createMenu(array $data): array
    {
        // Handle image upload if exists
        if (isset($data['image']) && $data['image']) {
            $imagePath = $data['image']->store('menus', 'public');
            $data['image'] = $imagePath;
        }

        $menu = $this->menuRepository->create($data);

        return [
            'success' => true,
            'message' => 'Menu berhasil dibuat',
            'data' => [
                'menu' => $menu
            ]
        ];
    }

    public function updateMenu($id, array $data): array
    {
        $menu = $this->menuRepository->find($id);

        if (!$menu) {
            return [
                'success' => false,
                'message' => 'Menu tidak ditemukan'
            ];
        }

        // Handle image upload if exists
        if (isset($data['image']) && $data['image']) {
            // Delete old image
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            
            $imagePath = $data['image']->store('menus', 'public');
            $data['image'] = $imagePath;
        }

        if (!$this->menuRepository->update($id, $data)) {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate menu'
            ];
        }

        return [
            'success' => true,
            'message' => 'Menu berhasil diupdate',
            'data' => [
                'menu' => $this->menuRepository->find($id)
            ]
        ];
    }

    public function deleteMenu($id): array
    {
        $menu = $this->menuRepository->find($id);

        if (!$menu) {
            return [
                'success' => false,
                'message' => 'Menu tidak ditemukan'
            ];
        }

        // Delete image if exists
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        if (!$this->menuRepository->delete($id)) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus menu'
            ];
        }

        return [
            'success' => true,
            'message' => 'Menu berhasil dihapus'
        ];
    }

    public function toggleAvailable($id): array
    {
        if (!$this->menuRepository->toggleAvailable($id)) {
            return [
                'success' => false,
                'message' => 'Gagal mengubah status menu'
            ];
        }

        return [
            'success' => true,
            'message' => 'Status menu berhasil diubah',
            'data' => [
                'menu' => $this->menuRepository->find($id)
            ]
        ];
    }

    public function searchMenus($query): array
    {
        return [
            'success' => true,
            'data' => [
                'menus' => $this->menuRepository->searchByName($query)
            ]
        ];
    }

    public function getMenusByPriceRange($min = null, $max = null): array
    {
        return [
            'success' => true,
            'data' => [
                'menus' => $this->menuRepository->getByPriceRange($min, $max)
            ]
        ];
    }
} 