@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Edit Jenis Simpanan</h4>
                    <form action="{{ route('jenis-simpanan.update', $item->id) }}" method="post">
                        @csrf
                        @method('patch')
                        <div class='form-group mb-3'>
                            <label for='jenis' class='mb-2'>Jenis <span class="text-danger">*</span></label>
                            <input type='text' name='jenis' class='form-control @error('jenis') is-invalid @enderror'
                                value='{{ $item->jenis ?? old('jenis') }}'>
                            @error('jenis')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='nominal' class='mb-2'>Nominal <span class="text-danger">*</span></label>
                            <input type='number' name='nominal' class='form-control @error('nominal') is-invalid @enderror'
                                value='{{ $item->nominal ?? old('nominal') }}'>
                            @error('nominal')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='periode_id' class='mb-2'>Periode</label>
                            <select name="periode_id" id="periode_id"
                                class="form-control @error('periode_id') is-invalid @enderror">
                                <option value="" selected>Pilih Periode</option>
                                @foreach ($data_periode as $periode)
                                    <option @selected($periode->id == $item->periode_id) value="{{ $periode->id }}">
                                        {{ $periode->periode() }}</option>
                                @endforeach
                            </select>
                            @error('periode_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('jenis-simpanan.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
