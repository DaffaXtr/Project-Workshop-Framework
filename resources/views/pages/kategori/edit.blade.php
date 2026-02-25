@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('style-page')
<!-- Style page disini -->
@endsection

@section('content')
<div class="container">
    <h2>Edit Kategori</h2>
</div>

<div class="container">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="{{ route('kategori.update', $kategori->idkategori) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group" style="margin-bottom: 0px;">
                        <label for="exampleInputUsername1">Nama Kategori</label>
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control" id="exampleInputUsername1" placeholder="Nama Kategori" name="nama_kategori" value="{{ $kategori->nama_kategori }}">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>        
        </div>
    </div>
</div>
@endsection

@push('script-page')
<!-- JS page disini -->
<script>
    console.log("Edit Kategori Page Loaded");
</script>
@endpush
