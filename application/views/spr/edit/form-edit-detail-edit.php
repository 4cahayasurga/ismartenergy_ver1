<form id="myformdetail">
    <label>No</label>
    <input type="text" name="NoUrut" id="NoUrut" class="form-control form-control-sm " value="<?= $row->NoUrut ?>" disabled>

    <label>Uraian</label>
    <textarea class="form-control" name="Uraian" id="Uraian" oninput="this.value = this.value.toUpperCase()" required="required"><?= $row->Uraian ?></textarea>
    <label>Volume</label>
    <input type="text" name="Vol" id="Vol" autocomplete="off" class="form-control" value="<?= floatval($row->Vol) ?>" onkeypress="return hanyaAngka(event)">
    <label>Satuan</label>
    <input type="text" name="Uom" id="Uom" class="form-control" value="<?= $row->Uom ?>" oninput="this.value = this.value.toUpperCase()" required>



    <br>
    <input type="hidden" name="NoSPR" id="NoSPR" value="<?= $row->NoSPR ?>">
    <input type="hidden" name="NoUrut" id="NoUrut" value="<?= $row->NoUrut ?>">
    <input type="hidden" name="Alokasi" id="Alokasi" value="<?= $row->Alokasi ?>">
    <input type="hidden" name="KdRAP" id="KdRAP" value="<?= $row->KdRAP ?>">
    <input type="hidden" name="UserEntry" id="UserEntry" value="<?= $this->session->userdata('MIS_LOGGED_NAME') ?>">

    <input type="hidden" name="Total" id="Total" value="<?= $row->Total ?>">

    <!-- RAP -->
    <input type="hidden" name="TotalRAP" id="TotalRAP" value="<?= $row->TotalRAP ?>">
    <input type="hidden" name="HrgSatuanRAP" id="HrgSatuanRAP" value="<?= $row->HrgSatuan ?>">
    <input type="hidden" name="VolRAP" id="VolRAP" value="<?= floatval($row->VolRAP) ?>">
    <input type="hidden" name="KdRAP" id="KdRAP" value="<?= $row->KdRAP ?>">



    <div class="form-group row">
        <div class="col-sm-6">
            <div class="callout callout-danger" id="notif" style="display: none;"></div>
        </div>
        <div class="col-sm-6">
            <input type="hidden" id="myperingatan" value="0">
            <button type="submit" class="btn btn-sm btn-primary pull-right" id="simpan-detail">Simpan</button>
        </div>
    </div>
</form>

<script>
    $(function() {
        DoHitung();

        $('#myformdetail').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "<?= site_url('Spr/edit_detail_edit') ?>",
                data: $(this).serialize(),
                dataType: "HTML",
                beforeSend: function() {
                    $('#simpan-detail').attr('disabled', 'disabled');
                    $('#simpan-detail').text('Loading.....');
                },
                success: function(response) {
                    $('#simpan-detail').removeAttr('disabled');
                    $('#simpan-detail').text('Simpan');
                    $.toast({
                        heading: 'Berhasil',
                        position: 'bottom-right',
                        showHideTransition: 'slide',
                        icon: 'success',
                        hideAfter: '100',
                        afterHidden: function() {
                            $('#MyModal').modal('hide');
                            show_detail_spr();
                        }
                    });



                }
            });
        })


        $('#Vol').keyup(function() {
            DoHitung();
        })

    })

    function DoHitung() {
        var notif = '';
        $('#notif').hide();


        var VolRAP = $('#VolRAP').val();
        var HrgSatuanRAP = $('#HrgSatuanRAP').val();
        var TotalRAP = $('#TotalRAP').val();
        var Vol = $('#Vol').val();
        var Total = $('#Total').val();

        if (TotalRAP == '') {
            return false;
        }



        var TotalSPR = Vol * HrgSatuanRAP;
        var Total = $('#Total').val(TotalSPR);

        var persenSPR = (TotalSPR * 80) / 100;
        var persenRAP = (TotalRAP * 80) / 100;

        if (persenSPR >= persenRAP) {
            $('#notif').removeAttr('class');
            $('#notif').attr('class', 'callout callout-success');
            notif = 'Permintaan Anda MELEBIHI 80%  Dari RAP';

            $('#notif').show();
        }

        if (TotalSPR == TotalRAP) {
            $('#notif').removeAttr('class');
            $('#notif').attr('class', 'callout callout-warning');
            notif = 'Permintaan Anda Sudah Batas Maximal RAP';

            $('#notif').show();
        }

        if (TotalSPR > TotalRAP) {
            $('#notif').removeAttr('class');
            $('#notif').attr('class', 'callout callout-danger');
            notif = 'Permintaan Anda Melebihi RAP';

            $('#notif').show();
            showPeringatan()
        }

        if (TotalSPR < TotalRAP) {
            $('#notif').removeAttr('class');
            notif = '';
            $('#notif').hide();
        }

        $('#notif').text(notif);

    }


    function showPeringatan() {
        var pering = $('#myperingatan').val();
        if (pering > 0) {
            return false;
        }

        swal({
            title: 'Apakah Anda Yakin?',
            text: 'Permintaan Anda Melebihi RAP',
            // showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Lanjut',
            cancelButtonText: 'Batal',
        }).then(
            result => {
                if (result.value) {
                    $('#myperingatan').val(1);
                } else {
                    Batal()
                }
            });
    }

    function Batal() {
        $('#MyModal').modal('hide');
    }
</script>
