<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Step;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\VisitorLog;

class MenuController extends Controller
{
    /**
     * Menampilkan daftar menu pada Dashboard Admin (aktif dan sampah).
     */
    public function index()
    {
        $menus = Menu::with('steps')->orderBy('num', 'asc')->get();
        $trashedMenus = Menu::onlyTrashed()->with('steps')->orderBy('deleted_at', 'desc')->get();
        $totalMenus = $menus->count();
        $totalSteps = Step::whereHas('menu')->count();
        $recentLogs = VisitorLog::with('user')->latest()->take(10)->get();
        $totalVisitorsToday = VisitorLog::whereDate('created_at', today())->count();

        return view('admin.dashboard', compact('menus', 'trashedMenus', 'totalMenus', 'totalSteps', 'recentLogs', 'totalVisitorsToday'));
    }

    /**
     * Menyimpan Menu baru (dan opsional slide pertamanya).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'num' => 'required|string|max:10',
            'title' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'text' => 'nullable|string',
        ], [
            'num.required' => 'Nomor urut menu wajib diisi.',
            'title.required' => 'Judul menu layanan wajib diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        $menu = Menu::create([
            'num' => $validated['num'],
            'title' => $validated['title'],
            'desc' => $validated['desc'] ?? null,
        ]);

        // Jika user mengunggah gambar saat membuat menu
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('steps', 'public');

            $menu->steps()->create([
                'step_order' => 1,
                'image_path' => $imagePath,
                'text' => $validated['text'] ?? $validated['title'],
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', "Menu '{$menu->title}' berhasil ditambahkan!");
    }

    /**
     * Menampilkan form edit menu beserta daftar slide/langkahnya.
     */
    public function edit(Menu $menu)
    {
        $menu->load('steps');
        return view('admin.edit', compact('menu'));
    }

    /**
     * Memperbarui data Menu.
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'num' => 'required|string|max:10',
            'title' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'text' => 'nullable|string',
        ]);

        $menu->update([
            'num' => $validated['num'],
            'title' => $validated['title'],
            'desc' => $validated['desc'] ?? null,
        ]);

        // Jika mengunggah slide tambahan
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('steps', 'public');
            $nextOrder = ($menu->steps()->max('step_order') ?? 0) + 1;

            $menu->steps()->create([
                'step_order' => $nextOrder,
                'image_path' => $imagePath,
                'text' => $validated['text'] ?? "Langkah {$nextOrder}",
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', "Data Menu '{$menu->title}' berhasil diperbarui!");
    }

    /**
     * Memindahkan Menu ke Tempat Sampah (Soft Delete - Bisa Dipulihkan).
     */
    public function destroy(Menu $menu)
    {
        $title = $menu->title;
        $menu->delete(); // Soft delete: data tersimpan di Tempat Sampah

        return redirect()->route('admin.dashboard')->with('success', "Menu '{$title}' berhasil dipindahkan ke Tempat Sampah. Anda dapat memulihkannya kembali kapan saja!");
    }

    /**
     * Memulihkan Menu dari Tempat Sampah.
     */
    public function restore($id)
    {
        $menu = Menu::onlyTrashed()->findOrFail($id);
        $menu->restore();

        return redirect()->route('admin.dashboard')->with('success', "Menu '{$menu->title}' berhasil dipulihkan kembali!");
    }

    /**
     * Menghapus Menu secara permanen beserta seluruh file gambarnya.
     */
    public function forceDelete($id)
    {
        $menu = Menu::onlyTrashed()->with('steps')->findOrFail($id);

        foreach ($menu->steps as $step) {
            if ($step->image_path && Storage::disk('public')->exists($step->image_path)) {
                Storage::disk('public')->delete($step->image_path);
            }
        }

        $title = $menu->title;
        $menu->forceDelete();

        return redirect()->route('admin.dashboard')->with('success', "Menu '{$title}' dan seluruh gambarnya telah dihapus secara permanen.");
    }

    /**
     * Menambahkan Step/Slide baru ke dalam menu yang dipilih.
     */
    public function storeStep(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'text' => 'nullable|string',
            'step_order' => 'nullable|integer',
        ], [
            'image.required' => 'Gambar slide wajib dipilih.',
            'image.image' => 'File harus berupa format gambar.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        $imagePath = $request->file('image')->store('steps', 'public');
        $order = $validated['step_order'] ?? (($menu->steps()->max('step_order') ?? 0) + 1);

        $menu->steps()->create([
            'step_order' => $order,
            'image_path' => $imagePath,
            'text' => $validated['text'] ?? "Langkah {$order}",
        ]);

        return back()->with('success', 'Slide baru berhasil ditambahkan ke menu ini!');
    }

    /**
     * Menghapus satu slide spesifik.
     */
    public function destroyStep(Step $step)
    {
        if ($step->image_path && Storage::disk('public')->exists($step->image_path)) {
            Storage::disk('public')->delete($step->image_path);
        }

        $step->delete();

        return back()->with('success', 'Slide berhasil dihapus.');
    }
}