<?php
$mpdfConfig = array(
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_header' => 0,
    'margin_header' => 5,
    'margin_footer' => 5,
    'orientation' => 'L',
    'margin_left' => 7,
    'margin_right' => 7,
    'setAutoBottomMargin' => 'stretch',
    'setAutoTopMargin' => 'stretch',

);


$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$stylesheet = file_get_contents('css_terbaru/css_bootstrap3.css');
$stylesheet_baru = file_get_contents('css_terbaru/css_rkp_inv_supplier.css');

$mpdf->SetTitle('REKAP INVOICE SUPPLIER || '.$this->Company);
$mpdf->use_kwt = true;

$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_baru, \Mpdf\HTMLParserMode::HEADER_CSS);


$mpdf->SetHTMLHeader($this->load->view('report_view/procurement/rekap_invoice_supplier/pdf_atas', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('report_view/procurement/rekap_invoice_supplier/pdf_bawah', NULL, TRUE));

$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;
$html ='';

$html .='
<table class="pengaturan_tbl" style="width: 100%;border-collapse: collapse;" border="0">
    
    <tr class="huruf_set border_bawah">
        <th style="width: 2%;text-align: center;font-size:10px;" class="border_bawah">No</th>
        <th style="width: 12%;text-align: left;" class="border_bawah" >Supplier</th>
        <th style="width: 4%;text-align: left;" class="border_bawah" >Job No</th>
        <th style="width: 12%;text-align: left;" class="border_bawah" >Proyek</th>
        <th style="width: 7%;text-align: left;" class="border_bawah" >No KO</th>
        <th style="width: 12%;text-align: left;" class="border_bawah" >No Invoice</th>
        <th style="width: 7%;text-align: left;" class="border_bawah" >Tgl Invoice</th>
        <th style="width: 10%;text-align: right;" class="border_bawah" >Nilai Invoice (Rp)</th>
        <th style="width: 3%;text-align: left;" class="border_bawah" >Paraf</th>
        <th style="width: 10%;text-align: left;" class="border_bawah" >No. Faktur Pajak</th>
        <th style="width: 7%;text-align: left;" class="border_bawah" >Tgl Faktur</th>
        <th class="border_bawah">Keterangan</th>
    </tr>';

    $nomor_urut = 1;
    $sumTotal_Detail = 0;

    foreach ($dataInv as $inv):

        $html .='

        <tr class="huruf_set">
            <td class="huruf_set">'.$nomor_urut++.'</td>
            <td class="huruf_set">'.$inv->VendorNm.'</td>
            <td class="huruf_set">'.$inv->JobNo.'</td>
            <td class="huruf_set">'.$inv->JobNm.'</td>
            <td class="huruf_set">'.$inv->NoKO.'</td>
            <td class="huruf_set">'.$inv->InvNo.'</td>
            <td class="huruf_set">'.TanggalSaya($inv->InvDate).'</td>
            <td class="huruf_set" align="right">'.UangBiasa($inv->Total).'</td>
            <td class="huruf_set"></td>
            <td class="huruf_set">'.$inv->FPNo.'</td>

            <td class="huruf_set">'.TanggalSaya($inv->FPDate).'</td>
            <td class="huruf_set">'.$inv->Keterangan.'</td>
        </tr>

        ';

        $myquery = $this->db->query("
            SELECT A.NoKO, A.InvNo, A.NoPD,  
            ISNULL((SELECT SUM(PaymentAmount) FROM BLE, InvPD, PdHdr WHERE  
                (BLE.NoPD=PdHdr.NoPD OR BLE.NoPD=PdHdr.NoRef) AND PdHdr.NoKO=InvPD.NoKO AND InvPD.NoPD=PdHdr.NoPD AND  
                InvPD.NoKO=A.NoKO AND InvPD.InvNo=A.InvNo AND InvPD.NoPD=A.NoPD),0) AS BLEAmount,  
            (SELECT TOP 1 TglBayar FROM BLE, InvPD, PdHdr WHERE  (BLE.NoPD=PdHdr.NoPD OR BLE.NoPD=PdHdr.NoRef) AND PdHdr.NoKO=InvPD.NoKO AND InvPD.NoPD=PdHdr.NoPD AND  InvPD.NoKO=A.NoKO AND InvPD.InvNo=A.InvNo AND InvPD.NoPD=A.NoPD) AS TglBayar  
            FROM InvPD A WHERE NoKO='$inv->NoKO' AND InvNo='$inv->InvNo' ");


             if ($myquery->num_rows() > 0) :

                $html.='
                <tr>
                    <td colspan="12" style="padding-left: 20px !important;">
                        <br>
                        <table style="border:1px solid black;" width="50%">
                            <tr class="atur_td_detail">
                                <td class="aturpading paddingkiri border_bawah" >No. PD</td>
                                <td class="aturpading kanan border_bawah">Payment Amount (Rp)</td>
                                <td class="aturpading border_bawah paddingkiri" >Tgl Bayar</td>
                                <td width="5%"></td>
                            </tr>';

                            foreach($myquery->result() as $dtl) {
                                $sumTotal_Detail += $dtl->BLEAmount;

                                $html .='<tr class="atur_td_detail">
                                    <td class="aturpading paddingkiri huruf_set" >'.$dtl->NoPD.'</td>
                                    <td class="aturpading kanan huruf_set" >'.UangBiasa($dtl->BLEAmount).'</td>
                                    <td class="aturpading paddingkiri huruf_set"  >'.TanggalSaya($dtl->TglBayar).'</td>
                                    <td></td>
                                </tr>';

                            }

                            $html .='
                            <tr>
                                <td></td>
                                <td class="atur_td_detail text-bold text-right" style="border-top:1px solid black;padding-bottom:5px !important;padding-top:5px !important;" >'.UangBiasa($sumTotal_Detail).'</td>
                                <td></td>
                            </tr>';



                        $html .='</table>
                        <br>
                    </td>
                </tr>';

            endif;

            

    endforeach;

$html .='
</table>';

// $html .='
// <table class="tbl_saya">

//     <tr>
//         <th>No</th>
//         <th>Supplier</th>
//         <th>Job No</th>
//         <th>Proyek</th>
//         <th>No KO</th>
//         <th>No Invoice</th>
//         <th>Tgl Invoice</th>
//         <th>Nilai Invoice (Rp)</th>
//         <th>Paraf</th>
//         <th>No. Faktur Pajak</th>
//         <th>Tgl Faktur</th>
//         <th>Keterangan</th>
//     </tr>';

//     $nomor_urut = 1;
//     $sumTotal_Detail = 0;

//     foreach ($dataInv as $inv):

//         $html.='
//         <tr class="huruf_set">
//             <td style="width: 2%;text-align: center;">'.$nomor_urut++.'</td>
//             <td>'.$inv[VendorNm].'</td>
//             <td>'.$inv[JobNo].'</td>
//             <td>'.$inv[JobNm].'</td>
//             <td>'.$inv[NoKO].'</td>
//             <td>'.$inv[InvNo].'</td>
//             <td>'.TanggalSaya($inv[InvDate]).'</td>
//             <td align="right">'.UangBiasa($inv[Total]).'</td>
//             <td></td>
//             <td>'.$inv[FPNo].'</td>
//             <td>'.TanggalSaya($inv[FPDate]).'</td>
//             <td>'.$inv[Keterangan].'</td>
//         </tr>';

//     endforeach;


// $html .='
// </table>';

$mpdf->WriteHTML($html);
$mpdf->Output('Rkp-Inv-Supplier-' . date('ymdHis') . '.pdf', 'D');