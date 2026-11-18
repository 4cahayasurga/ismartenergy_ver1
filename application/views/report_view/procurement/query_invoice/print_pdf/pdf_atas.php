
<table border="0" width="100%" style="border-collapse: collapse;" >
    <tr>
        <td style="font-weight:bold;border-bottom:1px solid;font-size:10px;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif !important;width: 40%;">REPORT (PROCUREMENT) - QUERY INVOICE</td>
        <td align="right" class="atur_huruf"><?= $gabung ?> Page {PAGENO} of {nbpg}</td>
    </tr>
</table>
<p></p>

<table border="0" width="30%" style="border-collapse: collapse;page-break-inside: avoid !important;" class="atur_huruf">
    <tr>
        <td width="20%" style="padding-top: 5px;padding-bottom: 5px;">Job No</td>
        <td width="2%" align="center" style="padding-top: 5px;padding-bottom: 5px;">:</td>
        <td style="border-bottom: 1px solid black;padding-left:5px;"><?= $JobKata?></td>
        <td></td>
    </tr>

   <tr>
        <td width="20%" style="padding-top: 5px;padding-bottom: 5px;" >Vendor</td>
        <td width="2%" align="center">:</td>
        <td style="border-bottom: 1px solid black;padding-left:5px;"><?=$VendorKata;?></td>
        <td></td>
    </tr>

    <tr>
        <td width="20%" style="padding-top: 5px;padding-bottom: 5px;">Periode Bayar</td>
        <td width="2%" align="center">:</td>
        <td style="border-bottom: 1px solid black;padding-left:5px;">
           <?php if ($PrdAwal == '' OR $PrdAkhir == '' ): ?>
                
            <?php else: ?>
                <?= date('d-M-Y', strtotime($PrdAwal)) . ' s.d ' . date('d-M-Y', strtotime($PrdAkhir)) ?>
            <?php endif; ?>
            
        </td>
    </tr>
</table>