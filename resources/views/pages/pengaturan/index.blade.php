@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Pengaturan</h4>
                    <form action="{{ route('pengaturan.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class='form-group mb-3'>
                            <label for='nama_situs' class='mb-2'>Nama Situs <span class="text-danger">*</span></label>
                            <input type='text' name='nama_situs'
                                class='form-control @error('nama_situs') is-invalid @enderror'
                                value='{{ $item->nama_situs ?? old('nama_situs') }}'>
                            @error('nama_situs')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='email' class='mb-2'>Email</label>
                            <input type='email' name='email' class='form-control @error('email') is-invalid @enderror'
                                value='{{ $item->email ?? old('email') }}'>
                            @error('email')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='nomor_telepon' class='mb-2'>Nomor Telepon</label>
                            <input type='text' name='nomor_telepon'
                                class='form-control @error('nomor_telepon') is-invalid @enderror'
                                value='{{ $item->nomor_telepon ?? old('nomor_telepon') }}'>
                            @error('nomor_telepon')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='pembuat' class='mb-2'>Pembuat</label>
                            <input type='text' name='pembuat' class='form-control @error('pembuat') is-invalid @enderror'
                                value='{{ $item->pembuat ?? old('pembuat') }}'>
                            @error('pembuat')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @if ($item && $item->favicon)
                            <div class='form-group mb-3 row'>
                                <div class="col-md-2">
                                    <label for='favicon' class='mb-2'>Favicon</label>
                                    <img src="{{ $item->favicon() }}" class="img-fluid" alt="">
                                </div>
                                <div class="col-md align-self-center">
                                    <input type='file' name='favicon'
                                        class='form-control @error('favicon') is-invalid @enderror'
                                        value='{{ old('favicon') }}'>
                                    @error('favicon')
                                        <div class='invalid-feedback'>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <div class='form-group mb-3'>
                                <label for='favicon' class='mb-2'>Favicon</label>
                                <input type='file' name='favicon'
                                    class='form-control @error('favicon') is-invalid @enderror'
                                    value='{{ old('favicon') }}'>
                                @error('favicon')
                                    <div class='invalid-feedback'>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endif
                        @if ($item && $item->logo)
                            <div class='form-group mb-3 row'>
                                <div class="col-md-2">
                                    <label for='logo' class='mb-2'>logo</label>
                                    <img src="{{ $item->logo() }}" class="img-fluid" alt="">
                                </div>
                                <div class="col-md align-self-center">
                                    <input type='file' name='logo'
                                        class='form-control @error('logo') is-invalid @enderror'
                                        value='{{ old('logo') }}'>
                                    @error('logo')
                                        <div class='invalid-feedback'>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <div class='form-group mb-3'>
                                <label for='logo' class='mb-2'>Logo</label>
                                <input type='file' name='logo'
                                    class='form-control @error('logo') is-invalid @enderror' value='{{ old('logo') }}'>
                                @error('logo')
                                    <div class='invalid-feedback'>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endif

                        <div class="form-group text-right">
                            <button class="btn btn-primary">Update Pengaturan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<x-Sweetalert />
