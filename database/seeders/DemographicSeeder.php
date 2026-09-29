<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemographicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecamatans = [
            'Batam Kota', 'Batu Aji', 'Batu Ampar', 'Belakang Padang', 
            'Bengkong', 'Bulang', 'Galang', 'Lubuk Baja', 
            'Nongsa', 'Sagulung', 'Sei Beduk', 'Sekupang'
        ];

        $users = User::all();

        foreach ($users as $user) {
            // Randomize age between 17 and 25
            $age = rand(17, 25);
            $birthDate = Carbon::now()->subYears($age)->subDays(rand(1, 365))->format('Y-m-d');
            
            $gender = (rand(0, 1) == 0) ? 'L' : 'P';
            $kecamatan = $kecamatans[array_rand($kecamatans)];

            $user->update([
                'gender' => $gender,
                'tanggal_lahir' => $birthDate,
                'kecamatan_domisili' => $kecamatan,
            ]);
        }
    }
}
