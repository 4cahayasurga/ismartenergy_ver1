<style>
    .box1 {
        width: 10px;
        height: 10px;
        background-color: #2ecc71;
    }

    .box2 {
        width: 10px;
        height: 10px;
        background-color: #f1c40f;
    }

    .box3 {
        width: 10px;
        height: 10px;
        background-color: #e74c3c;
    }
</style>

<div class="content-wrapper">

   <section class="content-header">
    <h1>
        <label class="col-lg col-form-label"><?php echo $judul ?> </label>
        <p></p>
        <label class="col-sm col-form-label"><?php echo $spr->JobNo ?></label> - <label><?php echo $spr->JobNm ?></label>
        <br><small><label class="col-sm col-form-label"><?php echo $spr->Lokasi ?></label>

        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?php echo $judul ?></li>
        </ol>
    </section>

    <section class="content-header">

        <a href="<?= site_url('Approved_spr/view/' . $spr->JobNo) ?>" class="btn btn-warning btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>

    </section>


    <section class="content">

        <div class="box">
            <!-- <div class="box-header">APprobe</div> -->

            <div class="box-body">

                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header bg-purple-gradient">
                            <h6>INFORMASI DASAR</h6>
                        </div>
                        <div class="box">
                            <div class="col-xs-6 row">
                                <label>Job No</label>
                                <input type="text" class="form-control form-control-sm" value="<?=$spr->JobNo . " - " . $spr->JobNm ?>" disabled>

                                <label>No SPR</label>
                                <input type="text" name="NoSpr" id="NoSpr" value="<?= $row->NoSPR ?>" class="form-control form-control-sm" disabled>

                                <label>Tgl SPR</label>
                                <input type="date" value="<?= $row->TglSPR ?>" name="TglSpr" id="TglSpr" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" required="required" disabled>
                            </div>

                            <div class="col-xs-6">

                                <label>Kepada</label>
                                <input type="text" name="Kepada" id="Kepada" class="form-control form-control-sm" value="<?= $row->Kepada ?>" required="required" disabled>

                                <label>Tgl Penggunaan</label>
                                <input type="Date" name="TglPenggunaan" id="TglPenggunaan form-control-sm" value="<?= $row->TglPenggunaan ?>" class="form-control" required="required" disabled>

                                <label>Untuk Pekerjaan</label>
                                <textarea class="form-control form-control-sm" name="UtkPekerjaan" id="UtkPekerjaan" disabled><?= $row->UtkPekerjaan ?></textarea>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xs-12" style="padding-top:10px;">

                    <div class="box">
                        <div class="box-header bg-blue-gradient" >
                            <h6>INFORMASI PENGIRIMAN</h6>
                        </div>
                        <div class="box-body">

                            <div class="col-xs-6 row">

                                <label>Nama PIC Logistik</label>
                                <input type="text" name="NamaKirim" id="NamaKirim" class="form-control form-control-sm" value="<?= $row->NamaKirim ?>" disabled>

                                <label>Telepon / HP</label>
                                <input type="text" name="TeleponKirim" id="TeleponKirim" class="form-control form-control-sm" value="<?= $row->TeleponKirim ?>" required="required" disabled>

                            </div>

                            <div class="col-xs-6">

                                <label>Alamat Dikirim</label>
                                <textarea class="form-control form-control-sm" name="AlamatKirim" id="AlamatKirim" disabled><?= $row->AlamatKirim ?></textarea>

                                <label>Waktu Pengiriman</label>
                                <input type="Date" name="TglKirim" id="TglKirim" class="form-control form-control-sm" value="<?= $row->TglKirim ?>" required="required" disabled>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="box">

                        <div class="box-body">

                            <table class="table">
                                <tr>
                                    <td>
                                        <div class="box1"></div>Permintaan Anda MELEBIHI 80% Dari RAP
                                    </td>
                                    <td>
                                        <div class="box2"></div>Permintaan Anda Sudah Batas Maximal RAP
                                    </td>
                                    <td>
                                        <div class="box3"></div>Permintaan Anda Melebihi RAP
                                    </td>
                                </tr>
                            </table>

                            <div class="col-xs-12 row">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover table-condesed" id="istable">
                                        <thead>
                                            <tr>
                                                <th style="background-color: #7ed6df;padding-top:10px;padding-bottom:10px;">No.</th>
                                                <th style="background-color: #7ed6df;padding-top:10px;padding-bottom:10px;">Kode RAP</th>
                                                <th style="background-color: #7ed6df;padding-top:10px;padding-bottom:10px;">Uraian</th>
                                                <th style="background-color: #7ed6df;padding-top:10px;padding-bottom:10px;">Vol</th>
                                                <th style="background-color: #7ed6df;padding-top:10px;padding-bottom:10px;">Satuan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="detail-table">

                                            <?php 
                                            if ($data->num_rows() > 0 ) {
                                                $notif = '';
                                                $TotalRAP = 0;
                                                $TotalSPR = 0;
                                                $persenRAP = 0;
                                                $persenSPR = 0;
                                                foreach($data->result() as $detail ){

                                                    $TotalRAP = $detail->TotalRAP;
                                                    $TotalSPR = $detail->Total;

                                                    $persenRAP = ($TotalRAP * 80) / 100;
                                                    $persenSPR = ($TotalSPR * 80) / 100;




                                                    if ($persenSPR >= $persenRAP) {
                                                        $notif = 'style="background-color: #2ecc71;color:white; "';
                                                    }

                                                    if ($TotalSPR == $TotalRAP) {
                                                        $notif = 'style="background-color: #f1c40f;color:white; "';
                                                    }

                                                    if ($TotalSPR > $TotalRAP) {
                                                        $notif = 'style="background-color: #e74c3c;color:white; "';
                                                    }

                                                    if ($TotalSPR < $TotalRAP) {
                                                        $notif = '';
                                                    }
                                                    ?>

                                                    <tr <?= $notif ?>>

                                                        <td style="padding-top:10px;padding-bottom:10px;"><?= $detail->NoUrut ?></td>
                                                        <td style="padding-top:10px;padding-bottom:10px;"><?= $detail->KdRAP ?></td>
                                                        <td style="padding-top:10px;padding-bottom:10px;"><?= $detail->Uraian ?></td>
                                                        <td style="padding-top:10px;padding-bottom:10px;"><?= floatval($detail->Vol) ?></td>
                                                        <td style="padding-top:10px;padding-bottom:10px;"><?= $detail->Uom ?></td>

                                                    </tr>

                                                    <?php 

                                                }
                                            }else{
                                                echo '<tr><td colspan="6" style="text-align:center;">Tidak Ada Data</td></tr>';
                                            }

                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </section>


</div>