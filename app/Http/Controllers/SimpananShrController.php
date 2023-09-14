<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use App\Models\Periode;
use App\Models\Simpanan;
use App\Models\SimpananAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SimpananShrController extends Controller
{
    public function tagihan()
    {
        $items = SimpananAnggota::jenisShr()->ByAnggota()->latest()->get();

        return view('pages.simpanan-shr.tagihan', [
            'title' => 'Tagihan Simpanan Wajib',
            'items' => $items
        ]);
    }
    public function tagihan_bayar($id)
    {
        // cek jika simpanan sudah lunas
        $item = SimpananAnggota::jenisShr()->with('simpanan')->where('id', $id)->firstOrFail();
        if ($item->status_tagihan == 2) {
            return redirect()->back()->with('warning', 'Tagihan tersebut sudah lunas.');
        }
        return view('pages.simpanan-shr/bayar', [
            'title' => 'Bayar Simpanan SHR',
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

        $simpanan_anggota = SimpananAnggota::jenisShr()->where('id', $id)->first();

        $simpanan_anggota->update([
            'metode_pembayaran_id' => request('metode_pembayaran_id'),
            'bukti_pembayaran' => request()->file('bukti_pembayaran') ? request()->file('bukti_pembayaran')->store('angsuran/bukti-pembayaran', 'public') : NULL,
            'status_tagihan' => 1
        ]);


        return redirect()->route('simpanan-shr.tagihan.index')->with('success', 'Bukti pembayaran berhasil diupload. Dimohon tunggu admin untuk memverifikasi pembayaran.');
    }

    public function saldo()
    {
        $periode_id = request('periode_id');
        $periode_aktif = Periode::where('status', 1)->first();

        if ($periode_id) {
            $items = SimpananAnggota::jenisShr()->with('simpanan')->ByAnggota()->where('status_tagihan', 2)->where('status_pencairan', 0)->whereHas('simpanan', function ($simpanan) use ($periode_id) {
                $simpanan->where('periode_id', $periode_id);
            })->get();

            $saldo = Simpanan::where('jenis', 'shr')->whereHas('simpanan_anggota', function ($sa) {
                $sa->where([
                    'anggota_id' => auth()->user()->anggota->id,
                    'status_tagihan' => 2,
                    'status_pencairan' => 0
                ]);
            })->where('periode_id', $periode_id)->sum('nominal');

            $periode = Periode::findOrFail($periode_id);
        } else {
            $items = SimpananAnggota::jenisShr()->with('simpanan')->ByAnggota()->where('status_tagihan', 2)->where('status_pencairan', 0)->whereHas('simpanan', function ($simpanan) use ($periode_aktif) {
                $simpanan->where('periode_id', $periode_aktif->id);
            })->get();

            $saldo = Simpanan::where('jenis', 'shr')->whereHas('simpanan_anggota', function ($sa) {
                $sa->where([
                    'anggota_id' => auth()->user()->anggota->id,
                    'status_tagihan' => 2,
                    'status_pencairan' => 0
                ]);
            })->where('periode_id', $periode_aktif->id)->sum('nominal');
            $periode = Periode::findOrFail($periode_aktif->id);
        }
        return view('pages.simpanan-shr.saldo', [
            'title' => 'Informasi Saldo Simpanan SHR',
            'items' => $items,
            'saldo' => $saldo,
            'periode' => $periode,
            'data_periode' => Periode::latest()->get()
        ]);
    }
}
