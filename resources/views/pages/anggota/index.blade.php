@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-3 align-self-center">Data Anggota</h4>
                        <div>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#modalImport"
                                class="btn my-2 mb-3 btn-sm py-2 btn-info">Import
                                Anggota</a>
                            <a href="{{ route('anggota.create') }}" class="btn my-2 mb-3 btn-sm py-2 btn-primary">Tambah
                                Anggota</a>
                        </div>
                    </div>
                    <table class="table dtTable table-hover" id="dtTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Avatar</th>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Jenis Kelamin</th>
                                <th>Jabatan</th>
                                <th>No. HP</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ $item->user->avatar() }}" class="img-fluid" alt="">
                                    </td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->nip }}</td>
                                    <td>{{ $item->jenis_kelamin }}</td>
                                    <td>{{ $item->jabatan->nama ?? '-' }}</td>
                                    <td>{{ $item->nomor_telepon }}</td>
                                    <td>{!! $item->user->isActive() !!}</td>
                                    <td>
                                        <a href="{{ route('anggota.show', $item->id) }}"
                                            class="btn btn-sm py-2 btn-warning">Detail</a>
                                        <a href="{{ route('anggota.edit', $item->id) }}"
                                            class="btn btn-sm py-2 btn-info">Edit</a>
                                        <form action="javascript:void(0)" method="post" class="d-inline" id="formDelete">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btnDelete btn-sm py-2 btn-danger"
                                                data-action="{{ route('anggota.destroy', $item->id) }}">Hapus</button>
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

    <!-- Modal import-->
    <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Anggota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <p>Silahkan download terlebih dahulu Format Excel <a
                                href="{{ asset('assets/format/format_anggota.xlsx') }}" class="">Disini.</a> <br>
                            Selanjutkan upload file excel sesuai dengan format yang ada.</p>
                    </div>
                    <form action="{{ route('anggota.import') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class='form-group mb-3'>
                            <label for='file' class='mb-2'>File ( <span class="text-danger">xlsx</span> )</label>
                            <input type='file' name='file' class='form-control @error('file') is-invalid @enderror'
                                value='{{ old('file') }}'>
                            @error('file')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <button class="btn btn-danger">Import Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<x-Sweetalert />
<x-Datatable />
