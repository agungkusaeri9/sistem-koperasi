<?php

namespace App\Http\Controllers;

use App\Models\LamaAngsuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LamaAngsuranController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkRole:admin')->except('show');
    }


    public function index()
    {
        $items = LamaAngsuran::orderBy('durasi', 'ASC')->get();
        return view('pages.lama-angsuran.index', [
            'title' => 'Lama Angsuran',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.lama-angsuran.create', [
            'title' => 'Tambah Lama Angsuran'
        ]);
    }

    public function store()
    {
        request()->validate([
            'durasi' => ['required', 'numeric', 'unique:lama_angsuran,durasi'],
            'potongan_awal_persen' => ['required', 'numeric'],
            'jasa_pinjaman_bulan_persen' => ['required', 'numeric'],
            'jenis' => ['required', 'in:Jangka Pendek,Jangka Panjang']
        ]);

        DB::beginTransaction();
        try {
            $data = request()->only(['durasi', 'potongan_awal_persen', 'jasa_pinjaman_bulan_persen', 'jenis']);
            LamaAngsuran::create($data);

            DB::commit();
            return redirect()->route('lama-angsuran.index')->with('success', 'Lama Angsuran berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('lama-angsuran.index')->with('error', 'Mohon Maaf Ada Kesalahan Sistem!');
        }
    }

    public function show($id)
    {
        $item = LamaAngsuran::where('id', $id)->first();
        if ($item) {
            return response()->json([
                'status' => 'success',
                'data' => $item
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'data' => NULL
            ]);
        }
    }

    public function edit($id)
    {
        $item = LamaAngsuran::findOrFail($id);
        return view('pages.lama-angsuran.edit', [
            'title' => 'Edit Lama Angsuran',
            'item' => $item
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'durasi' => ['required', 'numeric', 'unique:lama_angsuran,durasi,' . $id . ''],
            'potongan_awal_persen' => ['required', 'numeric'],
            'jasa_pinjaman_bulan_persen' => ['required', 'numeric'],
            'jenis' => ['required', 'in:Jangka Pendek,Jangka Panjang']
        ]);

        DB::beginTransaction();
        try {
            $item = LamaAngsuran::findOrFail($id);
            $data = request()->only(['durasi', 'potongan_awal_persen', 'jasa_pinjaman_bulan_persen', 'jenis']);

            $item->update($data);
            DB::commit();
            return redirect()->route('lama-angsuran.index')->with('success', 'Lama Angsuran berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('lama-angsuran.index')->with('error', 'Mohon Maaf Ada Kesalahan Sistem!');
        }
    }

    public function destroy($id)
    {
        $item = LamaAngsuran::findOrFail($id);

        DB::beginTransaction();
        try {
            $item->delete();
            DB::commit();
            return redirect()->route('lama-angsuran.index')->with('success', 'Lama Angsuran berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('lama-angsuran.index')->with('error', 'Data Tidak Dapat Dihapus!');
        }
    }
}
