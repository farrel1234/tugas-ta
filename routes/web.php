<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// 1. Halaman Public Web (Bisa diakses mahasiswa dari HP/Laptop)
Route::get('/', function () {
    return view('kiosk.index');
});

// 2. Halaman Digital Signage (Layar TV di Perpustakaan)
Route::get('/kiosk', function () {
    return view('kiosk.index');
});

// 3. Halaman Admin CMS (Nanti bisa diamankan dengan login)
Route::get('/admin', function () {
    // Data dummy sementara untuk kebutuhan tampilan Front-End
    $menus = [
        (object)[
            'num' => '01',
            'title' => 'Layanan Sirkulasi Mandiri',
            'desc' => 'Peminjaman, pengembalian, dan perpanjangan buku...',
            'steps' => [1, 2, 3, 4, 5]
        ],
        (object)[
            'num' => '02',
            'title' => 'Dropbox',
            'desc' => 'Kotak drop pengembalian buku mandiri 24 jam...',
            'steps' => [1, 2, 3]
        ],
        (object)[
            'num' => '03',
            'title' => 'Stock Opname',
            'desc' => 'Proses audit dan pendataan ulang inventaris koleksi buku...',
            'steps' => [1, 2, 3]
        ]
    ];

    // Kirim variabel $menus ke view admin.dashboard
    return view('admin.dashboard', compact('menus'));
});

Route::get('/admin', function () {
    return view('admin.dashboard');
});

// Tambahkan rute logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');