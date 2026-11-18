<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        @page {
            margin: 80px 25px;
        }

        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
            font-weight: bold;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
        }

        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            position: relative;
            font-size: 8px;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif !important;
        }

        .user {
            position: fixed;
            top: 0;
            right: 0;
            margin-top: 5px;
            margin-right: 5px;
        }

        .header table {
            /* border: 0.5px solid #000; */
            width: 100%;
        }

        .header table tr td {
            text-align: center;
        }

        .tbl_data {
            page-break-after: always;
        }

        .tbl_data:last-child {
            page-break-after: never;
        }


        .tbl_data {
            border-collapse: collapse;
            border: 1px solid;
        }

        .tbl_data td {
            padding: 5px;
        }

        .tbl_data tbody tr td .row-rupiah {
            width: 100%;
            display: flex;
        }

        .footer span {
            color: blue;
        }

        .page::after {
            content: counter(page);
        }
    </style>
</head>

<body>
    <header>
        <div class="header">
            <table width="100%">
                <tr>
                    <td style="text-align: left"><?= $title ?></td>
                    <td style="text-align: left"></td>
                </tr>
                <tr>
                    <td style="text-align: left"><?= $JobDetail ?></td>
                    <td style="text-align: left"></td>
                </tr>
                <tr>
                    <td style="text-align: left"><?= $periode ?></td>
                    <td style="text-align: right;" class="user">Printed On <?= $date ?> By <?= $user ?> | Page <span class="page"></span></td>
                </tr>
            </table>
        </div>
    </header>
    <footer>
        <p class="footer">Copyright &copy; 2022 <span>IT-KGS.</span> All rights servered</p>
    </footer>

    <main>
        <table width="100%" border="1" class="tbl_data">
            <thead>
                <tr>
                    <td style="text-align: left;">No</td>
                    <td style="text-align: left;">Kode Barang</td>
                    <td style="text-align: left;">Nama Barang</td>
                    <td style="text-align: left;">Nama Vendor</td>
                    <td style="text-align: center;">Tgl Pembelian</td>
                    <td style="text-align: center;">Vol</td>
                    <td style="text-align: center;">Harga Satuan</td>
                    <td style="text-align: center;">Total</td>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($query as $row) : ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $row['KdBarang']; ?></td>
                        <td><?= $row['Uraian']; ?></td>
                        <td><?= $row['VendorNm']; ?></td>
                        <td style="text-align: center;"><?= $row['TglKO']; ?></td>
                        <td style="text-align: right;"><?= $row['Vol']; ?></td>
                        <td>
                            <div class="row-rupiah">
                                <div>Rp.</div>
                                <div style="text-align: right;"><?= number_format(intval($row['HrgSatuan']), 0, ",", "."); ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="row-rupiah">
                                <div>Rp.</div>
                                <div style="text-align: right;"><?= number_format(intval($row['Total']), 0, ",", "."); ?></div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="7" style="text-align: center; font-weight: bold;">Sum = </td>
                    <td style="text-align: center; font-weight: bold;"><?= number_format(intval($sumTotal), 0, ",", "."); ?></td>
                </tr>
            </tbody>
        </table>
    </main>
</body>

</html>