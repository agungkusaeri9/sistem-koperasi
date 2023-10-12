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
            return redirect()->route('simpanan-wajib.index')->with('error', $th->getMessage());
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
            return redirect()->route('simpanan-wajib.index')->with('error', $th->getMessage());
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

    public function pencairan_create()
    {
        $data_anggota = Anggota::orderBy('nama', 'ASC')->get();
        $data_periode = Periode::latest()->get();
        return view('pages.simpanan-wajib.pencairan-create', [
            'title' => 'Buat Pencairan Simpanan Wajib',
            'data_anggota' => $data_anggota,
            'data_periode' => $data_periode
        ]);
    }


    public function pencairan_proses(WhatsappService $whatsappService)
    {
        request()->validate([
            'metode_pembayaran_id' => ['required', 'numeric'],
            'anggota_id' => ['required']
        ]);

        $anggota_id = request('anggota_id');

        // cek apakah simpanan sudah ada
        $cekPencairan = PencairanSimpanan::jenisWajib()->where('anggota_id', $anggota_id)->count();
        if ($cekPencairan > 0) {
            return redirect()->back()->with('error', 'Pencairan Simpanan wajib anggota tersebut sudah ada.');
        }
        // cek tagihan simpanan wajib dan shr yang belum bayar
        $cekSimpananBelumBayar = SimpananAnggota::where('status_tagihan', '!=', 2)->where('anggota_id', $anggota_id)->count();

        // cek tagihan pinjaman angsuran apakah ada yang belum di bayar
        $cekPinjamanAngsuran = Pinjaman::where([
            'status' => 1,
            'anggota_id' => $anggota_id
        ])->count();

        if ($cekSimpananBelumBayar > 0) {
            return redirect()->back()->with('error', 'Anda masih mempunyai tagihan simpanan wajib/shr yang belum dibayarkan.');
        }

        if ($cekPinjamanAngsuran > 0) {
            return redirect()->back()->with('error', 'Anda masih mempunyai tagihan pinjaman yang belum dibayarkan.');
        }


        DB::beginTransaction();
        try {
            $saldo = Simpanan::whereHas('simpanan_anggota', function ($sa) use ($anggota_id) {
                $sa->jenisWajib()->where([
                    'anggota_id' => $anggota_id,
                    'status_pencairan' => 0,
                    'status_tagihan' => 2
                ]);
            })->sum('nominal');

            if ($saldo < 1) {
                return redirect()->back()->with('error', 'Saldo yang anda miliki tidak mencukupi untuk melakukan pencairan dana simpanan wajib.');
            }

            $pencairan = PencairanSimpanan::create([
                'jenis' => 'wajib',
                'anggota_id' => $anggota_id,
                'nominal' => $saldo,
                'status' => 1,
                'periode_id' => NULL,
                'metode_pembayaran_id' => request('metode_pembayaran_id'),
                'bukti_pencairan' => request()->file('bukti_pencairan') ? request()->file('bukti_pencairan')->store('simpanan-shr/bukti-pencairan', 'public') : NULL
            ]);



            // update simpanan ke sudah dicairkan
            SimpananAnggota::jenisWajib()->where('anggota_id', $anggota_id)->update([
                'status_pencairan' => 1
            ]);
            // non aktifkan anggota
            Anggota::findOrFail($anggota_id)->update([
                'status' => 0
            ]);

            DB::commit();

            // kirim notifikasi ke anggota
            $whatsappService->anggota_pencairan_simpanan_wajib($pencairan->id);

            return redirect()->route('simpanan-wajib.pencairan.index')->with('success', 'Pencairan simpanan wajib berhasil ditambahkan.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function pencairan_delete($id)
    {
        $item = PencairanSimpanan::findOrFail($id);
        if ($item->status == 1) {
            return redirect()->back()->with('error', 'Pencairan Simpanan Wajib tidak bisa dihapus.');
        }
        $item->delete();
        return redirect()->back()->with('success', 'Pencairan Simpanan Wajib berhasil dihapus.');
    }

    public function pencairan_edit($id)
    {
        $item  = PencairanSimpanan::with('anggota')->findOrFail($id);
        $data_metode_pembayaran = MetodePembayaran::where('anggota_id', $item->anggota->id)->get();
        return view('pages.simpanan-wajib.pencairan-edit', [
            'title' => 'Buat Pencairan Simpanan Wajib',
            'item' => $item,
            'data_metode_pembayaran' => $data_metode_pembayaran
        ]);
    }

    public function pencairan_update($id)
    {
        request()->validate([
            'metode_pembayaran_id' => ['required'],
            'status' => ['required', 'in:0,1,2,3']
        ]);

        DB::beginTransaction();
        try {
            $item = PencairanSimpanan::findOrFail($id);

            // jika status gagal => 0, 2, 3
            if ($item->status != 1) {
                // jika status pencairan bukan sukses
                if (request('status') == 1) {
                    // jika pilihan = sukses
                    // update status simpanan di simpanan anggota
                    SimpananAnggota::jenisWajib()->where([
                        'anggota_id' => $item->anggota_id
                    ])->update([
                        'status_pencairan' => 1
                    ]);
                }
            } elseif ($item->status == 1) {
                if (request('status') != 1) {
                    // jika pilihan = sukses
                    // update status simpanan di simpanan anggota
                    SimpananAnggota::jenisWajib()->where([
                        'anggota_id' => $item->anggota_id
                    ])->update([
                        'status_pencairan' => 0
                    ]);
                }
            }

            $item->update([
                'metode_pembayaran_id' => request('metode_pembayaran_id'),
                'status' => request('status'),
                'bukti_pencairan' => request()->file('bukti_pencairan') ? request()->file('bukti_pencairan')->store('simpanan-shr/bukti-pencairan', 'public') : $item->bukti_pencairan
            ]);

            DB::commit();
            return redirect()->route('simpanan-wajib.pencairan.index')->with('success', 'Pencairan Simpanan Wajib berhasil di update');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('simpanan-wajib.pencairan.index')->with('error', $th->getMessage());
        }
    }

    public function cek_saldo()
    {
        if (request()->ajax()) {
            $anggota_id = request('anggota_id');

            if ($anggota_id) {
                $saldo = SimpananAnggota::jenisWajib()
                    ->withSum('simpanan', 'nominal') //
                    ->where('anggota_id', $anggota_id)
                    ->where('status_tagihan', 2)
                    ->where('status_pencairan', 0)
                    ->get();

                return response()->json([
                    'status' => 'success',
                    'saldo' => $saldo->sum('simpanan_sum_nominal') ?? 0
                ]);
            }
        }
    }
}
