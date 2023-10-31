@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h3>Panduan Manajemen Pinjaman</h3>
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
                            <h4 class="card-title">Melihat Data Pinjaman</h4>
                            <ol>
                                <li>Buka menu "Transaksi Pinjaman" di dashboard Anda.</li>
                                <li>Pilih sub menu Pinjaman
                                </li>
                                <li>Selanjutnya muncul data Pinjaman.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Memfilter Data Pinjaman</h4>
                            <ol>
                                <li>Buka menu "Transaksi Pinjaman" di dashboard Anda.</li>
                                <li>Pilih sub menu Pinjaman
                                </li>
                                <li>Selanjutnya muncul data Pinjaman.</li>
                                <li>Pilih Status dan tekan tombol filter.</li>
                                <li>Kemudian muncul data pinjaman sesuai status yang difilter.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Melihat Detail Pinjaman</h4>
                            <ol>
                                <li>Buka menu "Transaksi Pinjaman" di dashboard Anda.</li>
                                <li>Pilih sub menu Pinjaman
                                </li>
                                <li>Selanjutnya muncul data Pinjaman.</li>
                                <li>Pilih Pinjaman yang ingin dilihat secara detail dengan menekan tombol detail.</li>
                                <li>Maka akan muncul informasi Pinjaman beserta Angsurannya.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Menyetujui Pinjaman</h4>
                            <ol>
                                <li>Buka menu "Transaksi Pinjaman" di dashboard Anda.</li>
                                <li>Pilih sub menu Pinjaman
                                </li>
                                <li>Selanjutnya muncul data Pinjaman.</li>
                                <li>Pilih Pinjaman yang ingin di setujui dengan menekan tombol detail.</li>
                                <li>Maka akan muncul informasi Pinjaman beserta Angsurannya.</li>
                                <li>Tekan tombol Set Disetujui.</li>
                                <li>Tekan tombol Set Disetujui untuk konfirmasi Disetujui.</li>
                                <li>Kemudian secara otomatis angsuran pinjaman telah dibuat.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Merubah Status Angsuran Pinjaman </h4>
                            <ol>
                                <li>Buka menu "Transaksi Pinjaman" di dashboard Anda.</li>
                                <li>Pilih sub menu Pinjaman
                                </li>
                                <li>Selanjutnya muncul data Pinjaman.</li>
                                <li>Pilih Pinjaman yang ingin di rubah angsurannya dengan menekan tombol detail.</li>
                                <li>Maka akan muncul informasi Pinjaman beserta Angsurannya.</li>
                                <li>Tekan tombol edit di bagian angsuran yang ingin dirubah.</li>
                                <li>Isi formulir seperti Metode Pembayaran (Wajib) dan Status (Wajib).</li>
                                <li>Kemudian Tekan tombol Update Data.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Membuat Pembayaran tagihan Administrasi pinjaman </h4>
                            <ol>
                                <li>Buka menu "Transaksi Pinjaman" di dashboard Anda.</li>
                                <li>Pilih sub menu Pinjaman
                                </li>
                                <li>Selanjutnya muncul data Pinjaman.</li>
                                <li>Pilih Pinjaman dengan menekan tombol detail.</li>
                                <li>Maka akan muncul informasi Pinjaman beserta Angsurannya.</li>
                                <li>Kemudian Tekan tombol Set Sudah Bayar.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Menyelesaikan Proses Pinjaman </h4>
                            <ol>
                                <li>Buka menu "Transaksi Pinjaman" di dashboard Anda.</li>
                                <li>Pilih sub menu Pinjaman
                                </li>
                                <li>Selanjutnya muncul data Pinjaman.</li>
                                <li>Pilih Pinjaman dengan menekan tombol detail.</li>
                                <li>Maka akan muncul informasi Pinjaman beserta Angsurannya.</li>
                                <li>Pastikan semua angsuran dan administrasi sudah dibayarkan atau lunas.</li>
                                <li>Kemudian tekan tombol Set Selesai dan klik Set Selesai untuk konfirmasi.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Mencetak Formulir Pinjaman </h4>
                            <ol>
                                <li>Buka menu "Transaksi Pinjaman" di dashboard Anda.</li>
                                <li>Pilih sub menu Pinjaman
                                </li>
                                <li>Selanjutnya muncul data Pinjaman.</li>
                                <li>Pilih pinjaman dan tekan tombol cetak PDF.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Menghapus Pengajuan Pinjaman </h4>
                            <ol>
                                <li>Buka menu "Transaksi Pinjaman" di dashboard Anda.</li>
                                <li>Pilih sub menu Pinjaman
                                </li>
                                <li>Selanjutnya muncul data Pinjaman.</li>
                                <li>Pastikan Pinjaman tersebut statusnya "Masing Menunggu Persetujuan" atau "Ditolak"</li>
                                <li>Pilih pinjaman dan tekan tombol hapus.</li>
                                <li>Tekan tombol yakin untuk konfirmasi penghapusan pinjaman.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection
<x-Sweetalert />
