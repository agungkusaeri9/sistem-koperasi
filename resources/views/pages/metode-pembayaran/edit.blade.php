@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Edit Metode Pembayaran</h4>
                    <form action="{{ route('metode-pembayaran.update', $item->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class='form-group mb-3'>
                            <label for='nama' class='mb-2'>Nama <span class="text-danger">*</span></label>
                            <input type='text' name='nama' class='form-control @error('nama') is-invalid @enderror'
                                value='{{ $item->nama ?? old('nama') }}'>
                            @error('nama')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='nomor' class='mb-2'>Nomor</label>
                            <input type='text' name='nomor' class='form-control @error('nomor') is-invalid @enderror'
                                value='{{ $item->nomor ?? old('nomor') }}'>
                            @error('nomor')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class='form-group mb-3'>
                            <label for='pemilik' class='mb-2'>Pemilik</label>
                            <input type='text' name='pemilik' class='form-control @error('pemilik') is-invalid @enderror'
                                value='{{ $item->pemilik ?? old('pemilik') }}'>
                            @error('pemilik')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('metode-pembayaran.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
