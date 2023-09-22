@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-3 align-self-center">Pencairan Simpanan SHR</h4>
                        <a href="{{ route('simpanan-shr.pencairan.create') }}"
                            class="btn my-2 mb-3 btn-sm py-2 btn-primary">Buat
                            Pencairan</a>
                    </div>
                    <table class="table dtTable table-hover" id="dtTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Anggota</th>
                                <th>Tanggal</th>
                                <th>Periode Simpanan</th>
                                <th>Nominal</th>
                                <th>Metode Pencairan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->anggota->nama }}</td>
                                    <td>{{ formatTanggalBulanTahun($item->created_at) }}</td>
                                    <td>{{ $item->periode->periode() }}</td>
                                    <td>{{ formatRupiah($item->nominal) }}</td>
                                    <td>{{ $item->metode_pembayaran->getFull() }}</td>
                                    <td>{!! $item->status() !!}</td>
                                    <td>
                                        @if ($item->status == 0)
                                            <form action="{{ route('simpanan-wajib.pencairan.set-status', $item->id) }}"
                                                method="post" class="d-inline">
                                                @csrf
                                                <input type="number" hidden name="status" value="1">
                                                <button class="btn btnTerima btn-sm py-2 btn-success">Terima</button>
                                            </form>
                                            <form action="{{ route('simpanan-wajib.pencairan.set-status', $item->id) }}"
                                                method="post" class="d-inline">
                                                @csrf
                                                <input type="number" hidden name="status" value="2">
                                                <button class="btn btnTerima btn-sm py-2 btn-danger">Tolak</button>
                                            </form>
                                        @else
                                            <form action="javascript:void(0)" method="post" class="d-inline"
                                                id="formDelete">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btnDelete btn-sm py-2 btn-danger"
                                                    data-action="{{ route('simpanan-wajib.pencairan.destroy', $item->id) }}">Hapus</button>
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
<x-Datatable />
<x-Sweetalert />
@push('styles')
    <style>
        .info-saldo {
            font-size: 70px
        }
    </style>
@endpush
