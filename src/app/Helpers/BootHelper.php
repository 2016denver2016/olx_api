<?php

namespace App\Helpers;

use Carbon\Carbon;

trait BootHelper
{
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
    }
}
