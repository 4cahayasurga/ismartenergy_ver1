<div class="atas">

    <table border="0" width="100%" class="atur_tbl">
        <tr>
            <td style="font-size:14px;font-weight:strong;">REPORT QUERY PD/PJ</td>
            <td align="right"><?= $gabung ?> Page {PAGENO} of {nbpg}</td>
        </tr>
    </table>

    <!-- <table border="0" style="font-size:10px;padding-top:10px;">
        <tr>
            <td width="55px">Periode</td>
            <td width="10px" align="center">:</td>
            <td style="border-bottom:1px solid silver;" width="150px"><?= tgl_baru($dari_tgl) . ' s.d ' . tgl_baru($sampai_tgl) ?></td>

        </tr>


    </table> -->

    <table border="0" style="font-size:10px;padding-bottom:10px;page-break-inside: avoid !important;">
        <tr>
            <td width="55px">Job No</td>
            <td width="10px" align="center" style="padding-right:5px;">:</td>
            <td width="150px" style="border-bottom:1px solid silver;"><?= $JobNo . ' - ' . $JobNm ?></td>
        </tr>
        <tr>
            <td width="55px">Alokasi</td>
            <td width="10px" align="center" style="padding-right:5px;">:</td>
            <td width="100px" style="border-bottom:1px solid silver;"><?= $Alokasi . ' - ' . $Keterangan ?></td>
        </tr>


    </table>





</div>