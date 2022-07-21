<?php

use Illuminate\Support\Facades\Route;
use VindiGatway\testeController;

Route::post('teste/{subscription}', [testeController::class, 'teste']);
