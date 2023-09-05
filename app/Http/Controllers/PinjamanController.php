<?php

namespace App\Http\Controllers;

use App\Models\LamaAngsuran;
use App\Models\Pinjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PinjamanController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'anggota') {
            $items = Pinjaman::with('anggota')->latest()->get();
        } else {
            // anggota
            $items = Pinjaman::where('anggota_id', auth()->user()->anggota->id)->with('anggota')->latest()->get();
        }
        return view('pages.pinjaman.index', [
            'title' => 'Pinjaman',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.pinjaman.create', [
            'title' => 'Buat Pengangajuan Pinjaman',
            'data_lama_angsuran' => LamaAngsuran::orderBy('durasi', 'ASC')->get()
        ]);
    }

    public function store()
    {
        request()->validate([
            'besar_pinjaman' => ['required', 'numeric'],
            'keperluan' => ['required', 'min:5'],
            'lama_angsuran_id' => ['required', 'numeric']
        ]);

        DB::beginTransaction();

        try {
            $besar_pinjaman = request('besar_pinjaman');
            $lama_angsuran = LamaAngsuran::findOrFail(request('lama_angsuran_id'));
            $potongan_awal = $besar_pinjaman * ($lama_angsuran->potongan_awal_persen / 100);
            $jumlah_diterima = $besar_pinjaman - $potongan_awal;
            $angsuran_pokok_bulan = $besar_pinjaman / 12;
            $jasa_pinjaman_bulan = $besar_pinjaman * ($lama_angsuran->jasa_pinjaman_bulan_persen / 100);
            $total_jumlah_angsuran_bulan = $angsuran_pokok_bulan + $jasa_pinjaman_bulan;
            $kode_baru = Pinjaman::buatKodeBaru();

            // create pinjaman
            Pinjaman::create([
                'anggota_id' => auth()->user()->anggota->id,
                'kode' => $kode_baru,
                'besar_pinjaman' => $besar_pinjaman,
                'keperluan' => request('keperluan'),
                'lama_angsuran_id' => request('lama_angsuran_id'),
                'potongan_awal' => $potongan_awal,
                'jumlah_diterima' => $jumlah_diterima,
                'angsuran_pokok_bulan' => $angsuran_pokok_bulan,
                'jasa_pinjaman_bulan' => $jasa_pinjaman_bulan,
                'total_jumlah_angsuran_bulan' => $total_jumlah_angsuran_bulan
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Pengajuan pinjaman berhasil dilakukan. Mohon tunggu beberapa waktu untuk peninjauan admin.');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', 'Pengajuan pinjaman anda ditolak admin.');
        }
    }

    public function show($kode)
    {
        if (auth()->user()->role !== 'anggota') {
            $item = Pinjaman::with(['anggota', 'angsuran', 'lama_angsuran'])->where('kode', $kode)->firstOrFail();
        } else {
            $item = Pinjaman::with(['anggota', 'angsuran', 'lama_angsuran'])->where([
                'kode' => $kode,
                'anggota_id' => auth()->user()->anggota->id
            ])->firstOrFail();
        }
        return view('pages.pinjaman.show', [
            'title' => 'Detail Pinjaman',
            'item' => $item
        ]);
    }


    public function update($id)
    {
        request()->validate([
            'status' => ['required', 'numeric']
        ]);


        DB::beginTransaction();
        try {
            $status = request('status');
            $item = Pinjaman::findOrFail($id);

            if ($status == 1) {
                // disetujui, dan buatkan angsurannya
                // set bulan awal, tahun awal, bulan sampai dan tahun sampai
                $tanggal_sekarang = Carbon::now()->translatedFormat('d');
                $bulan_sekarang = Carbon::now()->translatedFormat('m');
                $tahun_sekarang = Carbon::now()->translatedFormat('Y');
                $bulan_mulai = $bulan_sekarang + 1;
                $lama_angsuran = $item->lama_angsuran->durasi;
                $bulan_sampai = $bulan_mulai + $lama_angsuran;

                // looping lama angsuran
                for ($i = 0; $i < $lama_angsuran; $i++) {
                    // jika bulan lebih dari 12
                    $bulan_baru = $bulan_mulai + $i;
                    if ($bulan_baru > 12) {
                        $bulan_baru = 1 + $i;
                    }
                    // buat angsuran
                    $item->angsuran()->create([
                        'bulan' => $bulan_baru,
                        'tahun' => $tahun_sekarang
                    ]);
                }

                //cek jika bulan sampai melebihi 12
                if ($bulan_sampai > 12) {
                    $bulan_sampai = $bulan_sampai - 12;
                    $tahun_sampai = $tahun_sekarang + 1;
                } else {
                    $tahun_sampai = $tahun_sekarang;
                }

                // update
                $item->tanggal_diterima = Carbon::now()->translatedFormat('Y-m-d');
                $item->diterima_oleh = auth()->id();
                $item->bulan_mulai = $bulan_mulai;
                $item->tahun_mulai = $tahun_sekarang;
                $item->bulan_sampai = $bulan_sampai;
                $item->tahun_sampai = $tahun_sampai;
            }
            $item->status = $status;
            $item->save();
            DB::commit();

            return redirect()->back()->with('success', 'Pinjaman berhasil diupdate.');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', 'Pinjaman gagal diupdate.');
        }
    }

    public function destroy($id)
    {
        $item = Pinjaman::findOrFail($id);
        // cek pinjaman jika success
        if ($item->status == 1 || $item->status == 2) {
            return redirect()->back()->with('warning', 'Pinjaman tidak bisa dihapus!');
        }
        $item->delete();
        return redirect()->back()->with('success', 'Pinjaman berhasil dihapus.');
    }
}
