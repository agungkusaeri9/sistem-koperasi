@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>Panduan Pengguna Anggota</h3>
        </div>
    </div>
    <div class="row">
        <div class="col mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <a href="{{ route('panduan.dashboard-anggota') }}">
                    <div class="card-body">
                        <p class="text-center text-white">Dashboard</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="col mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <a href="{{ route('panduan.pinjaman-anggota') }}">
                    <div class="card-body">
                        <p class="text-center text-white">Pinjaman</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="col mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <a href="{{ route('panduan.simpanan-wajib-anggota') }}">
                    <div class="card-body">
                        <p class="text-center text-white">Simpanan Wajib</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="col mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <a href="{{ route('panduan.simpanan-shr-anggota') }}">
                    <div class="card-body">
                        <p class="text-center text-white">Simpanan SHR</p>
                    </div>
                </a>
            </div>
        </div>

    </div>
@endsection
<x-Sweetalert />
