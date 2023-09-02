@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Tambah Lama Angsuran</h4>
                    <form action="{{ route('lama-angsuran.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class='form-group mb-3'>
                            <label for='durasi' class='mb-2'>Durasi (bulan) <span class="text-danger">*</span></label>
                            <input type='number' name='durasi' class='form-control @error('durasi') is-invalid @enderror'
                                value='{{ old('durasi') }}'>
                            @error('durasi')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='potongan_awal_persen' class='mb-2'>Potongan Awal (%) <span class="text-danger">*
                                </span>
                            </label>
                            <input type='number' name='potongan_awal_persen'
                                class='form-control @error('potongan_awal_persen') is-invalid @enderror'
                                value='{{ old('potongan_awal_persen') }}'>
                            @error('potongan_awal_persen')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='jasa_pinjaman_bulan_persen' class='mb-2'>Jasa Pinjaman Per Bulan (%)<span
                                    class="text-danger">*</span></label>
                            <input type='number' name='jasa_pinjaman_bulan_persen'
                                class='form-control @error('jasa_pinjaman_bulan_persen') is-invalid @enderror'
                                value='{{ old('jasa_pinjaman_bulan_persen') }}'>
                            @error('jasa_pinjaman_bulan_persen')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('lama-angsuran.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Tambah Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
