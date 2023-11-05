@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h3>Panduan Laporan</h3>
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
                            <h4 class="card-title">Mencetak Laporan Pinjaman</h4>
                            <ol>
                                <li>Buka menu "Laporan" di dashboard Anda.</li>
                                <li>Pilih sub menu Pinjaman
                                </li>
                                <li>Pilih Anggota (opsional), masukan Dari Tanggal (opsional), Sampai Tanggal (opsional) dan
                                    Status (opsional).</li>
                                <li>Selanjutnya tekan tombol Cetak PDF</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Mencetak Laporan Simpanan Wajib</h4>
                            <ol>
                                <li>Buka menu "Laporan" di dashboard Anda.</li>
                                <li>Pilih sub menu Simpanan Wajib
                                </li>
                                <li>Pilih Anggota (opsional).</li>
                                <li>Tekan tombol buat Cetak PDF.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Mencetak Laporan Simpanan SHR</h4>
                            <ol>
                                <li>Buka menu "Laporan" di dashboard Anda.</li>
                                <li>Pilih sub menu Simpanan SHR
                                </li>
                                <li>Pilih Anggota (opsional) dan Periode (opsional).</li>
                                <li>Tekan tombol buat Cetak PDF.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection
<x-Sweetalert />
