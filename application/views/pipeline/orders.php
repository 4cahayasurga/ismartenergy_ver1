<div class="content-wrapper"><section class="content-header"><h1>Pesanan Pipanisasi</h1></section><section class="content">
<div class="box box-primary"><div class="box-body">
<form method="get" action="<?= site_url('Pipanisasi/orders') ?>"><?php $this->load->view('pipeline/period'); ?></form></div></div>
<?php $this->load->view('pipeline/flow_rows',compact('rows','statuses')); ?>
<div class="box box-default"><div class="box-body"><h3>Riwayat Pesanan Sebelumnya</h3>
<div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Pesanan</th><th>Tanggal</th><th>Status</th><th>Volume (liter)</th></tr></thead><tbody>
<?php foreach ($orders as $row): ?><tr><td><?= html_escape($row['InvoiceNo']) ?></td><td><?= html_escape($row['CreatedAt']) ?></td><td><?= html_escape($row['OrderStatus']) ?></td><td><?= number_format($row['Quantity'], 3, ',', '.') ?></td></tr><?php endforeach; ?>
<?php if (!$orders): ?><tr><td colspan="4" class="text-center">Tidak ada pesanan pada periode ini.</td></tr><?php endif; ?>
</tbody></table></div></div></div></section></div>
