@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5 text-center">Tagihan Simpanan SHR</h4>
                    <table class="table dtTable table-hover" id="dtTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Nominal</th>
                                <th>Status Tagihan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $tagihan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ konversiBulan($tagihan->simpanan->bulan) }}</td>
                                    <td>{{ $tagihan->simpanan->tahun }}</td>
                                    <td>{{ formatRupiah($tagihan->simpanan->nominal) }}</td>
                                    <td>{!! $tagihan->status_tagihan() !!}</td>
                                    <td>
                                        @if ($tagihan->status_tagihan != 0)
                                            <a href="javascript:void(0)" class="btn disabled py-2 btn-sm btn-info">
                                                Bayar & Upload Bukti
                                            </a>
                                        @endif
                                        @if ($tagihan->status_tagihan == 0)
                                            <a href="{{ route('simpanan-shr.tagihan.bayar', [
                                                'id' => $tagihan->id,
                                            ]) }}"
                                                class="btn py-2 btn-sm btn-info">
                                                Bayar & Upload Bukti
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
<x-Datatable />
<x-Sweetalert />
