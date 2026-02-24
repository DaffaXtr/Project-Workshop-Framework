<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat</title>
    <style>
        @page {
            margin: 40px;
        }

        body {
            background-image: url('{{ asset('assets/images/bg-sertif.jpeg') }}');
            background-size: cover;
            font-family: "Times New Roman", serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }

        .outer-border {
            /* border: 6px solid #000080; */
            padding: 10px;
        }

        .inner-border {
            /* border: 2px solid #d4af37; */
            padding: 120px 0 0 0;
            text-align: center;
        }

        .title {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 3px;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 18px;
            margin-bottom: 40px;
        }

        .name {
            font-size: 28px;
            font-weight: bold;
            text-decoration: underline;
            margin: 20px 0;
        }

        .content-text {
            font-size: 16px;
            margin: 10px 0;
        }

        .signature-table {
            width: 100%;
            margin-top: 80px;
        }

        .signature-table td {
            width: 50%;
            text-align: center;
        }

        .line {
            margin-top: 70px;
            border-top: 1px solid #000;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
        }

        .date {
            text-align: right;
            margin-top: 40px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="outer-border">

    <div class="bg-image">
        <img src="{{ public_path('assets/images/bg-sertif.jpeg') }}" alt="bg-sertif" style="width: 100%; height: auto; position: absolute; top: 0; left: 0; z-index: -1;">
    </div>

    <div class="inner-border">

        <div class="title">SERTIFIKAT</div>
        <div class="subtitle">Certificate of Appreciation</div>

        <div class="content-text">Diberikan kepada:</div>
        <div class="name">Daffa Eka Sujianto</div>

        <div class="content-text">
            Atas partisipasinya dalam kegiatan
        </div>

        <div class="content-text">
            <strong>Workshop Pemrograman Web</strong>
        </div>

        <div class="content-text">
            yang diselenggarakan pada tanggal 24 Februari 2026
        </div>

        <table class="signature-table">
            <tr>
                <td>
                    Ketua Panitia
                    <div class="line"></div>
                    (Daffa Eka Sujianto)
                </td>
                <td>
                    Pembimbing
                    <div class="line"></div>
                    (Daffa Eka Sujianto)
                </td>
            </tr>
        </table>

    </div>
</div>

</body>
</html>