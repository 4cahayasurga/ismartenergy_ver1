<?php
$mpdfConfig = array(
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_header' => 0,
    'margin_header' => 5,
    'margin_footer' => 5,
    'orientation' => 'L',
    'margin_left' => 7,
    'margin_right' => 7,
    'setAutoBottomMargin' => 'stretch',
    'setAutoTopMargin' => 'stretch',

);


$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$stylesheet = file_get_contents('css/bootstrap-3.3.7.min.css');
$stylesheet_baru = file_get_contents('mycss/css_PiutangProgressFisik.css');

$mpdf->SetTitle('REPORT ( PIUTANG PROGRESS FISIK ) ');
$mpdf->use_kwt = true;

$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_baru, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('report_view/operasi/piutang_progress_fisik/pdf_atas', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('report_view/operasi/piutang_progress_fisik/pdf_bawah', NULL, TRUE));

$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;


$html = '';

$html .= '

<table width="100%" border="0" style="border-collapse:collapse;font-size:10px;font-family:Verdana, Arial, Tahoma, Serif !important;" >
    
    <thead>

       <tr style="border-bottom:0px solid silver;">
            <th colspan="2" ></th>
            <th colspan="3" style="text-align: center;padding-top: 5px;padding-bottom: 5px;border-bottom:1px solid silver;">RPPM</th>
            <th colspan="2" style="text-align: center;padding-top: 5px;padding-bottom: 5px;border-bottom:1px solid silver;">Termin</th>
            <th colspan="2" style="text-align: center;padding-top: 5px;padding-bottom: 5px;border-bottom:1px solid silver;">Sisa Yg Harus DiTagihkan
            </th>
        </tr>

        <tr style="border-bottom:1px solid silver;">
            <th rowspan="1" style="padding-top: 5px;padding-bottom: 5px;vertical-align: middle;padding-left: 5px;">JobNo</th>
            <th rowspan="1" style="padding-top: 5px;padding-bottom: 5px;vertical-align: middle;padding-left: 5px;">Job Name</th>
            <th style="padding-top: 5px;padding-bottom: 5px;vertical-align: middle;padding-left: 5px;border-left:0px solid black;">Tgl RPPM</th>
            <th style="padding-top: 5px;padding-bottom: 5px;vertical-align: middle;padding-left: 5px;">%</th>
            <th style="padding-top: 5px;padding-bottom: 5px;vertical-align: middle;padding-left: 5px;">Rp</th>
            <th style="padding-top: 5px;padding-bottom: 5px;vertical-align: middle;padding-left: 5px;border-left:0px solid black;">%</th>
            <th style="padding-top: 5px;padding-bottom: 5px;vertical-align: middle;padding-left: 5px;">Rp</th>
            <th style="padding-top: 5px;padding-bottom: 5px;vertical-align: middle;padding-left: 5px;border-left:0px solid black;">%</th>
            <th style="padding-top: 5px;padding-bottom: 5px;vertical-align: middle;padding-left: 5px;">Rp</th>
        </tr>

    </thead>

    <tbody>';

    $SUM_RpRPPM = 0;
    $SUM_RpTermin = 0;
    $SUM_RpSisa = 0;

    if ($dataPrimary == 1):

        $html .='
        <tr style="border-bottom:1px solid black;background-color: #95a5a6;color:white;">
            <td style="border:0px solid black;padding-top:5px;padding-bottom:5px;" colspan="9">
                    Kategori : Primary
            </td>
        </tr>';

        foreach($queryPrimary as $myPrimary) :

            $SUM_RpRPPM += $myPrimary['RpRPPM'];
            $SUM_RpTermin += $myPrimary['RpTermin'];
            $SUM_RpSisa += $myPrimary['RpSisa'];

            $html .='
            <tr>
                <td style="padding-top:5px;padding-bottom:5px;padding-left:30px;">'.$myPrimary['JobNo'].'</td>
                <td style="border-left:0px solid black;">'.$myPrimary['JobNm'].'</td>
                <td style="border-left:0px solid black;text-align: right;">'.tgl_baru_tahun_pendek($myPrimary['DateRPPM']).'</td>
                <td style="text-align: right;">'.new_decimal($myPrimary['PersenRPPM']).'</td>
                <td style="text-align: right;">'.uang_koma_genap_decimal($myPrimary['RpRPPM']).'</td>
                <td style="text-align: right;">'.new_decimal($myPrimary['PersenTermin']).'</td>
                <td style="text-align: right;">'.uang_koma_genap_decimal($myPrimary['RpTermin']).'</td>
                <td style="text-align: right;">'.new_decimal($myPrimary['PersenSisa']).'</td>
                <td style="text-align: right;">'.uang_koma_genap_decimal($myPrimary['RpSisa']).'</td>
            </tr>';
        endforeach;

        $html .='
        <tr style="border-top:1px solid silver;">
            <td colspan="4" style="padding-top:10px;padding-bottom:10px;"></td>
            <td class="text-right" style="padding-top:10px;padding-bottom:10px;">SUM = '.new_decimal($SUM_RpRPPM).'</td>
            <td class="text-right" colspan="2"  style="padding-top:10px;padding-bottom:10px;">SUM = '.new_decimal($SUM_RpTermin).'</td>
            <td class="text-right" colspan="2" style="padding-top:10px;padding-bottom:10px;">SUM = '.uang_koma_genap_decimal($SUM_RpSisa).'</td>

        </tr>
        ';
        $SUM_RpRPPM = 0;
        $SUM_RpTermin = 0;
        $SUM_RpSisa = 0;

    endif;

    if ($dataSecondary == 1):

        $html .='
        <tr style="border-bottom:1px solid black;background-color: #95a5a6;color:white;">
            <td style="border:0px solid black;padding-top:5px;padding-bottom:5px;" colspan="9">
                    Kategori : Secondary
            </td>
        </tr>';

        foreach($querySecondary as $mySecondary) :

            $SUM_RpRPPM += $mySecondary['RpRPPM'];
            $SUM_RpTermin += $mySecondary['RpTermin'];
            $SUM_RpSisa += $mySecondary['RpSisa'];

            $html .='
            <tr>
                <td style="padding-top:5px;padding-bottom:5px;padding-left:30px;">'.$mySecondary['JobNo'].'</td>
                <td style="border-left:0px solid black;">'.$mySecondary['JobNm'].'</td>
                <td style="border-left:0px solid black;text-align: right;">'.tgl_baru_tahun_pendek($mySecondary['DateRPPM']).'</td>
                <td style="text-align: right;">'.new_decimal($mySecondary['PersenRPPM']).'</td>
                <td style="text-align: right;">'.new_decimal($mySecondary['RpRPPM']).'</td>
                <td style="text-align: right;">'.new_decimal($mySecondary['PersenTermin']).'</td>
                <td style="text-align: right;">'.new_decimal($mySecondary['RpTermin']).'</td>
                <td style="text-align: right;">'.new_decimal($mySecondary['PersenSisa']).'</td>
                <td style="text-align: right;">'.new_decimal($mySecondary['RpSisa']).'</td>
            </tr>';
        endforeach;

        $html .='
        <tr style="border-top:1px solid silver;">
            <td colspan="4" style="padding-top:10px;padding-bottom:10px;"></td>
            <td class="text-right" style="padding-top:10px;padding-bottom:10px;">SUM = '.uang_koma_genap_decimal($SUM_RpRPPM).'</td>
            <td class="text-right" colspan="2"  style="padding-top:10px;padding-bottom:10px;">SUM = '.uang_koma_genap_decimal($SUM_RpTermin).'</td>
            <td class="text-right" colspan="2" style="padding-top:10px;padding-bottom:10px;">SUM = '.uang_koma_genap_decimal($SUM_RpSisa).'</td>
        </tr>
        ';
        $SUM_RpRPPM = 0;
        $SUM_RpTermin = 0;
        $SUM_RpSisa = 0;

    endif;

    if ($dataSYC == 1):

        $html .='
        <tr style="border-bottom:1px solid black;background-color: #95a5a6;color:white;">
            <td style="border:0px solid black;padding-top:5px;padding-bottom:5px;" colspan="9">
                    Kategori : SYC
            </td>
        </tr>';

        foreach($querySYC as $mySYC) :

            $SUM_RpRPPM += $mySYC['RpRPPM'];
            $SUM_RpTermin += $mySYC['RpTermin'];
            $SUM_RpSisa += $mySYC['RpSisa'];

            $html .='
            <tr>
                <td style="padding-top:5px;padding-bottom:5px;padding-left:30px;">'.$mySYC['JobNo'].'</td>
                <td style="border-left:0px solid black;">'.$mySYC['JobNm'].'</td>
                <td style="border-left:0px solid black;text-align: right;">'.tgl_baru_tahun_pendek($mySYC['DateRPPM']).'</td>
                <td style="text-align: right;">'.new_decimal($mySYC['PersenRPPM']).'</td>
                <td style="text-align: right;">'.uang_koma_genap_decimal($mySYC['RpRPPM']).'</td>
                <td style="text-align: right;">'.new_decimal($mySYC['PersenTermin']).'</td>
                <td style="text-align: right;">'.uang_koma_genap_decimal($mySYC['RpTermin']).'</td>
                <td style="text-align: right;">'.new_decimal($mySYC['PersenSisa']).'</td>
                <td style="text-align: right;">'.uang_koma_genap_decimal($mySYC['RpSisa']).'</td>
            </tr>';
        endforeach;

        $html .='
        <tr style="border-top:1px solid silver;">
            <td colspan="4" style="padding-top:10px;padding-bottom:10px;"></td>
            <td class="text-right" style="padding-top:10px;padding-bottom:10px;">SUM = '.uang_koma_genap_decimal($SUM_RpRPPM).'</td>
            <td class="text-right" colspan="2"  style="padding-top:10px;padding-bottom:10px;">SUM = '.uang_koma_genap_decimal($SUM_RpTermin).'</td>
            <td class="text-right" colspan="2" style="padding-top:10px;padding-bottom:10px;">SUM = '.uang_koma_genap_decimal($SUM_RpSisa).'</td>
        </tr>
        ';
        $SUM_RpRPPM = 0;
        $SUM_RpTermin = 0;
        $SUM_RpSisa = 0;

    endif;

    if ($dataKategoriNull == 1):

        $html .='
        <tr style="border-bottom:1px solid black;background-color: #95a5a6;color:white;">
            <td style="border:0px solid black;padding-top:5px;padding-bottom:5px;" colspan="9">
                    Kategori :
            </td>
        </tr>';

        foreach($queryKategoriNull as $myKategoriNull) :

            $SUM_RpRPPM += $myKategoriNull['RpRPPM'];
            $SUM_RpTermin += $myKategoriNull['RpTermin'];
            $SUM_RpSisa += $myKategoriNull['RpSisa'];

            $html .='
            <tr>
                <td style="padding-top:5px;padding-bottom:5px;padding-left:30px;">'.$myKategoriNull['JobNo'].'</td>
                <td style="border-left:0px solid black;">'.$myKategoriNull['JobNm'].'</td>
                <td style="border-left:0px solid black;text-align: right;">'.tgl_baru_tahun_pendek($myKategoriNull['DateRPPM']).'</td>
                <td style="text-align: right;">'.new_decimal($myKategoriNull['PersenRPPM']).'</td>
                <td style="text-align: right;">'.uang_koma_genap_decimal($myKategoriNull['RpRPPM']).'</td>
                <td style="text-align: right;">'.new_decimal($myKategoriNull['PersenTermin']).'</td>
                <td style="text-align: right;">'.uang_koma_genap_decimal($myKategoriNull['RpTermin']).'</td>
                <td style="text-align: right;">'.new_decimal($myKategoriNull['PersenSisa']).'</td>
                <td style="text-align: right;">'.uang_koma_genap_decimal($myKategoriNull['RpSisa']).'</td>
            </tr>';
        endforeach;

        $html .='
        <tr style="border-top:1px solid silver;">
            <td colspan="4" style="padding-top:10px;padding-bottom:10px;"></td>
            <td class="text-right" style="padding-top:10px;padding-bottom:10px;">SUM = '.uang_koma_genap_decimal($SUM_RpRPPM).'</td>
            <td class="text-right" colspan="2"  style="padding-top:10px;padding-bottom:10px;">SUM = '.uang_koma_genap_decimal($SUM_RpTermin).'</td>
            <td class="text-right" colspan="2" style="padding-top:10px;padding-bottom:10px;">SUM = '.uang_koma_genap_decimal($SUM_RpSisa).'</td>
        </tr>
        ';
        $SUM_RpRPPM = 0;
        $SUM_RpTermin = 0;
        $SUM_RpSisa = 0;

    endif;

    


$html .='
    </tbody>

    <tfoot>
        <tr style="border-top:1px solid silver;">
            <td colspan="4" style="padding-top:10px;padding-bottom:10px;font-weight: bold;">SUBTOTAL</td>
            <td class="text-right" style="padding-top:10px;padding-bottom:10px;font-weight: bold;border-right:1px solid;">
                SUM = '.uang_koma_genap_decimal($SUBTOTAL_RpRPPM).' &nbsp;
            </td>
            <td class="text-right" colspan="2" style="padding-top:10px;padding-bottom:10px;font-weight: bold;border-right:1px solid;" >
                &nbsp; SUM = '.uang_koma_genap_decimal($SUBTOTAL_RpTermin).' &nbsp;
            </td>
            <td class="text-right" colspan="2" style="padding-top:10px;padding-bottom:10px;font-weight: bold;border-right:0px solid;" >
                &nbsp; SUM = '.uang_koma_genap_decimal($SUBTOTAL_RpSisa).'
            </td>
        </tr>
    </tfoot>


</table>';


// exit;

$mpdf->WriteHTML($html);

// $mpdf->Output('Report - PIUTANG PROGRESS FISIK -  ' . date('ymdHis') . '.pdf', 'I');
$mpdf->Output('Report - PIUTANG PROGRESS FISIK -  ' . date('ymdHis') . '.pdf', 'D');