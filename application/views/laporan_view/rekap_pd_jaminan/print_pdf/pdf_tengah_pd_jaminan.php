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
$stylesheet_new = file_get_contents('css/css_rekap_pd_jaminan.css');


$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle('REPORT - REKAP PD JAMINAN || '.$this->config->item('Company'));
$mpdf->use_kwt = true;


$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('laporan_view/rekap_pd_jaminan/print_pdf/pdf_atas_pd_jaminan', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('laporan_view/rekap_pd_jaminan/print_pdf/pdf_bawah_pd_jaminan', NULL, TRUE));



$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;
$html = '';

// batas
$no = 1;

$html .='
    <table border="0" style="border-collapse:collapse;" width="100%"  class="atur_huruf tbl_rap" >

        <tr >

            <th class="atur_th" style="width:3%;border-bottom:1px solid black;">No.</th>
            <th class="atur_th" style="width:10%;border-bottom:1px solid black;" >No PD</th>
            <th class="atur_th" style="width:6% !important;border-bottom:1px solid black;">Tgl PD</th>
            <th class="atur_th" style="width:6% !important;border-bottom:1px solid black;">Tipe Form</th>
            <th class="atur_th" style="text-align: left;border-bottom:1px solid black;">Deskripsi</th>
            <th class="atur_th" style="text-align: right;width:8% !important;border-bottom:1px solid black;">Total PD (Rp)</th>
            <th class="atur_th"  style="text-align: right;width:8% !important;border-bottom:1px solid black;" >Tgl Bayar</th>
            <th class="atur_th" style="text-align: right;width:8% !important;border-bottom:1px solid black;" >Total Pembayaran</th>
        </tr>';

$nomor = 1;
$JobNo = '';
$total_pd = 0;

$Company = $this->config->item('Company');

foreach ($data_job as $data_job) :
    $JobNo = $data_job['JobNo'];

$html .='
        <tr>
            <td>'.$nomor++.'</td>
            <td colspan="7" style="font-weight:bold;">'.$data_job['JobNo'].' - '.$data_job['JobNm'].' ';

            $dapat_data_pd = $this->db->query("
                SELECT A.*, B.JobNo + ' - ' + B.JobNm AS 'JobNm1',
                (SELECT TOP 1 TglBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'TglBayar',
                (SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'BLEAmount'
                FROM PdHdr A LEFT JOIN Job B ON A.JobNo=B.JobNo WHERE A.Jaminan='1' AND A.ApprovedByKK IS NOT NULL AND 
                B.Company='$Company' AND A.JobNo='$JobNo'  ORDER BY A.NoPD DESC ")->result_array();

            foreach($dapat_data_pd as $data_pd ) {
                $total_pd += $data_pd['BLEAmount'];

                $html .='<tr>
                            <td></td>
                            <td style="padding-left:10px;" >'.$data_pd['NoPD'].'</td>
                            <td >'.tgl_baru($data_pd['TglPD']).'</td>
                            <td >'.$data_pd['TipeForm'].'</td>
                            <td   >'.$data_pd['Deskripsi'].'</td>
                            <td style="text-align:right;"  >'.UangBiasa($data_pd['TotalPD']).'</td>
                            <td style="text-align:right;"  >'.tgl_baru($data_pd['TglBayar']).'</td>
                            <td style="text-align:right;" >'.UangBiasa($data_pd['BLEAmount']).'</td>
                        </tr>';

                }

    $html .='</td></tr>
    <tr>
        <td></td>
        <td colspan="7" style="border-bottom:1px solid;text-align:right;font-size: 9px;font-weight: bold;" >SUM = '.UangBiasa($total_pd).'</td>
    </tr>
            ';


endforeach;

$html .='

</table>';


$mpdf->WriteHTML($html);

// $mpdf->Output('REPORT - REKAP PD JAMINAN - ' . date('ymdHis') . '.pdf', 'I');
$mpdf->Output('REPORT - REKAP PD JAMINAN - ' . date('ymdHis') . '.pdf', 'D');
