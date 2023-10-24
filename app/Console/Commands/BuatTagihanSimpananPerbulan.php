<?php

namespace App\Console\Commands;

use App\Models\Anggota;
use App\Models\MetodePembayaran;
use App\Models\Periode;
use App\Models\Simpanan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BuatTagihanSimpananPerbulan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'buat-tagihan-simpanan-perbulan:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(Schedule $schedule)
    {
        $bulan_sekarang = Carbon::now()->translatedFormat('m');
        $tahun_sekarang = Carbon::now()->translatedFormat('Y');
        $periode_aktif = Periode::where('status', 1)->first();
        $metode_pembayaran_potong_gaji = MetodePembayaran::where('nama', 'Potong Gaji')->first();
        $nominal_simpanan_wajib = $periode_aktif->nominal_simpanan_wajib;
        $nominal_simpanan_shr = $periode_aktif->nominal_simpanan_shr;
        // data anggota yang aktif
        $data_anggota = Anggota::whereHas('user', function ($q) {
            $q->where('is_active', 1);
        })->get();

        // panggil data anggota kemudian create simpanan wajib
        DB::beginTransaction();

        try {
            foreach ($data_anggota as $anggota) {

                $cekSimpananAnggota = Simpanan::where([
                    'anggota_id' => $anggota->id,
                    'bulan' => $bulan_sekarang,
                    'tahun' => $tahun_sekarang,
                    'jenis' => 'wajib'
                ]);

                // cek apakah simpanan anggota tersebut tidak ada di database sesuai dengan tahun, bulan dan jenis
                if ($cekSimpananAnggota->count() < 1) {
                    // Create Simpanan Wajib
                    Simpanan::create([
                        'anggota_id' => $anggota->id,
                        'nominal' => $nominal_simpanan_wajib,
                        'tahun' => $tahun_sekarang,
                        'bulan' => $bulan_sekarang,
                        'jenis' => 'wajib',
                        'periode_id' => NULL,
                        'uuid' => \Str::uuid(),
                        'status_pencairan' => 0,
                        'status' => 2,
                        'metode_pembayaran' => $metode_pembayaran_potong_gaji->id ?? NULL
                    ]);

                    // Create Simpanan SHR
                    Simpanan::create([
                        'anggota_id' => $anggota->id,
                        'nominal' => $nominal_simpanan_shr,
                        'tahun' => $tahun_sekarang,
                        'bulan' => $bulan_sekarang,
                        'jenis' => 'shr',
                        'periode_id' => $periode_aktif->id,
                        'uuid' => \Str::uuid(),
                        'status_pencairan' => 0,
                        'status' => 2,
                        'metode_pembayaran' => $metode_pembayaran_potong_gaji->id ?? NULL
                    ]);
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
        }
    }
}
