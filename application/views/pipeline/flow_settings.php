<div class="content-wrapper"><section class="content-header"><h1>Persyaratan Upload Pipanisasi</h1></section>
<section class="content"><?php $this->load->view('pipeline/billing_notice'); ?>
<div class="box box-primary"><div class="box-body">
<p>Centang dokumen yang wajib dilampirkan saat mengirim pesanan baru. Permohonan Bunker bertanda tangan selalu wajib. Pesanan yang sudah dibuat mengikuti pengaturan yang berlaku saat dibuat.</p>
<form method="post"><?php $this->load->view('pipeline/billing_token'); ?>
<?php foreach (array('Supervision','Notice','Payment') as $kind): ?><div class="checkbox"><label><input type="checkbox" name="<?= $kind ?>" value="1" <?= $rules[$kind]?'checked':'' ?>> <?= html_escape($labels[$kind]) ?></label></div><?php endforeach; ?>
<hr><h4>Penugasan Transportir</h4>
<div class="checkbox"><label><input type="checkbox" name="ShowTransportAssignment" value="1" <?= $showTransportAssignment?'checked':'' ?>> Tampilkan kotak Transportir pada detail pesanan</label></div>
<p>Penugasan opsional pada setiap pesanan.</p>
<?php if ($vesselSettingsReady): ?>
<hr><h4>Master Data Kapal Customer</h4>
<input type="hidden" name="VesselSettings" value="1">
<div class="checkbox"><label><input type="checkbox" name="ShowCustomerQuota" value="1" <?= $vesselSettings['show_quota']?'checked':'' ?>> Tampilkan kotak Ajukan Kuota</label></div>
<p>Centang kolom tambahan yang wajib diisi. Nama dan nomor registrasi selalu wajib.</p>
<?php foreach ($vesselFields as $key=>$field): if (in_array($key,array('VesselName','RegistrationNo'),true)) continue; ?>
<div class="checkbox"><label><input type="checkbox" name="RequiredVesselFields[]" value="<?= $key ?>" <?= in_array($key,$vesselSettings['required'],true)?'checked':'' ?>> <?= html_escape($field[0]) ?></label></div>
<?php endforeach; endif; ?>
<button class="btn btn-primary">Simpan Pengaturan</button></form></div></div></section></div>
