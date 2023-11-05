@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h3>Panduan Manajemen Pencairan Simpanan</h3>
                <a href="{{ route('panduan.index') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {{-- metode pembayaran --}}
            <section>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Melihat Data Pencairan Simpanan Wajib</h4>
                            <ol>
                                <li>Buka menu "Pencairan Dana" di dashboard Anda.</li>
                                <li>Pilih sub menu Simpanan Waib
                                </li>
                                <li>Selanjutnya muncul data Pencairan Simpanan Wajib.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Membuat Simpanan Pencairan Simpanan Wajib</h4>
                            <ol>
                                <li>Buka menu "Pencairan Dana" di dashboard Anda.</li>
                                <li>Pilih sub menu Simpanan Waib
                                </li>
                                <li>Selanjutnya muncul data Simpanan Wajib.</li>
                                <li>Tekan tombol buat pencairan.</li>
                                <li>Isi formulir seperti pilih anggota (Wajib), pilih metode pencairan (Wajib) dan muncul informasi saldo simpanan wajib.</li>
                                <li>Kemudian tekan tombol Buat Pencairan</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Melihat Data Pencairan Simpanan SHR</h4>
                            <ol>
                                <li>Buka menu "Pencairan Dana" di dashboard Anda.</li>
                                <li>Pilih sub menu Simpanan SHR
                                </li>
                                <li>Selanjutnya muncul data Pencairan Simpanan SHR.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Membuat Simpanan Pencairan Simpanan SHR</h4>
                            <ol>
                                <li>Buka menu "Pencairan Dana" di dashboard Anda.</li>
                                <li>Pilih sub menu Simpanan SHR
                                </li>
                                <li>Selanjutnya muncul data Simpanan SHR.</li>
                                <li>Tekan tombol buat pencairan.</li>
                                <li>Isi formulir seperti pilih anggota (Wajib), pilih periode (Wajib), pilih metode pencairan (Wajib) dan muncul informasi saldo simpanan SHR.</li>
                                <li>Kemudian tekan tombol Buat Pencairan</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection
<x-Sweetalert />
