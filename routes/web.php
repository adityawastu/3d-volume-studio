<?php

use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/calculator', [CalculatorController::class, 'index'])
    ->name('calculator.index');

Route::post('/calculator', [CalculatorController::class, 'calculate'])
    ->name('calculator.calculate');

Route::get('/orders', [OrderController::class, 'index'])
    ->name('orders.index');

Route::patch('/orders/{calculation}/fix', [OrderController::class, 'fix'])
    ->name('orders.fix');

Route::patch('/orders/{calculation}/cancel', [OrderController::class, 'cancel'])
    ->name('orders.cancel');
