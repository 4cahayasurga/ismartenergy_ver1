<div class="content-wrapper">
    <section class="content-header"><h1>Penerimaan BBM <small>Pemeriksaan pengiriman dari PERTAMINA Patra Niaga</small></h1></section>
    <section class="content">
        <?php if ($this->session->flashdata('Berhasil')) : ?><div class="alert alert-success"><?= html_escape($this->session->flashdata('Berhasil')) ?></div><?php endif; ?>
        <?php if ($this->session->flashdata('Gagal')) : ?><div class="alert alert-danger"><?= html_escape($this->session->flashdata('Gagal')) ?></div><?php endif; ?>
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Input Penerimaan</h3></div>
            <form action="<?= site_url('Spbb_receipt/save') ?>" method="post">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4"><div class="form-group"><label>PO PERTAMINA Patra Niaga</label><select name="PurchaseID" class="form-control" required>
                            <option value="">Pilih PO yang belum selesai</option>
                            <?php foreach ($open_purchases as $po) : ?><option value="<?= $po['PurchaseID'] ?>"><?= html_escape($po['PoNumber'] . ' | ' . $po['SpbbName'] . ' | ' . $po['FuelName'] . ' | Sisa ' . number_format($po['RemainingQty'], 2) . ' L') ?></option><?php endforeach; ?>
                        </select></div></div>
                        <div class="col-md-2"><div class="form-group"><label>No. Penerimaan</label><input name="ReceiptNo" class="form-control" required></div></div>
                        <div class="col-md-2"><div class="form-group"><label>No. Surat Jalan</label><input name="DeliveryNoteNo" class="form-control" required></div></div>
                        <div class="col-md-2"><div class="form-group"><label>Tanggal</label><input type="date" name="ReceiptDate" value="<?= date('Y-m-d') ?>" class="form-control" required></div></div>
                        <div class="col-md-2"><div class="form-group"><label>Jam</label><input type="time" name="ReceiptTime" value="<?= date('H:i') ?>" class="form-control"></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><div class="form-group"><label>No. Kendaraan</label><input name="VehicleNo" class="form-control"></div></div>
                        <div class="col-md-3"><div class="form-group"><label>Nama Sopir</label><input name="DriverName" class="form-control"></div></div>
                        <div class="col-md-3"><div class="form-group"><label>No. Seal</label><input name="SealNumber" class="form-control"></div></div>
                        <div class="col-md-3"><div class="form-group"><label>Hasil Pemeriksaan</label><select name="QualityStatus" class="form-control" required><option value="SESUAI">Sesuai</option><option value="SESUAI_DENGAN_CATATAN">Sesuai dengan Catatan</option><option value="DITOLAK">Ditolak</option></select></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><div class="form-group"><label>Volume Diterima (Liter)</label><input type="number" min="0.01" step="0.01" name="ReceivedQuantity" class="form-control" required></div></div>
                        <div class="col-md-3"><div class="form-group"><label>Volume Ditolak (Liter)</label><input type="number" min="0" step="0.01" name="RejectedQuantity" value="0" class="form-control"></div></div>
                        <div class="col-md-3"><div class="form-group"><label>Temperatur (C)</label><input type="number" step="0.01" name="Temperature" class="form-control"></div></div>
                        <div class="col-md-3"><div class="form-group"><label>Density</label><input type="number" step="0.0001" name="Density" class="form-control"></div></div>
                    </div>
                    <div class="form-group"><label>Catatan Pemeriksaan</label><textarea name="InspectionNotes" class="form-control" rows="2"></textarea></div>
                </div>
                <div class="box-footer"><button class="btn btn-primary"><i class="fa fa-save"></i> Simpan Penerimaan</button></div>
            </form>
        </div>
        <div class="box box-info"><div class="box-header with-border"><h3 class="box-title">Riwayat Penerimaan</h3></div>
            <div class="box-body table-responsive"><table class="table table-bordered table-striped js-app-datatable">
                <thead><tr><th>Penerimaan</th><th>PO / SPBB</th><th>Material</th><th>Surat Jalan</th><th>Kendaraan / Sopir</th><th>Diterima</th><th>Ditolak</th><th>QC</th><th>Petugas</th></tr></thead>
                <tbody><?php foreach ($receipts as $row) : ?><tr>
                    <td><strong><?= html_escape($row['ReceiptNo']) ?></strong><br><small><?= html_escape($row['ReceiptDate'] . ' ' . $row['ReceiptTime']) ?></small></td>
                    <td><?= html_escape($row['PoNumber']) ?><br><small><?= html_escape($row['SpbbName']) ?></small></td><td><?= html_escape($row['FuelName']) ?></td>
                    <td><?= html_escape($row['DeliveryNoteNo']) ?></td><td><?= html_escape($row['VehicleNo']) ?><br><small><?= html_escape($row['DriverName']) ?></small></td>
                    <td><?= number_format($row['ReceivedQuantity'], 2) ?> L</td><td><?= number_format($row['RejectedQuantity'], 2) ?> L</td>
                    <td><span class="label label-info"><?= html_escape($row['QualityStatus']) ?></span></td><td><?= html_escape($row['CreatedBy']) ?></td>
                </tr><?php endforeach; ?></tbody>
            </table></div>
        </div>
    </section>
</div>
