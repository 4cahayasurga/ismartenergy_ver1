<div class="content-wrapper"><section class="content-header"><h1>Master Kapal Customer</h1></section>
<section class="content"><div class="box box-primary"><div class="box-body table-responsive">
<table class="table table-bordered table-striped"><thead><tr><th>Perusahaan</th><th>Kapal</th><th>Registrasi / IMO</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($vessels as $vessel): ?><tr>
<td><?= html_escape($vessel['CompanyName']) ?></td><td><?= html_escape($vessel['VesselName']) ?></td>
<td><?= html_escape($vessel['RegistrationNo']) ?></td><td><?= $vessel['IsActive']?'Aktif':'Nonaktif' ?></td>
<td><a class="btn btn-default btn-sm" href="<?= site_url('Pipeline_customer_vessels/edit/'.(int)$vessel['VesselID']) ?>">Edit &amp; Log</a></td></tr>
<?php endforeach; ?><?php if(!$vessels): ?><tr><td colspan="5">Belum ada kapal.</td></tr><?php endif; ?>
</tbody></table></div></div></section></div>
