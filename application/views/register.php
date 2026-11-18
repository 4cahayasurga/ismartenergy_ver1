<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - iSmartEnergi</title>
    <link rel="stylesheet" href="<?= base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>">
    <style>
        body { background: #f4f6f9; }
        .register-wrap { max-width: 760px; margin: 35px auto; }
        .panel { border-radius: 6px; }
    </style>
</head>

<body>
    <div class="register-wrap">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Registrasi Akun iSmartEnergi</h3>
            </div>
            <div class="panel-body">
                <?php if ($this->session->flashdata('Berhasil')) : ?>
                    <div class="alert alert-success"><?= $this->session->flashdata('Berhasil') ?></div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('Gagal')) : ?>
                    <div class="alert alert-danger"><?= $this->session->flashdata('Gagal') ?></div>
                <?php endif; ?>

                <form action="<?= site_url('Register/submit') ?>" method="post" enctype="multipart/form-data">
                    <div class="alert alert-info">
                        <strong>Perusahaan sudah terdaftar?</strong>
                        Jangan membuat registrasi perusahaan baru. Mintalah user perusahaan yang sudah aktif mengajukan akun Anda melalui menu <b>Pengajuan User</b> setelah login.
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>User ID</label>
                                <input type="text" name="UserID" class="form-control" maxlength="30" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" name="UserName" class="form-control" maxlength="30" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>NIB</label>
                                <input type="text" name="NIB" class="form-control" maxlength="20" pattern="[0-9]{13}" title="NIB harus 13 digit" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="Email" class="form-control" maxlength="100" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>No WhatsApp</label>
                                <input type="text" name="Phone" class="form-control" maxlength="30" placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nama Perusahaan</label>
                        <input type="text" name="CompanyName" class="form-control" maxlength="150" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat Lengkap Perusahaan</label>
                        <textarea name="CompanyAddress" class="form-control" rows="3" maxlength="500" required></textarea>
                    </div>

                    <h4>Legalitas Perusahaan</h4>
                    <div class="row">
                        <div class="col-md-4"><div class="form-group"><label>NPWP</label><input name="NPWP" class="form-control" maxlength="30" required></div></div>
                        <div class="col-md-4"><div class="form-group"><label>No. Akta</label><input name="DeedNo" class="form-control" maxlength="100" required></div></div>
                        <div class="col-md-4"><div class="form-group"><label>Tanggal Akta</label><input type="date" name="DeedDate" class="form-control" required></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><div class="form-group"><label>Nama Notaris</label><input name="NotaryName" class="form-control" maxlength="150" required></div></div>
                        <div class="col-md-4"><div class="form-group"><label>No. SK Kemenkumham/AHU</label><input name="AhuNo" class="form-control" maxlength="100" required></div></div>
                        <div class="col-md-4"><div class="form-group"><label>No. Izin Usaha</label><input name="BusinessLicenseNo" class="form-control" maxlength="100" required></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><div class="form-group"><label>Dokumen NIB <small>(opsional)</small></label><input type="file" name="NibFile" class="form-control" accept=".pdf,.jpg,.jpeg,.png"></div></div>
                        <div class="col-md-3"><div class="form-group"><label>Dokumen NPWP <small>(opsional)</small></label><input type="file" name="NpwpFile" class="form-control" accept=".pdf,.jpg,.jpeg,.png"></div></div>
                        <div class="col-md-3"><div class="form-group"><label>Dokumen Akta <small>(opsional)</small></label><input type="file" name="DeedFile" class="form-control" accept=".pdf,.jpg,.jpeg,.png"></div></div>
                        <div class="col-md-3"><div class="form-group"><label>Dokumen AHU <small>(opsional)</small></label><input type="file" name="AhuFile" class="form-control" accept=".pdf,.jpg,.jpeg,.png"></div></div>
                    </div>

                    <h4>Rekening Sumber Pembayaran</h4>
                    <div class="alert alert-info">Rekening ini wajib digunakan saat membayar pesanan dan akan divalidasi ketika bukti pembayaran diupload.</div>
                    <div class="row">
                        <div class="col-md-4"><div class="form-group"><label>Nama Bank</label><input name="BankName" class="form-control" maxlength="100" required></div></div>
                        <div class="col-md-4"><div class="form-group"><label>Nomor Rekening</label><input name="BankAccountNo" class="form-control" inputmode="numeric" maxlength="50" required></div></div>
                        <div class="col-md-4"><div class="form-group"><label>Nama Pemilik Rekening</label><input name="BankAccountName" class="form-control" maxlength="150" required></div></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="Password" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Konfirmasi Password</label>
                                <input type="password" name="ConfirmPassword" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <a href="<?= site_url('Auth') ?>" class="btn btn-default">Kembali Login</a>
                        <button type="submit" class="btn btn-primary">Kirim Registrasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
