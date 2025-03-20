<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function getActive()
    {
        return $this->model->active()->get();
    }

    public function findWithMenus($id)
    {
        return $this->model->with('menus')->find($id);
    }

    public function toggleActive($id): bool
    {
        $category = $this->find($id);
        if (!$category) {
            return false;
        }

        return $category->update([
            'is_active' => !$category->is_active
        ]);
    }
} 