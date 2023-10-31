@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h3>Panduan Manajemen Lama Angsuran</h3>
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
                            <h4 class="card-title">Melihat Data Lama Angsuran</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih sub menu Lama Angsuran
                                </li>
                                <li>Selanjutnya muncul data Lama Angsuran.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Membuat Lama Angsuran Baru</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih submenu Lama Angsuran.
                                </li>
                                <li>Isi formulir seperti, Durasi (Wajib), Potongan Awal (Wajib), Jasa Pinjaman (Wajib) dan
                                    Jenis.
                                </li>
                                <li>Selanjutnya klik Tambah Data.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Merubah Data Lama Angsuran</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih submenu Lama Angsuran.
                                </li>
                                <li>Selanjutnya muncul data Lama Angsuran, pilih Lama Angsuran yang mau di rubah
                                    dengan menekan tombol
                                    edit.
                                </li>
                                <li>Isi formulir seperti, Durasi (Wajib), Potongan Awal (Wajib), Jasa Pinjaman (Wajib) dan
                                    Jenis.
                                </li>
                                <li>Selanjutnya klik Update Data.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Menghapus Data Lama Angsuran</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih submenu Lama Angsuran.
                                </li>
                                <li>Selanjutnya muncul data Lama Angsuran, pilih Lama Angsuran yang mau di hapus
                                    dengan menekan tombol
                                    hapus.</li>
                                <li>Kemudian klik yakin untuk konfirmasi penghapusan Lama Angsuran.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection
<x-Sweetalert />
