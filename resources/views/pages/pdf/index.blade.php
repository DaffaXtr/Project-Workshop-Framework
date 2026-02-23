@extends('layouts.app')

@section('title', 'Surat')

@section('content')
<div class="container">
    <h2>Daftar Surat</h2>
</div>

<div class="container">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <table class="table">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>Nomor Surat</th>
                            <th>Perihal</th>
                            <th>Tanggal</th>
                            <th>Tujuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>001/TI/2026</td>
                            <td>Sertifikat Kegiatan</td>
                            <td>15 Februari 2026</td>
                            <td>Penghargaan</td>
                            <td>
                                <a href="{{ route('pdf.landscape') }}" 
                                   class="btn btn-sm btn-danger">
                                   <i class="mdi mdi-file-pdf"></i> Download PDF
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td>2</td>
                            <td>002/TI/2026</td>
                            <td>Rapat Koordinasi</td>
                            <td>18 Februari 2026</td>
                            <td>Dosen Teknik Informatika</td>
                            <td>
                                <a href="{{ route('pdf.portrait') }}" 
                                   class="btn btn-sm btn-danger">
                                   <i class="mdi mdi-file-pdf"></i> Download PDF
                                </a>
                            </td>
                        </tr>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@endsection