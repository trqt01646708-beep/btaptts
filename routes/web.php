<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

// Registration routes
Route::get('/register', [RegistrationController::class, 'showForm'])->name('register.form');
Route::post('/register', [RegistrationController::class, 'register'])->name('register');

// Job logs routes
Route::get('/job-logs', [RegistrationController::class, 'showLogs'])->name('job-logs');

// Dashboard routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
Route::post('/dashboard/clear-failed', [DashboardController::class, 'clearFailed'])->name('dashboard.clear-failed');
Route::post('/dashboard/retry/{id}', [DashboardController::class, 'retryFailed'])->name('dashboard.retry');
Route::delete('/dashboard/delete-user/{email}', [DashboardController::class, 'deleteUser'])->name('dashboard.delete-user');
