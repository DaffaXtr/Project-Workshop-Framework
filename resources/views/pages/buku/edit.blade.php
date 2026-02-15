@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
<div class="container">
    <h2>Edit Buku</h2>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('buku.update', $buku->idbuku) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label>Kode</label>
                    <input type="text" 
                           name="kode" 
                           class="form-control"
                           value="{{ old('kode', $buku->kode) }}">
                </div>

                <div class="form-group mb-3">
                    <label>Judul</label>
                    <input type="text" 
                           name="judul" 
                           class="form-control" 
                           value="{{ old('judul', $buku->judul) }}"
                           required>
                </div>

                <div class="form-group mb-3">
                    <label>Pengarang</label>
                    <input type="text" 
                           name="pengarang" 
                           class="form-control" 
                           value="{{ old('pengarang', $buku->pengarang) }}"
                           required>
                </div>

                <div class="form-group mb-3">
                    <label>Kategori</label>
                    <select name="idkategori" class="form-control">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->idkategori }}"
                                {{ old('idkategori', $buku->idkategori) == $k->idkategori ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('buku.index') }}" class="btn btn-secondary">Kembali</a>

            </form>

        </div>
    </div>
</div>
@endsection
