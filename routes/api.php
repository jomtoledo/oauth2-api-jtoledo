<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;

Route::post('register', [AuthController::class, 'register'])->name('api.register');
Route::post('login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:api')->group(function () {
    Route::get('/customers', [CustomerController::class, 'index'])->name('api.customers.list');
    Route::post('/customer', [CustomerController::class, 'insert'])->name('api.customer.insert');
    Route::get('/customer/{id}', [CustomerController::class, 'details'])->name('api.customer.details');
    Route::put('/customer/{id}', [CustomerController::class, 'update'])->name('api.customer.update');
    Route::delete('/customer/{id}', [CustomerController::class, 'delete'])->name('api.customer.delete');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
});