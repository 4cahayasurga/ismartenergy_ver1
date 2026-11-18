<div class="content-wrapper"><section class="content-header"><h1>Akses Manajemen User Customer</h1></section><section class="content">
<?php $this->load->view('pipeline/billing_notice'); ?>
<div class="box"><div class="box-body table-responsive"><table class="table"><thead><tr><th>Perusahaan</th><th>Cabang dan akses</th></tr></thead><tbody>
<?php foreach($companies as $company): ?><tr><td><?= html_escape($company['CompanyName']) ?></td><td><form class="form-inline" method="post">
<input type="hidden" name="CompanyID" value="<?= (int)$company['BusinessEntityID'] ?>">
<?php if($developer): ?><label>Cabang <select class="form-control" name="SpbbID" required><option value="">Pilih cabang</option>
<?php foreach($branches as $bid=>$name): ?><option value="<?= (int)$bid ?>" <?= (int)$company['SpbbID']===(int)$bid?'selected':'' ?>><?= html_escape($name) ?></option><?php endforeach; ?></select></label>
<?php else: ?><?= html_escape($branches[$company['SpbbID']]??'') ?><?php endif; ?>
<label>Akses <select class="form-control" name="Enabled"><option value="0">Nonaktif</option><option value="1" <?= !empty($company['Enabled'])?'selected':'' ?>>Aktif</option></select></label>
<button class="btn btn-primary">Simpan</button></form></td></tr><?php endforeach; ?>
</tbody></table></div></div></section></div>
