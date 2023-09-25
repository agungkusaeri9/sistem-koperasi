@extends('layouts.app')
@section('content')
    @if (isLoginAnggota())
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning">
                    <strong>Perhatian!</strong>
                    <p>Dimohon memasukan data yang benar, dikarenakan data yang sudah dibuat tidak bisa diedit ataupun
                        dihapus.
                    </p>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">
                        @if (isLoginAnggota())
                            Tambah Metode Pencairan
                        @else
                            Tambah Metode Pembayaran
                        @endif
                    </h4>
                    <form action="{{ route('metode-pembayaran.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class='form-group mb-3'>
                            <label for='nama' class='mb-2'>Nama <span class="text-danger">*</span></label>
                            <input type='text' name='nama' class='form-control @error('nama') is-invalid @enderror'
                                value='{{ old('nama') }}'>
                            @error('nama')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='nomor' class='mb-2'>Nomor</label>
                            <input type='number' name='nomor' class='form-control @error('nomor') is-invalid @enderror'
                                value='{{ old('nomor') }}'>
                            @error('nomor')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='pemilik' class='mb-2'>Pemilik</label>
                            <input type='text' name='pemilik' class='form-control @error('pemilik') is-invalid @enderror'
                                value='{{ old('pemilik') }}'>
                            @error('pemilik')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('metode-pembayaran.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Tambah Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
