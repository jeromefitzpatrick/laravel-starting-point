<?php

namespace JeromeFitzpatrick\StartingPoint\Http\Responses;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\TransformerAbstract;

abstract class ModelResponse
{
    /**
     * @var TransformerAbstract
     */
    protected $transformer = null;

    /**
     * @var Model
     */
    protected $model = null;

    /**
     * @var array Default includes (not relations)
     */
    protected $defaultRelations = [];

    /**
     * Return the transformer class reference
     */
    public function getTransformer(): string
    {
        return $this->transformer;
    }

    /**
     * Return the model
     */
    public function getModel(): Model
    {
        return app()->make($this->model);
    }

    /**
     * Return the default includes / relations for this model
     */
    public function getDefaultRelations(): array
    {
        return $this->defaultRelations;
    }

    /**
     * Return response meta data
     */
    public function getMeta(): array
    {
        return [];
    }

    /**
     * Returns the transformed item as an array
     */
    public function transformed(Model $resource, array $includes = null): array
    {
        $includes = $this->getIncludes($includes);
        $resource->loadMissing($this->extractRelations($includes));

        return fractal()
            ->item($resource)
            ->transformWith($this->getTransformer())
            ->parseIncludes($includes)
            ->toArray();
    }

    /**
     * Returns the transformed collection as an array
     */
    public function transformedCollection(Builder $query, array $includes = null): array
    {
        $includes = $this->getIncludes($includes);
        $resources = $query
            ->with(array_diff( $this->extractRelations($includes), array_keys($query->getEagerLoads())))
            ->paginate(request()->get('itemsPerPage',15));

        return fractal()
            ->collection($resources)
            ->transformWith($this->getTransformer())
            ->parseIncludes($includes)
            ->paginateWith(new IlluminatePaginatorAdapter($resources))
            ->addMeta($this->getMeta())
            ->toArray();
    }

    /**
     * Returns merged requested includes and default includes
     */
    public function getIncludes(array $includes = null): array
    {
        return array_unique(array_merge($includes ?? [], $this->getDefaultRelations() ?? []));
    }

    /**
     * Converts snake case includes to camel case and returns the ones that are valid relations
     */
    protected function extractRelations(array $includes): array
    {
        return array_filter(array_map(function($include) {
            $camelCased = Str::camel($include);
            if (method_exists($this->getModel(), Str::before($camelCased, '.'))) {
                return $camelCased;
            }
            return null;
        }, $includes));
    }
}
