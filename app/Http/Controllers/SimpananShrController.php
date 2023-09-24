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

    public function proses_pencairan()
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

            PencairanSimpanan::create([
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
}
