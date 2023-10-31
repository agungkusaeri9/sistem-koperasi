@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h3>Panduan Manajemen Jabatan</h3>
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
                            <h4 class="card-title">Melihat Data Jabatan</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih sub menu Jabatan
                                </li>
                                <li>Selanjutnya muncul data Jabatan.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Membuat Jabatan Baru</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih submenu Jabatan.
                                </li>
                                <li>Isi formulir seperti, Nama (Wajib).
                                <li>Selanjutnya klik Tambah Data.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Merubah Data Jabatan</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih submenu Jabatan.
                                </li>
                                <li>Selanjutnya muncul data Jabatan, pilih Jabatan yang mau di rubah
                                    dengan menekan tombol
                                    edit.
                                </li>
                                <li>Isi formulir seperti, Nama (Wajib).
                                </li>
                                <li>Selanjutnya klik Update Data.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Menghapus Data Jabatan</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih submenu Jabatan.
                                </li>
                                <li>Selanjutnya muncul data Jabatan, pilih Jabatan yang mau di hapus
                                    dengan menekan tombol
                                    hapus.</li>
                                <li>Kemudian klik yakin untuk konfirmasi penghapusan Jabatan.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection
<x-Sweetalert />
