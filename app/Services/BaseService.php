<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseService
{
    protected $repository;

    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function find($id): ?Model
    {
        return $this->repository->find($id);
    }

    public function findOrFail($id): Model
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $attributes): Model
    {
        return $this->repository->create($attributes);
    }

    public function update($id, array $attributes): bool
    {
        return $this->repository->update($id, $attributes);
    }

    public function delete($id): bool
    {
        return $this->repository->delete($id);
    }
} 