<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\VisitorLog;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GateScanTest extends TestCase
{
    use RefreshDatabase;

    public function test_scan_active_user_returns_green_and_saves_log(): void
    {
        $user = User::factory()->create([
            'name' => 'Siswa Aktif',
            'kode_kartu' => 'KARTU_AKTIF_01',
            'expired_at' => Carbon::now()->addYear(),
        ]);

        $response = $this->postJson('/api/scan', [
            'kode_scan' => 'KARTU_AKTIF_01',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'color' => 'green',
                'name' => 'Siswa Aktif',
                'message' => 'Silakan Masuk',
            ]);

        $this->assertDatabaseHas('visitor_logs', [
            'user_id' => $user->id,
            'scanned_code' => 'KARTU_AKTIF_01',
            'status' => 'Bisa Masuk',
        ]);
    }

    public function test_scan_expired_user_returns_gray_and_saves_log(): void
    {
        $user = User::factory()->create([
            'name' => 'Siswa Expired',
            'kode_kartu' => 'KARTU_EXPIRED_01',
            'expired_at' => Carbon::now()->subMonths(2),
        ]);

        $response = $this->postJson('/api/scan', [
            'kode_scan' => 'KARTU_EXPIRED_01',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'color' => 'gray',
                'name' => 'Siswa Expired',
                'message' => 'Belum Perpanjang',
            ]);

        $this->assertDatabaseHas('visitor_logs', [
            'user_id' => $user->id,
            'scanned_code' => 'KARTU_EXPIRED_01',
            'status' => 'Belum Perpanjang',
        ]);
    }

    public function test_scan_unregistered_code_returns_red_and_saves_log(): void
    {
        $unknownCode = 'KARTU_UNKNOWN_999';

        $response = $this->postJson('/api/scan', [
            'kode_scan' => $unknownCode,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'color' => 'red',
                'name' => 'Tidak Dikenal',
                'message' => 'Belum Terdaftar',
            ]);

        $this->assertDatabaseHas('visitor_logs', [
            'user_id' => null,
            'scanned_code' => $unknownCode,
            'status' => 'Belum Daftar',
        ]);
    }

    public function test_scan_user_by_card_id_or_nim(): void
    {
        $user = User::factory()->create([
            'name' => 'Farrel Ariq Nadhil',
            'nim' => '4342301035',
            'email' => 'farrelariq3@gmail.com',
            'kode_kartu' => '058945125E9200',
            'expired_at' => Carbon::now()->addYear(),
        ]);

        // Scan dengan kode kartu (RFID)
        $responseCard = $this->postJson('/api/scan', [
            'kode_scan' => '058945125E9200',
        ]);
        $responseCard->assertStatus(200)
            ->assertJson([
                'color' => 'green',
                'name' => 'Farrel Ariq Nadhil',
                'message' => 'Silakan Masuk',
            ]);

        // Scan dengan NIM
        $responseNim = $this->postJson('/api/scan', [
            'kode_scan' => '4342301035',
        ]);
        $responseNim->assertStatus(200)
            ->assertJson([
                'color' => 'green',
                'name' => 'Farrel Ariq Nadhil',
                'message' => 'Silakan Masuk',
            ]);
    }
}
