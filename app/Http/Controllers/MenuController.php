<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Step;
use App\Models\VisitorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    /**
     * Helper: Menyimpan file gambar slide secara aman dengan nama unik dan ekstensi huruf kecil (lowercase).
     */
    private function saveUploadedSlide($file): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'png');
        $filename = Str::random(40) . '.' . $extension;

        // Pastikan folder penyimpanan fisik storage/app/public/steps ada
        $stepsDir = storage_path('app/public/steps');
        File::ensureDirectoryExists($stepsDir);

        $file->storeAs('steps', $filename, 'public');
        return 'steps/' . $filename;
    }

    /**
     * Menampilkan daftar menu pada Dashboard Admin (aktif dan sampah).
     * Diurutkan secara numerik alami (1, 2, ..., 10).
     */
    public function index()
    {
        $menus = Menu::with('steps')->orderByRaw('CAST(num AS UNSIGNED) ASC, num ASC')->get();
        $trashedMenus = Menu::onlyTrashed()->with('steps')->orderByRaw('CAST(num AS UNSIGNED) ASC, num ASC')->get();
        $totalMenus = $menus->count();
        $totalSteps = Step::whereHas('menu')->count();
        $recentLogs = VisitorLog::with('user')->latest()->take(100)->get();
        $totalVisitorsToday = VisitorLog::whereDate('created_at', today())->count();

        return view('admin.dashboard', compact('menus', 'trashedMenus', 'totalMenus', 'totalSteps', 'recentLogs', 'totalVisitorsToday'));
    }

    /**
     * Menyimpan Menu baru (dan opsional slide pertamanya) dalam Database Transaction.
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

        $menu = DB::transaction(function () use ($request, $validated) {
            $menu = Menu::create([
                'user_id' => auth()->id(),
                'num' => $validated['num'],
                'title' => $validated['title'],
                'desc' => $validated['desc'] ?? null,
            ]);

            // Jika user mengunggah gambar saat membuat menu
            if ($request->hasFile('image')) {
                $imagePath = $this->saveUploadedSlide($request->file('image'));

                $menu->steps()->create([
                    'step_order' => 1,
                    'image_path' => $imagePath,
                    'text' => $validated['text'] ?? $validated['title'],
                ]);
            }

            return $menu;
        });

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

        DB::transaction(function () use ($request, $menu, $validated) {
            $menu->update([
                'num' => $validated['num'],
                'title' => $validated['title'],
                'desc' => $validated['desc'] ?? null,
            ]);

            // Jika mengunggah slide tambahan
            if ($request->hasFile('image')) {
                $imagePath = $this->saveUploadedSlide($request->file('image'));
                $nextOrder = ($menu->steps()->max('step_order') ?? 0) + 1;

                $menu->steps()->create([
                    'step_order' => $nextOrder,
                    'image_path' => $imagePath,
                    'text' => $validated['text'] ?? "Langkah {$nextOrder}",
                ]);
            }
        });

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

        DB::transaction(function () use ($request, $menu, $validated) {
            $imagePath = $this->saveUploadedSlide($request->file('image'));
            $order = $validated['step_order'] ?? (($menu->steps()->max('step_order') ?? 0) + 1);

            $menu->steps()->create([
                'step_order' => $order,
                'image_path' => $imagePath,
                'text' => $validated['text'] ?? "Langkah {$order}",
            ]);
        });

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

    /**
     * Memperbarui slide/langkah tertentu (mengganti gambar atau teks).
     */
    public function updateStep(Request $request, Step $step)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'text' => 'nullable|string',
            'step_order' => 'nullable|integer',
        ], [
            'image.image' => 'File harus berupa format gambar.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        DB::transaction(function () use ($request, $step, $validated) {
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika tersimpan di disk public
                if ($step->image_path && Storage::disk('public')->exists($step->image_path)) {
                    Storage::disk('public')->delete($step->image_path);
                }
                $step->image_path = $this->saveUploadedSlide($request->file('image'));
            }

            if ($request->has('text')) {
                $step->text = $validated['text'];
            }

            if ($request->filled('step_order')) {
                $step->step_order = $validated['step_order'];
            }

            $step->save();
        });

        return back()->with('success', 'Slide berhasil diperbarui!');
    }
}