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

Route::prefix('api/v1')->group(function (): void {
    Route::prefix('columns')->group(function () {
        Route::controller(ColumnController::class)
            ->group(function (): void {
                Route::get('/', 'index')->name('columns.index');
                Route::post('/', 'store')->name('columns.store');
                Route::delete('{column}', 'destroy')->name('columns.destroy');
            });
        Route::controller(ColumnCardRelationshipController::class)
            ->group(function (): void {
                Route::get('{column}/relationships/cards', 'index')->name('columns.relationships.cards');
                Route::patch('{column}/relationships/cards', 'update')->name('columns.relationships.cards');
                Route::get('{column}/cards', 'indexRelated')->name('columns.cards');
            });
    });

    Route::prefix('cards')->group(function () {
        Route::controller(CardController::class)
            ->group(function (): void {
                Route::get('/', 'index')->name('cards.index');
                Route::post('/', 'store')->name('cards.store');
                Route::patch('/', 'updateMultiple')->name('cards.update.multiple');
                Route::patch('{card}', 'update')->name('cards.update');
                Route::delete('{card}', 'destroy')->name('cards.destroy');
            });
    });

    Route::controller(CardController::class)
        ->group(function (): void {
            Route::get('/list-cards', 'indexOverride')->name('cards.index.override.versioned');
        });
});

Route::controller(CardController::class)
    ->prefix('api')
    ->group(function (): void {
        Route::get('/list-cards', 'indexOverride')->name('cards.index.override');
        Route::get('/test', 'test')->name('cards.test');
    });
