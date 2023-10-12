@extends('layouts.app')
@section('content')
    <div class="row mb-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3">Filter</h4>
                    <form action="{{ route('simpanan-wajib.filter') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <select name="status" id="status" class="form-control">
                                    <option @selected($status === 'semua') value="semua">Pilih Status</option>
                                    <option @selected($status === '0') value="0">Belum Bayar</option>
                                    <option @selected($status == 1) value="1">Menunggu Verifikasi</option>
                                    <option @selected($status == 2) value="2">Lunas</option>
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
                        <h4 class="card-title mb-3 align-self-center">Data Simpanan Wajib</h4>
                        <a href="{{ route('simpanan-wajib.create') }}" class="btn my-2 mb-3 btn-sm py-2 btn-primary">Tambah
                            Simpanan Wajib</a>
                    </div>
                    <table class="table dtTable table-hover" id="dtTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama ANggota</th>
                                <th>NIP</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Nominal</th>
                                <th>Metode Pembayaran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->anggota->nama }}</td>
                                    <td>{{ $item->anggota->nip }}</td>
                                    <td>{{ konversiBulan($item->bulan) }}</td>
                                    <td>{{ $item->tahun }}</td>
                                    <td>{{ formatRupiah($item->nominal) }}</td>
                                    <td>{{ $item->metode_pembayaran_id ? $item->metode_pembayaran->getFull() : 'Tidak Ada' }}
                                    </td>
                                    <td>{!! $item->status() !!}</td>
                                    <td>
                                        <a href="{{ route('simpanan-wajib.edit', $item->uuid) }}"
                                            class="btn btn-sm py-2 btn-info">Edit</a>
                                        <form action="javascript:void(0)" method="post" class="d-inline" id="formDelete">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btnDelete btn-sm py-2 btn-danger"
                                                data-action="{{ route('simpanan-wajib.destroy', $item->uuid) }}">Hapus</button>
                                        </form>
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
@push('scripts')
    <script>
        // handle bukti di klik
        $('body').on('click', '.btnBukti', function() {
            let src = $(this).data('image');
            $('#modalBukti .imageModalBukti').attr('src', src);
            $('#modalBukti').modal('show');
        })
    </script>
@endpush
