@extends('layouts.app')
@section('content')
    <div class="row mb-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Laporan Simpanan Wajib</h4>
                    <form action="{{ route('laporan.simpanan-wajib.print') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class='form-group mb-3'>
                                    <label for='bulan' class='mb-2'>Bulan</label>
                                    <select name="bulan" id="bulan" class="form-control">
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
                            </div>
                            <div class="col-md-3">
                                <div class='form-group mb-3'>
                                    <label for='tahun' class='mb-2'>Tahun</label>
                                    <select name="tahun" id="tahun" class="form-control">
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
                            </div>
                            <div class="col-md-3">
                                <label for='status' class='mb-2'>Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option @selected($status === 'semua') value="semua">Semua</option>
                                    <option @selected($status == 0) value="0">Belum Bayar</option>
                                    <option @selected($status == 1) value="1">Menunggu Verifikasi</option>
                                    <option @selected($status == 2) value="2">Lunas</option>
                                </select>
                            </div>
                            <div class="col-md align-self-center">
                                <button class="btn mt-2 btn-danger">Cetak PDF</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<x-Sweetalert />
