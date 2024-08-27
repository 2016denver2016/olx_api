<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface AbstractRepositoryInterface
{
    public function createFromArray(array $data);

    public function updateFromArray(Model $model, array $data);

    public function findOneById(int $id, bool $throwException = true);

    public function findBy(array $params);
}
