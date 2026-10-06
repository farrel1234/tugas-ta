<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisitorLog;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->input('month', now()->format('m'));
        $selectedYear = $request->input('year', now()->format('Y'));

        // Load all logs for chart data
        $logs = VisitorLog::with('user')
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->get();

        // 1. Tren Harian (Line Chart)
        $dailyTrend = [];
        $daysInMonth = Carbon::create($selectedYear, $selectedMonth)->daysInMonth;
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dailyTrend[$i] = 0;
        }
        foreach ($logs as $log) {
            $day = (int) Carbon::parse($log->created_at)->format('j');
            if (isset($dailyTrend[$day])) {
                $dailyTrend[$day]++;
            }
        }

        // 2. Gender (Doughnut Chart)
        $genderData = [
            'L' => 0,
            'P' => 0,
        ];
        foreach ($logs as $log) {
            if ($log->user && $log->user->gender === 'L') {
                $genderData['L']++;
            } elseif ($log->user && $log->user->gender === 'P') {
                $genderData['P']++;
            }
        }

        // 3. Umur Lengkap (Vertical Bar Chart)
        $ageGroups = [
            '17-19 Tahun' => 0,
            '20-22 Tahun' => 0,
            '23-25 Tahun' => 0,
            '>25 Tahun' => 0,
        ];
        
        foreach ($logs as $log) {
            if ($log->user && $log->user->tanggal_lahir) {
                $age = Carbon::parse($log->user->tanggal_lahir)->age;
                if ($age >= 17 && $age <= 19) {
                    $ageGroups['17-19 Tahun']++;
                } elseif ($age >= 20 && $age <= 22) {
                    $ageGroups['20-22 Tahun']++;
                } elseif ($age >= 23 && $age <= 25) {
                    $ageGroups['23-25 Tahun']++;
                } elseif ($age > 25) {
                    $ageGroups['>25 Tahun']++;
                }
            }
        }

        // 4. Wilayah Lengkap (Horizontal Bar Chart)
        $regionData = [];
        foreach ($logs as $log) {
            if ($log->user && $log->user->kecamatan_domisili) {
                $region = $log->user->kecamatan_domisili;
                if (!isset($regionData[$region])) {
                    $regionData[$region] = 0;
                }
                $regionData[$region]++;
            }
        }
        arsort($regionData);

                // KPI Calculations
        $kpiTotal = count($logs);
        
        $kpiAvg = 0;
        $activeDays = count(array_filter($dailyTrend));
        if ($activeDays > 0) {
            $kpiAvg = round($kpiTotal / $activeDays, 1);
        }

        $hourTrend = [];
        foreach ($logs as $log) {
            $hour = Carbon::parse($log->created_at)->format('H:00');
            if(!isset($hourTrend[$hour])) $hourTrend[$hour] = 0;
            $hourTrend[$hour]++;
        }
        $kpiPeakTime = '-';
        if (!empty($hourTrend)) {
            arsort($hourTrend);
            $kpiPeakTime = array_key_first($hourTrend);
        }

        $kpiDominant = '-';
        if (!empty($regionData) && max($regionData) > 0) {
            $kpiDominant = array_key_first($regionData);
        }

        // 5. Pagination Table Data
        $paginatedLogs = VisitorLog::with('user')
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $paginatedLogs->appends(['month' => $selectedMonth, 'year' => $selectedYear]);

        return view('admin.analytics', compact(
            'selectedMonth',
            'selectedYear',
            'dailyTrend',
            'genderData',
            'ageGroups',
            'regionData',
            'paginatedLogs',
            'kpiTotal',
            'kpiAvg',
            'kpiPeakTime',
            'kpiDominant'
        ));
    }

    // --- FITUR EXPORT (Excel & PDF) ---

    public function export(Request $request)
    {
        $type = $request->input('type');
        $selectedMonth = $request->input('month', now()->format('m'));
        $selectedYear = $request->input('year', now()->format('Y'));

        // Ambil data sesuai filter
        $logs = VisitorLog::with('user')
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($type === 'excel') {
            return $this->exportExcel($logs, $selectedMonth, $selectedYear);
        } elseif ($type === 'pdf') {
            return $this->exportPdf($logs, $selectedMonth, $selectedYear);
        }

        return back()->with('error', 'Tipe ekspor tidak valid.');
    }

    private function exportExcel($logs, $month, $year)
    {
        // TODO: Implementasi Maatwebsite/Laravel-Excel
        // Contoh: return Excel::download(new VisitorLogExport($logs), "Log_Kunjungan_{$month}_{$year}.xlsx");
        
        // Return dummy untuk sementara
        return back()->with('success', "Kerangka Export Excel untuk bulan {$month}-{$year} berhasil dipanggil! Silakan install package Maatwebsite.");
    }

    private function exportPdf($logs, $month, $year)
    {
        // TODO: Implementasi barryvdh/laravel-dompdf
        // Contoh: 
        // $pdf = PDF::loadView('admin.exports.visitor_pdf', compact('logs', 'month', 'year'));
        // return $pdf->download("Laporan_Kunjungan_{$month}_{$year}.pdf");

        // Return dummy untuk sementara
        return back()->with('success', "Kerangka Cetak PDF untuk bulan {$month}-{$year} berhasil dipanggil! Silakan install package DomPDF.");
    }
}