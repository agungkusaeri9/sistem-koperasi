@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Panduan Anggota</h2>
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
                            <li>Selanjutnya akan muncul informasi mengenai Jumlah Pinjaman, Jumlah Pinjaman Menunggu
                                Persetujuan, Jumlah Pinjaman Selesai dan
                                Tagihan-tagihan Angsuran Pinjaman.</li>
                        </ol>
                    </div>
                </div>
            </div>
            <hr>
            <div class="card mb-3">
                <div class="card-body">
                    <h3 class="mb-3">Pinjaman</h3>
                    <p class="mb-4">
                        Fitur pinjaman adalah suatu layanan atau fungsi dalam sebuah aplikasi atau sistem yang memungkinkan
                        anggota untuk meminjam sejumlah uang ataupun melihat riwayat pinjaman.
                    </p>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body">
                    <div class="mb-3">
                        <h4 class="card-title">Membuat Pengajuan Pinjaman</h4>
                        <ol>
                            <li>Buka menu "Pengajuan Pinjaman" di dashboard Anda.</li>
                            <li>Isi formulir dengan memasukan Besaran Pinjaman, Untuk Keperluan, dan pilih Lama Angsuran.
                            </li>
                            <li>Selanjutnya akan muncul informasi Potongan Awal, Jumlah Diterima, Angsuran per bulan, dan
                                Total Jasa Pinjaman.</li>
                            <li>Klik Ajukan Pinjaman dan tunggu proses validasi dari admin.</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <h4 class="card-title">Melihat Riwayat Pinjaman</h4>
                        <ol>
                            <li>Buka menu "Riwayat Pinjaman" di dashboard Anda.</li>
                            <li>Maka muncul informasi Riwayat Pinjaman yang telah anda lakukan seperti Kode Pinjaman, Besar
                                Pinjaman, Keperluan, Tanggal Pengajuan, Status, dan Aksi.
                            </li>
                            <li>Klik tombol Detail disalah satu pinjaman untuk melihat informasi lengkap mengenai pinjaman
                                dan angsurannya.</li>
                        </ol>
                    </div>
                </div>
            </div>

            <hr>

            <div class="card mb-3">
                <div class="card-body">
                    <h3 class="mb-3">Informasi Saldo dan Riwayat Simpanan</h3>
                    <p class="mb-4">
                        Fitur Informasi Saldo dan Riwayat Simpanan memungkinkan anggota untuk melihat dan memantau saldo
                        serta riwayat transaksi simpanan di akun anda.
                    </p>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body">
                    <div class="mb-3">
                        <h4 class="card-title">Melihat Informasi Saldo dan Riwayat Simpanan Wajib</h4>
                        <ol>
                            <li>Klik menu "Informasi Saldo" di dashboard Anda.</li>
                            <li>Pilih submenu Simpanan Wajib.
                            </li>
                            <li>Selanjutnya akan muncul informasi mengenai saldo, dan riwayat simpanan wajib seperti bulan,
                                tahun, nominal dan status.</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body">
                    <div class="mb-3">
                        <h4 class="card-title">Melihat Informasi Saldo dan Riwayat Simpanan Hari Raya (SHR)</h4>
                        <ol>
                            <li>Klik menu "Informasi Saldo" di dashboard Anda.</li>
                            <li>Pilih submenu Simpanan SHR.
                            </li>
                            <li>Selanjutnya akan muncul informasi mengenai saldo, dan riwayat simpanan wajib seperti bulan,
                                tahun, nominal dan status.</li>
                            <li>Pilih periode lalu filter, jika ingin melihat informasi saldo dan riwayat berdasarkan
                                periode tertentu</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<x-Sweetalert />
