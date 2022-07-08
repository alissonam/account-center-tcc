<?php

use Illuminate\Support\Facades\Route;
use Plans\PlanController;

Route::group([
    'middleware' => ['auth:sanctum', 'user_checker']
], function () {
    Route::apiResource('plans', PlanController::class);
});
