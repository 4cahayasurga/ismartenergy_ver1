<form id="myformdetail">
    <div class="row in-loading" style="display: none;">
        <div class="col-sm-12" style="text-align: center;">
            <span>Loading...</span>
        </div>

    </div>


    <label>No</label>
    <input type="text" name="NoUrut" id="NoUrut" class="form-control form-control-sm">

    <label>Alokasi</label>
    <select name="Alokasi" id="Alokasi" class="form-control form-control-sm select2" onchange="getRAP(this.value)" required>
        <option value="">-- Pilih Salah Satu --</option>
        <?php
        $query = $this->db->query("SELECT Alokasi,Keterangan FROM Alokasi")->result();
        foreach ($query as $alokasi) { ?>
            <option value="<?= $alokasi->Alokasi ?>"><?= $alokasi->Alokasi . ' - ' . $alokasi->Keterangan ?></option>
        <?php } ?>
    </select>

    <div id="tampilrap"></div>


    <label>Uraian</label>
    <textarea class="form-control" name="Uraian" id="Uraian" oninput="this.value = this.value.toUpperCase()" required="required"></textarea>

    <label>Volume</label>
    <input type="text" name="Vol" id="Vol" class="form-control" onkeypress="return hanyaAngka(event)" value="0" autocomplete="off" required>

    <label>Satuan</label>
    <input type="text" name="Uom" id="Uom" class="form-control" oninput="this.value = this.value.toUpperCase()" autocomplete="off" required>

    <input type="hidden" name="JobNo" id="JobNo" value="<?= $mydata['JobNo'] ?>">
    <input type="hidden" name="NoSPR" id="NoSPR" value="<?= $mydata['NoSPR'] ?>">
    <br>
    <!-- <div class="alert alert-primary" role="alert" id="notif"></div> -->


    <!-- <span style="color:#e74c3c" id="notif"></span> -->
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


    $(document).ready(function() {

        Tutup()

        $('.select2').select2({
            width: '100%'
        })

        $('#myformdetail').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?= site_url('Spr/simpan_detail_spr_in_edit') ?>",
                data: $(this).serialize(),
                dataType: "JSON",
                beforeSend: function() {
                    $('#simpan-detail').attr('disabled', 'disabled').text('Loading...');
                },
                success: function(response) {
                    $('#simpan-detail').removeAttr('disabled').text('Simpan');

                    $.toast({
                        heading: 'Berhasil Disimpan',
                        position: 'bottom-right',
                        showHideTransition: 'slide',
                        icon: 'success',
                        hideAfter: 100,
                        afterHidden: function() {
                            $('#MyModal').modal('hide');
                            show_detail_spr()
                        }
                    })
                }
            });
        })

        $('#Vol').keyup(function() {
            DoHitung();
        })




    })



    function getRAP(Alokasi) {
        if (Alokasi == '') {
            return false;
        }

        $.ajax({
            type: "POST",
            url: "<?= site_url('Spr/getAlokasi') ?>",
            data: {
                'JobNo': "<?= $mydata['JobNo'] ?>",
                'NoSPR': "<?= $mydata['NoSPR'] ?>",
                'Alokasi': Alokasi
            },
            dataType: "HTML",
            beforeSend: function() {
                $('.in-loading').show();
            },
            success: function(response) {
                $('.in-loading').hide();
                $('#tampilrap').html(response);
            }
        });


    }



    function Tutup() {
        $('#NoUrut').attr('disabled', 'disabled');
    }
</script>