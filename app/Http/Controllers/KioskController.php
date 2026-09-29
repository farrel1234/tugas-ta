<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\VisitorLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KioskController extends Controller
{
    /**
     * Menampilkan halaman utama Kiosk / Digital Signage dengan data dinamis dari database.
     */
    public function index()
    {
        $menus = Menu::with(['steps' => fn($q) => $q->orderBy('step_order', 'asc')])
            ->orderByRaw('CAST(num AS UNSIGNED) ASC, num ASC')
            ->get();

        $libraryData = $menus->map(function ($menu) {
            $steps = $menu->steps->map(function ($step) {
                return [
                    'id' => $step->id,
                    'order' => $step->step_order,
                    'text' => $step->text ?: ($menu->title),
                    'img' => $step->image_url,
                ];
            })->values()->all();

            // Fallback jika menu baru belum memiliki slide
            if (empty($steps)) {
                $steps = [
                    [
                        'id' => 0,
                        'order' => 1,
                        'text' => $menu->desc ?: $menu->title,
                        'img' => asset('templating/img/polibatam.png'),
                    ]
                ];
            }

            return [
                'id' => $menu->id,
                'num' => $menu->num,
                'title' => $menu->title,
                'desc' => $menu->desc ?: '',
                'steps' => $steps,
            ];
        })->values()->all();

        // Data awal Traffic Tank Silinder hari ini
        $today = Carbon::today('Asia/Jakarta');
        $bisaMasuk = VisitorLog::whereDate('created_at', $today)->where('status', 'Bisa Masuk')->count();
        $belumPerpanjang = VisitorLog::whereDate('created_at', $today)->where('status', 'Belum Perpanjang')->count();
        $belumDaftar = VisitorLog::whereDate('created_at', $today)->where('status', 'Belum Daftar')->count();
        $totalVisitors = $bisaMasuk + $belumPerpanjang + $belumDaftar;
        $dailyTarget = 400;
        $capacityPct = $totalVisitors > 0 ? round(($totalVisitors / $dailyTarget) * 100, 1) : 0;

        $pctGreen = $totalVisitors > 0 ? round(($bisaMasuk / $totalVisitors) * 100, 1) : 0;
        $pctGray = $totalVisitors > 0 ? round(($belumPerpanjang / $totalVisitors) * 100, 1) : 0;
        $pctRed = $totalVisitors > 0 ? round(($belumDaftar / $totalVisitors) * 100, 1) : 0;

        $trafficData = [
            'total' => $totalVisitors,
            'capacity_percentage' => $capacityPct,
            'status_counts' => [
                'bisa_masuk' => $bisaMasuk,
                'belum_perpanjang' => $belumPerpanjang,
                'belum_daftar' => $belumDaftar,
            ],
            'status_percentages' => [
                'bisa_masuk' => $pctGreen,
                'belum_perpanjang' => $pctGray,
                'belum_daftar' => $pctRed,
            ],
        ];

        // Data Top 3 Pengunjung Bulan Ini (Leaderboard Gamifikasi "Pahlawan Literasi")
        $currentMonth = Carbon::now('Asia/Jakarta');
        $topVisitors = VisitorLog::select('user_id', DB::raw('count(*) as total_kunjungan'))
            ->whereNotNull('user_id')
            ->whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->where('status', 'Bisa Masuk')
            ->groupBy('user_id')
            ->orderByDesc('total_kunjungan')
            ->take(3)
            ->with('user:id,name,nim')
            ->get()
            ->map(function ($log, $idx) {
                return [
                    'rank' => $idx + 1,
                    'user_id' => $log->user_id,
                    'name' => $log->user ? $log->user->name : 'Mahasiswa',
                    'nim' => $log->user ? ($log->user->nim ?? '-') : '-',
                    'total' => (int) $log->total_kunjungan,
                ];
            });

        $leaderboardData = [
            'month_name' => $currentMonth->translatedFormat('F Y'),
            'top_visitors' => $topVisitors,
        ];

        return view('kiosk.index', compact('libraryData', 'trafficData', 'leaderboardData'));
    }
    
    public function getDemographicStats()
    {
        $currentMonth = Carbon::now('Asia/Jakarta');

        // Base query for this month's visitors
        $baseQuery = VisitorLog::whereMonth('visitor_logs.created_at', $currentMonth->month)
            ->whereYear('visitor_logs.created_at', $currentMonth->year)
            ->where('visitor_logs.status', 'Bisa Masuk')
            ->whereNotNull('visitor_logs.user_id');

        // 1. Gender Ratio
        $genderData = (clone $baseQuery)
            ->join('users', 'visitor_logs.user_id', '=', 'users.id')
            ->select('users.gender', DB::raw('count(*) as total'))
            ->whereNotNull('users.gender')
            ->groupBy('users.gender')
            ->pluck('total', 'gender');
            
        $totalGender = $genderData->sum();
        $male = $genderData->get('L', 0);
        $female = $genderData->get('P', 0);

        $genderRatio = [
            'L' => $male,
            'P' => $female,
            'L_pct' => $totalGender > 0 ? round(($male / $totalGender) * 100, 1) : 0,
            'P_pct' => $totalGender > 0 ? round(($female / $totalGender) * 100, 1) : 0,
        ];

        // 2. Age Groups
        $ages = (clone $baseQuery)
            ->join('users', 'visitor_logs.user_id', '=', 'users.id')
            ->select('users.tanggal_lahir')
            ->whereNotNull('users.tanggal_lahir')
            ->get()
            ->map(function ($log) {
                return Carbon::parse($log->tanggal_lahir)->age;
            });

        $ageGroupsCount = [
            '17-19 Tahun' => 0,
            '20-22 Tahun' => 0,
            '23+ Tahun' => 0,
        ];

        foreach ($ages as $age) {
            if ($age >= 17 && $age <= 19) {
                $ageGroupsCount['17-19 Tahun']++;
            } elseif ($age >= 20 && $age <= 22) {
                $ageGroupsCount['20-22 Tahun']++;
            } elseif ($age >= 23) {
                $ageGroupsCount['23+ Tahun']++;
            }
        }

        arsort($ageGroupsCount);
        $topAgeGroups = collect($ageGroupsCount)->take(3)->map(function ($val, $key) {
            return ['group' => $key, 'total' => $val];
        })->values();

        // 3. Top 3 Region (Kecamatan)
        $topRegions = (clone $baseQuery)
            ->join('users', 'visitor_logs.user_id', '=', 'users.id')
            ->select('users.kecamatan_domisili', DB::raw('count(*) as total'))
            ->whereNotNull('users.kecamatan_domisili')
            ->groupBy('users.kecamatan_domisili')
            ->orderByDesc('total')
            ->take(3)
            ->get();

        return response()->json([
            'month_name' => $currentMonth->translatedFormat('F Y'),
            'gender' => $genderRatio,
            'age_groups' => $topAgeGroups,
            'regions' => $topRegions,
        ]);
    }
}