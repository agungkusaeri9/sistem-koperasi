<?php

namespace App\Console\Commands;

use App\Models\Anggota;
use App\Models\JenisSimpanan;
use App\Models\Pengaturan;
use App\Models\Simpanan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BuatTagihanSimpananCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'buatTagihanSimpanan:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fungsi untuk membuat tagihan simpanan wajib dan shr secara otomatis dan dijalankan setiap awal bulan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // data anggota yang aktif
        $data_anggota = Anggota::whereHas('user', function ($user) {
            $user->where('is_active', 1);
        })->get();
        $nominal_simpanan_wajib = Pengaturan::first()->nominal_simpanan_wajib;
        $nominal_simpanan_shr = Pengaturan::first()->nominal_simpanan_shr;
        $bulan = Carbon::now()->translatedFormat('m');
        $tahun = Carbon::now()->translatedFormat('Y');

        // looping data anggota
        foreach ($data_anggota as $anggota) {

            // simpanan wajib
            Simpanan::create([
                'bulan' => $bulan,
                'tahun' => $tahun,
                'jenis' => 'wajib',
                'nominal' => $nominal_simpanan_wajib,
                'anggota_id' => $anggota->id
            ]);

            // simpanan shr
            Simpanan::create([
                'bulan' => $bulan,
                'tahun' => $tahun,
                'jenis' => 'shr',
                'nominal' => $nominal_simpanan_shr,
                'anggota_id' => $anggota->id
            ]);
        }
    }
}
