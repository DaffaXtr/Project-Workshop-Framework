@extends('layouts.app')

@section('title', 'Scan Barcode')

@section('content')

<div class="container">
    <h2 class="mb-3">Scan Barcode Barang</h2>
</div>

<div class="container">

    <div class="row">

        <!-- KAMERA -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Kamera</h5>

                    <div id="reader" style="width:100%;"></div>

                    <button onclick="startScan()" class="btn btn-primary btn-sm mt-3">
                        Scan Lagi
                    </button>
                </div>
            </div>
        </div>

        <!-- HASIL -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">

                    <h5>Hasil Scan</h5>

                    <table class="table table-bordered mt-3">
                        <thead class="table-light">
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="resultRow">
                                <td id="id_barang">-</td>
                                <td id="nama">-</td>
                                <td id="harga">-</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </div>

</div>

<!-- AUDIO -->
<audio id="beepSound" src="{{ asset('sounds/beep.mp3') }}"></audio>

@endsection


@push('script-page')

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    let html5QrCode;

    function startScan() {

        // reset tampilan
        document.getElementById('id_barang').innerText = '-';
        document.getElementById('nama').innerText = '-';
        document.getElementById('harga').innerText = '-';

        html5QrCode = new Html5Qrcode("reader");

        html5QrCode.start(
            { facingMode: "user" }, // kamera depan laptop
            {
                fps: 10,
                qrbox: { width: 250, height: 150 },
                formatsToSupport: [
                    Html5QrcodeSupportedFormats.CODE_128,
                    Html5QrcodeSupportedFormats.EAN_13,
                    Html5QrcodeSupportedFormats.EAN_8
                ]
            },
            function(decodedText) {

                console.log("SCAN:", decodedText);

                // stop scanner
                html5QrCode.stop();

                // bunyi beep
                document.getElementById('beepSound').play();

                // fetch ke backend
                fetch("{{ route('barang.getByBarcode') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        barcode: decodedText
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status) {

                        document.getElementById('id_barang').innerText = res.data.id_barang;
                        document.getElementById('nama').innerText = res.data.nama;

                        // format harga
                        document.getElementById('harga').innerText =
                            'Rp ' + new Intl.NumberFormat('id-ID').format(res.data.harga);

                        // highlight
                        let row = document.getElementById('resultRow');
                        row.classList.add('table-success');

                        setTimeout(() => {
                            row.classList.remove('table-success');
                        }, 1000);

                    } else {
                        alert(res.message);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Terjadi error");
                });

            },
            function(error) {
                // ignore scan error
            }
        ).catch(err => {
            console.error("Camera error:", err);
            alert("Tidak bisa akses kamera: " + err);
        });
    }

    // auto start
    document.addEventListener("DOMContentLoaded", function () {
        startScan();
    });

</script>

@endpush