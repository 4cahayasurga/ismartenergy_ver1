<form id="my-form-rap">

    <!-- <span class="you-loading" style="display:;padding-bottom:10px;">Loading...</span> -->
    <div class="form-group row">

        <div class="col-xs-3">
            <label for="">Tipe</label>
            <select name="Tipe" id="Tipe" class="form-control input-sm" required>
                <option value="">-- Pilih Salah Satu --</option>
                <option value="Header">Header</option>
                <option value="Detail">Detail</option>
                <option value="Sumda">Sumda</option>
                <option value="DetailSumda">Detail Sumda</option>
            </select>
        </div>

        <div class="col-xs-9">
            <label for="">Header</label>
            <select name="Header" id="Header" class="form-control input-sm" required>
                <option value="0">Top Header</option>
                <?php
                foreach ($header as $head) { ?>
                    <option value="<?= $head->KdRAP ?>"><?= $head->KdRAP . ' - ' . $head->Uraian ?></option>
                <?php } ?>
            </select>
        </div>

    </div>

    <div class="detail">

    </div>


    <div class="form-group row">

        <div class="col-xs-6">
            <label for="">Alokasi</label>
            <input type="text" name="Alokasi" id="Alokasi" class="form-control input-sm" value="<?= $Alokasi ?>" readonly>
        </div>
        <div class="col-xs-6">
            <label for="">Versi</label>
            <input type="text" name="Versi" id="Versi" class="form-control input-sm" value="<?= $Versi ?>" readonly>
        </div>
    </div>

    <div class="form-group row">

        <div class="col-xs-12">
            <label for="">Kode RAP</label>
            <input type="text" name="KdRAP" id="KdRAP" class="form-control input-sm" placeholder="Kode RAP" required>
        </div>
    </div>

    <div class="form-group row">

        <div class="col-xs-12">
            <label for="">Uraian Pekerjaan</label>
            <textarea name="Uraian" id="Uraian" class="form-control input-sm" placeholder="Uraian"></textarea>
        </div>
    </div>

    <div class="form-group row">

        <div class="col-xs-12">
            <label for="">Volume</label>
            <input type="text" name="Vol" id="Vol" class="form-control input-sm" placeholder="0.000" value="0" onkeypress="return hanyaAngka(event)">
        </div>
    </div>

    <div class="form-group row">

        <div class="col-xs-12">
            <label for="">Satuan</label>
            <input type="text" name="Uom" id="Uom" class="form-control input-sm" placeholder="Satuan">
        </div>
    </div>

    <div class="form-group row">

        <div class="col-xs-12">
            <label for="">Harga Satuan</label>
            <input type="text" name="HrgSatuan" id="HrgSatuan" class="form-control input-sm uang" placeholder="0.000" value="0">
        </div>
    </div>

    <div class="form-group row">

        <div class="col-xs-12">
            <label for="">Harga SATUAN (RAB)</label>
            <input type="text" name="HrgRAB" id="HrgRAB" class="form-control input-sm uang" placeholder="0.000" value="0">
        </div>
    </div>

    <div class="form-group row">

        <div class="col-xs-12">
            <input type="hidden" name="JobNo" id="JobNo" value="<?= $JobNo ?>">
            <button type="submit" class="btn bg-maroon btn-flat pull-right" id="simpan">Simpan</button>
        </div>
    </div>


</form>

<script>
    $(function() {
        $('.uang').mask('000,000,000,000,000', {
            reverse: true
        });
        $('#Header').select2({
            width: '100%'
        });

        $('#Tipe').change(function() {
            var Tipe = this.value;
            if (Tipe != 'Sumda' && Tipe != 'DetailSumda') {
                $('.detail').html('');

            }
            if (Tipe != 'DetailSumda') {
                $('.DetailSumda').html('');
            }
            if (Tipe == 'Sumda' || Tipe == 'Detail' || Tipe == 'DetailSumda') {
                $("#Header option[value='']").remove();
                $("#Header option[value='0']").remove();
                $("#Header").prepend("<option value='' selected>-- Pilih Salah Satu --</option>");
            } else {
                $("#Header option[value='']").remove();
                $("#Header option[value='0']").remove();
                $("#Header").prepend("<option value='0' selected>Top Header</option>");
            }
        })

        $('#Header').change(function() {
            var Alokasi = $('#Alokasi').val();
            var Versi = $('#Versi').val();
            var Tipe = $('#Tipe').val();
            var Header = this.value;
            if (Tipe != 'Sumda' && Tipe != 'DetailSumda') {
                $('.detail').html('');
                if (Tipe != 'DetailSumda') {
                    $('.DetailSumda').html('');
                    return false;
                }
            }

            $.ajax({
                type: "POST",
                url: "<?= site_url('Rap/getDetail') ?>",
                data: {
                    "JobNo": JobNo,
                    "Alokasi": Alokasi,
                    "Versi": Versi,
                    "Tipe": Tipe,
                    "Header": Header
                },
                dataType: "HTML",
                success: function(response) {

                    $('.detail').html(response);
                }
            });
        })




        // $('#Tipe').change(function() {
        //     var JobNo = $('#JobNo').val();
        //     var Alokasi = $('#Alokasi').val();
        //     var Versi = $('#Versi').val();
        //     $.ajax({
        //         type: "POST",
        //         url: "<?= site_url('Rap/getTipe') ?>",
        //         data: {
        //             "JobNo": JobNo,
        //             "Alokasi": Alokasi,
        //             "Versi": Versi,
        //             "Tipe": Tipe
        //         },
        //         dataType: "HTML",
        //         success: function(response) {
        //             $('#Header').html(response);
        //         }
        //     });
        // })

        $('#my-form-rap').submit(function(e) {
            e.preventDefault();
            $('#simpan').removeAttr('disabled').text('Simpan');

            var formData = new FormData(this);
            $.ajax({
                    url: '<?= site_url('Rap/simpanRAP') ?>',
                    type: 'POST',
                    dataType: 'HTML',
                    data: $(this).serialize(),
                    beforeSend: function() {
                        // $('.you-loading').show();
                        $('#simpan').attr('disabled', 'disabled').text('Loading...');
                    }
                })
                .done(function(response) {
                    // $('.you-loading').hide();
                    // $('#my-modal-body').html(response);
                    $('#my-modal').modal('hide');
                    $.toast({
                        heading: 'Berhasil',
                        // text: 'Data Telah Ditambahkan',
                        position: 'top-right',
                        showHideTransition: 'slide',
                        icon: 'success',
                        hideAfter: 1000
                    })

                    $('#simpan').removeAttr('disabled').text('Simpan');
                    // console.log(response);
                })
                .fail(function() {
                    // console.log("error");
                })
                .always(function() {
                    // console.log("complete");
                });

        })

    })
</script>