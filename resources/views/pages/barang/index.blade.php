@extends('layouts.app')

@section('title', 'Barang')

@section('style-page')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
@endsection

@section('content')
<div class="container">
    <h2>Daftar Barang</h2>
    <a href="{{ route('barang.create') }}" class="btn btn-sm btn-success mb-3 btn-link-loader">
        Tambah Barang
    </a>
</div>

<div class="container">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                <!-- ===================== TABLE ===================== -->

                <table id="datatable-barang" class="table">
                    <thead>
                        <tr>
                            <th width="5%">
                                <input type="checkbox" id="checkAll">
                            </th>
                            <th>NO</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Timestamp</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($barang as $index => $b)
                        <tr>
                            <td>
                                <!-- Checkbox tetap ikut formCetak -->
                                <input type="checkbox" name="barang_ids[]" value="{{ $b->id_barang }}" form="formCetak">
                            </td>

                            <td>{{ $index + 1 }}</td>
                            <td>{{ $b->nama }}</td>
                            <td>{{ $b->harga }}</td>
                            <td>{{ $b->timestamp }}</td>

                            <td>
                                <a href="{{ route('barang.edit', $b->id_barang) }}" class="btn btn-sm btn-primary btn-link-loader">
                                    Edit
                                </a>

                                <!-- Form DELETE berdiri sendiri (TIDAK nested) -->
                                <form action="{{ route('barang.destroy', $b->id_barang) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-loader"
                                        data-confirm="Yakin ingin menghapus barang ini?"
                                        data-loading-text="Menghapus...">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <hr>

                <!-- ===================== FORM CETAK ===================== -->

                <form id="formCetak" action="{{ route('barang.cetakMassal') }}" method="POST" target="_blank">

                    @csrf

                    <div class="row mt-3">
                        <div class="col-md-2">
                            <label>X (Kolom 1-5)</label>
                            <input type="number" name="start_x" class="form-control" min="1" max="5" required>
                        </div>

                        <div class="col-md-2">
                            <label>Y (Baris 1-8)</label>
                            <input type="number" name="start_y" class="form-control" min="1" max="8" required>
                        </div>

                        <div class="col-md-3 align-self-end">
                            <button type="submit" class="btn btn-primary btn-loader">
                                Cetak Label TNJ 108
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection


@push('script-page')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {

    // DataTables
    let table = $('#datatable-barang').DataTable({
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                next: "Next",
                previous: "Previous"
            }
        }
    });

    // Checkbox select all
    $('#checkAll').on('click', function() {
        $('input[name="barang_ids[]"]').prop('checked', this.checked);
    });

});
</script>

@endpush