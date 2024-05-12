<?php

use App\Http\Controllers\NewTransactionController;
use App\Http\Controllers\RevertTransactionController;
use Illuminate\Support\Facades\Route;


Route::prefix('api')->group(function () {
    Route::post('/transfer', NewTransactionController::class)->name('transfer');
    Route::post('/transactions/revert/{transaction}', RevertTransactionController::class)->name('transactions.reverse');
});
