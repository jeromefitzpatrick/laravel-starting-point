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
     * @var array
     */
    protected $defaultRelations = [];

    /**
     * Return the transformer
     */
    public function getTransformer(): TransformerAbstract
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
    public function transformed(Model $resource, $relations = null): array
    {
        $relations = $this->getIncludes($relations);
        $resource->loadMissing($this->extractRelations($relations));

        return fractal()
            ->item($resource)
            ->transformWith($this->getTransformer())
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
            ->transformWith($this->getTransformer())
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
        $relations = array_merge($relations ?? [], $this->getDefaultRelations() ?? []);
        return array_unique($relations);
    }

    /**
     * Converts snake case includes to camel case and returns the ones that are valid relations
     */
    protected function extractRelations(array $relations): array
    {
        return array_filter(array_map(function($relation) {
            $camelCased = Str::camel($relation);
            if (method_exists($this->getModel(), $camelCased) || strpos($relation, '.')) {
                return $camelCased;
            }
            return null;
        }, $relations));
    }
}