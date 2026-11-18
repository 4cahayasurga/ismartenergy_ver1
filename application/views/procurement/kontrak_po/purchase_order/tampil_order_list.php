<div class="table-responsive">

    <table id="order_list" class="table table-bordered table-striped table-hover table-condesed">
        <thead style="background-color: #27ae60;color:white;">
            <tr>
                <th style="text-align:center;width:5%;">No.</th>
                <th style="text-align:center;width:5%;">#</th>
                <th style="text-align:center;width:5%;">Kode RAP</th>
                <th style="text-align:center;width:30%;">Uraian</th>
                <th style="text-align:center;width:5%;">Vol</th>
                <th style="text-align:center;width:5%;">Sat</th>
                <th style="text-align:center;width:10%;">Harga Satuan (Rp)</th>
                <th style="text-align:center;width:10%;">Jumlah Harga (Rp)</th>
            </tr>
        </thead>
        <tbody>

            <?php
            if (count($data_detail) == 0) {
                echo '<tr>
                <th colspan="8">data tidak ditemukan</th>
                </tr>';
                exit;
            }

            ?>

            <?php
            $no = 1;
            $SubTotal = 0;
            foreach ($data_detail as $data_detail) :
                $SubTotal += $data_detail['JumlahHarga'];
                $NoUrut = $data_detail['NoUrut'];
            ?>

                <tr>
                    <td style="text-align:center;width:5%;"><?= $no++ . '.' ?></td>
                    <td style="text-align:center;width:5%;">
                        <a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="hapus_uraian('<?= $NoUrut ?>')" title="Hapus Order"><i class="fa fa-trash"></i> </a>

                        <a href="javascript:void(0)" class="btn btn-xs btn-warning" onclick="edit_uraian('<?= $NoUrut ?>')" title="Edit Order"><i class="fa fa-pencil"></i> </a>
                    </td>
                    <td style="text-align:left;width:100px;"><?= strtoupper($data_detail['KdRAP']) ?></td>
                    <td style="text-align:left;width"><?= strtoupper($data_detail['Uraian']) ?></td>
                    <td style="text-align:right;"><?= strtoupper(number_format($data_detail['Vol'])) ?></td>
                    <td style="text-align:center;"><?= strtoupper($data_detail['Uom']) ?></td>
                    <td style="text-align:right;"><?= strtoupper(number_format($data_detail['HrgSatuan'])) ?></td>
                    <td style="text-align:right;"><?= strtoupper(number_format($data_detail['JumlahHarga'])) ?></td>
                </tr>
            <?php endforeach; ?>

        </tbody>
        <tfoot style="background-color: #27ae60;color:white;">
            <tr>
                <th colspan="7" style="text-align:right;">Sub Total</th>
                <th style="text-align:right !important;" colspan="1"><?= UbahGabunganUang($SubTotal) ?></th>

            </tr>
        </tfoot>

    </table>

    <?php


    // $DiskPercentage = 0;
    // $DiscAmount = ($DiskPercentage / 100) * $SubTotal;
    // $PPN =  ($SubTotal / ppn_baru_decimal()) * ppn_baru_angka();
    // $GrandTotal = ($SubTotal + $DiscAmount) - $PPN;
    ?>

    <table width="100%" border="0" style="display:none;">
        <tr>
            <th class="text-right" style="padding-right:15px;">Sub Total</th>
            <th style="width:50%;" class="text-right" style="padding-right:5px;padding-top: 5px;padding-bottom: 5px;">
                <input type="text" class="form-control input-sm uang text-right" style="width:50%;" id="SubTotal" name="SubTotal" value="<?= $SubTotal ?>">
            </th>
        </tr>
    </table>

    <table width="100%" border="0">
        <tr>
            <th class="text-right" style="padding-right:15px;">Disc (%)</th>
            <th width="5%" class="text-right" style="padding-right:5px;padding-top: 5px;padding-bottom: 5px;">
                <input type="text" class="form-control input-sm uang text-right" id="DiscPercentage" name="DiscPercentage" value="0" min="0" max="100">
            </th>
            <th width="10%" class="text-right" style="padding-right:5px;padding-top: 5px;padding-bottom: 5px;">
                <input type="text" class="form-control input-sm uang text-right" id="DiscAmount" name="DiscAmount" value="0" readonly>
            </th>
            <th width="100px"></th>
        </tr>
    </table>

    <table width="100%" border="0" hidden>
        <tr>
            <th class="text-right" style="padding-right:15px;"></th>
            <th width="5%" class="text-center">
                PPN (Rp)
            </th>
            <th width="10%" class="text-right" style="padding-right:5px;padding-top: 5px;padding-bottom: 5px;">
                <input type="text" class="form-control input-sm uang text-right" id="PPN" name="PPN" value="0" readonly>
            </th>
            <th width="100px">
                <input type="checkbox" class="checkbox-inline" id="OverridePPN" name="OverridePPN" value="1" readonly>&nbsp; Override PPN
            </th>
        </tr>
    </table>

    <table width="100%" border="0">
        <tr>
            <th class="text-right" style="padding-right:15px;"></th>
            <th width="7%" class="text-center">
                Grand Total (Rp)
            </th>
            <th width="10%" class="text-right" style="padding-right:5px;padding-top: 5px;padding-bottom: 5px;">
                <input type="text" class="form-control input-sm uang text-right" id="GrandTotal" name="GrandTotal" value="0" readonly>
            </th>
            <th width="100px"></th>
        </tr>
    </table>
    <table width="100%" border="0">
        <tr>
            <th class="text-right" style="padding-right:15px;"></th>
            <th width="7%" class="text-center">
                Mengurangi KO
            </th>
            <th width="10%" class="text-right" style="padding-right:5px;padding-top: 5px;padding-bottom: 5px;">
                <input type="text" class="form-control input-sm uang text-right" id="MengurangiKO" disabled>
            </th>
            <th width="100px"></th>
        </tr>
    </table>
</div>

<script type="text/javascript">
    $(function() {

        hitung_po();


        $('#DiscPercentage').keyup(function() {
            var DiscPercentage = this.value;
            if (DiscPercentage > 100 || DiscPercentage < 0) {
                $(this).val(0);
            }
        });

        $('#DiscPercentage').focusout(function() {
            hitung_po();
        });

        $('#OverridePPN').change(function() {
            // hitung_po();

        })


    })



    function buat_jadi_uang(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

    function hitung_po() {
        var SubTotal = $('#SubTotal').val();
        var DiscPercentage = $('#DiscPercentage').val() / 100;
        var DiscAmount = 0;
        var PPN = 0;
        var GrandTotal = 0;

        // var OverridePPN = ($('#OverridePPN').is(':checked')) ? 1 : 0;

        DiscAmount = DiscPercentage * parseInt(SubTotal);
        // PPN = SubTotal * ppn_baru_angka_js();
        // PPN = (SubTotal / ppn_baru_decimal_js()) * ppn_baru_angka_js();


        // if ($('#OverridePPN').is(':checked')) {
        //     PPN = 0;
        // }

        // GrandTotal = (parseInt(SubTotal) - parseInt(DiscAmount)) + parseInt(PPN);
        GrandTotal = (parseInt(SubTotal) - parseInt(DiscAmount));
        DiscAmount = Math.round(DiscAmount);
        // PPN = Math.round(PPN);
        $('#DiscAmount').val(buat_jadi_uang(DiscAmount));
        $('#PPN').val(PPN);
        $('#GrandTotal').val(buat_jadi_uang(GrandTotal));

    }
</script>
