<?php

use App\Http\Controllers\Api\V1\ControlController;
use App\Http\Controllers\Api\V1\PostureController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/posture', [PostureController::class, 'show']);
    Route::get('/controls', [ControlController::class, 'index']);
    Route::patch('/controls/{control}/status', [ControlController::class, 'updateStatus']);
});
