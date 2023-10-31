@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h3>Panduan Pinjaman</h3>
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
                            <h4 class="card-title">Membuat Pengajuan Pinjaman</h4>
                            <ol>
                                <li>Buka menu "Pengajuan Pinjaman" di dashboard Anda.</li>
                                <li>Isi formulir dengan memasukan Besaran Pinjaman, Untuk Keperluan, dan pilih Lama
                                    Angsuran.
                                </li>
                                <li>Selanjutnya akan muncul informasi Potongan Awal, Jumlah Diterima, Angsuran per bulan,
                                    dan
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
                                <li>Maka muncul informasi Riwayat Pinjaman yang telah anda lakukan seperti Kode Pinjaman,
                                    Besar
                                    Pinjaman, Keperluan, Tanggal Pengajuan, Status, dan Aksi.
                                </li>
                                <li>Klik tombol Detail disalah satu pinjaman untuk melihat informasi lengkap mengenai
                                    pinjaman
                                    dan angsurannya.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection
<x-Sweetalert />
