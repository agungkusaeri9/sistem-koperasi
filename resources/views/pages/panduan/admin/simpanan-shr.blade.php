@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h3>Panduan Manajemen Simpanan SHR</h3>
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
                            <h4 class="card-title">Melihat Data Simpanan SHR</h4>
                            <ol>
                                <li>Buka menu "Transaksi Simpanan" di dashboard Anda.</li>
                                <li>Pilih sub menu Simpanan SHR
                                </li>
                                <li>Selanjutnya muncul data Simpanan SHR.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Memfilter Data Simpanan SHR</h4>
                            <ol>
                                <li>Buka menu "Transaksi Simpanan" di dashboard Anda.</li>
                                <li>Pilih sub menu Simpanan SHR
                                </li>
                                <li>Selanjutnya muncul data Simpanan SHR.</li>
                                <li>Pilih filterisasi berdasarkan status.</li>
                                <li>Tekan tombol filter.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Membuat Simpanan SHR</h4>
                            <ol>
                                <li>Buka menu "Transaksi Simpanan" di dashboard Anda.</li>
                                <li>Pilih submenu Simpanan SHR.
                                </li>
                                <li>Tekan tombol tambah Simpanan Waib</li>
                                <li>Isi formulir seperti, Periode (SHR), Anggota (SHR), Bulan (Waib), Tahun (Waijb),  Metode Pembayaran (SHR) dan Status (SHR).</li>
                                <li>Selanjutnya tekan Tambah Data.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Merubah Data Simpanan Waib</h4>
                            <ol>
                                <li>Buka menu "Transaksi Simpanan" di dashboard Anda.</li>
                                <li>Pilih submenu Simpanan SHR.
                                </li>
                                <li>Selanjutnya muncul data Simpanan SHR, pilih Simpanan SHR yang mau di rubah
                                    dengan menekan tombol
                                    edit.
                                </li>
                                <li>Isi formulir seperti, Bulan (Waib), Tahun (Waijb),  Metode Pembayaran (SHR) dan Status (SHR).</li>
                                <li>Selanjutnya klik Update Data.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Menghapus Data Simpanan SHR</h4>
                            <ol>
                                <li>Buka menu "Transaksi Simpanan" di dashboard Anda.</li>
                                <li>Pilih submenu Simpanan SHR.
                                </li>
                                <li>Selanjutnya muncul data Simpanan SHR, pilih Simpanan SHR yang mau di hapus
                                    dengan menekan tombol
                                    hapus.</li>
                                <li>Kemudian klik yakin untuk konfirmasi penghapusan Simpanan SHR.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection
<x-Sweetalert />
