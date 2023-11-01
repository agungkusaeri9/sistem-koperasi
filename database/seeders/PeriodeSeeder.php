<?php

namespace Database\Seeders;

use App\Models\Periode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Periode::create([
            'bulan_awal' => 1,
            'bulan_akhir' => 12,
            'tahun_awal' => 2023,
            'tahun_akhir' => 2023,
            'status' => 1,
            'nominal_simpanan_wajib' => 50000,
            'nominal_simpanan_shr' => 60000
        ]);
    }
}
