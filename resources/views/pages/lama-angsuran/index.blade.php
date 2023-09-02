@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-3 align-self-center">Data Lama Angsuran</h4>
                        <a href="{{ route('lama-angsuran.create') }}" class="btn my-2 mb-3 btn-sm py-2 btn-primary">Tambah
                            Lama Angsuran</a>
                    </div>
                    <table class="table dtTable table-hover" id="dtTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Durasi</th>
                                <th>Potongan Awal (%)</th>
                                <th>Jasa Pinjaman Per Bulan (%)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->durasi . ' bulan' }}</td>
                                    <td>{{ $item->potongan_awal_persen . '%' }}</td>
                                    <td>{{ $item->jasa_pinjaman_bulan_persen . '%' }}</td>
                                    <td>
                                        <a href="{{ route('lama-angsuran.edit', $item->id) }}"
                                            class="btn btn-sm py-2 btn-info">Edit</a>
                                        <form action="javascript:void(0)" method="post" class="d-inline" id="formDelete">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btnDelete btn-sm py-2 btn-danger"
                                                data-action="{{ route('lama-angsuran.destroy', $item->id) }}">Hapus</button>
                                        </form>
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
<x-Sweetalert />
<x-Datatable />
