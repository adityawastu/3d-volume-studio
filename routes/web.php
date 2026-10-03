<?php

use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

//dashboard
Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

//kalkulator
Route::get('/calculator', [CalculatorController::class, 'index'])
    ->name('calculator.index');

Route::post('/calculator', [CalculatorController::class, 'calculate'])
    ->name('calculator.calculate');

//orders
Route::get('/orders', [OrderController::class, 'index'])
    ->name('orders.index');

Route::patch('/orders/{calculation}/fix', [OrderController::class, 'fix'])
    ->name('orders.fix');

Route::patch('/orders/{calculation}/cancel', [OrderController::class, 'cancel'])
    ->name('orders.cancel');

//finance
Route::get('/finance/expenses', [ExpenseController::class, 'index'])
    ->name('finance.expenses.index');

Route::post('/finance/expenses', [ExpenseController::class, 'store'])
    ->name('finance.expenses.store');


Route::patch('/finance/expenses/{expense}', [ExpenseController::class, 'update'])
    ->name('finance.expenses.update');

Route::delete('/finance/expenses/{expense}', [ExpenseController::class, 'destroy'])
    ->name('finance.expenses.destroy');

Route::get('/finance/incomes', [IncomeController::class, 'index'])
    ->name('finance.incomes.index');

Route::post('/finance/incomes', [IncomeController::class, 'store'])
    ->name('finance.incomes.store');

Route::post('/finance/incomes/import/preview', [IncomeController::class, 'previewImport'])
    ->name('finance.incomes.import.preview');

Route::post('/finance/incomes/import/confirm', [IncomeController::class, 'confirmImport'])
    ->name('finance.incomes.import.confirm');
