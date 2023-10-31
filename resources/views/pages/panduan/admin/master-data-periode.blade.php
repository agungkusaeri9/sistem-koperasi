@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h3>Panduan Manajemen Periode</h3>
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
                            <h4 class="card-title">Melihat Data Periode</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih sub menu Periode
                                </li>
                                <li>Selanjutnya muncul data Periode.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Membuat Periode Baru</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih submenu Periode.
                                </li>
                                <li>Isi formulir seperti, Bulan Awal (Wajib), Tahun Awal (Wajib), Bulan Akhir (Wajib), Tahun
                                    Akhir (Wajib), Nominal Simpanan SHR (Wajib), Nominal Simpanan Wajib (Wajib) dan Status
                                    (Wajib).
                                </li>
                                <li>Selanjutnya klik Tambah Data.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Merubah Data Periode</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih submenu Periode.
                                </li>
                                <li>Selanjutnya muncul data Periode, pilih Periode yang mau di rubah
                                    dengan menekan tombol
                                    edit.
                                </li>
                                <li>Isi formulir seperti, Bulan Awal (Wajib), Tahun Awal (Wajib), Bulan Akhir (Wajib), Tahun
                                    Akhir (Wajib), Nominal Simpanan SHR (Wajib), Nominal Simpanan Wajib (Wajib) dan Status
                                    (Wajib).
                                </li>
                                <li>Selanjutnya klik Update Data.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Menghapus Data Periode</h4>
                            <ol>
                                <li>Buka menu "Master Data" di dashboard Anda.</li>
                                <li>Pilih submenu Periode.
                                </li>
                                <li>Selanjutnya muncul data Periode, pilih Periode yang mau di hapus
                                    dengan menekan tombol
                                    hapus.</li>
                                <li>Kemudian klik yakin untuk konfirmasi penghapusan Periode.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection
<x-Sweetalert />
