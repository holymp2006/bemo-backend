<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\JsonApiService;

class ApiController extends Controller
{
    public function __construct(
        public JsonApiService $service
    ) {
    }
}
