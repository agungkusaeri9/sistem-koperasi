<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use App\Models\Simpanan;
use App\Models\SimpananAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SimpananWajibController extends Controller
{
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
}
