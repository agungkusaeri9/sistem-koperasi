@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-3 align-self-center">Data Jenis Simpanan</h4>
                        <a href="{{ route('jenis-simpanan.create') }}" class="btn my-2 mb-3 btn-sm py-2 btn-primary">Tambah
                            Jenis Simpanan</a>
                    </div>
                    <table class="table dtTable table-hover" id="dtTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Jenis</th>
                                <th>Nominal</th>
                                <th>Periode</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->jenis }}</td>
                                    <td>{{ formatRupiah($item->nominal) }}</td>
                                    <td>{{ $item->periode_id ? $item->periode->periode() : '-' }}</td>
                                    <td>
                                        <a href="{{ route('jenis-simpanan.edit', $item->id) }}"
                                            class="btn btn-sm py-2 btn-info">Edit</a>
                                        <form action="javascript:void(0)" method="post" class="d-inline" id="formDelete">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btnDelete btn-sm py-2 btn-danger"
                                                data-action="{{ route('jenis-simpanan.destroy', $item->id) }}">Hapus</button>
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
