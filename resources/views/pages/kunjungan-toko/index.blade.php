@extends('layouts.app')

@section('title', 'Kunjungan Toko')

@section('content')

<div class="container">

    <h3 class="mb-4">Kunjungan Toko</h3>

    <!-- SECTION 1: Barcode Scanner & Titik Kunjungan -->
    <div class="row mb-4">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="mdi mdi-qrcode-scan"></i> Titik Kunjungan
                </div>
                <div class="card-body">

                    <!-- Barcode Scanner -->
                    <div class="mb-4">
                        <h5>Barcode Scanner</h5>
                        <div id="qr-reader" style="max-width: 400px; margin: 0 auto;"></div>
                        <input type="hidden" id="barcode" name="barcode">
                    </div>

                    <!-- Data dari DB hasil scan -->
                    <div id="barcode-result" class="d-none">
                        <div class="alert alert-info">
                            <strong>Barcode terdeteksi:</strong> <span id="barcode-value"></span>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Nama Toko (dari DB)</label>
                                <input type="text" id="toko-nama-db" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label>Barcode Toko</label>
                                <input type="text" id="toko-barcode-db" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label>Latitude (Toko)</label>
                                <input type="text" id="toko-latitude" class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Longitude (Toko)</label>
                                <input type="text" id="toko-longitude" class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Accuracy (Toko)</label>
                                <input type="text" id="toko-accuracy" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- SECTION 2: Input Lokasi Sales -->
    <div class="row mb-4">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <i class="mdi mdi-map-marker"></i> Lokasi Sales
                </div>
                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-12">
                            <button 
                                type="button"
                                id="btn-ambil-lokasi"
                                onclick="ambilLokasi()"
                                class="btn btn-success w-100">
                                <i class="mdi mdi-crosshairs-gps"></i> Ambil Lokasi
                            </button>
                            <div id="lokasi-status" class="d-none mt-3"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>Latitude (Sales)</label>
                            <input type="number" id="latitude_sales" class="form-control" readonly step="any">
                        </div>
                        <div class="col-md-4">
                            <label>Longitude (Sales)</label>
                            <input type="number" id="longitude_sales" class="form-control" readonly step="any">
                        </div>
                        <div class="col-md-4">
                            <label>Accuracy (Sales)</label>
                            <input type="number" id="accuracy_sales" class="form-control" step="any">
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- SECTION 3: Action Buttons -->
    <div class="row mb-4">

        <div class="col-lg-12">
            <div class="d-flex gap-2 justify-content-center">
                <button 
                    type="button"
                    onclick="submitValidasi()"
                    class="btn btn-primary btn-lg"
                    id="btn-submit">
                    <i class="mdi mdi-check-circle"></i> Submit Validasi
                </button>
                <button 
                    type="button"
                    onclick="resetForm()"
                    class="btn btn-secondary btn-lg">
                    <i class="mdi mdi-refresh"></i> Reset
                </button>
            </div>
        </div>

    </div>

    <!-- SECTION 4: Hasil Validasi -->
    <div class="row">

        <div class="col-lg-12">
            <div id="hasil-card" class="d-none">

                <div class="card">
                    <div class="card-header">
                        <i class="mdi mdi-information"></i> Hasil Validasi Kunjungan
                    </div>
                    <div class="card-body">

                        <div class="alert" id="status-alert"></div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label><strong>Nama Toko</strong></label>
                                <input type="text" id="hasil-toko-nama" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Status Kunjungan</strong></label>
                                <input type="text" id="hasil-status" class="form-control font-weight-bold" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Jarak Aktual</label>
                                <input type="text" id="hasil-jarak" class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Threshold + Accuracy</label>
                                <input type="text" id="hasil-accuracy-total" class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Threshold Maksimal</label>
                                <input type="text" id="hasil-threshold" class="form-control" readonly>
                            </div>
                        </div>

                        <hr>

                        <h6>Detail Perhitungan:</h6>
                        <ul id="detail-perhitungan" class="list-unstyled"></ul>

                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<!-- QR Scanner Library -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>

const RADIUS_THRESHOLD = 100; // meter - sesuaikan sesuai kebutuhan

// ==================== QR SCANNER ====================

let html5QrcodeScanner = null;

function onScanSuccess(decodedText, decodedResult) {
    document.getElementById('barcode').value = decodedText;
    document.getElementById('barcode-value').textContent = decodedText;

    // Fetch data toko dari DB
    fetchTokoData(decodedText);

    if (html5QrcodeScanner) {
        html5QrcodeScanner.pause();
    }
}

function onScanError(error) {
    console.log('QR Scan Error:', error);
}

window.addEventListener('DOMContentLoaded', function() {
    html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader",
        { fps: 10, qrbox: 200 },
        false
    );

    html5QrcodeScanner.render(onScanSuccess, onScanError);
});

// ==================== FETCH DATA TOKO ====================

async function fetchTokoData(barcode) {
    try {
        const response = await fetch(`/kunjungan-toko/get-toko/${barcode}`);
        const data = await response.json();

        if (!data.success) {
            alert(data.message);
            resetForm();
            return;
        }

        // Tampilkan data toko
        const toko = data.data;
        document.getElementById('toko-nama-db').value = toko.nama_toko;
        document.getElementById('toko-barcode-db').value = toko.barcode;
        document.getElementById('toko-latitude').value = toko.latitude;
        document.getElementById('toko-longitude').value = toko.longitude;
        document.getElementById('toko-accuracy').value = Math.round(toko.accuracy) + ' m';

        document.getElementById('barcode-result').classList.remove('d-none');

    } catch(error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    }
}

// ==================== GEOLOCATION ====================

function showLocationStatus(message, type = 'info') {
    const statusDiv = document.getElementById('lokasi-status');
    statusDiv.className = 'alert alert-' + type + ' d-block mb-0';
    statusDiv.textContent = message;
}

function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
    return new Promise((resolve, reject) => {

        if (!navigator.geolocation) {
            reject(new Error('Browser Anda tidak support Geolocation'));
            return;
        }

        let bestResult = null;
        const startTime = Date.now();

        showLocationStatus('Mengambil lokasi... pastikan GPS aktif', 'info');

        const watchId = navigator.geolocation.watchPosition(

            (position) => {
                const acc = position.coords.accuracy;

                if (!bestResult || acc < bestResult.coords.accuracy) {
                    bestResult = position;
                }

                if (acc <= targetAccuracy) {
                    navigator.geolocation.clearWatch(watchId);
                    resolve(bestResult);
                }

                if (Date.now() - startTime >= maxWait) {
                    navigator.geolocation.clearWatch(watchId);
                    
                    if (bestResult) {
                        resolve(bestResult);
                    } else {
                        reject(new Error('Timeout - tidak bisa mengambil lokasi'));
                    }
                }
            },

            (error) => {
                let errorMsg = 'Error: ';
                
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMsg += 'Izin lokasi ditolak. Aktifkan di pengaturan browser.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMsg += 'Lokasi tidak tersedia. Pastikan GPS aktif.';
                        break;
                    case error.TIMEOUT:
                        errorMsg += 'Timeout mencari lokasi. Coba lagi.';
                        break;
                    default:
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

async function ambilLokasi() {
    const btn = document.getElementById('btn-ambil-lokasi');
    btn.disabled = true;
    btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Mengambil lokasi...';

    try {
        const pos = await getAccuratePosition(50);

        document.getElementById('latitude_sales').value = pos.coords.latitude;
        document.getElementById('longitude_sales').value = pos.coords.longitude;
        document.getElementById('accuracy_sales').value = Math.round(pos.coords.accuracy);

        showLocationStatus('✓ Lokasi berhasil diambil (Accuracy: ' + Math.round(pos.coords.accuracy) + 'm)', 'success');

    } catch(error) {
        showLocationStatus('✗ ' + error.message, 'danger');
        console.error('Error:', error);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="mdi mdi-crosshairs-gps"></i> Ambil Lokasi';
    }
}

// ==================== VALIDASI & SUBMIT ====================

async function submitValidasi() {
    const barcode = document.getElementById('barcode').value;
    const latitude_sales = document.getElementById('latitude_sales').value;
    const longitude_sales = document.getElementById('longitude_sales').value;
    const accuracy_sales = document.getElementById('accuracy_sales').value;

    if (!barcode) {
        alert('Scan QR Code terlebih dahulu');
        return;
    }

    if (!latitude_sales || !longitude_sales || !accuracy_sales) {
        alert('Ambil Lokasi terlebih dahulu');
        return;
    }

    const btn = document.getElementById('btn-submit');
    btn.disabled = true;
    btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Validasi...';

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        const response = await fetch('/kunjungan-toko/cek', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                barcode: barcode,
                latitude_sales: parseFloat(latitude_sales),
                longitude_sales: parseFloat(longitude_sales),
                accuracy_sales: parseFloat(accuracy_sales)
            })
        });

        const data = await response.json();

        if (!data.success) {
            alert(data.message);
            return;
        }

        // Tampilkan hasil validasi
        const hasilCard = document.getElementById('hasil-card');
        const statusAlert = document.getElementById('status-alert');
        
        hasilCard.classList.remove('d-none');

        document.getElementById('hasil-toko-nama').value = data.toko.nama_toko;
        document.getElementById('hasil-status').value = data.status.toUpperCase();
        document.getElementById('hasil-jarak').value = data.jarak + ' m';
        document.getElementById('hasil-accuracy-total').value = data.accuracy_total + ' m';
        document.getElementById('hasil-threshold').value = RADIUS_THRESHOLD + ' m';

        // Status alert
        if (data.status === 'diterima') {
            statusAlert.className = 'alert alert-success';
            statusAlert.innerHTML = '<i class="mdi mdi-check-circle"></i> <strong>' + data.message + '</strong>';
        } else {
            statusAlert.className = 'alert alert-danger';
            statusAlert.innerHTML = '<i class="mdi mdi-close-circle"></i> <strong>' + data.message + '</strong>';
        }

        // Detail perhitungan
        const tokoAcc = Math.round(parseFloat(document.getElementById('toko-accuracy').value));
        const detail = document.getElementById('detail-perhitungan');
        detail.innerHTML = `
            <li>Jarak Aktual: <strong>${data.jarak} m</strong></li>
            <li>Accuracy Toko: <strong>${tokoAcc} m</strong></li>
            <li>Accuracy Sales: <strong>${Math.round(accuracy_sales)} m</strong></li>
            <li>Threshold Maksimal: <strong>${RADIUS_THRESHOLD} m</strong></li>
            <li class="mt-2">
                <strong>Threshold Efektif = ${RADIUS_THRESHOLD} + ${tokoAcc} + ${Math.round(accuracy_sales)} = ${data.accuracy_total} m</strong>
            </li>
            <li class="mt-2">
                ${data.jarak} m ≤ ${data.accuracy_total} m → <strong>${data.status.toUpperCase()}</strong>
            </li>
        `;

        // Scroll ke hasil
        setTimeout(() => {
            hasilCard.scrollIntoView({ behavior: 'smooth' });
        }, 300);

    } catch(error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="mdi mdi-check-circle"></i> Submit Validasi';
    }
}

// ==================== RESET FORM ====================

function resetForm() {
    document.getElementById('barcode').value = '';
    document.getElementById('latitude_sales').value = '';
    document.getElementById('longitude_sales').value = '';
    document.getElementById('accuracy_sales').value = '';
    
    document.getElementById('barcode-result').classList.add('d-none');
    document.getElementById('lokasi-status').classList.add('d-none');
    document.getElementById('hasil-card').classList.add('d-none');

    if (html5QrcodeScanner) {
        html5QrcodeScanner.resume();
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

</script>

@endsection
