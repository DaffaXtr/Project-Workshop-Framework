@extends('layouts.app')

@section('title', 'Cetak QR Code - ' . $toko->nama_toko)

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>QR Code: {{ $toko->nama_toko }}</h3>
        <a href="{{ route('lokasi-toko.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card text-center">

                <div class="card-body p-5">

                    <h5 class="mb-3">{{ $toko->nama_toko }}</h5>

                    <div class="mb-4">
                        {!! QrCode::size(300)->generate($toko->barcode) !!}
                    </div>

                    <p class="text-muted">
                        <strong>Barcode:</strong> {{ $toko->barcode }}
                    </p>

                    <p class="text-muted small">
                        <strong>Latitude:</strong> {{ $toko->latitude }}<br>
                        <strong>Longitude:</strong> {{ $toko->longitude }}<br>
                        <strong>Accuracy:</strong> {{ $toko->accuracy }} meter
                    </p>

                    <button onclick="window.print()" class="btn btn-primary mt-3">
                        <i class="mdi mdi-printer"></i> Cetak
                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

<style>
    @media print {
        body {
            background-color: white;
        }
        .container {
            max-width: 100%;
        }
        .btn, .btn-secondary, a {
            display: none !important;
        }
    }
</style>

@endsection
