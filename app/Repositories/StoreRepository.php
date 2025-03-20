<?php

namespace App\Repositories;

use App\Models\Store;

class StoreRepository extends BaseRepository
{
    public function __construct(Store $model)
    {
        parent::__construct($model);
    }

    public function getOpenStores()
    {
        return $this->model->where('is_open', true)->get();
    }

    public function toggleOpen($id): bool
    {
        $store = $this->find($id);
        if (!$store) {
            return false;
        }

        return $store->update([
            'is_open' => !$store->is_open
        ]);
    }

    public function searchByName($query)
    {
        return $this->model->where('name', 'like', "%{$query}%")->get();
    }
} 