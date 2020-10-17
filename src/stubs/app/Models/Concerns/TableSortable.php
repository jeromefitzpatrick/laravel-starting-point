<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait TableSortable
{
    public function scopeTableSort(Builder $query, $columns, $desc)
    {
        foreach ($columns as $index => $column) {
            $dir =  filter_var($desc[$index], FILTER_VALIDATE_BOOLEAN) ? 'desc' : 'asc';
            $query->orderBy($column,$dir);
        }
        return $query;
    }
}