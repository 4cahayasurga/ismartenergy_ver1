<div class="content-wrapper"><section class="content-header"><h1>Dokumen DO / SPP / Loading Order</h1></section><section class="content">
<div class="box box-primary"><div class="box-body"><form method="get"><?php $this->load->view('pipeline/period'); ?></form>
<div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Tanggal DO</th><th>Nomor DO</th><th>Kapal</th><th>Liter</th><th>Dokumen</th></tr></thead><tbody>
<?php foreach($deliveries as $row): ?><tr><td><?= html_escape($row['DeliveryDate']) ?></td><td><?= html_escape($row['DONumber']) ?></td><td><?= html_escape($row['VesselName']) ?></td><td><?= number_format($row['Quantity'],3,',','.') ?></td><td><a class="btn btn-primary btn-sm" href="<?= site_url('Pipeline_billing/documents/'.$row['DeliveryID']) ?>">Upload / View / Validasi</a></td></tr><?php endforeach; ?>
<?php if(!$deliveries): ?><tr><td colspan="5">Tidak ada DO pada periode ini.</td></tr><?php endif; ?>
</tbody></table></div></div></div></section></div>
