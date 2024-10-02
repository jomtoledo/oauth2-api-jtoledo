<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth:web'])->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('customers'); // Root route
    Route::get('/customers', [CustomerController::class, 'index']);
});