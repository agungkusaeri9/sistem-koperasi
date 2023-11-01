<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengaturan::create([
            'nama_situs' => 'Koperasi Karya Bersama',
            'email' => 'koperasikaryabersama@gmail.com',
            'nomor_telepon' => '08912312412',
            'pembuat' => 'Agung Kusaeri'
        ]);
    }
}
