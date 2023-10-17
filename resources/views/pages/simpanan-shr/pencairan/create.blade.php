@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Perhatian!</strong>
                <p>Pencairan Simpanan SHR Tidak bisa dibatalkan!.</p>
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
                    <h4 class="card-title mb-5">Buat Pencairan Simpanan SHR</h4>
                    <form action="{{ route('pencairan-simpanan-shr.index') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class='form-group mb-3'>
                            <label for='anggota_id' class='mb-2'>Anggota <span class="text-danger">*</span></label>
                            <select name="anggota_id" id="anggota_id"
                                class="form-control select2 @error('anggota_id') is-invalid @enderror">
                                <option value="">Pilih Anggota</option>
                                @foreach ($data_anggota as $anggota)
                                    <option value="{{ $anggota->id }}">{{ $anggota->nama }}</option>
                                @endforeach
                            </select>
                            @error('anggota_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class='form-group mb-3'>
                                    <label for='' class='mb-2'>NIP</label>
                                    <input type='text' name='nip' id="nip" class='form-control' value=''
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class='form-group mb-3'>
                                    <label for='' class='mb-2'>Jenis Kelamin</label>
                                    <input type='text' name='jenis_kelamin' id="jenis_kelamin" class='form-control'
                                        value='' readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class='form-group mb-3'>
                                    <label for='' class='mb-2'>Tempat Lahir</label>
                                    <input type='text' name='tempat_lahir' id="tempat_lahir" class='form-control'
                                        value='' readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class='form-group mb-3'>
                                    <label for='' class='mb-2'>Tanggal Lahir</label>
                                    <input type='text' name='tanggal_lahir' id="tanggal_lahir" class='form-control'
                                        value='' readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class='form-group mb-3'>
                                    <label for='' class='mb-2'>Nomor Telepon</label>
                                    <input type='text' name='nomor_telepon' id="nomor_telepon" class='form-control'
                                        value='' readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class='form-group mb-3'>
                                    <label for='' class='mb-2'>Jabatan</label>
                                    <input type='text' name='jabatan' id="jabatan" class='form-control' value=''
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <div class='form-group mb-3'>
                            <label for='metode_pembayaran_id' class='mb-2'>Metode Pencairan <span
                                    class="text-danger">*</span></label>
                            <select name="metode_pembayaran_id" id="metode_pembayaran_id"
                                class="form-control @error('metode_pembayaran_id') is-invalid @enderror">
                                <option value="" selected>Pilih Metode Pencairan</option>
                                @foreach ($data_metode_pembayaran as $pembayaran)
                                    <option value="{{ $pembayaran->id }}">{{ $pembayaran->getFull() }}</option>
                                @endforeach
                            </select>
                            @error('metode_pembayaran_id')
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
                                    <option value="{{ $periode->id }}">
                                        {{ $periode->periode() }} @if ($periode->status == 1)
                                            (Periode Aktif)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('periode_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class='form-group mb-3'>
                                    <label for='' class='mb-2'>Saldo</label>
                                    <input type='text' name='saldo' id="saldo" class='form-control'
                                        value='' readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('pencairan-simpanan-shr.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Buat Pencairan</button>
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


            $('#anggota_id').on('change', function() {
                let anggota_id = $(this).val();
                $.ajax({
                    url: "{{ route('anggota.detail-json', ':id') }}".replace(':id', anggota_id),
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.status === 'success') {
                            let anggota = response.data;
                            $('#nip').val(anggota.nip);
                            $('#jenis_kelamin').val(anggota.jenis_kelamin);
                            $('#tempat_lahir').val(anggota.tempat_lahir);
                            $('#tanggal_lahir').val(anggota.tanggal_lahir_format);
                            $('#nomor_telepon').val(anggota.nomor_telepon);
                            $('#jabatan').val(anggota.jabatan.nama);
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }

                })
            })

            $('#periode_id').on('change', function() {
                let periode_id = $(this).val();
                let anggota_id = $('#anggota_id').val();

                $.ajax({
                    url: "{{ route('simpanan-shr.cek-saldo') }}",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        anggota_id,
                        periode_id
                    },
                    success: function(response) {

                        if (response.status === 'success') {
                            $('#saldo').val(formatRupiah(response.saldo));
                            let status_pencairan = response.status_pencairan == 0 ?
                                'Belum Dicairkan' : 'Sudah Dicairkan';
                            $('#status_pencairan').val(status_pencairan);
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
