<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodeController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkRole:admin');
    }

    public function index()
    {
        $items = Periode::orderBy('id', 'DESC')->get();
        return view('pages.periode.index', [
            'title' => 'Periode',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.periode.create', [
            'title' => 'Tambah Periode',
            'data_bulan' => Periode::getBulan(),
            'data_tahun' => Periode::getTahun()
        ]);
    }

    public function store()
    {
        request()->validate([
            'bulan_awal' => ['required', 'numeric'],
            'tahun_awal' => ['required', 'numeric'],
            'bulan_akhir' => ['required', 'numeric'],
            'tahun_akhir' => ['required', 'numeric'],
            'nominal_simpanan_shr' => ['required', 'numeric'],
            'nominal_simpanan_wajib' => ['required', 'numeric'],
            'status' => ['required']
        ]);

        DB::beginTransaction();
        try {
            $data = request()->all();
            if (request('status') == 1) {
                $cekStatusAktif = Periode::where('status', 1);
                if ($cekStatusAktif->count() > 0) {
                    $cekStatusAktif->update([
                        'status' => 0
                    ]);
                }
            }
            Periode::create($data);
            DB::commit();
            return redirect()->route('periode.index')->with('success', 'Periode berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('periode.index')->with('error', 'Mohon Maaf Ada Kesalahan Sistem!');
        }
    }

    public function edit($id)
    {
        $item = Periode::findOrFail($id);
        return view('pages.periode.edit', [
            'title' => 'Edit Periode',
            'item' => $item,
            'data_bulan' => Periode::getBulan(),
            'data_tahun' => Periode::getTahun()
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'bulan_awal' => ['required', 'numeric'],
            'tahun_awal' => ['required', 'numeric'],
            'bulan_akhir' => ['required', 'numeric'],
            'tahun_akhir' => ['required', 'numeric'],
            'nominal_simpanan_shr' => ['required', 'numeric'],
            'nominal_simpanan_wajib' => ['required', 'numeric']
        ]);

        DB::beginTransaction();
        try {
            $item = Periode::findOrFail($id);
            $data = request()->all();

            // cek status apakah tidak sama dengan status di form
            if ($item->status == 0 && request('status') == 1) {
                // cek apakah di database ada status nya satu, kalau ada set jadi 0 yang ada di database
                $cekStatusAktif = Periode::where('status', 1);

                if ($cekStatusAktif->count() > 0) {
                    $cekStatusAktif->update([
                        'status' => 0
                    ]);
                }
            }

            $item->update($data);
            DB::commit();
            return redirect()->route('periode.index')->with('success', 'Periode berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('periode.index')->with('error', 'Mohon Maaf Ada Kesalahan Sistem!');
        }
    }

    public function destroy($id)
    {
        $item = Periode::findOrFail($id);

        DB::beginTransaction();
        try {
            $item->delete();
            DB::commit();
            return redirect()->route('periode.index')->with('success', 'Periode berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('periode.index')->with('error', 'Data Tidak Bisa Dihapus!');
        }
    }

    public function cekNominalSimpanan()
    {
        if (request()->ajax()) {
            request()->validate([
                'jenis' => ['required'],
                'periode_id' => ['required']
            ]);
            $periode_id = request('periode_id');
            $jenis = request('jenis');

            $item = Periode::where('id', $periode_id)->first();
            if ($item) {
                if ($jenis === 'shr') {
                    $nominal = $item->nominal_simpanan_shr;
                } else {
                    $nominal = $item->nominal_simpanan_wajib;
                }
                $status = 'success';
            } else {
                $status = 'error';
            }

            return response()->json([
                'status' => $status,
                'nominal' => $nominal
            ]);
        }
    }
}
