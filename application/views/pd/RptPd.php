<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORT PERMINTAAN DANA || <?= $row->JobNm ?></title>
    <style>
        body {
            font-family: garamond, serif;

        }

        h1 {
            text-align: center;
        }

        span {
            text-align: center;
        }

        .title {
            display: block;
            border-top: 4px solid #a7a59b;
            background-color: white;
            height: 22px;
            line-height: 22px;
            padding: 4px 6px;
            font-size: 14px;
            color: #000;
            margin-bottom: 13px;
            clear: both;

        }

        .tgl {
            float: right;
        }

        .judul {
            font-weight: bold;
        }

        table {
            border-collapse: collapse;
            border: 1px solid black;
            border-style: groove;
            width: 100%;
        }

        table th {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        table td {
            padding-top: 5px;
            padding-bottom: 5px;
        }
    </style>
</head>

<body>
    <h1>PERMINTAAN DANA</h1>
    <div class="title">
        <!-- <span>Cumulative performance</span> -->
        <span class="tgl"><?= date('d/m/Y') ?></span>
        <span class='judul'><?= $row->JobNo . ' - ' . $row->JobNm ?></span><br>

        <?= $alokasi ?>
        <?= $cari ?>

    </div>



    <br>
    <br>

    <table border="1">
        <thead>
            <tr>
                <th>No.</th>
                <th>No.PD</th>
                <th>No.Ref <p> Lapangan</th>
                <th>Tgl PD</th>
                <th>Alokasi</th>
                <th>Type Form</th>
                <th>No KO</th>
                <th>Deskripsi</th>
                <th>Total PD (Rp)</th>
                <th>Approved By (Lapangan)</th>
                <th>Approve On</th>
            </tr>

        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($pd as $data) { ?>
                <tr>
                    <td><?= $no++ . '.' ?></td>
                    <td><?= $data->NoPD ?></td>
                    <td><?= $data->NoRef ?></td>
                    <td><?= $data->TglPD ?></td>
                    <td><?= $data->Alokasi ?></td>
                    <td><?= $data->TipeForm ?></td>
                    <td><?= $data->NoKO ?></td>
                    <td><?= $data->Deskripsi ?></td>
                    <td><?= UbahGabunganUang($data->TotalPD) ?></td>
                    <td><?= $data->ApprovedByAK ?></td>
                    <td><?= tgl_indo_waktu($data->TimeApprovedAK) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>


</body>

</html>