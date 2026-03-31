@extends('layouts.app')

@section('title', 'Sistem Kasir - Axios')

@push('style-page')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .kasir-section {
            margin-bottom: 2rem;
        }

        .form-input-group {
            display: flex;
            gap: 0.5rem;
            align-items: flex-end;
        }

        .form-input-group .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .form-input-group .btn {
            margin-bottom: 0;
        }

        .input-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #495057;
            margin-bottom: 0.5rem;
            display: block;
        }

        .barang-info {
            background-color: #e8f5e9;
            padding: 1rem;
            border-radius: 0.375rem;
            margin-top: 1rem;
            border-left: 4px solid #198754;
        }

        .barang-info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #c8e6c9;
        }

        .barang-info-row:last-child {
            border-bottom: none;
        }

        .barang-info-label {
            font-weight: 600;
            color: #1b5e20;
        }

        .barang-info-value {
            color: #198754;
            font-weight: 600;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .status-loading {
            background-color: #fff3cd;
            color: #997404;
        }

        .status-success {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .status-error {
            background-color: #f8d7da;
            color: #842029;
        }

        .spinner-sm {
            width: 0.875rem;
            height: 0.875rem;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #198754;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .btn-group-action {
            display: flex;
            gap: 0.5rem;
        }

        .btn-group-action .btn {
            flex: 1;
        }

        .table-container {
            overflow-x: auto;
        }

        .table thead th {
            background-color: #e9ecef;
            font-weight: 600;
            text-align: center;
        }

        .table tbody td {
            vertical-align: middle;
            text-align: center;
        }

        .table tbody td:first-child {
            text-align: left;
        }

        .table tbody td .form-control {
            width: 100%;
        }

        .qty-control {
            display: flex;
            gap: 0.25rem;
        }

        .qty-control .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.85rem;
        }

        .qty-input {
            width: 80px;
            text-align: center;
        }

        .total-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 0.375rem;
            margin-top: 2rem;
            text-align: right;
        }

        .total-label {
            font-size: 1rem;
            font-weight: 600;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }

        .total-value {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .empty-cart {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }

        .empty-cart i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .no-data-row {
            text-align: center;
            padding: 2rem !important;
            color: #6c757d;
        }

        .badge-info {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background-color: #e3f2fd;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            font-size: 0.85rem;
            border-left: 3px solid #198754;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <h2>Sistem Kasir</h2>
        <p class="text-muted">Axios Version - Input Kode Barang dan Tambahkan ke Keranjang</p>
    </div>

    <div class="container">
        <div class="row">
            {{-- FORM KASIR --}}
            <div class="col-lg-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Data Barang</h4>

                        {{-- INPUT KODE BARANG --}}
                        <div class="kasir-section">
                            <label class="input-label">Kode Barang <span class="text-danger">*</span></label>
                            <div class="form-input-group">
                                <div class="form-group flex-1">
                                    <input 
                                        type="text" 
                                        id="inputKode" 
                                        class="form-control" 
                                        placeholder="Masukkan kode barang dan tekan Enter"
                                        autofocus
                                    >
                                </div>
                                <span id="statusBadge" class="status-badge" style="display: none;"></span>
                            </div>
                        </div>

                        {{-- BARANG INFO --}}
                        <div id="barangInfo" class="barang-info" style="display: none;">
                            <div class="barang-info-row">
                                <span class="barang-info-label">Nama Barang:</span>
                                <span class="barang-info-value" id="displayNama">-</span>
                            </div>
                            <div class="barang-info-row">
                                <span class="barang-info-label">Harga:</span>
                                <span class="barang-info-value" id="displayHarga">-</span>
                            </div>
                        </div>

                        {{-- INPUT JUMLAH --}}
                        <div class="kasir-section" style="display: none;" id="sectionJumlah">
                            <label class="input-label">Jumlah <span class="text-danger">*</span></label>
                            <div class="form-input-group">
                                <div class="form-group flex-1">
                                    <input 
                                        type="number" 
                                        id="inputJumlah" 
                                        class="form-control" 
                                        value="1"
                                        min="1"
                                    >
                                </div>
                            </div>
                        </div>

                        {{-- BUTTON TAMBAHKAN --}}
                        <button 
                            type="button" 
                            id="btnTambahkan" 
                            class="btn btn-success"
                            style="display: none;"
                            onclick="tambahKeKeranjang()"
                        >
                            <i class="mdi mdi-plus"></i> Tambahkan
                        </button>
                    </div>
                </div>
            </div>

            {{-- RINGKASAN KERANJANG --}}
            <div class="col-lg-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Ringkasan</h4>

                        <div id="ringkasan" class="empty-cart">
                            <i class="mdi mdi-cart-outline"></i>
                            <p>Keranjang masih kosong</p>
                        </div>

                        <div id="ringkasanData" style="display: none;">
                            <div class="barang-info">
                                <div class="barang-info-row">
                                    <span class="barang-info-label">Total Items:</span>
                                    <span class="barang-info-value" id="totalItems">0</span>
                                </div>
                                <div class="barang-info-row">
                                    <span class="barang-info-label">Total Harga:</span>
                                    <span class="barang-info-value" id="totalHarga">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABEL KERANJANG --}}
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Keranjang Belanja</h4>

                        <div class="table-container">
                            <table class="table" id="tableKeranjang">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyKeranjang">
                                    <tr class="no-data-row">
                                        <td colspan="6">Belum ada barang di keranjang</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- TOTAL SECTION --}}
                        <div class="total-section" id="totalSection" style="display: none;">
                            <div class="total-label">Total Pembayaran</div>
                            <div class="total-value" id="nilaiTotal">Rp 0</div>

                            <button 
                                type="button" 
                                id="btnBayar" 
                                class="btn btn-light mt-3"
                                onclick="prosesPembayaran()"
                            >
                                <i class="mdi mdi-credit-card"></i> Bayar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script-page')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        let keranjang = [];
        let barangAktif = null;

        $(document).ready(function () {
            // ================= EVENT HANDLERS =================
            // Enter pada input kode
            $('#inputKode').on('keypress', function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    cariBarang();
                }
            });

            // Change pada input jumlah
            $('#inputJumlah').on('change', function () {
                let jumlah = parseInt($(this).val()) || 0;
                if (jumlah > 0) {
                    $('#btnTambahkan').prop('disabled', false).removeClass('disabled');
                } else {
                    $('#btnTambahkan').prop('disabled', true).addClass('disabled');
                }
            });
        });

        // ================= FUNCTION CARI BARANG DENGAN AXIOS =================
        function cariBarang() {
            let kode = $('#inputKode').val().trim();

            if (!kode) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Masukkan kode barang terlebih dahulu!'
                });
                return;
            }

            // Show loading
            showStatusBadge('loading');

            axios.get('{{ route('kasir.get-barang') }}', {
                params: { kode: kode }
            })
            .then(response => {
                if (response.data.success) {
                    barangAktif = response.data.data;

                    // Display barang info
                    $('#displayNama').text(response.data.data.nama);
                    $('#displayHarga').text(formatRupiah(response.data.data.harga));
                    $('#barangInfo').slideDown();

                    // Show jumlah section
                    $('#sectionJumlah').slideDown();
                    $('#inputJumlah').val(1).focus();

                    // Enable button
                    $('#btnTambahkan').slideDown().prop('disabled', false).removeClass('disabled');

                    showStatusBadge('success');
                } else {
                    showStatusBadge('error');
                    Swal.fire({
                        icon: 'error',
                        title: 'Barang Tidak Ditemukan',
                        text: response.data.message
                    });
                    barangAktif = null;
                    resetForm();
                }
            })
            .catch(error => {
                showStatusBadge('error');
                let errorMsg = error.response?.data?.message || 'Terjadi kesalahan saat mencari barang!';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg
                });
                resetForm();
            });
        }

        // ================= FUNCTION TAMBAH KE KERANJANG DENGAN AXIOS =================
        function tambahKeKeranjang() {
            if (!barangAktif) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Barang belum dipilih!'
                });
                return;
            }

            let jumlah = parseInt($('#inputJumlah').val()) || 0;

            if (jumlah <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Jumlah harus lebih dari 0!'
                });
                return;
            }

            // Button loading
            let btn = $('#btnTambahkan');
            if (btn.hasClass('loading')) return;

            let originalText = btn.html();
            btn.addClass('loading').prop('disabled', true);
            btn.html(`<span class="spinner-border spinner-border-sm me-2"></span>Loading...`);

            setTimeout(() => {
                // Check if item already exists
                let existingItem = keranjang.find(item => item.id_barang === barangAktif.id_barang);

                if (existingItem) {
                    // Update quantity dan subtotal
                    existingItem.jumlah += jumlah;
                    existingItem.subtotal = existingItem.jumlah * existingItem.harga;
                } else {
                    // Add new item
                    keranjang.push({
                        id_barang: barangAktif.id_barang,
                        nama: barangAktif.nama,
                        harga: barangAktif.harga,
                        jumlah: jumlah,
                        subtotal: jumlah * barangAktif.harga
                    });
                }

                // Update table dan total
                renderTable();
                updateTotal();

                // Reset form
                resetForm();

                // Reset button
                btn.removeClass('loading').prop('disabled', false);
                btn.html(originalText);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Barang berhasil ditambahkan ke keranjang!',
                    timer: 1500
                });
            }, 600);
        }

        // ================= FUNCTION RENDER TABLE =================
        function renderTable() {
            let tbody = $('#tbodyKeranjang');
            tbody.empty();

            if (keranjang.length === 0) {
                tbody.html('<tr class="no-data-row"><td colspan="6">Belum ada barang di keranjang</td></tr>');
                return;
            }

            keranjang.forEach((item, index) => {
                tbody.append(`
                    <tr>
                        <td>${item.id_barang}</td>
                        <td>${item.nama}</td>
                        <td>${formatRupiah(item.harga)}</td>
                        <td>
                            <input 
                                type="number" 
                                class="form-control qty-input" 
                                value="${item.jumlah}"
                                min="1"
                                onchange="updateItemQty(${index}, this.value)"
                            >
                        </td>
                        <td>${formatRupiah(item.subtotal)}</td>
                        <td>
                            <button 
                                type="button" 
                                class="btn btn-sm btn-danger"
                                onclick="hapusItem(${index})"
                            >
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });
        }

        // ================= FUNCTION UPDATE ITEM QTY =================
        function updateItemQty(index, newQty) {
            let qty = parseInt(newQty) || 0;

            if (qty <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Jumlah harus lebih dari 0!'
                });
                renderTable();
                return;
            }

            keranjang[index].jumlah = qty;
            keranjang[index].subtotal = qty * keranjang[index].harga;

            renderTable();
            updateTotal();
        }

        // ================= FUNCTION HAPUS ITEM =================
        function hapusItem(index) {
            Swal.fire({
                icon: 'question',
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menghapus item ini?',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    keranjang.splice(index, 1);
                    renderTable();
                    updateTotal();

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Item berhasil dihapus!',
                        timer: 1500
                    });
                }
            });
        }

        // ================= FUNCTION UPDATE TOTAL =================
        function updateTotal() {
            let totalItems = keranjang.length;
            let totalHarga = 0;

            keranjang.forEach(item => {
                totalHarga += item.subtotal;
            });

            if (keranjang.length === 0) {
                $('#ringkasan').show();
                $('#ringkasanData').hide();
                $('#totalSection').hide();
            } else {
                $('#ringkasan').hide();
                $('#ringkasanData').show();
                $('#totalSection').show();

                $('#totalItems').text(totalItems);
                $('#totalHarga').text(formatRupiah(totalHarga));
                $('#nilaiTotal').text(formatRupiah(totalHarga));
            }
        }

        // ================= FUNCTION PROSES PEMBAYARAN DENGAN AXIOS =================
        function prosesPembayaran() {
            if (keranjang.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Keranjang masih kosong!'
                });
                return;
            }

            let btn = $('#btnBayar');
            if (btn.hasClass('loading')) return;

            let originalText = btn.html();
            btn.addClass('loading').prop('disabled', true);
            btn.html(`<span class="spinner-border spinner-border-sm me-2"></span>Memproses...`);

            axios.post('{{ route('kasir.save-penjualan') }}', {
                _token: '{{ csrf_token() }}',
                items: keranjang
            }, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (response.data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil',
                        html: `
                            <div style="text-align: left;">
                                <p><strong>ID Penjualan:</strong> ${response.data.data.id_penjualan}</p>
                                <p><strong>Total:</strong> ${formatRupiah(response.data.data.total)}</p>
                            </div>
                        `,
                        didClose: function () {
                            resetAllData();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.data.message
                    });
                }

                btn.removeClass('loading').prop('disabled', false);
                btn.html(originalText);
            })
            .catch(error => {
                let errorMsg = error.response?.data?.message || 'Terjadi kesalahan saat menyimpan data!';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg
                });

                btn.removeClass('loading').prop('disabled', false);
                btn.html(originalText);
            });
        }

        // ================= FUNCTION RESET FORM =================
        function resetForm() {
            $('#inputKode').val('').focus();
            $('#barangInfo').slideUp();
            $('#sectionJumlah').slideUp();
            $('#btnTambahkan').slideUp();
            $('#statusBadge').hide();
            barangAktif = null;
        }

        // ================= FUNCTION RESET ALL DATA =================
        function resetAllData() {
            keranjang = [];
            resetForm();
            renderTable();
            updateTotal();
        }

        // ================= FUNCTION FORMAT RUPIAH =================
        function formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        // ================= FUNCTION SHOW STATUS BADGE =================
        function showStatusBadge(status) {
            let badge = $('#statusBadge');
            badge.removeClass('status-loading status-success status-error').html('');

            if (status === 'loading') {
                badge.addClass('status-loading').html('<div class="spinner-sm"></div>Loading...').show();
            } else if (status === 'success') {
                badge.addClass('status-success').html('<i class="mdi mdi-check"></i> Ditemukan').show();
                setTimeout(() => badge.fadeOut(), 2000);
            } else if (status === 'error') {
                badge.addClass('status-error').html('<i class="mdi mdi-alert"></i> Tidak Ditemukan').show();
                setTimeout(() => badge.fadeOut(), 2000);
            }
        }
    </script>
@endpush
