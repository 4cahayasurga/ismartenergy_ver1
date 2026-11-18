<div class="content-wrapper">
<section class="content-header"><h1>Dokumen Legalitas Perusahaan <small>Customer</small></h1></section>
<section class="content">
<?php if ($this->session->flashdata('Berhasil')): ?><div class="alert alert-success"><?= html_escape($this->session->flashdata('Berhasil')) ?></div><?php endif; ?>
<?php if ($this->session->flashdata('Gagal')): ?><div class="alert alert-danger"><?= html_escape($this->session->flashdata('Gagal')) ?></div><?php endif; ?>
<?php if (!$entity): ?><div class="alert alert-danger">Akun belum terhubung ke badan usaha. Hubungi Admin.</div><?php else: ?>
<div class="box box-primary"><div class="box-header"><h3 class="box-title"><?= html_escape($entity['CompanyName']) ?> — NIB <?= html_escape($entity['NIB']) ?></h3></div>
<form method="post" enctype="multipart/form-data" action="<?= site_url('Spbb/upload_customer_documents') ?>"><?php $this->load->view('pipeline/billing_token'); ?><div class="box-body">
<div class="alert <?= $document_status['complete']?'alert-success':'alert-warning' ?>">Kelengkapan: <strong><?= $document_status['complete']?'LENGKAP':'BELUM LENGKAP' ?></strong><?php if(!$document_status['complete']): ?> — belum tersedia: <?= html_escape(implode(', ', $document_status['missing'])) ?><?php endif; ?></div>
<div class="row"><?php foreach(array('NibFile'=>'NIB','NpwpFile'=>'NPWP','DeedFile'=>'Akta','AhuFile'=>'AHU') as $field=>$label): ?><div class="col-md-3"><div class="form-group"><label><?= $label ?></label><?php if(!empty($entity[$field])): ?><p><a target="_blank" href="<?= site_url('Pipeline_legal/file/'.rawurlencode($entity[$field])) ?>">Lihat dokumen saat ini</a></p><?php endif; ?><input type="file" name="<?= $field ?>" class="form-control" accept=".pdf,.jpg,.jpeg,.png"></div></div><?php endforeach; ?></div>
</div><div class="box-footer"><button class="btn btn-primary">Upload Dokumen yang Dipilih</button></div></form></div>
<?php endif; ?>
</section></div>
