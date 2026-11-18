<div class="tab-content" id="custom-content-above-tabContent">
    <div class="tab-pane show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">

        <div class="alert alert-danger alert-dismissible" style="display:none;" id="peringatan">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>PJ Lebih Besar Dari Pada PD
        </div>

        <div class="col-sm-12 row" style="margin-bottom:10px;" id="sama">
            <a class="btn btn-primary btn-sm" href="<?= site_url('Pj/samadenganpd/' . $JobNo . '/' . $NoPD) ?>" <?=$disab?>>Sama Dengan PD</a>
        </div>

        <table class="table table-hover" border="1" cellspacing="2" width="100%">
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

                <th style="background-color: darksalmon;"></th>
            </tr>
            <div id="detail-table">

            </div>
            <?php
            $no = 1;
            $total = 0;
            $sum = 0;
            $saldo = 0;

            foreach ($pd as $value) {
                # code...
                $total = $value->PjVol * $value->PjHrgSatuan;
                $sum += $total;
                $MyNoPD = $this->secure->encrypt_url($value->NoPD);

            ?>
                <tr>
                    <td><?= $value->NoUrut ?></td>
                    <td><?= $value->KdRAP ?></td>
                    <td><?= $value->PjUraian ?></td>
                    <td><?= UbahGabunganUang($value->PjVol) ?></td>
                    <td><?= $value->Uom ?></td>
                    <td><?= UbahGabunganUang($value->PjHrgSatuan) ?></td>
                    <td><?= UbahGabunganUang($total) ?></td>
                    <td>
                        <a href="javascript:void(0)" onclick="EditDetailPj('<?= $MyNoPD ?>','<?= $value->NoUrut ?>')" data-toggle="tooltip" data-placement="top" class="btn btn-sm btn-success waves-effect waves-light" title="Edit Data"><i class="fa fa-edit"></i></a>
                        <!-- <a data-target="#modal-pj" data-toggle="modal" class="btn btn-danger btn-xs"><i class="fa fa-bank" title="Edit"></i></a> -->
                    </td>
                </tr>

            <?php } ?>

            <?php
            $saldo = (int) $row->TotalPD - (int) $sum;
            ?>

            <tfoot>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Total PertanggungJawaban</td>
                <td>
                    <?= UbahGabunganUang($sum) ?>
                    <input type="text" name="TotalPj" id="TotalPJ" value="<?= $sum ?>" hidden>
                </td>
                <td></td>
            </tfoot>
        </table>

        <div class="form-group row">
            <div class=" col-md-2" style="float: right; margin-right: 8%;">
                <input type="text" class="form-control" name="DanaPD" readonly value="<?= number_format($row->TotalPD) ?>">
            </div>
            <label for="" class="col-md-3 col-form-label" style="float: right; margin-right: 2%;">
                <h4> Dana yang tersedia dilapangan : </h4>
            </label>
        </div>


        <div class="form-group row">
            <div class="col-md-2" style="float: right; margin-right: 8%;">
                <input type="text" class="form-control" name="Saldo" id="Saldo" value="<?= UbahGabunganUang($saldo) ?>" readonly>
                <input type="hidden" class="form-control" name="SaldoBayangan" id="SaldoBayangan" value="<?= $saldo ?>" id="SaldoBayangan" disabled>
            </div>
            <label for="" class="col-md-3 col-form-label" style="float: right; margin-right: 2%;">
                <h4> Saldo : </h4>
            </label>
        </div>

    </div>

</div>

<div class="modal fade" id="MyModal">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header modal-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="MyModalTitle"></h4>
            </div>
            <div class="modal-body" id="MyModalBody">

            </div>

        </div>
    </div>
</div>



<script>
    var MyJobNo = '<?= $JobNo ?>';
    $(document).ready(function() {
        $('#peringatan').hide();
        $('#sama').hide();
        CekSaldo()
    })

    function CekSaldo() {
        var SaldoBayangan = $('#SaldoBayangan').val();
        if (SaldoBayangan < 0) {
            $('#peringatan').show();
            $('#sama').hide();
            $('#simpanEPD').attr('disabled', 'disabled');

        } else {
            $('#peringatan').hide();
            $('#sama').show();
            $('#simpanEPD').removeAttr('disabled', '');
        }
    }

    function EditDetailPj(NoPD, NoUrut) {
        $('#MyModalTitle').html('Edit Detail PJ');
        $('#MyModal').modal('show');

        $.post("<?= site_url('Pj/EditDetailPj') ?>", {
                "NoPD": NoPD,
                "NoUrut": NoUrut,
                'JobNo': JobNo
            })
            .done(function(data) {
                $('#MyModalBody').html(data);
            });
    }
</script>