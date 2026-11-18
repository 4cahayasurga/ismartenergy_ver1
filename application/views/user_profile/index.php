<?php $this->load->library('Pipeline_access'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Profile User <small>Update data akun login</small></h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url(get_instance()->pipeline_access->landing()) ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Profile User</li>
        </ol>
    </section>

    <section class="content">
        <?php if ($this->session->flashdata('Berhasil')) : ?>
            <div class="alert alert-success"><?= $this->session->flashdata('Berhasil') ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('Gagal')) : ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('Gagal') ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="<?= site_url('images/img-profile-default.png') ?>" alt="User profile picture">
                        <h3 class="profile-username text-center"><?= html_escape($user->UserName) ?></h3>
                        <p class="text-muted text-center"><?= html_escape(isset($user->UserRole) ? $user->UserRole : 'USER') ?></p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>User ID</b> <span class="pull-right"><?= html_escape($user->UserID) ?></span>
                            </li>
                            <?php if (isset($user->NIK) && $user->NIK !== '') : ?>
                                <li class="list-group-item">
                                    <b>NIK</b> <span class="pull-right"><?= html_escape($user->NIK) ?></span>
                                </li>
                            <?php endif; ?>
                            <?php if (isset($user->NIB) && $user->NIB !== '') : ?>
                                <li class="list-group-item">
                                    <b>NIB</b> <span class="pull-right"><?= html_escape($user->NIB) ?></span>
                                </li>
                            <?php endif; ?>
                            <li class="list-group-item">
                                <b>Status</b>
                                <span class="pull-right">
                                    <span class="label <?= isset($user->Active) && $user->Active == 1 ? 'label-success' : 'label-default' ?>">
                                        <?= isset($user->Active) && $user->Active == 1 ? 'Aktif' : 'Nonaktif' ?>
                                    </span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <?php if ($company) : ?>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Perusahaan Terkait</h3>
                        </div>
                        <div class="box-body">
                            <p><strong><?= html_escape($company->CompanyName) ?></strong></p>
                            <p class="text-muted">NIB: <?= html_escape($company->NIB) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-8">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Update Profile</h3>
                    </div>
                    <form method="post" action="<?= site_url('User_profile/update') ?>">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>User ID</label>
                                        <input type="text" class="form-control" value="<?= html_escape($user->UserID) ?>" readonly>
                                        <small class="text-muted">User ID tidak dapat diubah dari menu profile.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Hak Akses</label>
                                        <input type="text" class="form-control" value="<?= html_escape(isset($user->UserRole) ? $user->UserRole : 'USER') ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama User <span class="text-danger">*</span></label>
                                        <input type="text" name="UserName" class="form-control" maxlength="100" required value="<?= html_escape($user->UserName) ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="Email" class="form-control" maxlength="120" value="<?= html_escape(isset($user->Email) ? $user->Email : '') ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <?php foreach (array('Phone' => 'No. Telepon', 'PhoneNo' => 'No. Telepon', 'MobilePhone' => 'No. HP', 'NoHP' => 'No. HP', 'Handphone' => 'No. HP', 'Telp' => 'No. Telepon') as $field => $label) : ?>
                                    <?php if (in_array($field, $available_fields)) : ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?= $label ?></label>
                                                <input type="text" name="<?= $field ?>" class="form-control" maxlength="50" value="<?= html_escape(isset($user->$field) ? $user->$field : '') ?>">
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <?php foreach (array('Jabatan' => 'Jabatan', 'PositionName' => 'Jabatan') as $field => $label) : ?>
                                    <?php if (in_array($field, $available_fields)) : ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?= $label ?></label>
                                                <input type="text" name="<?= $field ?>" class="form-control" maxlength="100" value="<?= html_escape(isset($user->$field) ? $user->$field : '') ?>">
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>

                            <div class="alert alert-info">
                                Data seperti User ID, NIB, perusahaan, dan hak akses tidak diubah dari menu ini agar relasi kuota, kapal, dan approval tetap aman.
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Profile</button>
                            <a href="#changePassword" data-toggle="modal" class="btn btn-default"><i class="fa fa-key"></i> Ganti Password</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
