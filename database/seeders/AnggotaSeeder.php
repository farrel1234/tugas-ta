<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['4342301035', 'Farrel Ariq Nadhil', 'Laki-laki', 21, 'Batam Kota', 'Aktif', '2028-08-10'],
            ['4342301088', 'Shakira Maharani', 'Perempuan', 20, 'Bengkong', 'Aktif', '2027-09-12'],
            ['3312001099', 'Reza Pratama', 'Laki-laki', 22, 'Sekupang', 'Kadaluarsa', '2026-05-01'],
            ['4112501055', 'Nabila Ayu', 'Perempuan', 19, 'Nongsa', 'Aktif', '2029-02-25'],
            ['4342301102', 'Kevin Julius', 'Laki-laki', 21, 'Lubuk Baja', 'Aktif', '2027-11-30'],
            ['3312001015', 'Siti Aminah', 'Perempuan', 23, 'Sei Beduk', 'Kadaluarsa', '2025-12-15'],
            ['4112501077', 'Hendra Wijaya', 'Laki-laki', 20, 'Batu Ampar', 'Aktif', '2028-08-17'],
            ['4342301044', 'Putri Diana', 'Perempuan', 18, 'Sagulung', 'Aktif', '2030-04-05'],
            ['3312001021', 'Dede Ramadhan', 'Laki-laki', 24, 'Batu Aji', 'Kadaluarsa', '2026-01-10'],
            ['4112501090', 'Clara Sinta', 'Perempuan', 21, 'Belakang Padang', 'Aktif', '2027-07-22'],
            ['4342301111', 'Aditya Pratama', 'Laki-laki', 22, 'Sekupang', 'Aktif', '2028-05-14'],
            ['4112501088', 'Rina Melati', 'Perempuan', 19, 'Sagulung', 'Aktif', '2029-09-20'],
            ['3312001050', 'Surya Saputra', 'Laki-laki', 24, 'Bengkong', 'Kadaluarsa', '2025-11-10'],
            ['4342301205', 'Maya Sari', 'Perempuan', 20, 'Batam Kota', 'Aktif', '2028-01-05'],
            ['4112501112', 'Ilham Akbar', 'Laki-laki', 21, 'Batu Aji', 'Aktif', '2027-08-17'],
            ['3312001077', 'Tiara Andini', 'Perempuan', 23, 'Nongsa', 'Kadaluarsa', '2026-02-22'],
            ['4342301199', 'Rizky Fauzi', 'Laki-laki', 19, 'Batu Ampar', 'Aktif', '2028-12-12'],
            ['4112501095', 'Anita Rahman', 'Perempuan', 20, 'Sei Beduk', 'Aktif', '2028-04-08'],
            ['3312001034', 'Bayu Permana', 'Laki-laki', 25, 'Galang', 'Kadaluarsa', '2025-06-30'],
            ['4342301150', 'Cinta Laura', 'Perempuan', 18, 'Lubuk Baja', 'Aktif', '2029-10-19'],
            ['4112501222', 'Dandi Irawan', 'Laki-laki', 22, 'Belakang Padang', 'Aktif', '2027-03-02'],
            ['3312001090', 'Fadhila Putri', 'Perempuan', 21, 'Sekupang', 'Aktif', '2028-11-25'],
            ['4342301175', 'Gilang Ramadhan', 'Laki-laki', 20, 'Batam Kota', 'Aktif', '2029-02-14'],
            ['4112501130', 'Hana Safira', 'Perempuan', 19, 'Sagulung', 'Aktif', '2028-09-09'],
            ['3312001045', 'Iqbal Hakim', 'Laki-laki', 23, 'Bengkong', 'Kadaluarsa', '2026-07-18'],
            ['4342301210', 'Jihan Fahira', 'Perempuan', 21, 'Batu Aji', 'Aktif', '2028-05-21'],
            ['4112501188', 'Kiki Sanjaya', 'Laki-laki', 22, 'Nongsa', 'Aktif', '2027-08-30'],
            ['3312001066', 'Larasati Ayu', 'Perempuan', 24, 'Sei Beduk', 'Kadaluarsa', '2026-01-12'],
            ['4342301133', 'M. Ridwan', 'Laki-laki', 20, 'Batu Ampar', 'Aktif', '2029-07-07'],
            ['4112501101', 'Nadia Vega', 'Perempuan', 19, 'Lubuk Baja', 'Aktif', '2028-11-11']
        ];

        $currentYear = Carbon::now()->year;

        foreach ($data as $row) {
            $nim = $row[0];
            $name = $row[1];
            $genderRaw = $row[2];
            $umur = $row[3];
            $wilayah = $row[4];
            $status = $row[5];
            $tanggal = $row[6];

            // 1. Konversi Umur ke Tanggal Lahir (Asumsi lahir di 1 Januari)
            $tahunLahir = $currentYear - $umur;
            $tanggalLahir = Carbon::create($tahunLahir, 1, 1)->format('Y-m-d');

            // 2. Format Gender untuk DB ('L' atau 'P')
            $gender = $genderRaw === 'Laki-laki' ? 'L' : 'P';

            // 3. Status - Walaupun status bisa dilihat dari expired_at, kita masukkan sesuai datanya
            $expiredAt = Carbon::parse($tanggal)->format('Y-m-d H:i:s');

            User::updateOrCreate(
                ['nim' => $nim], // Pastikan tidak duplikat berdasarkan NIM
                [
                    'name' => $name,
                    'gender' => $gender,
                    'tanggal_lahir' => $tanggalLahir,
                    'kecamatan_domisili' => $wilayah,
                    'expired_at' => $expiredAt,
                    'kode_kartu' => $nim, // Gunakan NIM sebagai kode kartu sementara
                    'role' => 'mahasiswa', // Role default
                    'email' => $nim . '@student.polibatam.ac.id',
                    'password' => Hash::make('password123') // Default password
                ]
            );
        }
    }
}