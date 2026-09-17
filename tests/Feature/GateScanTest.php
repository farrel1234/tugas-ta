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

    public function test_live_traffic_endpoint_returns_aggregated_metrics(): void
    {
        $activeUser = User::factory()->create([
            'name' => 'Farrel Ariq Nadhil',
            'kode_kartu' => '058945125E9200',
            'expired_at' => Carbon::now()->addYear(),
        ]);

        $expiredUser = User::factory()->create([
            'name' => 'Expired User',
            'kode_kartu' => 'EXPIRED_CARD',
            'expired_at' => Carbon::now()->subDays(10),
        ]);

        // 1. Scan active user
        $this->postJson('/api/scan', ['kode_scan' => '058945125E9200']);
        // 2. Scan expired user
        $this->postJson('/api/scan', ['kode_scan' => 'EXPIRED_CARD']);
        // 3. Scan unknown user
        $this->postJson('/api/scan', ['kode_scan' => 'UNKNOWN_RFID']);

        $response = $this->getJson('/api/live-traffic');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'total',
                'capacity_percentage',
                'selected_date',
                'formatted_date',
                'is_today',
                'status_counts' => [
                    'bisa_masuk',
                    'belum_perpanjang',
                    'belum_daftar',
                ],
                'status_percentages' => [
                    'bisa_masuk',
                    'belum_perpanjang',
                    'belum_daftar',
                ],
                'recent_logs',
            ])
            ->assertJson([
                'total' => 3,
                'capacity_percentage' => 0.8, // (3 / 200) * 50 = 0.75 -> round 0.8
                'is_today' => true,
                'status_counts' => [
                    'bisa_masuk' => 1,
                    'belum_perpanjang' => 1,
                    'belum_daftar' => 1,
                ],
            ]);

        $this->assertCount(3, $response->json('recent_logs'));

        // Test filter histori tanggal kemarin (harus 0 karena scan dilakukan hari ini)
        $yesterday = Carbon::yesterday('Asia/Jakarta')->format('Y-m-d');
        $responseYesterday = $this->getJson('/api/live-traffic?date=' . $yesterday);
        $responseYesterday->assertStatus(200)
            ->assertJson([
                'total' => 0,
                'capacity_percentage' => 0,
                'is_today' => false,
            ]);

        // Buat log histori kemarin untuk memastikan kalkulasi kapasitas tangki histori
        VisitorLog::create([
            'scanned_code' => 'HIST_CODE_1',
            'status' => 'Bisa Masuk',
            'created_at' => Carbon::yesterday('Asia/Jakarta')->setTime(8, 0, 0),
        ]);
        VisitorLog::create([
            'scanned_code' => 'HIST_CODE_2',
            'status' => 'Belum Daftar',
            'created_at' => Carbon::yesterday('Asia/Jakarta')->setTime(9, 0, 0),
        ]);

        $responseHistWithData = $this->getJson('/api/live-traffic?date=' . $yesterday);
        $responseHistWithData->assertStatus(200)
            ->assertJson([
                'total' => 2,
                'capacity_percentage' => 0.5, // (2 / 200) * 50 = 0.5%
                'is_today' => false,
                'status_counts' => [
                    'bisa_masuk' => 1,
                    'belum_daftar' => 1,
                ],
                'status_percentages' => [
                    'bisa_masuk' => 50.0,
                    'belum_daftar' => 50.0,
                ],
            ]);
    }
}
