<div class="content-wrapper"><section class="content-header"><h1>Edit Kapal Customer</h1></section>
<section class="content"><?php $this->load->view('pipeline/billing_notice'); ?>
<div class="box box-primary"><div class="box-body">
<p>Perusahaan <strong><?= html_escape($vessel['CompanyName']) ?></strong></p>
<form method="post"><?php $this->load->view('pipeline/billing_token'); ?>
<input type="hidden" name="RevisionHash" value="<?= html_escape($revision) ?>">
<?php $this->load->view('pipeline/customer_vessel_fields',compact('fields','form','vesselSettings','routes')); ?>
<button class="btn btn-primary">Simpan Perubahan</button>
<a class="btn btn-default" href="<?= site_url($this->session->userdata('USER_ROLE')==='CUSTOMER'?'Spbb/customer_vessels':'Pipeline_customer_vessels') ?>">Daftar Kapal</a>
</form></div></div>
<div class="box box-default"><div class="box-header"><h3 class="box-title">Log Perubahan Kapal</h3></div><div class="box-body table-responsive">
<p>Dibuat oleh <?= html_escape($vessel['CreatedBy']) ?> pada <?= html_escape($vessel['CreatedAt']) ?>.</p>
<table class="table table-bordered"><thead><tr><th>Waktu</th><th>Pengguna</th><th>Kolom</th><th>Sebelum</th><th>Sesudah</th></tr></thead><tbody>
<?php foreach($history as $event): $snapshot=json_decode($event['Snapshot'],true); $changes=array();
foreach($fields as $key=>$field)if((string)($snapshot['Before'][$key]??'')!==(string)($snapshot['After'][$key]??''))$changes[$key]=$field[0];
if(!$changes)$changes=array('VesselName'=>'Disimpan kembali');
foreach($changes as $key=>$label): ?>
<tr><td><?= html_escape($event['At']) ?></td><td><?= html_escape(($event['UserName']?:$event['UserID']).' / '.$event['UserID']) ?></td>
<td><?= html_escape($label) ?></td><td><?= html_escape($snapshot['Before'][$key]??'') ?></td><td><?= html_escape($snapshot['After'][$key]??'') ?></td></tr>
<?php endforeach; endforeach; ?><?php if(!$history): ?><tr><td colspan="5">Belum ada perubahan yang tercatat.</td></tr><?php endif; ?>
</tbody></table></div></div></section></div>
