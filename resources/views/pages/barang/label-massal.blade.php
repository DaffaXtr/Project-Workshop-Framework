<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
    @page {
        margin: 0;
        padding: 0;
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

    table {
        border-collapse: collapse;
        table-layout: fixed;
        width: auto;
        /* border: 0.1pt solid #ccc; */
        margin-top: 2mm;
        margin-left: auto;
        margin-right: auto;
    }

    td {
        width: 39mm;
        height: 19mm;
        padding: 1mm;
        margin: 0;
        /* border: 0.1pt solid #ccc; */
        box-sizing: border-box;
        text-align: center;
        vertical-align: middle;
    }

    .label-content {
        width: 38mm;
        height: 18mm;
        /* border: 1pt solid #000000; */
        box-sizing: border-box;
        display: table;
        text-align: center;
        vertical-align: middle;
    }

    .label-inner {
        display: table-cell;
        vertical-align: middle;
        height: 18mm;
    }

    .nama {
        font-size: 7pt;
        font-weight: normal;
        line-height: 1;
        margin: 0 4mm;
        text-transform: uppercase;
        word-wrap: break-word;
    }

    .kode {
        font-size: 6pt;
        color: #555;
        margin-top: 0.5mm;
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

        @for($row = 0; $row < 8; $row++) 
            <tr>
                @for($col = 0; $col < 5; $col++) 
                    @php $currentIndex=($row * 5) + $col; @endphp 
                        <td>
                            @if($currentIndex >= ($startIndex ?? 0) && isset($barang[$dataIndex]))
                                <div class="label-content">
                                    <div class="label-inner">
                                        <div class="nama">
                                            {{ $barang[$dataIndex]->nama }}
                                        </div>
                                        <div class="kode">
                                            Id: {{ $barang[$dataIndex]->id_barang }}
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