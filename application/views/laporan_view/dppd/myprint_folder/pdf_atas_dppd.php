
<table border="0" width="100%" style="border-collapse: collapse;" id="tbl_atas_lainnya" >
    <tr>
        <td style="font-weight:bold;font-size:10px;border-bottom:1px solid;">REPORT (PD/PJ) - LAPORAN DPPD</td>
        <td style="text-align:right;"><?= $gabung ?> Page {PAGENO} of {nbpg}</td>
    </tr>
</table>

<table><tr><td></td></tr></table>
<table><tr><td></td></tr></table>

<table border="0" width="100%" id="tbl_atas_lainnya" >
    <tr>
        <td width="5%" >Job No</td>
        <td width="1%" align="center" >:</td>
        <td style="border-bottom: 1px solid black;padding-left:5px;"><?=$JobNo.' - '.$JobNm ?></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    
</table>

<table border="0" width="100%" id="tbl_atas_lainnya" >
    <tr>
        <td width="5%" >Alokasi</td>
        <td width="1%" align="center" >:</td>
        <td  width="2%" style="border-bottom: 1px solid black;padding-left:5px;"><?=$Alokasi?></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    
</table>

<table border="0" width="100%" id="tbl_atas_lainnya"  >
    <tr>
        <td width="5%" >Periode</td>
        <td width="1%" align="center" >:</td>
        <td width="15%" style="border-bottom: 1px solid black;padding-left:5px;<?=($all_periode==1) ? 'text-decoration: line-through;' : '' ?>">
            <?= date('d-M-Y', strtotime($dari_tgl)) . ' s.d ' . date('d-M-Y', strtotime($sampai_tgl)) ?>
        </td>
        <td width="1%" ></td>
        <td width="10%" style="border-bottom:1px solid black;<?=($all_periode==0) ? 'text-decoration: line-through;' : '' ?>">SEMUA PERIODE</td>
        <td></td>
    </tr>
    
</table>
<table style=""><tr><td></td></tr></table>

