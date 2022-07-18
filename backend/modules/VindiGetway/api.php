<?php

use Illuminate\Support\Facades\Route;
use VindiGetway\testeController;

Route::post('teste/{user}', [testeController::class, 'teste']);
