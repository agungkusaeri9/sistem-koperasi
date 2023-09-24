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
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-3 align-self-center">Pencairan Simpanan Wajib</h4>
                        @if (isLoginNotAnggota())
                            <a href="{{ route('simpanan-wajib.pencairan.create') }}"
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
                                <th>Nominal</th>
                                <th>Metode Pencairan</th>
                                <th>Bukti Pencairan</th>
                                <th>Status</th>
                                @if (isLoginNotAnggota())
                                    <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->anggota->nama }}</td>
                                    <td>{{ formatTanggalBulanTahun($item->created_at) }}</td>
                                    <td>{{ formatRupiah($item->nominal) }}</td>
                                    <td>{{ $item->metode_pembayaran->getFull() }}</td>
                                    <td>
                                        @if ($item->bukti_pencairan)
                                            <a href="javascript:void(0)" class="btn btnBukti py-2 btn-sm btn-success"
                                                data-image="{{ asset('storage/' . $item->bukti_pencairan) }}">Lihat</a>
                                        @else
                                            <a href="javascript:void(0)" class="btn py-2 btn-sm btn-danger">Tidak Ada</a>
                                        @endif
                                    </td>
                                    <td>{!! $item->status() !!}</td>
                                    @if (isLoginNotAnggota())
                                        <td>
                                            <a href="{{ route('simpanan-wajib.pencairan.edit', $item->id) }}"
                                                class="btn btn-sm py-2 btn-info">Edit</a>

                                            @if ($item->status != 1)
                                                <form action="javascript:void(0)" method="post" class="d-inline"
                                                    id="formDelete">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="btn btnDelete btn-sm py-2 btn-danger"
                                                        data-action="{{ route('simpanan-wajib.pencairan.destroy', $item->id) }}">Hapus</button>
                                                </form>
                                            @endif
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
