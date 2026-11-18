<style type="text/css">
    .ubah_ukuran {
        font-size: 10px !important;
        font-family: Arial, Helvetica, sans-serif;

    }

    th {
        white-space: nowrap;
    }
</style>

<div class="box">

    <div class="box-header">
        <a href="<?= site_url('Laporan/Rkp_pembayaran/print_rkp_pembayaran/' . $JobNo . '/' . $dari_tgl . '/' . $sampai_tgl) ?>" target="_blank" class="btn btn-sm btn-danger btn_print">Print <i class="fa fa-print"></i></a>

    </div>

    <div class="box-body">


        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover table-condesed" id="initable">
                <thead style="font-size:10px !important;font-family: Arial, Helvetica, sans-serif;">
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode RAP</th>
                        <th>Uraian</th>
                        <th>Uom</th>
                        <th>Vol</th>
                        <th>Harga Satuan</th>
                        <th>Total</th>
                        <th>Sudah Terserap</th>
                        <th>%</th>
                        <th>Sisa RAP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $Total = 0;
                    $total_terserap = 0;
                    $bg_header = '';
                    $Uom = '';
                    $Vol = 0;
                    $HrgSatuan = 0;
                    $SudahTerserap = 0;
                    foreach ($data_penyerapan_rap as $data_rap) {
                        $KdRAP = $data_rap['KdRAP'];
                        $Tipe = $data_rap['Tipe'];
                        // $SudahTerserap = dapat_total_serap_rap($JobNo, $Alokasi, $KdRAP, $dari_tgl, $sampai_tgl, $Tipe);

                        if ($data_rap['Tipe'] == 'Header') {
                            $bg_header = 'style="background-color:#7f8c8d;"';

                            $Uom = '';
                            $Vol = 0;
                            $HrgSatuan = 0;
                            $Total += $data_rap['HrgSatuan'] * $data_rap['Vol'];
                        } else {
                            $bg_header = '';

                            $Uom = $data_rap['Uom'];
                            $Vol = $data_rap['Vol'];
                            $HrgSatuan = $data_rap['HrgSatuan'];
                            $Total = $HrgSatuan * $Vol;
                        }



                    ?>
                        <tr <?= $bg_header ?>>
                            <td align="center"><?= $no++ ?></td>
                            <td><?= $data_rap['KdRAP'] ?></td>
                            <td><?= $data_rap['Uraian'] ?></td>
                            <td><?= $Uom ?></td>
                            <td><?= ($Vol == 0) ? '' : number_format($Vol, 2, '.', ',') ?></td>
                            <td><?= ($HrgSatuan == 0) ? '' : number_format($HrgSatuan, 2, '.', ',') ?></td>
                            <td><?= number_format($Total, 2, '.', ',') ?></td>
                            <td><?= coba_coba($JobNo, $Alokasi, $KdRAP, $dari_tgl, $sampai_tgl, $Tipe) ?></td>
                            <td>persen</td>
                            <td>sisa rap</td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>

        </div>

    </div>

</div>