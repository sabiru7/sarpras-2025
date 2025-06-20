<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReturnRequestController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('items', ItemController::class);
    Route::resource('loans', LoanController::class);
    Route::get('/returns', [ReturnRequestController::class, 'index'])->name('returns.index');
    Route::get('/returns/create/{loanId}', [ReturnRequestController::class, 'create'])->name('returns.create');
    Route::post('/returns', [ReturnRequestController::class, 'store'])->name('returns.store');
    Route::post('/returns/{id}/approve', [ReturnRequestController::class, 'approve'])->name('returns.approve');
    Route::post('/returns/{id}/reject', [ReturnRequestController::class, 'reject'])->name('returns.reject');
    Route::get('/returns-export', [ReturnRequestController::class, 'export'])->name('returns.export');
    Route::post('/loans/{id}/approve', [LoanController::class, 'approve'])->name('loans.approve');
    Route::post('/loans/{id}/reject', [LoanController::class, 'reject'])->name('loans.reject');
    Route::get('/loans-export', [LoanController::class, 'export'])->name('loans.export');
    Route::resource('users', UserController::class);

    Route::get('/', function () {
        return redirect()->route('login');
    });
});
