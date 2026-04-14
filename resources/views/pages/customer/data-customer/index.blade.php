@extends('layouts.app')

@section('title', 'Data Customer')

@section('content')
    <div class="container">
        <h2>Daftar Customer</h2>
        <a href="{{ route('admin.customer.create') }}" class="btn btn-sm btn-success mb-3 btn-link-loader">Tambah Customer</a>
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

                    @if($customers->isEmpty())
                        <p class="text-center text-muted">Belum ada data customer</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Kelurahan</th>
                                        <th>Kecamatan</th>
                                        <th>Kota</th>
                                        <th>Provinsi</th>
                                        <th>Terdaftar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $index => $customer)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $customer->nama }}</strong></td>
                                            <td>{{ Str::limit($customer->alamat, 30) }}</td>
                                            <td>{{ $customer->kelurahan }}</td>
                                            <td>{{ $customer->kecamatan }}</td>
                                            <td>{{ $customer->kota }}</td>
                                            <td>{{ $customer->provinsi }}</td>
                                            <td><small class="text-muted">{{ $customer->created_at->format('d M Y') }}</small></td>
                                            <td>
                                                <a href="{{ route('admin.customer.edit', $customer->id_customer) }}"
                                                    class="btn btn-sm btn-primary btn-link-loader">
                                                    Edit</a>

                                                <form action="{{ route('admin.customer.destroy', $customer->id_customer) }}" method="POST"
                                                    style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button 
                                                        type="submit" 
                                                        class="btn btn-sm btn-danger btn-loader"
                                                        data-confirm="Yakin ingin menghapus customer ini?"
                                                        data-loading-text="Menghapus...">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
