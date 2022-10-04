<?php

namespace App\Core\Abstracts\Repository;

use App\Core\Abstracts\Filters;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected $model;

    public function setModel(Model $model)
    {

        $this->model = $model;
    }

    public function findAll(Filters|null $filter = null, array $relations = [])
    {
        try {
            return $this->model->with($relations)->filter($filter)->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findById(int $id, array $relations = [], Filters|null $filter = null)
    {
        try {
            return $this->model->with($relations)->filter($filter)->findOrFail($id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function paginate(int $perPage = 10, array $relations = [], Filters|null $filter = null)
    {
        try {
            return $this->model->with($relations)->filter($filter)->paginate($perPage);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function create(array $params)
    {

        try {
            return $this->model->create($params);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update(int $id, array $params, Filters|null $filter = null)
    {
        try {
            $model = $this->findById($id, filter: $filter);
            $model->update($params);
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete(int $id, Filters|null $filter = null)
    {
        try {
            $model = $this->findById($id, filter: $filter);
            $model->delete();
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
