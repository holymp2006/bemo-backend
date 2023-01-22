<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Card extends JsonApiModel
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'title', 'description', 'column_id',
    ];

    public function type(): string
    {
        return $this->table;
    }

    public function column(): BelongsTo
    {
        return $this->belongsTo(Column::class);
    }
}
