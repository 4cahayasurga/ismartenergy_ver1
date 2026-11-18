<div class="content-wrapper">
    <section class="content-header"><h1>Order BBM ke PERTAMINA PATRA NIAGA <small>PO pembelian dan progres pengiriman</small></h1></section>
    <section class="content">
        <?php if ($this->session->flashdata('Berhasil')) : ?><div class="alert alert-success"><?= html_escape($this->session->flashdata('Berhasil')) ?></div><?php endif; ?>
        <?php if ($this->session->flashdata('Gagal')) : ?><div class="alert alert-danger"><?= html_escape($this->session->flashdata('Gagal')) ?></div><?php endif; ?>
        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border"><h3 class="box-title">Buat Order PERTAMINA PATRA NIAGA</h3></div>
                    <form action="<?= site_url('Spbb_pertamina/save') ?>" method="post">
                        <div class="box-body">
                            <div class="form-group"><label>SPBB</label><select class="form-control" name="SpbbID" id="poSpbb" required>
                                <option value="">Pilih SPBB</option>
                                <?php foreach ($spbb_list as $spbb) : ?><option value="<?= $spbb['SpbbID'] ?>"><?= html_escape($spbb['SpbbName']) ?></option><?php endforeach; ?>
                            </select></div>
                            <div class="form-group"><label>Material BBM</label><select class="form-control" name="FuelID" id="poFuel" required>
                                <option value="">Pilih material</option>
                                <?php foreach ($fuel_list as $fuel) : ?><option value="<?= $fuel['FuelID'] ?>" data-spbb="<?= $fuel['SpbbID'] ?>"><?= html_escape($fuel['FuelName']) ?></option><?php endforeach; ?>
                            </select></div>
                            <div class="form-group"><label>Nomor PO</label><input class="form-control" name="PoNumber" required></div>
                            <div class="row"><div class="col-sm-6"><div class="form-group"><label>Tanggal PO</label><input type="date" class="form-control" name="PurchaseDate" value="<?= date('Y-m-d') ?>" required></div></div>
                            <div class="col-sm-6"><div class="form-group"><label>Rencana Kirim</label><input type="date" class="form-control" name="RequestedDeliveryDate"></div></div></div>
                            <div class="form-group"><label>Supplier</label><input class="form-control" name="Supplier" value="PERTAMINA PATRA NIAGA" required></div>
                            <div class="row"><div class="col-sm-6"><div class="form-group"><label>Quantity (Liter)</label><input type="number" min="0.01" step="0.01" class="form-control" name="Quantity" required></div></div>
                            <div class="col-sm-6"><div class="form-group"><label>Harga/Liter</label><input type="number" min="0" step="0.01" class="form-control" name="UnitPrice" required></div></div></div>
                            <div class="form-group"><label>Termin Pembayaran</label><input class="form-control" name="PaymentTerms" placeholder="Contoh: Transfer 14 hari"></div>
                            <div class="form-group"><label>Catatan</label><textarea class="form-control" name="Notes"></textarea></div>
                        </div>
                        <div class="box-footer"><button class="btn btn-primary"><i class="fa fa-save"></i> Simpan Order</button></div>
                    </form>
                </div>
            </div>
            <div class="col-md-8">
                <div class="box box-info"><div class="box-header with-border"><h3 class="box-title">Daftar Order PERTAMINA PATRA NIAGA</h3></div>
                    <div class="box-body table-responsive"><table class="table table-bordered table-striped js-app-datatable">
                        <thead><tr><th>PO / Tanggal</th><th>SPBB</th><th>Material</th><th>Order</th><th>Diterima</th><th>Sisa</th><th>Total</th><th>Status</th></tr></thead>
                        <tbody><?php foreach ($purchases as $row) : ?><tr>
                            <td><strong><?= html_escape($row['PoNumber']) ?></strong><br><small><?= html_escape($row['PurchaseDate']) ?></small></td>
                            <td><?= html_escape($row['SpbbName']) ?></td><td><?= html_escape($row['FuelName']) ?></td>
                            <td><?= number_format($row['Quantity'], 2) ?></td><td><?= number_format($row['ReceivedQty'], 2) ?></td><td><?= number_format($row['RemainingQty'], 2) ?></td>
                            <td>Rp <?= number_format($row['TotalAmount'], 0, ',', '.') ?></td><td><span class="label label-info"><?= html_escape($row['PurchaseStatus']) ?></span></td>
                        </tr><?php endforeach; ?></tbody>
                    </table></div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
document.getElementById('poSpbb').addEventListener('change', function () {
    var id = this.value, fuel = document.getElementById('poFuel');
    Array.prototype.forEach.call(fuel.options, function (option) {
        option.hidden = option.value && option.getAttribute('data-spbb') !== id;
    });
    fuel.value = '';
});
</script>
