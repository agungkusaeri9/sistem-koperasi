<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Agama;
use App\Models\Jabatan;
use App\Models\Pengaturan;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['min:6', 'confirmed'],
            'nip' => ['numeric', 'unique:anggota,nip'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'tempat_lahir' => ['required'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat' => ['required'],
            'nomor_telepon' => ['required'],
            'agama_id' => ['required'],
            'jabatan_id' => ['required']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        $data_user = request()->only(['name', 'email']);
        $data_user['role'] = 'anggota';
        $data_anggota = request()->only(['nip', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'nomor_telepon', 'jabatan_id', 'agama_id']);
        $data_anggota['nama'] = request('name');
        $data_user['password'] = bcrypt(request('password'));
        request()->file('avatar') ? $data_user['avatar'] = request()->file('avatar')->store('users', 'public') : NULL;

        // create akun
        $user = User::create($data_user);
        // create anggota
        $user->anggota()->create($data_anggota);

        return $user;

        // return User::create([
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'password' => Hash::make($data['password']),
        // ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.register', [
            'pengaturan' => Pengaturan::first(),
            'data_agama' => Agama::orderBy('nama', 'ASC')->get(),
            'data_jabatan' => Jabatan::orderBy('nama', 'ASC')->get()
        ]);
    }

    public function registered()
    {
        session()->flush();
        return redirect()->route('login')->with('success', 'Anda berhasil mendaftar. Silahkan tunggu verifikasi admin untuk bisa masuk ke sistem.');
    }
}
