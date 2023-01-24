<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\Card;
use Illuminate\Http\Request;
use App\Services\CardService;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CreateCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Http\Resources\V1\JsonApiResource;
use App\Http\Resources\V1\JsonApiCollection;
use App\Http\Requests\UpdateMultipleCardRequest;

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
    public function indexOverride(
        Request $request,
        CardService $service
    ): JsonResponse {
        return $service->fetchResources($request->all());
    }
    public function updateMultiple(
        UpdateMultipleCardRequest $request,
    ): Response {
        return $this->service->updateMultipleResource(
            Card::class,
            $request->validated('data')
        );
    }
    public function test()
    {
        return [
            'data' => [
                [
                    'id' => '1',
                    'attributes' => [
                        'column_id' => '1',
                        'order' => 1,
                    ],
                ],
                [
                    'id' => '2',
                    'attributes' => [
                        'column_id' => '1',
                        'order' => 2,
                    ],
                ],
                [
                    'id' => '3',
                    'attributes' => [
                        'column_id' => '1',
                        'order' => 3,
                    ],
                ],
            ],
        ];
    }
}
