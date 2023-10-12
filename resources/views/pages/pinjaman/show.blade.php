@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5 text-center">Detail Pinjaman</h4>
                    <ul class="list-unstyled mt-5">
                        <li class="list-item mb-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Nama Peminjam</span>
                            <span>{{ $item->anggota->nama }}</span>
                        </li>
                        <li class="list-item mb-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Besar Pinjaman</span>
                            <span>Rp {{ number_format($item->besar_pinjaman, 0, '.', '.') }}</span>
                        </li>
                        <li class="list-item mb-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Untuk Keperluan</span>
                            <span>{{ $item->keperluan }}</span>
                        </li>
                        <li class="list-item mb-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Lama Angsuran</span>
                            <span>{{ $item->lama_angsuran->durasi . ' bulan' }}</span>
                        </li>
                        @if ($item->bulan_awal && $item->bulan_sampai)
                            <li class="list-item mb-3 d-flex justify-content-between">
                                <span class="font-weight-bold">Mulai Angsuran</span>
                                <span>{{ konversiBulan($item->bulan_mulai) . ' ' . $item->tahun_mulai . ' s.d ' . konversiBulan($item->bulan_sampai) . ' ' . $item->tahun_sampai }}</span>
                            </li>
                        @endif
                        <li class="list-item mb-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Potongan Awal</span>
                            <span>{{ formatRupiah($item->potongan_awal) }}</span>
                        </li>
                        <li class="list-item mb-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Total Jasa Pinjaman</span>
                            <span>{{ formatRupiah($item->jasa_pinjaman_bulan * $item->lama_angsuran->durasi) }}</span>
                        </li>
                        <li class="list-item mb-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Jumlah Diterima</span>
                            <span>{{ formatRupiah($item->jumlah_diterima) }}</span>
                        </li>
                        <li class="list-item mb-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Angsuran (bulan)</span>
                            <span>{{ formatRupiah($item->total_jumlah_angsuran_bulan) }}</span>
                        </li>
                        <li class="list-item mb-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Total Bayar</span>
                            <span>{{ formatRupiah($item->total_bayar) }}</span>
                        </li>
                        @if ($item->status == 1)
                            <li class="list-item mb-3 d-flex justify-content-between">
                                <span class="font-weight-bold">Sudah Terbayar</span>
                                <span>{{ formatRupiah($item->tagihanSudahTerbayar()) }}</span>
                            </li>
                            <li class="list-item mb-3 d-flex justify-content-between">
                                <span class="font-weight-bold">Sisa Belum Terbayar</span>
                                <span>{{ formatRupiah($item->tagihanBelumTerbayar()) }}</span>
                            </li>
                        @endif
                        <li class="list-item mb-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Metode Pencairan</span>
                            <span>{{ $item->met_pencairan ? $item->met_pencairan->getFull() : '-' }}</span>
                        </li>
                        <li class="list-item mb-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Tanggal Pengajuan</span>
                            <span>
                                {{ $item->created_at->translatedFormat('d-m-Y') }}
                            </span>
                        </li>
                        @if ($item->tanggal_diterima)
                            <li class="list-item mb-3 d-flex justify-content-between">
                                <span class="font-weight-bold">Tanggal Disetujui</span>
                                <span>
                                    {{ $item->tanggal_diterima->translatedFormat('d-m-Y') }}
                                </span>
                            </li>
                        @endif

                        <li class="list-item mb-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Status</span>
                            <span>
                                {!! $item->status() !!}
                            </span>
                        </li>

                        @if ($item->status != 2 && auth()->user()->role !== 'anggota')
                            <li class="list-item mb-3 d-flex justify-content-between">
                                <span class="font-weight-bold">Aksi</span>
                                <div>
                                    <form action="javscript:void(0)" id="formStatusPinjaman" method="post">
                                        @csrf
                                        @method('patch')
                                        @if ($item->status == 0)
                                            <button class="btn btn-sm btn-info btnStatusPinjaman"
                                                data-id="{{ $item->id }}" data-status="1"
                                                data-action="{{ route('pinjaman.update', $item->id) }}">Set
                                                Disetujui</button>
                                            <button class="btn btn-sm btn-danger btnStatusPinjaman"
                                                data-id="{{ $item->id }}" data-status="3"
                                                data-action="{{ route('pinjaman.update', $item->id) }}">Set
                                                Ditolak</button>
                                        @elseif($item->status == 1)
                                            @if ($item->cekVerifikasiStatusAngsuran() == true)
                                                <button class="btn btn-sm btn-success btnStatusPinjaman "
                                                    data-id="{{ $item->id }}" data-status="2"
                                                    data-action="{{ route('pinjaman.update', $item->id) }}">Set
                                                    Selesai</button>
                                            @else
                                                <button class="btn disabled btn-sm btn-success" type="button">Set
                                                    Selesai</button>
                                            @endif
                                        @elseif($item->status == 3)
                                            <button class="btn btn-sm btn-info btnStatusPinjaman"
                                                data-id="{{ $item->id }}" data-status="1"
                                                data-action="{{ route('pinjaman.update', $item->id) }}">Set
                                                Disetujui</button>
                                        @endif
                                    </form>
                                </div>
                            </li>
                        @endif
                        <li class="list-item mt-3 d-flex justify-content-between">
                            <span class="font-weight-bold">
                                <a href="{{ route('pinjaman.index') }}" class="btn btn-sm btn-warning">Kembali</a>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @if ($item->angsuran->count() < 1)
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Perhatian!</strong>
                            <p>Angsuran dibuatkan secara otomatis ketika admin menyetujui pinjaman tersebut.</p>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @elseif($item->tanggal_diterima)
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Perhatian!</strong>
                            <p>Pembayaran angsuran tidak boleh melebihi tanggal
                                {{ $item->tanggal_diterima->translatedFormat('d') }} dari bulan angsuran dengan nominal
                                {{ formatRupiah($item->total_jumlah_angsuran_bulan) }} / bulan</p>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <h4 class="card-title mb-5 text-center">Tagihan</h4>
                    <table class="table dtTable table-hover" id="dtTable">
                        <thead>
                            <tr>
                                <th>No.</th>
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
                            @foreach ($item->angsuran as $key => $angsuran)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>Angsuran Bulanan</td>
                                    <td>{{ formatTanggal($item->tanggal_diterima) . ' ' . konversiBulan($angsuran->bulan) . ' ' . $angsuran->tahun }}
                                    </td>
                                    <td>{{ formatRupiah($item->total_jumlah_angsuran_bulan) }}</td>
                                    <td>
                                        {!! $angsuran->status() !!}
                                    </td>
                                    @if (auth()->user()->role !== 'anggota')
                                        <td>
                                            <a href="{{ route('pinjaman-angsuran.edit', $angsuran->uuid) }}"
                                                class="btn btn-sm py-2 btn-warning">Edit</a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            @if ($item->status == 1)
                                <tr>
                                    <td>{{ $item->angsuran->count() + 1 }}</td>
                                    <td>Tagihan ADM ({{ $item->lama_angsuran->potongan_awal_persen . '%' }})</td>
                                    <td> - </td>
                                    <td>{{ formatRupiah($item->potongan_awal) }}</td>
                                    <td> {!! $item->statusTagihanPotonganAwal() !!}</td>
                                    @if (auth()->user()->role !== 'anggota')
                                        <td>
                                            @if ($item->status_potongan_awal == 1)
                                                <form action="{{ route('pinjaman.set-status-potongan-awal') }}"
                                                    method="post" id="formUpdateStatusPotonganAwal">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <input type="hidden" name="status" value="0">
                                                    <button type="submit" class="btn btn-sm py-2 btn-danger ">Set Belum
                                                        Bayar</button>
                                                </form>
                                            @else
                                                <form action="{{ route('pinjaman.set-status-potongan-awal') }}"
                                                    method="post" id="formUpdateStatusPotonganAwal">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <input type="hidden" name="status" value="1">
                                                    <button type="submit" class="btn btn-sm py-2 btn-success ">Set Sudah
                                                        Bayar</button>
                                                </form>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endif
                        </tbody>
                    </table>
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
    </script>
@endpush
