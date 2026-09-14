<?php

namespace App\Http\Controllers;

use App\Models\Menu;

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

        return view('kiosk.index', compact('libraryData'));
    }
}