<div class="content-wrapper">
<section class="content-header"><h1>Kuota JBT <small>SK BPH Migas - badan usaha - kapal - fuel terminal</small></h1></section>
<section class="content">
<?php if ($this->session->flashdata('Berhasil')): ?><div class="alert alert-success"><?= html_escape($this->session->flashdata('Berhasil')) ?></div><?php endif; ?>
<?php if ($this->session->flashdata('Gagal')): ?><div class="alert alert-danger"><?= html_escape($this->session->flashdata('Gagal')) ?></div><?php endif; ?>

<?php if (!count($entities)): ?>
<div class="alert alert-danger"><strong>Master badan usaha masih kosong.</strong> Buat badan usaha terlebih dahulu agar SK BPH, kapal, rekening, dan alokasi dapat diproses. <button class="btn btn-xs btn-default" data-toggle="modal" data-target="#modalBadanUsaha">Tambah Badan Usaha</button></div>
<?php endif; ?>

<div class="box box-primary">
 <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-plus-circle"></i> Aksi Data Kuota JBT</h3></div>
 <div class="box-body">
  <div class="row">
   <div class="col-md-2 col-sm-4"><button class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalBadanUsaha"><i class="fa fa-building"></i><br> Badan Usaha</button></div>
   <div class="col-md-2 col-sm-4"><button class="btn btn-warning btn-block" data-toggle="modal" data-target="#modalSkBph" <?= !count($entities)?'disabled':'' ?>><i class="fa fa-file-text"></i><br> SK BPH</button></div>
   <div class="col-md-2 col-sm-4"><button class="btn btn-info btn-block" data-toggle="modal" data-target="#modalKapal" <?= !count($entities)?'disabled':'' ?>><i class="fa fa-ship"></i><br> Kapal</button></div>
   <div class="col-md-2 col-sm-4"><button class="btn btn-success btn-block" data-toggle="modal" data-target="#modalAlokasi" <?= (!count($decrees)||!count($vessels))?'disabled':'' ?>><i class="fa fa-pie-chart"></i><br> Alokasi Kuota</button></div>
   <div class="col-md-2 col-sm-4"><button class="btn btn-default btn-block" data-toggle="modal" data-target="#modalRekening" <?= !count($entities)?'disabled':'' ?>><i class="fa fa-credit-card"></i><br> Rekening Transfer</button></div>
   <div class="col-md-2 col-sm-4"><button class="btn btn-success btn-block" data-toggle="modal" data-target="#modalImport"><i class="fa fa-file-excel-o"></i><br> Import Excel</button></div>
  </div>
  <p class="help-block" style="margin-top:12px">Urutan data: Badan Usaha → SK BPH dan Kapal → Alokasi Kuota → Monitoring.</p>
 </div>
</div>

<div class="box box-warning" id="pengajuan-kuota">
 <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-bell-o"></i> Pengajuan Kapal dan Kuota Customer</h3><span class="label label-warning pull-right"><?= count(array_filter($quota_requests,function($r){return $r['Status']==='PENDING';})) ?> pending</span></div>
 <div class="box-body table-responsive">
 <table class="table table-bordered table-striped"><thead><tr><th>Tanggal</th><th>Perusahaan/Customer</th><th>Kapal/Lokasi</th><th>SK Kuota yang Dilampirkan</th><th>Status/Aksi</th></tr></thead><tbody>
 <?php foreach($quota_requests as $r): ?><tr><td><?= html_escape($r['CreatedAt']) ?></td><td><?= html_escape($r['CompanyName'].' / '.$r['RequestedBy']) ?></td><td><?= html_escape($r['VesselName'].' - '.$r['RegistrationNo']) ?><br><?= html_escape($r['No_SPBB'].' - '.$r['Area']) ?></td><td><strong><?= html_escape($r['SubmittedDecreeNo'] ?: 'Data SK belum tersedia') ?></strong><br><?= html_escape($r['DecreeIssuer'].' / '.$r['SubmittedDecreeDate']) ?><br>Kuota SK: <?= number_format((float)($r['SubmittedTotalQuota'] ?: $r['RequestedQuota']),2) ?> L<?php if(!empty($r['DecreeDocument'])): ?><br><a target="_blank" class="btn btn-xs btn-default" href="<?= base_url('uploads/jbt_decrees/'.$r['DecreeDocument']) ?>"><i class="fa fa-file-pdf-o"></i> Lihat Lampiran SK</a><?php endif; ?></td><td>
 <?php if($r['Status']==='PENDING'): ?><form method="post" action="<?= site_url('Ist/Basic_Data/JbtQuota/review_quota_request/'.$r['RequestID']) ?>"><textarea name="AdminNotes" class="form-control input-sm" placeholder="Catatan hasil verifikasi"></textarea><button name="Decision" value="APPROVED" class="btn btn-xs btn-success" <?= empty($r['DecreeDocument'])?'disabled':'' ?>>Verifikasi & Setujui</button> <button name="Decision" value="REJECTED" class="btn btn-xs btn-danger">Tolak</button></form><?php else: ?><span class="label label-<?= $r['Status']==='APPROVED'?'success':'danger' ?>"><?= html_escape($r['Status']) ?></span><br><?= html_escape($r['AdminNotes']) ?><?php endif; ?>
 </td></tr><?php endforeach; ?>
 <?php if(!count($quota_requests)): ?><tr><td colspan="6" class="text-center">Belum ada pengajuan customer.</td></tr><?php endif; ?>
 </tbody></table></div>
</div>

<div class="row">
 <div class="col-md-4"><div class="small-box bg-aqua"><div class="inner"><h3><?= count($entities) ?></h3><p>Badan Usaha</p></div><div class="icon"><i class="fa fa-building"></i></div></div></div>
 <div class="col-md-4"><div class="small-box bg-yellow"><div class="inner"><h3><?= count($decrees) ?></h3><p>SK BPH</p></div><div class="icon"><i class="fa fa-file-text"></i></div></div></div>
 <div class="col-md-4"><div class="small-box bg-blue"><div class="inner"><h3><?= count($vessels) ?></h3><p>Master Kapal</p></div><div class="icon"><i class="fa fa-ship"></i></div></div></div>
</div>

<div class="row">
 <div class="col-md-6"><div class="box box-default"><div class="box-header with-border"><h3 class="box-title">Master SK BPH</h3><button class="btn btn-xs btn-warning pull-right" data-toggle="modal" data-target="#modalSkBph" <?= !count($entities)?'disabled':'' ?>><i class="fa fa-plus"></i> Tambah</button></div><div class="box-body table-responsive"><table class="table table-bordered table-condensed"><thead><tr><th>No. SK</th><th>Badan Usaha</th><th>Periode</th><th>Total Kuota</th></tr></thead><tbody><?php foreach($decrees as $d): ?><tr><td><?= html_escape($d['DecreeNo']) ?></td><td><?= html_escape($d['CompanyName']) ?></td><td><?= html_escape($d['PeriodYear']) ?></td><td><?= number_format($d['TotalQuota'],2) ?> L</td></tr><?php endforeach; ?><?php if(!count($decrees)): ?><tr><td colspan="4" class="text-center">Belum ada SK BPH.</td></tr><?php endif; ?></tbody></table></div></div></div>
 <div class="col-md-6"><div class="box box-default"><div class="box-header with-border"><h3 class="box-title">Master Kapal</h3><button class="btn btn-xs btn-info pull-right" data-toggle="modal" data-target="#modalKapal" <?= !count($entities)?'disabled':'' ?>><i class="fa fa-plus"></i> Tambah</button></div><div class="box-body table-responsive"><table class="table table-bordered table-condensed"><thead><tr><th>Kapal</th><th>Perusahaan</th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php foreach($vessels as $v): ?><tr><td><?= html_escape($v['VesselName'].' - '.$v['RegistrationNo']) ?></td><td><?= html_escape($v['CompanyName']) ?></td><td><span class="label label-<?= $v['IsActive']?'success':'default' ?>"><?= $v['IsActive']?'AKTIF':'NONAKTIF' ?></span></td><td><form method="post" action="<?= site_url('Ist/Basic_Data/JbtQuota/toggle_vessel/'.$v['VesselID']) ?>"><button class="btn btn-xs btn-<?= $v['IsActive']?'warning':'success' ?>"><?= $v['IsActive']?'Nonaktifkan':'Aktifkan' ?></button></form></td></tr><?php endforeach; ?><?php if(!count($vessels)): ?><tr><td colspan="4" class="text-center">Belum ada kapal.</td></tr><?php endif; ?></tbody></table></div></div></div>
</div>

<div class="box box-success"><div class="box-header with-border"><h3 class="box-title">Rekening yang Berhak Transfer</h3><button class="btn btn-xs btn-success pull-right" data-toggle="modal" data-target="#modalRekening" <?= !count($entities)?'disabled':'' ?>><i class="fa fa-plus"></i> Verifikasi Rekening</button></div><div class="box-body table-responsive"><table class="table table-bordered table-striped"><thead><tr><th>Perusahaan</th><th>Bank/Rekening</th><th>Pihak Berwenang</th><th>Status</th></tr></thead><tbody><?php foreach($authorized_accounts as $a): ?><tr><td><?= html_escape($a['CompanyName']) ?></td><td><?= html_escape($a['BankName'].' / '.$a['AccountNo'].' / '.$a['AccountName']) ?></td><td><?= html_escape($a['AuthorizedPersonName'].' - '.$a['AuthorizedPersonPosition']) ?></td><td><span class="label label-success"><?= html_escape($a['VerificationStatus']) ?></span></td></tr><?php endforeach; ?><?php if(!count($authorized_accounts)): ?><tr><td colspan="4" class="text-center">Belum ada rekening terverifikasi.</td></tr><?php endif; ?></tbody></table></div></div>

<div class="box box-success"><div class="box-header with-border"><h3 class="box-title">Rekening Penerima Pembayaran SPBB/Pipanisasi</h3><button class="btn btn-xs btn-success pull-right" data-toggle="modal" data-target="#modalRekeningPenerima"><i class="fa fa-plus"></i> Tambah Rekening Penerima</button></div><div class="box-body table-responsive"><table class="table table-bordered table-striped"><thead><tr><th>SPBB/Fuel Terminal</th><th>Kanal</th><th>Bank</th><th>Nomor Rekening</th><th>Nama Penerima</th><th>Status</th></tr></thead><tbody><?php foreach($receiving_accounts as $a): ?><tr><td><?= html_escape($a['No_SPBB'].' - '.$a['Area']) ?></td><td><?= html_escape($a['OrderChannel']) ?></td><td><?= html_escape($a['BankName']) ?></td><td><?= html_escape($a['AccountNo']) ?></td><td><?= html_escape($a['AccountName']) ?></td><td><span class="label label-success"><?= html_escape($a['VerificationStatus']) ?></span></td></tr><?php endforeach; ?><?php if(!count($receiving_accounts)): ?><tr><td colspan="6" class="text-center">Belum ada rekening penerima. Validasi otomatis bukti bayar belum dapat dinyatakan sesuai.</td></tr><?php endif; ?></tbody></table></div></div>

<div class="box box-info"><div class="box-header with-border"><h3 class="box-title">Monitoring dan Ranking Pemakaian Kapal</h3><button class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-target="#modalAlokasi" <?= (!count($decrees)||!count($vessels))?'disabled':'' ?>><i class="fa fa-plus"></i> Alokasi Kuota</button></div><div class="box-body table-responsive">
<table class="table table-bordered table-striped js-app-datatable"><thead><tr><th>Rank</th><th>Badan Usaha</th><th>Kapal</th><th>Fuel Terminal</th><th>SK</th><th>Alokasi</th><th>Booking</th><th>Terpakai</th><th>Sisa</th><th>Status</th></tr></thead><tbody>
<?php foreach($allocations as $i=>$r): $pct=$r['AllocatedQuota']?(100*$r['UsedQuota']/$r['AllocatedQuota']):0; ?><tr class="<?= $r['IsExceeded']?'danger':($pct>=90?'warning':'') ?>"><td><?= $i+1 ?></td><td><?= html_escape($r['CompanyName']) ?></td><td><?= html_escape($r['VesselName']) ?></td><td><?= html_escape($r['No_SPBB'].' - '.$r['Area']) ?></td><td><?= html_escape($r['DecreeNo']) ?></td><td><?= number_format($r['AllocatedQuota'],2) ?></td><td><?= number_format($r['BookedQuota'],2) ?></td><td><?= number_format($r['UsedQuota'],2) ?></td><td><?= number_format($r['RemainingQuota'],2) ?></td><td><span class="label label-<?= $r['IsExceeded']?'danger':($pct>=90?'warning':'success') ?>"><?= $r['IsExceeded']?'MELEBIHI KUOTA':number_format($pct,1).'%' ?></span></td></tr><?php endforeach; ?>
</tbody></table></div></div>
</section></div>

<!-- Modal Badan Usaha -->
<div class="modal fade" id="modalBadanUsaha"><div class="modal-dialog"><div class="modal-content"><form method="post" action="<?= site_url('Ist/Basic_Data/JbtQuota/save_entity') ?>">
<div class="modal-header"><button class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><i class="fa fa-building"></i> Tambah Badan Usaha</h4></div><div class="modal-body">
<div class="form-group"><label>NIB</label><input type="text" name="NIB" class="form-control" required></div>
<div class="form-group"><label>Nama Badan Usaha</label><input type="text" name="CompanyName" class="form-control" required></div>
<div class="form-group"><label>Hubungkan Akun Customer Lama (opsional)</label><select name="CustomerUserID" class="form-control"><option value="">Tidak menghubungkan akun</option><?php foreach($unlinked_customers as $u): ?><option value="<?= html_escape($u['UserID']) ?>"><?= html_escape($u['UserName'].' ('.$u['UserID'].')') ?></option><?php endforeach; ?></select></div>
</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan Badan Usaha</button></div></form></div></div></div>

<!-- Modal SK BPH -->
<div class="modal fade" id="modalSkBph"><div class="modal-dialog"><div class="modal-content"><form method="post" action="<?= site_url('Ist/Basic_Data/JbtQuota/save_decree') ?>">
<div class="modal-header"><button class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><i class="fa fa-file-text"></i> Tambah SK BPH</h4></div><div class="modal-body">
<div class="form-group"><label>Badan Usaha</label><select name="BusinessEntityID" class="form-control" required><option value="">Pilih badan usaha</option><?php foreach($entities as $r): ?><option value="<?= $r['BusinessEntityID'] ?>"><?= html_escape($r['CompanyName'].' - NIB '.$r['NIB']) ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>No. SK BPH</label><input type="text" name="DecreeNo" class="form-control" required></div>
<div class="row"><div class="col-md-6"><div class="form-group"><label>Tanggal SK</label><input type="date" name="DecreeDate" class="form-control" required></div></div><div class="col-md-6"><div class="form-group"><label>Tahun Periode</label><input type="number" name="PeriodYear" min="2000" max="2100" value="<?= date('Y') ?>" class="form-control" required></div></div></div>
<div class="form-group"><label>Total Kuota SK (Liter)</label><input type="number" min="1" step=".01" name="TotalQuota" class="form-control" required></div>
</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Batal</button><button class="btn btn-warning">Simpan SK BPH</button></div></form></div></div></div>

<!-- Modal Kapal -->
<div class="modal fade" id="modalKapal"><div class="modal-dialog"><div class="modal-content"><form method="post" action="<?= site_url('Ist/Basic_Data/JbtQuota/save_vessel') ?>">
<div class="modal-header"><button class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><i class="fa fa-ship"></i> Tambah Master Kapal</h4></div><div class="modal-body">
<div class="form-group"><label>Badan Usaha</label><select name="BusinessEntityID" class="form-control" required><option value="">Pilih badan usaha</option><?php foreach($entities as $r): ?><option value="<?= $r['BusinessEntityID'] ?>"><?= html_escape($r['CompanyName'].' - NIB '.$r['NIB']) ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Nama Kapal</label><input type="text" name="VesselName" class="form-control" required></div>
<div class="row"><div class="col-md-6"><div class="form-group"><label>No. Registrasi Kapal</label><input type="text" name="RegistrationNo" class="form-control"></div></div><div class="col-md-6"><div class="form-group"><label>Jenis Kapal</label><input type="text" name="VesselType" class="form-control"></div></div></div>
<div class="form-group"><label>Gross Tonnage</label><input type="number" min="0" step=".01" name="GrossTonnage" class="form-control"></div>
</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Batal</button><button class="btn btn-info">Simpan Kapal</button></div></form></div></div></div>

<!-- Modal Alokasi -->
<div class="modal fade" id="modalAlokasi"><div class="modal-dialog"><div class="modal-content"><form method="post" action="<?= site_url('Ist/Basic_Data/JbtQuota/save') ?>">
<div class="modal-header"><button class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><i class="fa fa-pie-chart"></i> Alokasi Kuota Kapal</h4></div><div class="modal-body">
<div class="form-group"><label>SK BPH / Badan Usaha</label><select name="DecreeID" class="form-control" required><option value="">Pilih SK</option><?php foreach($decrees as $r): ?><option value="<?= $r['DecreeID'] ?>"><?= html_escape($r['DecreeNo'].' - '.$r['CompanyName'].' ('.number_format($r['TotalQuota'],2).' L)') ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Kapal</label><select name="VesselID" class="form-control" required><option value="">Pilih kapal</option><?php foreach($vessels as $r): ?><option value="<?= $r['VesselID'] ?>"><?= html_escape($r['VesselName'].' - '.$r['CompanyName']) ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Fuel Terminal / SPBB</label><select name="SpbbID" class="form-control" required><option value="">Pilih lokasi</option><?php foreach($spbb as $r): ?><option value="<?= $r['LedgerNo'] ?>"><?= html_escape($r['No_SPBB'].' - '.$r['Area']) ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Kuota JBT (Liter)</label><input type="number" min="1" step=".01" name="AllocatedQuota" class="form-control" required></div>
</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Batal</button><button class="btn btn-success">Simpan Alokasi</button></div></form></div></div></div>

<!-- Modal Rekening -->
<div class="modal fade" id="modalRekening"><div class="modal-dialog"><div class="modal-content"><form method="post" action="<?= site_url('Ist/Basic_Data/JbtQuota/save_authorized_account') ?>">
<div class="modal-header"><button class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><i class="fa fa-credit-card"></i> Verifikasi Rekening Transfer</h4></div><div class="modal-body">
<div class="form-group"><label>Badan Usaha</label><select name="BusinessEntityID" class="form-control" required><option value="">Pilih badan usaha</option><?php foreach($entities as $e): ?><option value="<?= $e['BusinessEntityID'] ?>"><?= html_escape($e['CompanyName']) ?></option><?php endforeach; ?></select></div>
<div class="row"><div class="col-md-6"><div class="form-group"><label>Bank</label><input name="BankName" class="form-control" required></div></div><div class="col-md-6"><div class="form-group"><label>No. Rekening</label><input name="AccountNo" class="form-control" required></div></div></div>
<div class="form-group"><label>Nama Rekening</label><input name="AccountName" class="form-control" required></div>
<div class="row"><div class="col-md-8"><div class="form-group"><label>Nama yang Berhak Transfer</label><input name="AuthorizedPersonName" class="form-control" required></div></div><div class="col-md-4"><div class="form-group"><label>Jabatan</label><input name="AuthorizedPersonPosition" class="form-control"></div></div></div>
</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Batal</button><button class="btn btn-success">Verifikasi Rekening</button></div></form></div></div></div>

<!-- Modal Import -->
<div class="modal fade" id="modalImport"><div class="modal-dialog"><div class="modal-content"><form method="post" enctype="multipart/form-data" action="<?= site_url('Ist/Basic_Data/JbtQuota/import') ?>">
<div class="modal-header"><button class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><i class="fa fa-file-excel-o"></i> Import Kuota JBT</h4></div><div class="modal-body">
<div class="alert alert-info">Kolom: NIB, Badan Usaha, No SK, Kapal, No Registrasi Kapal, Tanggal SK, No SPBB, Kuota Kapal, Total Kuota SK.</div>
<div class="form-group"><label>File Excel</label><input type="file" name="file_excel" class="form-control" accept=".xls,.xlsx" required></div>
</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Batal</button><button class="btn btn-success">Import Excel</button></div></form></div></div></div>

<!-- Modal Rekening Penerima -->
<div class="modal fade" id="modalRekeningPenerima"><div class="modal-dialog"><div class="modal-content"><form method="post" action="<?= site_url('Ist/Basic_Data/JbtQuota/save_receiving_account') ?>">
<div class="modal-header"><button class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><i class="fa fa-bank"></i> Rekening Penerima Pembayaran</h4></div><div class="modal-body">
<div class="form-group"><label>SPBB/Fuel Terminal</label><select name="SpbbID" class="form-control" required><option value="">Pilih lokasi</option><?php foreach($spbb as $s): ?><option value="<?= $s['LedgerNo'] ?>"><?= html_escape($s['No_SPBB'].' - '.$s['Area']) ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Kanal Pesanan</label><select name="OrderChannel" class="form-control" required><option value="SPBB">SPBB</option><option value="PIPANISASI">Pipanisasi</option></select></div>
<div class="row"><div class="col-md-6"><div class="form-group"><label>Bank Penerima</label><input name="BankName" class="form-control" required></div></div><div class="col-md-6"><div class="form-group"><label>Nomor Rekening Tujuan</label><input name="AccountNo" class="form-control" inputmode="numeric" required></div></div></div>
<div class="form-group"><label>Nama Pemilik/Rekening Penerima</label><input name="AccountName" class="form-control" required></div>
</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Batal</button><button class="btn btn-success">Simpan Rekening Penerima</button></div></form></div></div></div>
