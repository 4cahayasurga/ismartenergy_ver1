<table border="0" width="100%" style="border-collapse: collapse;" >
    <tr>
        <td style="font-weight:bold;border-bottom:1px solid;font-size:10px;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif !important;width: 40%;">REPORT - REKAP PEMBAYARAN</td>
        <td align="right" class="atur_huruf"><?= $gabung ?> Page {PAGENO} of {nbpg}</td>
    </tr>
</table>
<p></p>


<table border="0" width="100%" style="border-collapse: collapse;page-break-inside: avoid !important;" class="atur_huruf">
    <tr>
        <td width="8%" style="padding-top: 5px;padding-bottom: 5px;">Job No</td>
        <td width="2%" align="center" style="padding-top: 5px;padding-bottom: 5px;">:</td>
        <td style="border-bottom: 1px solid black;padding-left:5px;" width="20%"><?= $dataJob ?></td>
        <td></td>
    </tr>

    <tr>
        <td width="8%" style="padding-top: 5px;padding-bottom: 5px;" >Tgl Pembayaran</td>
        <td width="2%" align="center">:</td>
        <td style="border-bottom: 1px solid black;padding-left:5px;" width="20%" ><?= date('d-M-Y', strtotime($dari_tgl)) . ' s.d ' . date('d-M-Y', strtotime($sampai_tgl))  ?></td>
        <td style="text-align:center;font-size: 14px;text-decoration: underline;font-weight: bold;" ><?=uang_koma_genap_decimal($Ttl_bayar)?></td>
    </tr>
</table>
