@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>Panduan Admin</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-md-3 col-lg-3 col-xl-2 mb-4 stretch-card transparent">
            <a href="{{ route('panduan.master-data-pegawai') }}">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="text-center">Manajemen Pegawai</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-4 col-md-3 col-lg-3 col-xl-2 mb-4 stretch-card transparent">
            <a href="{{ route('panduan.master-data-metode-pembayaran') }}">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="text-center">Manajemen Metode Pembayaran</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-4 col-md-3 col-lg-3 col-xl-2 mb-4 stretch-card transparent">
            <a href="{{ route('panduan.master-data-jabatan') }}">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="text-center">Manajemen Jabatan</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-4 col-md-3 col-lg-3 col-xl-2 mb-4 stretch-card transparent">
            <a href="{{ route('panduan.master-data-agama') }}">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="text-center">Manajemen Agama</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-4 col-md-3 col-lg-3 col-xl-2 mb-4 stretch-card transparent">
            <a href="{{ route('panduan.master-data-periode') }}">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="text-center">Manajemen Periode</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-4 col-md-3 col-lg-3 col-xl-2 mb-4 stretch-card transparent">
            <a href="{{ route('panduan.master-data-lama-angsuran') }}">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="text-center">Manajemen Lama Angsuran</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-4 col-md-3 col-lg-3 col-xl-2 mb-4 stretch-card transparent">
            <a href="{{ route('panduan.pinjaman') }}">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="text-center">Manajemen Pinjaman</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection
<x-Sweetalert />
