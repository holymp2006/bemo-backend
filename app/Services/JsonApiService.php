<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\V1\JsonApiResource;
use App\Http\Resources\V1\JsonApiCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\JsonApiIdentifierResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class JsonApiService
{
    public function fetchResources(string $modelClass, string $type): JsonApiCollection
    {
        $models = QueryBuilder::for($modelClass)
            ->allowedSorts(config("jsonapi.resources.{$type}.allowedSorts"))
            ->allowedIncludes(config("jsonapi.resources.{$type}.allowedIncludes"))
            ->allowedFilters(config("jsonapi.resources.{$type}.allowedFilters"))
            ->jsonPaginate();
        return new JsonApiCollection($models);
    }

    public function fetchResource(
        string|Model $model,
        string $id = '',
        string $type = ''
    ): JsonApiResource {
        if ($model instanceof Model) {
            return new JsonApiResource($model);
        }

        $query = QueryBuilder::for($model::where('id', $id))
            ->allowedIncludes(config("jsonapi.resources.{$type}.allowedIncludes"))
            ->firstOrFail();
        return new JsonApiResource($query);
    }

    public function createResource(
        string $modelClass,
        array $attributes,
        array $relationships = null
    ): JsonResponse {
        $model = $modelClass::create($attributes);

        if ($relationships) {
            $this->handleRelationship($relationships, $model);
        }

        return $this->jsonApiResourceWithLocation($model);
    }

    public function updateResource(
        Model $model,
        array $attributes,
        array $relationships = null
    ): JsonApiResource {
        $model->update($attributes);

        if (!is_null($relationships)) {
            $this->handleRelationship($relationships, $model);
        }

        return new JsonApiResource($model);
    }

    public function deleteResource(Model $model): Response
    {
        $model->delete();
        return response(null, 204);
    }

    public function fetchRelationship(
        Model $model,
        string $relationship
    ): JsonResource {
        if ($model->$relationship instanceof Model) {
            return new JsonApiIdentifierResource($model->$relationship);
        }

        return JsonApiIdentifierResource::collection($model->$relationship);
    }

    public function updateToOneRelationship(
        Model $model,
        string $relationship,
        int|string $id = null
    ): Response {
        $relatedModel = $model->$relationship()->getRelated();

        $model->$relationship()->dissociate();

        if (!is_null($id)) {
            $newModel = $relatedModel->newQuery()->findOrFail($id);
            $model->$relationship()->associate($newModel);
        }

        $model->save();
        return response(null, 204);
    }

    public function updateToManyRelationships(
        Model $model,
        string $relationship,
        array $ids
    ): Response {
        $foreignKey = $model->$relationship()->getForeignKeyName();
        $relatedModel = $model->$relationship()->getRelated();

        $relatedModel->newQuery()->findOrFail($ids);

        $relatedModel->newQuery()->where($foreignKey, $model->id)->update([
            $foreignKey => null,
        ]);
        $relatedModel->newQuery()->whereIn('id', $ids)->update([
            $foreignKey => $model->id,
        ]);

        return response(null, 204);
    }
    
    public function fetchRelated(
        Model $model,
        string $relationship
    ): JsonApiResource|ResourceCollection {
        if ($model->$relationship instanceof Model) {
            return new JsonApiResource($model->$relationship);
        }

        return new JsonApiCollection($model->$relationship);
    }

    protected function handleRelationship(
        array $relationships,
        Model $model
    ): void {
        foreach ($relationships as $relationshipName => $contents) {
            if ($model->$relationshipName() instanceof BelongsTo) {
                $this->updateToOneRelationship(
                    $model,
                    $relationshipName,
                    $contents['data']['id']
                );
            }
        }

        $model->load(array_keys($relationships));
    }

    protected function jsonApiResourceWithLocation(
        Model $model
    ): JsonResponse {
        $response =  (new JsonApiResource($model))
            ->response();
        if (Route::has("{$model->type()}.show")) {
            return $response->header(
                'Location',
                route("{$model->type()}.show", [
                    Str::singular($model->type()) => $model,
                ])
            );
        }

        return $response;
    }
}
