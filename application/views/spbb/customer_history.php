<div class="content-wrapper">
    <section class="content-header">
        <h1>Riwayat Pengisian dan Status <small>Customer</small></h1>
    </section>

    <section class="content">
        <?php if ($this->pipeline_access->allowed('customer_order')): ?>
            <h2>Pengisian BBM Pipanisasi</h2>
            <?php $this->load->view('pipeline/flow_rows',array('rows'=>$pipeline_history)); ?>
        <?php endif; ?>
        <?php if ($this->session->flashdata('Berhasil')) : ?>
            <div class="alert alert-success"><?= $this->session->flashdata('Berhasil') ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('Gagal')) : ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('Gagal') ?></div>
        <?php endif; ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Daftar Pesanan</h3>
                <div class="box-tools">
                    <a href="<?= site_url('Spbb/customer_order') ?>" class="btn btn-sm btn-primary">Pesan Baru</a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped js-app-datatable">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>SPBB</th>
                            <th>BBM</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Penerimaan</th>
                            <th>Bukti Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order) : ?>
                            <tr>
                                <td><?= $order['InvoiceNo'] ?></td>
                                <td><?= $order['SpbbName'] ?></td>
                                <td><?= $order['FuelName'] ?></td>
                                <td><?= number_format($order['Quantity'], 2) ?></td>
                                <td>Rp <?= number_format($order['TotalAmount']) ?></td>
                                <td><?= $order['PaymentMethod'] ?></td>
                                <td><span class="label label-info"><?= $order['OrderStatus'] ?></span></td>
                                <td>
                                    <?php if ($order['OrderStatus'] === 'SELESAI' && $order['ReceiptConfirmationStatus'] !== 'CONFIRMED'): ?>
                                        <form method="post" action="<?= site_url('Spbb/confirm_receipt/'.$order['OrderID']) ?>"><?php $this->load->view('pipeline/billing_token'); ?>
                                            <textarea name="ReceiptNotes" class="form-control input-sm" placeholder="Catatan penerimaan"></textarea>
                                            <button class="btn btn-xs btn-success" onclick="return confirm('Konfirmasi BBM sudah diterima sesuai realisasi?')">Konfirmasi Diterima</button>
                                        </form>
                                    <?php elseif ($order['ReceiptConfirmationStatus'] === 'CONFIRMED'): ?>
                                        <span class="label label-success">DITERIMA</span>
                                    <?php else: ?>-<?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($order['PaymentProofRequired'] && (!$order['ProofFile'] || $order['OcrValidationStatus'] !== 'AUTOMATIC_MATCH')) : ?>
                                        <?php if ($order['ProofFile']): ?><a target="_blank" href="<?= base_url('uploads/spbb_payments/' . $order['ProofFile']) ?>">Lihat bukti yang tidak sesuai</a><br><small class="text-danger">Silakan upload ulang bukti yang jelas dan sesuai.</small><?php endif; ?>
                                        <form action="<?= site_url('Spbb/upload_payment/' . $order['OrderID']) ?>" method="post" enctype="multipart/form-data"><?php $this->load->view('pipeline/billing_token'); ?>
                                            <select name="OriginAccountNo" class="form-control input-sm verified-payment-account" required>
                                                <option value="">Pilih rekening asal terverifikasi</option>
                                                <?php foreach ($authorized_accounts as $account): ?>
                                                    <option value="<?= html_escape($account['AccountNo']) ?>" data-bank="<?= html_escape($account['BankName']) ?>" data-name="<?= html_escape($account['AccountName']) ?>">
                                                        <?= html_escape($account['BankName'].' - '.$account['AccountNo'].' - '.$account['AccountName']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <input type="hidden" name="OriginBankName">
                                            <input type="hidden" name="OriginAccountName">
                                            <input type="file" name="ProofFile" class="form-control input-sm" accept=".jpg,.jpeg,.png" required>
                                            <button class="btn btn-xs btn-primary" style="margin-top:5px;">Upload</button>
                                        </form>
                                    <?php elseif ($order['ProofFile']) : ?>
                                        <a target="_blank" href="<?= base_url('uploads/spbb_payments/' . $order['ProofFile']) ?>">Lihat bukti</a><br>
                                        <small>Rekening asal: <?= html_escape($order['OriginAccountNo']) ?><br>
                                        Validasi rekening: <?= html_escape($order['AccountValidationStatus']) ?><br>
                                        OCR: <strong><?= html_escape($order['OcrValidationStatus']) ?></strong><br>
                                        Rekening asal <?= $order['OcrAccountNoFound']?'✓ penuh':($order['OcrSourceAccountMasked']?'✓ tersamarkan':'✗') ?> ·
                                        Tujuan <?= $order['OcrDestinationAccountFound']?'✓':'✗' ?> ·
                                        Penerima <?= $order['OcrDestinationNameFound']?'✓':'✗' ?> ·
                                        Nominal <?= $order['OcrAmountFound']?'✓':'✗' ?> ·
                                        Status <?= $order['OcrTransferSuccessFound']?'✓':'✗' ?> ·
                                        Referensi <?= $order['OcrReferenceFound']?'✓':'✗' ?></small>
                                    <?php else : ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!count($orders)) : ?>
                            <tr><td colspan="9" class="text-center">Belum ada pesanan</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
<script>
(function () {
    document.addEventListener('change', function (event) {
        if (!event.target.classList.contains('verified-payment-account')) return;
        var option = event.target.options[event.target.selectedIndex];
        var form = event.target.closest('form');
        form.querySelector('[name="OriginBankName"]').value = option.getAttribute('data-bank') || '';
        form.querySelector('[name="OriginAccountName"]').value = option.getAttribute('data-name') || '';
    });
})();
</script>
