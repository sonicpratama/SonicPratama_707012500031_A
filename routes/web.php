<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {

    Route::get('login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('login', [LoginController::class, 'store'])
        ->name('login.store');

    // Register
    Route::get('register', [RegisterController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisterController::class, 'store'])
        ->name('register.store');

});

Route::middleware('auth')->group(function () {

    Route::get('dashboard', DashboardController::class)
        ->name('dashboard');

    Route::get('profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::post('logout', [LoginController::class, 'destroy'])
        ->name('logout');

});
