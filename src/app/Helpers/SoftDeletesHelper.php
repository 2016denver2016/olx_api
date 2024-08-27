<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

trait SoftDeletesHelper
{
    use SoftDeletes;

    protected static function boot(): void
    {
        parent::boot();

        $now = Carbon::now();
        static::creating(function ($model) use ($now) {
            $model->created_at = $now;
            $model->updated_at = $now;
            $model->created_by = auth()->id() ?? 1;
            $model->updated_by = auth()->id() ?? 1;
        });
        static::updating(function ($model) use ($now) {
            $model->updated_at = $now;
            $model->updated_by = auth()->id() ?? 1;
        });
        static::deleting(function ($model) use ($now) {
            $model->deleted_at = $now;
            $model->deleted_by = auth()->id() ?? 1;
        });
    }
}
