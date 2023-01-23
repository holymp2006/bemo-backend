<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Card;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\V1\CardResource;

class CardService
{
    /**
     * Fetch all cards, but not the JSON:API way
     * @param array<string, mixed> $params
     */
    public function fetchResources(array $params): JsonResponse
    {
        $allowedFilters = config('jsonapi.resources.cards.allowedFilters');

        $cards = Card::query()->withTrashed();
        foreach ($params as $key => $value) {
            if (in_array($key, $allowedFilters)) {
                $cards->where($key, $value);
            }
        }

        return response()->json(
            CardResource::collection($cards->get())
        );
    }
}
