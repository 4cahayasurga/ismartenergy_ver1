<style>
    table {
        font-size: 11px !important;
    }

    .select2-results__option {
        font-size: 11px;
    }

    .lebar {
        width: 20% !important;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive">
            <form id="formsimpan">
                <table width="100%" border="0">
                    <tr>
                        <th colspan="2" style="font-size:15px;padding-bottom:5px;" class="bg-success"><?= $NoPD ?></th>
                    </tr>

                    <tr>
                        <th class="lebar">No.</th>
                        <td>
                            <input type="text" class="form-control input-sm" style="width:10%;" disabled>
                        </td>
                    </tr>
                    <tr>
                        <th class="lebar">Alokasi</th>
                        <td>
                            <input type="text" class="form-control input-sm" style="width:100%;" value="<?= AlokasiBiasa($Alokasi)  ?>" disabled>
                        </td>
                    </tr>
                    <tr>
                        <th class="lebar">Kode RAP</th>
                        <td>
                            <select name="KdRAP" id="KdRAP" class="form-control input-sm select2" style="width:60%;" required>
                                <option value="">-- Pilih salah satu --</option>
                                <?php
                                foreach ($data_rap as $data_rap) { ?>
                                    <option value="<?= $data_rap['KdRAP'] ?>"><?= $data_rap['KdRAP'] . ' - ' . $data_rap['Uraian'] ?></option>
                                <?php } ?>


                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="lebar">Uraian</th>
                        <td>
                            <textarea name="Uraian" id="Uraian" class="form-control input-sm" style="width:100%;" placeholder="Isi Uraian" autocomplete="off" required></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th class="lebar">Volume</th>
                        <td>
                            <input type="text" name="Vol" id="Vol" class="form-control input-sm" style="width:100%;" placeholder="Isi Volume" autocomplete="off" value="1" onkeypress="return hanyaAngka(event)" min="1" required>
                        </td>
                    </tr>
                    <tr>
                        <th class="lebar">Satuan</th>
                        <td>
                            <input type="text" id="Uom" name="Uom" class="form-control input-sm" style="width:100%;" placeholder="Isi Satuan" autocomplete="off" required>
                        </td>
                    </tr>
                    <tr>
                        <th class="lebar">Harga Satuan</th>
                        <td>
                            <input type="text" id="HrgSatuan" name="HrgSatuan" class="form-control input-sm" style="width:100%;" placeholder="Isi Harga Satuan" autocomplete="off" value="1" onkeypress="return hanyaAngka(event)" min="1" required>
                        </td>
                    </tr>
                    <tr>
                        <th class="lebar">Total Harga</th>
                        <td>
                            <input type="text" id="TotalHrg" class="form-control input-sm" style="width:50%;" placeholder="0" value="1" min="1" disabled>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="hidden" value="<?= $NoPD ?>" name="NoPD">
                            <button id="simpan" type="submit" class="btn btn-sm btn-primary pull-right">Simpan</button>
                        </td>
                    </tr>

                </table>
            </form>
        </div>
    </div>
</div>
<script>
    $(function() {
        $(".select2").select2({
            width: '100%',
            theme: "classic",

        });

        $('#Vol').keyup(function() {
            var Vol = (this.value == '') ? 0 : this.value;
            var HrgSatuan = $('#HrgSatuan').val();
            var total = Vol * HrgSatuan;

            $('#TotalHrg').val(total);

        })

        $('#HrgSatuan').keyup(function() {
            var Vol = $('#Vol').val();
            var HrgSatuan = (this.value == '') ? 0 : this.value;
            var total = Vol * HrgSatuan;

            $('#TotalHrg').val(total);

        })

        $('#formsimpan').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "<?= site_url('PD_lap/simpan_tambah_uraian_di_pd_edit') ?>",
                data: $(this).serialize(),
                dataType: "html",
                beforeSend: function() {
                    $('#simpan').attr('disabled', true);
                },
                success: function(response) {

                    location.reload();



                },
                error: function() {
                    $('#simpan').attr('disabled', false);
                    $.toast({
                        heading: 'Error',
                        text: 'Terjadi Kesalahan',
                        icon: 'error',
                        loader: true,
                        // loaderBg: '#9EC600'
                    })
                }
            });
        })

    })

    // function hitung_total(Vol, HrgSatuan) {
    //     var total = Vol * HrgSatuan;
    //     console.log(total);
    //     $('#TotalHrg').val(total);
    // }
</script>