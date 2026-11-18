
<table border="0" class="huruf"  width="100%" style="border-collapse: collapse;" >
    <tr>
        <td class="" style="font-weight:bold;text-decoration: underline;">REKAP KO</td>
        <td class="font_10" style="text-align:right;"><?= $gabung ?> Page {PAGENO} of {nbpg}</td>
    </tr>
</table>

<p></p>
<table border="0" class="huruf font_11"  width="100%" style="border-collapse: collapse;margin-top:10px !important;" >
    <tr>
        <td width="70px" style="padding-top:8px;padding-bottom: 8px;">Job No:</td>
        <td width="300px" style="border-bottom:1px solid black;padding-top:8px;padding-bottom: 8px;" ><?=$JobNo.' - '.$JobNm?></td>
        <td></td>
    </tr>
    <tr>
        <td width="70px" style="padding-top:8px;padding-bottom: 8px;">Periode:</td>
        <td width="300px" style="border-bottom:1px solid black;padding-top:8px;padding-bottom: 8px;" ><?=($AllPeriode==1) ? 'Semua Periode' : tgl_baru($DariTgl).' s.d '.tgl_baru($SampaiTgl) ?></td>
        <td></td>
    </tr>
</table>

