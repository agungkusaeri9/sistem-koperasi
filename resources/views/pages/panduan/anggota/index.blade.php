@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>Panduan Anggota</h3>
        </div>
    </div>
    <div class="row">
        <div class="col mb-4 stretch-card transparent">
            <a href="{{ route('panduan.dashboard-anggota') }}">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="text-center">Dashboard</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col mb-4 stretch-card transparent">
            <a href="{{ route('panduan.pinjaman-anggota') }}">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="text-center">Pinjaman</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col mb-4 stretch-card transparent">
            <a href="{{ route('panduan.simpanan-wajib-anggota') }}">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="text-center">Simpanan Wajib</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col mb-4 stretch-card transparent">
            <a href="{{ route('panduan.simpanan-shr-anggota') }}">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="text-center">Simpanan SHR</p>
                    </div>
                </div>
            </a>
        </div>

    </div>
@endsection
<x-Sweetalert />
