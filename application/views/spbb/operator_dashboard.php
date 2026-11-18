<style>
.operator-screen{background:#eef3f7;min-height:100vh;padding:18px}.operator-hero{background:#16324f;color:#fff;padding:22px;border-left:6px solid #19a974;margin-bottom:16px}.operator-hero h1{margin:0 0 6px;font-size:28px}.operator-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:18px}.operator-stat{background:#fff;border:1px solid #d8e1e8;padding:18px;text-align:center}.operator-stat strong{display:block;font-size:32px;color:#16324f}.operator-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.order-touch{background:#fff;border:2px solid #dce5ec;padding:18px;box-shadow:0 4px 10px rgba(0,0,0,.06)}.order-touch.active-fill{border-color:#1687d9}.order-head{display:flex;justify-content:space-between;gap:12px;border-bottom:1px solid #e5ebef;padding-bottom:12px;margin-bottom:12px}.order-head h3{margin:0;font-size:20px}.order-volume{font-size:30px;font-weight:700;color:#16324f}.touch-btn{min-height:58px;width:100%;font-size:18px;font-weight:700;border-radius:6px}.operator-section{background:#fff;padding:18px;margin-top:18px;border-top:4px solid #1687d9}.operator-section h2{font-size:22px;margin:0 0 15px}.complete-controls{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin:14px 0}.complete-controls .form-control{height:52px;font-size:18px}@media(max-width:800px){.operator-stats,.operator-grid{grid-template-columns:1fr}.operator-hero h1{font-size:23px}.complete-controls{grid-template-columns:1fr}}
</style>
<div class="content-wrapper">
<div class="operator-screen">
    <div class="operator-hero">
        <h1><i class="fa fa-tint"></i> Operator Pengisian BBM</h1>
        <div><?= html_escape($this->session->userdata('MIS_LOGGED_NAME')) ?> · <?= date('d-m-Y H:i') ?></div>
    </div>
    <?php if ($this->session->flashdata('Berhasil')) : ?><div class="alert alert-success"><?= html_escape($this->session->flashdata('Berhasil')) ?></div><?php endif; ?>
    <?php if ($this->session->flashdata('Gagal')) : ?><div class="alert alert-danger"><?= html_escape($this->session->flashdata('Gagal')) ?></div><?php endif; ?>
    <?php if($this->pipeline_access->allowed('operator_company')): ?>
    <div class="box box-primary"><div class="box-body"><a class="btn btn-primary" href="<?= site_url('Pipeline_operator_company') ?>">Nama, Identitas &amp; Logo Perusahaan</a></div></div><?php endif; ?>
    <div class="operator-stats">
        <div class="operator-stat"><strong><?= count($waiting_orders) ?></strong>Menunggu Pengisian</div>
        <div class="operator-stat"><strong><?= count($active_orders) ?></strong>Sedang Diisi</div>
        <div class="operator-stat"><strong><?= count($today_completed) ?></strong>Selesai Hari Ini</div>
    </div>

    <?php if($can_queue&&$operator_menu!=='shift'): ?><section class="operator-section" id="pengisian">
        <h2><i class="fa fa-tint text-blue"></i> Sedang Pengisian</h2>
        <div class="operator-grid">
        <?php foreach ($active_orders as $order) : ?>
            <article class="order-touch active-fill">
                <div class="order-head"><h3><?= html_escape($order['InvoiceNo']) ?></h3><span class="label label-primary">SEDANG DIISI</span></div>
                <p><strong><?= html_escape($order['SpbbName']) ?></strong><br><?= html_escape($order['CustomerID']) ?> · <?= html_escape($order['FuelName']) ?></p>
                <div class="order-volume"><?= number_format($order['Quantity'], 0, ',', '.') ?> L</div>
                <form action="<?= site_url('Spbb_operator/complete/' . $order['OrderID']) ?>" method="post" onsubmit="return confirm('Pastikan pengisian sudah benar-benar selesai. Lanjutkan?')">
                    <?php $this->load->view('pipeline/billing_token'); ?><div class="complete-controls">
                        <input type="number" name="ActualQuantity" class="form-control" value="<?= (float) $order['Quantity'] ?>" min="0.01" max="<?= (float) $order['Quantity'] ?>" step="0.01" required>
                        <select name="ShiftCode" class="form-control" required>
                            <option value="">Pilih Shift</option><option value="SHIFT_1">Shift 1</option><option value="SHIFT_2">Shift 2</option><option value="SHIFT_3">Shift 3</option>
                        </select>
                    </div>
                    <button class="btn btn-success touch-btn"><i class="fa fa-check-circle"></i> SELESAI PENGISIAN</button>
                </form>
            </article>
        <?php endforeach; ?>
        <?php if (!count($active_orders)) : ?><p class="text-muted">Tidak ada pengisian aktif.</p><?php endif; ?>
        </div>
    </section>

    <section class="operator-section" id="antrean">
        <h2><i class="fa fa-list text-blue"></i> Antrean Siap Pengisian</h2>
        <div class="operator-grid">
        <?php foreach ($waiting_orders as $order) : ?>
            <article class="order-touch">
                <div class="order-head"><h3><?= html_escape($order['InvoiceNo']) ?></h3><span class="label label-warning">SIAP</span></div>
                <p><strong><?= html_escape($order['SpbbName']) ?></strong><br><?= html_escape($order['CustomerID']) ?> · <?= html_escape($order['FuelName']) ?></p>
                <div class="order-volume"><?= number_format($order['Quantity'], 0, ',', '.') ?> L</div>
                <form action="<?= site_url('Spbb_operator/start/' . $order['OrderID']) ?>" method="post" onsubmit="return confirm('Mulai pengisian pesanan ini?')">
                    <?php $this->load->view('pipeline/billing_token'); ?><button class="btn btn-primary touch-btn"><i class="fa fa-play"></i> MULAI PENGISIAN</button>
                </form>
            </article>
        <?php endforeach; ?>
        <?php if (!count($waiting_orders)) : ?><p class="text-muted">Belum ada pesanan yang dikonfirmasi administrator.</p><?php endif; ?>
        </div>
    </section>

    <?php endif; ?><?php if($can_shift&&$operator_menu!=='queue'): ?><section class="operator-section" id="rekap">
        <h2><i class="fa fa-bar-chart text-blue"></i> Rekap Penjualan per Shift</h2>
        <form method="get" action="<?= site_url('Spbb_operator/shift') ?>" class="form-inline" style="margin-bottom:15px">
            <input type="date" name="date_from" value="<?= html_escape($date_from) ?>" class="form-control">
            <input type="date" name="date_to" value="<?= html_escape($date_to) ?>" class="form-control">
            <button class="btn btn-primary"><i class="fa fa-filter"></i> Tampilkan</button>
        </form>
        <div class="table-responsive"><table class="table table-bordered table-striped js-app-datatable">
            <thead><tr><th>Tanggal</th><th>SPBB</th><th>Shift</th><th>Operator</th><th>Transaksi</th><th>Volume</th><th>Nilai</th></tr></thead>
            <tbody><?php foreach ($shift_recap as $row) : ?><tr>
                <td><?= html_escape($row['SalesDate']) ?></td><td><?= html_escape($row['SpbbName']) ?></td><td><?= html_escape($row['ShiftCode']) ?></td><td><?= html_escape($row['CompletedBy']) ?></td>
                <td><?= number_format($row['TransactionCount']) ?></td><td><?= number_format($row['TotalQuantity'], 2, ',', '.') ?> L</td><td>Rp <?= number_format($row['TotalAmount'], 0, ',', '.') ?></td>
            </tr><?php endforeach; ?></tbody>
        </table></div>
    </section><?php endif; ?>
</div>
</div>
