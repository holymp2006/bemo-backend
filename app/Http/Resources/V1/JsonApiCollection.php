<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Support\Collection;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\ResourceCollection;

class JsonApiCollection extends ResourceCollection
{
    public $collects = JsonApiResource::class;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
            'included' => $this->mergeIncludedRelations($request),
        ];
    }
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>|MissingValue
     */
    private function mergeIncludedRelations($request): Collection|array|MissingValue
    {
        $includes = $this->collection->flatMap->included($request)
            ->unique()->values();
        return $includes->isNotEmpty() ? $includes : new MissingValue();
    }
}
