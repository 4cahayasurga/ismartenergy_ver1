<div class="content-wrapper">
<section class="content-header"><h1>Pengajuan User Perusahaan <small>Customer</small></h1></section>
<section class="content">
<?php if ($this->session->flashdata('Berhasil')): ?><div class="alert alert-success"><?= html_escape($this->session->flashdata('Berhasil')) ?></div><?php endif; ?>
<?php if ($this->session->flashdata('Gagal')): ?><div class="alert alert-danger"><?= html_escape($this->session->flashdata('Gagal')) ?></div><?php endif; ?>
<?php if (!$business_entity_id): ?><div class="alert alert-danger">Akun belum terhubung ke badan usaha. Hubungi Admin.</div><?php else: ?>
<div class="alert alert-info">User yang diajukan akan memperoleh akses Customer pada <strong><?= html_escape($company['CompanyName']) ?></strong>. Akun baru aktif setelah disetujui Admin.</div>
<div class="box box-primary"><div class="box-header"><h3 class="box-title">Ajukan User Baru</h3></div>
<form method="post" action="<?= site_url('Spbb/request_customer_user') ?>"><?php $this->load->view('pipeline/billing_token'); ?><div class="box-body"><div class="row">
<div class="col-md-4"><div class="form-group"><label>User ID</label><input name="UserID" class="form-control" minlength="4" maxlength="30" pattern="[A-Za-z0-9._-]+" required></div></div>
<div class="col-md-4"><div class="form-group"><label>Nama Lengkap</label><input name="UserName" class="form-control" maxlength="100" required></div></div>
<div class="col-md-4"><div class="form-group"><label>Jabatan</label><input name="PositionName" class="form-control" maxlength="100"></div></div>
</div><div class="row">
<div class="col-md-4"><div class="form-group"><label>Email</label><input type="email" name="Email" class="form-control" required></div></div>
<div class="col-md-4"><div class="form-group"><label>No. WhatsApp</label><input name="Phone" class="form-control"></div></div>
<div class="col-md-2"><div class="form-group"><label>Password Awal</label><input type="password" name="Password" minlength="6" class="form-control" required></div></div>
<div class="col-md-2"><div class="form-group"><label>Konfirmasi</label><input type="password" name="ConfirmPassword" minlength="6" class="form-control" required></div></div>
</div></div><div class="box-footer"><button class="btn btn-primary">Kirim untuk Approval</button></div></form></div>

<div class="row"><div class="col-md-7"><div class="box box-default"><div class="box-header"><h3 class="box-title">Status Pengajuan</h3></div><div class="box-body table-responsive"><table class="table table-bordered"><thead><tr><th>Tanggal</th><th>User</th><th>Status</th><th>Catatan Admin</th></tr></thead><tbody><?php foreach($requests as $r): ?><tr><td><?= html_escape($r['CreatedAt']) ?></td><td><strong><?= html_escape($r['UserID']) ?></strong><br><?= html_escape($r['UserName'].' / '.$r['Email']) ?></td><td><span class="label label-<?= $r['Status']==='APPROVED'?'success':($r['Status']==='REJECTED'?'danger':'warning') ?>"><?= html_escape($r['Status']) ?></span></td><td><?= html_escape($r['AdminNotes']) ?></td></tr><?php endforeach; ?></tbody></table></div></div></div>
<div class="col-md-5"><div class="box box-success"><div class="box-header"><h3 class="box-title">User Aktif Perusahaan</h3></div><div class="box-body table-responsive"><table class="table table-bordered"><thead><tr><th>User</th><th>Email</th><th>Status</th></tr></thead><tbody><?php foreach($users as $u): ?><tr><td><?= html_escape($u['UserName'].' ('.$u['UserID'].')') ?></td><td><?= html_escape($u['Email']) ?></td><td><?= $u['Active']==='1'?'AKTIF':'NONAKTIF' ?></td></tr><?php endforeach; ?></tbody></table></div></div></div></div>
<?php endif; ?>
</section></div>
