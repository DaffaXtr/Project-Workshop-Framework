@extends('layouts.app')

@section('title', 'Form Select dengan JavaScript')

@push('style-page')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style>
        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .select-result {
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 0.375rem;
            margin-top: 1rem;
        }

        .select-result span {
            font-weight: 600;
            color: #495057;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <h2>Form Select dengan JavaScript</h2>
        <p class="text-muted">Studi Kasus: Membandingkan Select Normal vs Select2</p>
    </div>

    <div class="container">
        <div class="row">
            {{-- SELECT NORMAL --}}
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Select Normal</h4>

                        <div class="form-group mb-3">
                            <label class="form-label">Nama Kota</label>
                            <input 
                                type="text" 
                                id="inputBiasa" 
                                class="form-control" 
                                placeholder="Masukkan nama kota"
                                @keyup.enter="tambahKeSelect"
                            >
                        </div>

                        <button 
                            type="button" 
                            class="btn btn-primary mb-4"
                            onclick="tambahKeSelect(this,'inputBiasa','selectBiasa')"
                        >
                            <i class="mdi mdi-plus"></i> Tambahkan
                        </button>

                        <div class="form-group mb-3">
                            <label class="form-label">Daftar Kota</label>
                            <select id="selectBiasa" class="form-select">
                                <option value="" disabled selected>-- Pilih Kota --</option>
                            </select>
                        </div>

                        <div class="select-result">
                            <b>Kota Terpilih :</b>
                            <span id="displayBiasa">-</span>
                        </div>

                    </div>
                </div>
            </div>

            {{-- SELECT2 --}}
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Select2 (dengan Pencarian)</h4>

                        <div class="form-group mb-3">
                            <label class="form-label">Nama Kota</label>
                            <input 
                                type="text" 
                                id="inputTwo" 
                                class="form-control" 
                                placeholder="Masukkan nama kota"
                                @keyup.enter="tambahKeSelect"
                            >
                        </div>

                        <button 
                            type="button" 
                            class="btn btn-primary mb-4"
                            onclick="tambahKeSelect(this,'inputTwo','selectTwo')"
                        >
                            <i class="mdi mdi-plus"></i> Tambahkan
                        </button>

                        <div class="form-group mb-3">
                            <label class="form-label">Daftar Kota</label>
                            <select id="selectTwo" class="form-select select2-element">
                                <option></option>
                            </select>
                            <small class="text-muted">Tip: Ketik untuk mencari kota</small>
                        </div>

                        <div class="select-result">
                            <b>Kota Terpilih :</b>
                            <span id="displayTwo">-</span>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('script-page')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            // ================= SELECT2 INITIALIZATION =================
            $('#selectTwo').select2({
                theme: 'bootstrap-5',
                placeholder: "-- Pilih atau Cari Kota --",
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 0,
                closeOnSelect: true,
                language: {
                    noResults: function () {
                        return "Tidak ada hasil ditemukan";
                    },
                    searching: function () {
                        return "Mencari...";
                    },
                    inputTooShort: function () {
                        return "Ketik untuk mencari...";
                    }
                }
            });

            // ================= EVENT HANDLERS =================
            // SELECT NORMAL
            $('#selectBiasa').on('change', function () {
                let val = $(this).val();
                $('#displayBiasa').text(val || '-');
            });

            // SELECT2
            $('#selectTwo').on('select2:select', function (e) {
                let val = e.params.data.text;
                $('#displayTwo').text(val || '-');
            });

            $('#selectTwo').on('select2:clear', function () {
                $('#displayTwo').text('-');
            });

            // ENTER KEY HANDLER
            $('#inputBiasa, #inputTwo').keypress(function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    let selectId = $(this).attr('id') === 'inputBiasa' ? 'selectBiasa' : 'selectTwo';
                    tambahKeSelect(
                        $('button[onclick*="' + selectId + '"]')[0],
                        $(this).attr('id'),
                        selectId
                    );
                }
            });
        });

        // ================= FUNCTION TAMBAH KE SELECT =================
        function tambahKeSelect(button, inputId, selectId) {
            // Prevent double submit
            if (button.classList.contains("loading")) return;

            const originalText = button.innerHTML;
            const inputVal = $('#' + inputId).val().trim();

            // Validate input
            if (inputVal === "") {
                alert("Silakan isi nama kota terlebih dahulu!");
                return;
            }

            // Check if already exists
            let selectEl = $('#' + selectId);
            let exists = selectEl.find('option').filter(function () {
                return $(this).val() === inputVal;
            }).length > 0;

            if (exists) {
                alert("Kota '" + inputVal + "' sudah ada dalam daftar!");
                return;
            }

            // BUTTON LOADER START
            button.classList.add("loading");
            button.disabled = true;
            button.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Loading...
            `;

            // Simulate API call / processing
            setTimeout(() => {
                // Add new option
                let newOption = new Option(inputVal, inputVal, false, true);
                selectEl.append(newOption).trigger('change');

                // Update Select2 display
                if (selectEl.hasClass('select2-element')) {
                    $('#displayTwo').text(inputVal);
                }

                // Clear input
                $('#' + inputId).val('').focus();

                // Reset button
                resetButton();
            }, 600);

            function resetButton() {
                button.classList.remove("loading");
                button.disabled = false;
                button.innerHTML = originalText;
            }
        }
    </script>
@endpush