@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-3 align-self-center">Data Pegawai</h4>
                        <a href="{{ route('pegawai.create') }}" class="btn my-2 mb-3 btn-sm py-2 btn-primary">Tambah
                            Pegawai</a>
                    </div>
                    <table class="table dtTable table-hover" id="dtTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Avatar</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ $item->avatar() }}" class="img-fluid" alt="">
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->role }}</td>
                                    <td>{!! $item->isActive() !!}</td>
                                    <td>
                                        <a
                                            @if ($item->id == auth()->id()) title="Tidak ada akses!" href="javascript:void(0)" class="btn btn-sm py-2 btn-info disabled" @else href="{{ route('pegawai.edit', $item->id) }}"
                                            class="btn btn-sm py-2 btn-info" @endif>Edit</a>
                                        <form action="javascript:void(0)" method="post" class="d-inline" id="formDelete">
                                            @csrf
                                            @method('delete')
                                            <button
                                                @if ($item->id == auth()->id()) title="Tidak ada akses!" href="javascript:void(0)"
                                                class="btn btn-sm py-2 btn-danger disabled"
                                            @else
                                                class="btn btnDelete btn-sm py-2 btn-danger"
                                                data-action="{{ route('pegawai.destroy', $item->id) }}" @endif>Hapus</button>
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
