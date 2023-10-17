<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\MetodePembayaran;
use App\Models\PencairanSimpanan;
use App\Models\Periode;
use App\Models\Simpanan;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PencairanSimpananShrController extends Controller
{
    public function index()
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
        return view('pages.simpanan-shr.pencairan.index', [
            'title' => 'Pencairan Simpanan SHR',
            'items' => $items,
            'data_periode' => $data_periode,
            'data_anggota' => $data_anggota,
            'anggota_id' => $anggota_id ?? NULL,
            'periode_id' => $periode_id ?? NULL
        ]);
    }

    public function create()
    {
        $data_anggota = Anggota::orderBy('nama', 'ASC')->get();
        $data_periode = Periode::latest()->get();
        $data_metode_pembayaran = MetodePembayaran::get();
        return view('pages.simpanan-shr.pencairan.create', [
            'title' => 'Buat Pencairan Simpanan SHR',
            'data_anggota' => $data_anggota,
            'data_periode' => $data_periode,
            'data_metode_pembayaran' => $data_metode_pembayaran
        ]);
    }



    public function store(WhatsappService $whatsappService)
    {
        request()->validate([
            'periode_id' => ['required'],
            'anggota_id' => ['required'],
            'metode_pembayaran_id' => ['required'],
        ]);

        DB::beginTransaction();
        try {

            $anggota_id = request('anggota_id');
            $periode_id = request('periode_id');
            $metode_pembayaran_id = request('metode_pembayaran_id');

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
                'metode_pembayaran_id' => $metode_pembayaran_id
            ]);


            // update status pencairan simpanan
            Simpanan::jenisShr()->where([
                'anggota_id' => $anggota_id,
                'periode_id' => $periode_id
            ])->update([
                'status_pencairan' => 1
            ]);

            // notifikasi ke anggota
            $whatsappService->anggota_pencairan_simpanan_shr($pencairan->id);
            DB::commit();


            return redirect()->route('pencairan-simpanan-shr.index')->with('success', 'Pencairan Simpanan SHR berhasil dibuat.');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return redirect()->route('pencairan-simpanan-shr.index')->with('error', $th->getMessage());
        }
    }

    public function pencairan_edit($id)
    {
        $item  = PencairanSimpanan::with('anggota')->findOrFail($id);
        $data_metode_pembayaran = MetodePembayaran::get();
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
                    Simpanan::jenisShr()->where([
                        'anggota_id' => $item->anggota_id,
                        'periode_id' => $periode_id
                    ])->update([
                        'status_pencairan' => 1
                    ]);
                }
            } elseif ($item->status == 1) {
                if (request('status') != 1) {
                    // jika pilihan = sukses
                    $periode_id = $item->periode_id;
                    // update status simpanan di simpanan anggota
                    Simpanan::jenisShr()->where([
                        'anggota_id' => $item->anggota_id,
                        'periode_id' => $periode_id
                    ])->update([
                        'status_pencairan' => 0
                    ]);
                }
            }
            $item->update([
                'metode_pembayaran_id' => request('metode_pembayaran_id'),
                'status' => request('status')
            ]);
            DB::commit();
            return redirect()->route('pencairan-simpanan-shr.index')->with('success', 'Pencairan Simpanan SHR berhasil di update');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('pencairan-simpanan-shr.index')->with('error', $th->getMessage());
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
