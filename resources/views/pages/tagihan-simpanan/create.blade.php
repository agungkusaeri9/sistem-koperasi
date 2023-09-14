@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Tambah Tagihan Simpanan</h4>
                    <form action="{{ route('tagihan-simpanan.store') }}" method="post">
                        @csrf
                        <div class='form-group mb-3'>
                            <label for='jenis' class='mb-2'>Jenis <span class="text-danger">*</span></label>
                            <select name="jenis" id="jenis" class="form-control @error('jenis') is-invalid @enderror">
                                <option value="">Pilih Jenis</option>
                                <option value="wajib">Wajib</option>
                                <option value="shr">SHR</option>
                            </select>
                            @error('jenis')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='bulan' class='mb-2'>Bulan <span class="text-danger">*</span></label>
                            <select name="bulan" id="bulan" class="form-control @error('bulan') is-invalid @enderror">
                                <option value="">Pilih Bulan</option>
                                @foreach ($data_bulan as $bulan)
                                    <option value="{{ $bulan->no }}">{{ $bulan->nama }}</option>
                                @endforeach
                            </select>
                            @error('bulan')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='tahun' class='mb-2'>Tahun <span class="text-danger">*</span></label>
                            <select name="tahun" id="tahun" class="form-control @error('tahun') is-invalid @enderror">
                                <option value="">Pilih Tahun</option>
                                @foreach ($data_tahun as $tahun)
                                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                                @endforeach
                            </select>
                            @error('tahun')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='periode_id' class='mb-2'>Periode <span class="text-danger">(Jangan dipilih jika
                                    jenisnya wajib)</span></label>
                            <select name="periode_id" id="periode_id"
                                class="form-control @error('periode_id') is-invalid @enderror">
                                <option value="" selected>Pilih Periode</option>
                                @foreach ($data_periode as $periode)
                                    <option value="{{ $periode->id }}">
                                        {{ $periode->periode() }}</option>
                                @endforeach
                            </select>
                            @error('periode_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('tagihan-simpanan.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Tambah Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<x-Sweetalert />
