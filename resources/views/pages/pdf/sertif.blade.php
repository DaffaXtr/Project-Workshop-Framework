<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        /* Pengaturan Kertas Dompdf */
        @page {
            margin: 0;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', 'Arial', sans-serif; /* Font standar agar aman di dompdf */
            background-color: #fdfaf5;
        }

        /* Pembungkus utama agar tidak meluap ke halaman 2 */
        .cert-canvas {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            padding: 30px;
            box-sizing: border-box;
        }

        /* Bingkai Emas Luar */
        .border-gold-outer {
            border: 5px solid #c5a059;
            height: 100%;
            position: relative;
            box-sizing: border-box;
        }

        /* Bingkai Tipis Dalam */
        .border-inner-content {
            border: 1px solid #c5a059;
            margin: 10px;
            height: calc(100% - 20px);
            position: relative;
            text-align: center;
            box-sizing: border-box;
        }

        /* Ornamen Sudut (Menggunakan karakter aman dompdf) */
        .ornament {
            position: absolute;
            color: #c5a059;
            font-size: 40px;
        }
        .tl { top: 5px; left: 10px; }
        .tr { top: 5px; right: 10px; }
        .bl { bottom: 5px; left: 10px; }
        .br { bottom: 5px; right: 10px; }

        /* Konten Teks */
        .header {
            margin-top: 60px;
        }

        .title {
            font-size: 60px;
            color: #3d2b1f;
            margin: 0;
            text-transform: uppercase;
            font-weight: bold;
        }

        .subtitle {
            font-size: 20px;
            letter-spacing: 5px;
            color: #3d2b1f;
            margin-top: 5px;
        }

        .presented {
            margin-top: 40px;
            font-style: italic;
            font-size: 18px;
        }

        .name {
            font-size: 70px;
            color: #c5a059;
            margin: 20px auto;
            border-bottom: 2px solid #e0e0e0;
            width: 75%;
            padding-bottom: 5px;
        }

        .desc {
            font-size: 16px;
            color: #444;
            line-height: 1.4;
            width: 80%;
            margin: 0 auto;
        }

        /* Footer Tanda Tangan (Gunakan Table agar dompdf stabil) */
        .footer-table {
            width: 90%;
            margin: 60px auto 0 auto;
            border-collapse: collapse;
        }

        .sig-cell {
            width: 33%;
            text-align: center;
            vertical-align: bottom;
        }

        .sig-line {
            width: 80%;
            border-top: 1px solid #c5a059;
            margin: 0 auto 10px auto;
        }

        .medal {
            width: 70px;
            height: 70px;
            background-color: #c5a059;
            border-radius: 50%;
            margin: 0 auto;
            border: 4px solid #b38f4d;
        }

        .label-name { font-weight: bold; font-size: 16px; margin: 0; }
        .label-title { font-size: 13px; color: #666; margin: 0; }

    </style>
</head>
<body>

    <div class="cert-canvas">
        <div class="border-gold-outer">
            <div class="border-inner-content">
                
                <div class="ornament tl">✦</div>
                <div class="ornament tr">✦</div>
                <div class="ornament bl">✦</div>
                <div class="ornament br">✦</div>

                <div class="header">
                    <h1 class="title">CERTIFICATE</h1>
                    <div class="subtitle">OF PARTICIPATION</div>
                </div>

                <p class="presented">This Certificate Is Presented To</p>
                
                <div class="name">Marceline Anderson</div>
                
                <p class="desc">
                    Thank you for participating in the competition and winning <br>
                    the photography competition 2026.
                </p>

                <table class="footer-table">
                    <tr>
                        <td class="sig-cell">
                            <div class="sig-line"></div>
                            <p class="label-name">Juliana Silva</p>
                            <p class="label-title">Head of Marketing</p>
                        </td>
                        <td class="sig-cell">
                            <div class="medal"></div>
                        </td>
                        <td class="sig-cell">
                            <div class="sig-line"></div>
                            <p class="label-name">Benjamin Shah</p>
                            <p class="label-title">President Director</p>
                        </td>
                    </tr>
                </table>

            </div>
        </div>
    </div>

</body>
</html>