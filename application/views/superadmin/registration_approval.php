<div class="content-wrapper">
    <section class="content-header">
        <h1>Approval Registrasi User <small>SuperAdmin</small></h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome/index') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Approval Registrasi</li>
        </ol>
    </section>

    <section class="content">
        <?php if ($this->session->flashdata('Berhasil')) : ?>
            <div class="alert alert-success"><?= $this->session->flashdata('Berhasil') ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('Gagal')) : ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('Gagal') ?></div>
        <?php endif; ?>

        <div class="box box-success" id="customer-user-requests">
            <div class="box-header with-border"><h3 class="box-title">Pengajuan User Tambahan dari Customer</h3></div>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead><tr><th>Tanggal</th><th>Perusahaan</th><th>Pengaju</th><th>User Baru</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                    <?php foreach ($customer_user_requests as $request): ?>
                        <tr>
                            <td><?= html_escape($request->CreatedAt) ?></td>
                            <td><strong><?= html_escape($request->CompanyName) ?></strong><br>NIB: <?= html_escape($request->NIB) ?></td>
                            <td><?= html_escape($request->RequestedBy) ?></td>
                            <td><strong><?= html_escape($request->UserID) ?></strong><br><?= html_escape($request->UserName) ?><br><?= html_escape($request->Email) ?><br><?= html_escape($request->PositionName) ?></td>
                            <td><span class="label label-<?= $request->Status==='APPROVED'?'success':($request->Status==='REJECTED'?'danger':'warning') ?>"><?= html_escape($request->Status) ?></span><br><?= html_escape($request->AdminNotes) ?></td>
                            <td>
                            <?php if ($request->Status === 'PENDING'): ?>
                                <form method="post" action="<?= site_url('Superadmin_registration/approve_customer_user/'.$request->UserRequestID) ?>"><?php $this->load->view('pipeline/billing_token'); ?>
                                    <textarea name="AdminNotes" class="form-control input-sm" placeholder="Catatan Admin"></textarea>
                                    <button class="btn btn-xs btn-success" style="margin-top:5px">Approve & Buat Akun</button>
                                </form>
                                <form method="post" action="<?= site_url('Superadmin_registration/reject_customer_user/'.$request->UserRequestID) ?>" style="margin-top:5px"><?php $this->load->view('pipeline/billing_token'); ?>
                                    <input name="AdminNotes" class="form-control input-sm" placeholder="Alasan penolakan" required>
                                    <button class="btn btn-xs btn-danger" style="margin-top:5px">Tolak</button>
                                </form>
                            <?php else: ?>Diproses oleh <?= html_escape($request->ReviewedBy) ?><?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!count($customer_user_requests)): ?><tr><td colspan="6" class="text-center">Belum ada pengajuan user dari customer.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Daftar Registrasi</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>User</th>
                            <th>Perusahaan</th>
                            <th>Kontak</th>
                            <th>Akses</th>
                            <th>Cabang</th>
                            <th>Status</th>
                            <th>Konfirmasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registrations as $row) : ?>
                            <tr>
                                <td><?= $row->CreatedAt ?></td>
                                <td>
                                    <strong><?= $row->UserID ?></strong><br>
                                    <?= $row->UserName ?><br>
                                    <small>NIB: <?= html_escape($row->NIB) ?></small>
                                </td>
                                <td>
                                    <strong><?= isset($row->CompanyName) ? html_escape($row->CompanyName) : '-' ?></strong><br>
                                    <small>
                                        NPWP: <?= html_escape(isset($row->NPWP) ? $row->NPWP : '-') ?><br>
                                        Akta: <?= html_escape(isset($row->DeedNo) ? $row->DeedNo : '-') ?><br>
                                        AHU: <?= html_escape(isset($row->AhuNo) ? $row->AhuNo : '-') ?><br>
                                        Rekening: <?= html_escape(isset($row->BankName) ? $row->BankName : '-') ?> /
                                        <?= html_escape(isset($row->BankAccountNo) ? $row->BankAccountNo : '-') ?> /
                                        <?= html_escape(isset($row->BankAccountName) ? $row->BankAccountName : '-') ?>
                                    </small><br>
                                    <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modalDokumenRegistrasi<?= (int)$row->RegistrationID ?>">
                                        <i class="fa fa-folder-open"></i> Lihat Dokumen
                                    </button>
                                </td>
                                <td>
                                    <?= $row->Email ?><br>
                                    <?= $row->Phone ?>
                                </td>
                                <td>
                                    <?php if ($row->RequestedRole == 'PEMILIK_SPBB') : ?>
                                        Akses SPBB
                                    <?php elseif ($row->RequestedRole == 'OPERATOR') : ?>
                                        Operator SPBB
                                    <?php elseif ($row->RequestedRole == 'CUSTOMER') : ?>
                                        Akses Customer
                                    <?php else : ?>
                                        Belum ditentukan
                                    <?php endif; ?>
                                </td>
                                <td><?= isset($spbb_names[$row->RegistrationID]) ? $spbb_names[$row->RegistrationID] : '-' ?></td>
                                <td>
                                    <span class="label <?= $row->Status == 'PENDING' ? 'label-warning' : ($row->Status == 'APPROVED' ? 'label-success' : 'label-danger') ?>">
                                        <?= $row->Status ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($row->Status !== 'PENDING') : ?>
                                        Email: <?= $row->EmailSent ? 'Terkirim' : 'Tidak terkirim' ?><br>
                                        <?php if ($row->WhatsappUrl) : ?>
                                            <a href="<?= $row->WhatsappUrl ?>" target="_blank" class="btn btn-xs btn-success">
                                                Kirim WhatsApp
                                            </a>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        Menunggu approval
                                    <?php endif; ?>
                                </td>
                                <td style="min-width:220px;">
                                    <?php if ($row->Status == 'PENDING') : ?>
                                        <form action="<?= site_url('Superadmin_registration/approve/' . $row->RegistrationID) ?>" method="post" style="margin-bottom:6px;"><?php $this->load->view('pipeline/billing_token'); ?>
                                            <div class="form-group">
                                                <select name="UserRole" class="form-control input-sm approval-role" required>
                                                    <option value="">Pilih Akses</option>
                                                    <option value="CUSTOMER">Akses Customer</option>
                                                    <option value="PEMILIK_SPBB">Akses SPBB</option>
                                                    <option value="OPERATOR">Operator SPBB</option>
                                                </select>
                                            </div>
                                            <div class="form-group approval-spbb" style="display:none;">
                                                <select name="SpbbID[]" class="form-control input-sm" multiple size="4">
                                                    <?php foreach ($spbb_list as $spbb) : ?>
                                                        <option value="<?= $spbb->LedgerNo ?>"><?= $spbb->No_SPBB ?> - <?= $spbb->Area ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <small class="text-muted">Gunakan Ctrl + klik untuk multi cabang.</small>
                                            </div>
                                            <textarea name="ApprovalNotes" class="form-control input-sm" placeholder="Catatan approval"></textarea>
                                            <button class="btn btn-xs btn-success" style="margin-top:5px;">
                                                <i class="fa fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form action="<?= site_url('Superadmin_registration/reject/' . $row->RegistrationID) ?>" method="post"><?php $this->load->view('pipeline/billing_token'); ?>
                                            <textarea name="ApprovalNotes" class="form-control input-sm" placeholder="Alasan reject"></textarea>
                                            <button class="btn btn-xs btn-danger" style="margin-top:5px;">
                                                <i class="fa fa-times"></i> Reject
                                            </button>
                                        </form>
                                    <?php else : ?>
                                        <small><?= $row->ApprovalNotes ?></small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php foreach ($registrations as $row) : ?>
        <div class="modal fade registration-document-modal" id="modalDokumenRegistrasi<?= (int)$row->RegistrationID ?>" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-folder-open"></i> Dokumen Registrasi - <?= html_escape($row->CompanyName) ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered table-condensed">
                                    <tr><th>User ID</th><td><?= html_escape($row->UserID) ?></td></tr>
                                    <tr><th>Nama</th><td><?= html_escape($row->UserName) ?></td></tr>
                                    <tr><th>Nama Perusahaan</th><td><?= html_escape($row->CompanyName) ?></td></tr>
                                    <tr><th>NIB</th><td><?= html_escape($row->NIB) ?></td></tr>
                                    <tr><th>NPWP</th><td><?= html_escape($row->NPWP) ?></td></tr>
                                    <tr><th>Alamat</th><td><?= nl2br(html_escape($row->CompanyAddress)) ?></td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered table-condensed">
                                    <tr><th>No. Akta</th><td><?= html_escape($row->DeedNo) ?></td></tr>
                                    <tr><th>Tanggal Akta</th><td><?= html_escape($row->DeedDate) ?></td></tr>
                                    <tr><th>Notaris</th><td><?= html_escape($row->NotaryName) ?></td></tr>
                                    <tr><th>No. AHU</th><td><?= html_escape($row->AhuNo) ?></td></tr>
                                    <tr><th>Izin Usaha</th><td><?= html_escape($row->BusinessLicenseNo) ?></td></tr>
                                    <tr><th>Status Registrasi</th><td><span class="label <?= $row->Status==='APPROVED'?'label-success':($row->Status==='REJECTED'?'label-danger':'label-warning') ?>"><?= html_escape($row->Status) ?></span></td></tr>
                                </table>
                            </div>
                        </div>
                        <h4>File Legalitas</h4>
                        <p class="text-muted">Sumber dokumen: <?= $row->DocumentSource === 'BUSINESS_ENTITY' ? 'dokumen terbaru pada master badan usaha' : 'dokumen saat registrasi' ?>.</p>
                        <table class="table table-bordered">
                            <thead><tr><th>Dokumen</th><th>Status</th><th>Aksi</th></tr></thead>
                            <tbody>
                            <?php foreach (array('NibFile'=>'NIB','NpwpFile'=>'NPWP','DeedFile'=>'Akta Perusahaan','AhuFile'=>'SK Kemenkumham/AHU') as $field=>$label): ?>
                                <tr>
                                    <td><?= $label ?></td>
                                    <td><span class="label label-<?= !empty($row->$field)?'success':'default' ?>"><?= !empty($row->$field)?'SUDAH DIUPLOAD':'BELUM DIUPLOAD' ?></span></td>
                                    <td>
                                        <?php if (!empty($row->$field)): ?>
                                            <a target="_blank" class="btn btn-sm btn-primary" href="<?= site_url('Pipeline_legal/file/'.rawurlencode($row->$field)) ?>"><i class="fa fa-eye"></i> Buka Dokumen</a>
                                        <?php else: ?>-<?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </section>
</div>

<script>
    (function() {
        var roles = document.querySelectorAll('.approval-role');
        Array.prototype.forEach.call(roles, function(role) {
            var form = role.closest('form');
            var spbb = form ? form.querySelector('.approval-spbb') : null;
            function toggle() {
                if (spbb) {
                    spbb.style.display = ['PEMILIK_SPBB', 'OPERATOR'].indexOf(role.value) !== -1 ? 'block' : 'none';
                }
            }
            role.addEventListener('change', toggle);
            toggle();
        });
    })();

    $(function() {
        $('.registration-document-modal').appendTo(document.body);
        $('.registration-document-modal').on('shown.bs.modal', function() {
            $(this).css('z-index', 1060);
            $('.modal-backdrop').last().css('z-index', 1050);
        }).on('hidden.bs.modal', function() {
            if (!$('.modal.in:visible').length) {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('padding-right', '');
            }
        });
    });
</script>
