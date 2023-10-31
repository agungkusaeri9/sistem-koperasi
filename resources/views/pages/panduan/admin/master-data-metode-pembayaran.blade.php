@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h3>Panduan Manajemen Metode Pembayaran</h3>
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
                            <h4 class="card-title">Melihat Data Metode Pembayaran</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih sub menu Metode Pembayaran
                                </li>
                                <li>Selanjutnya muncul data Metode Pembayaran.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Membuat Metode Pembayaran Baru</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih submenu Metode Pembayaran.
                                </li>
                                <li>Isi formulir seperti, Nama (Wajib), Nomor, dan Pemilik.
                                </li>
                                <li>Selanjutnya klik Tambah Data.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Merubah Data Metode Pembayaran</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih submenu Metode Pembayaran.
                                </li>
                                <li>Selanjutnya muncul data Metode Pembayaran, pilih Metode Pembayaran yang mau di rubah
                                    dengan menekan tombol
                                    edit.
                                </li>
                                <li>Isi formulir seperti, Nama (Wajib), Nomor, dan Pemilik.
                                </li>
                                <li>Selanjutnya klik Update Data.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Menghapus Data Metode Pembayaran</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih submenu Metode Pembayaran.
                                </li>
                                <li>Selanjutnya muncul data Metode Pembayaran, pilih Metode Pembayaran yang mau di hapus
                                    dengan menekan tombol
                                    hapus.</li>
                                <li>Kemudian klik yakin untuk konfirmasi penghapusan Metode Pembayaran.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <hr>
            </section>

        </div>
    </div>
@endsection
<x-Sweetalert />
