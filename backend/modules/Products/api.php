<?php

use Illuminate\Support\Facades\Route;
use Products\ProductController;

Route::group([
    'middleware' => ['auth:sanctum', 'user_checker']
], function () {
    Route::apiResource('products', ProductController::class)->only((['index', 'show']));
    Route::apiResource('products', ProductController::class)->except((['index', 'show']))->middleware(['ability:products,admin']);
});
