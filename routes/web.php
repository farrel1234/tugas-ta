<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\KioskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Halaman Public & Digital Signage Kiosk (Data dinamis dari database)
Route::get('/', [KioskController::class, 'index'])->name('kiosk.index');
Route::get('/kiosk', [KioskController::class, 'index']);

// 2. Rute Autentikasi (Login untuk guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// 3. Rute Logout (Khusus user yang terautentikasi)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 4. Rute Admin CMS (Dilindungi middleware 'auth')
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [MenuController::class, 'index'])->name('dashboard');
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
    Route::get('/menus/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');

    // Pengelolaan Langkah / Slide spesifik
    Route::post('/menus/{menu}/steps', [MenuController::class, 'storeStep'])->name('steps.store');
    Route::delete('/steps/{step}', [MenuController::class, 'destroyStep'])->name('steps.destroy');
});