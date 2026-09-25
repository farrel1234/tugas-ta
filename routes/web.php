<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\GateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Halaman Public & Digital Signage Kiosk (Data dinamis dari database)
Route::get('/', [KioskController::class, 'index'])->name('kiosk.index');
Route::get('/kiosk', [KioskController::class, 'index']);

// 1.1 API Scanner Gerbang Masuk (KTP/QR/RFID Pengunjung)
Route::post('/api/scan', [GateController::class, 'scan'])->name('api.scan');

// 1.2 API Live Traffic Gerbang (Dashboard Real-time Telemetry)
Route::get('/api/live-traffic', [GateController::class, 'liveTraffic'])->name('api.live-traffic');

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
    Route::post('/menus/{id}/restore', [MenuController::class, 'restore'])->name('menus.restore');
    Route::delete('/menus/{id}/force-delete', [MenuController::class, 'forceDelete'])->name('menus.force-delete');

    // Pengelolaan Langkah / Slide spesifik
    Route::post('/menus/{menu}/steps', [MenuController::class, 'storeStep'])->name('steps.store');
    Route::put('/steps/{step}', [MenuController::class, 'updateStep'])->name('steps.update');
    Route::delete('/steps/{step}', [MenuController::class, 'destroyStep'])->name('steps.destroy');
});