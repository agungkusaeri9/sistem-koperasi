@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-3 align-self-center">Pencairan Simpanan Wajib</h4>
                        @if (isLoginNotAnggota())
                            <a href="{{ route('pencairan-simpanan-wajib.create') }}"
                                class="btn my-2 mb-3 btn-sm py-2 btn-primary">Buat
                                Pencairan</a>
                        @endif
                    </div>
                    <table class="table dtTable table-hover" id="dtTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>Anggota</th>
                                <th>Nominal</th>
                                <th>Metode Pencairan</th>
                                <th>Status</th>
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
                    <h5 class="modal-title" id="exampleModalLabel">Bukti Pencairan</h5>
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
@push('scripts')
    <script>
        $(function() {
            $('body').on('click', '.btnBukti', function() {
                let src = $(this).data('image');
                $('#modalBukti .imageModalBukti').attr('src', src);
                $('#modalBukti').modal('show');
            })
        })
    </script>
@endpush
