<div class="table-responsive">
    <table class="table table-hover" border="1" cellspacing="2" width="100%">
        <thead>
            <tr>
                <th style="background-color: mediumturquoise">
                    <center> No.</center>
                </th>
                <th style="background-color: darksalmon">
                    <center> Kode RAP</center>
                </th>
                <th style="background-color: mediumturquoise;">
                    <center> Uraian</center>
                </th>
                <th style="background-color: darksalmon;">
                    <center> Volume</center>
                </th>
                <th style="background-color: mediumturquoise;">
                    <center> Sat</center>
                </th>
                <th style="background-color: darksalmon;">
                    <center> Harga Satuan (Rp)</center>
                </th>
                <th style="background-color: mediumturquoise;">
                    <center> Jumlah Harga (Rp)</center>
                </th>

                <th style="background-color: darksalmon;text-align:center;">#</th>
            </tr>
        </thead>
        <tbody>


            <?php
            $no = 1;
            $total = 0;
            $jumlah = 0;
            foreach ($row as $key) {
                $total = $key->Vol * $key->HrgSatuan;
                $jumlah += $total;

            ?>
                <tr>
                    <td style="text-align: left;"><?= $no++ ?></td>
                    <td style="text-align: left;"><?= $key->KdRAP ?></td>
                    <td style="text-align: left;"><?= $key->Uraian ?></td>
                    <td style="text-align: left;"><?= $key->Vol ?></td>
                    <td style="text-align: left;"><?= $key->Uom ?></td>
                    <td style="text-align: left;"><?= UbahGabunganUang($key->HrgSatuan) ?></td>
                    <td style="text-align: left;"><?= UbahGabunganUang($total) ?></td>
                    <td style="text-align: center;">
                        <button onclick="EditDetailPd('<?= $key->NoPD ?>','<?= $key->NoUrut ?>','<?= $JobNo ?>')" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></button>
                        <button onclick="HapusDetailPd('<?= $key->NoPD ?>','<?= $key->NoUrut ?>','<?= $JobNo ?>')" class="btn btn-sm btn-danger" title="Hapus" <?= $disab ?>><i class="fa fa-trash"></i></button>
                    </td>
                </tr>

            <?php } ?>
        </tbody>
    </table>
</div>
<div class="form-group row">
    <div class=" col-md-2" style="float: right; margin-right: 8%;">
        <input type="text" class="form-control" id="TotalPD" name="TotalPD" readonly value="<?= UbahGabunganUang($jumlah) ?>">
    </div>
    <label for="" class="col-md-3 col-form-label" style="float: right; margin-right: 2%;">
        <h4> TOTAL : </h4>
    </label>
</div>



<script>
    function EditDetailPd(NoPD, NoUrut, JobNo) {
        $('#my-modal').modal('show');
        $('#my-modal-title').text('Edit Detail PD / NoPD : ' + NoPD);
        $('.my-loading').show();
        $.ajax({
            type: "POST",
            url: "<?= site_url('Pd/getEditDetailPd') ?>",
            data: {
                'NoPD': NoPD,
                'NoUrut': NoUrut,
                'JobNo': JobNo
            },
            dataType: "HTML",
            beforeSend: function() {
                $('.my-loading').show();
            },
            success: function(response) {
                $('.my-loading').hide();
                $('#my-modal-body').html(response);

            }
        });
    }

    function HapusDetailPd(NoPD, NoUrut, JobNo) {
        Swal({
            title: 'Anda yakin?',
            text: "Apakah yakin ingin menghapus data ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Yakin',
            cancelButtonColor: '#d33',

        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?= base_url('Pd/hapus_detail') ?>/",
                    data: {
                        'NoPD': NoPD,
                        'NoUrut': NoUrut,
                        'JobNo': JobNo
                    },
                    type: "POST",
                    dataType: "JSON",
                    success: function(response) {
                        // if (response.status == 'success') {
                        //     toastr.success('Berhasil dihapus');
                        // } else {
                        //     toastr.error('Gagal dihapus');
                        // }
                        detailtable(JobNo, NoPD);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        });
    }
</script>