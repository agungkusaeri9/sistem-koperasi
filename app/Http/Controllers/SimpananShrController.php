<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\MetodePembayaran;
use App\Models\PencairanSimpanan;
use App\Models\Pengaturan;
use App\Models\Periode;
use App\Models\Simpanan;
use App\Models\SimpananAnggota;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SimpananShrController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkRole:admin,anggota')->only(['pencairan']);
        $this->middleware('checkRole:anggota')->only(['tagihan', 'tagihan_bayar', 'proses_tagihan_bayar', 'saldo']);
        $this->middleware('checkRole:admin')->only(['index', 'edit', 'update', 'pencairan_create', 'cek_saldo', 'proses_pencairan', 'pencairan_edit', 'pencairan_update', 'pencairan_delete']);
    }

    public function index()
    {
        $status = request('status');
        // if ($status && $status === 'semua')
        //     $items = SimpananAnggota::with(['anggota'])->jenisShr()->latest()->get();
        // elseif ($status || $status === '0')
        //     $items = SimpananAnggota::where('status_tagihan', $status)->with(['anggota'])->jenisShr()->latest()->get();
        // else
        //     $items = SimpananAnggota::with(['anggota'])->jenisShr()->latest()->get();
        $items = Simpanan::jenisShr()->with(['anggota', 'periode'])->latest()->get();


        return view('pages.simpanan-shr.index', [
            'title' => 'Simpanan SHR',
            'items' => $items,
            'status' => $status === 'semua' ? 'semua' : $status
        ]);
    }

    public function create()
    {
        $data_anggota = Anggota::orderBy('nama', 'ASC')->get();
        $data_metode_pembayaran = MetodePembayaran::get();
        $data_bulan = Periode::getBulan();
        $data_tahun = Periode::getTahun();
        $data_periode = Periode::latest()->get();
        $pengaturan = Pengaturan::first();
        $data_periode = Periode::latest()->get();
        return view('pages.simpanan-shr.create', [
            'title' => 'Simpanan SHR',
            'data_anggota' => $data_anggota,
            'data_metode_pembayaran' => $data_metode_pembayaran,
            'data_bulan' => $data_bulan,
            'data_tahun' => $data_tahun,
            'data_periode' => $data_periode,
            'pengaturan' => $pengaturan,
            'data_periode' => $data_periode
        ]);
    }

    public function store(WhatsappService $whatsappService)
    {
        request()->validate([
            'anggota_id' => ['required'],
            'bulan' => ['required', 'numeric'],
            'tahun' => ['required', 'numeric'],
            'nominal' => ['required'],
            'metode_pembayaran_id' => ['required'],
            'status' => ['required'],
            'periode_id' => ['required'],
        ]);

        DB::beginTransaction();
        try {

            // cek apakah ada yang sama
            $cekSImpanan = Simpanan::where([
                'anggota_id' => request('anggota_id'),
                'jenis' => 'shr',
                'bulan' => request('bulan'),
                'tahun' => request('tahun'),
            ])->count();


            if ($cekSImpanan > 0) {
                return  redirect()->back()->with('error', 'Simpanan tersebut sudah ada di database.');
            }

            $data = request()->only(['anggota_id', 'bulan', 'tahun', 'nominal', 'metode_pembayaran_id', 'status', 'periode_id']);
            $data['jenis'] = 'shr';
            $data['uuid'] = \Str::uuid();
            $simpanan = Simpanan::create($data);

            DB::commit();
            return redirect()->route('simpanan-shr.index')->with('success', 'Simpanan SHR berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('simpanan-shr.index')->with('error', $th->getMessage());
        }
    }

    public function edit($uuid)
    {
        $item = Simpanan::where('uuid', $uuid)->firstOrFail();
        $data_anggota = Anggota::orderBy('nama', 'ASC')->get();
        $data_metode_pembayaran = MetodePembayaran::get();
        $data_bulan = Periode::getBulan();
        $data_tahun = Periode::getTahun();
        $data_periode = Periode::latest()->get();
        $pengaturan = Pengaturan::first();
        return view('pages.simpanan-shr.edit', [
            'title' => 'Edit Simpanan SHR',
            'data_anggota' => $data_anggota,
            'data_metode_pembayaran' => $data_metode_pembayaran,
            'data_bulan' => $data_bulan,
            'data_tahun' => $data_tahun,
            'data_periode' => $data_periode,
            'pengaturan' => $pengaturan,
            'item' => $item
        ]);
    }

    public function update(WhatsappService $whatsappService, $uuid)
    {
        request()->validate([
            'bulan' => ['required', 'numeric'],
            'tahun' => ['required', 'numeric'],
            'nominal' => ['required'],
            'metode_pembayaran_id' => ['required'],
            'status' => ['required'],
            'periode_id' => ['required']
        ]);

        DB::beginTransaction();
        try {
            $item = Simpanan::where('uuid', $uuid)->firstOrFail();

            $data = request()->only(['bulan', 'tahun', 'nominal', 'metode_pembayaran_id', 'status', 'periode_id']);
            $item->update($data);

            DB::commit();
            return redirect()->route('simpanan-shr.index')->with('success', 'Simpanan SHR berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('simpanan-shr.index')->with('error', $th->getMessage());
        }
    }

    public function destroy($uuid)
    {
        $item = Simpanan::where('uuid', $uuid)->firstOrFail();
        $item->delete();
        return redirect()->back()->with('success', 'Simpanan SHR berhasil dihapus.');
    }

    public function saldo()
    {
        $periode = Periode::where('status', 1)->first();
        $periode_id = request('periode_id');
        $simpanan = Simpanan::jenisShr()->where([
            'anggota_id' => auth()->user()->anggota->id,
            'status' => 2,
            'status_pencairan' => 0
        ]);
        if ($periode_id) {
            $simpanan->where('periode_id', $periode_id);
        } else {
            $simpanan->where('periode_id', $periode->id);
        }

        $saldo = $simpanan->sum('nominal');
        $items = $simpanan->latest()->get();

        $data_periode = Periode::latest()->get();
        return view('pages.simpanan-shr.saldo', [
            'title' => 'Informasi Saldo Simpanan SHR',
            'items' => $items,
            'saldo' => $saldo,
            'data_periode' => $data_periode,
            'periode' => $periode
        ]);
    }

    public function cek_saldo()
    {
        if (request()->ajax()) {
            $periode_id = request('periode_id');
            $anggota_id = request('anggota_id');

            if ($periode_id && $anggota_id) {
                $simpanan = Simpanan::jenisShr()->where([
                    'anggota_id' => $anggota_id,
                    'status' => 2,
                    'status_pencairan' => 0
                ]);
                if ($periode_id) {
                    $simpanan->where('periode_id', $periode_id);
                } else {
                    $simpanan->where('periode_id', $periode_id);
                }

                $saldo = $simpanan->sum('nominal');

                return response()->json([
                    'status' => 'success',
                    'saldo' => $saldo ?? 0,
                    'status_pencairan' => 0
                ]);
            }
        }
    }
}
