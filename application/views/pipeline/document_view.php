<div class="content-wrapper"><section class="content-header"><h1>View / Periksa <?= html_escape($document['DocumentType']) ?></h1></section><section class="content">
<?php $this->load->view('pipeline/billing_notice'); ?>
<div class="box box-primary"><div class="box-body">
<p><?= html_escape($document['OriginalName']) ?> &middot; Referensi: <strong><?= html_escape($document['ReferenceNo']) ?></strong> &middot; Tanggal: <?= html_escape($document['DocumentDate']) ?></p>
<p>Kapal: <?= html_escape($document['VesselName']) ?> &middot; Volume: <?= number_format($document['VolumeLiters'],3,',','.') ?> L &middot; Referensi LO: <?= html_escape($document['LoadingReference']) ?></p>
<p><?= html_escape($document['ReviewNote']) ?></p>
<p><a class="btn btn-default" href="<?= site_url('Pipeline_billing/file/'.$document['DocumentID'].'?download=1') ?>">Download</a> <a class="btn btn-primary" target="_blank" rel="noopener" href="<?= site_url('Pipeline_billing/file/'.$document['DocumentID']) ?>">Buka untuk Print (Ctrl+P)</a></p>
<?php if($document['MimeType']==='application/pdf'): ?><iframe title="Dokumen PDF" style="width:100%;height:650px;border:1px solid #ddd" src="<?= site_url('Pipeline_billing/file/'.$document['DocumentID']) ?>"></iframe><?php else: ?><img alt="Dokumen <?= html_escape($document['DocumentType']) ?>" style="max-width:100%" src="<?= site_url('Pipeline_billing/file/'.$document['DocumentID']) ?>"><?php endif; ?>
<?php if(!$document['Reviewed'] && $this->pipeline_access->allowed('delivery')): ?><form method="post" style="margin-top:15px"><?php $this->load->view('pipeline/billing_token'); ?><label><input type="checkbox" name="Reviewed" value="1" required> Saya sudah memeriksa file dan memastikan nomor, tanggal, kapal, volume, serta referensi LO sesuai bagian dokumen yang digunakan.</label><p><button class="btn btn-success">Konfirmasi Pemeriksaan</button></p></form><?php else: ?><p>Diperiksa: <?= html_escape($document['ReviewedBy'].' / '.$document['ReviewedAt']) ?></p><?php endif; ?>
<a href="<?= site_url('Pipeline_billing/documents/'.$document['DeliveryID']) ?>">Kembali ke DO</a>
</div></div></section></div>
