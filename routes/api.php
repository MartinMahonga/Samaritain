<?php

use App\Http\Controllers\API\ParcelleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('parcelles')->group(function () {
    Route::get('/', [ParcelleController::class, 'index']);
    Route::get('/{id}', [ParcelleController::class, 'show']);

    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
        Route::delete('/images/{imageId}', [ParcelleController::class, 'deleteImage']);
        Route::post('/', [ParcelleController::class, 'store']);
        Route::post('/{id}', [ParcelleController::class, 'update']);
        Route::delete('/{id}', [ParcelleController::class, 'destroy']);
    });
});
