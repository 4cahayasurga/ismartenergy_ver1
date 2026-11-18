<div class="content-wrapper"><section class="content-header"><h1>Profil &amp; Logo Perusahaan Pelayaran</h1></section>
<section class="content"><?php $this->load->view('pipeline/billing_notice'); ?>
<?php if ($error): ?><div class="alert alert-danger"><?= html_escape($error) ?></div><?php endif; ?>
<div class="box box-primary"><div class="box-body"><p><strong><?= html_escape($company['CompanyName']) ?></strong> · NIB <?= html_escape($company['NIB']) ?></p>
<?php if ($profile): ?><img alt="Logo perusahaan" style="max-width:220px;max-height:100px" src="<?= site_url('Pipeline_flow/logo') ?>"><?php endif; ?>
<form method="post" enctype="multipart/form-data"><?php $this->load->view('pipeline/billing_token'); ?>
<div class="form-group"><label for="profile-company">Nama perusahaan</label><input id="profile-company" class="form-control" name="CompanyName" maxlength="200" required value="<?= html_escape($company['CompanyName']) ?>"></div>
<h4>Rekening Pengirim Pembayaran Customer</h4><p>Tambahkan seluruh rekening pengirim perusahaan. OCR akan mencocokkan bukti bayar dengan daftar rekening ini.</p>
<?php $this->load->library('Pipeline_bank_accounts'); $accounts=get_instance()->pipeline_bank_accounts->from_profile($profile?:array()); ?>
<input type="hidden" name="BankAccountsPresent" value="1">
<div id="company-bank-accounts">
<?php foreach($accounts as $index=>$account): ?><div class="bank-account-row well well-sm"><div class="row">
<?php foreach(array('BankAccountName'=>'Nama Pemilik Rekening','BankName'=>'Nama Bank','BankAccountNumber'=>'No. Rekening') as $field=>$label): ?><div class="col-sm-4 form-group"><label><?= $label ?></label><input class="form-control" data-account-field="<?= $field ?>" name="BankAccounts[<?= (int)$index ?>][<?= $field ?>]" maxlength="<?= $field==='BankAccountNumber'?50:($field==='BankName'?100:200) ?>" required value="<?= html_escape($account[$field]??'') ?>"></div><?php endforeach; ?></div><button type="button" class="btn btn-default remove-bank-account">Hapus Rekening</button></div><?php endforeach; ?>
</div><button type="button" class="btn btn-info" id="add-bank-account">+ Tambah Rekening</button><p class="text-muted">Klik Simpan Profil untuk menyimpan penambahan, perubahan, atau penghapusan rekening. Pemeriksaan bukti bayar lama tetap memakai rekening saat unggah.</p>
<template id="bank-account-template"><div class="bank-account-row well well-sm"><div class="row"><?php foreach(array('BankAccountName'=>'Nama Pemilik Rekening','BankName'=>'Nama Bank','BankAccountNumber'=>'No. Rekening') as $field=>$label): ?><div class="col-sm-4 form-group"><label><?= $label ?></label><input class="form-control" data-account-field="<?= $field ?>" maxlength="<?= $field==='BankAccountNumber'?50:($field==='BankName'?100:200) ?>" required></div><?php endforeach; ?></div><button type="button" class="btn btn-default remove-bank-account">Hapus Rekening</button></div></template>
<script>(function(){var box=document.getElementById('company-bank-accounts'),add=document.getElementById('add-bank-account');function names(){Array.prototype.forEach.call(box.children,function(row,i){row.querySelectorAll('[data-account-field]').forEach(function(input){input.name='BankAccounts['+i+']['+input.dataset.accountField+']';});});add.disabled=box.children.length>=30;}add.addEventListener('click',function(){if(box.children.length>=30)return;box.appendChild(document.getElementById('bank-account-template').content.cloneNode(true));names();box.lastElementChild.querySelector('input').focus();});box.addEventListener('click',function(e){if(e.target.classList.contains('remove-bank-account')){e.target.closest('.bank-account-row').remove();names();}});names();})();</script>
<p>Jika kode kosong, isi nomor surat secara manual saat membuat permohonan Bunkering.</p>
<div class="row"><?php foreach (array('IssuerCode'=>array('Kode penerbit surat (opsional), misalnya ASDP-PB',30,false),'Address'=>array('Alamat kop surat',500,true),'Phone'=>array('Telepon',100,false),'Website'=>array('Situs web',150,false)) as $key=>$field): ?>
<div class="col-sm-6 form-group"><label for="profile-<?= $key ?>"><?= $field[0] ?></label>
<input id="profile-<?= $key ?>" class="form-control" name="<?= $key ?>" maxlength="<?= $field[1] ?>" value="<?= html_escape($profile[$key]??'') ?>" <?= $field[2]?'required':'' ?>></div>
<?php endforeach; ?></div>
<div class="form-group"><label for="shipping-logo">Logo JPG/PNG, maks. 5 MB</label><input id="shipping-logo" type="file" name="Logo" accept="image/png,image/jpeg" <?= $profile?'':'required' ?>></div>
<p class="text-muted">Perubahan logo digunakan pada surat baru. Surat yang sudah dibuat menyimpan logo yang digunakan saat penerbitan.</p>
<button class="btn btn-primary">Simpan Profil</button></form></div></div></section></div>
