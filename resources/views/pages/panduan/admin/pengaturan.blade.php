@extends('layouts.app')
@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h3>Panduan Edit Pengaturan</h3>
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
                            <h4 class="card-title">Merubah Pengaturan Web</h4>
                            <ol>
                                <li>Buka menu "Pengaturan" di dashboard Anda.</li>
                                <li>Isi formulir seperti, Nama Situs (wajib), Email (opsional), Nomor Telepon (opsional),
                                    Pembuat (opsional), Favicon (opsional) dan Logo (opsional)</li>
                                <li>Selanjutnya tekan tombol Update Pengaturan</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection
<x-Sweetalert />
