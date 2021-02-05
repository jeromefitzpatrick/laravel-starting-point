<?php

namespace JeromeFitzpatrick\StartingPoint\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait Searchable
{
    public function scopeSearch(Builder $query, $term = null)
    {
        if (is_null($term) || (is_array($term) && empty($term)) || !$this->searchables ?? []) {
            return $query;
        }

        if (is_array($term)) {
            $searchableColumns = collect($this->searchables);
            foreach ($term as $col => $val) {
                if (!$column = $searchableColumns->firstWhere('column', $col)) {
                    continue;
                }

                if (array_key_exists('scope', $column)) {
                    $this->addScope($query, $column['scope'], $val);
                    continue;
                }

                $this->addConstraint($query, [['column' => $col]], $val);
            }
        }

        if (is_string($term)) {
            $this->addConstraint($query, $this->searchables, $term);
        }

        return $query;
    }

    protected function addScope(Builder $query, $scope, $term)
    {
        return $query->{$scope}($term);
    }

    protected function addConstraint(Builder $query, $searchables, $term)
    {
        $query->where(function (Builder $query) use ($term, $searchables) {
            foreach ($searchables as $searchable) {
                // Ignore filter only searches
                if ($searchable['filter'] ?? null) {
                    continue;
                }

                if (!isset($searchable['relation'])) {
                    $this->shouldConcat($searchable['column']) ?
                        $query->orWhere(DB::raw(
                            "CONCAT({$this->searchGetConcatString($searchable['column'])})"),
                            'like',
                            "%{$term}%") :
                        $query->orWhere($searchable['column'], 'like', "%$term%");
                }
                else {
                    switch(count($relationParts = explode('.',$searchable['relation']))) {
                        case 1:
                            $query->orWhereHas($relationParts[0], function(Builder $query) use ($term, $searchable) {
                                $this->shouldConcat($searchable['column']) ?
                                    $this->addContactLikeQuery($query, $searchable['column'], $term) :
                                    $this->addSimpleLikeQuery($query, $searchable['column'], $term);
                            });
                            break;
                        case 2:
                            $query->orWhereHas($relationParts[0], function(Builder $query) use ($searchable, $term, $relationParts) {
                                $query->whereHas($relationParts[1], function (Builder $query) use ($searchable, $term){
                                    $this->shouldConcat($searchable['column']) ?
                                        $this->addContactLikeQuery($query, $searchable['column'], $term) :
                                        $this->addSimpleLikeQuery($query, $searchable['column'], $term);
                                });
                            });
                            break;
                    }
                }
            }
        });
    }

    protected function searchGetConcatString($fields)
    {
        return implode(",' ', ",array_map(function ($field) {
            return "`". $field . "`";
        }, explode('+',$fields)));
    }

    protected function shouldConcat(string $column)
    {
        return substr_count($column, '+') > 0;
    }

    protected function addSimpleLikeQuery(Builder $query, $column, $term)
    {
        $query->where($column, 'like', "%$term%");
    }

    protected function addContactLikeQuery(Builder $query, $column, $term)
    {
        $query->where(DB::raw(
            "CONCAT({$this->searchGetConcatString($column)})"),
            'like',
            "%{$term}%");
    }
}
