@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5 text-center">Detail Anggota</h4>
                    <div class="d-flex justify-content-center">
                        <div class="foto" style="background-image: url({{ $item->user->avatar() }})">
                        </div>
                    </div>
                    <ul class="list-unstyled mt-5">
                        <li class="list-item d-flex justify-content-between">
                            <span class="font-weight-bold">Nama</span>
                            <span>{{ $item->user->name }}</span>
                        </li>
                        <li class="list-item mt-3 d-flex justify-content-between">
                            <span class="font-weight-bold">NIP</span>
                            <span>{{ $item->nip ?? '-' }}</span>
                        </li>
                        <li class="list-item mt-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Jenis Kelamin</span>
                            <span>{{ $item->jenis_kelamin }}</span>
                        </li>
                        <li class="list-item mt-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Tempat Lahir</span>
                            <span>{{ $item->tempat_lahir }}</span>
                        </li>
                        <li class="list-item mt-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Tanggal Lahir</span>
                            <span>{{ $item->tanggal_lahir->translatedFormat('d-m-Y') }}</span>
                        </li>
                        <li class="list-item mt-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Alamat</span>
                            <span>{{ $item->alamat }}</span>
                        </li>
                        <li class="list-item mt-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Nomor Hp</span>
                            <span>{{ $item->nomor_telepon }}</span>
                        </li>
                        <li class="list-item mt-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Email</span>
                            <span>{{ $item->user->email }}</span>
                        </li>
                        <li class="list-item mt-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Agama</span>
                            <span>{{ $item->agama->nama }}</span>
                        </li>
                        <li class="list-item mt-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Jabatan</span>
                            <span>{{ $item->jabatan->nama }}</span>
                        </li>
                        <li class="list-item mt-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Role</span>
                            <span>{{ $item->user->role }}</span>
                        </li>
                        <li class="list-item mt-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Bergabung</span>
                            <span>{{ $item->user->created_at->translatedFormat('l, d F Y') }}</span>
                        </li>
                        <li class="list-item mt-3 d-flex justify-content-between">
                            <span class="font-weight-bold">Status Akun</span>
                            <span>{!! $item->user->isActive() !!}</span>
                        </li>
                        <li class="list-item mt-3 d-flex justify-content-between">
                            <span class="font-weight-bold">
                                <a href="{{ route('anggota.index') }}" class="btn btn-sm btn-warning">Kembali</a>
                            </span>
                            <span>
                                <a href="{{ route('anggota.edit', $item->id) }}" class="btn btn-sm btn-info">Edit</a>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5 text-center">Aktivitas</h4>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .foto {
            height: 120px;
            width: 120px;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            border-radius: 50%;
        }
    </style>
@endpush
