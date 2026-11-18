<style type="text/css">

    .atur{
        background-color:#5D7B9D;
        text-align: center;
        font-size: 14px !important;
        font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
        color:white;
    }

    #tableSisaInvoice thead tr th{
        background-color:#FFA447;
        text-align: center;
        font-size: 14px !important;
        font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
        color:white;
    }

    #tableSisaInvoice tbody tr td{
        font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
        font-size: 13px !important;
    }

</style>

<div class="row">
    <div class="col-md-10">
        <table width="100%" border="0" style="margin-top:10px !important;">
            <tr>
                <td align="center" bgcolor="silver" style="height:20px; font-weight:bold">L I S T&nbsp; &nbsp;S I S A I N V O I C E&nbsp; </td>
            </tr>
        </table>
    </div>
</div>

<div class="row" style="margin-top:10px !important;">
    <div class="col-md-10">
        <div class="table-responsive">


            <table width="100%" border="1" id="tableSisaInvoice"  class="table table-bordered table-striped table-hover ">
                <thead>
                    <tr>
                        <!-- <th width="70px" class="text-center"><input type="checkbox" id="select_all"></th> -->
                        <th width="100px">Kode RAP</th>
                        <th>Uraian</th>
                        <th width="100px">Uom</th>
                        <th width="150px">Vol Masuk</th>
                        <th width="150px">Hrg Satuan (Rp)</th>
                        <th width="150px">Vol Sisa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ListSisaInvoice as $row): ?>
                        <tr>

                            <td><?=ShowKodeAman(($row->KdRAP))?></td>
                            <td><?=ShowKodeAman($row->Uraian)?></td>
                            <td class="text-center"><?=ShowKodeAman($row->Uom)?></td>
                            <td class="text-right" ><?=UbahDecimal_Nol_Tiga($row->VolBrgMasuk_Detail)?></td>
                            <td class="text-right" ><?=number_format($row->HrgSatuan)?></td>
                            <td class="text-right">

                                <input type="text" name="DariSisaInvoice[<?=$row->LedgerNo?>]" id="DariSisaInvoice" value="Ya">

                                <input type="text" name="VolSisaInvoice[<?=$row->LedgerNo?>]" id="VolSisaInvoice_ID_<?=$row->LedgerNo?>"  class="form-control input-sm" value="<?=UbahDecimal_Nol_Tiga($row->Vol_SisaInvoice)?>" style="text-align: right;" readonly>
                            </td>

                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
