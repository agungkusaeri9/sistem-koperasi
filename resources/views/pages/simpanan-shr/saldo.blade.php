@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Perhatian!</strong>
                <p>Saldo Simpanan SHR bisa dicairkan diperiode berikutnya.</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title mb-3 text-center">Periode Saat Ini</h5>
                    <h4 class="text-center">{{ $periode ? $periode->periode() : 'Tidak Ada!' }}</h4>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5 text-center">Informasi Saldo</h4>
                    <h1 class="info-saldo text-center">{{ formatRupiah($saldo) }}</h1>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="card-title mb-5">Filter</h4>
                    <form action="{{ route('simpanan-shr.saldo.filter') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <select name="periode_id" id="periode_id" class="form-control">
                                    <option value="" disabled selected>Pilih Periode</option>
                                    @foreach ($data_periode as $per)
                                        <option @selected($per->id == $periode->id) value="{{ $per->id }}">
                                            {{ $per->periode() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md">
                                <button class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5 text-center">Riwayat Simpanan SHR</h4>
                    <table class="table dtTable table-hover" id="dtTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Nominal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $tagihan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ konversiBulan($tagihan->simpanan->bulan) }}</td>
                                    <td>{{ $tagihan->simpanan->tahun }}</td>
                                    <td>{{ formatRupiah($tagihan->simpanan->nominal) }}</td>
                                    <td>{!! $tagihan->status_tagihan() !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
<x-Datatable />
<x-Sweetalert />
@push('styles')
    <style>
        .info-saldo {
            font-size: 70px
        }
    </style>
@endpush
