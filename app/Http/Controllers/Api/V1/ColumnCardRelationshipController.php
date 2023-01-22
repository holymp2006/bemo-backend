<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\Column;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\JsonApiRelationshipRequest;

class ColumnCardRelationshipController extends ApiController
{
    public function index(Column $column): JsonResource
    {
        return $this->service->fetchRelationship($column, 'cards');
    }
    public function indexRelated(Column $column): JsonResource
    {
        return $this->service->fetchRelated($column, 'cards');
    }
    public function update(JsonApiRelationshipRequest $request, Column $column): Response
    {
        return $this->service->updateToManyRelationships(
            $column,
            'cards',
            $request->input('data.*.id'),
        );
    }
}
