<?php
$mpdfConfig = array(
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_header' => 0,
    'margin_header' => 5,
    'margin_footer' => 5,
    'orientation' => 'L',
    'margin_left' => 1,
    'margin_right' => 1,
    'setAutoBottomMargin' => 'stretch',
    'setAutoTopMargin' => 'stretch',

    // 'mode' => '',
    //     'format' => 'A4',
    //     'default_font_size' => 0,
    //     'default_font' => '',
    //     'margin_left' => 1,
    //     'margin_right' => 1,
    //     'margin_top' => 16,
    //     'margin_bottom' => 16,
    //     'margin_header' => 9,
    //     'margin_footer' => 9,
    //     'orientation' => 'P',

);


$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$stylesheet = file_get_contents('css/bootstrap-3.3.7.min.css');
$stylesheet_baru = file_get_contents('mycss/PD_keuangan.css');

$mpdf->SetTitle('PD KEUANGAN - '.$this->config->item('Company'));
$mpdf->use_kwt = true;

$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_baru, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('Pdf_master_view/PD_keuangan_view/pd_keuangan_header', NULL, TRUE), 'O');

$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;

$html = '';

// $html .='ok';
$Company = $this->config->item('Company');
$mpdf->WriteHTML($html);
// $mpdf->Output('MDH-PURCHASE-ORDER-' . date('ymdHis') . '.pdf', 'I');
$mpdf->Output('PD-KEUANGAN - '.$Company.' - ' . date('ymdHis') . '.pdf', 'I');