<div class="content-wrapper"><section class="content-header"><h1><?= !empty($submission_list)?'Pengajuan Bunkering':(!empty($supervision_only)?'Approval Permohonan Bunkering':(empty($approval_only)?'Pesanan BBM Pipanisasi':'Approval Pengisian BBM')) ?></h1></section>
<section class="content"><?php $this->load->view('pipeline/billing_notice'); ?>
<?php if (empty($approval_only)): ?>
<div class="box box-primary"><div class="box-body">
<?php if (!empty($company)): ?><p><?= html_escape($company['CompanyName']) ?></p>
<a class="btn btn-primary" href="<?= site_url('Pipeline_flow/create') ?>">Buat Permohonan Bunker</a>
<?php if ($this->pipeline_access->allowed('customer_logo')): ?><a class="btn btn-default" href="<?= site_url('Pipeline_flow/profile') ?>">Profil &amp; Logo Perusahaan</a><?php endif; ?>
<?php else: ?><p>Hubungi administrator untuk menghubungkan akun ke perusahaan pelayaran.</p><?php endif; ?>
</div></div>
<?php endif; ?>
<?php if (!empty($supervision_only)): ?><p><strong><?= count($rows) ?></strong> permohonan menunggu keputusan.</p><?php endif; ?>
<?php if (!empty($kkm_list)): ?><h2>Menunggu konfirmasi</h2><?php endif; ?>
<?php $this->load->view('pipeline/flow_rows',array('rows'=>$rows,'statuses'=>$statuses,'creators'=>$creators??null,'showOrderActions'=>empty($approval_only) && !empty($company),'orderToken'=>$pipeline_token)); ?>
<?php if (!empty($completedKkm)): ?>
<h2>Riwayat Konfirmasi KKM</h2>
<?php $this->load->view('pipeline/flow_rows',array('rows'=>$completedKkm)); ?>
<?php endif; ?>
<?php if (!empty($revisions)): ?>
<h2>Menunggu pengajuan ulang Customer</h2>
<p>Permohonan dapat disetujui setelah Customer mengirim ulang revisinya.</p>
<?php $this->load->view('pipeline/flow_rows',array('rows'=>$revisions,'creators'=>$creators)); ?>
<?php endif; ?>
</section></div>
