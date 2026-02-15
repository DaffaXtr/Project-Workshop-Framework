@extends('layouts.app')

@section('title', 'Buku')

@section('content')
<div class="container">
    <h2>Daftar Buku</h2>
    <a href="{{ route('buku.create') }}" class="btn btn-sm btn-success mb-3">Tambah Buku</a>
</div>

<div class="container">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>Kode</th>
                            <th>Judul</th>
                            <th>Pengarang</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buku as $index => $b)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $b->kode }}</td>
                            <td>{{ $b->judul }}</td>
                            <td>{{ $b->pengarang }}</td>
                            <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                            <td>
                                <a href="{{ route('buku.edit', $b->idbuku) }}" class="btn btn-sm btn-primary">Edit</a>

                                <form action="{{ route('buku.destroy', $b->idbuku) }}" 
                                      method="POST" 
                                      style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus buku ini?')">
                                        Hapus
                                    </button>
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
