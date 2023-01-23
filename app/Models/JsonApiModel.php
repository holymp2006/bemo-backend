<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class JsonApiModel extends Model
{
    abstract public function type(): string;

    /**
     * Filter out attributes that are not allowed to be shown
     * @return array<string, mixed>
     */
    public function allowedAttributes(): array
    {
        $appends = [];
        foreach ($this->appends as $append) {
            $appends[$append] = $this->{$append};
        }
        $dates = [
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
        if (isset($this->deleted_at) || $this->deleted_at === null) {
            $dates['deleted_at'] = $this->deleted_at?->toDateTimeString();
        }
        return collect($this->attributes)->filter(function ($item, $key) {
            return !collect($this->hidden)->contains($key) && $key !== 'id';
        })->merge(collect($dates))
            ->merge(collect($appends))
            ->toArray();
    }
}
