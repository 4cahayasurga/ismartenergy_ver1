<?php

$mpdfConfig = array(
    'mode' => '',
    'format' => 'A4',
    'orientation' => 'L',
    'setAutoBottomMargin' => 'stretch',
    'setAutoTopMargin' => 'stretch',
);


$stylesheet_new = file_get_contents('css/css_query_pdpj.css');

$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle('REPORT QUERY PD/PJ|| '.$this->config->item('Company'));
$mpdf->use_kwt = true;


// $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('laporan_view/report_query_pdpj/print/pdf_querypdpj_header', NULL, TRUE), 'O');
// $mpdf->SetHTMLFooter($this->load->view('laporan_view/report_query_pdpj/print/pdf_querypdpj_footer', NULL, TRUE));

$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;
$no = 1;

$html = '';

$html .= '

<table id="mytable" border="0" width="100%">
    <thead>
        <tr class="satu">
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

foreach ($data_query_pdpj as $mydata) :

    $html .= '
        <tr>
            <td align="left">' . $mydata['JobNo'] . '</td>
            <td align="left">' . $mydata['NoPD'] . '</td>
            <td align="left">' . $mydata['TglPD'] . '</td>
            <td align="left">' . $mydata['NoRef'] . '</td>
            <td align="left">' . $mydata['Alokasi'] . '</td>
            <td align="left">' . $mydata['TipeForm'] . '</td>
            <td align="left">' . $mydata['Deskripsi'] . '</td>
            <td align="left">' . $mydata['NoKO'] . '</td>
            <td align="left">0</td>
            <td align="left">' . $mydata['NoTagihan'] . '</td>
            <td align="left">' . $mydata['NoRek'] . '</td>
            <td align="left">' . $mydata['AtasNama'] . '</td>
            <td align="left">' . $mydata['TotalPD'] . '</td>
            <td align="left">' . $mydata['TglBayar'] . '</td>
            <td align="left">' . $mydata['JenisTrf'] . '</td>
            <td align="left">' . $mydata['BLEAmount'] . '</td>
            <td align="left">' . $mydata['RekId'] . '</td>
            <td align="left">' . $mydata['NoPJ'] . '</td>
            <td align="left">' . $mydata['TglPJ'] . '</td>
            <td align="left">' . $mydata['TotalPJ'] . '</td>
            <td align="left">0</td>


        </tr>
        ';

endforeach;

$html .= '
    </tbody>


    ';



$html .= '
</table>';


$mpdf->WriteHTML($html);
// die;
$mpdf->Output('REPORT-QUERY PD/PJ-' . date('ymdHis') . '.pdf', 'I');
// $mpdf->Output('REPORT-OUTSTANDING-APPROVED-PD-' . date('ymdHis') . '.pdf', 'D');
