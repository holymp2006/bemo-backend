<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('columns')->group(function () {
    Route::controller(ColumnController::class)
        ->group(function (): void {
            Route::get('/', 'index')->name('columns.index');
            Route::post('/', 'store')->name('columns.store');
            Route::delete('/{column}', 'destroy')->name('columns.destroy');
        });
});
