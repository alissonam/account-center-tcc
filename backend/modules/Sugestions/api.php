<?php

use Illuminate\Support\Facades\Route;
use Sugestions\SugestionController;

Route::group([
    'middleware' => ['auth:sanctum', 'user_checker']
], function () {
    Route::apiResource('sugestions', SugestionController::class)->only((['index', 'store']));
});
