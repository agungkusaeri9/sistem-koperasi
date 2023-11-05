@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h3>Panduan Manajemen Angsuran Pinjaman</h3>
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
                            <h4 class="card-title">Melihat Data Angsuran Pinjaman</h4>
                            <ol>
                                <li>Buka menu "Transaksi Pinjaman" di dashboard Anda.</li>
                                <li>Pilih sub menu Angsuran Pinjaman
                                </li>
                                <li>Selanjutnya muncul data Angsuran Pinjaman.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Memfilter Data Angsuran Pinjaman</h4>
                            <ol>
                                <li>Buka menu "Transaksi Pinjaman" di dashboard Anda.</li>
                                <li>Pilih sub menu Angsuran Pinjaman
                                </li>
                                <li>Selanjutnya muncul data Angsuran Pinjaman.</li>
                                <li>Pilih Bulan, Tahun dan status kemudian tekan tombol filter.</li>
                                <li>Kemudian muncul data Angsuran Pinjaman sesuai yang difilter.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Merubah Status Angsuran Pinjaman</h4>
                            <ol>
                                <li>Buka menu "Transaksi Pinjaman" di dashboard Anda.</li>
                                <li>Pilih sub menu Angsuran Pinjaman
                                </li>
                                <li>Selanjutnya muncul data Angsuran Pinjaman.</li>
                                <li>Pilih Angsuran Pinjaman yang ingin dilihat dirubah dengan menekan tombol edit.</li>
                                <li>Kemudian muncul informasi mengenai kode pinjaman, nominal dan isi formulir seperti metode pembayaran dan status.</li>
                                <li>Tekan tombol Update Data.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection
<x-Sweetalert />
