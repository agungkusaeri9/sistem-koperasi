@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h3>Panduan Manajemen Pegawai</h3>
                <a href="{{ route('panduan.index') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {{-- pegawai --}}
            <section>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Melihat Data Pegawai</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih sub menu pegawai
                                </li>
                                <li>Selanjutnya muncul data pegawai yang sudah terdaftar.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Membuat Pegawai Baru</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih submenu pegawai.
                                </li>
                                <li>Isi formulir seperti, Avatar, Nama, Email, Role, Password, Konfirmasi Password dan
                                    Status.
                                </li>
                                <li>Selanjutnya klik Tambah Pegawai.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Merubah Data Pegawai</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih submenu pegawai.
                                </li>
                                <li>Selanjutnya muncul data pegawai, pilih pegawai yang mau di rubah dengan menekan tombol
                                    edit.
                                </li>
                                <li>Isi formulir edit seperti, Avatar, Nama, Email, Role, Password, Konfirmasi Password dan
                                    Status.</li>
                                <li>Selanjutnya klik Update Pegawai.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Menghapus Data Pegawai</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih submenu pegawai.
                                </li>
                                <li>Selanjutnya muncul data pegawai, pilih pegawai yang mau di hapus dengan menekan tombol
                                    hapus.</li>
                                <li>Kemudian klik yakin untuk konfirmasi penghapusan pegawai.</li>
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
