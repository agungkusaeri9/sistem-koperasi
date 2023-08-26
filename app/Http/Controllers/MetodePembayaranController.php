<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MetodePembayaranController extends Controller
{
    public function index()
    {
        $items = MetodePembayaran::orderBy('nama', 'ASC')->get();
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
            MetodePembayaran::create($data);

            DB::commit();
            return redirect()->route('metode-pembayaran.index')->with('success', 'Metode Pembayaran berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('metode-pembayaran.index')->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $item = MetodePembayaran::findOrFail($id);
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
            return redirect()->route('metode-pembayaran.index')->with('error', $th->getMessage());
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
            return redirect()->route('metode-pembayaran.index')->with('error', $th->getMessage());
        }
    }
}
