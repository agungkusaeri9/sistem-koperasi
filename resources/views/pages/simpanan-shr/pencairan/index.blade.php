@extends('layouts.app')
@section('content')
    @if (auth()->user()->role !== 'anggota')
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Filter</h4>
                        <form action="{{ route('pencairan-simpanan-shr.filter') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <select name="anggota_id" id="anggota_id" class="form-control select2">
                                        <option value="">Pilih Anggota</option>
                                        @foreach ($data_anggota as $anggota)
                                            <option @selected($anggota->id == $anggota_id) value="{{ $anggota->id }}">
                                                {{ $anggota->nama . ' | ' . $anggota->nip }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="periode_id" id="periode_id" class="form-control select2">
                                        <option value="">Pilih Periode</option>
                                        @foreach ($data_periode as $periode)
                                            <option @selected($periode->id == $periode_id) value="{{ $periode->id }}">
                                                {{ $periode->periode() }}
                                            </option>
                                        @endforeach
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
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-3 align-self-center">Pencairan Simpanan SHR</h4>
                        @if (isLoginNotAnggota())
                            <a href="{{ route('pencairan-simpanan-shr.create') }}"
                                class="btn my-2 mb-3 btn-sm py-2 btn-primary">Buat
                                Pencairan</a>
                        @endif
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalBukti" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Bukti Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img src="" class="img-fluid imageModalBukti" alt="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
@push('stylesBefore')
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function() {
            $('.select2').select2();

            $('body').on('click', '.btnBukti', function() {
                let src = $(this).data('image');
                $('#modalBukti .imageModalBukti').attr('src', src);
                $('#modalBukti').modal('show');
            })
        })
    </script>
@endpush
