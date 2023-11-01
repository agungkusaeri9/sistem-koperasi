<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\MetodePembayaran;
use App\Models\PencairanSimpanan;
use App\Models\Pengaturan;
use App\Models\Periode;
use App\Models\Pinjaman;
use App\Models\PinjamanAngsuran;
use App\Models\Simpanan;
use App\Models\SimpananAnggota;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Sabberworm\CSS\Settings;

class SimpananWajibController extends Controller
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
        // if ($status === 'semua')
        //     $items = Simpanan::with(['anggota'])->jenisWajib()->latest()->get();
        // elseif ($status)
        //     $items = Simpanan::where('status_tagihan', $status)->with(['anggota'])->jenisWajib()->latest()->get();
        // else
        //     $items = Simpanan::with(['anggota'])->jenisWajib()->latest()->get();
        $items = Simpanan::jenisWajib()->with('anggota')->latest()->get();

        return view('pages.simpanan-wajib.index', [
            'title' => 'Simpanan Wajib',
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
        return view('pages.simpanan-wajib.create', [
            'title' => 'Simpanan Wajib',
            'data_anggota' => $data_anggota,
            'data_metode_pembayaran' => $data_metode_pembayaran,
            'data_bulan' => $data_bulan,
            'data_tahun' => $data_tahun,
            'data_periode' => $data_periode,
            'pengaturan' => $pengaturan
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
            'status' => ['required']
        ]);

        DB::beginTransaction();
        try {

            // cek apakah ada yang sama
            $cekSImpanan = Simpanan::where([
                'anggota_id' => request('anggota_id'),
                'jenis' => 'wajib',
                'bulan' => request('bulan'),
                'tahun' => request('tahun'),
            ])->count();


            if ($cekSImpanan > 0) {
                return  redirect()->back()->with('error', 'Simpanan tersebut sudah ada di database.');
            }

            $data = request()->only(['anggota_id', 'bulan', 'tahun', 'nominal', 'metode_pembayaran_id', 'status']);
            $data['jenis'] = 'wajib';
            $data['uuid'] = \Str::uuid();
            $simpanan = Simpanan::create($data);

            DB::commit();
            return redirect()->route('simpanan-wajib.index')->with('success', 'Simpanan Wajib berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('simpanan-wajib.index')->with('error',  'Mohon Maaf Ada Kesalahan Sistem!');
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
        return view('pages.simpanan-wajib.edit', [
            'title' => 'Edit Simpanan Wajib',
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
            'status' => ['required']
        ]);

        DB::beginTransaction();
        try {
            $item = Simpanan::where('uuid', $uuid)->firstOrFail();

            $data = request()->only(['bulan', 'tahun', 'nominal', 'metode_pembayaran_id', 'status']);
            $item->update($data);

            DB::commit();
            return redirect()->route('simpanan-wajib.index')->with('success', 'Simpanan Wajib berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('simpanan-wajib.index')->with('error',  'Mohon Maaf Ada Kesalahan Sistem!');
        }
    }

    public function destroy($uuid)
    {
        $item = Simpanan::where('uuid', $uuid)->firstOrFail();
        $item->delete();
        return redirect()->back()->with('success', 'Simpanan Wajib berhasil dihapus.');
    }


    public function saldo()
    {
        $simpanan = Simpanan::jenisWajib()->where([
            'anggota_id' => auth()->user()->anggota->id,
            'status' => 2,
            'status_pencairan' => 0
        ]);


        $saldo = $simpanan->sum('nominal');
        $items = $simpanan->latest()->get();
        return view('pages.simpanan-wajib.saldo', [
            'title' => 'Informasi Saldo Simpanan Wajib',
            'items' => $items,
            'saldo' => $saldo
        ]);
    }


    public function pencairan()
    {
        $items = PencairanSimpanan::where('jenis', 'wajib')->latest()->get();
        return view('pages.simpanan-wajib.pencairan', [
            'title' => 'Pencairan Simpanan Wajib',
            'items' => $items
        ]);
    }

    public function cek_saldo()
    {
        if (request()->ajax()) {
            $anggota_id = request('anggota_id');

            $simpanan = Simpanan::jenisWajib()->where([
                'anggota_id' => $anggota_id,
                'status' => 2,
                'status_pencairan' => 0
            ]);

            $saldo = $simpanan->sum('nominal');
            return response()->json([
                'status' => 'success',
                'saldo' => $saldo
            ]);
        }
    }
}
