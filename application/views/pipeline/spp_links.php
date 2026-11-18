<div class="content-wrapper">
<section class="content-header"><h1>Dokumen Terkait SPP #<?= (int)$spp['CaptureID'] ?></h1></section>
<section class="content">
<?php $this->load->view('pipeline/billing_notice'); ?>
<div class="box box-primary"><div class="box-body">
<p><a href="<?= site_url('Pipeline_spp/review/'.(int)$spp['CaptureID']) ?>"><?= html_escape($spp['OriginalName']) ?></a></p>
<ul>
<?php if($delivery): ?>
<li>DO <a href="<?= site_url('Pipeline_billing/documents/'.(int)$delivery['DeliveryID']) ?>"><?= html_escape($delivery['DONumber'].' / '.$delivery['VesselName']) ?></a></li>
<?php endif; ?>
<?php if($document): ?>
<li>Lampiran SPP pada DO <a href="<?= site_url('Pipeline_billing/document/'.(int)$document['DocumentID']) ?>"><?= html_escape($document['ReferenceNo'].' / '.$document['OriginalName']) ?></a></li>
<?php endif; ?>
<?php foreach($orders as $order): ?><li>Pesanan <a href="<?= site_url('Pipeline_flow/detail/'.(int)$order['RequestID']) ?>"><?= html_escape($order['RequestNo']) ?></a></li><?php endforeach; ?>
<?php foreach($operations as $operation): ?><li><?= html_escape($operation['Kind']) ?> <a href="<?= site_url('Pipeline_documents/print_document/'.(int)$operation['OperationID']) ?>"><?= html_escape($operation['DocumentNo']) ?></a></li><?php endforeach; ?>
<?php foreach($invoices as $invoice): ?><li>Invoice <a href="<?= site_url('Pipeline_billing/invoice/'.(int)$invoice['InvoiceID']) ?>"><?= html_escape($invoice['InvoiceNumber']) ?></a></li><?php endforeach; ?>
</ul>
<?php if($canDetach): ?>
<form method="post" action="<?= site_url('Pipeline_documents/detach_spp/'.(int)$spp['CaptureID']) ?>">
<?php $this->load->view('pipeline/billing_token'); ?>
<input type="hidden" name="RevisionHash" value="<?= $revision ?>">
<label><input type="checkbox" required> Hapus lampiran SPP dari DO ini agar arsip SPP dapat digunakan kembali.</label>
<p>Nomor DO dan arsip OCR SPP tetap tersimpan.</p>
<button type="submit" class="btn btn-danger">Hapus Tautan SPP ke DO</button>
</form>
<?php elseif($orders || $operations || $invoices): ?>
<p>Dokumen yang memakai SPP ini harus ditangani melalui alurnya sebelum tautan dapat dihapus.</p>
<?php endif; ?>
</div></div>
<a class="btn btn-default" href="<?= site_url('Pipeline_spp') ?>">Daftar SPP</a>
</section></div>
