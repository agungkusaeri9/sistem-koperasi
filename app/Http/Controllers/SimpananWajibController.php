<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\MetodePembayaran;
use App\Models\PencairanSimpanan;
use App\Models\Pinjaman;
use App\Models\PinjamanAngsuran;
use App\Models\Simpanan;
use App\Models\SimpananAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SimpananWajibController extends Controller
{

    public function index()
    {
        $status = request('status');
        if ($status === 'semua')
            $items = SimpananAnggota::with(['anggota'])->jenisWajib()->latest()->get();
        elseif ($status)
            $items = SimpananAnggota::where('status_tagihan', $status)->with(['anggota'])->jenisWajib()->latest()->get();
        else
            $items = SimpananAnggota::with(['anggota'])->jenisWajib()->latest()->get();


        return view('pages.simpanan-wajib.index', [
            'title' => 'Simpanan Wajib',
            'items' => $items,
            'status' => $status === 'semua' ? 'semua' : $status
        ]);
    }

    public function tagihan()
    {
        $items = SimpananAnggota::jenisWajib()->ByAnggota()->whereHas('simpanan', function ($simpanan) {
            $simpanan->where('jenis', 'wajib');
        })->latest()->get();

        return view('pages.simpanan-wajib.tagihan', [
            'title' => 'Tagihan Simpanan Wajib',
            'items' => $items
        ]);
    }
    public function tagihan_bayar($id)
    {
        // cek jika simpanan sudah lunas
        $item = SimpananAnggota::jenisWajib()->with('simpanan')->where('id', $id)->firstOrFail();
        if ($item->status_tagihan == 2) {
            return redirect()->back()->with('warning', 'Tagihan tersebut sudah lunas.');
        }
        return view('pages.simpanan-wajib/bayar', [
            'title' => 'Bayar Simpanan Wajib',
            'item' => $item,
            'data_metode_pembayaran' => MetodePembayaran::bySistem()->get()
        ]);
    }

    public function proses_tagihan_bayar($id)
    {
        $metode_pembayaran = MetodePembayaran::find(request('metode_pembayaran_id'));
        request()->validate([
            'metode_pembayaran_id' => ['required', 'numeric'],
            'bukti_pembayaran' => [Rule::when($metode_pembayaran->nomor ?? 0 != NULL, ['required', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'])]
        ]);

        $simpanan_anggota = SimpananAnggota::jenisWajib()->where('id', $id)->first();

        $simpanan_anggota->update([
            'metode_pembayaran_id' => request('metode_pembayaran_id'),
            'bukti_pembayaran' => request()->file('bukti_pembayaran') ? request()->file('bukti_pembayaran')->store('angsuran/bukti-pembayaran', 'public') : NULL,
            'status_tagihan' => 1
        ]);


        return redirect()->route('simpanan-wajib.tagihan.index')->with('success', 'Bukti pembayaran berhasil diupload. Dimohon tunggu admin untuk memverifikasi pembayaran.');
    }

    public function saldo()
    {
        $items = SimpananAnggota::jenisWajib()->with('simpanan')->ByAnggota()->where('status_tagihan', 2)->where('status_pencairan', 0)->get();

        $saldo = Simpanan::where('jenis', 'wajib')->whereHas('simpanan_anggota', function ($sa) {
            $sa->where([
                'anggota_id' => auth()->user()->anggota->id,
                'status_tagihan' => 2,
                'status_pencairan' => 0
            ]);
        })->sum('nominal');

        return view('pages.simpanan-wajib.saldo', [
            'title' => 'Informasi Saldo Simpanan Wajib',
            'items' => $items,
            'saldo' => $saldo
        ]);
    }

    public function edit($id)
    {
        $item = SimpananAnggota::jenisWajib()->where('id', $id)->firstOrFail();
        return view('pages.simpanan-wajib.edit', [
            'title' => 'Edit Simpanan Wajib',
            'item' => $item,
            'data_metode_pembayaran' => MetodePembayaran::bySistem()->get()
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'status_tagihan' => ['required', 'in:0,1,2']
        ]);

        DB::beginTransaction();

        try {
            $data = request()->only(['metode_pembayaran_id', 'status_tagihan']);
            $item = SimpananAnggota::jenisWajib()->where('id', $id)->firstOrFail();
            $item->update($data);

            DB::commit();
            return redirect()->route('simpanan-wajib.index')->with('success', 'Simpanan Wajib berhasil diupdate.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('simpanan-wajib.index')->with('error', $th->getMessage());
        }
    }

    public function pengajuan_pencairan()
    {
        $saldo = Simpanan::whereHas('simpanan_anggota', function ($sa) {
            $sa->jenisWajib()->where([
                'anggota_id' => auth()->user()->anggota->id,
                'status_pencairan' => 0,
                'status_tagihan' => 2
            ]);
        })->sum('nominal');
        $pengajuan = PencairanSimpanan::where([
            'anggota_id' => auth()->user()->anggota->id,
            'jenis' => 'wajib'
        ])->where('status', '0');

        $data_pengajuan_pencairan = PencairanSimpanan::where('jenis', 'wajib')->where('anggota_id', auth()->user()->anggota->id)->latest()->get();
        return view('pages.simpanan-wajib.pengajuan-pencairan', [
            'title' => 'Pengajuan Pencairan Simpanan Wajib',
            'saldo' => $saldo,
            'data_metode_pembayaran' => MetodePembayaran::byAnggota()->latest()->get(),
            'pengajuan' => $pengajuan,
            'data_pengajuan_pencairan' => $data_pengajuan_pencairan
        ]);
    }

    public function proses_pencairan()
    {
        request()->validate([
            'metode_pembayaran_id' => ['required', 'numeric']
        ]);

        // cek tagihan simpanan wajib dan shr yang belum bayar
        $cekSimpananBelumBayar = SimpananAnggota::where('status_tagihan', '!=', 2)->where('anggota_id', auth()->user()->anggota->id)->count();

        // cek tagihan pinjaman angsuran apakah ada yang belum di bayar
        $cekPinjamanAngsuran = Pinjaman::where([
            'status' => 1,
            'anggota_id' => auth()->user()->anggota->id
        ])->count();

        if ($cekSimpananBelumBayar > 0) {
            return redirect()->back()->with('error', 'Anda masih mempunyai tagihan simpanan wajib/shr yang belum dibayarkan.');
        }

        if ($cekPinjamanAngsuran > 0) {
            return redirect()->back()->with('error', 'Anda masih mempunyai tagihan pinjaman yang belum dibayarkan.');
        }



        DB::beginTransaction();
        try {
            $saldo = Simpanan::whereHas('simpanan_anggota', function ($sa) {
                $sa->jenisWajib()->where([
                    'anggota_id' => auth()->user()->anggota->id,
                    'status_pencairan' => 0,
                    'status_tagihan' => 2
                ]);
            })->sum('nominal');

            if ($saldo < 1) {
                return redirect()->back()->with('error', 'Saldo yang anda miliki tidak mencukupi untuk melakukan pencairan dana simpanan wajib.');
            }

            PencairanSimpanan::create([
                'jenis' => 'wajib',
                'anggota_id' => auth()->user()->anggota->id,
                'nominal' => $saldo,
                'metode_pembayaran_id' => request('metode_pembayaran_id'),
                'status' => 0
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Pengajuan pencairan simpanan wajib berhasil dilakukan. Silahkan tunggu admin untuk proses validasi.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function proses_batal()
    {
        DB::beginTransaction();
        try {
            $pengajuan = PencairanSimpanan::where([
                'anggota_id' => auth()->user()->anggota->id,
                'jenis' => 'wajib',
                'status' => 0
            ])->firstOrFail();

            $pengajuan->update([
                'status' => 3
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Anda berhasil membatalkan pengajuan pencairan dana simpanan wajib');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function pencairan()
    {
        $items = PencairanSimpanan::where('jenis', 'wajib')->latest()->get();
        return view('pages.simpanan-wajib.pencairan', [
            'title' => 'Pencairan Simpanan Wajib',
            'items' => $items
        ]);
    }

    public function pencairan_delete($id)
    {
        $item = PencairanSimpanan::findOrFail($id);
        if ($item->status != 2) {
            return redirect()->back()->with('error', 'Pencairan Simpanan Wajib tidak bisa dihapus.');
        }
        $item->delete();
        return redirect()->back()->with('success', 'Pencairan Simpanan Wajib berhasil dihapus.');
    }

    public function pencairan_update_status($id)
    {
        $item = PencairanSimpanan::findOrFail($id);
        $anggota_id = $item->anggota_id;

        if (request('status') == 1) {
            // cek tagihan simpanan wajib dan shr yang belum bayar
            $cekSimpananBelumBayar = SimpananAnggota::where('status_tagihan', '!=', 2)->where('anggota_id', $anggota_id)->count();

            // cek tagihan pinjaman angsuran apakah ada yang belum di bayar
            $cekPinjamanAngsuran = Pinjaman::where([
                'status' => 1,
                'anggota_id' => $anggota_id
            ])->count();

            if ($cekSimpananBelumBayar > 0) {
                return redirect()->back()->with('error', 'Anggota tersebut masih mempunyai tagihan simpanan wajib/shr yang belum dibayarkan.');
            }

            if ($cekPinjamanAngsuran > 0) {
                return redirect()->back()->with('error', 'Anggota tersebut masih mempunyai tagihan pinjaman yang belum dibayarkan.');
            }

            // nonaktifkan langsung anggotanya
            $item->anggota->user->update([
                'is_active' => 0
            ]);
        }
        $item->update([
            'status' => request('status')
        ]);
        return redirect()->back()->with('success', 'Status Pencairan Simpanan Wajib berhasil diupdate.');
    }
}
