<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login - MyIfpro</title>
	<link rel="shortcut icon" href="<?= base_url('assets/image/logo/LogoifpronlyKecil.png') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>">
</head>

<body>
	<?php if ($this->session->flashdata('pesan')) : ?>
		<div style="position:fixed;top:16px;left:50%;transform:translateX(-50%);z-index:9999;width:min(92%,560px);">
			<?= $this->session->flashdata('pesan') ?>
		</div>
	<?php endif; ?>
	<div class="container">
		<div class="card">
			<div class="left">
				<img src="<?= base_url('assets/image/logo/icon2.jpg') ?>" alt="">
			</div>
			<div class="right">
				<div class="logo">
					<div class="small-logo" style="width: max-content;">
						<img src="<?= base_url('assets/image/logo/FastFuel_kecil.png') ?>" alt="">
					</div>
					<!-- <h1>MyIfpro</h1> -->
				</div>
				<p>Enter your username & password to login</p>
				<form id="formLogin" action="<?= site_url('Auth/index') ?>" method="POST">
					<div class="inp-outer">
						<div class="input-grup">
							<label for="username">Username</label>
							<input type="text" id="username" name="username" autocomplete="off">
						</div>
						<div class="input-grup">
							<label for="password">Password</label>
							<input type="password" id="password" name="password">
						</div>
						<div class="input-grup">
							<button type="submit" id="login" name="login">Login</button>
						</div>
						<p>Don't have account? <a href="<?= site_url('Register') ?>">Create an account</a></p>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="container2">
		<div class="content">
			<div class="top">
				<div class="contImg">
					<img src="<?= base_url('assets/image/logo/logoifpronlyKecil.png') ?>" alt="">
				</div>
				<h1>MyIfpro</h1>
			</div>

			<div class="bottom">
				<div class="card2">
					<h2>Wellcome Back!</h2>
					<p class="msg">Enter your username & password to login</p>
					<form id="formLogin" action="<?= site_url('Auth/index') ?>" method="POST">
						<div class="inp-outer">
							<div class="input-grup">
								<label for="username">Username</label>
								<input type="text" id="username" name="username" autocomplete="off">
							</div>
							<div class="input-grup">
								<label for="password">Password</label>
								<input type="password" id="password" name="password">
							</div>
							<div class="input-grup">
								<button type="submit" id="btnlogin">Login</button>
							</div>
							<p>Don't have account? <a href="<?= site_url('Register') ?>">Create an account</a></p>
						</div>
					</form>
				</div>
			</div>

		</div>

	</div>

	<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
	<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</body>

</html>
