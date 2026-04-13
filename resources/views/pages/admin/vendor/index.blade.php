@extends('layouts.app')

@section('title', 'Kelola Vendor')

@section('content')
    <div class="container">
        <h2>Daftar Vendor</h2>
        <a href="{{ route('admin.vendor.create') }}" class="btn btn-sm btn-success mb-3 btn-link-loader">Tambah Vendor</a>
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
                                <th>Nama Vendor</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vendors as $index => $vendor)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $vendor->nama_vendor }}</td>
                                    <td>
                                        <a href="{{ route('admin.vendor.edit', $vendor->idvendor) }}" class="btn btn-sm btn-primary btn-link-loader">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.vendor.destroy', $vendor->idvendor) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger btn-loader"
                                                data-confirm="Yakin ingin menghapus vendor ini?"
                                                data-loading-text="Menghapus...">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Tidak ada vendor</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
