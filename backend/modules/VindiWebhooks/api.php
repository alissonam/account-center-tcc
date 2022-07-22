<?php

use Illuminate\Support\Facades\Route;
use VindiWebhooks\VindiReceptorController;

Route::post('vindi-receptor', [VindiReceptorController::class, 'vindiReceptor']);
