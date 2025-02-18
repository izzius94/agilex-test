<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::prefix('orders')->middleware('auth:sanctum')->name('orders')->group(function () {
    Route::get('', [OrderController::class, 'index'])->name('index');
    Route::post('', [OrderController::class, 'store'])->name('store');
    Route::get('{id}', [OrderController::class, 'get'])->name('get');
    Route::put('{id}', [OrderController::class, 'update'])->name('update');
});
