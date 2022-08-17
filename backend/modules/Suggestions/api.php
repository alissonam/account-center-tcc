<?php

use Illuminate\Support\Facades\Route;
use Suggestions\SuggestionController;

Route::group([
    'middleware' => ['auth:sanctum', 'user_checker']
], function () {
    Route::apiResource('suggestions', SuggestionController::class)->only((['index', 'store']));
});
