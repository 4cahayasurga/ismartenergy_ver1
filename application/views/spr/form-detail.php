<form id="myformdetail">
    <label>No</label>
    <input type="text" name="NoUrut" id="NoUrut" class="form-control form-control-sm" readonly>
    <label>Alokasi</label>
    <select name="Alokasi" id="Alokasi" class="form-control form-control-sm select2" required>
        <option value="">-- Pilih Salah Satu --</option>
        <?php
        $query = $this->db->query("SELECT Alokasi,Keterangan FROM Alokasi")->result();
        foreach ($query as $alokasi) { ?>
            <option value="<?= $alokasi->Alokasi ?>"><?= $alokasi->Alokasi . ' - ' . $alokasi->Keterangan ?></option>
        <?php } ?>
    </select>
    <label>Kode RAP</label>
    <select name="KdRAP" id="KdRAP" class="form-control form-control-sm select2" required>
        <option value="">-- Pilih Salah Satu --</option>
    </select>
    <label>Uraian</label>
    <textarea class="form-control" name="Uraian" id="Uraian" required="required"></textarea>
    <label>Volume</label>
    <input type="text" name="Vol" id="Vol" class="form-control" onkeypress="return hanyaAngka(event)">
    <label>Satuan</label>
    <input type="text" name="Uom" id="Uom" class="form-control">
    <input type="hidden" name="JobNo" id="JobNo" value="<?= $JobNo ?>">
    <input type="hidden" name="NoSPR" id="NoSPR" value="<?= $NoSPR ?>">
    <br>
    <button type="submit" class="btn btn-sm btn-primary pull-right" id="simpan-detail">Simpan</button>
</form>

<script>
    $(function() {
        $('#Alokasi').select2({
            width: '100%'
        });
        $('#KdRAP').select2({
            width: '100%'
        });

        $('#Alokasi').change(function() {
            var JobNo = $('#JobNo').val();
            $.ajax({
                type: "POST",
                url: "<?= site_url('Spr/getKdRAP') ?>",
                data: {
                    "JobNo": JobNo,
                    "Alokasi": this.value
                },
                dataType: "HTML",
                success: function(response) {
                    // alert(response);
                    $('#KdRAP').html(response);
                }
            });
        })

        $('#myformdetail').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?= site_url('Spr/simpan_detail') ?>",
                data: $(this).serialize(),
                dataType: "HTML",
                beforeSend: function() {
                    $('#simpan-detail').attr('disabled', 'disabled');
                    $('#simpan-detail').text('Loading.....');
                },
                success: function(response) {
                    console.log(response);
                    $('#simpan-detail').removeAttr('disabled');
                    $('#simpan-detail').text('Simpan');
                    $.toast({
                        heading: 'Berhasil',
                        // text: 'Data Telah Ditambahkan',
                        position: 'bottom-right',
                        showHideTransition: 'slide',
                        icon: 'success',
                        hideAfter: '100',
                        afterHidden: function() {
                            $('#MyModal').modal('hide');
                            detail_table();
                        }
                    });



                }
            });
        })

    })
</script>
