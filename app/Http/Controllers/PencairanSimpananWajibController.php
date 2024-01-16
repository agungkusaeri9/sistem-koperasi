<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\MetodePembayaran;
use App\Models\PencairanSimpanan;
use App\Models\Periode;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use App\Models\SimpananAnggota;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PencairanSimpananWajibController extends Controller
{
    public function index()
    {
        $items = PencairanSimpanan::jenisWajib()->latest()->get();
        return view('pages.simpanan-wajib.pencairan.index', [
            'title' => 'Pencairan Simpanan Wajib',
            'items' => $items
        ]);
    }

    public function create()
    {
        $data_anggota = Anggota::orderBy('nama', 'ASC')->get();
        $data_periode = Periode::latest()->get();
        $items = Simpanan::jenisWajib()->latest()->get();
        $data_metode_pembayaran = MetodePembayaran::get();
        return view('pages.simpanan-wajib.pencairan.create', [
            'title' => 'Buat Pencairan Simpanan Wajib',
            'data_anggota' => $data_anggota,
            'data_periode' => $data_periode,
            'items' => $items,
            'data_metode_pembayaran' => $data_metode_pembayaran
        ]);
    }


    public function store(WhatsappService $whatsappService)
    {
        request()->validate([
            'metode_pembayaran_id' => ['required', 'numeric'],
            'anggota_id' => ['required']
        ]);

        $anggota_id = request('anggota_id');

        // cek tagihan simpanan wajib dan shr yang belum bayar
        $cekSimpananBelumBayar = Simpanan::where('status', '!=', 2)->where('anggota_id', $anggota_id)->count();

        // cek tagihan pinjaman angsuran apakah ada yang belum di bayar
        $cekPinjamanAngsuran = Pinjaman::where([
            'status' => 1,
            'anggota_id' => $anggota_id
        ])->count();

        if ($cekSimpananBelumBayar > 0) {
            return redirect()->back()->with('error', 'Amggpta masih mempunyai tagihan simpanan wajib/shr yang belum dibayarkan.');
        }

        if ($cekPinjamanAngsuran > 0) {
            return redirect()->back()->with('error', 'Anggota masih mempunyai tagihan pinjaman yang belum dibayarkan.');
        }


        DB::beginTransaction();
        try {
            $saldo = Simpanan::jenisWajib()->where([
                'anggota_id' => $anggota_id,
                'status' => 2,
                'status_pencairan' => 0
            ])->sum('nominal');

            if ($saldo < 1) {
                return redirect()->back()->with('error', 'Saldo yang anda miliki tidak mencukupi untuk melakukan pencairan dana simpanan wajib.');
            }

            $pencairan = PencairanSimpanan::create([
                'jenis' => 'wajib',
                'anggota_id' => $anggota_id,
                'nominal' => $saldo,
                'status' => 1,
                'periode_id' => NULL,
                'metode_pembayaran_id' => request('metode_pembayaran_id'),
            ]);

            // update simpanan ke sudah dicairkan
            Simpanan::jenisWajib()->where('anggota_id', $anggota_id)->update([
                'status_pencairan' => 1
            ]);
            // non aktifkan anggota
            Anggota::findOrFail($anggota_id)->user->update([
                'is_avtive' => 0
            ]);

            DB::commit();

            // kirim notifikasi ke anggota
            // $whatsappService->anggota_pencairan_simpanan_wajib($pencairan->id);

            return redirect()->route('pencairan-simpanan-wajib.index')->with('success', 'Pencairan simpanan wajib berhasil ditambahkan.');
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', 'Mohon Maaf Ada Kesalahan Sistem!');
        }
    }
}
