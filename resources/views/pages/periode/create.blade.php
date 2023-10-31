@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Tambah Periode</h4>
                    <form action="{{ route('periode.store') }}" method="post">
                        @csrf
                        <div class='form-group mb-3'>
                            <label for='bulan_awal' class='mb-2'>Bulan Awal <span class="text-danger">*</span></label>
                            <select name="bulan_awal" id="bulan_awal"
                                class="form-control @error('bulan_awal') is-invalid @enderror">
                                <option value="">Pilih Bulan Awal</option>
                                @foreach ($data_bulan as $bulan_awal)
                                    <option value="{{ $bulan_awal->no }}">{{ $bulan_awal->nama }}</option>
                                @endforeach
                            </select>
                            @error('bulan_awal')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='tahun_awal' class='mb-2'>Tahun Awal <span class="text-danger">*</span></label>
                            <select name="tahun_awal" id="tahun_awal"
                                class="form-control @error('tahun_awal') is-invalid @enderror">
                                <option value="">Pilih Tahun Awal</option>
                                @foreach ($data_tahun as $tahun_awal)
                                    <option value="{{ $tahun_awal }}">{{ $tahun_awal }}</option>
                                @endforeach
                            </select>
                            @error('tahun_awal')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='bulan_akhir' class='mb-2'>Bulan Akhir <span class="text-danger">*</span></label>
                            <select name="bulan_akhir" id="bulan_akhir"
                                class="form-control @error('bulan_akhir') is-invalid @enderror">
                                <option value="">Pilih Bulan Akhir</option>
                                @foreach ($data_bulan as $bulan_akhir)
                                    <option value="{{ $bulan_akhir->no }}">{{ $bulan_akhir->nama }}</option>
                                @endforeach
                            </select>
                            @error('bulan_akhir')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='tahun_akhir' class='mb-2'>Tahun Akhir <span class="text-danger">*</span></label>
                            <select name="tahun_akhir" id="tahun_akhir"
                                class="form-control @error('tahun_akhir') is-invalid @enderror">
                                <option value="">Pilih Tahun Akhir</option>
                                @foreach ($data_tahun as $tahun_akhir)
                                    <option value="{{ $tahun_akhir }}">{{ $tahun_akhir }}</option>
                                @endforeach
                            </select>
                            @error('tahun_akhir')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='nominal_simpanan_shr' class='mb-2'>Nominal Simpanan SHR <span
                                    class="text-danger">*</span></label>
                            <input type='number' name='nominal_simpanan_shr'
                                class='form-control @error('nominal_simpanan_shr') is-invalid @enderror'
                                value='{{ old('nominal_simpanan_shr') }}'>
                            @error('nominal_simpanan_shr')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='nominal_simpanan_wajib' class='mb-2'>Nominal Simpanan Wajib <span
                                    class="text-danger">*</span></label>
                            <input type='number' name='nominal_simpanan_wajib'
                                class='form-control @error('nominal_simpanan_wajib') is-invalid @enderror'
                                value='{{ old('nominal_simpanan_wajib') }}'>
                            @error('nominal_simpanan_wajib')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='status' class='mb-2'>Status <span class="text-danger">*</span></label>
                            <select name="status" id="status"
                                class="form-control @error('status') is-invalid @enderror">
                                <option value="">Pilih Status</option>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                            @error('status')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('periode.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Tambah Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
