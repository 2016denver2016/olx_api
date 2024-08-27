<?php

declare(strict_types=1);

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class ToArrayTransformer extends TransformerAbstract
{
    public function transform($model)
    {
        return $model->toArray();
    }
}
