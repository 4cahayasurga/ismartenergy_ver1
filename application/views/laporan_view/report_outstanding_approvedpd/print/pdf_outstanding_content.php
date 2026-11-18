<?php

$mpdfConfig = array(
    'mode' => '',
    'format' => 'A4',
    'orientation' => 'L',
    'setAutoBottomMargin' => 'stretch',
    'setAutoTopMargin' => 'stretch',
);


$stylesheet_new = file_get_contents('css/css_outstanding_approved_pd.css');

$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle('REPORT OUTSTANDING / APPROVED PD || '.$this->config->item('Company'));
$mpdf->use_kwt = true;


// $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('laporan_view/report_outstanding_approvedpd/print/pdf_outstanding_header', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('laporan_view/report_outstanding_approvedpd/print/pdf_outstanding_footer', NULL, TRUE));

$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;
$no = 1;

$html = '';
// $html .='<div style="width:100%;border:1px solid silver;">';
$html .= '

<table id="mytable" border="0" width="100%">
    <thead>

        <tr>
            <td border="0" style="background-color:transparent;font-size:12px;" colspan="8"><i>*Terbilang : ' . terbilang($subtotal) . '</i>
            </td>
            <td border="0" style="background-color:transparent;font-weight: bold;font-size:14px;" colspan="6" align="right">Sub Total : ' . number_format($subtotal) . '</td>
        </tr>

        <tr class="satu">
            <th align="center" width="10px" >No</th>
            <th align="left">Job No</th>
            <th align="left">Job Name</th>
            <th align="left">No PD</th>
            <th align="left">Alokasi</th>
            <th align="left">Tipe Form</th>
            <th align="left">Atas Nama</th>
            <th align="left">Rekening Penerima</th>
            <th align="left">No KO</th>
            <th align="left">Total PD</th>
            <th align="left">Tgl PD</th>
            <th align="left">Jth Tempo</th>
            <th align="left">Deskripsi</th>
            <th align="left">Approved By</th>
            
        </tr>
    </thead>
    <tbody>';

$subtotal_pd = 0;
foreach ($mydata as $mydata) :
    $subtotal_pd += $mydata['TotalPD'];

    $html .= '
        <tr>
            <td align="center">' . $no++ . '</td>
            <td align="left">' . $mydata['JobNo'] . '</td>
            <td align="left">' . $mydata['JobNm'] . '</td>
            <td align="left">' . $mydata['NoPD'] . '</td>
            <td align="left">' . $mydata['Alokasi'] . '</td>
            <td align="left">' . $mydata['TipeForm'] . '</td>
            <td align="left">' . $mydata['AtasNama'] . '</td>
            <td align="left">' . $mydata['NoRek'] . '</td>
            <td align="left">' . $mydata['NoKO'] . '</td>
            <td align="left">' . UbahGabunganUang($mydata['TotalPD']) . '</td>
            <td align="left">' . tgl_baru($mydata['TglPD']) . '</td>
            <td align="left">' . tgl_baru($mydata['DueDate']) . '</td>
            <td align="left">' . $mydata['Deskripsi'] . '</td>
            <td align="left">' . $mydata['ApprovedBy'] . '</td>

        </tr>
        ';

endforeach;
$html .= '
    </tbody>
    <tfoot >
        <tr>
            <td border="0" style="background-color:transparent;font-size:12px;border-top:1px solid silver;" colspan="8"><i>*Terbilang : ' . terbilang($subtotal) . '</i>
            </td>
            <td border="0" style="background-color:transparent;font-weight: bold;font-size:14px;border-top:1px solid silver;" colspan="6" align="right">Sub Total : ' . number_format($subtotal) . '</td>
        </tr>
    </tfoot>

    ';



$html .= '
</table>';


$mpdf->WriteHTML($html);
// die;
$mpdf->Output('REPORT-OUTSTANDING-APPROVED-PD-' . date('ymdHis') . '.pdf', 'D');
// $mpdf->Output('REPORT-OUTSTANDING-APPROVED-PD-' . date('ymdHis') . '.pdf', 'D');
