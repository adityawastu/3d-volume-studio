<?php

use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/calculator', [CalculatorController::class, 'index'])
    ->name('calculator.index');

Route::post('/calculator', [CalculatorController::class, 'calculate'])
    ->name('calculator.calculate');
