@extends('layouts.app')
@section('content')
    @if ($pengajuan->count() < 1)
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Perhatian!</strong>
                    <p>Simpanan Wajib bisa dicairkan ketika anda sudah tidak lagi menjadi anggota. Pastikan semua tagihan
                        pinjaman sudah
                        dibayarkan.
                        <br>Anda akan di nonaktifkan sesudah dana pencairan simpanan wajib anda terima.
                    </p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
    <div class="row mb-3">
        <div class="col-md-12">
            @if ($pengajuan->count() < 1)
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-5">Pengajuan Pencairan Simpanan Wajib</h4>
                        <form
                            @if ($pengajuan->count() < 1) action="{{ route('simpanan-wajib.pengajuan-pencairan.proses') }}" @endif
                            method="post">
                            @csrf
                            <div class='form-group mb-3'>
                                <label for='saldo' class='mb-2'>Saldo</label>
                                <input type='text' name='saldo'
                                    class='form-control @error('saldo') is-invalid @enderror'
                                    value='{{ formatRupiah($saldo) }}' readonly>
                                @error('saldo')
                                    <div class='invalid-feedback'>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class='form-group mb-3'>
                                <label for='metode_pembayaran_id' class='mb-2'>Metode Pencairan <span
                                        class="text-danger">*</span></label>
                                <select name="metode_pembayaran_id" id="metode_pembayaran_id"
                                    class="form-control @error('metode_pembayaran_id') is-invalid @enderror">
                                    <option value="" selected>Pilih Metode Pembayaran</option>
                                    @foreach ($data_metode_pembayaran as $metode_pembayaran)
                                        <option value="{{ $metode_pembayaran->id }}">
                                            {{ $metode_pembayaran->getFull() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('metode_pembayaran_id')
                                    <div class='invalid-feedback'>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group text-right">
                                <button
                                    @if ($pengajuan->count() < 1) class="btn btn-primary" @else type="button" class="btn btn-primary disbled" @endif>Ajukan</button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-warning" role="alert">
                    <strong>Perhatian!</strong>
                    <p>
                        Pengajuan pencairan dana simpanan wajib yang anda ajukan telah kami proses. Mohon tunggu proses
                        validasi. <br>
                        Untuk membatalkan pengajuan pencairan dana simpanan wajib anda bisa mengklik tombol batal dibawah
                        ini.
                    </p>
                    <form action="{{ route('simpanan-wajib.pengajuan-pencairan.batal') }}" method="post" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-danger">Batalkan Pengajuan</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5 text-center">Riwayat Pengajuan Pencairan Simpanan Wajib</h4>
                    <table class="table dtTable table-hover" id="dtTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Anggota</th>
                                <th>Nominal</th>
                                <th>Metoe Pencairan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data_pengajuan_pencairan as $item)
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
@endsection
<x-Sweetalert />
