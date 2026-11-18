<div class="content-wrapper">
    <section class="content-header">
        <h1>Dashboard Customer <small>Ringkasan pesanan BBM</small></h1>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Progress Persiapan Akun — <?= $onboarding['percent'] ?>%</h3></div>
            <div class="box-body">
                <div class="progress"><div class="progress-bar progress-bar-success" style="width:<?= $onboarding['percent'] ?>%"><?= $onboarding['percent'] ?>%</div></div>
                <div class="row">
                    <?php foreach ($onboarding['steps'] as $index => $step): ?>
                    <div class="col-md-<?= $index === 4 ? '4' : '2' ?>">
                        <a href="<?= $step['url'] ?>" class="btn btn-block btn-<?= $step['complete']?'success':'default' ?>" style="white-space:normal;min-height:62px">
                            <i class="fa fa-<?= $step['complete']?'check-circle':'circle-o' ?>"></i>
                            <?= ($index+1).'. '.html_escape($step['title']) ?>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if (!$onboarding['document']['complete']): ?><p class="text-warning" style="margin-top:10px"><i class="fa fa-warning"></i> Lengkapi dokumen: <?= html_escape(implode(', ', $onboarding['document']['missing'])) ?>.</p><?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="small-box bg-aqua">
                    <div class="inner"><h3><?= $summary['total'] ?></h3><p>Total Pesanan</p></div>
                    <div class="icon"><i class="fa fa-file-text-o"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="small-box bg-yellow">
                    <div class="inner"><h3><?= $summary['need_payment'] ?></h3><p>Perlu Bukti Bayar</p></div>
                    <div class="icon"><i class="fa fa-upload"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="small-box bg-blue">
                    <div class="inner"><h3><?= $summary['pending'] ?></h3><p>Menunggu Approval</p></div>
                    <div class="icon"><i class="fa fa-clock-o"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="small-box bg-green">
                    <div class="inner"><h3><?= $summary['ready'] ?></h3><p>Siap Pengisian</p></div>
                    <div class="icon"><i class="fa fa-check-circle"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-7">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pesanan Terbaru</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>SPBB</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order) : ?>
                                    <tr>
                                        <td><?= $order['InvoiceNo'] ?></td>
                                        <td><?= $order['SpbbName'] ?></td>
                                        <td>Rp <?= number_format($order['TotalAmount']) ?></td>
                                        <td><span class="label label-info"><?= $order['OrderStatus'] ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (!count($orders)) : ?>
                                    <tr><td colspan="4" class="text-center">Belum ada pesanan</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer">
                        <a href="<?= site_url('Spbb/customer_history') ?>" class="btn btn-default">Lihat Semua Riwayat</a>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Alur Pemesanan</h3>
                    </div>
                    <div class="box-body">
                        <ul class="timeline">
                            <li><i class="fa fa-map-marker bg-blue"></i><div class="timeline-item"><h3 class="timeline-header">Pilih SPBB</h3><div class="timeline-body">Pilih cabang SPBB dan produk BBM yang tersedia.</div></div></li>
                            <li><i class="fa fa-shopping-cart bg-aqua"></i><div class="timeline-item"><h3 class="timeline-header">Buat Pesanan</h3><div class="timeline-body">Sistem membuat invoice sesuai quantity dan harga.</div></div></li>
                            <li><i class="fa fa-credit-card bg-yellow"></i><div class="timeline-item"><h3 class="timeline-header">Pembayaran</h3><div class="timeline-body">Transfer/QRIS wajib upload bukti bayar; Tempo/Lainnya menunggu approval.</div></div></li>
                            <li><i class="fa fa-check bg-green"></i><div class="timeline-item"><h3 class="timeline-header">Pengisian</h3><div class="timeline-body">Pengisian dapat dilakukan setelah status SIAP PENGISIAN.</div></div></li>
                        </ul>
                    </div>
                    <div class="box-footer">
                        <a href="<?= site_url('Spbb/customer_order') ?>" class="btn btn-success btn-block">Buat Pesanan Baru</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
