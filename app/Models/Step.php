<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;

    protected $fillable = ['menu_id', 'image_path', 'step_order', 'text'];

    protected $appends = ['image_url'];

    // Relasi kebalikan: 1 Step (Slide) ini milik 1 Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Mutator: Menyeragamkan path penyimpanan gambar di database.
     * Membersihkan prefix redundant (public/, storage/) dan menyeragamkan ekstensi ke huruf kecil.
     */
    public function setImagePathAttribute($value)
    {
        if (!$value) {
            $this->attributes['image_path'] = null;
            return;
        }

        // 1. Seragamkan separator ke forward slash dan buang spasi / slash di awal
        $clean = str_replace('\\', '/', trim($value));
        $clean = ltrim($clean, '/');

        // 2. Bersihkan prefix redundant seperti 'public/', 'storage/', 'storage/app/public/'
        $clean = preg_replace('/^(storage\/app\/public\/|public\/storage\/|storage\/|public\/)+/i', '', $clean);
        $clean = ltrim($clean, '/');

        // 3. Seragamkan ekstensi file menjadi huruf kecil (lowercase)
        $clean = preg_replace_callback('/\.(jpe?g|png|webp|gif|svg)$/i', function ($matches) {
            return '.' . strtolower($matches[1]);
        }, $clean);

        $this->attributes['image_path'] = $clean;
    }

    /**
     * Accessor: Menghasilkan URL gambar yang siap dirender oleh browser.
     * Memverifikasi keberadaan fisik file dan mencegah broken image (404).
     */
    public function getImageUrlAttribute(): string
    {
        if (!$this->image_path) {
            return asset('templating/img/polibatam.png');
        }

        // Jika URL eksternal absolut
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        // Normalisasi path lokal
        $clean = str_replace('\\', '/', trim($this->image_path));
        $clean = ltrim($clean, '/');
        $clean = preg_replace('/^(storage\/app\/public\/|public\/storage\/|storage\/|public\/)+/i', '', $clean);
        $clean = ltrim($clean, '/');

        // 1. Cek file aset bawaan template di public/
        if (str_starts_with($clean, 'templating/')) {
            if (file_exists(public_path($clean))) {
                return asset($clean);
            }
        }

        // 2. Cek file fisik di disk storage/app/public atau public/storage
        $publicFile = public_path('storage/' . $clean);
        $storageFile = storage_path('app/public/' . $clean);

        if (file_exists($publicFile) || file_exists($storageFile)) {
            return asset('storage/' . $clean);
        }

        // 3. Fallback pencarian case-insensitive jika ada perbedaan huruf besar/kecil di disk
        $dir = dirname($storageFile);
        $baseName = basename($clean);
        if (is_dir($dir)) {
            $files = scandir($dir);
            if ($files !== false) {
                foreach ($files as $file) {
                    if (strcasecmp($file, $baseName) === 0) {
                        $matchedSubDir = dirname($clean);
                        $matchedPath = ($matchedSubDir !== '.' ? $matchedSubDir . '/' : '') . $file;
                        return asset('storage/' . $matchedPath);
                    }
                }
            }
        }

        // 4. Fallback default aman jika file fisik benar-benar tidak ditemukan (mencegah broken image)
        return asset('templating/img/polibatam.png');
    }
}
