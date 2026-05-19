@extends('layouts.app')

@section('title', 'Scan QR Pesanan')

@section('content')

<div class="container">
    <h2 class="mb-3">Scan QR Pesanan</h2>
</div>

<div class="container">

    <!-- 🔼 KAMERA -->
    <div class="card mb-4">
        <div class="card-body text-center">
            <h5>Kamera</h5>

            <div id="reader" style="width:320px; margin:auto;"></div>

            <button onclick="startScan()" class="btn btn-primary btn-sm mt-3">
                Scan Lagi
            </button>
        </div>
    </div>

    <!-- 🔽 TABEL HASIL -->
    <div class="card">
        <div class="card-body">

            <h5>Detail Pesanan</h5>

            <table class="table table-bordered mt-3">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Menu</th>
                        <th>Vendor</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                        <th>Waktu</th>
                        <th>Catatan</th>
                        <th>Status Bayar</th>
                    </tr>
                </thead>
                <tbody id="resultTable">
                    <tr>
                        <td colspan="9" class="text-center">Belum ada data</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

</div>

<audio id="beepSound" src="{{ asset('sounds/beep.mp3') }}"></audio>

@endsection


@push('script-page')

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
let html5QrCode;

function startScan() {

    document.getElementById('resultTable').innerHTML =
        `<tr><td colspan="9" class="text-center">Scanning...</td></tr>`;

    html5QrCode = new Html5Qrcode("reader");

    html5QrCode.start(
        { facingMode: "user" },
        { fps: 10, qrbox: 200 },
        function(decodedText) {

            html5QrCode.stop();
            document.getElementById('beepSound').play();

            fetch("{{ route('pesanan.getDetail') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    idpesanan: decodedText
                })
            })
            .then(res => res.json())
            .then(res => {

                if (!res.status) {
                    alert(res.message);
                    return;
                }

                let rows = "";

                res.data.forEach(item => {

                    let statusBadge = item.status_bayar == '1'
                        ? `<span class="badge bg-success">Lunas</span>`
                        : `<span class="badge bg-warning text-dark">Belum</span>`;

                    rows += `
                        <tr>
                            <td>${item.idpesanan}</td>
                            <td>${item.nama_menu}</td>
                            <td>${item.nama_vendor}</td>
                            <td>${item.jumlah}</td>
                            <td>Rp ${new Intl.NumberFormat('id-ID').format(item.harga)}</td>
                            <td>Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</td>
                            <td>${item.timestamp}</td>
                            <td>${item.catatan ?? '-'}</td>
                            <td>${statusBadge}</td>
                        </tr>
                    `;
                });

                document.getElementById('resultTable').innerHTML = rows;

            });

        }
    );
}

document.addEventListener("DOMContentLoaded", function () {
    startScan();
});
</script>

@endpush