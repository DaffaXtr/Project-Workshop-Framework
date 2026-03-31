@extends('layouts.app')

@section('title', 'Barang DataTables')

@push('style-page')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

<style>
    #tableBarang tbody tr:hover {
        cursor: pointer;
        background: #f5f5f5;
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

                    <button type="submit" class="btn btn-primary">Submit</button>

                </form>


                <div class="table-responsive">
                    <table id="tableBarang" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

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
                <button id="btnHapus" class="btn btn-danger">Hapus</button>
                <button id="btnUbah" class="btn btn-primary">Ubah</button>
            </div>

        </div>
    </div>
</div>

@endsection


@push('script-page')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script>

let counter = 1;
let table;
let selectedRow;

$(document).ready(function () {

    table = $("#tableBarang").DataTable({
        language: {
            emptyTable: "Belum ada data barang"
        }
    });

    $("#formBarang").submit(function (e) {

        e.preventDefault();

        let submitBtn = $(this).find("button[type='submit']");
        submitBtn.prop("disabled", true);

        let nama = $("input[name='nama_barang']").val();
        let harga = $("input[name='harga_barang']").val();

        let id = String(counter).padStart(3, '0');

        table.row.add([
            id,
            nama,
            "Rp " + parseInt(harga).toLocaleString('id-ID')
        ]).draw();

        counter++;
        this.reset();

        submitBtn.prop("disabled", false);

    });


    $('#tableBarang tbody').on('click', 'tr', function () {

        selectedRow = table.row(this);

        let data = selectedRow.data();

        $("#edit_id").val(data[0]);
        $("#edit_nama").val(data[1]);
        $("#edit_harga").val(data[2].replace(/[^\d]/g, ''));

        new bootstrap.Modal(document.getElementById("modalBarang")).show();

    });


    $("#btnUbah").click(function () {

        let id = $("#edit_id").val();
        let nama = $("#edit_nama").val();
        let harga = $("#edit_harga").val();

        if (nama == "" || harga == "") {
            alert("Nama dan harga wajib diisi");
            return;
        }

        selectedRow.data([
            id,
            nama,
            "Rp " + parseInt(harga).toLocaleString('id-ID')
        ]).draw();

        bootstrap.Modal.getInstance(document.getElementById("modalBarang")).hide();

    });


    $("#btnHapus").click(function () {

        selectedRow.remove().draw();

        bootstrap.Modal.getInstance(document.getElementById("modalBarang")).hide();

    });

});

</script>

@endpush