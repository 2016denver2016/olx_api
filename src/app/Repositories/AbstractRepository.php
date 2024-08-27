<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Models\File;
use App\Models\Flowk;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class AbstractRepository implements AbstractRepositoryInterface
{
    protected $model;

    public function createFromArray(array $data): User
    {
        return $this->model::create($data);
    }

    public function updateFromArray(Model $model, array $data): void
    {
        $model->update($data);
    }

    /**
     * @param int  $id
     * @param bool $throwException
     *
     * @return Model|User|Flowk|File|null
     */
    public function findOneById(int $id, bool $throwException = true): ?Model
    {
        $model = $this->model::where('id', $id)->first();

        if (!$model && $throwException) {
            throw new NotFoundException("{$this->model} ID not found!");
        }

        return $model;
    }

    /**
     * @throws Exception
     */
    public function findBy(array $params): ?Collection
    {
        $models = $this->model::query();

        foreach ($params as $field => $value) {
            if (!is_array($value) && !is_string($value) && !is_numeric($value)) {
                throw new Exception('Invalid type "'.gettype($value).'" for query builder!');
            }

            if (is_array($value)) {
                $models = $models->whereIn($field, $value);
            } else {
                $models = $models->where($field, $value);
            }
        }

        return $models->get();
    }
}
