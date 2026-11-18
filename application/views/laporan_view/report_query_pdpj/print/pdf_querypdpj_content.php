<?php

$mpdfConfig = array(
    'mode' => '',
    'format' => 'A3',
    'orientation' => 'L',
    'setAutoBottomMargin' => 'stretch',
    'setAutoTopMargin' => 'stretch',
    // 'margin-left' => 0,
    // 'margin-right' => 0
);


$stylesheet_new = file_get_contents('css/css_query_pdpj.css');

$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle('REPORT QUERY PD/PJ|| '.$this->config->item('Company'));
$mpdf->use_kwt = true;


// $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('laporan_view/report_query_pdpj/print/pdf_querypdpj_header', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('laporan_view/report_query_pdpj/print/pdf_querypdpj_footer', NULL, TRUE));

$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;
$no = 1;

$html = '';
// <tr>
// <th style="border:1px solid black;text-align:right;" colspan="17">Grand Total PD ' . number_format($this->session->flashdata('GrandTotal_PD')) . '</th>
// <th style="border:1px solid black;text-align:right;" colspan="2">Grand Total Pembayaran ' . number_format($this->session->flashdata('GrandTotal_Pembayaran')) . '</th>
// <th style="border:1px solid black;text-align:right;" colspan="2" width="100px">Grand Total PD ' . number_format($this->session->flashdata('GrandTotal_PJ')) . '</th>
// </tr>
$html .= '

<table id="mytable" border="0" width="100%">
    <thead>
       
        <tr class="satu" style="background-color:gray;">
            <th align="left">Job No</th>
            <th align="left">No PD</th>
            <th align="left">Tgl PD</th>
            <th align="left">No Ref.Lapangan</th>
            <th align="left">Alokasi</th>
            <th align="left">Tipe Form</th>
            <th align="left">Deskripsi</th>
            <th align="left">No. KO</th>
            <th align="left">Total KO (Rp)</th>
            <th align="left">No. Tagihan</th>
            <th align="left">Rekening Penerima</th>
            <th align="left">Atas Nama</th>
            <th align="left">Total PD (Rp)</th>
            <th align="left">Tgl Bayar</th>
            <th align="left">Via</th>
            <th align="left">Total Pembayaran</th>
            <th align="left">Rekening Pengirim</th>
            <th align="left">No PJ</th>
            <th align="left">Tgl PJ</th>
            <th align="left">Total PJ</th>
            <th align="left">Saldo (Rp)</th>
        </tr>
    </thead>
    <tbody>';

$sum_total_pd = 0;
$sum_total_pembayaran = 0;
$sum_total_pj = 0;
foreach ($data_query_pdpj as $mydata) :

    $sum_total_pd += $mydata['TotalPD'];
    $sum_total_pembayaran += $mydata['BLEAmount'];
    $sum_total_pj += $mydata['TotalPJ'];

    $html .= '
        <tr>
            <td align="left">' . $mydata['JobNo'] . '</td>
            <td align="left">' . $mydata['NoPD'] . '</td>
            <td align="left">' . tgl_baru($mydata['TglPD']) . '</td>
            <td align="left">' . $mydata['NoRef'] . '</td>
            <td align="left">' . $mydata['Alokasi'] . '</td>
            <td align="left">' . $mydata['TipeForm'] . '</td>
            <td align="left">' . $mydata['Deskripsi'] . '</td>
            <td align="left">' . $mydata['NoKO'] . '</td>
            <td align="left">0</td>
            <td align="left">' . $mydata['NoTagihan'] . '</td>
            <td align="left">' . $mydata['NoRek'] . '</td>
            <td align="left">' . $mydata['AtasNama'] . '</td>
            <td align="left">' . UbahGabunganUang($mydata['TotalPD']) . '</td>
            <td align="left">' . tgl_baru($mydata['TglBayar']) . '</td>
            <td align="left">' . $mydata['JenisTrf'] . '</td>
            <td align="left">' . UbahGabunganUang($mydata['BLEAmount']) . '</td>
            <td align="left">' . $mydata['RekId'] . '</td>
            <td align="left">' . $mydata['NoPJ'] . '</td>
            <td align="left">' . tgl_baru($mydata['TglPJ']) . '</td>
            <td align="left">' . UbahGabunganUang($mydata['TotalPJ']) . '</td>
            <td align="left">0</td>


        </tr>
        ';

endforeach;

$html .= '
    </tbody>
    <tfoot>
        <tr>
            <th style="text-align:right;border:0px solid black;" colspan="13">' . UbahGabunganUang($sum_total_pd) . '</th>
            <th style="text-align:right;border:0px solid black;" colspan="3">' . UbahGabunganUang($sum_total_pembayaran) . '</th>
            <th style="text-align:right;border:0px solid black;" colspan="4">' . UbahGabunganUang($sum_total_pj) . '</th>
            <th style="text-align:right;border:0px solid black;"></th>
        </tr>
    </tfoot>


    ';



$html .= '
</table>';


$mpdf->WriteHTML($html);
// die;
$mpdf->Output('REPORT-QUERY PD/PJ-' . date('ymdHis') . '.pdf', 'D');
// $mpdf->Output('REPORT-QUERY PD/PJ-' . date('ymdHis') . '.pdf', 'I');
// $mpdf->Output('REPORT-OUTSTANDING-APPROVED-PD-' . date('ymdHis') . '.pdf', 'D');
