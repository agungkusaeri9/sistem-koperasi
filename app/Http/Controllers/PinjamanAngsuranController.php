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
            return redirect()->route('pinjaman.show', $item->pinjaman->kode)->with('error', 'Status Angsuran gagal diupdate.');
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

    public function proses_bayar(WhatsappService $whatsappService, $kode_pinjaman, $pinjaman_angsuran_id)
    {
        $metode_pembayaran = MetodePembayaran::findOrFail(request('metode_pembayaran_id'));
        request()->validate([
            'metode_pembayaran_id' => ['required', 'numeric'],
            'bukti_pembayaran' => [Rule::when($metode_pembayaran->nomor != NULL, ['required', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'])]
        ]);

        DB::beginTransaction();
        try {
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
                'bukti_pembayaran' => request()->file('bukti_pembayaran') ? request()->file('bukti_pembayaran')->store('angsuran/bukti-pembayaran', 'public') : NULL,
                'status' => 1
            ]);

            // kirim notifikasi ke admin
            $whatsappService->admin_bukti_pembayaran_angsuran($pinjaman_angsuran_id);

            DB::commit();
            return redirect()->route('pinjaman.show', $item->pinjaman->kode)->with('success', 'Bukti pembayaran berhasil diupload. Dimohon tunggu admin untuk memverifikasi pembayaran.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('pinjaman.show', $item->pinjaman->kode)->with('error', $th->getMessage());
        }
    }
}
