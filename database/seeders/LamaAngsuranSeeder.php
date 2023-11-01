<?php

namespace Database\Seeders;

use App\Models\LamaAngsuran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LamaAngsuranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data1 = ['durasi' => 1, 'potongan_awal_persen' => 2, 'jasa_pinjaman_bulan_persen' => 2, 'jenis' => 'Jangka Pendek'];
        LamaAngsuran::create($data1);

        $data2 = ['durasi' => 2, 'potongan_awal_persen' => 2, 'jasa_pinjaman_bulan_persen' => 2, 'jenis' => 'Jangka Pendek'];
        LamaAngsuran::create($data2);

        $data3 = ['durasi' => 3, 'potongan_awal_persen' => 2, 'jasa_pinjaman_bulan_persen' => 2, 'jenis' => 'Jangka Pendek'];
        LamaAngsuran::create($data3);

        $data4 = ['durasi' => 4, 'potongan_awal_persen' => 2, 'jasa_pinjaman_bulan_persen' => 2, 'jenis' => 'Jangka Panjang'];
        LamaAngsuran::create($data4);
    }
}
