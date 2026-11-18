<div class="content-wrapper">
    <section class="content-header">
        <h1>Data Material dan Harga <small>Master BBM per SPBB</small></h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome/index') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Data Material</li>
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
                    <div class="box-header with-border">
                        <h3 class="box-title" id="formTitle">Input Material dan Harga</h3>
                    </div>
                    <form action="<?= site_url('Spbb_material/save') ?>" method="post" id="materialForm">
                        <input type="hidden" name="FuelID" id="FuelID">
                        <div class="box-body">
                            <div class="form-group">
                                <label>SPBB / Cabang</label>
                                <select class="form-control" name="SpbbID" id="SpbbID" required>
                                    <option value="">Pilih SPBB</option>
                                    <?php foreach ($spbb_list as $spbb) : ?>
                                        <option value="<?= $spbb['SpbbID'] ?>"><?= $spbb['SpbbName'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Kode Material</label>
                                <input type="text" name="FuelCode" id="FuelCode" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Material</label>
                                <input type="text" name="FuelName" id="FuelName" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Harga / Liter</label>
                                <input type="number" step="0.01" min="0" name="UnitPrice" id="UnitPrice" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Efektif Harga</label>
                                <input type="date" name="EffectiveDate" id="EffectiveDate" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" name="IsActive" id="IsActive" value="1" checked> Aktif</label>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="button" class="btn btn-default" id="resetForm">Reset</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Daftar Material Aktif</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped js-app-datatable">
                            <thead>
                                <tr>
                                    <th>SPBB</th>
                                    <th>Kode</th>
                                    <th>Nama Material</th>
                                    <th>Harga</th>
                                    <th>Efektif</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($materials as $row) : ?>
                                    <tr>
                                        <td><?= $row['SpbbName'] ?></td>
                                        <td><?= $row['FuelCode'] ?></td>
                                        <td><?= $row['FuelName'] ?></td>
                                        <td>Rp <?= number_format($row['UnitPrice']) ?></td>
                                        <td><?= $row['EffectiveDate'] ?></td>
                                        <td><span class="label <?= $row['IsActive'] ? 'label-success' : 'label-default' ?>"><?= $row['IsActive'] ? 'Aktif' : 'Nonaktif' ?></span></td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-primary edit-material"
                                                data-fuelid="<?= $row['FuelID'] ?>"
                                                data-spbbid="<?= $row['SpbbID'] ?>"
                                                data-code="<?= $row['FuelCode'] ?>"
                                                data-name="<?= $row['FuelName'] ?>"
                                                data-price="<?= $row['UnitPrice'] ?>"
                                                data-effective="<?= $row['EffectiveDate'] ?>"
                                                data-active="<?= $row['IsActive'] ?>">
                                                <i class="fa fa-edit"></i> Edit Harga
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (!count($materials)) : ?>
                                    <tr><td colspan="7" class="text-center">Belum ada material</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">History Perubahan Harga</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped js-app-datatable">
                            <thead>
                                <tr>
                                    <th>Tanggal Update</th>
                                    <th>SPBB</th>
                                    <th>Material</th>
                                    <th>Harga Lama</th>
                                    <th>Harga Baru</th>
                                    <th>Efektif</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($price_history as $history) : ?>
                                    <tr>
                                        <td><?= $history['CreatedAt'] ?></td>
                                        <td><?= $history['SpbbName'] ?></td>
                                        <td><?= $history['FuelName'] ?></td>
                                        <td>Rp <?= number_format($history['OldPrice']) ?></td>
                                        <td>Rp <?= number_format($history['NewPrice']) ?></td>
                                        <td><?= $history['EffectiveDate'] ?></td>
                                        <td><?= $history['CreatedBy'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (!count($price_history)) : ?>
                                    <tr><td colspan="7" class="text-center">Belum ada history perubahan harga</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    (function() {
        var formTitle = document.getElementById('formTitle');
        var reset = document.getElementById('resetForm');
        var fields = ['FuelID', 'SpbbID', 'FuelCode', 'FuelName', 'UnitPrice', 'EffectiveDate'];

        function setValue(id, value) {
            document.getElementById(id).value = value || '';
        }

        Array.prototype.forEach.call(document.querySelectorAll('.edit-material'), function(btn) {
            btn.addEventListener('click', function() {
                formTitle.innerHTML = 'Edit Material dan Harga';
                setValue('FuelID', btn.getAttribute('data-fuelid'));
                setValue('SpbbID', btn.getAttribute('data-spbbid'));
                setValue('FuelCode', btn.getAttribute('data-code'));
                setValue('FuelName', btn.getAttribute('data-name'));
                setValue('UnitPrice', btn.getAttribute('data-price'));
                setValue('EffectiveDate', btn.getAttribute('data-effective'));
                document.getElementById('IsActive').checked = btn.getAttribute('data-active') == '1';
                window.scrollTo(0, 0);
            });
        });

        reset.addEventListener('click', function() {
            formTitle.innerHTML = 'Input Material dan Harga';
            fields.forEach(function(id) {
                setValue(id, id === 'EffectiveDate' ? '<?= date('Y-m-d') ?>' : '');
            });
            document.getElementById('IsActive').checked = true;
        });
    })();
</script>
