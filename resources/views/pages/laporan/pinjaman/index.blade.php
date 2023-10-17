@extends('layouts.app')
@section('content')
    <div class="row mb-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Laporan Pinjaman</h4>
                    <form action="{{ route('laporan.pinjaman.print') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for='anggota_id' class='mb-2'>Anggota</label>
                                    <select name="anggota_id" id="anggota_id" class="form-control select2">
                                        <option @selected($status === 'semua') value="semua">Pilih Anggota</option>
                                        @foreach ($data_anggota as $anggota)
                                            <option value="{{ $anggota->id }}">{{ $anggota->nama . ' | ' . $anggota->nip }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class='form-group mb-3'>
                                    <label for='tanggal_awal' class='mb-2'>Dari</label>
                                    <input type='date' name='tanggal_awal'
                                        class='form-control @error('tanggal_awal') is-invalid @enderror'
                                        value='{{ old('tanggal_awal') }}'>
                                    @error('tanggal_awal')
                                        <div class='invalid-feedback'>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class='form-group mb-3'>
                                    <label for='tanggal_sampai' class='mb-2'>Sampai</label>
                                    <input type='date' name='tanggal_sampai'
                                        class='form-control @error('tanggal_sampai') is-invalid @enderror'
                                        value='{{ old('tanggal_sampai') }}'>
                                    @error('tanggal_sampai')
                                        <div class='invalid-feedback'>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-3">
                                    <label for='status' class='mb-2'>Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option @selected($status === 'semua') value="semua">Semua</option>
                                        <option @selected($status == 0) value="0">Menunggu Persetujuan</option>
                                        <option @selected($status == 1) value="1">Disetujui</option>
                                        <option @selected($status == 2) value="2">Selesai</option>
                                        <option @selected($status == 3) value="3">Ditolak</option>
                                    </select>
                                </div>
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
@push('stylesBefore')
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function() {
            $('.select2').select2();
        })
    </script>
@endpush
