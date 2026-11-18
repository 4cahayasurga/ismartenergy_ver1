<div class="content-wrapper">
<section class="content-header"><h1>Master Data Kapal <small>Customer</small></h1></section>
<section class="content">
<?php if ($this->session->flashdata('Berhasil')): ?><div class="alert alert-success"><?= html_escape($this->session->flashdata('Berhasil')) ?></div><?php endif; ?>
<?php if ($this->session->flashdata('Gagal')): ?><div class="alert alert-danger"><?= html_escape($this->session->flashdata('Gagal')) ?></div><?php endif; ?>
<?php if (!$business_entity_id): ?><div class="alert alert-danger"><strong>Akun belum terhubung ke badan usaha.</strong> Admin harus menghubungkan akun ini melalui Basic Data Kuota JBT.</div><?php endif; ?>

<div class="box box-warning"><div class="box-header with-border"><h3 class="box-title"><i class="fa fa-bell-o"></i> Status Pengajuan SK Kuota</h3></div><div class="box-body table-responsive"><table class="table table-bordered table-striped"><thead><tr><th>Tanggal</th><th>Kapal</th><th>SK</th><th>Kuota SK</th><th>Status</th><th>Catatan</th></tr></thead><tbody><?php foreach($requests as $r): ?><tr><td><?= html_escape($r['CreatedAt']) ?></td><td><?= html_escape($r['VesselName'].' - '.$r['RegistrationNo']) ?></td><td><?= html_escape($r['SubmittedDecreeNo'] ?: '-') ?><?php if(!empty($r['DecreeDocument'])): ?><br><a target="_blank" href="<?= base_url('uploads/jbt_decrees/'.$r['DecreeDocument']) ?>">Lihat lampiran</a><?php endif; ?></td><td><?= number_format((float)($r['SubmittedTotalQuota'] ?: $r['RequestedQuota']),2) ?> L</td><td><span class="label label-<?= $r['Status']==='APPROVED'?'success':($r['Status']==='REJECTED'?'danger':'warning') ?>"><?= html_escape($r['Status']) ?></span></td><td><?= html_escape($r['AdminNotes']) ?></td></tr><?php endforeach; ?><?php if(!count($requests)): ?><tr><td colspan="6" class="text-center">Belum ada pengajuan SK kuota.</td></tr><?php endif; ?></tbody></table></div></div>

<div class="box box-primary"><div class="box-header with-border"><h3 class="box-title">Daftar Kapal dan Realisasi</h3><div class="box-tools"><button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalTambahKapal" <?= !$business_entity_id?'disabled':'' ?>><i class="fa fa-plus"></i> Tambah Kapal</button></div></div><div class="box-body table-responsive">
<p class="text-muted">Kapal nonaktif tidak dapat dipilih saat pemesanan maupun pengajuan kuota baru.</p>
<table class="table table-bordered table-striped"><thead><tr><th>Kapal</th><th>Status</th><th>Registrasi</th><th>Alokasi</th><th>Booking</th><th>Terpakai</th><th>Sisa</th><th>Realisasi</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($vessels as $v): ?><tr class="<?= $v['IsActive']?'':'active' ?>"><td><?= html_escape($v['VesselName']) ?></td><td><span class="label label-<?= $v['IsActive']?'success':'default' ?>"><?= $v['IsActive']?'AKTIF':'NONAKTIF' ?></span></td><td><?= html_escape($v['RegistrationNo']) ?></td><td><?= number_format($v['AllocatedQuota'],2) ?> L</td><td><?= number_format($v['BookedQuota'],2) ?> L</td><td><?= number_format($v['UsedQuota'],2) ?> L</td><td><?= number_format($v['RemainingQuota'],2) ?> L</td><td><?= number_format($v['RealizedQuantity'],2) ?> L</td><td><a class="btn btn-default btn-xs" href="<?= site_url('Pipeline_customer_vessels/edit/'.(int)$v['VesselID']) ?>">Edit &amp; Log</a> <form method="post" action="<?= site_url('Spbb/toggle_customer_vessel/'.$v['VesselID']) ?>"><?php $this->load->view('pipeline/billing_token'); ?><button class="btn btn-xs btn-<?= $v['IsActive']?'warning':'success' ?>"><?= $v['IsActive']?'Nonaktifkan':'Aktifkan' ?></button></form></td></tr><?php endforeach; ?>
<?php if(!count($vessels)): ?><tr><td colspan="9" class="text-center">Belum ada data kapal.</td></tr><?php endif; ?>
</tbody></table></div></div>

<?php if ($vesselSettings['show_quota']): ?>
<div class="box box-warning"><div class="box-header"><h3 class="box-title">Ajukan SK Kuota Pertamina/BPH</h3></div>
<form method="post" enctype="multipart/form-data" action="<?= site_url('Spbb/request_vessel_quota') ?>"><?php $this->load->view('pipeline/billing_token'); ?><div class="box-body">
<div class="alert alert-info">Masukkan data sesuai SK kuota resmi yang diberikan kepada perusahaan/asosiasi dan lampirkan dokumennya.</div><div class="row">
<div class="col-md-4"><div class="form-group"><label>Kapal Aktif</label><select name="VesselID" class="form-control" required><option value="">Pilih kapal</option><?php foreach($active_vessels as $v): ?><option value="<?= $v['VesselID'] ?>"><?= html_escape($v['VesselName'].' - '.$v['RegistrationNo']) ?></option><?php endforeach; ?></select></div></div>
<div class="col-md-4"><div class="form-group"><label>Lokasi SPBB/Fuel Terminal</label><select name="RequestedSpbbID" class="form-control" required><option value="">Pilih lokasi</option><?php foreach($spbb_list as $s): ?><option value="<?= $s['SpbbID'] ?>"><?= html_escape($s['SpbbName']) ?></option><?php endforeach; ?></select></div></div>
<div class="col-md-4"><div class="form-group"><label>Penerbit SK</label><select name="DecreeIssuer" class="form-control" required><option value="">Pilih penerbit</option><option value="BPH MIGAS">BPH Migas</option><option value="PERTAMINA">Pertamina</option></select></div></div>
</div><div class="row">
<div class="col-md-3"><div class="form-group"><label>Nomor SK Kuota</label><input name="SubmittedDecreeNo" class="form-control" required></div></div>
<div class="col-md-3"><div class="form-group"><label>Tanggal SK</label><input type="date" name="SubmittedDecreeDate" class="form-control" required></div></div>
<div class="col-md-3"><div class="form-group"><label>Kuota Sesuai SK (Liter)</label><input type="number" min="1" step=".01" name="SubmittedTotalQuota" class="form-control" required></div></div>
<div class="col-md-3"><div class="form-group"><label>Lampiran SK</label><input type="file" name="DecreeDocument" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required><small>PDF/JPG/PNG, maks. 5 MB</small></div></div>
</div></div><div class="box-footer"><button class="btn btn-warning" <?= !count($active_vessels)?'disabled':'' ?>>Kirim SK untuk Verifikasi</button></div></form></div>

<?php endif; ?>
</section></div>

<div class="modal fade" id="modalTambahKapal" tabindex="-1" role="dialog" aria-labelledby="modalTambahKapalLabel">
<div class="modal-dialog" role="document"><div class="modal-content">
<form method="post" action="<?= site_url('Spbb/save_customer_vessel') ?>"><?php $this->load->view('pipeline/billing_token'); ?>
<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title" id="modalTambahKapalLabel"><i class="fa fa-ship"></i> Tambah Data Kapal</h4></div>
<div class="modal-body">
<?php $this->load->view('pipeline/customer_vessel_fields',compact('fields','vesselSettings','routes')); ?>
</div>
<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Batal</button><button class="btn btn-success"><i class="fa fa-save"></i> Simpan Kapal</button></div>
</form></div></div></div>
