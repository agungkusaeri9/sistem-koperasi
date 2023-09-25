<?php

namespace App\Http\Controllers;

use App\Models\JenisSimpanan;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class JenisSimpananController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkRole:admin');
    }


    public function index()
    {
        $items = JenisSimpanan::orderBy('jenis', 'ASC')->get();
        return view('pages.jenis-simpanan.index', [
            'title' => 'Jenis Simpanan',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.jenis-simpanan.create', [
            'title' => 'Tambah Jenis Simpanan',
            'data_periode' => Periode::get()
        ]);
    }

    public function store()
    {
        request()->validate([
            'jenis' => ['required'],
            'nominal' => ['required', 'numeric'],
            'periode_id' => [Rule::when(request('periode_id') != NULL, ['numeric'])]
        ]);

        DB::beginTransaction();
        try {
            $data = request()->only(['jenis', 'nominal', 'periode_id']);
            JenisSimpanan::create($data);

            DB::commit();
            return redirect()->route('jenis-simpanan.index')->with('success', 'Jenis Simpanan berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('jenis-simpanan.index')->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $item = JenisSimpanan::findOrFail($id);
        return view('pages.jenis-simpanan.edit', [
            'title' => 'Edit Jenis Simpanan',
            'item' => $item,
            'data_periode' => Periode::get()
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'jenis' => ['required'],
            'nominal' => ['required', 'numeric'],
            'periode_id' => [Rule::when(request('periode_id') != NULL, ['numeric'])]
        ]);

        DB::beginTransaction();
        try {
            $item = JenisSimpanan::findOrFail($id);
            $data = request()->only(['jenis', 'nominal', 'periode_id']);
            $item->update($data);
            DB::commit();
            return redirect()->route('jenis-simpanan.index')->with('success', 'Jenis Simpanan berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('jenis-simpanan.index')->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = JenisSimpanan::findOrFail($id);

        DB::beginTransaction();
        try {
            $item->delete();
            DB::commit();
            return redirect()->route('jenis-simpanan.index')->with('success', 'Jenis Simpanan berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('jenis-simpanan.index')->with('error', $th->getMessage());
        }
    }
}
