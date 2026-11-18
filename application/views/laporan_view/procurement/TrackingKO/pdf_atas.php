
<table border="0" class="tulisan"  width="100%" style="border-collapse: collapse;" >
    <tr>
        <td class="" style="font-weight:bold;text-decoration: underline;">TRACKING KO</td>
        <td class="font_10" style="text-align:right;"><?= $gabung ?> Page {PAGENO} of {nbpg}</td>
    </tr>
</table>

<p></p>
<table border="0" class="tulisan font_11"  width="100%" style="border-collapse: collapse;margin-top:10px !important;" >
    <tr>
        <td width="70px" style="padding-top:8px;padding-bottom: 8px;">Job No:</td>
        <td width="300px" style="border-bottom:1px solid black;padding-top:8px;padding-bottom: 8px;" ><?=$JobNo.' - '.$JobNm?></td>
        <td></td>
    </tr>
    <?php
    $StatusText = '';
    if ($Status == 'AllStatus' ) {
        $StatusText = 'All Status';
    }else if($Status == 0){
        $StatusText = 'In Process';
    }else{
        $StatusText = 'In Delivered';
    }
    ?>
    <tr>
        <td width="70px" style="padding-top:8px;padding-bottom: 8px;">Status:</td>
        <td width="300px" style="border-bottom:1px solid black;padding-top:8px;padding-bottom: 8px;" ><?=$StatusText?></td>
        <td></td>
    </tr>
</table>
<p></p>
