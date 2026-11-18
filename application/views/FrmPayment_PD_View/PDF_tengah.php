<?php

$mpdfConfig = array(
    'mode' => 'utf-8',
    'format' => 'A4',
    // 'margin_header' => 0,
    // 'margin_footer' => 0,
    'orientation' => 'P',
    'setAutoBottomMargin' => 'stretch',
    'setAutoTopMargin' => 'stretch',

);
$stylesheet = file_get_contents('css/bootstrap-3.3.7.min.css');
$stylesheet_new = file_get_contents('application/views/FrmPayment_PD_View/css_PDF.css');


$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle('Payment Export PDF || '.$this->config->item('Company'));
$mpdf->use_kwt = true;

$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('FrmPayment_PD_View/PDF_atas', NULL, TRUE), 'O');


$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;

$html ='';
// $html .= '<h1>ddd</h1>';


$mpdf->WriteHTML($html);

$Company = $this->config->item('Company');
$mpdf->Output('PaymentEXpPDF.pdf', 'I');
