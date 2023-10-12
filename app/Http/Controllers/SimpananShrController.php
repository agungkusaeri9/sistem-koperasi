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
            'periode_id' => ['required']
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

    public function pencairan()
    {
        if (auth()->user()->role !== 'anggota') {
            $anggota_id = request('anggota_id');
            $periode_id = request('periode_id');


            $items = PencairanSimpanan::jenisShr()->latest();

            if ($anggota_id)
                $items = $items->where('anggota_id', $anggota_id);

            if ($periode_id)
                $items = $items->where('periode_id', $periode_id);


            $items = $items->get();
        } else {

            $items = PencairanSimpanan::jenisShr()->where('anggota_id', auth()->user()->anggota->id)->latest()->get();
        }
        $data_periode = Periode::latest()->get();
        $data_anggota = Anggota::orderBy('nama', 'ASC')->get();
        return view('pages.simpanan-shr.pencairan', [
            'title' => 'Pencairan Simpanan SHR',
            'items' => $items,
            'data_periode' => $data_periode,
            'data_anggota' => $data_anggota,
            'anggota_id' => $anggota_id ?? NULL,
            'periode_id' => $periode_id ?? NULL
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

                $status_pencairan = PencairanSimpanan::jenisShr()->where([
                    'anggota_id' => $anggota_id,
                    'periode_id' => $periode_id
                ])->first();

                return response()->json([
                    'status' => 'success',
                    'saldo' => $saldo->sum('simpanan_sum_nominal') ?? 0,
                    'status_pencairan' => $status_pencairan ? 1 : 0
                ]);
            }
        }
    }

    public function proses_pencairan(WhatsappService $whatsappService)
    {
        request()->validate([
            'periode_id' => ['required'],
            'anggota_id' => ['required'],
            'metode_pembayaran_id' => ['required'],
            'bukti_pencairan' => [Rule::when(request()->file('bukti_pencairan'), ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'])]
        ]);

        DB::beginTransaction();
        try {

            $anggota_id = request('anggota_id');
            $periode_id = request('periode_id');
            $metode_pembayaran_id = request('metode_pembayaran_id');

            $simpanan = SimpananAnggota::jenisShr()
                ->withSum('simpanan', 'nominal') // 'nominal' adalah nama kolom yang ingin Anda jumlahkan
                ->whereHas('simpanan', function ($simpanan) use ($periode_id) {
                    $simpanan->where('periode_id', $periode_id);
                })
                ->where('anggota_id', $anggota_id)
                ->get();

            $saldo = $simpanan->sum('simpanan_sum_nominal') ?? 0;

            // cek apakah simpanan sudah dicairkan
            $cekSimpananShr = PencairanSimpanan::jenisShr()->where([
                'anggota_id' => $anggota_id,
                'periode_id' => $periode_id
            ])->count();

            if ($cekSimpananShr > 0) {
                return redirect()->back()->with('error', 'Simpanan SHR periode tersebut sebelumnya sudah dicairkan.');
            }

            // cek ketika periode aktif, maka tidak bisa
            $cekPeriodeAktif = Periode::find($periode_id);
            if ($cekPeriodeAktif->status == 1) {
                return redirect()->back()->with('error', 'Simpanan SHR periode tersebut sedang aktif dan tidak bisa dicairkan.');
            }

            // cek apabila nominal kurang dari 1
            if ($saldo < 1) {
                return redirect()->back()->with('error', 'Saldo Simpanan SHR kosong dan tidak bisa dicairkan.');
            }

            $pencairan = PencairanSimpanan::create([
                'jenis' => 'shr',
                'anggota_id' => $anggota_id,
                'nominal' => $saldo,
                'status' => 1,
                'periode_id' => $periode_id,
                'metode_pembayaran_id' => $metode_pembayaran_id,
                'bukti_pencairan' => request()->file('bukti_pencairan') ? request()->file('bukti_pencairan')->store('simpanan-shr/bukti-pencairan', 'public') : NULL
            ]);


            // update status simpanan di simpanan anggota
            SimpananAnggota::jenisShr()->where([
                'anggota_id' => $anggota_id,
                'status_pencairan' => 0
            ])->whereHas('simpanan', function ($simpanan) use ($periode_id) {
                $simpanan->where('periode_id', $periode_id);
            })->update([
                'status_pencairan' => 1
            ]);

            // notifikasi ke anggota
            $whatsappService->anggota_pencairan_simpanan_shr($pencairan->id);
            DB::commit();


            return redirect()->route('simpanan-shr.pencairan.index')->with('success', 'Pencairan Simpanan SHR berhasil dibuat.');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return redirect()->route('simpanan-shr.pencairan.index')->with('error', $th->getMessage());
        }
    }

    public function pencairan_edit($id)
    {
        $item  = PencairanSimpanan::with('anggota')->findOrFail($id);
        $data_metode_pembayaran = MetodePembayaran::where('anggota_id', $item->anggota->id)->get();
        return view('pages.simpanan-shr.pencairan-edit', [
            'title' => 'Buat Pencairan Simpanan SHR',
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
                    $periode_id = $item->periode_id;
                    // update status simpanan di simpanan anggota
                    SimpananAnggota::jenisShr()->where([
                        'anggota_id' => $item->anggota_id
                    ])->whereHas('simpanan', function ($simpanan) use ($periode_id) {
                        $simpanan->where('periode_id', $periode_id);
                    })->update([
                        'status_pencairan' => 1
                    ]);
                }
            } elseif ($item->status == 1) {
                if (request('status') != 1) {
                    // jika pilihan = sukses
                    $periode_id = $item->periode_id;
                    // update status simpanan di simpanan anggota
                    SimpananAnggota::jenisShr()->where([
                        'anggota_id' => $item->anggota_id
                    ])->whereHas('simpanan', function ($simpanan) use ($periode_id) {
                        $simpanan->where('periode_id', $periode_id);
                    })->update([
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
            return redirect()->route('simpanan-shr.pencairan.index')->with('success', 'Pencairan Simpanan SHR berhasil di update');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('simpanan-shr.pencairan.index')->with('error', $th->getMessage());
        }
    }

    public function pencairan_delete($id)
    {
        $item = PencairanSimpanan::findOrFail($id);
        if ($item->status == 1) {
            return redirect()->back()->with('error', 'Pencairan Simpanan SHR tidak bisa dihapus.');
        }
        $item->delete();
        return redirect()->back()->with('success', 'Pencairan Simpanan SHR berhasil dihapus.');
    }
}
