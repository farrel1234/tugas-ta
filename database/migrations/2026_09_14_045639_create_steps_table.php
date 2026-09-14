<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            // Foreign key yang menyambung ke tabel menus.
            // cascadeOnDelete() artinya kalau menu dihapus, slidenya ikut terhapus.
            $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();
            $table->string('image_path'); // Lokasi file gambar
            $table->integer('step_order'); // Urutan slide
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steps');
    }
};
