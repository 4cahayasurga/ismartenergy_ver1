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
$stylesheet = file_get_contents('mycss/rekap_ko_baru.css');




$Company = $this->config->item('Company');
$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle('REPORT (PROCUREMENT) - REKAP KO || '.$Company.' ');
$mpdf->use_kwt = true;


$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('report_view/procurement/rekap_ko/print_pdf/pdf_atas_rekap_ko', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('report_view/procurement/rekap_ko/print_pdf/pdf_bawah_rekap_ko', NULL, TRUE));



$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;
$html = '';

// batas
$no = 1;
$html .=
'
<table style="border-collapse: collapse;width:100%;border-bottom:1px solid black;" border="0" id="rekap_tbl">
    
    <tr >
        <th style="width:3%;border-bottom:1px solid black;border-top:1px solid black;">No.</th>
        <th style="text-align:left;width:8%;border-bottom:1px solid black;border-top:1px solid black;" >No KO</th>
        <th style="text-align:left;width:8%;border-bottom:1px solid black;border-top:1px solid black;">Tanggal</th>
        <th style="text-align:left;border-bottom:1px solid black;border-top:1px solid black;">Vendor</th>
        <th style="text-align:center;width:8%;border-bottom:1px solid black;border-top:1px solid black;">Kategori</th>
        <th style="text-align:right;width:10%;border-bottom:1px solid black;border-top:1px solid black;">Nilai KO (Rp)</th>
        <th style="text-align:right;width:10%;border-bottom:1px solid black;border-top:1px solid black;">Telah Terbayar (Rp)</th>
        <th style="text-align:right;width:10%;border-bottom:1px solid black;border-top:1px solid black;">Sisa Pembayaran (Rp)</th>
        <th style="text-align:center;width:8%;border-bottom:1px solid black;border-top:1px solid black;">Status Closed</th>
    </tr>';

    $no_urut = 1;
    $ClosedBy = NULL;
    $NoKO = NULL;
    $jumlah_data_ko = 0;
    $coba = '';
    foreach($dataRekapKO as $data_ko) : 

        $ClosedBy = ($data_ko->ClosedBy !='' OR $data_ko->ClosedBy !=NULL ) ? 'Yes' : 'No';
        $NoKO = $data_ko->NoKO;
        $myquery = $this->db->query(" SELECT * FROM BLE WHERE NoKO='$NoKO' AND TglBayar>='$dari_tgl' AND TglBayar<='$sampai_tgl' ORDER BY NoKO ");
        $jumlah_data_ko = $myquery->num_rows();
        // $coba = ($jumlah_data_ko > 0 ) ? 'style="color:green;" ' : '';
        $html .=
        '
        <tr  >
            <td style="width:3%;text-align:center;">'.$no_urut++.'</td>
            <td style="text-align:left;">'.$data_ko->NoKO.'</td>
            <td style="text-align:left;">'.tgl_baru($data_ko->TglKO).'</td>
            <td style="text-align:left;">'.$data_ko->VendorNm.'</td>
            <td style="text-align:center;">'.$data_ko->KategoriId.'</td>
            <td style="text-align:right;">'.UangBiasa($data_ko->SubTotal).'</td>
            <td style="text-align:right;">0</td>
            <td style="text-align:right;">0</td>
            <td style="text-align:center;">'.$ClosedBy.'</td>
        </tr>

        ';

        if ($jumlah_data_ko > 0):

            $html .='<tr><td colspan="9" style="padding-bottom:5px !important;"></td></tr>';

            $html .=
            '
            <tr style="font-weight: 690 !important;" >
                <td></td>

                <td style="border-bottom:1px solid black; width:10%;border-left:1px solid black;border-top:1px solid black; ">No PD</td>

                <td style="border-bottom:1px solid black;width:10%;border-left:1px solid black;border-top:1px solid black;">Tgl Bayar</td>

                <td style="border-bottom:1px solid black;width:10%;border-left:1px solid black;border-top:1px solid black;">Nilai (Rp)</td>

                <td colspan="2" style="border-bottom:1px solid black;width:10%;border-left:1px solid black;border-top:1px solid black;border-right:1px solid black;" >Keterangan</td>

                <td></td>

                <td></td>
                <td></td>
            </tr>

            ';

            foreach($myquery->result() as $detail_ko_pd ) :

                $html .=
                '
                <tr style="font-weight: normal !important;" >

                    <td></td>

                    <td style="border-bottom:1px solid black;border-left:1px solid black;border-top:1px solid black;">'.$detail_ko_pd->NoPD.'</td>

                    <td style="border-bottom:1px solid black;border-left:1px solid black;border-top:1px solid black;">'.tgl_baru($detail_ko_pd->TglBayar).'</td>

                    <td style="border-bottom:1px solid black;border-left:1px solid black;border-top:1px solid black;">'.UangBiasa($detail_ko_pd->Amount).'</td>

                    <td colspan="2" style="border-bottom:1px solid black;border-left:1px solid black;border-top:1px solid black;border-right:1px solid black;" >'.$detail_ko_pd->Keterangan.'</td>

                    <td></td>

                    <td></td>
                    <td></td>
                </tr>

                ';

            endforeach;
            $html .='<tr><td colspan="9" style="padding-bottom:5px !important;"></td></tr>';


        endif;

    endforeach;
    $html .='<tr><td colspan="9" style="padding-bottom:5px !important;"></td></tr>';
    $html .='<tr><td colspan="9" style="padding-bottom:5px !important;"></td></tr>';
    $html .='<tr><td colspan="9" style="padding-bottom:5px !important;"></td></tr>';
$html .='</table>';


$mpdf->WriteHTML($html);

// $mpdf->Output('REPORT (PROCUREMENT) - REKAP KO - ' . date('ymdHis') . '.pdf', 'I');
$mpdf->Output('REPORT (PROCUREMENT) - REKAP KO - ' . date('ymdHis') . '.pdf', 'D');
