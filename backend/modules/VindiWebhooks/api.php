<?php

use Illuminate\Support\Facades\Route;
use VindiWebhooks\VindiReceptorController;

Route::group([
    'middleware' => ['query_token_check']
], function () {
    Route::post('vindi-receptor', [VindiReceptorController::class, 'vindiReceptor']);
});
