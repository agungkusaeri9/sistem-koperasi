@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Perhatian!</strong>
                <p>Ketika simpanan wajib di terima/acc, maka secara otomatis anggota tersebut di nonaktifkan.</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5 text-center">Pencairan Simpanan Wajib</h4>
                    <table class="table dtTable table-hover" id="dtTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Anggota</th>
                                <th>Nominal</th>
                                <th>Metoe Pencairan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ formatTanggalBulanTahun($item->created_at) }}</td>
                                    <td>{{ $item->anggota->nama }}</td>
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
