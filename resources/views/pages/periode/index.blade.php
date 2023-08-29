@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-3 align-self-center">Data Periode</h4>
                        <a href="{{ route('periode.create') }}" class="btn my-2 mb-3 btn-sm py-2 btn-primary">Tambah
                            Periode</a>
                    </div>
                    <table class="table dtTable table-hover" id="dtTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->periode() }}</td>
                                    <td>{!! $item->status() !!}</td>
                                    <td>
                                        <a href="{{ route('periode.edit', $item->id) }}"
                                            class="btn btn-sm py-2 btn-info">Edit</a>
                                        <form action="javascript:void(0)" method="post" class="d-inline" id="formDelete">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btnDelete btn-sm py-2 btn-danger"
                                                data-action="{{ route('periode.destroy', $item->id) }}">Hapus</button>
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
