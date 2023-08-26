<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class JabatanController extends Controller
{
    public function index()
    {
        $items = Jabatan::orderBy('nama', 'ASC')->get();
        return view('pages.jabatan.index', [
            'title' => 'Jabatan',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.jabatan.create', [
            'title' => 'Tambah Pegawai'
        ]);
    }

    public function store()
    {
        request()->validate([
            'nama' => ['required', 'min:3', 'unique:jabatan']
        ]);

        DB::beginTransaction();
        try {
            $data = request()->only(['nama']);
            Jabatan::create($data);

            DB::commit();
            return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('jabatan.index')->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $item = Jabatan::findOrFail($id);
        return view('pages.jabatan.edit', [
            'title' => 'Edit Jabatan',
            'item' => $item
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'nama' => ['required', 'min:3', Rule::unique('jabatan', 'nama')->ignore($id)]
        ]);

        DB::beginTransaction();
        try {
            $item = Jabatan::findOrFail($id);
            $data = request()->only(['nama']);
            $item->update($data);
            DB::commit();
            return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('jabatan.index')->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = Jabatan::findOrFail($id);

        DB::beginTransaction();
        try {
            $item->delete();
            DB::commit();
            return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('jabatan.index')->with('error', $th->getMessage());
        }
    }
}
