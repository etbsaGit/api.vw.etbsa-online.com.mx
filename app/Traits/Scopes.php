<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Scopes
{
    public function scopeFilter(Builder $query, array $filters)
    {
        foreach ($filters as $key => $value) {
            if ($value !== null) {
                $query->where($key, $value);
            }
        }
    }
}
