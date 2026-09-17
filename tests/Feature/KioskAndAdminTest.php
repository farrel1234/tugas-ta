<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Step;
use App\Models\User;
use App\Models\VisitorLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KioskAndAdminTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prioritas 1: Verifikasi Model dan Relasi Menu - Step
     */
    public function test_menu_and_step_relationship(): void
    {
        $menu = Menu::create([
            'num' => '01',
            'title' => 'Layanan Sirkulasi Mandiri',
            'desc' => 'Deskripsi sirkulasi',
        ]);

        $step1 = $menu->steps()->create([
            'step_order' => 1,
            'image_path' => 'steps/slide1.png',
            'text' => 'Langkah 1',
        ]);

        $this->assertCount(1, $menu->steps);
        $this->assertEquals($menu->id, $step1->menu->id);
    }

    /**
     * Prioritas 2: Proteksi Halaman Admin & Autentikasi Login/Logout
     */
    public function test_unauthenticated_user_cannot_access_admin(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_user_can_login_and_access_admin(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@polibatam.ac.id',
            'password' => bcrypt('admin123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@polibatam.ac.id',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);

        $adminResponse = $this->get('/admin');
        $adminResponse->assertStatus(200);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     * Prioritas 3: CRUD Menu & Upload Slide
     */
    public function test_admin_can_create_menu_with_slide(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $image = UploadedFile::fake()->image('slide.png');

        $response = $this->actingAs($user)->post('/admin/menus', [
            'num' => '02',
            'title' => 'Layanan Dropbox',
            'desc' => 'Pengembalian 24 jam',
            'image' => $image,
            'text' => 'Penjelasan dropbox',
        ]);

        $response->assertRedirect('/admin');
        $this->assertDatabaseHas('menus', ['num' => '02', 'title' => 'Layanan Dropbox']);
        $this->assertDatabaseHas('steps', ['text' => 'Penjelasan dropbox']);
    }

    public function test_admin_can_update_menu(): void
    {
        $user = User::factory()->create();
        $menu = Menu::create(['num' => '03', 'title' => 'Menu Lama', 'desc' => 'Desc Lama']);

        $response = $this->actingAs($user)->put("/admin/menus/{$menu->id}", [
            'num' => '03',
            'title' => 'Menu Baru Diupdate',
            'desc' => 'Desc Baru',
        ]);

        $response->assertRedirect('/admin');
        $this->assertDatabaseHas('menus', ['title' => 'Menu Baru Diupdate']);
    }

    public function test_admin_can_soft_delete_restore_and_force_delete_menu(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $menu = Menu::create(['num' => '04', 'title' => 'Menu Dihapus']);
        $step = $menu->steps()->create(['step_order' => 1, 'image_path' => 'steps/test.png']);

        // 1. Soft delete (Pindahkan ke Tempat Sampah)
        $response = $this->actingAs($user)->delete("/admin/menus/{$menu->id}");
        $response->assertRedirect('/admin');
        $this->assertSoftDeleted('menus', ['id' => $menu->id]);

        // 2. Pulihkan menu dari Tempat Sampah
        $responseRestore = $this->actingAs($user)->post("/admin/menus/{$menu->id}/restore");
        $responseRestore->assertRedirect('/admin');
        $this->assertNotSoftDeleted('menus', ['id' => $menu->id]);

        // 3. Hapus permanen
        $this->actingAs($user)->delete("/admin/menus/{$menu->id}");
        $responseForce = $this->actingAs($user)->delete("/admin/menus/{$menu->id}/force-delete");
        $responseForce->assertRedirect('/admin');
        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    }

    /**
     * Prioritas 4: Tampilan Utama Kiosk menampilkan data dinamis
     */
    public function test_kiosk_displays_menu_data(): void
    {
        $menu = Menu::create([
            'num' => '01',
            'title' => 'Digital Signage Kiosk Polibatam',
            'desc' => 'Kiosk info',
        ]);
        $menu->steps()->create([
            'step_order' => 1,
            'image_path' => 'templating/img/0.png',
            'text' => 'Langkah Pertama Kiosk',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Digital Signage Kiosk Polibatam');
        $response->assertSee('kiosk-traffic-tank');
        $response->assertSee('Traffic Tank Silinder');
        $response->assertSee('traffic-tank-liquid');
        $response->assertSee('Bisa Masuk');
        $response->assertSee('Belum Daftar');
    }

    public function test_admin_dashboard_has_uji_scanner_modal_and_button(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Riwayat Scan Kunjungan Gerbang');
        $response->assertSee('open-scanner-modal-btn');
        $response->assertSee('Uji Scanner');
        $response->assertDontSee('Tap Kartu / Uji Scanner');
        $response->assertSee('scannerTestModal');
        $response->assertSee('3312001001');
    }

    public function test_admin_dashboard_has_table_scroll_containers_and_no_scroll_buttons(): void
    {
        $user = User::factory()->create();
        VisitorLog::create([
            'user_id' => $user->id,
            'scanned_code' => 'TEST_CARD',
            'status' => 'Bisa Masuk',
        ]);

        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('menu-table-scroll');
        $response->assertSee('custom-table-scrollbar');
        $response->assertSee('visitor-logs-scroll');
        $response->assertDontSee('scrollTable');
        $response->assertDontSee('Scroll:');
    }

    public function test_kiosk_does_not_have_manual_test_modal_but_retains_hardware_scan_popup(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertDontSee('open-scanner-modal-btn');
        $response->assertDontSee('scannerTestModal');
        $response->assertSee('scanPopup');
    }

    public function test_admin_dashboard_has_live_gate_traffic_cylinder_and_command_center_console(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('live-traffic-section');
        $response->assertSee('Live Gate Traffic');
        $response->assertSee('Traffic Tank Silinder');
        $response->assertSee('traffic-tank-liquid');
        $response->assertSee('terminal-body-container');
        $response->assertSee('/api/live-traffic');
        $response->assertDontSee('btn-clear-terminal');
        $response->assertDontSee('admin@library-gate:~$');
        $response->assertDontSee('GATE-TELEMETRY-STREAM');
        $response->assertDontSee('[GATE-01]');
        $response->assertDontSee('Listening socket');
        $response->assertDontSee('Belum Terdaftar');
        $response->assertSee('Belum Daftar');
        $response->assertDontSee('btn-toggle-autoscroll');
        $response->assertDontSee('Target Harian:');
        $response->assertSee('btn-prev-day');
        $response->assertSee('traffic-date-picker');
        $response->assertSee('btn-today-live');
        $response->assertSee('tank-mode-badge');
        $response->assertSee('terminal-mode-label');
        $response->assertSee('tank-layer-red');
        $response->assertSee('tank-layer-gray');
        $response->assertSee('tank-layer-green');
        $response->assertSee('traffic-tank-count-hud');
        $response->assertSee('traffic-tank-percent-hud');
        $response->assertDontSee('0 / 100 Kunjungan');
    }
}