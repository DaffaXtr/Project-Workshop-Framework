@extends('layouts.app')

@section('title', 'Kelola Menu')

@section('content')
    <div class="container">
        <h2>Daftar Menu</h2>
        <a href="{{ route('admin.menu.create') }}" class="btn btn-sm btn-success mb-3 btn-link-loader">Tambah Menu</a>
    </div>

    <div class="container">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    @if($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Nama Menu</th>
                                <th>Harga</th>
                                <th>Vendor</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menus as $index => $menu)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $menu->nama_menu }}</td>
                                    <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                                    <td>{{ $menu->vendor->nama_vendor ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.menu.edit', $menu->idmenu) }}" class="btn btn-sm btn-primary btn-link-loader">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.menu.destroy', $menu->idmenu) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger btn-loader"
                                                data-confirm="Yakin ingin menghapus menu ini?"
                                                data-loading-text="Menghapus...">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Tidak ada menu</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
