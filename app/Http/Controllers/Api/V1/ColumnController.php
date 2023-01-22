<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\Column;

class ColumnController extends ApiController
{
    public function index()
    {
        return $this->service->fetchResources(Column::class, 'columns');
    }
}
