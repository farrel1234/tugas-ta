<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\VisitorLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GateController extends Controller
{
    /**
     * Memproses pemindaian kode kartu / QR dari scanner gerbang perpustakaan.
     */
    public function scan(Request $request): JsonResponse
    {
        // Tangkap kode dari scanner (KTP/QR/RFID)
        $scannedCode = trim($request->input('kode_scan') ?? '');

        if (empty($scannedCode)) {
            return response()->json([
                'color'   => 'red',
                'name'    => 'Tidak Dikenal',
                'message' => 'Kode Scan Kosong'
            ], 400);
        }

        // Cari user di database berdasarkan kode tersebut
        $user = User::where('kode_kartu', $scannedCode)->first();

        // SKENARIO 1: MERAH (Belum Daftar / Data tidak ditemukan)
        if (!$user) {
            VisitorLog::create([
                'scanned_code' => $scannedCode,
                'status' => 'Belum Daftar'
            ]);

            return response()->json([
                'color'   => 'red',
                'name'    => 'Tidak Dikenal',
                'message' => 'Belum Terdaftar'
            ]);
        }

        // SKENARIO 2: ABU-ABU (Belum Perpanjang / Expired)
        $sekarang = Carbon::now();
        $isExpired = $user->expired_at ? $sekarang->greaterThan(Carbon::parse($user->expired_at)) : false;

        if ($isExpired) {
            VisitorLog::create([
                'user_id' => $user->id,
                'scanned_code' => $scannedCode,
                'status' => 'Belum Perpanjang'
            ]);

            return response()->json([
                'color'   => 'gray',
                'name'    => $user->name,
                'message' => 'Belum Perpanjang'
            ]);
        }

        // SKENARIO 3: HIJAU (Bisa Masuk / Aktif)
        VisitorLog::create([
            'user_id' => $user->id,
            'scanned_code' => $scannedCode,
            'status' => 'Bisa Masuk'
        ]);

        return response()->json([
            'color'   => 'green',
            'name'    => $user->name,
            'message' => 'Silakan Masuk'
        ]);
    }
}
