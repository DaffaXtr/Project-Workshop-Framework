@extends('layouts.app')

@section('title', 'Tambah Customer')

@section('content')
<div class="container">
    <h2>Tambah Customer Baru</h2>
</div>

<div class="container">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                <form action="{{ route('camera.blob.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- ================= FORM ================= -->
                    <div class="form-group mb-3">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" name="provinsi" class="form-control mb-2" placeholder="Provinsi" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="kota" class="form-control mb-2" placeholder="Kota" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" name="kecamatan" class="form-control mb-2" placeholder="Kecamatan" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="kelurahan" class="form-control mb-2" placeholder="Kelurahan" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <input type="text" name="kodepos" class="form-control" placeholder="Kode Pos" required>
                    </div>

                    <!-- ================= FOTO ================= -->
                    <div class="mb-3">
                        <label>Foto</label>

                        <div style="display:flex; align-items:center; gap:20px;">
                            
                            <!-- Preview -->
                            <div style="
                                width:150px;
                                height:150px;
                                border:2px solid #8bc34a;
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                background:#f9f9f9;
                            ">
                                <span id="previewText">Foto</span>
                                <img id="preview" style="width:100%; height:100%; object-fit:cover; display:none;">
                            </div>

                            <div>
                                <button type="button" class="btn btn-primary" onclick="openCamera()">
                                    Ambil Foto
                                </button>
                            </div>

                        </div>

                        <input type="file" name="foto_path" id="fotoInput" hidden>
                    </div>

                    <!-- ================= SUBMIT ================= -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">Simpan Data</button>
                        <a href="{{ route('camera.blob.index') }}" class="btn btn-secondary">Batal</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL ================= -->
<div class="modal fade" id="cameraModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Ambil Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div style="display:flex; gap:20px; justify-content:center; align-items:center;">
                    
                    <!-- VIDEO -->
                    <div style="width:260px;">
                        <p class="text-center">Video</p>
                        <video id="video" autoplay style="
                            width:100%;
                            height:200px;
                            object-fit:cover;
                            border:2px solid #8bc34a;
                        "></video>
                    </div>

                    <!-- SNAPSHOT -->
                    <div style="width:260px;">
                        <p class="text-center">Snapshot</p>
                        <canvas id="canvas" style="
                            width:100%;
                            height:200px;
                            border:2px solid #8bc34a;
                        "></canvas>
                    </div>

                </div>

                <div class="text-center mt-3">
                    <button type="button" class="btn btn-secondary" onclick="startCamera()">
                        Pilih Kamera
                    </button>

                    <button type="button" class="btn btn-primary" onclick="capture()">
                        Ambil Foto
                    </button>

                    <button type="button" class="btn btn-success" onclick="savePhoto()">
                        Simpan Foto
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
let video = document.getElementById('video');
let canvas = document.getElementById('canvas');
let ctx = canvas.getContext('2d');
let capturedBlob = null;

// buka modal
function openCamera() {
    let modal = new bootstrap.Modal(document.getElementById('cameraModal'));
    modal.show();
}

// aktifkan kamera
function startCamera() {
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
        })
        .catch(err => alert("Tidak bisa akses kamera: " + err));
}

// ambil foto ke canvas
function capture() {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    ctx.drawImage(video, 0, 0);

    canvas.toBlob(function(blob) {
        capturedBlob = blob;
    }, 'image/png');
}

// simpan ke form + preview
function savePhoto() {
    if (!capturedBlob) {
        alert("Ambil foto dulu!");
        return;
    }

    let file = new File([capturedBlob], "foto.png", { type: "image/png" });

    let container = new DataTransfer();
    container.items.add(file);

    document.getElementById('fotoInput').files = container.files;

    // preview utama
    let preview = document.getElementById('preview');
    let text = document.getElementById('previewText');

    preview.src = URL.createObjectURL(capturedBlob);
    preview.style.display = 'block';
    text.style.display = 'none';

    // tutup modal
    bootstrap.Modal.getInstance(document.getElementById('cameraModal')).hide();

    // stop kamera
    video.srcObject.getTracks().forEach(track => track.stop());
}
</script>
@endpush