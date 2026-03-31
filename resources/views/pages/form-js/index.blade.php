@extends('layouts.app')

@section('title', 'Barang HTML')

@push('style-page')
    <style>
        #tabelBarang tr:hover {
            cursor: pointer;
            background: #f5f5f5;
        }

        .btn.loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .btn.loading .spinner-border {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            vertical-align: -0.25em;
            border-color: currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spinner-border 0.75s linear infinite;
        }

        @keyframes spinner-border {
            to { transform: rotate(360deg); }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title mb-4">Input Barang</h4>

                    <form id="formBarang" class="mb-4">

                        <div class="form-group mb-3">
                            <label>Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Harga Barang</label>
                            <input type="number" name="harga_barang" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-submit" id="btnSubmit">
                            Submit
                        </button>

                    </form>


                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                            </tr>
                        </thead>

                        <tbody id="tabelBarang">
                            <tr id="emptyRow">
                                <td colspan="3" class="text-center">Belum ada data barang</td>
                            </tr>
                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>


    {{-- MODAL --}}
    <div class="modal fade" id="modalBarang">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Barang</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="form-group mb-3">
                        <label>ID Barang</label>
                        <input type="text" id="edit_id" class="form-control" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label>Nama Barang</label>
                        <input type="text" id="edit_nama" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Harga Barang</label>
                        <input type="number" id="edit_harga" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button id="btnHapus" class="btn btn-danger" data-loading-text="Menghapus...">
                        <span class="btn-text">Hapus</span>
                    </button>
                    <button id="btnUbah" class="btn btn-primary" data-loading-text="Mengubah...">
                        <span class="btn-text">Ubah</span>
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection


@push('script-page')

    <script>

        let counter = 1;
        let selectedRow = null;

        let tabel = document.getElementById("tabelBarang");


        $("#formBarang").submit(function (e) {

            e.preventDefault();

            let btn = $("#btnSubmit");
            let originalText = btn.html();
            
            btn.prop('disabled', true).addClass('loading');
            btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');

            setTimeout(() => {

                let nama = $("input[name='nama_barang']").val();
                let harga = $("input[name='harga_barang']").val();

                if (!nama || !harga) {
                    btn.prop('disabled', false).removeClass('loading');
                    btn.html(originalText);
                    alert('Nama dan harga wajib diisi!');
                    return;
                }

                let id = String(counter).padStart(3, '0');

                let emptyRow = document.getElementById("emptyRow");
                if (emptyRow) emptyRow.remove();

                let row = `
                    <tr>
                        <td>${id}</td>
                        <td>${nama}</td>
                        <td>Rp ${parseInt(harga).toLocaleString('id-ID')}</td>
                    </tr>
                `;

                tabel.insertAdjacentHTML("beforeend", row);

                counter++;
                document.getElementById("formBarang").reset();
                
                btn.prop('disabled', false).removeClass('loading');
                btn.html(originalText);

            }, 600);

        });


        tabel.addEventListener("click", function (e) {

            let row = e.target.closest("tr");
            if (!row) return;

            selectedRow = row;

            let id = row.cells[0].innerText;
            let nama = row.cells[1].innerText;
            let harga = row.cells[2].innerText.replace(/[^\d]/g, '');

            document.getElementById("edit_id").value = id;
            document.getElementById("edit_nama").value = nama;
            document.getElementById("edit_harga").value = harga;

            new bootstrap.Modal(document.getElementById("modalBarang")).show();

        });


        document.getElementById("btnUbah").onclick = function () {

            let btn = this;
            let originalText = btn.innerHTML;
            let loadingText = btn.getAttribute('data-loading-text') || 'Loading...';
            
            let nama = document.getElementById("edit_nama").value;
            let harga = document.getElementById("edit_harga").value;

            if (nama == "" || harga == "") {
                alert("Nama dan harga wajib diisi");
                return;
            }

            btn.disabled = true;
            btn.classList.add('loading');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>' + loadingText;

            setTimeout(() => {
                selectedRow.cells[1].innerText = nama;
                selectedRow.cells[2].innerText = "Rp " + parseInt(harga).toLocaleString('id-ID');

                btn.disabled = false;
                btn.classList.remove('loading');
                btn.innerHTML = originalText;
                
                bootstrap.Modal.getInstance(document.getElementById("modalBarang")).hide();
            }, 600);

        };


        document.getElementById("btnHapus").onclick = function () {

            let btn = this;
            let originalText = btn.innerHTML;
            let loadingText = btn.getAttribute('data-loading-text') || 'Loading...';
            
            btn.disabled = true;
            btn.classList.add('loading');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>' + loadingText;

            setTimeout(() => {
                if (selectedRow) {
                    selectedRow.remove();
                }

                btn.disabled = false;
                btn.classList.remove('loading');
                btn.innerHTML = originalText;
                
                bootstrap.Modal.getInstance(document.getElementById("modalBarang")).hide();
            }, 600);

        };

    </script>

@endpush