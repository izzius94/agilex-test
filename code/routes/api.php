<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::prefix('orders')->middleware('auth')->name('orders')->group(function () {
    Route::post('', [OrderController::class, 'store'])->name('store');
});
