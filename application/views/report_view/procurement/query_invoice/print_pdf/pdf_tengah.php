<?php

$mpdfConfig = array(
    'mode' => 'utf-8',
    'format' => 'A4',
    'orientation' => 'L',
    'setAutoBottomMargin' => 'stretch',
    'setAutoTopMargin' => 'stretch',
    'margin-left' => 0,
    'margin-right' => 0,

);
$stylesheet = file_get_contents('css/bootstrap-3.3.7.min.css');
$stylesheet_new = file_get_contents('css/query_invoice_css.css');


$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle('REPORT (PROCUREMENT) - QUERY INVOICE || '.$this->config->item('Company').' ');
$mpdf->use_kwt = true;


$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('report_view/procurement/query_invoice/print_pdf/pdf_atas', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('report_view/procurement/query_invoice/print_pdf/pdf_bawah', NULL, TRUE));



$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;
$html = '';
$no = 1;

$nomor_urut = 1;
$SisaInvoice = 0;

$html .='
<table border="0" width="100%"  class="atur_huruf" id="table_detail">
    
    <thead>
        <tr >
            <th style="border-bottom:1px solid black;padding-top:5px;padding-bottom:5px;text-align:center;width:3%;">No.</th>
            <th style="border-bottom:1px solid black;padding-left:5px;">Job No</th>
            <th style="border-bottom:1px solid black;padding-left:5px;">No KO</th>
            <th style="border-bottom:1px solid black;padding-left:5px;">Invoice</th>
            <th style="border-bottom:1px solid black;padding-left:5px;">Tgl Invoice</th>
            <th style="border-bottom:1px solid black;padding-left:5px;">Due Date</th>
            <th style="border-bottom:1px solid black;text-align:right;">Total Invoice (Rp)</th>
            <th style="border-bottom:1px solid black;text-align:right;">PPN (Rp)</th>
            <th style="border-bottom:1px solid black;text-align:right;">PPH (Rp)</th>
            <th style="border-bottom:1px solid black;padding-left:5px;">No. Faktur Pajak</th>
            <th style="border-bottom:1px solid black;padding-left:5px;">Tgl Faktur Pajak</th>
            <th style="border-bottom:1px solid black;text-align:right;">Payment Amount (Rp)</th>
            <th style="border-bottom:1px solid black;text-align:right;">Sisa Invoice (Rp)</th>
        </tr>
    </thead>
    <tbody>';

  
    foreach($data_query_inv as $mydata  ) :
    
        $html .='
        <tr>
            <td style="padding-top:5px;padding-bottom:5px;text-align:center;width:3%;">'.$nomor_urut++.'</td>
            <td style="padding-left:5px;">'.$mydata->JobNo1.'</td>
            <td style="padding-left:5px;">'.$mydata->NoKO.'</td>
            <td style="padding-left:5px;">'.$mydata->InvNo.'</td>
            <td style="padding-left:5px;">'.tgl_baru($mydata->InvDate).'</td>
            <td style="padding-left:5px;">'.tgl_baru($mydata->DueDate).'</td>
            <td style="text-align:right;">'.UangBiasa($mydata->Total).'</td>
            <td style="text-align:right;">'.UangBiasa($mydata->PPN).'</td>
            <td style="text-align:right;">'.UangBiasa($mydata->PPH).'</td>
            <td style="padding-left:5px;">'.$mydata->FPNo.'</td>
            <td style="padding-left:5px;">'.tgl_baru($mydata->FPDate).'</td>
            <td style="text-align:right;">'.UangBiasa($mydata->PaymentAmount).'</td>
            <td style="text-align:right;">'.UangBiasa($SisaInvoice).'</td>
        </tr>';

    endforeach;

$html.='</tbody></table>';


$mpdf->WriteHTML($html);
// $mpdf->Output('REPORT (PROCUREMENT) - QUERY INVOICE - '.$this->config->item('Company').' - ' . date('ymdHis') . '.pdf', 'I');
$mpdf->Output('REPORT (PROCUREMENT) - QUERY INVOICE - '.$this->config->item('Company').' - ' . date('ymdHis') . '.pdf', 'D');
