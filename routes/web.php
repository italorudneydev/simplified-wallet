<?php

use App\Http\Controllers\NewTransactionController;
use Illuminate\Support\Facades\Route;

Route::post('/transfer', NewTransactionController::class)->name('transfer');
