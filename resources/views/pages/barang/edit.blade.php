@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
<div class="container">
    <h2>Edit Barang</h2>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label>Nama Barang</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $barang->nama) }}"
                        required>
                </div>

                <div class="form-group mb-3">
                    <label>Harga</label>
                    <input type="text" name="harga" class="form-control" value="{{ old('harga', $barang->harga) }}"
                        required>
                </div>

                <button type="submit" class="btn btn-primary btn-loader">Update</button>
                <a href="{{ route('barang.index') }}" class="btn btn-secondary btn-link-loader">Kembali</a>

            </form>

        </div>
    </div>
</div>
@endsection