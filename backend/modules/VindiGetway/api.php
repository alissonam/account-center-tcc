<?php

use Illuminate\Support\Facades\Route;
use VindiGetway\testeController;

Route::post('teste/{subscription}', [testeController::class, 'teste']);
