<div class="content-wrapper">
    <style>
        #modalTambahUser {
            z-index: 20050 !important;
            pointer-events: auto !important;
        }
        body > .modal-backdrop,
        .modal-backdrop {
            z-index: 20000 !important;
            pointer-events: auto !important;
        }
        #modalTambahUser .modal-dialog {
            z-index: 20060 !important;
            pointer-events: auto !important;
            position: relative;
        }
        #modalTambahUser .modal-content {
            pointer-events: auto !important;
        }
    </style>
    <section class="content-header">
        <h1>Pengaturan Akses User SPBB <small>Customer, Pemilik, dan Operator</small></h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome/index') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Akses User SPBB</li>
        </ol>
    </section>

    <section class="content">
        <?php if ($this->session->flashdata('Berhasil')) : ?>
            <div class="alert alert-success"><?= $this->session->flashdata('Berhasil') ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('Gagal')) : ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('Gagal') ?></div>
        <?php endif; ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Daftar User</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalTambahUser">
                        <i class="fa fa-user-plus"></i> Tambah User
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Cari User / NIB</label>
                            <input type="text" id="filterKeyword" class="form-control" placeholder="User ID, nama, atau NIB">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Filter Akses</label>
                            <select id="filterRole" class="form-control">
                                <option value="">Semua Akses</option>
                                <option value="CUSTOMER">Akses Customer</option>
                                <option value="PEMILIK_SPBB">Akses SPBB</option>
                                <option value="OPERATOR">Operator SPBB</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Filter No SPBB / Nama Cabang</label>
                            <select id="filterSpbb" class="form-control">
                                <option value="">Semua Cabang</option>
                                <?php foreach ($spbb_list as $spbb) : ?>
                                    <option value="<?= $spbb->LedgerNo ?>"><?= $spbb->No_SPBB ?> - <?= $spbb->Area ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="button" id="resetFilter" class="btn btn-default btn-block">
                                <i class="fa fa-refresh"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <small class="text-muted"><span id="filterCount"><?= count($users) ?></span> user ditampilkan</small>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped" id="tableUserAccess">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Nama User</th>
                            <th>NIB</th>
                            <th>Status</th>
                            <th>Akses</th>
                            <th>No SPBB / Nama Cabang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user) : ?>
                                <?php $selected_spbb = isset($user_spbb_access[$user->UserID]) ? $user_spbb_access[$user->UserID] : array(); ?>
                                <?php if (!count($selected_spbb) && $user->SpbbID) { $selected_spbb = array($user->SpbbID); } ?>
                            <tr data-keyword="<?= strtolower($user->UserID . ' ' . $user->UserName . ' ' . $user->NIB) ?>" data-role="<?= $user->UserRole ?>" data-spbb=",<?= implode(',', $selected_spbb) ?>,">
                                <?php $form_id = 'form-access-' . md5($user->UserID); ?>
                                <td>
                                    <?= $user->UserID ?>
                                    <form id="<?= $form_id ?>" action="<?= site_url('Spbb_user_access/update') ?>" method="post">
                                        <input type="hidden" name="UserID" value="<?= $user->UserID ?>">
                                    </form>
                                </td>
                                <td><?= $user->UserName ?></td>
                                <td><?= html_escape($user->NIB) ?></td>
                                <td>
                                    <span class="label <?= $user->Active == 1 ? 'label-success' : 'label-default' ?>">
                                        <?= $user->Active == 1 ? 'Aktif' : 'Nonaktif' ?>
                                    </span>
                                </td>
                                <td style="min-width:160px;">
                                    <select name="UserRole" form="<?= $form_id ?>" class="form-control input-sm user-role">
                                        <option value="CUSTOMER" <?= $user->UserRole == 'CUSTOMER' ? 'selected' : '' ?>>Akses Customer</option>
                                        <option value="PEMILIK_SPBB" <?= $user->UserRole == 'PEMILIK_SPBB' ? 'selected' : '' ?>>Akses SPBB</option>
                                        <option value="OPERATOR" <?= $user->UserRole == 'OPERATOR' ? 'selected' : '' ?>>Operator SPBB</option>
                                    </select>
                                </td>
                                <td style="min-width:260px;">
                                    <select name="SpbbID[]" form="<?= $form_id ?>" class="form-control input-sm spbb-select" multiple size="4">
                                        <?php foreach ($spbb_list as $spbb) : ?>
                                            <option value="<?= $spbb->LedgerNo ?>" <?= in_array($spbb->LedgerNo, $selected_spbb) ? 'selected' : '' ?>>
                                                <?= $spbb->No_SPBB ?> - <?= $spbb->Area ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="text-muted">Untuk memilih lebih dari satu cabang, gunakan Ctrl + klik.</small>
                                    <?php if (isset($user_spbb_names[$user->UserID]) && count($user_spbb_names[$user->UserID])) : ?>
                                        <br><small class="text-info">Aktif: <?= implode(', ', $user_spbb_names[$user->UserID]) ?></small>
                                    <?php endif; ?>
                                    <?php if (in_array($user->UserRole, array('PEMILIK_SPBB', 'OPERATOR')) && !count($selected_spbb)) : ?>
                                        <small class="text-danger">Mapping SPBB belum diisi. Pilih minimal satu cabang.</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="submit" form="<?= $form_id ?>" class="btn btn-sm btn-primary">
                                        <i class="fa fa-save"></i> Simpan
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="modalTambahUser" tabindex="-1" role="dialog" aria-labelledby="modalTambahUserLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="<?= site_url('Spbb_user_access/create_user') ?>" method="post" autocomplete="off">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="modalTambahUserLabel"><i class="fa fa-user-plus"></i> Tambah User dan Hak Akses</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                Buat akun baru dari menu ini lalu langsung tentukan aksesnya. Untuk <strong>Pemilik SPBB</strong> atau <strong>Operator SPBB</strong>, pilih minimal satu SPBB/Fuel Terminal.
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>User ID <span class="text-danger">*</span></label>
                                        <input type="text" name="UserID" class="form-control" maxlength="50" required placeholder="contoh: operator_merak">
                                        <small class="text-muted">Huruf, angka, titik, strip, atau underscore.</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nama User <span class="text-danger">*</span></label>
                                        <input type="text" name="UserName" class="form-control" maxlength="100" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="Email" class="form-control" maxlength="120">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Password <span class="text-danger">*</span></label>
                                        <input type="password" name="Password" class="form-control" minlength="6" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Konfirmasi Password <span class="text-danger">*</span></label>
                                        <input type="password" name="ConfirmPassword" class="form-control" minlength="6" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Status User</label>
                                        <select name="Active" class="form-control">
                                            <option value="1">Aktif</option>
                                            <option value="0">Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Hak Akses <span class="text-danger">*</span></label>
                                        <select name="UserRole" id="createUserRole" class="form-control" required>
                                            <option value="CUSTOMER">Akses Customer</option>
                                            <option value="PEMILIK_SPBB">Akses SPBB</option>
                                            <option value="OPERATOR">Operator SPBB</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4" id="createBusinessEntityWrapper">
                                    <div class="form-group">
                                        <label>Badan Usaha Customer</label>
                                        <select name="BusinessEntityID" id="createBusinessEntity" class="form-control">
                                            <option value="">Tidak dikaitkan / pilih nanti</option>
                                            <?php foreach ($business_entities as $entity) : ?>
                                                <option value="<?= $entity->BusinessEntityID ?>"><?= html_escape($entity->CompanyName . ' - NIB ' . $entity->NIB) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="text-muted">Dipakai agar customer langsung terhubung ke perusahaan dan kapal.</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Akses SPBB / Fuel Terminal</label>
                                        <select name="SpbbID[]" id="createSpbbAccess" class="form-control" multiple size="5">
                                            <?php foreach ($spbb_list as $spbb) : ?>
                                                <option value="<?= $spbb->LedgerNo ?>"><?= $spbb->No_SPBB ?> - <?= $spbb->Area ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="text-muted">Wajib untuk Pemilik SPBB / Operator SPBB. Gunakan Ctrl + klik untuk banyak cabang.</small>
                                    </div>
                                </div>
                            </div>
                            <?php if (!count($business_entities)) : ?>
                                <div class="alert alert-warning">
                                    Master badan usaha belum tersedia. User customer tetap bisa dibuat, tetapi relasi perusahaan perlu dilengkapi nanti.
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    (function() {
        var keyword = document.getElementById('filterKeyword');
        var roleFilter = document.getElementById('filterRole');
        var spbbFilter = document.getElementById('filterSpbb');
        var resetFilter = document.getElementById('resetFilter');
        var filterCount = document.getElementById('filterCount');
        var createUserRole = document.getElementById('createUserRole');
        var createSpbbAccess = document.getElementById('createSpbbAccess');
        var createBusinessEntity = document.getElementById('createBusinessEntity');
        var createBusinessEntityWrapper = document.getElementById('createBusinessEntityWrapper');
        var createUserModal = document.getElementById('modalTambahUser');

        function placeCreateUserModalOnBody() {
            if (!createUserModal) return;
            if (createUserModal.parentNode !== document.body) {
                document.body.appendChild(createUserModal);
            }
            createUserModal.style.zIndex = '20050';
            window.setTimeout(function() {
                var backdrops = document.querySelectorAll('.modal-backdrop');
                Array.prototype.forEach.call(backdrops, function(backdrop) {
                    backdrop.style.zIndex = '20000';
                });
            }, 0);
        }

        function refreshRow(row) {
            var role = row.querySelector('.user-role');
            var spbb = row.querySelector('.spbb-select');
            if (!role || !spbb) return;
            spbb.disabled = ['PEMILIK_SPBB', 'OPERATOR'].indexOf(role.value) === -1;
            if (spbb.disabled) spbb.value = '';
        }

        var rows = document.querySelectorAll('#tableUserAccess tbody tr');
        Array.prototype.forEach.call(rows, function(row) {
            refreshRow(row);
            var role = row.querySelector('.user-role');
            if (role) {
                role.addEventListener('change', function() {
                    refreshRow(row);
                });
            }
        });

        function refreshCreateUserForm() {
            if (!createUserRole || !createSpbbAccess) return;
            var role = createUserRole.value;
            var isSpbbUser = ['PEMILIK_SPBB', 'OPERATOR'].indexOf(role) !== -1;
            createSpbbAccess.disabled = !isSpbbUser;
            if (!isSpbbUser) {
                createSpbbAccess.value = '';
            }
            if (createBusinessEntity && createBusinessEntityWrapper) {
                createBusinessEntity.disabled = role !== 'CUSTOMER';
                createBusinessEntityWrapper.style.opacity = role === 'CUSTOMER' ? '1' : '.55';
                if (role !== 'CUSTOMER') {
                    createBusinessEntity.value = '';
                }
            }
        }

        if (createUserRole) {
            createUserRole.addEventListener('change', refreshCreateUserForm);
            refreshCreateUserForm();
        }

        if (createUserModal) {
            var openButtons = document.querySelectorAll('[data-target="#modalTambahUser"]');
            Array.prototype.forEach.call(openButtons, function(button) {
                button.addEventListener('click', placeCreateUserModalOnBody);
            });
            if (window.jQuery) {
                window.jQuery(createUserModal).on('show.bs.modal shown.bs.modal', placeCreateUserModalOnBody);
            }
        }

        function applyFilter() {
            var q = (keyword.value || '').toLowerCase();
            var role = roleFilter.value;
            var spbb = spbbFilter.value;
            var shown = 0;

            Array.prototype.forEach.call(rows, function(row) {
                var matchKeyword = !q || row.getAttribute('data-keyword').indexOf(q) !== -1;
                var matchRole = !role || row.getAttribute('data-role') === role;
                var matchSpbb = !spbb || row.getAttribute('data-spbb').indexOf(',' + spbb + ',') !== -1;
                var visible = matchKeyword && matchRole && matchSpbb;
                row.style.display = visible ? '' : 'none';
                if (visible) shown++;
            });

            filterCount.innerHTML = shown;
        }

        keyword.addEventListener('input', applyFilter);
        roleFilter.addEventListener('change', applyFilter);
        spbbFilter.addEventListener('change', applyFilter);
        resetFilter.addEventListener('click', function() {
            keyword.value = '';
            roleFilter.value = '';
            spbbFilter.value = '';
            applyFilter();
        });
    })();
</script>
