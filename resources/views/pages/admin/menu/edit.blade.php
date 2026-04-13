@extends('layouts.app')

@section('title', 'Edit Menu')

@section('content')
<div class="container">
    <h2>Edit Menu</h2>

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

            <form action="{{ route('admin.menu.update', $menu->idmenu) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label>Nama Menu</label>
                    <input type="text" name="nama_menu" class="form-control @error('nama_menu') is-invalid @enderror" 
                           value="{{ $menu->nama_menu }}" required>
                    @error('nama_menu')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label>Harga</label>
                    <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" 
                           value="{{ $menu->harga }}" min="0" required>
                    @error('harga')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label>Vendor</label>
                    <select name="idvendor" class="form-control @error('idvendor') is-invalid @enderror" required>
                        <option value="">-- Pilih Vendor --</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->idvendor }}" {{ $menu->idvendor == $vendor->idvendor ? 'selected' : '' }}>
                                {{ $vendor->nama_vendor }}
                            </option>
                        @endforeach
                    </select>
                    @error('idvendor')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-loader">Update</button>
                <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary btn-link-loader">Kembali</a>

            </form>

        </div>
    </div>
</div>
@endsection
