<?php

use Illuminate\Support\Facades\Route;

// 1. Halaman Public Web (Bisa diakses mahasiswa dari HP/Laptop)
Route::get('/', function () {
    return view('web.index');
});

// 2. Halaman Digital Signage (Layar TV di Perpustakaan)
Route::get('/kiosk', function () {
    return view('kiosk.index');
});

// 3. Halaman Admin CMS (Nanti bisa diamankan dengan login)
Route::get('/admin', function () {
    return view('admin.index');
});