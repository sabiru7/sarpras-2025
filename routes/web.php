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
    return view('barangs.index'); // This should point to your next page, e.g., resources/views/next-page.blade.php
});
Route::get('/home', function () {
    return view('home'); // This should point to your next page, e.g., resources/views/next-page.blade.php
});
Route::get('/barang', function () {
    return view('barangs.barang'); // This should point to your next page, e.g., resources/views/next-page.blade.php
});
Route::get('/aproval', function () {
    return view('laporan.aproval'); // This should point to your next page, e.g., resources/views/next-page.blade.php
});