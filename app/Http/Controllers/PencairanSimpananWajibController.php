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
            return redirect()->back()->with('error', 'Anda masih mempunyai tagihan simpanan wajib/shr yang belum dibayarkan.');
        }

        if ($cekPinjamanAngsuran > 0) {
            return redirect()->back()->with('error', 'Anda masih mempunyai tagihan pinjaman yang belum dibayarkan.');
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
                'bukti_pencairan' => request()->file('bukti_pencairan') ? request()->file('bukti_pencairan')->store('simpanan-shr/bukti-pencairan', 'public') : NULL
            ]);

            // update simpanan ke sudah dicairkan
            Simpanan::jenisWajib()->where('anggota_id', $anggota_id)->update([
                'status_pencairan' => 1
            ]);
            // non aktifkan anggota
            Anggota::findOrFail($anggota_id)->update([
                'status' => 0
            ]);

            DB::commit();

            // kirim notifikasi ke anggota
            $whatsappService->anggota_pencairan_simpanan_wajib($pencairan->id);

            return redirect()->route('pencairan-simpanan-wajib.index')->with('success', 'Pencairan simpanan wajib berhasil ditambahkan.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = PencairanSimpanan::findOrFail($id);
        if ($item->status == 1) {
            return redirect()->back()->with('error', 'Pencairan Simpanan Wajib tidak bisa dihapus.');
        }
        $item->delete();
        return redirect()->back()->with('success', 'Pencairan Simpanan Wajib berhasil dihapus.');
    }

    public function edit($id)
    {
        $item  = PencairanSimpanan::with('anggota')->findOrFail($id);
        $data_metode_pembayaran = MetodePembayaran::where('anggota_id', $item->anggota->id)->get();
        return view('pages.simpanan-wajib.pencairan.edit', [
            'title' => 'Buat Pencairan Simpanan Wajib',
            'item' => $item,
            'data_metode_pembayaran' => $data_metode_pembayaran
        ]);
    }

    public function pencairan_update($id)
    {
        request()->validate([
            'metode_pembayaran_id' => ['required'],
            'status' => ['required', 'in:0,1,2,3']
        ]);

        DB::beginTransaction();
        try {
            $item = PencairanSimpanan::findOrFail($id);

            // jika status gagal => 0, 2, 3
            if ($item->status != 1) {
                // jika status pencairan bukan sukses
                if (request('status') == 1) {
                    // jika pilihan = sukses
                    // update status simpanan di simpanan anggota
                    SimpananAnggota::jenisWajib()->where([
                        'anggota_id' => $item->anggota_id
                    ])->update([
                        'status_pencairan' => 1
                    ]);
                }
            } elseif ($item->status == 1) {
                if (request('status') != 1) {
                    // jika pilihan = sukses
                    // update status simpanan di simpanan anggota
                    SimpananAnggota::jenisWajib()->where([
                        'anggota_id' => $item->anggota_id
                    ])->update([
                        'status_pencairan' => 0
                    ]);
                }
            }

            $item->update([
                'metode_pembayaran_id' => request('metode_pembayaran_id'),
                'status' => request('status'),
                'bukti_pencairan' => request()->file('bukti_pencairan') ? request()->file('bukti_pencairan')->store('simpanan-shr/bukti-pencairan', 'public') : $item->bukti_pencairan
            ]);

            DB::commit();
            return redirect()->route('pencairan-simpanan-wajib.index')->with('success', 'Pencairan Simpanan Wajib berhasil di update');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('pencairan-simpanan-wajib.index')->with('error', $th->getMessage());
        }
    }
}
