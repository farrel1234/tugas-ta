<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membersihkan dan menstandarisasi seluruh path gambar pada tabel steps.
     */
    public function up(): void
    {
        $steps = DB::table('steps')->get();

        foreach ($steps as $step) {
            if (!$step->image_path) {
                continue;
            }

            $clean = str_replace('\\', '/', trim($step->image_path));
            $clean = ltrim($clean, '/');
            $clean = preg_replace('/^(storage\/app\/public\/|public\/storage\/|storage\/|public\/)+/i', '', $clean);
            $clean = ltrim($clean, '/');

            // Seragamkan ekstensi ke lowercase
            $clean = preg_replace_callback('/\.(jpe?g|png|webp|gif|svg)$/i', function ($matches) {
                return '.' . strtolower($matches[1]);
            }, $clean);

            if ($clean !== $step->image_path) {
                DB::table('steps')->where('id', $step->id)->update([
                    'image_path' => $clean,
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Standarisasi path bersifat idempotens dan tidak perlu dibalikkan.
    }
};
