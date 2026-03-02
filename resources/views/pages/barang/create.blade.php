@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
<div class="container">
    <h2>Tambah Barang</h2>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('barang.store') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label>Nama Barang</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label>Harga</label>
                    <input type="text" name="harga" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>

            </form>

        </div>
    </div>
</div>
@endsection