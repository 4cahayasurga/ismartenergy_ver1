
<table border="0" width="100%" style="border-collapse: collapse;" >
    <tr>
        <td style="font-weight:bold;border-bottom:1px solid;font-size:10px;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif !important;width: 20%;">REPORT - REKAP PD JAMINAN</td>
        <td align="right" class="atur_huruf"><?= $gabung ?> Page {PAGENO} of {nbpg}</td>
    </tr>
</table>
<p></p>

<table border="0" width="30%" style="border-collapse: collapse;page-break-inside: avoid !important;" class="atur_huruf">
    <tr>
        <td width="20%" style="padding-top: 5px;padding-bottom: 5px;">JOB NO</td>
        <td width="2%" align="center" style="padding-top: 5px;padding-bottom: 5px;">:</td>
        <td style="border-bottom: 1px solid black;padding-left:5px;"><?=($JobNo=='all_job') ? 'SEMUA JOB' : $JobNo.' - '.$JobNm ?></td>
        <td></td>
    </tr>

    
</table>