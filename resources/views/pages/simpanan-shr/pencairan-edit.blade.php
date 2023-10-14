@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Edit Pencairan Simpanan SHR</h4>
                    <form action="{{ route('simpanan-shr.pencairan.update', $item->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class='form-group mb-3'>
                            <label for='' class='mb-2'>Nama Anggota</label>
                            <input type='text' name='nama' id="nama" class='form-control'
                                value='{{ $item->anggota->nama }}' readonly>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class='form-group mb-3'>
                                    <label for='' class='mb-2'>NIP</label>
                                    <input type='text' name='nip' id="nip" class='form-control'
                                        value='{{ $item->anggota->nip }}' readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class='form-group mb-3'>
                                    <label for='' class='mb-2'>Jenis Kelamin</label>
                                    <input type='text' name='jenis_kelamin' id="jenis_kelamin" class='form-control'
                                        value='{{ $item->anggota->jenis_kelamin }}' readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class='form-group mb-3'>
                                    <label for='' class='mb-2'>Tempat Lahir</label>
                                    <input type='text' name='tempat_lahir' id="tempat_lahir" class='form-control'
                                        value='{{ $item->anggota->tempat_lahir }}' readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class='form-group mb-3'>
                                    <label for='' class='mb-2'>Tanggal Lahir</label>
                                    <input type='text' name='tanggal_lahir' id="tanggal_lahir" class='form-control'
                                        value='{{ $item->anggota->tanggal_lahir->translatedFormat('d F Y') }}' readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class='form-group mb-3'>
                                    <label for='' class='mb-2'>Nomor Telepon</label>
                                    <input type='text' name='nomor_telepon' id="nomor_telepon" class='form-control'
                                        value='{{ $item->anggota->nomor_telepon }}' readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class='form-group mb-3'>
                                    <label for='' class='mb-2'>Jabatan</label>
                                    <input type='text' name='jabatan' id="jabatan" class='form-control'
                                        value='{{ $item->anggota->jabatan->nama }}' readonly>
                                </div>
                            </div>
                        </div>
                        <div class='form-group mb-3'>
                            <label for='metode_pembayaran_id' class='mb-2'>Metode Pencairan <span
                                    class="text-danger">*</span></label>
                            <select name="metode_pembayaran_id" id="metode_pembayaran_id"
                                class="form-control @error('metode_pembayaran_id') is-invalid @enderror">
                                <option value="" selected>Pilih Metode Pencairan</option>
                                @foreach ($data_metode_pembayaran as $mp)
                                    <option value="{{ $mp->id }}" @selected($mp->id == $item->metode_pembayaran_id)>{{ $mp->getFull() }}
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
                            <label for='' class='mb-2'>Periode</label>
                            <input type='text' name='periode' id="periode" class='form-control'
                                value='{{ $item->periode ? $item->periode->periode() : 'Tidak Ada' }}' readonly>
                        </div>
                        <div class='form-group mb-3'>
                            <label for='' class='mb-2'>Nominal</label>
                            <input type='text' name='nominal' id="nominal" class='form-control'
                                value='Rp {{ number_format($item->nominal, 0, 2, '.') }}' readonly>
                        </div>
                        <div class='form-group mb-3'>
                            <label for='status' class='mb-2'>Status <span class="text-danger">*</span></label>
                            <select name="status" id="status"
                                class="form-control @error('status') is-invalid @enderror">
                                <option @selected($item->status == 0) value="0">Menunggu Validasi</option>
                                <option @selected($item->status == 1) value="1">Diterima</option>
                                <option @selected($item->status == 2) value="2">Ditolak</option>
                                <option @selected($item->status == 3) value="3">Dibatalkan</option>
                            </select>
                            @error('status')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('simpanan-shr.pencairan.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Update Pencairan</button>
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

                $.ajax({
                    url: '{{ route('metode-pembayaran.get-json.by-anggota') }}',
                    data: {
                        anggota_id
                    },
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(response) {
                        $('#metode_pembayaran_id').empty();
                        $('#metode_pembayaran_id').append(`
                            <option>Pilih Metode Pencairan</option>
                        `);
                        response.data.forEach(mp => {
                            $('#metode_pembayaran_id').append(`
                            <option value="${mp.id}">${mp.nama} - ${mp.nomor} (a.n ${mp.pemilik})</option>
                        `);
                        });
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
