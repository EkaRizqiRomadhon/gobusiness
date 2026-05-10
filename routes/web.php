<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SimplePasswordResetController;
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

    // Simple Password Reset Routes
    Route::get('/lupa-password', [SimplePasswordResetController::class, 'showVerificationForm'])->name('simple.password.request');
    Route::post('/lupa-password/verifikasi', [SimplePasswordResetController::class, 'verifyUser'])->name('simple.password.verify');
    Route::get('/lupa-password/reset', [SimplePasswordResetController::class, 'showResetForm'])->name('simple.password.resetForm');
    Route::post('/lupa-password/reset', [SimplePasswordResetController::class, 'updatePassword'])->name('simple.password.update');
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
