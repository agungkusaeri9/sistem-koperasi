<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    public function index()
    {
        $items = User::pegawai()->orderBy('name', 'ASC')->get();
        return view('pages.pegawai.index', [
            'title' => 'Pegawai',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.pegawai.create', [
            'title' => 'Tambah Pegawai'
        ]);
    }

    public function store()
    {
        request()->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['min:6', 'confirmed'],
            'role' => ['required', 'in:super admin,admin'],
            'is_active' => ['required', 'in:0,1'],
            'avatar' => ['image', 'mimes:jpg,jpeg,png,svg', 'max:2048']
        ]);

        DB::beginTransaction();
        try {
            $data = request()->only(['name', 'email', 'role', 'is_active']);
            $data['password'] = bcrypt(request('password'));
            request()->file('avatar') ? $data['avatar'] = request()->file('avatar')->store('users', 'public') : NULL;
            User::create($data);

            DB::commit();
            return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('pegawai.index')->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $items = User::where('id', '!=', auth()->id())->where('id', $id)->firstOrFail();
        return view('pages.pegawai.edit', [
            'title' => 'Edit User',
            'item' => $items
        ]);
    }

    public function update($id)
    {
        request()->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email,' . $id . ''],
            'role' => ['required', 'in:super admin,admin'],
            'is_active' => ['required', 'in:0,1'],
            'avatar' => ['image', 'mimes:jpg,jpeg,png,svg', 'max:2048']
        ]);


        if (request('password')) {
            request()->validate([
                'password' => ['min:5', 'confirmed'],
            ]);
        }

        DB::beginTransaction();
        try {
            $item = User::findOrFail($id);
            $data = request()->only(['name', 'email', 'role', 'is_active']);

            // cek jika ada password
            if (request('password'))
                $data['password'] = bcrypt(request('password'));

            // cek jika ada gambar
            if (request()->file('avatar')) {
                // ada di database
                if ($item->avatar)
                    Storage::disk('public')->delete($item->avatar);

                $data['avatar'] = request()->file('avatar')->store('users', 'public');
            }

            $item->update($data);
            DB::commit();
            return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('pegawai.index')->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = User::findOrFail($id);

        DB::beginTransaction();
        try {
            $item->delete();
            DB::commit();
            return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('pegawai.index')->with('error', $th->getMessage());
        }
    }
}
