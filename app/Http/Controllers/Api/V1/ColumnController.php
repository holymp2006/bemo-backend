<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\Column;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CreateColumnRequest;
use App\Http\Resources\V1\JsonApiCollection;

class ColumnController extends ApiController
{
    public function index(): JsonApiCollection
    {
        return $this->service->fetchResources(Column::class, 'columns');
    }
    public function store(CreateColumnRequest $request): JsonResponse
    {
        return $this->service->createResource(
            Column::class,
            $request->validated()
        );
    }
    public function destroy(Column $column): Response
    {
        return $this->service->deleteResource($column);
    }
}
