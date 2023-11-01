<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use App\Models\Periode;
use App\Models\PinjamanAngsuran;
use App\Services\WhatsappService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PinjamanAngsuranController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkRole:anggota')->only(['bayar', 'proses_bayar']);
        $this->middleware('checkRole:admin')->only(['update']);
    }


    public function index()
    {
        $status = request('status');
        $bulan = request('bulan');
        $tahun = request('tahun');

        $items = PinjamanAngsuran::with('pinjaman');
        if ($status === 'semua') {
        } elseif ($status != NULL) {
            $items->where('status', $status);
        }

        if ($bulan)
            $items->where('bulan', $bulan);

        if ($tahun)
            $items->where('tahun', $tahun);

        $data = $items->latest()->get();

        $data_bulan = Periode::getBulan();
        $data_tahun = Periode::getTahun();
        return view('pages.pinjaman-angsuran.index', [
            'title' => 'Pinjaman',
            'items' => $data,
            'status' => $status ?? 'semua',
            'data_bulan' => $data_bulan,
            'data_tahun' => $data_tahun
        ]);
    }

    public function edit($uuid)
    {
        $item = PinjamanAngsuran::where('uuid', $uuid)->firstOrFail();
        return view('pages.pinjaman-angsuran.edit', [
            'title' => 'Pinjaman Angsuran Edit',
            'item' => $item,
            'data_metode_pembayaran' => MetodePembayaran::get()
        ]);
    }

    public function update(WhatsappService $whatsappService, $uuid)
    {
        request()->validate([
            'status' => ['required', 'numeric'],
            'metode_pembayaran_id' => ['required']
        ]);

        DB::beginTransaction();
        try {
            $status = request('status');
            $item = PinjamanAngsuran::where('uuid', $uuid)->firstOrFail();
            if ($status == 2)
                $item->tanggal_verifikasi = Carbon::now()->format('Y-m-d');
            $item->status = $status;
            $item->save();

            // kirim notifikasi ke anggota
            if ($status == 2) {
                // lunas
                // $whatsappService->anggota_verifikasi_angsuran_pinjaman($item->id);
            }
            DB::commit();

            return redirect()->route('pinjaman.show', $item->pinjaman->kode)->with('success', 'Status Angsuran berhasil diupdate.');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return redirect()->route('pinjaman.show', $item->pinjaman->kode)->with('error', 'Mohon Maaf Ada Kesalahan Sistem!');
        }
    }
}
