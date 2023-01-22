<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class Column extends JsonApiModel
{
    use HasFactory;
    use HasUuids;

    public function type(): string
    {
        return $this->table;
    }

    protected $fillable = [
        'title',
    ];
}
