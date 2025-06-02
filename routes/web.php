<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('akun.welcome'); // This should point to your main HTML file, e.g., resources/views/welcome.blade.php
});
Route::get('/home', function () {
    return view('home'); // This should point to your next page, e.g., resources/views/next-page.blade.php
});
Route::get('/welcome', function () {
    return view('akun.welcome'); // This should point to your next page, e.g., resources/views/next-page.blade.php
});
Route::get('/peminjaman', function () {
    return view('peminjaman'); // This should point to your next page, e.g., resources/views/next-page.blade.php
});
Route::get('/users', function () {
    return view('akun.user'); // This should point to your next page, e.g., resources/views/next-page.blade.php
});
Route::get('/peminjaman', function () {
    return view('barangs.peminjaman'); // This should point to your next page, e.g., resources/views/next-page.blade.php
});
Route::get('/home', function () {
    return view('home.home'); // This should point to your next page, e.g., resources/views/next-page.blade.php
});
Route::get('/barang', function () {
    return view('barangs.barang'); // This should point to your next page, e.g., resources/views/next-page.blade.php
});
Route::get('/aproval', function () {
    return view('approval'); // This should point to your next page, e.g., resources/views/next-page.blade.php
});
use App\Http\Controllers\BorrowingViewController;


// routes/web.php
use App\Http\Controllers\LaporanController;

Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
// routes/web.php

use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\ApprovalController;

// Form dan submit peminjaman
Route::get('/peminjaman', [BorrowingController::class, 'index'])->name('peminjaman.index');
Route::post('/peminjaman', [BorrowingController::class, 'store'])->name('peminjaman.store');

// Halaman approval admin
Route::get('/approval', [ApprovalController::class, 'index'])->name('approval.index');
Route::post('/approval/approve/{id}', [ApprovalController::class, 'approve'])->name('approval.approve');
Route::post('/approval/reject/{id}', [ApprovalController::class, 'reject'])->name('approval.reject');