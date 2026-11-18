<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Tetapkan password</title><link rel="stylesheet" href="<?= base_url('assets/css/ver11-ui.css') ?>"></head><body class="v11-login"><main class="v11-login-form-side" style="min-height:100vh"><div class="v11-login-card">
<h1>Tetapkan password</h1><?php if($error): ?><p role="alert"><?= html_escape($error) ?></p><?php endif; ?>
<form method="post" action="<?= site_url('Pipeline_customer_team/activate/'.$token) ?>">
<label for="team-password">Password baru (minimal 10 karakter)</label><input id="team-password" class="form-control" name="Password" type="password" minlength="10" maxlength="72" autocomplete="new-password" required>
<label for="team-confirmation">Ulangi password</label><input id="team-confirmation" class="form-control" name="Confirmation" type="password" autocomplete="new-password" required>
<button class="v11-login-submit">Simpan password</button></form></div></main></body></html>
