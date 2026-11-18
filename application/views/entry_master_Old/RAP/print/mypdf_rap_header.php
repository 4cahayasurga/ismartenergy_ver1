<div class="atas">

    <table border="0" width="100%" class="atur_tbl">
        <tr>
            <td style="font-size:8px;font-weight:bold;">DATA RAP</td>
            <td align="right" style="font-size: 8px;"><?= $gabung ?> Page {PAGENO} of {nbpg}</td>
        </tr>
    </table>

    <table border="0" style="font-size:8px;padding-bottom:10px;">
        <tr>
            <td width="55px">Job No</td>
            <td width="10px" align="center" style="padding-right:5px;">:</td>
            <td width="150px" style="border-bottom:1px solid silver;"><?= $JobNo.' - '.$JobNm ?></td>
        </tr>
        <tr>
            <td width="55px">Alokasi</td>
            <td width="10px" align="center" style="padding-right:5px;">:</td>
            <td width="100px" style="border-bottom:1px solid silver;"><?= $Alokasi.' - '.$Keterangan ?></td>
        </tr>
        <tr>
            <td width="55px">Versi</td>
            <td width="10px" align="center" style="padding-right:5px;">:</td>
            <td width="100px" style="border-bottom:1px solid silver;"><?= $Versi_baru?></td>
        </tr>
    </table>

    <table border="0" width="100%" id="table_saya" >
    
        <tr>
            <td align="left" style="width:15%;border-bottom:1px solid black;"><?='Terbilang : '.ucwords(terbilang($grand_total_rap))?></td>
            <th width="1%"></th>

            <th align="center" style="width:15%;border-bottom:1px solid black;">Total (Rp)</th>
            <th width="1%"></th>
            <th align="right" style="width:15%;border-bottom:1px solid black;background-color:yellow;"><?=new_decimal($grand_total_rap)?></th>
        </tr>

</table>

   <!--  <table border="0" style="font-size:10px;padding-top:10px;">
        <tr>
            <td width="55px">Periode</td>
            <td width="10px" align="center">:</td>
            <td style="border-bottom:1px solid silver;" width="150px"><?= tgl_baru($dari_tgl) . ' s.d ' . tgl_baru($sampai_tgl) ?></td>

        </tr>


    </table>

    <table border="0" style="font-size:10px;padding-bottom:10px;page-break-inside: avoid !important;">
        <tr>
            <td width="55px">By</td>
            <td width="10px" align="center" style="padding-right:5px;">:</td>
            <td width="150px" style="border-bottom:1px solid silver;"><?= $By_pilihan ?></td>
        </tr>
        <tr>
            <td width="55px">Type</td>
            <td width="10px" align="center" style="padding-right:5px;">:</td>
            <td width="100px" style="border-bottom:1px solid silver;"><?= $Type_pilihan ?></td>
        </tr>


    </table> -->

    



</div>