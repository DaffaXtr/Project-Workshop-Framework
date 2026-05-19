@extends('layouts.app')

@section('title', 'Lokasi Toko')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between mb-3">
        <h3>Data Lokasi Toko</h3>

        <a href="{{ route('lokasi-toko.create') }}"
           class="btn btn-primary">

            Tambah Toko
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">

        <div class="card-body">

            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th>Barcode</th>
                        <th>Nama Toko</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Accuracy</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($tokos as $toko)

                    <tr>
                        <td>{{ $toko->barcode }}</td>

                        <td>{{ $toko->nama_toko }}</td>

                        <td>{{ $toko->latitude }}</td>

                        <td>{{ $toko->longitude }}</td>

                        <td>
                            {{ $toko->accuracy }} meter
                        </td>

                        <td>
                            <a href="{{ route('lokasi-toko.qrcode', $toko->barcode) }}"
                               class="btn btn-success btn-sm">

                                Cetak QR
                            </a>

                            <a href="{{ route('lokasi-toko.edit', $toko->barcode) }}"
                               class="btn btn-primary btn-sm">

                                Edit
                            </a>

                            <form action="{{ route('lokasi-toko.destroy', $toko->barcode) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="6"
                            class="text-center">

                            Belum ada data toko
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection