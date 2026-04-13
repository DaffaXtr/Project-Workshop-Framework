@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container">
    <h2>Detail Pesanan #{{ $pesanan->idpesanan }}</h2>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Informasi Pesanan</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nama Pelanggan:</strong><br>{{ $pesanan->nama }}</p>
                    <p><strong>Total:</strong><br>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>
                    <p><strong>Metode Bayar:</strong><br>{{ $pesanan->metode_bayar }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status Bayar:</strong><br>
                        @if($pesanan->status_bayar == 1)
                            <span class="badge badge-success">Lunas</span>
                        @else
                            <span class="badge badge-warning">Belum Bayar</span>
                        @endif
                    </p>
                    <p><strong>Waktu Pesanan:</strong><br>{{ $pesanan->timestamp->format('d/m/Y H:i:s') }}</p>
                    <p><strong>No. Transaksi:</strong><br>{{ $pesanan->transaction_id ?? '-' }}</p>
                </div>
            </div>
            @if($pesanan->status_message)
                <p><strong>Pesan Status:</strong><br>{{ $pesanan->status_message }}</p>
            @endif
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Detail Item Pesanan</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>Menu</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanan->details as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $detail->menu->nama_menu ?? 'Menu Dihapus' }}</td>
                                <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada item</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="text-right mt-2">
                <h5>Total Pesanan: <strong>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</strong></h5>
            </div>
        </div>
    </div>

    <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary btn-link-loader">Kembali</a>
</div>
@endsection
