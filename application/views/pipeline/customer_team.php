<div class="content-wrapper"><section class="content-header"><h1>Manajemen User</h1><p><?= html_escape($company['CompanyName'].' · '.$company['Area']) ?></p></section><section class="content">
<?php $this->load->view('pipeline/billing_notice'); $invite=$this->session->flashdata('team_invite'); ?>
<?php if($invite): ?><div class="alert alert-info">Salin tautan sekali pakai berikut dan berikan secara privat kepada pemilik akun. Berlaku 60 menit.<input class="form-control" readonly value="<?= html_escape($invite) ?>"></div><?php endif; ?>
<?php $roles=array('ADMIN'=>'Admin Customer','SUPERVISI'=>'Supervisi Customer','KKM'=>'KKM Customer'); ?>
<div class="box"><div class="box-body"><details><summary>Tambah User</summary>
<form method="post"><input type="hidden" name="action" value="create">
<div class="row"><div class="col-md-4 form-group"><label>User ID <input class="form-control" name="UserID" required maxlength="30"></label></div><div class="col-md-4 form-group"><label>Nama lengkap <input class="form-control" name="UserName" required maxlength="30"></label></div><div class="col-md-4 form-group"><label>Email <input class="form-control" name="Email" type="email" required maxlength="100"></label><?php if ($hasPhone): ?><label>Nomor HP <input class="form-control" name="TeamPhone" type="tel" maxlength="20" ></label><?php endif; ?></div></div>
<label>Role <select class="form-control" name="TeamRole"><?php foreach($roles as $key=>$name): ?><option value="<?= $key ?>"><?= html_escape($name) ?></option><?php endforeach; ?></select></label>
<label>Kapal untuk KKM <select class="form-control" name="VesselIDs[]" multiple><?php foreach($vessels as $vid=>$name): ?><option value="<?= (int)$vid ?>"><?= html_escape($name) ?></option><?php endforeach; ?></select></label>
<label>Status <select class="form-control" name="Active"><option value="1">Aktif</option><option value="0">Nonaktif</option></select></label>
<button class="btn btn-primary">Buat akun</button></form></details></div></div>
<div class="box"><div class="box-body table-responsive">
<form method="get" class="form-inline"><input class="form-control" name="q" aria-label="Cari user" placeholder="Cari nama, User ID, email" value="<?= html_escape($q) ?>">
<select class="form-control" name="role" aria-label="Filter role"><option value="">Semua role</option><?php foreach($roles as $key=>$name): ?><option value="<?= $key ?>" <?= $role===$key?'selected':'' ?>><?= html_escape($name) ?></option><?php endforeach; ?></select>
<select class="form-control" name="status" aria-label="Filter status"><option value="">Semua status</option><option value="1" <?= $status==='1'?'selected':'' ?>>Aktif</option><option value="0" <?= $status==='0'?'selected':'' ?>>Nonaktif</option></select><button class="btn btn-default">Cari</button></form>
<table class="table"><thead><tr><th>User</th><th>Role / Kapal</th><th>Status</th><th>Terakhir login</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($rows as $user): $uid=$user['UserID'];$assigned=$assignments[$uid]??array(); ?><tr><td><?= html_escape($user['UserName']) ?><br><small><?= html_escape($uid.' · '.$user['Email']) ?></small></td>
<td><?= html_escape($roles[$user['TeamRole']]??'Customer existing') ?><?php foreach($assigned as $vid): ?><br><?= html_escape($vessels[$vid]??'Kapal nonaktif') ?><?php endforeach; ?></td><td><?= $user['Active']==='1'?'Aktif':'Nonaktif' ?></td><td><?= html_escape($user['last_login']??'—') ?></td>
<td><?php if(strcasecmp($uid,$this->session->userdata('MIS_LOGGED_ID'))!==0): ?><details><summary>Edit / Reset akses</summary>
<form method="post" onsubmit="return confirm('Simpan perubahan akses user ini?')"><input type="hidden" name="UserID" value="<?= html_escape($uid) ?>">
<label>Nama <input class="form-control" name="UserName" value="<?= html_escape($user['UserName']) ?>" required maxlength="30"></label>
<label>Email <input class="form-control" name="Email" type="email" value="<?= html_escape($user['Email']) ?>" required></label>
<?php if ($hasPhone): ?><label>Nomor HP <input class="form-control" name="TeamPhone" type="tel" maxlength="20" value="<?= html_escape($user['TeamPhone'] ?? '') ?>"></label><?php endif; ?>
<label>Role <select class="form-control" name="TeamRole" required><option value="">Pilih role</option><?php foreach($roles as $key=>$name): ?><option value="<?= $key ?>" <?= $user['TeamRole']===$key?'selected':'' ?>><?= html_escape($name) ?></option><?php endforeach; ?></select></label>
<label>Kapal untuk KKM <select class="form-control" name="VesselIDs[]" multiple><?php foreach($vessels as $vid=>$name): ?><option value="<?= (int)$vid ?>" <?= in_array((int)$vid,$assigned,true)?'selected':'' ?>><?= html_escape($name) ?></option><?php endforeach; ?></select></label>
<label>Status <select class="form-control" name="Active"><option value="1">Aktif</option><option value="0" <?= $user['Active']!=='1'?'selected':'' ?>>Nonaktif</option></select></label>
<button class="btn btn-primary" name="action" value="edit">Simpan</button><button class="btn btn-default" name="action" value="reset" formnovalidate>Reset Akses</button></form></details><?php endif; ?></td></tr><?php endforeach; ?>
</tbody></table>
<nav aria-label="Halaman user">Halaman <?= $page ?> / <?= $pages ?>
<?php foreach(array($page-1=>'Sebelumnya',$page+1=>'Berikutnya') as $p=>$label): if($p<1||$p>$pages)continue; ?><a class="btn btn-default" href="?<?= html_escape(http_build_query(array('q'=>$q,'role'=>$role,'status'=>$status,'page'=>$p))) ?>"><?= $label ?></a><?php endforeach; ?></nav>
</div></div></section></div>
