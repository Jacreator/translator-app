<?php

use App\Http\Controllers\Api\TranslationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(
    function () {
        Route::apiResource('translations', TranslationController::class)
            ->only(['index', 'store', 'show']);
    }
);