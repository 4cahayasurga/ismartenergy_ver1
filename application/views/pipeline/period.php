<div class="row">
    <div class="col-sm-4 form-group"><label for="period-start">Tanggal awal</label><input id="period-start" type="date" name="start" class="form-control" value="<?= html_escape($start) ?>" required></div>
    <div class="col-sm-4 form-group"><label for="period-end">Tanggal akhir</label><input id="period-end" type="date" name="end" class="form-control" value="<?= html_escape($end) ?>" required></div>
    <div class="col-sm-4 form-group"><label>&nbsp;</label><div><button class="btn btn-primary" type="submit"><?= isset($export_form) ? 'Unduh Excel (.xlsx)' : 'Terapkan periode' ?></button></div></div>
</div>
<p class="text-muted">Periode inklusif: pesanan mengikuti tanggal pembuatan; DO mengikuti tanggal DO.</p>
