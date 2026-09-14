<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Menu;
use App\Models\Step;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Admin jika belum ada
        User::updateOrCreate(
            ['email' => 'admin@polibatam.ac.id'],
            [
                'name' => 'Admin Perpustakaan',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. Data Menu dan Step Awal
        $menusData = [
            [
                'num' => '01',
                'title' => 'Layanan Sirkulasi Mandiri',
                'desc' => 'Layanan sirkulasi mencakup peminjaman, pengembalian, dan perpanjangan masa pinjam koleksi perpustakaan.',
                'steps' => [
                    ['step_order' => 1, 'image_path' => 'templating/img/0.png', 'text' => 'Masuk ke Perpustakaan Polibatam.'],
                    ['step_order' => 2, 'image_path' => 'templating/img/1.png', 'text' => 'Cari data bibliografi melalui katalog komputer (OPAC).'],
                    ['step_order' => 3, 'image_path' => 'templating/img/2.png', 'text' => 'Cari bahan pustaka tersebut pada ruang koleksi.'],
                    ['step_order' => 4, 'image_path' => 'templating/img/4.png', 'text' => 'Meminjam buku di meja layanan mandiri dengan kartu anggota.'],
                    ['step_order' => 5, 'image_path' => 'templating/img/qrpeminjaman.png', 'text' => 'Panduan peminjaman lengkap dapat dilihat pada link: https://lib.polibatam.ac.id/panduan/panduan-peminjaman/ atau scan QR Code di atas menggunakan kamera HP Anda.'],
                    ['step_order' => 6, 'image_path' => 'templating/img/5.png', 'text' => 'Kembalikan buku di meja layanan mandiri dengan kartu anggota masing-masing.'],
                    ['step_order' => 7, 'image_path' => 'templating/img/6.png', 'text' => 'Tempatkan buku ke keranjang pengembalian buku yang tersedia.'],
                    ['step_order' => 8, 'image_path' => 'templating/img/7.png', 'text' => 'Bila terdapat denda, hubungi staf perpustakaan dan bayar sesuai jumlah denda.'],
                    ['step_order' => 9, 'image_path' => 'templating/img/qrpengembalian.png', 'text' => 'Panduan pengembalian lengkap dapat dilihat pada link: https://lib.polibatam.ac.id/panduan/panduan-pengembalian/ atau scan QR Code di atas menggunakan kamera HP Anda.'],
                    ['step_order' => 10, 'image_path' => 'templating/img/8.png', 'text' => 'Peminjam dapat memperpanjang buku di halaman member aplikasi perpustakaan (online).'],
                    ['step_order' => 11, 'image_path' => 'templating/img/9.png', 'text' => 'Atau memperpanjang buku di meja layanan mandiri dengan kartu anggota masing-masing.'],
                ]
            ],
            [
                'num' => '02',
                'title' => 'Dropbox',
                'desc' => 'Fasilitas kotak drop pengembalian buku praktis di luar jam layanan utama.',
                'steps' => [
                    ['step_order' => 1, 'image_path' => 'templating/img/dropbox-1.png', 'text' => 'Masukkan buku yang ingin dikembalikan ke dalam fasilitas dropbox.'],
                    ['step_order' => 2, 'image_path' => 'templating/img/dropbox-2.png', 'text' => 'Pastikan buku masuk dengan aman ke dalam kotak drop.'],
                    ['step_order' => 3, 'image_path' => 'templating/img/dropbox-3.png', 'text' => 'Petugas akan memproses pengembalian secara berkala.'],
                ]
            ],
            [
                'num' => '03',
                'title' => 'Stock Opname',
                'desc' => 'Proses audit dan pendataan ulang inventaris koleksi buku perpustakaan tahunan.',
                'steps' => [
                    ['step_order' => 1, 'image_path' => 'templating/img/15.png', 'text' => 'Perhatikan pengumuman resmi jadwal pelaksanaan stock opname di website perpustakaan polibatam.'],
                    ['step_order' => 2, 'image_path' => 'templating/img/16.png', 'text' => 'Layanan peminjaman fisik ditutup sementara selama audit.'],
                    ['step_order' => 3, 'image_path' => 'templating/img/17.png', 'text' => 'Petugas melakukan pemindaian barcode seluruh koleksi.'],
                ]
            ],
            [
                'num' => '04',
                'title' => 'Layanan Referensi',
                'desc' => 'Koleksi digital yang sudah kita sediakan termasuk e-journal dan e-majalah.',
                'steps' => [
                    ['step_order' => 1, 'image_path' => 'templating/img/19.png', 'text' => 'Buku Digital (E-Book): Menyediakan koleksi buku digital akademik lengkap dari berbagai disiplin ilmu untuk menunjang materi perkuliahan.'],
                    ['step_order' => 2, 'image_path' => 'templating/img/20.png', 'text' => 'E-Jurnal: Menyediakan akses e-jurnal nasional maupun internasional terkemuka (seperti IEEE, Emerald) untuk memperkaya referensi riset dan tugas akhir.'],
                    ['step_order' => 3, 'image_path' => 'templating/img/21.png', 'text' => 'Majalah Digital: Menyediakan berbagai koleksi majalah digital pilihan guna memperluas wawasan umum dan berita industri terkini.'],
                    ['step_order' => 2, 'image_path' => 'templating/img/22.png', 'text' => 'Database Ilmiah: Menyediakan akses ke database ilmiah dan repositori institusi yang memuat data riset serta dokumen ilmiah.'],
                    ['step_order' => 5, 'image_path' => 'templating/img/23.png', 'text' => 'Tools Referensi: Menyediakan berbagai tools referensi seperti bantuan sitasi otomatis dan fasilitas pengecekan plagiasi untuk penulisan karya ilmiah.'],
                ]
            ],
            [
                'num' => '05',
                'title' => 'Website Perpus',
                'desc' => 'Portal resmi informasi, layanan online, dan akses katalog digital perpustakaan.',
                'steps' => [
                    ['step_order' => 1, 'image_path' => 'templating/img/11.png', 'text' => 'Buka peramban web (browser) seperti Google Chrome atau Safari pada perangkat.'],
                    ['step_order' => 2, 'image_path' => 'templating/img/12.png', 'text' => 'Masukkan alamat URL resmi perpustakaan yaitu https://lib.polibatam.ac.id pada bilah alamat.'],
                    ['step_order' => 3, 'image_path' => 'templating/img/14.png', 'text' => 'Tekan Enter dan halaman utama website UPA Perpustakaan Polibatam siap diakses.'],
                ]
            ],
            [
                'num' => '06',
                'title' => 'Koleksi Perpus',
                'desc' => 'Akses koleksi pustaka lengkap untuk kebutuhan riset dan pengembangan proyek.',
                'steps' => [
                    ['step_order' => 1, 'image_path' => 'templating/img/2.png', 'text' => 'Koleksi Cetak: Temukan buku teks dan referensi di rak perpustakaan. Pastikan mencatat nomor panggil dari katalog OPAC.'],
                    ['step_order' => 2, 'image_path' => 'templating/img/25.png', 'text' => 'Koleksi Digital: Akses ribuan e-book dan materi digital kapan saja via portal perpustakaan menggunakan akun mahasiswa.'],
                    ['step_order' => 3, 'image_path' => 'templating/img/26.png', 'text' => 'Support PBL: Tersedia manual teknis, standar industri, dan laporan riset mahasiswa terdahulu.'],
                ]
            ],
            [
                'num' => '07',
                'title' => 'Sharing Session',
                'desc' => 'Program sharing session literasi informasi rutin di Perpustakaan Polibatam.',
                'steps' => [
                    ['step_order' => 1, 'image_path' => 'templating/img/24.png', 'text' => 'Program ini hadir untuk meningkatkan literasi akademik. Silakan daftarkan diri Anda melalui portal.'],
                ]
            ],
            [
                'num' => '08',
                'title' => 'Layanan Disabilitas',
                'desc' => 'Ruang privasi nyaman dan edukatif bagi pemustaka berkebutuhan khusus.',
                'steps' => [
                    ['step_order' => 1, 'image_path' => 'templating/img/disabilitas.jpeg', 'text' => 'Layanan disabilitas dilengkapi quiet room (ruang tenang) privat, perangkat akses literasi digital, serta panduan Abjad Isyarat SIBI.'],
                ]
            ],
            [
                'num' => '09',
                'title' => 'Kuesioner Layanan',
                'desc' => 'Masukan Anda sangat berharga untuk peningkatan kualitas layanan perpustakaan.',
                'steps' => [
                    ['step_order' => 1, 'image_path' => 'templating/img/kuisioner.png', 'text' => 'Silakan pindai (scan) QR Code di atas menggunakan kamera HP Anda untuk mengisi survei kepuasan layanan UPA Perpustakaan Polibatam. Terima kasih atas partisipasi Anda!'],
                ]
            ],
        ];

        foreach ($menusData as $m) {
            $menu = Menu::updateOrCreate(
                ['num' => $m['num']],
                [
                    'title' => $m['title'],
                    'desc' => $m['desc'],
                ]
            );

            // Simpan steps jika belum ada
            if ($menu->steps()->count() === 0) {
                foreach ($m['steps'] as $s) {
                    $menu->steps()->create([
                        'step_order' => $s['step_order'],
                        'image_path' => $s['image_path'],
                        'text' => $s['text'],
                    ]);
                }
            }
        }
    }
}
