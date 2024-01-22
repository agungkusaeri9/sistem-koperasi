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
            'bulan_awal' => 8,
            'bulan_akhir' => 5,
            'tahun_awal' => 2018,
            'tahun_akhir' => 2019,
            'status' => 0,
            'nominal_simpanan_wajib' => 0,
            'nominal_simpanan_shr' => 0
        ]);

        Periode::create([
            'bulan_awal' => 6,
            'bulan_akhir' => 5,
            'tahun_awal' => 2019,
            'tahun_akhir' => 2020,
            'status' => 0,
            'nominal_simpanan_wajib' => 0,
            'nominal_simpanan_shr' => 0
        ]);

        Periode::create([
            'bulan_awal' => 6,
            'bulan_akhir' => 5,
            'tahun_awal' => 2020,
            'tahun_akhir' => 2021,
            'status' => 0,
            'nominal_simpanan_wajib' => 0,
            'nominal_simpanan_shr' => 0
        ]);

        Periode::create([
            'bulan_awal' => 6,
            'bulan_akhir' => 5,
            'tahun_awal' => 2021,
            'tahun_akhir' => 2022,
            'status' => 0,
            'nominal_simpanan_wajib' => 0,
            'nominal_simpanan_shr' => 0
        ]);

        Periode::create([
            'bulan_awal' => 5,
            'bulan_akhir' => 4,
            'tahun_awal' => 2022,
            'tahun_akhir' => 2023,
            'status' => 0,
            'nominal_simpanan_wajib' => 0,
            'nominal_simpanan_shr' => 0
        ]);

        Periode::create([
            'bulan_awal' => 5,
            'bulan_akhir' => 4,
            'tahun_awal' => 2023,
            'tahun_akhir' => 2024,
            'status' => 1,
            'nominal_simpanan_wajib' => 0,
            'nominal_simpanan_shr' => 0
        ]);
    }
}
