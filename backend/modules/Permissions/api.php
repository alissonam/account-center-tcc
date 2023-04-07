<?php

use Illuminate\Support\Facades\Route;
use Permissions\PermissionController;

Route::group([
    'middleware' => ['auth:sanctum', 'user_checker']
], function () {
    Route::get('permissions/all-permission', [PermissionController::class, 'allPermissions'])->middleware(['abilities:permissions,admin']);
    Route::apiResource('permissions', PermissionController::class)->middleware(['abilities:permissions,admin']);
});
