@extends('layouts.app')

@section('title', 'Cascading Select - jQuery AJAX')

@push('style-page')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style>
        .cascade-card {
            margin-bottom: 2rem;
        }

        .cascade-result {
            padding: 1.5rem;
            background-color: #e7f3ff;
            border-left: 4px solid #0d6efd;
            border-radius: 0.375rem;
            margin-top: 2rem;
        }

        .cascade-result h5 {
            color: #0d6efd;
            margin-bottom: 1rem;
        }

        .result-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid #dee2e6;
        }

        .result-item:last-child {
            border-bottom: none;
        }

        .result-label {
            font-weight: 600;
            color: #495057;
            display: inline-block;
            width: 100px;
        }

        .result-value {
            color: #0d6efd;
            font-weight: 600;
        }

        .loading-indicator {
            display: none;
            color: #6c757d;
            font-size: 0.875rem;
        }

        .loading-indicator.show {
            display: inline;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <h2>Cascading Select Wilayah Indonesia</h2>
        <p class="text-muted">Versi jQuery AJAX - Pilih Provinsi, Kota, Kecamatan, dan Kelurahan</p>
    </div>

    <div class="container">
        <div class="row">
            {{-- FORM CASCADING SELECT --}}
            <div class="col-lg-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Form Pilihan Wilayah</h4>

                        <form id="formWilayah">
                            {{-- LEVEL 1: PROVINSI --}}
                            <div class="form-group mb-4 cascade-card">
                                <label class="form-label">Provinsi <span class="text-danger">*</span></label>
                                <select id="selectProvinsi" class="form-select select2-element" required>
                                    <option value="">-- Pilih Provinsi --</option>
                                </select>
                                <span class="loading-indicator" id="loadingKota">Loading...</span>
                            </div>

                            {{-- LEVEL 2: KOTA --}}
                            <div class="form-group mb-4 cascade-card">
                                <label class="form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                                <select id="selectKota" class="form-select select2-element" required disabled>
                                    <option value="">-- Pilih Kota --</option>
                                </select>
                                <span class="loading-indicator" id="loadingKecamatan">Loading...</span>
                            </div>

                            {{-- LEVEL 3: KECAMATAN --}}
                            <div class="form-group mb-4 cascade-card">
                                <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                                <select id="selectKecamatan" class="form-select select2-element" required disabled>
                                    <option value="">-- Pilih Kecamatan --</option>
                                </select>
                                <span class="loading-indicator" id="loadingKelurahan">Loading...</span>
                            </div>

                            {{-- LEVEL 4: KELURAHAN --}}
                            <div class="form-group mb-4 cascade-card">
                                <label class="form-label">Kelurahan <span class="text-danger">*</span></label>
                                <select id="selectKelurahan" class="form-select select2-element" required disabled>
                                    <option value="">-- Pilih Kelurahan --</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-check"></i> Simpan Pilihan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- RESULT DISPLAY --}}
            <div class="col-lg-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Hasil Pilihan</h4>

                        <div class="cascade-result">
                            <div class="result-item">
                                <span class="result-label">Provinsi:</span>
                                <span class="result-value" id="displayProvinsi">-</span>
                            </div>
                            <div class="result-item">
                                <span class="result-label">Kota:</span>
                                <span class="result-value" id="displayKota">-</span>
                            </div>
                            <div class="result-item">
                                <span class="result-label">Kecamatan:</span>
                                <span class="result-value" id="displayKecamatan">-</span>
                            </div>
                            <div class="result-item">
                                <span class="result-label">Kelurahan:</span>
                                <span class="result-value" id="displayKelurahan">-</span>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3" id="debugInfo" style="font-size: 0.875rem; display: none;">
                            <strong>Debug Info:</strong><br>
                            <small id="debugText"></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script-page')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        let wilayahData = {
            provinsi: [],
            kota: [],
            kecamatan: [],
            kelurahan: []
        };

        $(document).ready(function () {
            loadWilayahData();
            initSelect2();

            // ================= EVENT HANDLERS =================
            // Provinsi Change Event
            $('#selectProvinsi').on('select2:select', function (e) {
                let provinsiId = $(this).val();
                let provinsiName = e.params.data.text;

                // Update display
                $('#displayProvinsi').text(provinsiName);
                $('#displayKota').text('-');
                $('#displayKecamatan').text('-');
                $('#displayKelurahan').text('-');

                // Clear dan load kota
                clearSelect('selectKota');
                clearSelect('selectKecamatan');
                clearSelect('selectKelurahan');

                loadKota(provinsiId);
            });

            // Kota Change Event
            $('#selectKota').on('select2:select', function (e) {
                let kotaId = $(this).val();
                let kotaName = e.params.data.text;

                // Update display
                $('#displayKota').text(kotaName);
                $('#displayKecamatan').text('-');
                $('#displayKelurahan').text('-');

                // Clear dan load kecamatan
                clearSelect('selectKecamatan');
                clearSelect('selectKelurahan');

                loadKecamatan(kotaId);
            });

            // Kecamatan Change Event
            $('#selectKecamatan').on('select2:select', function (e) {
                let kecamatanId = $(this).val();
                let kecamatanName = e.params.data.text;

                // Update display
                $('#displayKecamatan').text(kecamatanName);
                $('#displayKelurahan').text('-');

                // Clear dan load kelurahan
                clearSelect('selectKelurahan');

                loadKelurahan(kecamatanId);
            });

            // Kelurahan Change Event
            $('#selectKelurahan').on('select2:select', function (e) {
                let kelurahanName = e.params.data.text;
                $('#displayKelurahan').text(kelurahanName);
            });

            // Form Submit
            $('#formWilayah').on('submit', function (e) {
                e.preventDefault();

                let provinsiVal = $('#selectProvinsi').val();
                let kotaVal = $('#selectKota').val();
                let kecamatanVal = $('#selectKecamatan').val();
                let kelurahanVal = $('#selectKelurahan').val();

                // Validasi
                if (!provinsiVal) {
                    Swal.fire('Peringatan', 'Pilih Provinsi terlebih dahulu!', 'warning');
                    return;
                }
                if (!kotaVal) {
                    Swal.fire('Peringatan', 'Pilih Kota/Kabupaten terlebih dahulu!', 'warning');
                    return;
                }
                if (!kecamatanVal) {
                    Swal.fire('Peringatan', 'Pilih Kecamatan terlebih dahulu!', 'warning');
                    return;
                }
                if (!kelurahanVal) {
                    Swal.fire('Peringatan', 'Pilih Kelurahan terlebih dahulu!', 'warning');
                    return;
                }

                let data = {
                    provinsi: $('#selectProvinsi').find('option:selected').text(),
                    kota: $('#selectKota').find('option:selected').text(),
                    kecamatan: $('#selectKecamatan').find('option:selected').text(),
                    kelurahan: $('#selectKelurahan').find('option:selected').text()
                };

                Swal.fire({
                    icon: 'success',
                    title: 'Data Tersimpan',
                    html: '<div style="text-align: left;"><p><strong>Provinsi:</strong> ' + data.provinsi + '</p>' +
                          '<p><strong>Kota:</strong> ' + data.kota + '</p>' +
                          '<p><strong>Kecamatan:</strong> ' + data.kecamatan + '</p>' +
                          '<p><strong>Kelurahan:</strong> ' + data.kelurahan + '</p></div>'
                });
            });
        });

        // FUNCTION LOAD DATA WILAYAH
        function loadWilayahData() {
            $.ajax({
                url: '{{ asset('json/wilayah.json') }}',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    wilayahData = data;

                    // Load provinsi
                    if (data.provinsi && data.provinsi.length > 0) {
                        let selectProvinsi = $('#selectProvinsi');
                        data.provinsi.forEach(function (prov) {
                            selectProvinsi.append(
                                $('<option></option>')
                                    .attr('value', prov.id)
                                    .text(prov.name)
                            );
                        });
                        // Reinitialize select2
                        selectProvinsi.trigger('change');
                    }
                },
                error: function () {
                    alert('Gagal memuat data wilayah!');
                }
            });
        }

        // ================= FUNCTION LOAD KOTA =================
        function loadKota(provinsiId) {
            $('#loadingKota').addClass('show');

            setTimeout(function () {
                let kota = wilayahData.kota.filter(k => k.provinsi_id == provinsiId);

                let selectKota = $('#selectKota');
                selectKota.html('<option value="">-- Pilih Kota --</option>');

                if (kota.length > 0) {
                    kota.forEach(function (k) {
                        selectKota.append(
                            $('<option></option>')
                                .attr('value', k.id)
                                .text(k.name)
                        );
                    });
                    selectKota.prop('disabled', false);
                } else {
                    selectKota.prop('disabled', true);
                }

                selectKota.trigger('change');
                $('#loadingKota').removeClass('show');
            }, 300);
        }

        // ================= FUNCTION LOAD KECAMATAN =================
        function loadKecamatan(kotaId) {
            $('#loadingKecamatan').addClass('show');

            // Simulate AJAX delay
            setTimeout(function () {
                let kecamatan = wilayahData.kecamatan.filter(k => k.kota_id == kotaId);

                let selectKecamatan = $('#selectKecamatan');
                selectKecamatan.html('<option value="">-- Pilih Kecamatan --</option>');

                if (kecamatan.length > 0) {
                    kecamatan.forEach(function (k) {
                        selectKecamatan.append(
                            $('<option></option>')
                                .attr('value', k.id)
                                .text(k.name)
                        );
                    });
                    selectKecamatan.prop('disabled', false);
                } else {
                    selectKecamatan.prop('disabled', true);
                }

                selectKecamatan.trigger('change');
                $('#loadingKecamatan').removeClass('show');
            }, 300);
        }

        // ================= FUNCTION LOAD KELURAHAN =================
        function loadKelurahan(kecamatanId) {
            $('#loadingKelurahan').addClass('show');

            // Simulate AJAX delay
            setTimeout(function () {
                let kelurahan = wilayahData.kelurahan.filter(k => k.kecamatan_id == kecamatanId);

                let selectKelurahan = $('#selectKelurahan');
                selectKelurahan.html('<option value="">-- Pilih Kelurahan --</option>');

                if (kelurahan.length > 0) {
                    kelurahan.forEach(function (k) {
                        selectKelurahan.append(
                            $('<option></option>')
                                .attr('value', k.id)
                                .text(k.name)
                        );
                    });
                    selectKelurahan.prop('disabled', false);
                } else {
                    selectKelurahan.prop('disabled', true);
                }

                selectKelurahan.trigger('change');
                $('#loadingKelurahan').removeClass('show');
            }, 300);
        }

        // ================= FUNCTION CLEAR SELECT =================
        function clearSelect(selectId) {
            let select = $('#' + selectId);
            let placeholder = select.attr('id');

            if (placeholder === 'selectKota') {
                select.html('<option value="">-- Pilih Kota --</option>');
            } else if (placeholder === 'selectKecamatan') {
                select.html('<option value="">-- Pilih Kecamatan --</option>');
            } else if (placeholder === 'selectKelurahan') {
                select.html('<option value="">-- Pilih Kelurahan --</option>');
            }

            select.prop('disabled', true);
            select.trigger('change');
        }

        // ================= FUNCTION INITIALIZE SELECT2 =================
        function initSelect2() {
            $('.select2-element').select2({
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: false,
                minimumResultsForSearch: 0
            });
        }
    </script>
@endpush
