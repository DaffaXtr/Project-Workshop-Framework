@extends('layouts.app')

@section('title', 'Barang')

@section('style-page')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

@endsection

@section('content')
<div class="container">
    <h2>Daftar Barang</h2>
    <a href="{{ route('barang.create') }}" class="btn btn-sm btn-success mb-3 btn-link-loader">Tambah Barang</a>
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

                <table id="datatable-barang" class="table">
                    <thead>
                        <tr>
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
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $b->nama }}</td>
                            <td>{{ $b->harga }}</td>
                            <td>{{ $b->timestamp }}</td>
                            <td>
                                <a href="{{ route('barang.edit', $b->id_barang) }}"
                                    class="btn btn-sm btn-primary">Edit</a>

                                <form action="{{ route('barang.destroy', $b->id_barang) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-loader"
                                        data-confirm="Yakin ingin menghapus barang ini?">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3 text-end">
                    <a href="#" class="btn btn-sm btn-info btn-link-loader">Download PDF</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#datatable-barang').DataTable({
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
});
</script>
@endpush