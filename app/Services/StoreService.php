<?php

namespace App\Services;

use App\Repositories\StoreRepository;
use Illuminate\Support\Facades\Storage;

class StoreService extends BaseService
{
    protected $storeRepository;

    public function __construct(StoreRepository $repository)
    {
        parent::__construct($repository);
        $this->storeRepository = $repository;
    }

    public function getAllStores(): array
    {
        return [
            'success' => true,
            'data' => [
                'stores' => $this->storeRepository->all()
            ]
        ];
    }

    public function getOpenStores(): array
    {
        return [
            'success' => true,
            'data' => [
                'stores' => $this->storeRepository->getOpenStores()
            ]
        ];
    }

    public function getStoreDetail($id): array
    {
        $store = $this->storeRepository->find($id);

        if (!$store) {
            return [
                'success' => false,
                'message' => 'Toko tidak ditemukan'
            ];
        }

        return [
            'success' => true,
            'data' => [
                'store' => $store
            ]
        ];
    }

    public function createStore(array $data): array
    {
        // Handle logo upload if exists
        if (isset($data['logo']) && $data['logo']) {
            $logoPath = $data['logo']->store('stores', 'public');
            $data['logo'] = $logoPath;
        }

        $store = $this->storeRepository->create($data);

        return [
            'success' => true,
            'message' => 'Toko berhasil dibuat',
            'data' => [
                'store' => $store
            ]
        ];
    }

    public function updateStore($id, array $data): array
    {
        $store = $this->storeRepository->find($id);

        if (!$store) {
            return [
                'success' => false,
                'message' => 'Toko tidak ditemukan'
            ];
        }

        // Handle logo upload if exists
        if (isset($data['logo']) && $data['logo']) {
            // Delete old logo
            if ($store->logo) {
                Storage::disk('public')->delete($store->logo);
            }
            
            $logoPath = $data['logo']->store('stores', 'public');
            $data['logo'] = $logoPath;
        }

        if (!$this->storeRepository->update($id, $data)) {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate toko'
            ];
        }

        return [
            'success' => true,
            'message' => 'Toko berhasil diupdate',
            'data' => [
                'store' => $this->storeRepository->find($id)
            ]
        ];
    }

    public function deleteStore($id): array
    {
        $store = $this->storeRepository->find($id);

        if (!$store) {
            return [
                'success' => false,
                'message' => 'Toko tidak ditemukan'
            ];
        }

        // Delete logo if exists
        if ($store->logo) {
            Storage::disk('public')->delete($store->logo);
        }

        if (!$this->storeRepository->delete($id)) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus toko'
            ];
        }

        return [
            'success' => true,
            'message' => 'Toko berhasil dihapus'
        ];
    }

    public function toggleOpen($id): array
    {
        if (!$this->storeRepository->toggleOpen($id)) {
            return [
                'success' => false,
                'message' => 'Gagal mengubah status toko'
            ];
        }

        return [
            'success' => true,
            'message' => 'Status toko berhasil diubah',
            'data' => [
                'store' => $this->storeRepository->find($id)
            ]
        ];
    }

    public function searchStores($query): array
    {
        return [
            'success' => true,
            'data' => [
                'stores' => $this->storeRepository->searchByName($query)
            ]
        ];
    }

    public function getNearbyStores($latitude, $longitude, $radius = 10): array
    {
        $stores = $this->storeRepository->getNearbyStores($latitude, $longitude, $radius);

        return [
            'success' => true,
            'message' => 'Daftar toko terdekat berhasil didapatkan',
            'data' => [
                'stores' => $stores,
                'total' => count($stores)
            ]
        ];
    }
} 