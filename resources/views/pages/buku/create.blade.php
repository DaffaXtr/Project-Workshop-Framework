@extends('layouts.app')

@section('title', 'Tambah Buku')

@section('content')
<div class="container">
    <h2>Tambah Buku</h2>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('buku.store') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label>Kode</label>
                    <input type="text" name="kode" class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label>Judul</label>
                    <input type="text" name="judul" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label>Pengarang</label>
                    <input type="text" name="pengarang" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label>Kategori</label>
                    <select name="idkategori" class="form-control">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->idkategori }}">
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('buku.index') }}" class="btn btn-secondary">Kembali</a>

            </form>

        </div>
    </div>
</div>
@endsection
