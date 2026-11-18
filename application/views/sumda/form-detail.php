<div class="form-group row">
    <div class="col-xs-6">
        <label for="">Sub Header</label>
        <select name="subheader" id="subheader" class="form-control input-sm" required>
            <option value="">-- Pilih Salah Satu --</option>
            <?php
            foreach ($detail as $detail) { ?>
                <option value="<?= $detail->KdRAP ?>"><?= $detail->KdRAP . ' - ' . $detail->Uraian ?></option>
            <?php } ?>
        </select>
    </div>
</div>

<script>
    $(function() {
        $('#subheader').select2();
    });
</script>