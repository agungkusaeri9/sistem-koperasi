@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>Panduan Pengguna Admin</h3>
        </div>
    </div>
    <div class="row">
        <div class="col mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <a href="{{ route('panduan.master-data-pegawai') }}">
                    <div class="card-body">
                        <p class="text-center text-white text-white">Manajemen Pegawai</p>
                    </div>
            </div>
            </a>
        </div>

        <div class="col mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <a href="{{ route('panduan.master-data-metode-pembayaran') }}">
                    <div class="card-body">
                        <p class="text-center text-white">Manajemen Metode Pembayaran</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="col mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <a href="{{ route('panduan.master-data-jabatan') }}">
                    <div class="card-body">
                        <p class="text-center text-white">Manajemen Jabatan</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="col mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <a href="{{ route('panduan.master-data-agama') }}">
                    <div class="card-body">
                        <p class="text-center text-white">Manajemen Agama</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="col mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <a href="{{ route('panduan.master-data-periode') }}">
                    <div class="card-body">
                        <p class="text-center text-white">Manajemen Periode</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="col mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <a href="{{ route('panduan.master-data-lama-angsuran') }}">
                    <div class="card-body">
                        <p class="text-center text-white">Manajemen Lama Angsuran</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="col mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <a href="{{ route('panduan.anggota') }}">
                    <div class="card-body">
                        <p class="text-center text-white">Manajemen Anggota</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="col mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <a href="{{ route('panduan.pinjaman') }}">
                    <div class="card-body">
                        <p class="text-center text-white">Manajemen Pinjaman</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="col mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <a href="{{ route('panduan.angsuran-pinjaman') }}">
                    <div class="card-body">
                        <p class="text-center text-white">Manajemen Angsuran Pinjaman</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="col mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <a href="{{ route('panduan.simpanan-wajib') }}">
                    <div class="card-body">
                        <p class="text-center text-white">Manajemen Simpanan Wajib</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="col mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <a href="{{ route('panduan.simpanan-shr') }}">
                    <div class="card-body">
                        <p class="text-center text-white">Manajemen Simpanan SHR</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="col mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <a href="{{ route('panduan.pencairan-simpanan') }}">
                    <div class="card-body">
                        <p class="text-center text-white">Manajemen Pencairan Simpanan</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <a href="{{ route('panduan.laporan') }}">
                    <div class="card-body">
                        <p class="text-center text-white">Laporan</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <a href="{{ route('panduan.pengaturan') }}">
                    <div class="card-body">
                        <p class="text-center text-white">Pengaturan Web</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
<x-Sweetalert />
