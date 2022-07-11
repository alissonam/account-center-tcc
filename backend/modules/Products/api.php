<?php

use Illuminate\Support\Facades\Route;
use Products\ProductController;

Route::group([
    'middleware' => ['auth:sanctum', 'user_checker']
], function () {
    Route::apiResource('products', ProductController::class);
});
