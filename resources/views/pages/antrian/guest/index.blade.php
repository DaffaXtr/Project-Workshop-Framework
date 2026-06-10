@extends('layouts.login')

@section('title', 'Ambil Nomor Antrian')

@section('content')
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
            <div class="row flex-grow">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left p-5">
                        
                        <div class="brand-logo mb-4 text-center">
                            <img src="{{ asset('assets/images/logo.svg') }}" alt="logo">
                        </div>

                        {{-- FORM PENDAFTARAN --}}
                        <div id="formContainer">
                            <h4>Ambil Antrian</h4>
                            <h6 class="font-weight-light mb-4">Silakan isi data untuk mendapatkan nomor antrian.</h6>

                            <form id="formAntrian" class="pt-2">
                                @csrf
                                
                                <div class="form-group">
                                    <label class="font-weight-medium text-muted mb-1 small">Nama Lengkap</label>
                                    <input type="text"
                                           id="nama"
                                           name="nama"
                                           class="form-control form-control-lg"
                                           placeholder="Contoh: Budi Santoso"
                                           minlength="2"
                                           maxlength="100"
                                           autocomplete="name"
                                           required>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-medium text-muted mb-1 small">Tujuan Poli</label>
                                    <select id="poli" name="poli" class="form-control form-control-lg text-dark" style="height: calc(2.875rem + 2px);" required>
                                        <option value="" disabled selected>-- Pilih Poli Tujuan --</option>
                                        <option value="Poli Umum">Poli Umum</option>
                                        <option value="Poli Gigi">Poli Gigi</option>
                                        <option value="Poli Anak">Poli Anak</option>
                                        <option value="Poli Mata">Poli Mata</option>
                                        <option value="Poli THT">Poli THT</option>
                                    </select>
                                </div>

                                <div class="mt-4 d-grid gap-2">
                                    <button type="submit" id="btnSubmit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn btn-loader">
                                        AMBIL NOMOR ANTRIAN
                                    </button>
                                </div>

                                <div class="loading text-center mt-3 text-primary font-weight-medium small" id="loading" style="display: none;">
                                    <span>⏳ Sedang memproses antrian Anda...</span>
                                </div>
                            </form>
                        </div>

                        {{-- HASIL TICKET --}}
                        <div class="ticket text-center" id="ticketContainer" style="display: none;">

                            <h4 class="text-success font-weight-bold mb-2">Berhasil Diambil</h4>
                            <h6 class="font-weight-light text-muted">Simpan atau cetak nomor Anda.</h6>

                            <div class="nomor my-4" id="textNomor" style="font-size: 72px; font-weight: 800; color: #b66dff; line-height: 1; letter-spacing: -2px;">000</div>

                            <div class="info mb-4">
                                <h4 id="textNama" class="font-weight-bold text-dark mb-1">Nama Anda</h4>
                                <h6 id="textPoli" class="font-weight-light text-muted">Poli</h6>
                            </div>

                            <div class="mt-3 d-grid gap-2">
                                <button type="button" class="btn btn-block btn-gradient-success btn-lg font-weight-medium auth-form-btn" onclick="window.print()">
                                    <i class="mdi mdi-printer me-2"></i> CETAK ANTRIAN
                                </button>
                            </div>

                            <div class="mt-2 d-grid gap-2">
                                <button type="button" class="btn btn-block btn-info btn-lg font-weight-medium auth-form-btn text-white" onclick="resetForm()">
                                    <i class="mdi mdi-plus me-2"></i> ANTRIAN BARU
                                </button>
                            </div>

                            <div class="mt-2 d-grid gap-2">
                                <a href="{{ route('papan') }}" class="btn btn-block btn-light auth-form-btn font-weight-medium">
                                    <i class="mdi mdi-television me-2"></i> LIHAT PAPAN ANTRIAN
                                </a>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Media CSS khusus layout cetak tiket --}}
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #ticketContainer, #ticketContainer * {
            visibility: visible;
        }
        #ticketContainer {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 20px;
            text-align: center;
        }
        #ticketContainer .btn, #ticketContainer a {
            display: none !important;
        }
        #textNomor {
            font-size: 90px !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const formAntrian = document.getElementById('formAntrian');
        const formContainer = document.getElementById('formContainer');
        const ticketContainer = document.getElementById('ticketContainer');
        
        if (formAntrian) {
            formAntrian.addEventListener('submit', function (e) {
                // 1. Mencegah reload halaman
                e.preventDefault();

                const btnSubmit = document.getElementById('btnSubmit');
                const loading = document.getElementById('loading');
                
                // 2. Aktifkan mode loading
                btnSubmit.disabled = true;
                btnSubmit.style.display = 'none'; 
                loading.style.display = 'block';
                
                // 3. Kumpulkan data form
                const formData = new FormData(formAntrian);
                
                // 4. Kirim via AJAX ke controller
                fetch("{{ route('guest.store') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // 5. Masukkan data dari server ke dalam tiket
                        document.getElementById('textNomor').textContent = data.nomor;
                        document.getElementById('textNama').textContent = data.nama;
                        document.getElementById('textPoli').textContent = data.poli;

                        // 6. Sembunyikan form, tampilkan tiket
                        formContainer.style.display = 'none';
                        ticketContainer.style.display = 'block';

                        // 7. Scroll ke tiket
                        setTimeout(() => {
                            ticketContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 300);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                })
                .finally(() => {
                    // Reset state tombol
                    btnSubmit.disabled = false;
                    btnSubmit.style.display = 'block'; 
                    loading.style.display = 'none';
                });
            });
        }
    });

    // Fungsi untuk reset form dan tampilkan form kembali
    function resetForm() {
        document.getElementById('formAntrian').reset();
        document.getElementById('formContainer').style.display = 'block';
        document.getElementById('ticketContainer').style.display = 'none';
        
        // Scroll ke atas form
        document.getElementById('formContainer').scrollIntoView({ behavior: 'smooth' });
    }
</script>
@endsection