@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Edit Simpanan Wajib</h4>
                    <form action="{{ route('simpanan-wajib.update', $item->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class='form-group mb-3'>
                            <label for='bulan' class='mb-2'>Bulan <span class="text-danger">*</span></label>
                            <input type='text' name='bulan' class='form-control @error('bulan') is-invalid @enderror'
                                value='{{ konversiBulan($item->simpanan->bulan) ?? old('bulan') }}' readonly>
                            @error('bulan')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='tahun' class='mb-2'>Tahun <span class="text-danger">*</span></label>
                            <input type='text' name='tahun' class='form-control @error('tahun') is-invalid @enderror'
                                value='{{ $item->simpanan->tahun ?? old('tahun') }}' readonly>
                            @error('tahun')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='anggota' class='mb-2'>Anggota <span class="text-danger">*</span></label>
                            <input type='text' name='anggota' class='form-control @error('anggota') is-invalid @enderror'
                                value='{{ $item->anggota->nama ?? old('anggota') }}' readonly>
                            @error('anggota')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='nominal' class='mb-2'>Nominal <span class="text-danger">*</span></label>
                            <input type='text' name='nominal' class='form-control @error('nominal') is-invalid @enderror'
                                value='{{ formatRupiah($item->simpanan->nominal) ?? old('nominal') }}' readonly>
                            @error('nominal')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='metode_pembayaran_id' class='mb-2'>Metode Pembayaran</label>
                            <select name="metode_pembayaran_id" id="metode_pembayaran_id"
                                class="form-control @error('metode_pembayaran_id') is-invalid @enderror">
                                <option value="" selected>Pilih Metode Pembayaran</option>
                                @foreach ($data_metode_pembayaran as $metode_pembayaran)
                                    <option @selected($metode_pembayaran->id == $item->metode_pembayaran_id) value="{{ $metode_pembayaran->id }}">
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
                        <div class='form-group mb-3'>
                            <label for='status_tagihan' class='mb-2'>Status Tagihan <span
                                    class="text-danger">*</span></label>
                            <select name="status_tagihan" id="status_tagihan"
                                class="form-control @error('status') is-invalid @enderror">
                                <option @selected($item->status_tagihan == 0) value="0">Belum Bayar</option>
                                <option @selected($item->status_tagihan == 1) value="1">Menunggu Verifikasi</option>
                                <option @selected($item->status_tagihan == 2) value="2">Lunas</option>
                            </select>
                            @error('status_tagihan')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('simpanan-wajib.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
