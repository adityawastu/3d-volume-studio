<?php

use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ExpenseController;
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

Route::get('/finance/expenses', [ExpenseController::class, 'index'])
    ->name('finance.expenses.index');

Route::post('/finance/expenses', [ExpenseController::class, 'store'])
    ->name('finance.expenses.store');


Route::patch('/finance/expenses/{expense}', [ExpenseController::class, 'update'])
    ->name('finance.expenses.update');

Route::delete('/finance/expenses/{expense}', [ExpenseController::class, 'destroy'])
    ->name('finance.expenses.destroy');
