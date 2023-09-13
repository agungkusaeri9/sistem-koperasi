<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use App\Models\Simpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SimpananWajibController extends Controller
{
    public function tagihan()
    {
        $items = Simpanan::ByAnggota()->where('jenis', 'wajib')->whereIn('status_tagihan', [0, 1, 3])->get();
        return view('pages.simpanan-wajib/tagihan', [
            'title' => 'Tagihan Simpanan Wajib',
            'items' => $items
        ]);
    }
    public function tagihan_bayar($id)
    {
        $item = Simpanan::ByAnggota()->where('jenis', 'wajib')->whereIn('status_tagihan', [0, 1, 3])->where('id', $id)->firstOrFail();
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

        $item = Simpanan::ByAnggota()->where('jenis', 'wajib')->whereIn('status_tagihan', [0, 1, 3])->where('id', $id)->firstOrFail();

        // hapus gambar/bukti jika sebelumnya ada
        if ($item->bukti_pembayaran) {
            Storage::disk('public')->delete($item->bukti_pembayaran);
        }

        $item->update([
            'metode_pembayaran_id' => request('metode_pembayaran_id'),
            'bukti_pembayaran' => request()->file('bukti_pembayaran') ? request()->file('bukti_pembayaran')->store('angsuran/bukti-pembayaran', 'public') : NULL,
            'status_tagihan' => 1
        ]);

        return redirect()->route('simpanan-wajib.tagihan.index')->with('success', 'Bukti pembayaran berhasil diupload. Dimohon tunggu admin untuk memverifikasi pembayaran.');
    }
}
