@extends('layouts.app')
@section('content')
    <div class="row mb-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Laporan Simpanan Wajib</h4>
                    <form action="{{ route('laporan.simpanan-wajib.print') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for='anggota_id' class='mb-2'>Anggota</label>
                                    <select name="anggota_id" id="anggota_id" class="form-control select2">
                                        <option @selected($status === '') value="">Pilih Anggota</option>
                                        @foreach ($data_anggota as $anggota)
                                            <option value="{{ $anggota->id }}">{{ $anggota->nama . ' | ' . $anggota->nip }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md align-self-center">
                                <button class="btn mt-2 btn-danger">Cetak PDF</button>
                            </div>
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
@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function() {
            $('.select2').select2();
        })
    </script>
@endpush
