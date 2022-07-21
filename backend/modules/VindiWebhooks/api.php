<?php

use Illuminate\Support\Facades\Route;

Route::post('vindi-receptor', [VindiController::class, 'vindiReceptor']);
