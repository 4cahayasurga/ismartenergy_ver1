<?php

$mpdfConfig = array(
    'mode' => '',
    'format' => 'A4',
    'orientation' => 'P',
    'setAutoBottomMargin' => 'stretch',
    'setAutoTopMargin' => 'stretch',
    'margin-footer' => 5
);


$stylesheet_new = file_get_contents('css/css_entry_rap.css');

$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle('RAP || '.$this->config->item('Company'));
$mpdf->use_kwt = true;


$mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('entry_master/RAP/print/mypdf_rap_header', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('entry_master/RAP/print/mypdf_rap_footer', NULL, TRUE));

$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;
$no = 1;

$html = '';

$html .='

<br>
<table border="0" width="100%" id="mytable" >
    
    <thead>
        <tr>
            <th style="width:7% !important;">Kode RAP</th>
            <th style="width:30% !important;">Uraian</th>
            <th style="text-align: center;width:5% !important;">Volume</th>
            <th style="text-align: center;width:5% !important;">Satuan</th>
            <th style="text-align: center;width:15% !important;">Harga Satuan (Rp)</th>
            <th style="text-align: center;width:15% !important;">Jumlah Harga (Rp)</th>

        </tr>
    </thead>
    <tbody>';
    $header = '';
    $Vol = 0;
    $HrgSatuanRAP_Custom = 0;
    $JumlahHargaRAP = 0;

    foreach($mydata as $rap):

        $header = ($rap['Tipe'] == 'Header') ? 'style="font-weight:bold;background-color:#bdc3c7;"' : '';

        $Vol = ($rap['Tipe'] == 'Header') ?''  : number_format($rap['Vol'], 3);
        $HrgSatuanRAP_Custom = ($rap['HrgSatuanRAP_Custom'] == NULL) ? NULL : new_decimal($rap['HrgSatuanRAP_Custom']);

        $JumlahHargaRAP = number_format($rap['JumlahHargaRAP'],2);

        $html .='

            <tr>
                <td '.$header.'>'.$rap['KdRAP'].'</td>
                <td '.$header.'>'.$rap['Uraian'].'</td>
                <td style="text-align:right;">'.$Vol.'</td>
                <td style="text-align:center;">'.$rap['Uom'].'</td>
                <td style="text-align:right">'.$HrgSatuanRAP_Custom.'</td>
                <td '.$header.' align="right">'.$JumlahHargaRAP.'</td>

            </tr>

        ';

    endforeach;

    $html .='
    </tbody>
</table>';


$mpdf->WriteHTML($html);
// die;
$mpdf->Output('ENTRY-RAP-' . date('ymdHis').'-' . '.pdf', 'D');
// $mpdf->Output('REPORT-OUTSTANDING-APPROVED-PD-' . date('ymdHis') . '.pdf', 'D');
