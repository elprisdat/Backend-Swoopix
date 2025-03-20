<?php

namespace App\Repositories;

use App\Models\Menu;

class MenuRepository extends BaseRepository
{
    public function __construct(Menu $model)
    {
        parent::__construct($model);
    }

    public function getAvailable()
    {
        return $this->model->available()->get();
    }

    public function getByCategoryId($categoryId)
    {
        return $this->model->where('category_id', $categoryId)->get();
    }

    public function getAvailableByCategoryId($categoryId)
    {
        return $this->model->available()->where('category_id', $categoryId)->get();
    }

    public function findWithCategory($id)
    {
        return $this->model->with('category')->find($id);
    }

    public function toggleAvailable($id): bool
    {
        $menu = $this->find($id);
        if (!$menu) {
            return false;
        }

        return $menu->update([
            'is_available' => !$menu->is_available
        ]);
    }

    public function searchByName($query)
    {
        return $this->model->where('name', 'like', "%{$query}%")->get();
    }

    public function getByPriceRange($min, $max)
    {
        return $this->model->when($min, function ($q) use ($min) {
            return $q->where('price', '>=', $min);
        })->when($max, function ($q) use ($max) {
            return $q->where('price', '<=', $max);
        })->get();
    }
} 