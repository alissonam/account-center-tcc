<?php

use Illuminate\Support\Facades\Route;
use Users\UserController;

Route::post('login', [UserController::class, 'login']);
Route::post('forgot-password', [UserController::class, 'forgotPassword']);
Route::post('register', [UserController::class, 'register']);

Route::group([
    'middleware' => ['auth:sanctum', 'user_checker']
], function () {
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('logout-all', [UserController::class, 'logoutAll']);
    Route::get('users/logged-user',[UserController::class, 'loggedUser']);
    Route::apiResource('users', UserController::class)->only((['show', 'update']));
    Route::apiResource('users', UserController::class)->except((['show', 'update']))->middleware(['abilities:admin']);
});

Route::group([
    'middleware' => ['auth:sanctum']
], function () {
    Route::post('reset-password', [UserController::class, 'resetPassword']);
});
