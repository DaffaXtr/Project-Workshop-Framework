@extends('layouts.app')

@section('title', 'Cascading Select - Axios')

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
            background-color: #f0f7ff;
            border-left: 4px solid #198754;
            border-radius: 0.375rem;
            margin-top: 2rem;
        }

        .cascade-result h5 {
            color: #198754;
            margin-bottom: 1rem;
        }

        .result-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
        }

        .result-item:last-child {
            border-bottom: none;
        }

        .result-label {
            font-weight: 600;
            color: #495057;
        }

        .result-value {
            color: #198754;
            font-weight: 600;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            display: inline-block;
            margin-left: 0.5rem;
        }

        .status-loading {
            background-color: #ffc107;
            color: #000;
        }

        .status-success {
            background-color: #198754;
            color: #fff;
        }

        .status-error {
            background-color: #dc3545;
            color: #fff;
        }

        .spinner-sm {
            width: 1rem;
            height: 1rem;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #0d6efd;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 0.5rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <h2>Cascading Select Wilayah Indonesia</h2>
        <p class="text-muted">Versi Axios - Pilih Provinsi, Kota, Kecamatan, dan Kelurahan</p>
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
                                <label class="form-label">
                                    Provinsi <span class="text-danger">*</span>
                                    <span id="statusProvinsi" class="status-badge status-loading" style="display: none;">
                                        <span class="spinner-sm"></span>Loading
                                    </span>
                                </label>
                                <select id="selectProvinsi" class="form-select select2-element" required>
                                    <option value="">-- Pilih Provinsi --</option>
                                </select>
                            </div>

                            {{-- LEVEL 2: KOTA --}}
                            <div class="form-group mb-4 cascade-card">
                                <label class="form-label">
                                    Kota/Kabupaten <span class="text-danger">*</span>
                                    <span id="statusKota" class="status-badge" style="display: none;"></span>
                                </label>
                                <select id="selectKota" class="form-select select2-element" required disabled>
                                    <option value="">-- Pilih Kota --</option>
                                </select>
                            </div>

                            {{-- LEVEL 3: KECAMATAN --}}
                            <div class="form-group mb-4 cascade-card">
                                <label class="form-label">
                                    Kecamatan <span class="text-danger">*</span>
                                    <span id="statusKecamatan" class="status-badge" style="display: none;"></span>
                                </label>
                                <select id="selectKecamatan" class="form-select select2-element" required disabled>
                                    <option value="">-- Pilih Kecamatan --</option>
                                </select>
                            </div>

                            {{-- LEVEL 4: KELURAHAN --}}
                            <div class="form-group mb-4 cascade-card">
                                <label class="form-label">
                                    Kelurahan <span class="text-danger">*</span>
                                    <span id="statusKelurahan" class="status-badge" style="display: none;"></span>
                                </label>
                                <select id="selectKelurahan" class="form-select select2-element" required disabled>
                                    <option value="">-- Pilih Kelurahan --</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success">
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

                        <div class="alert alert-success mt-3" id="successMessage" style="display: none;">
                            <i class="mdi mdi-check-circle"></i>
                            <strong>Data berhasil disimpan!</strong>
                        </div>

                        <div class="alert alert-danger mt-3" id="errorMessage" style="display: none;">
                            <i class="mdi mdi-alert-circle"></i>
                            <strong id="errorText">Terjadi kesalahan!</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script-page')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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

                loadKotaAxios(provinsiId);
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

                loadKecamatanAxios(kotaId);
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

                loadKelurahanAxios(kecamatanId);
            });

            // Kelurahan Change Event
            $('#selectKelurahan').on('select2:select', function (e) {
                let kelurahanName = e.params.data.text;
                $('#displayKelurahan').text(kelurahanName);
            });

            // Form Submit
            $('#formWilayah').on('submit', function (e) {
                e.preventDefault();

                let data = {
                    provinsi: $('#selectProvinsi').find('option:selected').text(),
                    kota: $('#selectKota').find('option:selected').text(),
                    kecamatan: $('#selectKecamatan').find('option:selected').text(),
                    kelurahan: $('#selectKelurahan').find('option:selected').text()
                };

                // Show success message
                $('#successMessage').fadeIn().delay(3000).fadeOut();

                // Log data (in real app, send to server)
                console.log('Data yang dipilih:', data);
            });
        });

        // FUNCTION LOAD DATA WILAYAH AXIOS 
        function loadWilayahData() {
            showStatusBadge('statusProvinsi', 'loading');

            axios.get('{{ asset('json/wilayah.json') }}')
                .then(function (response) {
                    wilayahData = response.data;

                    // Load provinsi
                    if (response.data.provinsi && response.data.provinsi.length > 0) {
                        let selectProvinsi = $('#selectProvinsi');
                        response.data.provinsi.forEach(function (prov) {
                            selectProvinsi.append(
                                $('<option></option>')
                                    .attr('value', prov.id)
                                    .text(prov.name)
                            );
                        });
                        // Reinitialize select2
                        selectProvinsi.trigger('change');
                        hideStatusBadge('statusProvinsi');
                    }
                })
                .catch(function (error) {
                    console.error('Error loading wilayah data:', error);
                    showErrorMessage('Gagal memuat data provinsi!');
                    showStatusBadge('statusProvinsi', 'error');
                });
        }

        // ================= FUNCTION LOAD KOTA DENGAN AXIOS =================
        function loadKotaAxios(provinsiId) {
            showStatusBadge('statusKota', 'loading');

            // Simulate Axios request delay
            axios.get(`{{ asset('json/wilayah.json') }}?provinsi=${provinsiId}`)
                .then(function (response) {
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
                            showStatusBadge('statusKota', 'success');
                        } else {
                            selectKota.prop('disabled', true);
                            showStatusBadge('statusKota', 'error');
                        }

                        selectKota.trigger('change');
                    }, 300);
                })
                .catch(function (error) {
                    console.error('Error loading kota:', error);
                    showStatusBadge('statusKota', 'error');
                });
        }

        // ================= FUNCTION LOAD KECAMATAN DENGAN AXIOS =================
        function loadKecamatanAxios(kotaId) {
            showStatusBadge('statusKecamatan', 'loading');

            axios.get(`{{ asset('json/wilayah.json') }}?kota=${kotaId}`)
                .then(function (response) {
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
                            showStatusBadge('statusKecamatan', 'success');
                        } else {
                            selectKecamatan.prop('disabled', true);
                            showStatusBadge('statusKecamatan', 'error');
                        }

                        selectKecamatan.trigger('change');
                    }, 300);
                })
                .catch(function (error) {
                    console.error('Error loading kecamatan:', error);
                    showStatusBadge('statusKecamatan', 'error');
                });
        }

        // ================= FUNCTION LOAD KELURAHAN DENGAN AXIOS =================
        function loadKelurahanAxios(kecamatanId) {
            showStatusBadge('statusKelurahan', 'loading');

            axios.get(`{{ asset('json/wilayah.json') }}?kecamatan=${kecamatanId}`)
                .then(function (response) {
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
                            showStatusBadge('statusKelurahan', 'success');
                        } else {
                            selectKelurahan.prop('disabled', true);
                            showStatusBadge('statusKelurahan', 'error');
                        }

                        selectKelurahan.trigger('change');
                    }, 300);
                })
                .catch(function (error) {
                    console.error('Error loading kelurahan:', error);
                    showStatusBadge('statusKelurahan', 'error');
                });
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

        // ================= FUNCTION SHOW STATUS BADGE =================
        function showStatusBadge(elementId, status) {
            let badge = $('#' + elementId);
            badge.removeClass('status-loading status-success status-error');
            badge.show();

            if (status === 'loading') {
                badge.addClass('status-loading').html('<span class="spinner-sm"></span>Loading');
            } else if (status === 'success') {
                badge.addClass('status-success').html('<i class="mdi mdi-check"></i> Loaded');
            } else if (status === 'error') {
                badge.addClass('status-error').html('<i class="mdi mdi-alert"></i> Error');
            }
        }

        // ================= FUNCTION HIDE STATUS BADGE =================
        function hideStatusBadge(elementId) {
            let badge = $('#' + elementId);
            setTimeout(function () {
                badge.fadeOut();
            }, 1000);
        }

        // ================= FUNCTION SHOW ERROR MESSAGE =================
        function showErrorMessage(message) {
            $('#errorText').text(message);
            $('#errorMessage').fadeIn().delay(3000).fadeOut();
        }
    </script>
@endpush
