@extends('layouts.app')

@section('title', 'Edit Lokasi Toko')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Edit Lokasi Toko</h2>
        <a href="{{ route('lokasi-toko.index') }}" class="btn btn-secondary btn-link-loader">Kembali</a>
    </div>

    <div class="card">

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('lokasi-toko.update', $toko->barcode) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="barcode">Barcode <span class="text-danger">*</span></label>
                    <input type="text"
                           id="barcode"
                           name="barcode"
                           class="form-control"
                           value="{{ $toko->barcode }}"
                           readonly>
                </div>

                <div class="form-group">
                    <label for="nama_toko">Nama Toko <span class="text-danger">*</span></label>
                    <input type="text"
                           id="nama_toko"
                           name="nama_toko"
                           class="form-control @error('nama_toko') is-invalid @enderror"
                           value="{{ $toko->nama_toko }}"
                           required>
                    @error('nama_toko')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <p class="text-muted small">
                            <i class="mdi mdi-information"></i> Anda bisa mengambil lokasi otomatis atau input manual
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <button
                            type="button"
                            id="btn-ambil-lokasi"
                            onclick="ambilLokasi()"
                            class="btn btn-info w-100">
                            <i class="mdi mdi-crosshairs-gps"></i> Ambil Lokasi Otomatis
                        </button>
                    </div>
                    <div class="col-6">
                        <button
                            type="button"
                            onclick="toggleInputManual()"
                            class="btn btn-warning w-100">
                            <i class="mdi mdi-pencil"></i> Input Manual
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="latitude">Latitude <span class="text-danger">*</span></label>
                    <input type="number"
                           id="latitude"
                           name="latitude"
                           class="form-control @error('latitude') is-invalid @enderror"
                           value="{{ $toko->latitude }}"
                           step="any"
                           required>
                    @error('latitude')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="longitude">Longitude <span class="text-danger">*</span></label>
                    <input type="number"
                           id="longitude"
                           name="longitude"
                           class="form-control @error('longitude') is-invalid @enderror"
                           value="{{ $toko->longitude }}"
                           step="any"
                           required>
                    @error('longitude')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="accuracy">Accuracy (meter) <span class="text-danger">*</span></label>
                    <input type="number"
                           id="accuracy"
                           name="accuracy"
                           class="form-control @error('accuracy') is-invalid @enderror"
                           value="{{ $toko->accuracy }}"
                           step="any"
                           required>
                    @error('accuracy')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="mdi mdi-check"></i> Update
                </button>

                <a href="{{ route('lokasi-toko.index') }}" class="btn btn-secondary">Batal</a>

            </form>

        </div>

    </div>

</div>

<script>

function getAccuratePosition(
    targetAccuracy = 50,
    maxWait = 20000
) {

    return new Promise((resolve, reject) => {

        if (!navigator.geolocation) {
            reject(new Error('Browser Anda tidak support Geolocation'));
            return;
        }

        let bestResult = null;

        const startTime = Date.now();

        const watchId =
        navigator.geolocation.watchPosition(

            (position) => {

                const acc =
                    position.coords.accuracy;

                if (
                    !bestResult ||
                    acc <
                    bestResult.coords.accuracy
                ) {
                    bestResult = position;
                }

                if (acc <= targetAccuracy) {

                    navigator.geolocation
                        .clearWatch(watchId);

                    resolve(bestResult);
                }

                if (
                    Date.now() - startTime
                    >= maxWait
                ) {

                    navigator.geolocation
                        .clearWatch(watchId);

                    if(bestResult)
                        resolve(bestResult);

                    else
                        reject(
                            new Error(
                                'Gagal mengambil lokasi. Pastikan GPS aktif dan izin lokasi diberikan.'
                            )
                        );
                }
            },

            (error) => {
                let errorMsg = 'Error: ';
                
                if (error.code === error.PERMISSION_DENIED) {
                    errorMsg = 'Izin lokasi ditolak. Aktifkan di pengaturan browser.';
                } else if (error.code === error.POSITION_UNAVAILABLE) {
                    errorMsg = 'Lokasi tidak tersedia. Pastikan GPS aktif.';
                } else if (error.code === error.TIMEOUT) {
                    errorMsg = 'Timeout mencari lokasi. Coba lagi.';
                } else {
                    errorMsg += error.message;
                }
                
                reject(new Error(errorMsg));
            },

            {
                enableHighAccuracy: true,
                maximumAge: 0,
                timeout: maxWait
            }
        );
    });
}

async function ambilLokasi()
{
    const btn = document.getElementById('btn-ambil-lokasi');
    btn.disabled = true;
    btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Mengambil lokasi...';

    try {

        const pos =
            await getAccuratePosition(50);

        document
            .getElementById('latitude')
            .value =
            pos.coords.latitude;

        document
            .getElementById('longitude')
            .value =
            pos.coords.longitude;

        document
            .getElementById('accuracy')
            .value =
            Math.round(pos.coords.accuracy);

        alert(
            'Lokasi berhasil diupdate\n' +
            'Accuracy: ' + Math.round(pos.coords.accuracy) + ' meter'
        );

    } catch(error) {

        alert('Error: ' + error.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="mdi mdi-crosshairs-gps"></i> Ambil Lokasi Otomatis';
    }
}

function toggleInputManual()
{
    alert(
        'Silahkan isi secara manual:\n' +
        '- Latitude\n' +
        '- Longitude\n' +
        '- Accuracy (dalam meter)\n\n' +
        'Anda bisa mendapatkan koordinat dari Google Maps'
    );
    
    // Fokus ke field latitude
    document.getElementById('latitude').focus();
}

</script>

@endsection
