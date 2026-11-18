<?php
ini_set("pcre.backtrack_limit", "5000000");
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
$stylesheet_new = file_get_contents('css/css_rkp_pembayaran.css');


$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle('REPORT REKAP PEMBAYARAN || ' . $this->config->item('Company'));
$mpdf->use_kwt = true;


$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('laporan_view/rkp_pembayaran/print/print_rkp_pembayaran_header', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('laporan_view/rkp_pembayaran/print/print_rkp_pembayaran_footer', NULL, TRUE));



$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$no = 1;
$html = '';

$html .= '

<table border="0" width="100%" class="atur_huruf">

    <thead>
        <tr>
            <th class="text-center"></th>
            <th class="text-center"></th>
            <th style="border-bottom:1px solid black;padding-top:5px;padding-bottom:5px;width:2%;text-align:center;" class="atur_huruf">No</th>
            <th style="border-bottom:1px solid black;">Tanggal</th>
            <th style="border-bottom:1px solid black;">Form</th>
            <th style="border-bottom:1px solid black;">No PD</th>
            <th style="border-bottom:1px solid black;">Job Name</th>
            <th style="border-bottom:1px solid black;">Nama Penerima</th>
            <th style="border-bottom:1px solid black;">Rekening Penerima</th>
            <th style="border-bottom:1px solid black;">Bank Penerima</th>
            <th style="border-bottom:1px solid black;">Nilai Transfer</th>
            <th style="border-bottom:1px solid black;">Bank Pengirim</th>
            <th style="border-bottom:1px solid black;">No Cek/BG</th>
            <th style="border-bottom:1px solid black;">Rekening Pengirim</th>
            <th style="border-bottom:1px solid black;">No Kontrak/PO</th>
            <th style="border-bottom:1px solid black;">Keterangan</th>
        </tr>
    </thead>
    <tbody>';

foreach ($data_rkp as $mydata) :

    $html .= '

        <tr>
            <td class="huruf_td" >
                <input type="text" class="form-control input-lg" style="width:5%;">
            </td>
            <td class="huruf_td" >
                <input type="text" class="form-control input-lg" style="width:5%;">
            </td>  
            <td align="center" style="width:2%;text-align:center;">' . $no++ . '</td>
            <td style="width:20px !important;" class="huruf_td">' . date('d-M', strtotime($mydata['TglBayar'])) . '</td>
            <td class="huruf_td" >' . $mydata['TipeForm'] . '</td>
            <td class="huruf_td" >' . $mydata['NoPD'] . '</td>
            <td class="huruf_td" >' . $mydata['JobNm'] . '</td>
            <td class="huruf_td" >' . $mydata['NamaTerima'] . '</td>
            <td class="huruf_td" >' . $mydata['NoRekTerima'] . '</td>
            <td class="huruf_td" >' . $mydata['BankTerima'] . '</td>
            <td class="huruf_td" >' . uang_koma_genap_decimal($mydata['Amount']) . '</td>
            <td class="huruf_td" >' . $mydata['BankKirim'] . '</td>
            <td class="huruf_td" >' . $mydata['JenisTrf'] . '</td>
            <td class="huruf_td" >' . $mydata['NoRekKirim'] . '</td>
            <td class="huruf_td" >' . $mydata['NoKO'] . '</td>
            <td class="huruf_td" >' . $mydata['Keterangan'] . '</td>  

        </tr>
        ';
endforeach;

$html .= '

    </tbody>


</table>';


$html .= '
<table border="0" width="50%" class="atur_huruf" style="padding-top:10px;">
    <tr>
        <th class="text-center" width="14%"></th>
        <th class="text-center" width="14%"></th>
        <th class="text-center" width="14%">Dibuat</th>
        <th class="text-center" width="14%">Diperiksa</th>
        <th class="text-center" width="14%">Disetujui</th>
        <th></th>
    </tr>

    <tr>
        <th class="text-center" width="14%"></th>
        <th class="text-center" width="14%"></th>
        <th class="text-center" width="14%">

            <input type="text" class="form-control input-lg" style="width:5%;">

        </th>
        <th class="text-center" width="14%">

            <input type="text" class="form-control input-lg" style="width:5%;">

        </th>
        <th class="text-center" width="14%">

            <input type="text" class="form-control input-lg" style="width:5%;">

        </th>
        <th></th>
    </tr>

    
</table>';





$mpdf->WriteHTML($html);
// exit;
// $mpdf->Output('REPORT- REKAP PEMBAYARAN - ' . date('ymdHis') . '.pdf', 'I');
$mpdf->Output('REPORT- REKAP PEMBAYARAN - ' . date('ymdHis') . '.pdf', 'D');
