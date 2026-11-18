<div class="col-xs-6">
    <label for="">Detail Sumda</label>
    <select name="DetailSumda" id="detailsumdamy" class="form-control input-sm" required>
        <option value="">-- Pilih Salah Satu --</option>
        <?php
        foreach ($mydatamy as $mydatamy) { ?>
            <option value="<?= $mydatamy->KdRAP ?>"><?= $mydatamy->KdRAP . ' - ' . $mydatamy->Uraian ?></option>
        <?php } ?>
    </select>
</div>
<script>
    $(function() {
        $('#detailsumdamy').select2();
    });
</script>