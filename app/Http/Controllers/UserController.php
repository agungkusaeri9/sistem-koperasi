<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name', 'ASC')->get();
        return view('pages.user.index', [
            'title' => 'User',
            'users' => $users
        ]);
    }

    public function create()
    {
        return view('pages.user.create', [
            'title' => 'Tambah User'
        ]);
    }

    public function store()
    {
        request()->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'unique:users,email'],
            'password' => ['min:5', 'confirmed'],
            'role' => ['required'],
            'avatar' => ['image', 'mimes:jpg,jpeg,png,svg', 'max:2048']
        ]);

        DB::beginTransaction();
        try {
            $data = request()->only(['name', 'email', 'role']);
            $data['password'] = bcrypt(request('password'));
            request()->file('avatar') ? $data['avatar'] = request()->file('avatar')->store('users', 'public') : NULL;
            User::create($data);

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function edit($id)
    {
        $user = User::where('id', '!=', auth()->id())->where('id', $id)->firstOrFail();
        return view('pages.user.edit', [
            'title' => 'Edit User',
            'user' => $user
        ]);
    }

    public function update($id)
    {
        $user = User::findOrFail($id);
        request()->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'unique:users,email,' . $id . ''],
            'role' => ['required'],
            'avatar' => ['image', 'mimes:jpg,jpeg,png,svg', 'max:2048']
        ]);

        if (request('password')) {
            request()->validate([
                'password' => ['min:5', 'confirmed'],
            ]);
        }

        DB::beginTransaction();
        try {
            $data = request()->only(['name', 'email', 'role']);
            request('password') ? $data['password'] = bcrypt(request('password')) : NULL;
            request()->file('avatar') ? $data['avatar'] = request()->file('avatar')->store('users', 'public') : NULL;
            $user->update($data);

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        DB::beginTransaction();
        try {
            $user->delete();
            DB::commit();
            return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
