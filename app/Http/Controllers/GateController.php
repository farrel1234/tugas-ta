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

        // Cari user di database berdasarkan kode kartu (RFID/Barcode) atau NIM
        $user = User::where('kode_kartu', $scannedCode)
            ->orWhere('nim', $scannedCode)
            ->orWhereRaw('LOWER(kode_kartu) = ?', [strtolower($scannedCode)])
            ->first();

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

        // SKENARIO COOLDOWN 30 DETIK (Mencegah oper kartu / tap berulang di gerbang)
        $kunjunganTerakhir = VisitorLog::where(function ($q) use ($user, $scannedCode) {
                $q->where('user_id', $user->id)
                  ->orWhere('scanned_code', $scannedCode);
            })
            ->where('status', 'Bisa Masuk')
            ->latest('id')
            ->first();

        if ($kunjunganTerakhir) {
            $waktuSekarang = Carbon::now();
            $waktuTerakhirScan = Carbon::parse($kunjunganTerakhir->created_at);
            $selisihDetik = $waktuTerakhirScan->diffInSeconds($waktuSekarang);

            if ($selisihDetik < 30) {
                VisitorLog::create([
                    'user_id' => $user->id,
                    'scanned_code' => $scannedCode,
                    'status' => 'Ditolak (Cooldown)'
                ]);

                return response()->json([
                    'color'   => 'orange',
                    'name'    => $user->name,
                    'message' => 'Kartu baru saja digunakan. Tunggu 30 detik!'
                ]);
            }
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

    /**
     * Mengambil telemetry data trafik gerbang hari ini / histori secara real-time.
     */
    public function liveTraffic(Request $request): JsonResponse
    {
        $dateParam = $request->query('date');
        if ($dateParam) {
            try {
                $selectedDate = Carbon::createFromFormat('Y-m-d', $dateParam, 'Asia/Jakarta')->startOfDay();
            } catch (\Exception $e) {
                $selectedDate = Carbon::today('Asia/Jakarta');
            }
        } else {
            $selectedDate = Carbon::today('Asia/Jakarta');
        }

        $bisaMasuk = VisitorLog::whereDate('created_at', $selectedDate)
            ->where('status', 'Bisa Masuk')
            ->count();

        $belumPerpanjang = VisitorLog::whereDate('created_at', $selectedDate)
            ->where('status', 'Belum Perpanjang')
            ->count();

        $belumDaftar = VisitorLog::whereDate('created_at', $selectedDate)
            ->where('status', 'Belum Daftar')
            ->count();

        $total = $bisaMasuk + $belumPerpanjang + $belumDaftar;

        // Skala: 200 mahasiswa = 50% kapasitas, tanpa batas maksimal kunjungan
        $capacityPercentage = round(($total / 200) * 50, 1);

        // Persentase proporsional dari total untuk susunan layer tabung
        $pctBisaMasuk = $total > 0 ? round(($bisaMasuk / $total) * 100, 1) : 0;
        $pctBelumPerpanjang = $total > 0 ? round(($belumPerpanjang / $total) * 100, 1) : 0;
        $pctBelumDaftar = $total > 0 ? round(($belumDaftar / $total) * 100, 1) : 0;

        // Ambil log pengunjung sesuai tanggal yang dipilih untuk terminal console (Live vs Histori)
        $recentLogs = VisitorLog::with('user:id,name,nim')
            ->whereDate('created_at', $selectedDate)
            ->latest('created_at')
            ->take(30)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'time' => $log->created_at ? $log->created_at->timezone('Asia/Jakarta')->format('H:i:s') : '--:--:--',
                    'date' => $log->created_at ? $log->created_at->timezone('Asia/Jakarta')->format('d/m/Y') : '',
                    'name' => $log->user ? $log->user->name : 'Tidak Dikenal',
                    'nim' => $log->user ? ($log->user->nim ?? '-') : '-',
                    'scanned_code' => $log->scanned_code,
                    'status' => $log->status,
                    'color' => $log->status === 'Bisa Masuk' ? 'green' : ($log->status === 'Belum Perpanjang' ? 'gray' : ($log->status === 'Ditolak (Cooldown)' ? 'orange' : 'red')),
                ];
            });

        return response()->json([
            'total' => $total,
            'capacity_percentage' => $capacityPercentage,
            'selected_date' => $selectedDate->format('Y-m-d'),
            'formatted_date' => $selectedDate->translatedFormat('d M Y'),
            'is_today' => $selectedDate->isToday(),
            'status_counts' => [
                'bisa_masuk' => $bisaMasuk,
                'belum_perpanjang' => $belumPerpanjang,
                'belum_daftar' => $belumDaftar,
            ],
            'status_percentages' => [
                'bisa_masuk' => $pctBisaMasuk,
                'belum_perpanjang' => $pctBelumPerpanjang,
                'belum_daftar' => $pctBelumDaftar,
            ],
            'recent_logs' => $recentLogs,
        ]);
    }
}
