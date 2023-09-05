@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Pengajuan Pinjaman</h4>
                    <form action="{{ route('pinjaman.store') }}" method="post">
                        @csrf
                        <div class='form-group mb-3'>
                            <label for='besar_pinjaman' class='mb-2'>Besar Pinjaman <span
                                    class="text-danger">*</span></label>
                            <input type='number' name='besar_pinjaman'
                                class='form-control @error('besar_pinjaman') is-invalid @enderror'
                                value='{{ old('besar_pinjaman') }}' id="besar_pinjaman">
                            @error('besar_pinjaman')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='keperluan' class='mb-2'>Untuk Keperluan <span class="text-danger">*</span></label>
                            <input type='text' name='keperluan'
                                class='form-control @error('keperluan') is-invalid @enderror'
                                value='{{ old('keperluan') }}'>
                            @error('keperluan')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='lama_angsuran_id' class='mb-2'>Lama Angsuran <span
                                    class="text-danger">*</span></label>
                            <select name="lama_angsuran_id" id="lama_angsuran_id"
                                class="form-control @error('lama_angsuran_id') is-invalid @enderror">
                                <option value="" selected>Pilih Lama Angsuran</option>
                                @foreach ($data_lama_angsuran as $lama_angsuran)
                                    <option value="{{ $lama_angsuran->id }}">{{ $lama_angsuran->durasi . ' bulan' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lama_angsuran_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='potongan_awal' class='mb-2'>Potongan Awal</label>
                            <input type='text' name='potongan_awal'
                                class='form-control @error('potongan_awal') is-invalid @enderror'
                                value='{{ old('potongan_awal') }}' readonly>
                            @error('potongan_awal')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='jumlah_diterima' class='mb-2'>Jumlah Diterima</label>
                            <input type='text' name='jumlah_diterima'
                                class='form-control @error('jumlah_diterima') is-invalid @enderror'
                                value='{{ old('jumlah_diterima') }}' readonly>
                            @error('jumlah_diterima')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='angsuran_pokok_bulan' class='mb-2'>Angsuran Pokok (bulan)</label>
                            <input type='text' name='angsuran_pokok_bulan'
                                class='form-control @error('angsuran_pokok_bulan') is-invalid @enderror'
                                value='{{ old('angsuran_pokok_bulan') }}' readonly>
                            @error('angsuran_pokok_bulan')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='jasa_pinjaman_bulan' class='mb-2'>Jasa Pinjaman (bulan) </label>
                            <input type='text' name='jasa_pinjaman_bulan'
                                class='form-control @error('jasa_pinjaman_bulan') is-invalid @enderror'
                                value='{{ old('jasa_pinjaman_bulan') }}' readonly>
                            @error('jasa_pinjaman_bulan')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='total_jumlah_angsuran_bulan' class='mb-2'>Total jumlah angsuran (bulan)</label>
                            <input type='text' name='total_jumlah_angsuran_bulan'
                                class='form-control @error('total_jumlah_angsuran_bulan') is-invalid @enderror'
                                value='{{ old('total_jumlah_angsuran_bulan') }}' readonly>
                            @error('total_jumlah_angsuran_bulan')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <button class="btn btn-primary">Ajukan Pinjaman</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<x-Sweetalert />
@push('scripts')
    <script>
        $(function() {
            $('#lama_angsuran_id').on('change', function() {
                let lama_angsuran_id = $(this).val();
                let url = '{{ route('lama-angsuran.show', ':id') }}'
                // detail lama angsuran
                $.ajax({
                    url: url.replace(":id", lama_angsuran_id),
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(response) {
                        let data = response.data;
                        let besar_pinjaman = $('#besar_pinjaman').val();
                        // potongan awal
                        let potongan_awal = besar_pinjaman * (data.potongan_awal_persen / 100);
                        let jasa_pinjaman_bulan = besar_pinjaman * (data
                            .jasa_pinjaman_bulan_persen /
                            100);
                        let jumlah_diterima = besar_pinjaman - potongan_awal;
                        let angsuran_pokok_bulan = besar_pinjaman / 12;
                        let total_jumlah_angsuran_bulan = angsuran_pokok_bulan +
                            jasa_pinjaman_bulan;
                        if (besar_pinjaman > 0) {
                            // set ke inputan
                            $('input[name=potongan_awal]').val(formatRupiah(potongan_awal));
                            $('input[name=jumlah_diterima]').val(formatRupiah(jumlah_diterima));
                            $('input[name=angsuran_pokok_bulan]').val(formatRupiah(
                                angsuran_pokok_bulan));
                            $('input[name=jasa_pinjaman_bulan]').val(formatRupiah(
                                jasa_pinjaman_bulan));
                            $('input[name=total_jumlah_angsuran_bulan]').val(
                                formatRupiah(total_jumlah_angsuran_bulan));
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            })

            function formatRupiah(angka) {
                return angka.toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                });
            }
        })
    </script>
@endpush
