<div class="content-wrapper">
    <section class="content-header">
        <h1>Approval Pesanan <?= html_escape($order_channel) ?> <small>Verifikasi pembayaran dan konfirmasi pengisian</small></h1>
    </section>

    <section class="content">
        <?php if ($this->session->flashdata('Berhasil')) : ?>
            <div class="alert alert-success"><?= $this->session->flashdata('Berhasil') ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('Gagal')) : ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('Gagal') ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" id="approval-pesanan">
                    <div class="box-header with-border"><h3 class="box-title">Verifikasi Pesanan</h3></div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped js-app-datatable" data-order-column="0" data-order-direction="desc">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>SPBB</th>
                                    <th>BBM</th>
                                    <th>Identitas Kapal</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th>Bayar</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order) : ?>
                                    <tr>
                                        <td data-order="<?= (int)$order['OrderID'] ?>"><?= $order['InvoiceNo'] ?></td>
                                        <td><?= $order['SpbbName'] ?></td>
                                        <td><?= $order['FuelName'] ?></td>
                                        <td>
                                            <strong><?= html_escape($order['VesselName'] ?: '-') ?></strong><br>
                                            Registrasi: <?= html_escape($order['RegistrationNo'] ?: '-') ?><br>
                                            Jenis/GT: <?= html_escape($order['VesselType'] ?: '-') ?> / <?= number_format((float)$order['GrossTonnage'],2) ?><br>
                                            Badan Usaha: <?= html_escape($order['CompanyName'] ?: '-') ?><br>
                                            NIB: <?= html_escape($order['NIB'] ?: '-') ?><br>
                                            Sisa Kuota: <?= number_format((float)$order['VesselRemainingQuota'],2) ?> L
                                        </td>
                                        <td><?= number_format($order['Quantity'], 2) ?></td>
                                        <td>Rp <?= number_format($order['TotalAmount']) ?></td>
                                        <td>
                                            <?= $order['PaymentMethod'] ?><br>
                                            Kanal: <?= html_escape($order['OrderChannel']) ?><br>
                                            <?php if ($order['ProofFile']) : ?>
                                                <a target="_blank" href="<?= base_url('uploads/spbb_payments/' . $order['ProofFile']) ?>">Bukti bayar</a>
                                                <br><small>Rekening: <?= html_escape($order['OriginBankName'].' / '.$order['OriginAccountNo'].' / '.$order['OriginAccountName']) ?><br>
                                                Validasi rekening: <?= html_escape($order['AccountValidationStatus']) ?><br>
                                                OCR: <strong><?= html_escape($order['OcrValidationStatus']) ?></strong><br>
                                                Rekening asal <?= $order['OcrAccountNoFound']?'✓ penuh':($order['OcrSourceAccountMasked']?'✓ tersamarkan':'✗') ?> ·
                                                Tujuan <?= $order['OcrDestinationAccountFound']?'✓':'✗' ?> · Penerima <?= $order['OcrDestinationNameFound']?'✓':'✗' ?> ·
                                                Nominal <?= $order['OcrAmountFound']?'✓':'✗' ?> · Status <?= $order['OcrTransferSuccessFound']?'✓':'✗' ?> · Referensi <?= $order['OcrReferenceFound']?'✓':'✗' ?><br>
                                                Metode: <?= html_escape($order['OcrValidationMethod']) ?><br>
                                                Referensi: <?= html_escape($order['OcrReferenceNo'] ?: '-') ?> <?= $order['OcrReferenceDuplicate']?'(DUPLIKAT)':'' ?><br>
                                                Nominal bukti/invoice: Rp <?= number_format((float)$order['OcrDetectedAmount']) ?> / Rp <?= number_format((float)$order['OcrExpectedAmount']) ?></small>
                                                <?php if(!empty($order['OcrExtractedText'])): ?><details><summary><small>Lihat teks hasil OCR</small></summary><pre style="white-space:pre-wrap;max-width:320px;font-size:10px"><?= html_escape($order['OcrExtractedText']) ?></pre></details><?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="label label-info"><?= $order['OrderStatus'] ?></span></td>
                                        <td>
                                            <?php if ($order['OwnerApprovalStatus'] === 'PENDING') : ?>
                                                <form action="<?= site_url('Spbb/approve_order/' . $order['OrderID']) ?>" method="post">
                                                    <textarea class="form-control input-sm" name="OwnerNotes" placeholder="Catatan"></textarea>
                                                    <button name="Status" value="APPROVED" class="btn btn-xs btn-success" style="margin-top:5px;" <?= in_array($order['PaymentMethod'],array('TRANSFER','QRIS')) && $order['OcrValidationStatus']!=='AUTOMATIC_MATCH'?'disabled title="Bukti belum lolos validasi otomatis"':'' ?>>Approve</button>
                                                    <button name="Status" value="REJECTED" class="btn btn-xs btn-danger" style="margin-top:5px;">Tolak</button>
                                                </form>
                                            <?php elseif ($order['OrderStatus'] === 'SIAP_PENGISIAN') : ?>
                                                <span class="label label-warning">Menunggu Operator</span>
                                            <?php elseif ($order['OrderStatus'] === 'SEDANG_PENGISIAN') : ?>
                                                <span class="label label-primary">Diisi oleh <?= html_escape($order['OperatorStartedBy']) ?></span>
                                            <?php else : ?>
                                                <?= $order['OwnerApprovalStatus'] ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-5"><div class="box box-success"><div class="box-header"><h3 class="box-title">Summary Kuota per Fuel Terminal</h3></div><div class="box-body table-responsive">
                <table class="table table-bordered"><thead><tr><th>Terminal</th><th>Kapal</th><th>Kuota</th><th>Terpakai</th><th>Sisa</th></tr></thead><tbody>
                <?php foreach($terminal_summary as $r): ?><tr><td><?= html_escape($r['No_SPBB'].' — '.$r['Area']) ?></td><td><?= $r['VesselCount'] ?></td><td><?= number_format($r['AllocatedQuota'],2) ?></td><td><?= number_format($r['UsedQuota'],2) ?></td><td><?= number_format($r['RemainingQuota'],2) ?></td></tr><?php endforeach; ?>
                </tbody></table>
            </div></div></div>
            <div class="col-md-7"><div class="box box-danger"><div class="box-header"><h3 class="box-title">Ranking Pemakaian Kapal</h3></div><div class="box-body table-responsive">
                <table class="table table-bordered"><thead><tr><th>#</th><th>Kapal / Badan Usaha</th><th>Terminal</th><th>Kuota</th><th>Terpakai</th><th>%</th><th>Analisa</th></tr></thead><tbody>
                <?php foreach($vessel_ranking as $i=>$r): $p=(float)$r['UsagePercent']; ?><tr class="<?= $p>100?'danger':($p>=90?'warning':'') ?>"><td><?= $i+1 ?></td><td><strong><?= html_escape($r['VesselName']) ?></strong><br><?= html_escape($r['CompanyName']) ?></td><td><?= html_escape($r['No_SPBB']) ?></td><td><?= number_format($r['AllocatedQuota'],2) ?></td><td><?= number_format($r['UsedQuota'],2) ?></td><td><?= number_format($p,1) ?>%</td><td><?= $p>100?'MELEBIHI KUOTA':($p>=90?'HAMPIR HABIS':'NORMAL') ?></td></tr><?php endforeach; ?>
                </tbody></table>
            </div></div></div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border"><h3 class="box-title">Ringkasan Quota SPBB</h3></div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered">
                            <thead><tr><th>No SPBB</th><th>Area</th><th>Quota</th><th>Booking</th><th>Terpakai</th><th>Sisa</th></tr></thead>
                            <tbody>
                                <?php foreach ($quota_summary as $quota) : ?>
                                    <tr>
                                        <td><?= $quota['No_SPBB'] ?></td>
                                        <td><?= $quota['Area'] ?></td>
                                        <td><?= number_format($quota['Vol_Quota'], 2) ?></td>
                                        <td><?= number_format($quota['Vol_Booking'], 2) ?></td>
                                        <td><?= number_format($quota['Vol_Terpakai'], 2) ?></td>
                                        <td><?= number_format($quota['Sisa_Quota'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
