<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PinjamanController extends Controller
{
    public function index()
    {
        $items = Pinjaman::with('anggota')->latest()->get();
        return view('pages.pinjaman.index', [
            'title' => 'Pinjaman',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.pinjaman.create', [
            'title' => 'Tambah Pegawai'
        ]);
    }

    public function show($kode)
    {
        $item = Pinjaman::with(['anggota', 'angsuran', 'lama_angsuran'])->where('kode', $kode)->firstOrFail();
        return view('pages.pinjaman.show', [
            'title' => 'Detail Pinjaman',
            'item' => $item
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'status' => ['required', 'numeric']
        ]);


        DB::beginTransaction();
        try {
            $status = request('status');
            $item = Pinjaman::findOrFail($id);
            $item->update([
                'status' => $status
            ]);
            DB::commit();

            return redirect()->back()->with('success', 'Pinjaman berhasil diupdate.');
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', 'Pinjaman gagal diupdate.');
        }
    }
}
