@extends('layouts.app')

@section('title', 'Kelola Pesanan')

@section('content')
    <div class="container">
        <h2>Daftar Pesanan</h2>
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
                                <th>ID Pesanan</th>
                                <th>Nama Pelanggan</th>
                                <th>Total</th>
                                <th>Metode Bayar</th>
                                <th>Status Bayar</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanans as $index => $pesanan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $pesanan->idpesanan }}</td>
                                    <td>{{ $pesanan->nama }}</td>
                                    <td>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td>
                                    <td>{{ $pesanan->metode_bayar }}</td>
                                    <td>
                                        @if($pesanan->status_bayar == 1)
                                            <span class="badge badge-success">Lunas</span>
                                        @else
                                            <span class="badge badge-warning">Belum Bayar</span>
                                        @endif
                                    </td>
                                    <td>{{ $pesanan->timestamp->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.pesanan.show', $pesanan->idpesanan) }}" class="btn btn-sm btn-primary btn-link-loader">Detail</a>
                                        @if($pesanan->status_bayar == 0)
                                            <form action="{{ route('admin.pesanan.update', $pesanan->idpesanan) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status_bayar" value="1">
                                                <button type="submit" class="btn btn-sm btn-success btn-loader"
                                                    data-confirm="Tandai pesanan ini sebagai lunas?"
                                                    data-loading-text="Memproses...">
                                                    Lunas
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Tidak ada pesanan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
