<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class Column extends JsonApiModel
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'title',
    ];
    protected $with = [
        'cards',
    ];

    public function type(): string
    {
        return $this->table;
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }
}
