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
$stylesheet_baru = file_get_contents('mycss/css_QueryHutangKO.css');

$mpdf->SetTitle('REKAP Query Hutang KO');
$mpdf->use_kwt = true;

$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_baru, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('Pdf_master_view/query_HutangKO/header_pdf_QueryHutangKO', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('Pdf_master_view/query_HutangKO/footer_pdf_QueryHutangKO', NULL, TRUE));

$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;


$html = '';

$html .= '
<table width="100%" border="0" id="table_QHutangKO">

    <thead>
        <tr>
            <th>No.</th>
            <th>Job. No</th>
            <th>Job Name</th>
            <th>No. KO</th>
            <th>Tgl KO</th>
            <th>Tipe KO</th>
            <th>Vendor</th>
            <th>Amount KO (Rp)</th>
            <th>Payment KO (Rp)</th>
            <th>Remaining KO (Rp)</th>
            <th>Amount Invoice (Rp)</th>
            <th>Payment Invoice (Rp)</th>
            <th>Remaining Invoice (Rp)</th>

        </tr>
    </thead>';

$html .='
<tbody>';  

        $nomor = 1;
        $TotalAmountKO = 0;
        $TotalPaymentKO = 0;
        $TotalRemainingKO = 0;


        $TotalAmountInv = 0;
        $TotalPaymentInv = 0;
        $TotalRemainingInv = 0;
    if(count($dataKO) == 0){
        $html .='<tr><td colspan="13">Data tidak ditemukan</td></tr>';
        
    }else{
       
        foreach($dataKO as $dataKO):

            $TotalAmountKO += $dataKO['TotalKO'];
            $TotalPaymentKO += $dataKO['PaymentKO'];
            $TotalRemainingKO += $dataKO['RemainingKO'];

            $TotalAmountInv += $dataKO['TotalInv'];
            $TotalPaymentInv += $dataKO['PaymentInv'];
            $TotalRemainingInv += $dataKO['RemainingInv'];


        
        $html .='
        <tr>
            <td style="text-align:left;border-bottom:1px solid silver;" >'.$nomor++.'</td>
            <td style="text-align:left;border-bottom:1px solid silver;" >'.$dataKO['JobNo'].'</td>
            <td style="text-align:left;border-bottom:1px solid silver;">'.$dataKO['JobNm'].'</td>
            <td style="text-align:left;border-bottom:1px solid silver;">'.$dataKO['NoKO'].'</td>
            <td style="text-align:left;border-bottom:1px solid silver;">'.TanggalSaya($dataKO['TglKO']).'</td>
            <td style="text-align:left;border-bottom:1px solid silver;">'.$dataKO['KategoriId'].'</td>
            <td style="text-align:left;border-bottom:1px solid silver;">'.$dataKO['VendorNm'].'</td>
            <td style="text-align:right;border-bottom:1px solid silver;">'.new_decimal($dataKO['TotalKO']).'</td>
            <td style="text-align:right;border-bottom:1px solid silver;">'.new_decimal($dataKO['PaymentKO']).'</td>
            <td style="text-align:right;border-bottom:1px solid silver;">'.new_decimal($dataKO['RemainingKO']).'</td>
            <td style="text-align:right;border-bottom:1px solid silver;">'.new_decimal($dataKO['TotalInv']).'</td>
            <td style="text-align:right;border-bottom:1px solid silver;">'.new_decimal($dataKO['PaymentInv']).'</td>
            <td style="text-align:right;border-bottom:1px solid silver;">'.new_decimal($dataKO['RemainingInv']).'</td>
        </tr>
       
        ';
        endforeach;

    }
    
    


$html .='
</tbody>';

$html .='
<tfoot>';
    
    $html .='
    <tr>
        <th colspan="7"></th>
        <th style="text-align:right;">Total = '.new_decimal($TotalAmountKO).'</th>
        <th style="text-align:right;">Total = '.new_decimal($TotalPaymentKO).'</th>
        <th style="text-align:right;">Total = '.new_decimal($TotalRemainingKO).'</th>

        <th style="text-align:right;">Total = '.new_decimal($TotalAmountInv).'</th>
        <th style="text-align:right;">Total = '.new_decimal($TotalPaymentInv).'</th>
        <th style="text-align:right;">Total = '.new_decimal($TotalRemainingInv).'</th>
    </tr>
    ';


$html .='
</tfoot>';



$html .='
</table>';

// exit;

$mpdf->WriteHTML($html);

// $mpdf->Output('REKAP - QUERY-HUTANGKO - ' . date('ymdHis') . '.pdf', 'I');
$mpdf->Output('REKAP-QUERY-HUTANGKO - ' . date('ymdHis') . '.pdf', 'D');