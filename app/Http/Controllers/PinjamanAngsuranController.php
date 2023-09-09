<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use App\Models\PinjamanAngsuran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PinjamanAngsuranController extends Controller
{
    public function update($id)
    {
        request()->validate([
            'status' => ['required', 'numeric']
        ]);

        DB::beginTransaction();
        try {
            $status = request('status');
            $item = PinjamanAngsuran::findOrFail($id);
            if ($status == 2)
                $item->tanggal_verifikasi = Carbon::now()->format('Y-m-d');
            $item->status = $status;
            $item->save();
            DB::commit();

            return redirect()->back()->with('success', 'Status Angsuran berhasil diupdate.');
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', 'Status Angsuran gagal diupdate.');
        }
    }

    public function bayar($kode_pinjaman, $pinjaman_angsuran_id)
    {
        $item = PinjamanAngsuran::whereHas('pinjaman', function ($q) use ($kode_pinjaman) {
            $q->where([
                'kode' => $kode_pinjaman,
                'anggota_id' => auth()->user()->anggota->id
            ]);
        })->where('id', $pinjaman_angsuran_id)->firstOrFail();

        // jika angsuran status nya bukan bayar, maka alihkan ke detail pinjaman
        if ($item->status == 2) {
            return redirect()->route('pinjaman.show', $item->pinjaman->kode);
        }

        return view('pages.pinjaman-angsuran.bayar', [
            'title' => 'Bayar angsuran ' . $item->pinjaman->kode,
            'item' => $item,
            'data_metode_pembayaran' => MetodePembayaran::bySistem()->orderBy('nama', 'ASC')->get()
        ]);
    }

    public function proses_bayar($kode_pinjaman, $pinjaman_angsuran_id)
    {
        request()->validate([
            'metode_pembayaran_id' => ['required', 'numeric'],
            'bukti_pembayaran' => ['required', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048']
        ]);

        $item = PinjamanAngsuran::whereHas('pinjaman', function ($q) use ($kode_pinjaman) {
            $q->where([
                'kode' => $kode_pinjaman,
                'anggota_id' => auth()->user()->anggota->id
            ]);
        })->where('id', $pinjaman_angsuran_id)->firstOrFail();

        // hapus gambar/bukti jika sebelumnya ada
        if ($item->bukti_pembayaran) {
            Storage::disk('public')->delete($item->bukti_pembayaran);
        }

        $item->update([
            'metode_pembayaran_id' => request('metode_pembayaran_id'),
            'bukti_pembayaran' => request()->file('bukti_pembayaran')->store('angsuran/bukti-pembayaran', 'public'),
            'status' => 1
        ]);

        return redirect()->route('pinjaman.show', $item->pinjaman->kode)->with('success', 'Bukti pembayaran berhasil diupload. Dimohon tunggu admin untuk memverifikasi pembayaran.');
    }
}
