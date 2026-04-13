@extends('layouts.app')

@section('title', 'Tambah Vendor')

@section('content')
<div class="container">
    <h2>Tambah Vendor</h2>

    <div class="card">
        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Ada kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('admin.vendor.store') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label>Nama Vendor</label>
                    <input type="text" name="nama_vendor" class="form-control @error('nama_vendor') is-invalid @enderror" 
                           value="{{ old('nama_vendor') }}" required>
                    @error('nama_vendor')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-loader">Simpan</button>
                <a href="{{ route('admin.vendor.index') }}" class="btn btn-secondary btn-link-loader">Kembali</a>

            </form>

        </div>
    </div>
</div>
@endsection
