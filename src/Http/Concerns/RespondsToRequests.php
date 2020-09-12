<?php

namespace JeromeFitzpatrick\StartingPoint\Http\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

trait RespondsToRequests
{
    /**
     * Returns the transformed item as an array
     */
    public function transformed(Model $resource, $relations = null): array
    {
        $relations = $this->getIncludes($relations);
        $resource->loadMissing($this->extractRelations($relations));

        return fractal()
            ->item($resource)
            ->transformWith($this->transformer)
            ->parseIncludes($relations)
            ->toArray();
    }

    /**
     * Returns the transformed collection as an array
     */
    public function transformedCollection(Builder $query, array $relations = null): array
    {
        $relations = $this->getIncludes($relations);
        $resources = $query->with($this->extractRelations($relations))->paginate(request()->get('itemsPerPage',15));

        return fractal()
            ->collection($resources)
            ->transformWith($this->transformer)
            ->parseIncludes($relations)
            ->paginateWith(new IlluminatePaginatorAdapter($resources))
            ->addMeta($this->getMeta())
            ->toArray();
    }

    /**
     * Returns merged requested relations and default relations
     */
    public function getIncludes(array $relations = null): array
    {
        $relations = array_merge($relations ?? [], $this->defaultRelations ?? []);
        return array_unique($relations);
    }

    /**
     * Returns an Eloquent Model
     */
    public function model(): Model
    {
        return new $this->model;
    }

    /**
     * Converts snake case includes to camel case and returns the ones that are valid relations
     */
    protected function extractRelations(array $relations): array
    {
        return array_filter(array_map(function($relation) {
            $camelCased = Str::camel($relation);
            if (method_exists($this->model(), $camelCased) || strpos($relation, '.')) {
                return $camelCased;
            }
            return null;
        }, $relations));
    }

    /**
     * @return array
     */
    protected function getMeta()
    {
        return [];
    }
}