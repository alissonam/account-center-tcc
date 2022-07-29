<?php

use Illuminate\Support\Facades\Route;
use Subscriptions\SubscriptionController;

Route::group([
    'middleware' => ['auth:sanctum', 'user_checker']
], function () {
    Route::apiResource('subscriptions', SubscriptionController::class)->only((['index', 'show', 'store']));
    Route::apiResource('subscriptions', SubscriptionController::class)->except((['index', 'show', 'store', 'destroy']))->middleware(['abilities:admin']);
});
