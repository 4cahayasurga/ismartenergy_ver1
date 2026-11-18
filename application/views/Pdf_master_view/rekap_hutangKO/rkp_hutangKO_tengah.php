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
$stylesheet = file_get_contents('css/bootstrap-3.3.7.min.css');
$stylesheet_baru = file_get_contents('mycss/css_RekapHutangKO.css');

$mpdf->SetTitle('REKAP HUTANG KO');
$mpdf->use_kwt = true;

$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_baru, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('Pdf_master_view/rekap_hutangKO/rkp_hutangKO_atas', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('Pdf_master_view/rekap_hutangKO/rkp_hutangKO_footer', NULL, TRUE));

$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;

$html = '';

$html .='

<table width="100%" border="1" style="border-collapse:collapse;font-size:10px;">
    <thead>
        <tr>
            <th style="text-align:center;" colspan="5"></th>
                    
            <th style="text-align:center;color:black;" colspan="2">RAP</th>

            
            <th style="text-align:center;color:black;" colspan="2">Total KO dlm Periode</th>

            
            <th colspan="2" style="text-align:center;color:black;">Total KO</th>

            
            <th colspan="2" style="text-align:center;color:black;">Total Pembayaran KO dlm periode ini</th>

            
            <th colspan="2" style="text-align:center;color:black;">Total Pembayaran KO</th>

            
            <th colspan="2" style="text-align:center;color:black;">Total Pembayaran KO</th>

            <th style="text-align:center;"></th>

        </tr>
    </thead>

    <tbody>

        <tr>

            <th class="text-center" style="color:black;" >No</th>
            <th class="text-left" style="color:black;">Job No</th>
            <th class="text-left" style="color:black;">Job Name</th>
            <th class="text-center" style="color:black;">Tanggal PHO</th>
            <th class="text-right"  style="width:7%;color:black;">Netto Porsi Kontrak (Rp)</th>

            <th class="text-right" style="color:black;">Rp</th>
            <th class="text-right" style="color:black;">% thd Netto Kontrak</th>

            <th class="text-right" style="color:black;">Rp</th>
            <th class="text-right" style="color:black;">% thd RAP</th>

            <th class="text-right" style="color:black;">Rp</th>
            <th class="text-right" style="color:black;">% thd RAP</th>

            <th class="text-right" style="color:black;">Rp</th>
            <th class="text-right" style="color:black;">% thd RAP</th>

            <th class="text-right" style="color:black;">Rp</th>
            <th class="text-right" style="color:black;">% thd RAP</th>

            <th class="text-right" style="color:black;">Rp</th>
            <th class="text-right" style="color:black;">% thd RAP</th>

            <th class="text-center" style="color:black;">Total Hutang Invoice (Rp)</th>

        </tr>
    </tbody>';
        // for($i = 0; $i < 100; $i++) {

$nomor=1;
$JobNo = NULL;
$JobNm = NULL;
$TglPHO = NULL;
$NettKontrak = 0;
$RAP = 0;
$NoKO = NULL;
$KO1 = 0;
$KO2 = 0;

$DrTgl = NULL;
$SpTgl = NULL;


$BLE1 = 0;
$BLE2 = 0;

$Invoice1 = 0;
$TtlInv = 0;


$RAP_persen=0;
$KO2_persen=0;
$KO1_persen=0;
$BLE1_persen = 0;
$BLE2_persen = 0;


$SaldoKO = 0;
$SaldoKO_persen = 0;

foreach($data as $data_Dari_Job) :

    $JobNo = $data_Dari_Job['JobNo'];
    $JobNm = $data_Dari_Job['JobNm'];
    $TglPHO = $data_Dari_Job['TglPHO'];
    $NettKontrak = $data_Dari_Job['NettKontrak'];
    $RAP = $data_Dari_Job['RAP'];

    $DrTgl = $data_Dari_Job['DrTgl'];
    $SpTgl = $data_Dari_Job['SpTgl'];


    //tentang KO
    $query_KO = $this->db->query("
        SELECT KoHdr.*, Vendor.VendorNm 
        FROM KoHdr, Vendor 
        WHERE JobNo='$JobNo' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL AND KoHdr.VendorId=Vendor.VendorId")->result_array();

    foreach($query_KO as $dataKO) : 

        $NoKO = $dataKO['NoKO'];


        if ($dataKO['ClosedBy'] == NULL  || $dataKO['ClosedBy'] == '' ) {
            $KO1 += $dataKO['SubTotal'] + $dataKO['PPN'] - $dataKO['DiscAmount'];
        }else{
            $cek = $this->db->query("SELECT ISNULL(SUM(BLE.Amount),0) FROM BLE, KoHdr WHERE BLE.NoKO=KoHdr.NoKO AND KoHdr.NoKO='$NoKO'  ")->row(0);
            $KO1 += $cek;
        }

                                //total ko periode
        if ($dataKO['TglKO'] >= $DrTgl && $dataKO['TglKO'] <= $SpTgl  ) {

            if ($dataKO['ClosedBy'] == NULL  || $dataKO['ClosedBy'] == '' ) {
                $KO2 += $dataKO['SubTotal'] + $dataKO['PPN'] - $dataKO['DiscAmount'];
            }else{

                $cekKO2 = $this->db->query("SELECT ISNULL(SUM(BLE.Amount),0) FROM BLE, KoHdr WHERE BLE.NoKO=KoHdr.NoKO AND KoHdr.NoKO='$NoKO'  ");
                if ($cekKO2->num_rows() > 0) {
                    $dataKO2 = $cekKO2->row(0);
                    $KO2 += $dataKO2;
                }

            }

        }

    endforeach;


                            //tentang pemnbayaran ko
    $queryPembayaraKO = $this->db->query("
        SELECT BLE.Amount, BLE.TglBayar FROM BLE, KoHdr WHERE BLE.JobNo='$JobNo' And BLE.NoKO=KoHdr.NoKO AND 
        KoHdr.ApprovedBy Is Not NULL And KoHdr.CanceledBy Is NULL ");

    foreach($queryPembayaraKO->result_array() as $dataPembayaranKO ):
        $BLE1 += $dataPembayaranKO['Amount'];
        if ($dataPembayaranKO['TglBayar'] >= $DrTgl && $dataPembayaranKO['TglBayar'] <= $SpTgl ) {
            $BLE2 += $dataPembayaranKO['Amount'];
        }
    endforeach;


                            //tentang invoice
    $query_Invoice = $this->db->query("
        SELECT ISNULL(SUM(Invoice.Total),0) AS 'Invoice1' FROM Invoice, KoHdr
        WHERE Invoice.JobNo='$JobNo' AND Invoice.NoKO=KoHdr.NoKO AND KoHdr.ApprovedBy IS NOT NULL AND KoHdr.CanceledBy IS NULL
        AND KoHdr.ClosedBy IS NULL AND Invoice.InvDate>='$DrTgl' AND Invoice.InvDate<='$SpTgl' ");
    if ($query_Invoice->num_rows() > 0 ) {
        $Invoice1 = $query_Invoice->row_array()['Invoice1'];
    }



                            // tentang total invoice
    $DapatTotalInvoice = $this->db->query(" 
        SELECT ISNULL(SUM(Invoice.Total),0) AS 'TtlInv' FROM Invoice, KoHdr 
        WHERE Invoice.JobNo='$JobNo'
        AND Invoice.NoKO=KoHdr.NoKO AND KoHdr.ApprovedBy IS NOT NULL AND KoHdr.CanceledBy IS NULL
        AND KoHdr.ClosedBy IS NULL

        ");
    if ($DapatTotalInvoice->num_rows() > 0 ) {
        $TtlInv = $DapatTotalInvoice->row_array()['TtlInv'];
    }

                            //tentang invoice2
    $Dapat_Invoice2 = $this->db->query("
        SELECT ISNULL(SUM(InvPD.PaymentAmount),0) AS 'Invoice2' FROM BLE, InvPD, KoHdr WHERE
        InvPD.JobNo='$JobNo' AND BLE.NoPD=InvPD.NoPD AND InvPD.NoKO=KoHdr.NoKO AND
        KoHdr.ClosedBy IS NULL AND KoHdr.CanceledBy IS NULL AND KoHdr.ApprovedBy IS NOT NULL

        ");

    if ($Dapat_Invoice2->num_rows() > 0 ) {
        $myrow =  $Dapat_Invoice2->row_array();
        $rowInv2 = $myrow['Invoice2'];
        $Invoice2 =  $TtlInv - $rowInv2;
    }

    $SaldoKO = $KO1 - $BLE1;
    $SaldoKO_persen = ($SaldoKO / $data_Dari_Job['RAP']) * 100;
    $SaldoKO_persen = new_decimal($SaldoKO_persen).'%';

    $SaldoKO = $SaldoKO / 1000;
    $SaldoKO = round($SaldoKO);
    $SaldoKO = new_decimal($SaldoKO);





                            // echo $KO1."<br>";
                            // echo $Invoice2."<br>";
                            // echo $TtlInv."<br>";

                            // exit;



    $RAP_persen = ($data_Dari_Job['RAP']/ $data_Dari_Job['NettKontrak']) * 100;
    $KO2_persen = ($KO2 / $data_Dari_Job['RAP']) * 100;
    $KO1_persen = ($KO1 / $data_Dari_Job['RAP']) * 100;

    $BLE1_persen = ($BLE1 / $data_Dari_Job['RAP']) * 100;
    $BLE2_persen = ($BLE2 / $data_Dari_Job['RAP']) * 100;



    $NettKontrak = $data_Dari_Job['NettKontrak'] / 1000;
    $NettKontrak = round($NettKontrak);
    $NettKontrak = new_decimal($NettKontrak);
                            // $SubTotalNettKontrak = $NettKontrak;

    $RAP = $data_Dari_Job['RAP'] / 1000;
    $RAP = round($RAP);
    $RAP = new_decimal($RAP);
    $RAP_persen = new_decimal($RAP_persen).'%';


    $KO1 = $KO1 / 1000;
    $KO1 = round($KO1);
    $KO1 = new_decimal($KO1);
    $KO1_persen =  new_decimal($KO1_persen).'%';


    $KO2 = $KO2 / 1000;
    $KO2 = round($KO2);
    $KO2 = new_decimal($KO2);
    $KO2_persen =  new_decimal($KO2_persen).'%';


    $BLE1 = $BLE1 / 1000;
    $BLE1 = round($BLE1);
    $BLE1 = new_decimal($BLE1);
    $BLE1_persen =  new_decimal($BLE1_persen).'%';

    $BLE2 = $BLE2 / 1000;
    $BLE2 = round($BLE2);
    $BLE2 = new_decimal($BLE2);
    $BLE2_persen =  new_decimal($BLE2_persen).'%';


    $Invoice1 = $Invoice1 / 1000;
    $Invoice1 = round($Invoice1);
    $Invoice1 = new_decimal($Invoice1);

    $Invoice2 = $Invoice2 / 1000;
    $Invoice2 = round($Invoice2);
    $Invoice2 = new_decimal($Invoice2);


    $TtlInv = $TtlInv / 1000;
    $TtlInv = round($TtlInv);
    $TtlInv = new_decimal($TtlInv);



    $html .='<tfoot>';

    $html .='
    <tr>
        <td style="color:black;font-weight: bold;padding-top:10px;padding-bottom:5px;" colspan="18">TAHUN '.date('Y',strtotime($data_Dari_Job['TglKontrak'])).'</td>
    </tr>
    <tr >
        <td class="text-center" style="padding-left:5px;padding-right:5px;">'.$nomor++.'</td>
        <td class="text-left" style="padding-left:5px;padding-right:5px;">'.$JobNo.'</td>
        <td class="text-left" style="padding-left:5px;padding-right:5px;">'.$JobNm.'</td>
        <td class="text-center" style="padding-left:5px;padding-right:5px;">'.tgl_baru_tahun_pendek($TglPHO).'</td>
        <td class="text-right" style="padding-left:5px;padding-right:5px;">'.$NettKontrak.'</td>
        <td class="text-right" style="padding-left:5px;padding-right:5px;">'.$RAP.'</td>
        <td class="text-right">'.$RAP_persen.'</td>
        <td class="text-right">'.$KO2.'</td>
        <td class="text-right">'.$KO2_persen.'</td>
        <td class="text-right">'.$KO1.'</td>
        <td class="text-right">'.$KO1_persen.'</td>
        <td class="text-right">'.$BLE2.'</td>
        <td class="text-right">'.$BLE2_persen.'</td>
        <td class="text-right">'.$BLE1.'</td>
        <td class="text-right">'.$BLE1_persen.'</td>
        <td class="text-right">'.$SaldoKO.'</td>
        <td class="text-right">'.$SaldoKO_persen.'</td>
        <td class="text-center">'.$Invoice2.'</td>
    </tr>';

endforeach;

    $html .='<tr style="padding-top:10px;">
                <td colspan="4" style="text-align: right;font-weight: bold;padding-top:5px;padding-bottom:5px;">SUB TOTAL</td>
                <td style="text-align: right;text-decoration: underline;font-weight: bold;">'.$NettKontrak.'</td>

                <td style="text-align: right;text-decoration: underline;font-weight: bold;">'.$RAP.'</td>
                <td></td>

                <td style="text-align: right;text-decoration: underline;font-weight: bold;">'.$KO2.'</td>
                <td></td>

                <td style="text-align: right;text-decoration: underline;font-weight: bold;">'.$KO1.'</td>
                <td></td>

                <td style="text-align: right;text-decoration: underline;font-weight: bold;">'.$BLE2.'</td>
                <td></td>

                <td style="text-align: right;text-decoration: underline;font-weight: bold;">'.$BLE1.'</td>
                <td></td>

                <td style="text-align: right;text-decoration: underline;font-weight: bold;">'.$KO1.'</td>
                <td></td>

                <td style="text-align: right;text-decoration: underline;font-weight: bold;">'.$Invoice2.'</td>
                

            </tr>';

$html .='</tfoot>';



$html .='</table>';

$mpdf->WriteHTML($html);

// $mpdf->Output('REKAP-HUTANGKO - ' . date('ymdHis') . '.pdf', 'I');
$mpdf->Output('REKAP-HUTANGKO - ' . date('ymdHis') . '.pdf', 'D');