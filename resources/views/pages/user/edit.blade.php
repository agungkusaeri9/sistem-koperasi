@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Edit User</h4>
                    <form action="{{ route('users.update', $user->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class='form-group mb-3'>
                            <label for='avatar' class='mb-2'>Avatar</label>
                            <input type='file' name='avatar' class='form-control @error('avatar') is-invalid @enderror'
                                value='{{ old('avatar') }}'>
                            @error('avatar')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='name' class='mb-2'>Name</label>
                            <input type='text' name='name' class='form-control @error('name') is-invalid @enderror'
                                value='{{ $user->name ?? old('name') }}'>
                            @error('name')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='email' class='mb-2'>Email</label>
                            <input type='text' name='email' class='form-control @error('email') is-invalid @enderror'
                                value='{{ $user->email ?? old('email') }}'>
                            @error('email')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='role' class='mb-2'>Role</label>
                            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                                <option value="" selected>Pilih</option>
                                <option @selected($user->role === 'super admin') value="super admin">Super Admin</option>
                                <option @selected($user->role === 'admin') value="admin">Admin</option>
                                <option @selected($user->role === 'anggota') value="anggota">Anggota</option>
                            </select>
                            @error('role')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='password' class='mb-2'>Password</label>
                            <input type='password' name='password'
                                class='form-control @error('password') is-invalid @enderror' value='{{ old('password') }}'>
                            @error('password')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='password_confirmation' class='mb-2'>Password Confirmation</label>
                            <input type='password' name='password_confirmation'
                                class='form-control @error('password_confirmation') is-invalid @enderror'
                                value='{{ old('password_confirmation') }}'>
                            @error('password_confirmation')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('users.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Update User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
