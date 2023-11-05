@extends('layouts.app')
@section('content')
    <div class="row mb-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3">Filter</h4>
                    <form action="{{ route('pinjaman-angsuran.filter') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <select name="bulan" id="bulan" class="form-control">
                                        <option value="" selected>Pilih Bulan</option>
                                        @foreach ($data_bulan as $bulan)
                                            <option @selected($bulan->no == request('bulan')) value="{{ $bulan->no }}">
                                                {{ $bulan->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <select name="tahun" id="tahun" class="form-control">
                                        <option value="" selected>Pilih Tahun</option>
                                        @foreach ($data_tahun as $tahun)
                                            <option @selected($tahun == request('tahun')) value="{{ $tahun }}">
                                                {{ $tahun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option @selected($status === 'semua') value="semua">Pilih Status</option>
                                        <option @selected($status == 0) value="0">Belum Bayar</option>
                                        <option @selected($status == 1) value="1">Menunggu Verifikasi</option>
                                        <option @selected($status == 2) value="2">Lunas</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md">
                                <button class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h4 class="card-title mb-3 align-self-center">Data Angsuran Pinjaman</h4>
                    </div>
                    <table class="table dtTable table-hover" id="dtTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Anggota</th>
                                <th>Jenis</th>
                                <th>Jatuh Tempo</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                @if (auth()->user()->role !== 'anggota')
                                    <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $key => $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->pinjaman->anggota->nama }}</td>
                                    <td>Angsuran Bulanan ({{ $item->pinjaman->lama_angsuran->jenis }})</td>
                                    <td>{{ formatTanggal($item->pinjaman->tanggal_diterima) . ' ' . konversiBulan($item->bulan) . ' ' . $item->tahun }}
                                    </td>
                                    <td>{{ formatRupiah($item->pinjaman->total_jumlah_angsuran_bulan) }}</td>
                                    <td>
                                        {!! $item->status() !!}
                                    </td>
                                    @if (auth()->user()->role !== 'anggota')
                                        <td>
                                            <a href="{{ route('pinjaman-angsuran.edit', $item->uuid) }}"
                                                class="btn btn-sm py-2 btn-info">Edit</a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
<x-Datatable />
<x-Sweetalert />
