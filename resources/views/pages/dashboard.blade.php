@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Selamat Datang {{ auth()->user()->name }}, </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-tale">
                <div class="card-body">
                    <p class="mb-4">Anggota</p>
                    <p class="fs-30 mb-2">{{ $admin_count['jumlah_anggota'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <div class="card-body">
                    <p class="mb-4">Pinjaman</p>
                    <p class="fs-30 mb-2">{{ $admin_count['jumlah_pinjaman'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4  stretch-card transparent">
            <div class="card card-light-danger">
                <div class="card-body">
                    <p class="mb-4">Pinjaman Menunggu Persetujuan</p>
                    <p class="fs-30 mb-2">{{ $admin_count['jumlah_pinjaman_menunggu_persetujuan'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-light-blue">
                <div class="card-body">
                    <p class="mb-4">Pinjaman Selesai</p>
                    <p class="fs-30 mb-2">{{ $admin_count['jumlah_pinjaman_selesai'] }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title mb-3">Pinjaman Terbaru</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kode</th>
                                    <th>Nama Anggota</th>
                                    <th>Besar Pinjaman</th>
                                    <th>Keperluan</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pinjaman_terakhir as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode }}</td>
                                        <td>{{ $item->anggota->nama }}</td>
                                        <td>Rp {{ number_format($item->besar_pinjaman, 0, '.', '.') }}</td>
                                        <td>{{ $item->keperluan }}</td>
                                        <td>{{ formatTanggalBulanTahun($item->created_at) }}</td>
                                        <td>{!! $item->status() !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
