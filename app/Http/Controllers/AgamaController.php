<?php

namespace App\Http\Controllers;

use App\Models\Agama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AgamaController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkRole:admin');
    }

    public function index()
    {
        $items = Agama::orderBy('nama', 'ASC')->get();
        return view('pages.agama.index', [
            'title' => 'Agama',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.agama.create', [
            'title' => 'Tambah Agama'
        ]);
    }

    public function store()
    {
        request()->validate([
            'nama' => ['required', 'min:3', 'unique:agama']
        ]);

        DB::beginTransaction();
        try {
            $data = request()->only(['nama']);
            Agama::create($data);

            DB::commit();
            return redirect()->route('agama.index')->with('success', 'Agama berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('agama.index')->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $item = Agama::findOrFail($id);
        return view('pages.agama.edit', [
            'title' => 'Edit Agama',
            'item' => $item
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'nama' => ['required', 'min:3', Rule::unique('agama', 'nama')->ignore($id)]
        ]);

        DB::beginTransaction();
        try {
            $item = Agama::findOrFail($id);
            $data = request()->only(['nama']);
            $item->update($data);
            DB::commit();
            return redirect()->route('agama.index')->with('success', 'Agama berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('agama.index')->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = Agama::findOrFail($id);

        DB::beginTransaction();
        try {
            $item->delete();
            DB::commit();
            return redirect()->route('agama.index')->with('success', 'Agama berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('agama.index')->with('error', $th->getMessage());
        }
    }
}
