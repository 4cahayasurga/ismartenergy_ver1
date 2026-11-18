<div class="form-group row">

    <div class="col-xs-6">
        <label for="">Sumda</label>
        <select name="Detail" id="mydetail" class="form-control input-sm" required>
            <option value="">-- Pilih Salah Satu --</option>
            <?php
            foreach ($mydata as $mydata) { ?>
                <option value="<?= $mydata->KdRAP ?>"><?= $mydata->KdRAP . ' - ' . $mydata->Uraian ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="DetailSumda">

    </div>
    <!-- <div class="col-xs-6">
        <label for="">Detail</label>
        <select name="DetailSumda" id="MyDetailSumda" class="form-control input-sm" required>
            <option value="">-- Pilih Salah Satu --</option>

        </select>
    </div> -->

</div>

<script>
    $(function() {
        var Alokasi = '<?= $Alokasi ?>';
        var Versi = '<?= $Versi ?>';
        $('#mydetail').select2();

        $('#mydetail').change(function() {
            var Tipe = $('#Tipe').val();
            var Header = this.value;
            if (Tipe != 'DetailSumda') {
                $('.DetailSumda').html('');
                return false;
            }

            $.ajax({
                type: "POST",
                url: "<?= site_url('Rap/getDetailSumda') ?>",
                data: {
                    "JobNo": JobNo,
                    "Alokasi": Alokasi,
                    "Versi": Versi,
                    "Header": Header
                },
                dataType: "HTML",
                success: function(response) {

                    $('.DetailSumda').html(response);
                    // console.log(response);
                }
            });
        })

    })
</script>