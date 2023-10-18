@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Edit Simpanan SHR</h4>
                    <form action="{{ route('simpanan-shr.update', $item->uuid) }}" method="post">
                        @csrf
                        @method('patch')

                        <div class='form-group mb-3'>
                            <label for='nama_anggota' class='mb-2'>Nama Anggota</label>
                            <input type='text' name='nama_anggota'
                                class='form-control @error('nama_anggota') is-invalid @enderror'
                                value='{{ $item->anggota->nama ?? old('nama_anggota') }}' readonly>
                            @error('nama_anggota')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='nip' class='mb-2'>NIP</label>
                            <input type='text' name='nip' class='form-control @error('nip') is-invalid @enderror'
                                value='{{ $item->anggota->nip ?? old('nip') }}' readonly>
                            @error('nip')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='periode_id' class='mb-2'>Periode <span class="text-danger">*</span></label>
                            <select name="periode_id" id="periode_id"
                                class="form-control @error('periode_id') is-invalid @enderror">
                                <option value="" selected>Pilih Periode</option>
                                @foreach ($data_periode as $periode)
                                    <option @selected($periode->id == $item->periode_id) value="{{ $periode->id }}">
                                        {{ $periode->periode() }}</option>
                                @endforeach
                            </select>
                            @error('periode_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='bulan' class='mb-2'>Bulan <span class="text-danger">*</span></label>
                            <select name="bulan" id="bulan" class="form-control @error('bulan') is-invalid @enderror">
                                <option value="">Pilih Bulan</option>
                                @foreach ($data_bulan as $bulan)
                                    <option @selected($bulan->no == $item->bulan) value="{{ $bulan->no }}">{{ $bulan->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bulan')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='tahun' class='mb-2'>Tahun <span class="text-danger">*</span></label>
                            <select name="tahun" id="tahun" class="form-control @error('tahun') is-invalid @enderror">
                                <option value="">Pilih Tahun</option>
                                @foreach ($data_tahun as $tahun)
                                    <option @selected($tahun == $item->tahun) value="{{ $tahun }}">{{ $tahun }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tahun')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='nominal' class='mb-2'>Nominal <span class="text-danger">*</span></label>
                            <input type='text' name='nominal' class='form-control @error('nominal') is-invalid @enderror'
                                value='{{ $item->nominal ?? old('nominal') }}' readonly>
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
                                    <option @selected($metode_pembayaran->id == $item->metode_pembayaran_id) value="{{ $metode_pembayaran->id }}">
                                        {{ $metode_pembayaran->getFull() }}</option>
                                @endforeach
                            </select>
                            @error('metode_pembayaran_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='status' class='mb-2'>Status <span class="text-danger">*</span></label>
                            <select name="status" id="status"
                                class="form-control @error('status') is-invalid @enderror">
                                <option value="" selected>Pilih Status</option>
                                <option @selected($item->status == 0) value="0">Belum Bayar</option>
                                <option @selected($item->status == 1) value="1">Menunggu Verifikasi</option>
                                <option @selected($item->status == 2) value="2">Lunas</option>
                            </select>
                            @error('status')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('simpanan-shr.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<x-Sweetalert />
@push('stylesBefore')
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
            $('.select2').select2();

            function formatRupiah(angka) {
                var formatter = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                });
                return formatter.format(angka);
            }

            $('#periode_id').on('change', function() {
                let periode_id = $(this).val();
                let anggota_id = $('#anggota_id').val();
                let jenis = 'shr';

                $.ajax({
                    url: "{{ route('periode.cek-nominal-simpanan') }}",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        jenis,
                        periode_id
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('input[name=nominal]').val(response.nominal);
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            })
        })
    </script>
@endpush
