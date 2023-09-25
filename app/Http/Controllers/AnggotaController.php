<?php

namespace App\Http\Controllers;

use App\Models\Agama;
use App\Models\Anggota;
use App\Models\Jabatan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AnggotaController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkRole:admin')->except('detail_json');
    }


    public function index()
    {
        $items = Anggota::with(['user', 'jabatan', 'agama'])->orderBy('nama', 'ASC')->get();
        return view('pages.anggota.index', [
            'title' => 'Anggota',
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('pages.anggota.create', [
            'title' => 'Tambah Anggota',
            'data_jabatan' => Jabatan::orderBy('nama', 'ASC')->get(),
            'data_agama' => Agama::orderBy('nama', 'ASC')->get()
        ]);
    }

    public function store()
    {
        request()->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['min:6', 'confirmed'],
            'is_active' => ['required', 'in:0,1'],
            'avatar' => ['image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'nip' => [Rule::when(request('nip') != NULL, ['numeric', 'unique:anggota,nip'])],
            'tempat_lahir' => ['required'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat' => ['required'],
            'nomor_telepon' => ['required'],
            'agama_id' => ['required'],
            'jabatan_id' => ['required']
        ]);

        DB::beginTransaction();
        try {
            $data_user = request()->only(['name', 'email', 'is_active']);
            $data_user['role'] = 'anggota';
            $data_anggota = request()->only(['nip', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'nomor_telepon', 'jabatan_id', 'agama_id']);
            $data_anggota['nama'] = request('name');
            $data_user['password'] = bcrypt(request('password'));
            request()->file('avatar') ? $data_user['avatar'] = request()->file('avatar')->store('users', 'public') : NULL;

            // create akun
            $user = User::create($data_user);
            // create anggota
            $user->anggota()->create($data_anggota);

            DB::commit();
            return redirect()->route('anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('anggota.index')->with('error', $th->getMessage());
        }
    }

    public function show($id)
    {
        $item = Anggota::with('user')->findOrFail($id);
        return view('pages.anggota.show', [
            'title' => 'Detail Anggota',
            'item' => $item
        ]);
    }

    public function edit($id)
    {
        $item = Anggota::with('user')->findOrFail($id);
        return view('pages.anggota.edit', [
            'title' => 'Edit Anggota',
            'item' => $item,
            'data_jabatan' => Jabatan::orderBy('nama', 'ASC')->get(),
            'data_agama' => Agama::orderBy('nama', 'ASC')->get()
        ]);
    }

    public function update($id)
    {
        $item = Anggota::with(['user'])->findOrFail($id);
        request()->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email,' . $item->user->id . ''],
            'is_active' => ['required', 'in:0,1'],
            'avatar' => ['image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
            'nip' => [Rule::when(request('nip') != NULL, ['numeric', 'unique:anggota,nip, ' . $id . ''])],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'tempat_lahir' => ['required'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat' => ['required'],
            'nomor_telepon' => ['required'],
            'agama_id' => ['required'],
            'jabatan_id' => ['required'],
            'password' => [Rule::when(request('password') != NULL, ['min:6', 'confirmed'])]
        ]);


        DB::beginTransaction();
        try {
            $data_user = request()->only(['name', 'email', 'is_active']);
            $data_user['role'] = 'anggota';
            $data_anggota = request()->only(['nip', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'nomor_telepon', 'jabatan_id', 'agama_id']);
            $data_anggota['nama'] = request('name');
            request()->file('avatar') ? $data_user['avatar'] = request()->file('avatar')->store('users', 'public') : NULL;

            // cek jika ada password
            if (request('password'))
                $data_user['password'] = bcrypt(request('password'));

            // cek jika ada gambar
            if (request()->file('avatar')) {
                // ada di database
                if ($item->user->avatar)
                    Storage::disk('public')->delete($item->user->avatar);

                $data_user['avatar'] = request()->file('avatar')->store('users', 'public');
            }

            $item->user->update($data_user);
            $item->update($data_anggota);
            DB::commit();
            return redirect()->route('anggota.index')->with('success', 'Anggota berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('anggota.index')->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {

        $item = Anggota::with('user')->findOrFail($id);

        DB::beginTransaction();
        try {
            // delete anggota
            $item->delete();
            // delete user
            $item->user->delete();
            DB::commit();
            return redirect()->route('anggota.index')->with('success', 'Anggota berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('anggota.index')->with('error', $th->getMessage());
        }
    }

    public function detail_json($id)
    {
        if (request()->ajax()) {
            $anggota = Anggota::with('jabatan')->find($id);
            if ($anggota) {
                $anggota['tanggal_lahir_format'] = $anggota->tanggal_lahir->translatedFormat('d F Y');
                return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'data' => $anggota
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'data' => $anggota
                ]);
            }
        }
    }
}
