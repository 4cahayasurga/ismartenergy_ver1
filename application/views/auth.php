<!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Masuk | iSmartEnergy 1.1</title>
<link rel="icon" href="<?= base_url($this->config->item('PATH_LogoCompany')) ?>">
<link rel="stylesheet" href="<?= base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/ver11-ui.css') ?>"></head>
<body class="v11-login"><main class="v11-login-layout">
<section class="v11-login-story" aria-label="Tentang iSmartEnergy"><div class="v11-login-wordmark">iSmartEnergy<span>1.1</span></div><div><div class="v11-login-eyebrow">Operasional pipanisasi</div><h1>Setiap pengisian.<br>Terhubung dengan jelas.</h1><p>Kelola permohonan, dokumen, realisasi pengisian, dan penagihan dalam satu ruang kerja.</p><div class="v11-login-trail"><span>01 &nbsp; Permohonan</span><span>02 &nbsp; Pengisian</span><span>03 &nbsp; Administrasi</span></div></div><div class="v11-login-footer">INDONESIA FERRY PROPERTY · iSmartEnergy</div></section>
<section class="v11-login-form-side"><div class="v11-login-card"><img src="<?= base_url($this->config->item('PATH_LogoCompany')) ?>" alt="Indonesia Ferry Property"><h2>Selamat datang kembali</h2><p>Masuk untuk melanjutkan aktivitas Anda.</p>
<?= $this->session->flashdata('pesan') ?>
<form action="<?= site_url('Auth') ?>" method="post">
<?php if($this->config->item('csrf_protection')): ?><input type="hidden" name="<?= html_escape($this->security->get_csrf_token_name()) ?>" value="<?= html_escape($this->security->get_csrf_hash()) ?>"><?php endif; ?>
<input type="hidden" name="corp" value="<?= html_escape($this->config->item('Company')) ?>">
<div class="form-group"><label for="username">User ID</label><input id="username" name="username" class="form-control" autocomplete="username" required placeholder="Masukkan User ID Anda" autocapitalize="none" spellcheck="false"></div>
<div class="form-group"><label for="password">Password</label><div class="v11-login-password"><input type="password" id="password" name="password" class="form-control" autocomplete="current-password" required placeholder="Masukkan password"><button type="button" id="password-toggle" aria-controls="password" aria-pressed="false">Lihat</button></div></div>
<button type="submit" name="login" class="v11-login-submit">Masuk ke ruang kerja &nbsp; →</button></form><p class="v11-login-help">Gunakan akun yang telah disetujui. Hubungi administrator jika akses Anda belum tersedia.</p></div></section></main>
<script>document.getElementById('password-toggle').addEventListener('click',function(){var field=document.getElementById('password'),show=field.type==='password';field.type=show?'text':'password';this.textContent=show?'Sembunyikan':'Lihat';this.setAttribute('aria-pressed',show?'true':'false');});</script></body></html>
