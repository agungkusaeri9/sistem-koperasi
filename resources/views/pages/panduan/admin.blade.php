@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Panduan Admin</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="mb-3">
                        <h4 class="card-title">Dashboard</h4>
                        <ol>
                            <li>Buka menu "Dashboard".</li>
                            <li>Selanjutnya akan muncul informasi mengenai Jumlah Anggota, Jumlah Pinjaman Menunggu
                                Persetujuan, Jumlah Pinjaman Selesai dan
                                Pinjaman Terbaru.</li>
                        </ol>
                    </div>
                </div>
            </div>
            <hr>
            <div class="card mb-3">
                <div class="card-body">
                    <h3 class="mb-3">Pegawai</h3>
                    <p class="mb-4">

                    </p>
                </div>
            </div>
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
                            <li>Isi formulir seperti, Avatar, Nama, Email, Role, Password, Konfirmasi Password dan Status.</li>
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
                            <li>Selanjutnya muncul data pegawai, pilih pegawai yang mau di rubah dengan menekan tombol edit.</li>
                            <li>Isi formulir edit seperti, Avatar, Nama, Email, Role, Password, Konfirmasi Password dan Status.</li>
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
                            <li>Selanjutnya muncul data pegawai, pilih pegawai yang mau di hapus dengan menekan tombol hapus.</li>
                            <li>Kemudian klik yakin untuk konfirmasi penghapusan pegawai.</li>
                        </ol>
                    </div>
                </div>
            </div>
            <hr>
        </div>
    </div>
@endsection
<x-Sweetalert />
