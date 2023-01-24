<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class Card extends JsonApiModel
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'title', 'description',
        'column_id', 'order'
    ];
    protected $hidden = [
        'status', 'date',
    ];

    public static function boot()
    {
        parent::boot();
        static::created(function (self $model) {
            $model->date = $model->created_at->format('Y-m-d');
            $model->order = self::max('order') + 1;
            $model->save();
        });
        static::deleted(function (self $model) {
            $model->status = 0;
            $model->save();
        });
        static::restored(function (self $model) {
            $model->status = 1;
            $model->save();
        });
        // static::addGlobalScope('order', function (Builder $builder) {
        //     $builder->orderBy('order', 'asc');
        // });
    }

    public function type(): string
    {
        return $this->table;
    }

    public function column(): BelongsTo
    {
        return $this->belongsTo(Column::class);
    }
}
