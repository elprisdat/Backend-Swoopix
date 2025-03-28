<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
});

// Payment callback routes
Route::prefix('payment')->group(function () {
    Route::get('/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/failed', [PaymentController::class, 'failed'])->name('payment.failed');
    Route::post('/callback', [PaymentController::class, 'callback'])->name('payment.callback');
});
