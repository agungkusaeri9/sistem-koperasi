@extends('layouts.app')
@section('content')
    <div class="row mb-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3">Filter</h4>
                    <form action="{{ route('pinjaman.filter') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <select name="status" id="status" class="form-control">
                                    <option @selected($status === 'semua') value="semua">Pilih Status</option>
                                    <option @selected($status == 0) value="0">Menunggu Persetujuan</option>
                                    <option @selected($status == 1) value="1">Disetujui</option>
                                    <option @selected($status == 2) value="2">Selesai</option>
                                    <option @selected($status == 3) value="3">Ditolak</option>
                                </select>
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
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-3 align-self-center">Data Pinjaman</h4>
                    </div>
                    <table class="table dtTable table-hover" id="dtTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode</th>
                                <th>Nama Anggota</th>
                                <th>Besar Pinjaman</th>
                                <th>Keperluan</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->anggota->nama }}</td>
                                    <td>Rp {{ number_format($item->besar_pinjaman, 0, '.', '.') }}</td>
                                    <td>{{ $item->keperluan }}</td>
                                    <td>{{ formatTanggalBulanTahun($item->created_at) }}</td>
                                    <td>{!! $item->status() !!}</td>
                                    <td>
                                        <a href="{{ route('pinjaman.show', $item->kode) }}"
                                            class="btn btn-sm py-2 btn-warning">Detail</a>

                                        @if ((auth()->user()->role !== 'anggota' && $item->status == 0) || $item->status == 3)
                                            <form action="javascript:void(0)" method="post" class="d-inline"
                                                id="formDelete">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btnDelete btn-sm py-2 btn-danger"
                                                    data-action="{{ route('pinjaman.destroy', $item->id) }}">Hapus</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
<x-Sweetalert />
<x-Datatable />
