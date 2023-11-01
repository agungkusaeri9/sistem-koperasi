<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MetodePembayaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkRole:admin,anggota')->only(['index', 'create', 'store', 'get_json_by_anggota']);
        $this->middleware('checkRole:admin')->only(['edit', 'update', 'destroy']);
    }


    public function index()
    {
        if (auth()->user()->role === 'anggota') {
            $items = MetodePembayaran::byAnggota()->orderBy('nama', 'ASC')->get();
        } else {
            $items = MetodePembayaran::orderBy('nama', 'ASC')->get();
        }
        return view('pages.metode-pembayaran.index', [
            'title' => 'Metode Pembayaran',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.metode-pembayaran.create', [
            'title' => 'Tambah Pegawai'
        ]);
    }

    public function store()
    {
        request()->validate([
            'nama' => ['required', 'min:3']
        ]);

        DB::beginTransaction();
        try {
            $data = request()->only(['nama', 'nomor', 'pemilik']);
            auth()->user()->anggota ? $data['anggota_id'] = auth()->user()->anggota->id : NULL;
            MetodePembayaran::create($data);

            DB::commit();
            return redirect()->route('metode-pembayaran.index')->with('success', 'Metode Pembayaran berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('metode-pembayaran.index')->with('error', 'Mohon Maaf Ada Kesalahan Sistem!');
        }
    }

    public function edit($id)
    {
        if (auth()->user()->role === 'anggota') {
            $item = MetodePembayaran::byAnggota()->findOrFail($id);
        } else {
            $item = MetodePembayaran::findOrFail($id);
        }
        return view('pages.metode-pembayaran.edit', [
            'title' => 'Edit Metode Pembayaran',
            'item' => $item
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'nama' => ['required', 'min:3']
        ]);

        DB::beginTransaction();
        try {
            $item = MetodePembayaran::findOrFail($id);
            $data = request()->only(['nama', 'nomor', 'pemilik']);
            $item->update($data);
            DB::commit();
            return redirect()->route('metode-pembayaran.index')->with('success', 'Metode Pembayaran berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('metode-pembayaran.index')->with('error', 'Mohon Maaf Ada Kesalahan Sistem!');
        }
    }

    public function destroy($id)
    {
        $item = MetodePembayaran::findOrFail($id);
        DB::beginTransaction();
        try {
            $item->delete();
            DB::commit();
            return redirect()->route('metode-pembayaran.index')->with('success', 'Metode Pembayaran berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('metode-pembayaran.index')->with('error', 'Data Tidak Dapat Dihapus!');
        }
    }

    public function get_json_by_anggota()
    {
        if (request()->ajax()) {
            $anggota_id  = request('anggota_id');
            if ($anggota_id) {
                $metode_pembayaran = MetodePembayaran::where('anggota_id', $anggota_id)->get();
                return response()->json([
                    'status' => 'success',
                    'data' => $metode_pembayaran
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'data' => NULL
                ]);
            }
        }
    }
}
