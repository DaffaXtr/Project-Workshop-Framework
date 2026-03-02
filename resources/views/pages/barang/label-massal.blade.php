<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
    /* Standar dompdf: hapus margin agar tidak tabrakan dengan style internal */
    @page {
        margin: 0;
        padding: 0;
        /* Margin minimal untuk area cetak printer */
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 0;
        /* width: auto; */
        text-align: center;
        font-family: Arial, sans-serif;
    }

    /* Mengunci tabel agar tidak melebar otomatis */
    table {
        border-collapse: collapse;
        table-layout: fixed;
        width: auto;
        /* border: 0.1pt solid #ccc; */
        margin-left: auto;
        margin-right: auto;
    }

    /* Set ukuran sel label secara absolut */
    td {
        width: 39mm;
        height: 19mm;
        padding: 1mm;
        margin: 0;
        /* border: 0.1pt solid #ccc; */
        box-sizing: border-box;
        text-align: center;
        /* tengah horizontal */
        vertical-align: middle;
        /* tengah vertikal */
    }

    .label-content {
        width: 38mm;
        height: 18mm;
        border: 0.1pt solid #ccc;
        box-sizing: border-box;
        display: table;
        text-align: center;
    }

    .label-inner {
        display: table-cell;
        vertical-align: middle;
        height: 18mm;
    }

    /* Kontainer teks di dalam td */
    /* .label-content {
        width: 38mm;
        height: 18mm;
        display: block;
        border: 0.1pt solid #ccc;
        box-sizing: border-box;
    } */

    .nama {
        font-size: 7pt;
        font-weight: normal;
        line-height: 1;
        margin: 0 4mm;
        text-transform: uppercase;
        word-wrap: break-word;
    }

    .harga {
        font-weight: bold;
        font-size: 8pt;
        /* margin-top: 1mm; */
    }
    </style>
</head>

<body>

    <table>
        @php $dataIndex = 0; @endphp

        @for($row = 0; $row < 8; $row++) <tr>
            @for($col = 0; $col < 5; $col++) @php $currentIndex=($row * 5) + $col; @endphp <td>
                {{-- Logika startIndex untuk skip label kosong di awal jika diperlukan --}}
                @if($currentIndex >= ($startIndex ?? 0) && isset($barang[$dataIndex]))
                <div class="label-content">
                    <div class="label-inner">
                        <div class="nama">
                            {{ $barang[$dataIndex]->nama }}
                        </div>
                        <div class="harga">
                            Rp{{ number_format($barang[$dataIndex]->harga, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                @php $dataIndex++; @endphp
                @endif
                </td>
                @endfor
                </tr>
                @endfor
    </table>

</body>

</html>