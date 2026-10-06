<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisitorLog;
use App\Models\User;
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

        // 5. Pagination Table Data (Rekap Data Anggota Terdaftar)
        $memberPage = max(1, (int) $request->input('page', 1));
        $membersQuery = User::where('role', '!=', 'admin')->orderBy('name', 'asc');
        $totalMembers = $membersQuery->count();
        $maxMemberPage = max(1, (int) ceil($totalMembers / 15));
        if ($memberPage > $maxMemberPage) {
            $memberPage = 1;
        }

        $paginatedMembers = $membersQuery->paginate(15, ['*'], 'page', $memberPage);
        $paginatedMembers->appends($request->except('page'));

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
            'paginatedMembers',
            'paginatedLogs',
            'kpiTotal',
            'kpiAvg',
            'kpiPeakTime',
            'kpiDominant'
        ));
    }

    // --- FITUR EXPORT (Excel & PDF Rekap Data Anggota) ---

    public function export(Request $request)
    {
        $type = $request->input('type');

        // Ambil seluruh data anggota terdaftar (kecuali akun admin)
        $members = User::where('role', '!=', 'admin')
            ->orderBy('name', 'asc')
            ->get();

        if ($type === 'excel') {
            return $this->exportExcel($members);
        } elseif ($type === 'pdf') {
            return $this->exportPdf($members);
        }

        return back()->with('error', 'Tipe ekspor tidak valid.');
    }

    private function exportExcel($members)
    {
        $fileName = "Rekap_Data_Anggota_Terdaftar.xlsx";
        $tempPath = tempnam(sys_get_temp_dir(), 'export_member_') . '.xlsx';

        $writer = new \OpenSpout\Writer\XLSX\Writer();
        $writer->openToFile($tempPath);

        // Header Style (Bold)
        $headerStyle = (new \OpenSpout\Common\Entity\Style\Style())->withFontBold(true);

        // Judul Laporan
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValuesWithStyle(["REKAP DATA ANGGOTA TERDAFTAR PERPUSTAKAAN"], $headerStyle));
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues([]));

        // Header Kolom
        $headers = ['NO', 'NIM', 'NAMA ANGGOTA', 'GENDER', 'UMUR', 'WILAYAH', 'STATUS'];
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValuesWithStyle($headers, $headerStyle));

        // Baris Data
        if ($members->isEmpty()) {
            $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['-', '-', 'Tidak ada data anggota terdaftar.', '-', '-', '-', '-']));
        } else {
            foreach ($members as $index => $member) {
                $age = $member->tanggal_lahir ? Carbon::parse($member->tanggal_lahir)->age . ' Thn' : '-';
                $gender = $member->gender === 'L' ? 'Laki-laki' : ($member->gender === 'P' ? 'Perempuan' : '-');
                $statusKartu = '-';
                if ($member->expired_at) {
                    $exp = Carbon::parse($member->expired_at);
                    $statusKartu = $exp->isPast() ? 'Kadaluarsa (exp: ' . $exp->format('d/m/Y') . ')' : 'Aktif (s.d. ' . $exp->format('d/m/Y') . ')';
                }

                $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues([
                    $index + 1,
                    $member->nim ?? '-',
                    $member->name,
                    $gender,
                    $age,
                    $member->kecamatan_domisili ?? '-',
                    $statusKartu,
                ]));
            }
        }

        $writer->close();

        return response()->download($tempPath, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    private function exportPdf($members)
    {
        $fileName = "Rekap_Data_Anggota_Terdaftar.pdf";

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.exports.members_pdf', compact('members'))
            ->setPaper('a4', 'portrait');

        return $pdf->download($fileName);
    }
}