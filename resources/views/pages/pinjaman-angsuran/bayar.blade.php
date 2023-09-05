@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Bayar Angsuran ({{ konversiBulan($item->bulan) }}
                        {{ $item->tahun }})</h4>
                    <form
                        action="{{ route('pinjaman-angsuran.proses-bayar', [
                            'kode_pinjaman' => $item->pinjaman->kode,
                            'pinjaman_angsuran_id' => $item->id,
                        ]) }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        <div class='form-group mb-3'>
                            <label for='kode' class='mb-2'>Kode Pinjaman</label>
                            <input type='text' name='kode' class='form-control @error('kode') is-invalid @enderror'
                                value='{{ $item->pinjaman->kode }}' readonly>
                            @error('kode')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='nominal' class='mb-2'>Nominal</label>
                            <input type='text' name='nominal' class='form-control @error('nominal') is-invalid @enderror'
                                value='{{ formatRupiah($item->pinjaman->total_jumlah_angsuran_bulan) }}' readonly>
                            @error('nominal')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='metode_pembayaran_id' class='mb-2'>Metode Pembayaran <span
                                    class="text-danger">*</span></label>
                            <select name="metode_pembayaran_id" id="metode_pembayaran_id"
                                class="form-control @error('metode_pembayaran_id') is-invalid @enderror">
                                <option value="" selected>Pilih Metode Pembayaran</option>
                                @foreach ($data_metode_pembayaran as $metode_pembayaran)
                                    <option @selected($metode_pembayaran->id == $item->metode_pembayaran_id ?? old('metode_pembayaran_id')) value="{{ $metode_pembayaran->id }}">
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
                            <label for='bukti_pembayaran' class='mb-2'>Bukti Pembayaran <span
                                    class="text-danger">*</span></label>
                            <input type='file' name='bukti_pembayaran'
                                class='form-control @error('bukti_pembayaran') is-invalid @enderror'
                                value='{{ old('bukti_pembayaran') }}'>
                            @error('bukti_pembayaran')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('pinjaman.show', $item->pinjaman->kode) }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
