<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\VisitorLog;
use Carbon\Carbon;

class KioskController extends Controller
{
    /**
     * Menampilkan halaman utama Kiosk / Digital Signage dengan data dinamis dari database.
     */
    public function index()
    {
        $menus = Menu::with('steps')->orderBy('num', 'asc')->get();

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

        return view('kiosk.index', compact('libraryData', 'trafficData'));
    }
}