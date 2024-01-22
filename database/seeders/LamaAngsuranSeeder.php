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
        $data5 = ['durasi' => 5, 'potongan_awal_persen' => 2, 'jasa_pinjaman_bulan_persen' => 2, 'jenis' => 'Jangka Panjang'];
        LamaAngsuran::create($data5);
        $data6 = ['durasi' => 6, 'potongan_awal_persen' => 2, 'jasa_pinjaman_bulan_persen' => 2, 'jenis' => 'Jangka Panjang'];
        LamaAngsuran::create($data6);
        $data7 = ['durasi' => 7, 'potongan_awal_persen' => 2, 'jasa_pinjaman_bulan_persen' => 2, 'jenis' => 'Jangka Panjang'];
        LamaAngsuran::create($data7);
        $data8 = ['durasi' => 8, 'potongan_awal_persen' => 2, 'jasa_pinjaman_bulan_persen' => 2, 'jenis' => 'Jangka Panjang'];
        LamaAngsuran::create($data8);
        $data9 = ['durasi' => 9, 'potongan_awal_persen' => 2, 'jasa_pinjaman_bulan_persen' => 2, 'jenis' => 'Jangka Panjang'];
        LamaAngsuran::create($data9);
        $data10 = ['durasi' => 10, 'potongan_awal_persen' => 2, 'jasa_pinjaman_bulan_persen' => 2, 'jenis' => 'Jangka Panjang'];
        LamaAngsuran::create($data10);
        $data11 = ['durasi' => 11, 'potongan_awal_persen' => 2, 'jasa_pinjaman_bulan_persen' => 2, 'jenis' => 'Jangka Panjang'];
        LamaAngsuran::create($data11);
        $data12 = ['durasi' => 12, 'potongan_awal_persen' => 2, 'jasa_pinjaman_bulan_persen' => 2, 'jenis' => 'Jangka Panjang'];
        LamaAngsuran::create($data12);
    }
}
