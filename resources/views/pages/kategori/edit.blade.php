@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Edit Kategori</h2>
            <a href="{{ route('kategori.index') }}" class="btn btn-secondary btn-link-loader">Back</a>
        </div>
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
                                <input type="text" class="form-control" id="exampleInputUsername1"
                                    placeholder="Nama Kategori" name="nama_kategori" value="{{ $kategori->nama_kategori }}">
                                <button type="submit" class="btn btn-primary btn-loader">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection