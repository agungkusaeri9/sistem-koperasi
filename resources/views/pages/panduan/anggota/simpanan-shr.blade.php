@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h3>Panduan Simpanan Hari Raya</h3>
                <a href="{{ route('panduan.index') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {{-- Simpanan Hari Raya --}}
            <section>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="card-title">Melihat Informasi Saldo dan Riwayat Simpanan Hari Raya (SHR)</h4>
                            <ol>
                                <li>Klik menu "Informasi Saldo" di dashboard Anda.</li>
                                <li>Pilih submenu Simpanan SHR.
                                </li>
                                <li>Selanjutnya akan muncul informasi mengenai saldo, dan riwayat simpanan wajib seperti
                                    bulan,
                                    tahun, nominal dan status.</li>
                                <li>Pilih periode lalu filter, jika ingin melihat informasi saldo dan riwayat berdasarkan
                                    periode tertentu</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection
<x-Sweetalert />
