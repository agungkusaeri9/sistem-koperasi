@extends('auth.app')
@section('title')
    Register
@endsection
@section('content')
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                        <div class="brand-logo text-center">
                            <img src="{{ $pengaturan ? $pengaturan->logo() : asset('assets/images/logo.svg') }}"
                                alt="logo">
                        </div>
                        <h6 class="font-weight-light text-center">Silahkan daftar terlebih dahulu</h6>
                        <form class="pt-3" method="post" action="{{ route('register') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class='form-group mb-3'>
                                        <label for='name' class='mb-2'>Nams</label>
                                        <input type='text' name='name'
                                            class='form-control @error('name') is-invalid @enderror'
                                            value='{{ old('name') }}'>
                                        @error('name')
                                            <div class='invalid-feedback'>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class='form-group mb-3'>
                                        <label for='nip' class='mb-2'>NIP</label>
                                        <input type='number' name='nip'
                                            class='form-control @error('nip') is-invalid @enderror'
                                            value='{{ old('nip') }}'>
                                        @error('nip')
                                            <div class='invalid-feedback'>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class='form-group mb-3'>
                                        <label for='jenis_kelamin' class='mb-2'>Jenis Kelamin <span
                                                class="text-danger">*</span></label>
                                        <select name="jenis_kelamin" id="jenis_kelamin"
                                            class="form-control @error('jenis_kelamin') is-invalid @enderror">
                                            <option value="" selected>Pilih Jenis Kelamin</option>
                                            <option @selected(old('jenis_kelamin') === 'Laki-laki') value="Laki-laki">Laki-laki</option>
                                            <option @selected(old('jenis_kelamin') === 'Perempuan') value="Perempuan">Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')
                                            <div class='invalid-feedback'>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class='form-group mb-3'>
                                        <label for='tempat_lahir' class='mb-2'>Tempat Lahir <span
                                                class="text-danger">*</span></label>
                                        <input type='text' name='tempat_lahir'
                                            class='form-control @error('tempat_lahir') is-invalid @enderror'
                                            value='{{ old('tempat_lahir') }}'>
                                        @error('tempat_lahir')
                                            <div class='invalid-feedback'>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class='form-group mb-3'>
                                        <label for='tanggal_lahir' class='mb-2'>Tanggal Lahir <span
                                                class="text-danger">*</span></label>
                                        <input type='date' name='tanggal_lahir'
                                            class='form-control @error('tanggal_lahir') is-invalid @enderror'
                                            value='{{ old('tanggal_lahir') }}'>
                                        @error('tanggal_lahir')
                                            <div class='invalid-feedback'>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class='form-group mb-3'>
                                        <label for='alamat' class='mb-2'>Alamat <span
                                                class="text-danger">*</span></label>
                                        <textarea name='alamat' id='alamat' cols='30' rows='3'
                                            class='form-control @error('alamat') is-invalid @enderror'>{{ old('alamat') }}</textarea>
                                        @error('alamat')
                                            <div class='invalid-feedback'>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class='form-group mb-3'>
                                        <label for='nomor_telepon' class='mb-2'>Nomor Telepon <span
                                                class="text-danger">*</span></label>
                                        <input type='text' name='nomor_telepon'
                                            class='form-control @error('nomor_telepon') is-invalid @enderror'
                                            value='{{ old('nomor_telepon') }}'>
                                        @error('nomor_telepon')
                                            <div class='invalid-feedback'>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class='form-group mb-3'>
                                        <label for='agama_id' class='mb-2'>Agama <span
                                                class="text-danger">*</span></label>
                                        <select name="agama_id" id="agama_id"
                                            class="form-control @error('agama_id') is-invalid @enderror">
                                            <option value="" selected>Pilih Agama</option>
                                            @foreach ($data_agama as $agama)
                                                <option value="{{ $agama->id }}">{{ $agama->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('agama_id')
                                            <div class='invalid-feedback'>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class='form-group mb-3'>
                                        <label for='jabatan_id' class='mb-2'>Jabatan <span
                                                class="text-danger">*</span></label>
                                        <select name="jabatan_id" id="jabatan_id"
                                            class="form-control @error('jabatan_id') is-invalid @enderror">
                                            <option value="" selected>Pilih Jabatan</option>
                                            @foreach ($data_jabatan as $jabatan)
                                                <option value="{{ $jabatan->id }}">{{ $jabatan->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('jabatan_id')
                                            <div class='invalid-feedback'>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class='form-group mb-3'>
                                        <label for='email' class='mb-2'>Email</label>
                                        <input type='text' name='email'
                                            class='form-control @error('email') is-invalid @enderror'
                                            value='{{ old('email') }}'>
                                        @error('email')
                                            <div class='invalid-feedback'>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class='form-group mb-3'>
                                        <label for='password' class='mb-2'>Password</label>
                                        <input type='password' name='password'
                                            class='form-control @error('password') is-invalid @enderror'
                                            value='{{ old('password') }}'>
                                        @error('password')
                                            <div class='invalid-feedback'>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class='form-group mb-3'>
                                        <label for='password_confirmation' class='mb-2'>Konfirmasi Password</label>
                                        <input type='password' name='password_confirmation'
                                            class='form-control @error('password_confirmation') is-invalid @enderror'>
                                        @error('password_confirmation')
                                            <div class='invalid-feedback'>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button
                                    class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">DAFTAR</button>
                            </div>
                            <div class="text-center mt-4 font-weight-light">
                                Sudah punya akun? <a href="{{ route('login') }}" class="text-primary">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
@endsection
