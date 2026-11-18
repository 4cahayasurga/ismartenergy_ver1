<div class="content-wrapper">
    <section class="content-header">
        <h1>Customer <small><?= $order_channel === 'PIPANISASI' ? 'Pemesanan melalui Pipanisasi' : 'Pemesanan melalui SPBB' ?></small></h1>
    </section>

    <section class="content">
        <?php if ($this->session->flashdata('Berhasil')) : ?>
            <div class="alert alert-success"><?= $this->session->flashdata('Berhasil') ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('Gagal')) : ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('Gagal') ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-7">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Buat Pesanan</h3>
                    </div>
                    <form action="<?= site_url('Spbb/create_order') ?>" method="post"><?php $this->load->view('pipeline/billing_token'); ?>
                        <input type="hidden" name="OrderChannel" value="<?= html_escape($order_channel) ?>">
                        <div class="box-body">
                            <?php if (!$document_status['complete']): ?><div class="alert alert-warning"><strong>Dokumen legalitas belum lengkap.</strong> Dokumen yang belum tersedia: <?= html_escape(implode(', ', $document_status['missing'])) ?>. <a href="<?= site_url('Spbb/customer_documents') ?>">Lengkapi dokumen</a>.</div><?php endif; ?>
                            <?php if ($receipt_blocked): ?><div class="alert alert-danger">Pemesanan dikunci karena masih ada pengisian selesai yang belum Anda konfirmasi pada menu Riwayat & Invoice.</div><?php endif; ?>
                            <div class="form-group">
                                <label>Kapal</label>
                                <select class="form-control" name="VesselID" required>
                                    <option value="">Pilih kapal</option>
                                    <?php foreach ($vessels as $vessel) : ?>
                                        <option value="<?= $vessel['VesselID'] ?>"><?= html_escape($vessel['VesselName'].' - '.$vessel['RegistrationNo']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (!count($vessels)) : ?><div class="alert alert-danger" style="margin-top:8px">Belum ada kapal aktif yang terhubung ke akun Anda. Pastikan registrasi perusahaan sudah disetujui beserta data kapal, atau minta Admin menghubungkan NIB dan master kapal ke akun ini.</div><?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label>Nama SPBB</label>
                                <select class="form-control" id="spbbSelector">
                                    <option value="">Pilih SPBB</option>
                                    <?php foreach ($spbb_list as $spbb) : ?>
                                        <option value="<?= $spbb['SpbbID'] ?>"><?= $spbb['SpbbName'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Jenis BBM</label>
                                <select class="form-control" name="FuelID" id="fuelSelector" required>
                                    <option value="">Pilih BBM</option>
                                    <?php foreach ($fuel_list as $fuel) : ?>
                                        <option value="<?= $fuel['FuelID'] ?>" data-spbb="<?= $fuel['SpbbID'] ?>" data-price="<?= $fuel['UnitPrice'] ?>">
                                            <?= $fuel['FuelName'] ?> - Rp <?= number_format($fuel['UnitPrice']) ?>/liter
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Quantity (Liter)</label>
                                <input type="number" step="0.01" min="1" class="form-control" name="Quantity" id="quantityInput" required>
                            </div>
                            <div class="form-group">
                                <label>Metode Pembayaran</label>
                                <select class="form-control" name="PaymentMethod" required>
                                    <?php foreach ($payment_methods as $key => $label) : ?>
                                        <option value="<?= $key ?>"><?= $label ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="callout callout-info">
                                <strong>Estimasi Invoice:</strong>
                                <div id="invoicePreview">Rp 0</div>
                            </div>
                            <div id="quotaWarning" class="alert alert-warning" style="display:none">Pesanan akan diverifikasi terhadap sisa kuota kapal dan perusahaan saat dikirim.</div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-block" <?= (!count($vessels) || $receipt_blocked) ? 'disabled' : '' ?>>Buat Pesanan <?= html_escape($order_channel) ?></button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-5">
                <div class="box box-info">
                    <div class="box-header with-border"><h3 class="box-title">Prosedur Customer</h3></div>
                    <div class="box-body">
                        <p><strong>Rekening transfer terverifikasi:</strong><br>
                            <?php if (count($authorized_accounts)): ?>
                                <?php foreach ($authorized_accounts as $account): ?>
                                    <?= html_escape($account['BankName'].' - '.$account['AccountNo'].' a.n. '.$account['AccountName']) ?><br>
                                    <small>Pihak berwenang: <?= html_escape($account['AuthorizedPersonName']) ?></small><br>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-danger">Belum tersedia. Hubungi Admin sebelum melakukan pembayaran.</span>
                            <?php endif; ?>
                        </p>
                        <ol>
                            <li>Pilih SPBB dan jenis BBM yang tersedia.</li>
                            <li>Isi quantity liter dan metode pembayaran.</li>
                            <li>Untuk Transfer/QRIS, upload bukti pembayaran dari menu Riwayat dan Invoice.</li>
                            <li>Tunggu approval pemilik SPBB.</li>
                            <li>Jika status sudah SIAP PENGISIAN, lakukan pengisian sesuai invoice.</li>
                        </ol>
                    </div>
                    <div class="box-footer">
                        <a href="<?= site_url('Spbb/customer_history') ?>" class="btn btn-default btn-block">Lihat Riwayat / Invoice</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    (function() {
        var spbbSelector = document.getElementById('spbbSelector');
        var fuelSelector = document.getElementById('fuelSelector');
        var qty = document.getElementById('quantityInput');
        var preview = document.getElementById('invoicePreview');

        function refreshFuelOptions() {
            var selectedSpbb = spbbSelector.value;
            Array.prototype.forEach.call(fuelSelector.options, function(opt) {
                if (!opt.value) return;
                opt.hidden = selectedSpbb && opt.getAttribute('data-spbb') !== selectedSpbb;
            });
            fuelSelector.value = '';
            refreshPreview();
        }

        function refreshPreview() {
            var opt = fuelSelector.options[fuelSelector.selectedIndex];
            var price = opt ? parseFloat(opt.getAttribute('data-price') || 0) : 0;
            var amount = price * parseFloat(qty.value || 0);
            preview.innerHTML = 'Rp ' + amount.toLocaleString('id-ID');
        }

        spbbSelector.addEventListener('change', refreshFuelOptions);
        fuelSelector.addEventListener('change', refreshPreview);
        qty.addEventListener('input', refreshPreview);
        qty.addEventListener('input', function(){ document.getElementById('quotaWarning').style.display = parseFloat(qty.value||0)>0 ? 'block':'none'; });
    })();
</script>
