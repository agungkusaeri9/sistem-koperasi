@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Tambah Simpanan Wajib</h4>
                    <form action="{{ route('simpanan-wajib.store') }}" method="post">
                        @csrf
                        <div class='form-group mb-3'>
                            <label for='anggota_id' class='mb-2'>Anggota <span class="text-danger">*</span></label>
                            <select name="anggota_id" id="anggota_id"
                                class="form-control @error('anggota_id') is-invalid @enderror">
                                <option value="" selected>Pilih Anggota</option>
                                @foreach ($data_anggota as $anggota)
                                    <option value="{{ $anggota->id }}">
                                        {{ $anggota->nama . ' | NIP. ' . $anggota->nip }}</option>
                                @endforeach
                            </select>
                            @error('anggota_id')
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
                            <label for='nominal' class='mb-2'>Nominal <span class="text-danger">*</span></label>
                            <input type='text' name='nominal' class='form-control @error('nominal') is-invalid @enderror'
                                value='{{ $pengaturan->nominal_simpanan_wajib ?? old('nominal') }}'>
                            @error('nominal')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='metode_pembayaran_id' class='mb-2'>Metode Pembayaran <span
                                    class="text-danger">*</span></label>
                            <select name="metode_pembayaran_id" id="metode_pembayaran_id"
                                class="form-control @error('metode_pembayaran_id') is-invalid @enderror">
                                <option value="" selected>Pilih Metode Pembayaran</option>
                                @foreach ($data_metode_pembayaran as $metode_pembayaran)
                                    <option value="{{ $metode_pembayaran->id }}">
                                        {{ $metode_pembayaran->getFull() }}</option>
                                @endforeach
                            </select>
                            @error('metode_pembayaran_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='status' class='mb-2'>Status <span class="text-danger">*</span></label>
                            <select name="status" id="status"
                                class="form-control @error('status') is-invalid @enderror">
                                <option value="" selected>Pilih Status</option>
                                <option value="0">Belum Bayar</option>
                                <option value="1">Menunggu Verifikasi</option>
                                <option value="2">Lunas</option>
                            </select>
                            @error('status')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('simpanan-wajib.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Tambah Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<x-Sweetalert />
