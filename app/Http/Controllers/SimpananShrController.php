<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\MetodePembayaran;
use App\Models\PencairanSimpanan;
use App\Models\Periode;
use App\Models\Simpanan;
use App\Models\SimpananAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SimpananShrController extends Controller
{

    public function index()
    {
        $status = request('status');
        if ($status && $status === 'semua')
            $items = SimpananAnggota::with(['anggota'])->jenisShr()->latest()->get();
        elseif ($status || $status === '0')
            $items = SimpananAnggota::where('status_tagihan', $status)->with(['anggota'])->jenisShr()->latest()->get();
        else
            $items = SimpananAnggota::with(['anggota'])->jenisShr()->latest()->get();


        return view('pages.simpanan-shr.index', [
            'title' => 'Simpanan SHR',
            'items' => $items,
            'status' => $status === 'semua' ? 'semua' : $status
        ]);
    }

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

    public function edit($id)
    {
        $item = SimpananAnggota::jenisShr()->where('id', $id)->firstOrFail();
        return view('pages.simpanan-shr.edit', [
            'title' => 'Edit Simpanan SHR',
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
            $item = SimpananAnggota::jenisShr()->where('id', $id)->firstOrFail();
            $item->update($data);

            DB::commit();
            return redirect()->route('simpanan-shr.index')->with('success', 'Simpanan SHR berhasil diupdate.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('simpanan-shr.index')->with('error', $th->getMessage());
        }
    }

    public function pencairan()
    {
        if (auth()->user()->role !== 'anggota') {
            $items = PencairanSimpanan::jenisShr()->latest()->get();
        } else {
            $items = PencairanSimpanan::jenisShr()->where('anggota_id', auth()->user()->anggota->id)->latest()->get();
        }
        return view('pages.simpanan-shr.pencairan', [
            'title' => 'Pencairan Simpanan SHR',
            'items' => $items
        ]);
    }

    public function pencairan_create()
    {
        $data_anggota = Anggota::orderBy('nama', 'ASC')->get();
        $data_periode = Periode::latest()->get();
        return view('pages.simpanan-shr.pencairan-create', [
            'title' => 'Buat Pencairan Simpanan SHR',
            'data_anggota' => $data_anggota,
            'data_periode' => $data_periode
        ]);
    }

    public function cek_saldo()
    {
        if (request()->ajax()) {
            $periode_id = request('periode_id');
            $anggota_id = request('anggota_id');

            if ($periode_id && $anggota_id) {
                $saldo = SimpananAnggota::jenisShr()
                    ->withSum('simpanan', 'nominal') // 'nominal' adalah nama kolom yang ingin Anda jumlahkan
                    ->whereHas('simpanan', function ($simpanan) use ($periode_id) {
                        $simpanan->where('periode_id', $periode_id);
                    })
                    ->where('anggota_id', $anggota_id)
                    ->get();

                return response()->json([
                    'status' => 'success',
                    'saldo' => $saldo->sum('simpanan_sum_nominal') ?? 0,
                    'status_pencairan' => $saldo->first()->simpanan->status_pencairan ?? 0
                ]);
            }
        }
    }

    public function proses_pencairan()
    {
        request()->validate([
            'periode_id' => ['required'],
            'anggota_id' => ['required'],
            'metode_pembayaran_id' => ['required']
        ]);

        DB::beginTransaction();
        try {

            $anggota_id = request('anggota_id');
            $periode_id = request('periode_id');

            $simpanan = SimpananAnggota::jenisShr()
                ->withSum('simpanan', 'nominal') // 'nominal' adalah nama kolom yang ingin Anda jumlahkan
                ->whereHas('simpanan', function ($simpanan) use ($periode_id) {
                    $simpanan->where('periode_id', $periode_id);
                })
                ->where('anggota_id', $anggota_id)
                ->get();

            $saldo = $simpanan->sum('simpanan_sum_nominal') ?? 0;

            PencairanSimpanan::create([
                'jenis' => 'shr',
                'anggota_id' => request('anggota_id'),
                'nominal' => $saldo,
                'status' => 1,
                'periode_id' => request('periode_id'),
                'metode_pembayaran_id' => request('metode_pembayaran_id')
            ]);

            DB::commit();

            return redirect()->route('simpanan-shr.pencairan.index')->with('success', 'Pencairan Simpanan SHR berhasil dibuat.');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return redirect()->route('simpanan-shr.pencairan.index')->with('error', $th->getMessage());
        }
    }
}
