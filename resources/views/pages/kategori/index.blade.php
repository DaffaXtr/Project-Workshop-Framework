@extends('layouts.app')

@section('title', 'Kategori')

@section('style-page')

@endsection

@section('content')
    <div class="container">
        <h2>Daftar Kategori</h2>
        <a href="{{ route('kategori.create') }}" class="btn btn-sm btn-success mb-3 btn-link-loader">Tambah Kategori</a>
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
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kategori as $index => $kategori)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $kategori->nama_kategori }}</td>
                                    <td>
                                        <a href="{{ route('kategori.edit', $kategori->idkategori) }}"
                                            class="btn btn-sm btn-primary btn-link-loader">Edit</a>

                                        <form action="{{ route('kategori.destroy', $kategori->idkategori) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger btn-loader"
                                                data-confirm="Yakin ingin menghapus kategori ini?"
                                                data-loading-text="Menghapus...">Hapus</button>
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

@push('script-page')
    <script>
        console.log("Kategori Page Loaded");
    </script>
@endpush