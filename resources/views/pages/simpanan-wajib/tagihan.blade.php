@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5 text-center">Tagihan Simpanan Wajib</h4>
                    <table class="table dtTable table-hover" id="dtTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $tagihan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ konversiBulan($tagihan->bulan) }}</td>
                                    <td>{{ $tagihan->tahun }}</td>
                                    <td>{!! $tagihan->status_tagihan() !!}</td>
                                    <td>
                                        @if ($tagihan->status_tagihan == 2 || $tagihan->status_tagihan == 3)
                                            <a href="javascript:void(0)" class="btn disabled py-2 btn-sm btn-info">
                                                Upload Ulang
                                            </a>
                                        @endif
                                        @if ($tagihan->status_tagihan == 0)
                                            <a href="{{ route('simpanan-wajib.tagihan.bayar', [
                                                'id' => $tagihan->id,
                                            ]) }}"
                                                class="btn py-2 btn-sm btn-info">
                                                Bayar & Upload Bukti
                                            </a>
                                        @endif
                                        @if ($tagihan->status_tagihan == 1)
                                            <a href="{{ route('simpanan-wajib.tagihan.bayar', [
                                                'id' => $tagihan->id,
                                            ]) }}"
                                                class="btn py-2 btn-sm btn-info">
                                                Upload Ulang
                                            </a>
                                        @endif

                                        {{-- @if ($tagihan->status == 2)
                                            <a href="javascript:void(0)" class="btn disabled py-2 btn-sm btn-info">
                                                Upload Ulang
                                            </a>
                                        @else
                                            @if ($angsuran->status == 0)
                                            @elseif($angsuran->status == 1)
                                                <a href="{{ route('pinjaman-angsuran.bayar', [
                                                    'kode_pinjaman' => $tagihan->kode,
                                                    'pinjaman_angsuran_id' => $angsuran->id,
                                                ]) }}"
                                                    class="btn py-2 btn-sm btn-info">
                                                    Upload Ulang
                                                </a>
                                            @else
                                                <a href="javascript:void(0)" class="btn py-2 btn-sm btn-info disabled">
                                                    Upload Ulang
                                                </a>
                                            @endif
                                        @endif --}}
                                    </td>
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
@push('scripts')
    <script>
        // handle status pinjaman di klik
        $('body').on('click', '.btnStatusPinjaman', function(e) {
            let id = $(this).data('id');
            let status = $(this).data('status');
            let message, confirm;
            if (status == 1) {
                message = "Anda akan menyetujui pinjaman tersebut!";
                confirm = "Set Disetujui";
            } else if (status == 2) {
                message = "Pinjaman yang sudah diselesaikan tidak bisa di batalakn!";
                confirm = "Set Selesaikan";
            } else if (status == 3) {
                message = "Anda akan menolak pinjaman tersebut!";
                confirm = "Set Ditolak";
            }
            e.preventDefault();
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: confirm,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let action = $(this).data('action');
                    var inputNumber = $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'status')
                        .attr('value', status)
                        .appendTo('#formStatusPinjaman');
                    $('#formStatusPinjaman').attr('action', action);
                    $('#formStatusPinjaman').submit();
                }
            })
        })

        // handle status angsuran di klik
        $('body').on('click', '.statusAngsuran', function(e) {
            let id = $(this).data('id');
            let status = $(this).data('status');
            let message, confirm;
            if (status == 0) {
                message = "Anda akan membatalkan status menjadi Belum Bayar!";
                confirm = "Set Batal Verifikasi";
            } else {
                message = "Pastikan nominal pembayaran sesuai total jumlah angsuran perbulan!";
                confirm = "Set Verifikasi";
            }
            e.preventDefault();
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: confirm,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let action = $(this).data('action');
                    var inputNumber = $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'status')
                        .attr('value', status)
                        .appendTo('#formStatusAngsuran');
                    $('#formStatusAngsuran').attr('action', action);
                    $('#formStatusAngsuran').submit();
                }
            })
        })

        // handle bukti di klik
        $('body').on('click', '.btnBukti', function() {
            let src = $(this).data('image');
            $('#modalBukti .imageModalBukti').attr('src', src);
            $('#modalBukti').modal('show');
        })
    </script>
@endpush
