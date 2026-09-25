<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambahkan kolom 'role' pada tabel users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('mahasiswa')->after('name');
            }
        });

        // Tandai role 'admin' jika email mengandung admin
        DB::table('users')
            ->where('email', 'like', '%admin%')
            ->update(['role' => 'admin']);

        // 2. Tambahkan kolom 'user_id' pada tabel menus berelasi ke users.id
        Schema::table('menus', function (Blueprint $table) {
            if (!Schema::hasColumn('menus', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }
        });

        // 3. Kaitkan menu yang sudah ada ke admin pertama
        $firstAdminId = DB::table('users')->where('role', 'admin')->value('id')
            ?? DB::table('users')->value('id');

        if ($firstAdminId) {
            DB::table('menus')->whereNull('user_id')->update(['user_id' => $firstAdminId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            if (Schema::hasColumn('menus', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
