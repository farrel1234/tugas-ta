<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\VisitorLog;
use App\Models\User;
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
            ->orderByNaturalNum()
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

        // Data Demografi Pemustaka dari Database Lokal (tabel users)
        $demographicData = $this->getDemographicDataArray();

        return view('kiosk.index', compact('libraryData', 'trafficData', 'demographicData'));
    }

    /**
     * Mengambil data analitik demografi pemustaka dari database lokal.
     */
    public function getDemographicStats()
    {
        return response()->json($this->getDemographicDataArray());
    }

    /**
     * Membangun payload statistik demografi pengunjung unik gerbang hari ini (Opsi B).
     */
    private function getDemographicDataArray(): array
    {
        $today = Carbon::today('Asia/Jakarta');

        // Ambil ID pengguna unik dari orang yang berhasil scan/tap gerbang hari ini
        $uniqueUserIds = VisitorLog::whereDate('created_at', $today)
            ->where('status', 'Bisa Masuk')
            ->whereNotNull('user_id')
            ->distinct()
            ->pluck('user_id');

        $totalUniqueVisitors = $uniqueUserIds->count();

        // Ambil data profil pengunjung unik dari database
        $visitors = User::whereIn('id', $uniqueUserIds)->get();

        // 1. Rasio Gender Hari Ini (Pengunjung Unik)
        $maleCount = $visitors->where('gender', 'L')->count();
        $femaleCount = $visitors->where('gender', 'P')->count();
        $malePct = $totalUniqueVisitors > 0 ? round(($maleCount / $totalUniqueVisitors) * 100, 1) : 0;
        $femalePct = $totalUniqueVisitors > 0 ? round(($femaleCount / $totalUniqueVisitors) * 100, 1) : 0;

        $genderRatio = [
            'L' => $maleCount,
            'P' => $femaleCount,
            'L_pct' => $malePct,
            'P_pct' => $femalePct,
            'total' => $totalUniqueVisitors,
        ];

        // 2. Kelompok Umur Hari Ini
        $ageGroupsCount = [
            '20-22 Tahun' => 0,
            '17-19 Tahun' => 0,
            '23+ Tahun' => 0,
        ];

        foreach ($visitors as $v) {
            if ($v->tanggal_lahir) {
                $age = Carbon::parse($v->tanggal_lahir)->age;
                if ($age >= 17 && $age <= 19) {
                    $ageGroupsCount['17-19 Tahun']++;
                } elseif ($age >= 20 && $age <= 22) {
                    $ageGroupsCount['20-22 Tahun']++;
                } elseif ($age >= 23) {
                    $ageGroupsCount['23+ Tahun']++;
                }
            }
        }

        arsort($ageGroupsCount);
        $topAgeGroups = collect($ageGroupsCount)->take(3)->map(function ($val, $key) {
            return ['group' => $key, 'total' => $val];
        })->values()->all();

        // 3. Top 3 Wilayah Asal Pengunjung Hari Ini
        $topRegions = $visitors->whereNotNull('kecamatan_domisili')
            ->groupBy('kecamatan_domisili')
            ->map(function ($group, $kecamatan) {
                return [
                    'kecamatan_domisili' => $kecamatan,
                    'total' => $group->count(),
                ];
            })
            ->sortByDesc('total')
            ->take(3)
            ->values()
            ->all();

        $badgeText = $totalUniqueVisitors > 0 ? $totalUniqueVisitors . ' Pengunjung' : 'Live Hari Ini';

        return [
            'month_name' => $badgeText,
            'badge' => $badgeText,
            'total_members' => $totalUniqueVisitors,
            'gender' => $genderRatio,
            'age_groups' => $topAgeGroups,
            'regions' => $topRegions,
        ];
    }
}