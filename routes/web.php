<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Transactions Routes
    Route::get('/transactions/all', [TransactionController::class, 'index'])->name('transactions.all');
    Route::get('/transactions/add', [TransactionController::class, 'create'])->name('transactions.add');
    Route::post('/transactions/store', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/recap', [TransactionController::class, 'recap'])->name('transactions.recap');
});