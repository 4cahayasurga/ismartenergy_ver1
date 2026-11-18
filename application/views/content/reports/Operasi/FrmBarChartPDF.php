<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            font-size: 12px;
        }

        .header {
            text-align: right;
            margin-bottom: 20px;
        }

        .header p {
            font-size: 10px;
        }

        .container {
            padding: 20px 60px;
            /* background-color: #D1B894; */
        }

        .content {
            margin-bottom: 20px;
        }

        .content:first-child {
            border: 1px solid #000;
        }

        img {
            width: 100%;
            height: 300px;
            object-fit: contain;
            text-align: center;
        }

        table {
            border: 1px solid;
            border-collapse: collapse;
        }

        table tr th,
        table tr td {
            border: 1px solid;
            padding: 4px 5px;
        }

        table tr th {
            text-align: center;
            font-weight: normal;
        }

        table tr td {
            text-align: right;
        }

        table tr th {
            width: 40%;
        }

        table tr td {
            width: 60%;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <p>Printed On <?= date('d-M-Y') ?> By <?= $user ?></p>
        </div>
        <div class="content">
            <img src="<?= base_url('images/chart_image.png') ?>" alt="">
        </div>
        <div class="content">
            <table width="100%" class="table ">
                <tr>
                    <th colspan="2" style="background-color: #d8dee9;">Keterangan</th>
                    <th style="background-color: #d8dee9;">Rp</th>
                    <th style="background-color: #d8dee9;">%</th>
                </tr>
                <tr>
                    <th colspan="2" style="background-color: #38d9a9;">Kontrak</th>
                    <td><?= UbahMinus($bruto) ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th rowspan="2">RAP</th>
                    <th style="background-color: #6F9FD8;">B</th>
                    <td><?= UbahMinus($RAP_B) ?></td>
                    <td><?= UbahMinus(round($persen_rap_b)) . "%" ?></td>
                </tr>
                <tr>
                    <th style="background-color:#fff85b;">C</th>
                    <td><?= UbahMinus($RAP_C) ?></td>
                    <td><?= UbahMinus(round($persen_rap_c)) . "%" ?></td>
                </tr>
                <tr>
                    <th rowspan="2">Biaya (1)</th>
                    <th style="background-color: #6F9FD8;">B</th>
                    <td><?= UbahMinus($biaya_B) ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th style="background-color:#fff85b;">C</th>
                    <td><?= UbahMinus($biaya_C) ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th colspan="2" style="background-color:#D1B894;">Sisa Hutang KO (2)</th>
                    <td><?= UbahMinus($sisa_hutang_KO) ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th rowspan="2">Sisa YAD (3)</th>
                    <th style="background-color: #ffb90f;">B</th>
                    <td><?= UbahMinus($sum_total_YAD_B) ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th style="background-color:#da77f2;">C</th>
                    <td><?= UbahMinus($sum_total_YAD_C) ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th colspan="2">Total Biaya (1+2+3)</th>
                    <td><?= UbahMinus($total_biaya_baru) ?></td>
                    <td><?= UbahMinus(round($persen_biaya_baru)) . "%" ?></td>
                </tr>
                <tr>
                    <th rowspan="2">Sisa Biaya</th>
                    <th>Kontrak</th>
                    <td><?= UbahMinus($sisa_biaya_terhadap_kontrak_baru) ?></td>
                    <td><?= UbahMinus(round($persen_sisa_biaya_terhadap_kontrak_baru)) . "%" ?></td>
                </tr>
                <tr>
                    <th>RAP</th>
                    <td><?= UbahMinus($sisa_biaya_rap_terhadap_kontrak) ?></td>
                    <td><?= UbahMinus(round($persen_sisa_biaya_rap_terhadap_kontrak)) . "%" ?></td>
                </tr>

            </table>
        </div>
    </div>
</body>

</html>