<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\JsonApiIdentifierResource;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Route;

final class JsonApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        $data = [
            'id' => (string)$this->id,
            'type' => $this->type(),
            'attributes' => $this->allowedAttributes(),
            'relationships' => $this->prepareRelationships(),
        ];
        if ($this->uuid) {
            $data['id'] = (string)$this->uuid;
            $data['uuid'] = (string)$this->uuid;
        }

        return $data;
    }

    private function prepareRelationships(): Collection|MissingValue
    {
        $collection = collect(config("jsonapi.resources.{$this->type()}.relationships"))
            ->filter(function ($related) {
                return empty($related['type']) === false &&
                    empty($related['method']) === false;
            })
            ->flatMap(function ($related) {
                $relatedType = $related['type'];
                $relationship = $related['method'];
                if (is_null($relatedType) || is_null($relationship)) {
                    return [];
                }
                $return = [
                    'data' => $this->prepareRelationshipData($relatedType, $relationship),
                ];

                if (Route::has("{$this->type()}.relationships.{$relatedType}")) {
                    $return['links']['self'] = route(
                        "{$this->type()}.relationships.{$relatedType}",
                        ['id' => $this->id]
                    );
                }
                if (Route::has("{$this->type()}.{$relatedType}")) {
                    $return['links']['related'] = route(
                        "{$this->type()}.{$relatedType}",
                        ['id' => $this->id]
                    );
                }

                return [$relatedType => $return];
            });

        return $collection->count() > 0 ? $collection : new MissingValue();
    }

    private function prepareRelationshipData(
        string $relatedType,
        string $relationship
    ): JsonResource|MissingValue {
        if ($this->whenLoaded($relationship) instanceof MissingValue) {
            return new MissingValue();
        }

        if ($this->$relationship() instanceof BelongsTo) {
            return new JsonApiIdentifierResource($this->$relationship);
        }
        return JsonApiIdentifierResource::collection($this->$relationship);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     */
    public function with($request): array
    {
        $with = [];
        if ($this->included($request)->isNotEmpty()) {
            $with['included'] = $this->included($request);
        }

        return $with;
    }

    public function included(Request $request): Collection
    {
        $relations = $this->relations();
        if ($relations->isEmpty()) {
            return collect();
        }
        return collect($relations)
            ->filter(function ($resource) {
                return $resource->collection !== null;
            })->flatMap->toArray($request);
    }

    private function relations(): Collection
    {
        return collect(config("jsonapi.resources.{$this->type()}.relationships"))
            ->filter(function ($relation) {
                return empty($relation['method']) === false;
            })
            ->map(function ($relation) {
                $modelOrCollection = $this->whenLoaded($relation['method']);

                if ($modelOrCollection instanceof Model) {
                    $modelOrCollection = collect([new JsonApiResource($modelOrCollection)]);
                }

                return JsonApiResource::collection($modelOrCollection);
            });
    }
}
