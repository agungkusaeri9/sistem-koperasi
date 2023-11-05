@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h3>Panduan Manajemen Anggota</h3>
                <a href="{{ route('panduan.index') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <section>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Melihat Data Agama</h4>
                            <ol>
                                <li>Buka menu "Anggota" di dashboard Anda.</li>
                                <li>Selanjutnya muncul data Anggota.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Membuat Anggota Baru</h4>
                            <ol>
                                <li>Buka menu "Anggota" di dashboard Anda.</li>
                                <li>Isi formulir seperti, Avatar/Gambar (opsional), Nama (Wajib), NIP (opsional), Jenis
                                    Kelamin (Wajib), Tempat Lahir (wajib), Tanggal Lahir (wajib), Alamat (wajib), Nomor
                                    Telepon (wajib), Agama (wajib), Jabatan (wajib), Email (wajib), Password (wajib),
                                    Konfirmasi Password (wajib) dan Status (Wajib).
                                </li>
                                <li>Selanjutnya klik Tambah Anggota.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Melihaat Detail Anggota</h4>
                            <ol>
                                <li>Buka menu "Anggota" di dashboard Anda.</li>
                                <li>Selanjutnya muncul data Anggota, pilih Anggota yang mau di dilihat
                                    dengan menekan tombol
                                    detail.
                                </li>
                                <li>Selanjutnya muncul detail Anggota tersebut.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Merubah Data Anggota</h4>
                            <ol>
                                <li>Buka menu "Anggota" di dashboard Anda.</li>
                                <li>Selanjutnya muncul data Anggota, pilih Anggota yang mau di rubah
                                    dengan menekan tombol
                                    edit.
                                </li>
                                <li>Isi formulir seperti, Avatar/Gambar (opsional), Nama (Wajib), NIP (opsional), Jenis
                                    Kelamin (Wajib), Tempat Lahir (wajib), Tanggal Lahir (wajib), Alamat (wajib), Nomor
                                    Telepon (wajib), Agama (wajib), Jabatan (wajib), Email (wajib), Password (wajib),
                                    Konfirmasi Password (wajib) dan Status (Wajib).
                                </li>
                                <li>Selanjutnya klik Update Anggotaa.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Menghapus Data Anggota</h4>
                            <ol>
                                <li>Buka menu "Anggota" di dashboard Anda.</li>
                                <li>Selanjutnya muncul data Anggota, pilih Anggota yang mau di hapus
                                    dengan menekan tombol
                                    hapus.</li>
                                <li>Kemudian klik yakin untuk konfirmasi penghapusan Anggota.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection
<x-Sweetalert />
