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

        // 5. Pagination Table Data (Log Kunjungan Detail)
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

    // --- FITUR EXPORT (Excel & PDF Log Kunjungan Detail) ---

    public function export(Request $request)
    {
        $type        = $request->input('type');
        $month       = $request->input('month', now()->format('m'));
        $year        = $request->input('year', now()->format('Y'));

        $logs = VisitorLog::with('user')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->get();

        $monthName = Carbon::create($year, $month)->translatedFormat('F');

        if ($type === 'excel') {
            return $this->exportExcel($logs, $monthName, $year);
        } elseif ($type === 'pdf') {
            return $this->exportPdf($logs, $monthName, $year);
        }

        return back()->with('error', 'Tipe ekspor tidak valid.');
    }

    private function exportExcel($logs, $monthName, $year)
    {
        $fileName = "Log_Kunjungan_{$monthName}_{$year}.xlsx";
        $tempPath = tempnam(sys_get_temp_dir(), 'export_log_') . '.xlsx';

        $writer = new \OpenSpout\Writer\XLSX\Writer();
        $writer->openToFile($tempPath);

        $headerStyle = (new \OpenSpout\Common\Entity\Style\Style())->withFontBold(true);

        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValuesWithStyle(
            ["LOG KUNJUNGAN DETAIL - {$monthName} {$year}"], $headerStyle
        ));
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues([]));

        $headers = ['NO', 'WAKTU MASUK', 'NAMA PENGUNJUNG', 'GENDER', 'UMUR', 'WILAYAH'];
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValuesWithStyle($headers, $headerStyle));

        if ($logs->isEmpty()) {
            $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['-', '-', 'Tidak ada data kunjungan.', '-', '-', '-']));
        } else {
            foreach ($logs as $index => $log) {
                $age    = ($log->user && $log->user->tanggal_lahir) ? Carbon::parse($log->user->tanggal_lahir)->age . ' Thn' : '-';
                $gender = '-';
                if ($log->user) {
                    $gender = $log->user->gender === 'L' ? 'Laki-laki' : ($log->user->gender === 'P' ? 'Perempuan' : '-');
                }

                $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues([
                    $index + 1,
                    $log->created_at ? Carbon::parse($log->created_at)->format('d/m/Y H:i') : '-',
                    $log->user->name ?? 'Unknown / Guest',
                    $gender,
                    $age,
                    $log->user->kecamatan_domisili ?? '-',
                ]));
            }
        }

        $writer->close();

        return response()->download($tempPath, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    private function exportPdf($logs, $monthName, $year)
    {
        $fileName = "Log_Kunjungan_{$monthName}_{$year}.pdf";

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.exports.visitor_pdf', compact('logs', 'monthName', 'year'))
            ->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }
}