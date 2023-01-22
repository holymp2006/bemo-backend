<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

final class JsonApiIdentifierResource extends JsonResource
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
        ];
        if ($this->uuid) {
            $data['id'] = (string)$this->uuid;
            $data['uuid'] = (string)$this->uuid;
        }

        return $data;
    }
}
