<?php

namespace App\Console\Commands;

use App\Models\MetodePembayaran;
use App\Models\PinjamanAngsuran;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AngsuranPinjamanOtomatisUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'angsuranPinjamanOtomatisUpdate:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '
        Angsuran pinjaman otomatis update ketika memilih jenis angsuran jangka pendek, maka  otomatis diubah menjadi lunas dengan metode pembayaran potong gaji
    ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tanggal_sekarang = Carbon::now()->translatedFormat('Y-m-d');
        $bulan_sekarang = Carbon::now()->translatedFormat('m');
        $tahun_sekarang = Carbon::now()->translatedFormat('Y');
        // $tanggal_sekarang = 2023 - 11 - 27;
        // $bulan_sekarang = 11;
        // $tahun_sekarang = 2023;
        $data_angsuran_pinjaman = PinjamanAngsuran::whereHas('pinjaman', function ($q) use ($tanggal_sekarang) {
            $q->whereHas('lama_angsuran', function ($lm) {
                $lm->where('jenis', 'Jangka Panjang');
            })->where([
                'status' => 1
            ]);
        })->where([
            'bulan' => $bulan_sekarang,
            'tahun' => $tahun_sekarang
        ])->get();
        $metode_pembayaran_potong_gaji = MetodePembayaran::where('nama', 'Potong Gaji')->first();

        DB::beginTransaction();
        try {
            foreach ($data_angsuran_pinjaman as $angsuran) {
                $angsuran->update([
                    'status' => 2,
                    'metode_pembayaran_id' => $metode_pembayaran_potong_gaji->id ?? NULL
                ]);
            }

            DB::commit();
            Log::alert($data_angsuran_pinjaman);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Log::error($th->getMessage());
        }
    }
}
