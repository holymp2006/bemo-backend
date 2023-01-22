<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\Card;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CreateCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Http\Resources\V1\JsonApiCollection;
use App\Http\Resources\V1\JsonApiResource;

class CardController extends ApiController
{
    public function index(): JsonApiCollection
    {
        return $this->service->fetchResources(Card::class, 'cards');
    }
    public function store(CreateCardRequest $request): JsonResponse
    {
        return $this->service->createResource(
            Card::class,
            $request->input('data.attributes'),
        );
    }
    public function update(UpdateCardRequest $request, Card $card): JsonApiResource
    {
        return $this->service->updateResource(
            $card,
            $request->input('data.attributes')
        );
    }
    public function destroy(Card $card): Response
    {
        return $this->service->deleteResource($card);
    }
}
