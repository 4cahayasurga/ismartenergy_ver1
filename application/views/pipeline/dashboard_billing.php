<?php
    $vessels=array(); $monthly=array();
    foreach($deliveries as $delivery) {
        $vessel=$delivery['VesselName']; $month=substr($delivery['DeliveryDate'],0,7);
        $vessels[$vessel]=isset($vessels[$vessel])?$vessels[$vessel]+$delivery['Quantity']:$delivery['Quantity'];
        $monthly[$month]=isset($monthly[$month])?$monthly[$month]+$delivery['Quantity']:$delivery['Quantity'];
    }
    arsort($vessels); ksort($monthly);
?>
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">Biaya Jasa Administrasi / Fasilitas BBM</h3>
    </div>
    <div class="box-body">

    <?php if($billing_ready): ?>
        <div class="row">
            <?php foreach(array('Jasa BBM (Rp)'=>$cost['amount'],'PPN Jasa BBM (Rp)'=>$cost['tax'],'Total Estimasi (Rp)'=>$cost['amount']+$cost['tax']) as $label=>$amount): ?>
                <div class="col-sm-4">
                    <h4><?= html_escape($label) ?></h4>
                    <strong style="font-size:24px">
                        <?= number_format($amount,2,',','.') ?>
                    </strong>
                </div>
            <?php endforeach; ?>
        </div>

    <p>Estimasi berdasarkan tanggal DO dan harga berlaku; 1 TON = 1.000 liter. 
        <?= (int)$cost['pending'] ?> DO belum lolos validasi dokumen. 
        <?= (int)$cost['missing_rates'] ?> DO belum memiliki tarif dan belum masuk estimasi. Nilai final tersimpan saat invoice diterbitkan.</p>

        <a class="btn btn-default" href="<?= site_url('Pipeline_billing/documents').'?'.html_escape(http_build_query(array('start'=>$start,'end'=>$end))) ?>">Dokumen DO / SPP / LO</a>

        <?php if($this->pipeline_access->allowed('invoices')): ?>
            <a class="btn btn-success" href="<?= site_url('Pipeline_billing/invoices') ?>">Create Invoice</a>
        <?php endif; ?>

        <?php else: ?><p>Aktifkan migrasi tagihan untuk menampilkan biaya dan dokumen.</p><?php endif; ?>

    </div>
</div>
<div class="row">
    <?php foreach(array('Realisasi DO per Bulan'=>$monthly,'Total Realisasi DO per Kapal'=>$vessels) as $title=>$values): ?>
        <div class="col-md-6"><div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><?= html_escape($title) ?></h3>
            </div>
            <div class="box-body">

            <?php $max=$values?max($values):1; foreach($values as $label=>$value): ?>
                <div style="margin-bottom:10px">
                    <span><?= html_escape($label) ?></span>
                    <strong class="pull-right"><?= number_format($value,3,',','.') ?> L</strong>
                    <div class="progress" style="margin:4px 0">
                        <div class="progress-bar progress-bar-info" style="width:<?= $max>0?round(100*$value/$max,2):0 ?>%"></div></div></div><?php endforeach; ?>
<?php if(!$values): ?>
    <p>Tidak ada realisasi DO pada periode terpilih.</p>
    <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<div class="box">
    <div class="box-header">
        <h3 class="box-title">Monitoring Pengiriman per Kapal</h3>
    </div>
    <div class="box-body table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kapal</th>
                    <th>Total DO (liter)</th>
                    <th>Dasar Jasa (TON)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($vessels as $name=>$value): ?>
                <tr>
                    <td><?= html_escape($name) ?></td>
                    <td><?= number_format($value,3,',','.') ?></td>
                    <td><?= number_format($value/1000,6,',','.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="box">
    <div class="box-header">
        <h3 class="box-title">Monitoring Pembelian dan Penggunaan per Kapal</h3>
    </div>
    <div class="box-body table-responsive">
        <p>Pembelian/realisasi untuk pesanan dibuat dalam periode. Alokasi, booking, dan sisa adalah posisi kuota terkini dari kuota terkait.</p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kapal / Perusahaan</th>
                    <th>Alokasi</th>
                    <th>Pembelian</th>
                    <th>Booking</th>
                    <th>Realisasi</th>
                    <th>Sisa Kuota</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($monitoring as $row): ?>
                <tr>
                    <td><?= html_escape($row['VesselName'].' / '.$row['CompanyName']) ?></td>
                    <?php foreach(array($row['Allocated'],$row['Purchased'],$row['Booked'],$row['Realized'],$row['Allocated']-$row['Booked']-$row['Used']) as $value): ?>
                    <td><?= number_format($value,3,',','.') ?> L</td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
                <?php if(!$monitoring): ?>
                <tr>
                    <td colspan="6">Tidak ada pesanan dalam periode.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="box">
    <div class="box-header">
        <h3 class="box-title">Jurnal Stock Movement Terbaru — Pipanisasi</h3>
    </div>
    <div class="box-body table-responsive">
        <p>Maksimal 100 mutasi yang terhubung ke pesanan Pipanisasi pada periode terpilih.</p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Tipe</th>
                    <th>Perusahaan / Kapal</th>
                    <th>Referensi</th>
                    <th>Material</th>
                    <th>Liter</th>
                    <th>Petugas</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($movements as $row): ?>
                <tr>
                    <td><?= html_escape($row['CreatedAt']) ?></td>
                    <td><?= html_escape($row['MovementType']) ?></td>
                    <td><?= html_escape($row['CompanyName'].' / '.$row['VesselName']) ?></td>
                    <td><?= html_escape($row['ReferenceNo']) ?></td>
                    <td><?= html_escape($row['FuelName']) ?></td>
                    <td><?= number_format(abs($row['Quantity']),3,',','.') ?></td>
                    <td><?= html_escape($row['CreatedBy']) ?></td>
                    <td><?= html_escape($row['Notes']) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if(!$movements): ?>
                <tr>
                    <td colspan="8">Tidak ada mutasi stock dalam periode.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>