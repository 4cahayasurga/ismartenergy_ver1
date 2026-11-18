<div class="content-wrapper"><section class="content-header"><h1>Edit Pesanan BBM</h1></section>
<section class="content">
<div class="box box-primary"><div class="box-body">
<p><strong><?= html_escape($row['RequestNo']) ?></strong> · <?= html_escape($letter['VesselName'].' / '.$letter['CompanyName']) ?></p>
<form method="post" action="<?= site_url('Pipeline_flow/action/'.(int)$row['RequestID']) ?>">
<input type="hidden" name="pipeline_token" value="<?= html_escape($pipeline_token) ?>">
<input type="hidden" name="OrderVersion" value="<?= html_escape($version) ?>">
<div class="row">
<div class="col-sm-6 form-group"><label for="filling-date">Tanggal rencana pengisian</label>
<input class="form-control" id="filling-date" type="date" name="FillingDate" value="<?= html_escape($letter['FillingDate']) ?>" required></div>
<div class="col-sm-6 form-group"><label for="planned-liters">Volume rencana (liter)</label>
<input class="form-control" id="planned-liters" type="number" min="0.001" step="0.001" name="PlannedLiters" value="<?= html_escape($letter['PlannedLiters']) ?>" required></div>
</div>
<button class="btn btn-primary" name="action" value="edit_order">Simpan Perubahan</button>
<a class="btn btn-default" href="<?= site_url('Pipeline_flow') ?>">Kembali</a>
</form></div></div>
</section></div>
