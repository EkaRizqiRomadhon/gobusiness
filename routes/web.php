<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Password Reset Routes
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');

    Route::get('/stock', [ProductController::class, 'index'])->name('stock.index');
    Route::post('/stock', [ProductController::class, 'store'])->name('stock.store');
    Route::put('/stock/{product}', [ProductController::class, 'update'])->name('stock.update');
    Route::delete('/stock/{product}', [ProductController::class, 'destroy'])->name('stock.destroy');

    Route::get('/reports', [ReportController::class, 'reports'])->name('reports');
    Route::get('/analytics', [ReportController::class, 'analytics'])->name('analytics');

    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', [App\Http\Controllers\ProfileController::class, 'settings'])->name('profile.settings');
    Route::delete('/settings', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});
