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
$stylesheet = file_get_contents('mycss/tracking_ko_baru.css');




$Company = $this->config->item('Company');
$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle('REPORT (PROCUREMENT) - TRACKING KO || '.$Company.' ');
$mpdf->use_kwt = true;


$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
// $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::FOOTER_CSS);

$mpdf->SetHTMLHeader($this->load->view('report_view/procurement/tracking_ko/print_pdf/pdf_atas_tracking_ko', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('report_view/procurement/tracking_ko/print_pdf/pdf_bawah_tracking_ko', NULL, TRUE),'O');



$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;
$html = '';

// batas
$no = 1;
$html .='
<table id="tbl_tracking" width="100%" border="0" >
    <tr style="border-bottom:1px solid silver;">
        <th id="atur_th" style="width: 30px !important;text-align: center;border-bottom:1px solid black;" >No</th>
        <th style="width:7%;border-bottom:1px solid black;" align="left">No KO</th>
        <th style="width:7%;border-bottom:1px solid black;" align="left">Tanggal</th>
        <th align="left" style="border-bottom:1px solid black;">Vendor</th>
        <th style="width:7%;text-align: right;border-bottom:1px solid black;">Nilai KO (Rp)</th>
    </tr>
';

$nomor_urut = 1;

$NoKO = NULL;
$TglKO = NULL;
$VendorNm = NULL;
$TotalKO = 0;
$myarray = array();
$MyStatus = NULL;
foreach($dataTrackingKO as $mydata) : 

    $NoKO = $mydata['NoKO'];
    $TglKO = $mydata['TglKO'];
    $VendorNm = $mydata['VendorNm'];
    $TotalKO = $mydata['SubTotal'] - $mydata['DiscAmount'] + $mydata['PPN'];

    $html .='
    <tr>
        <td id="atur_td" style="width:30px;text-align: center;">'.$nomor_urut++.'</td>
        <td >'.$NoKO.'</td>
        <td >'.tgl_baru($TglKO).'</td>
        <td >'.$VendorNm.'</td>
        <td style="width:7%;text-align: right;" >'.UangBiasa($TotalKO).'</td>
    </tr>';


    if($Status <> 'All_Status' ){


        $myquery = $this->db->query("SELECT TOP 1 * FROM TraceKO WHERE NoKO='$NoKO' ORDER BY Tanggal DESC");


        if ($myquery->num_rows() > 0) {

            $row_myquery = $myquery->row();

            $nomor_urut_detail = 1;

            $myquery_detail = $this->db->query("SELECT * FROM TraceKO WHERE NoKO='$NoKO' ");

            if ($Status == $myquery_detail->row()->Status) {


              $html .='
              <tr>
                <td></td>
                <td colspan="4">
                    <table border="0" width="80%" style="border:1px solid silver;">
                        <tr style="border-bottom:1px solid silver;">
                            <td style="text-align:center;padding-top: 5px;padding-bottom: 5px;width: 3%;border-bottom:1px solid silver;">No</td>
                            <td style="border-bottom:1px solid silver;">Keterangan</td>
                            <td style="width:7%;border-bottom:1px solid silver;">Tanggal</td>
                             <td style="width:7%;border-bottom:1px solid silver;">Status</td>
                        </tr>';

                      foreach($myquery_detail->result_array() as $mydetail) :

                        if ($mydetail["Status"] == 0) {
                            $MyStatus = "In Process";
                        }else{
                            $MyStatus = "Delivered";
                        }

                        $html .='

                        <tr>
                            <td style="text-align:center;padding-top: 5px;padding-bottom: 5px;">'.$nomor_urut_detail++.'</td>
                            <td>'.$mydetail[Keterangan].'</td>
                            <td>'.tgl_baru($mydetail[Tanggal]).'</td>
                            <td>'.$MyStatus .'</td>
                        </tr>';

                      endforeach;

              $html .='
                    </table>
                </td>
              </tr>';


          }

      }

  }else{
    $myquery = $this->db->query("SELECT * FROM TraceKO WHERE NoKO='$NoKO' ");
    

    if ($myquery->num_rows() > 0) :

        $nomor_urut_detail = 1;
    

        $html .='<tr>
            <td></td>
            <td colspan="4" >
                <table border="0" width="90%" style="border:1px solid silver;">
                    <tr style="border-bottom:1px solid silver;">
                        <td style="text-align:center;padding-top: 5px;padding-bottom: 5px;width: 3%;border-bottom:1px solid silver;">No</td>
                        <td style="border-bottom:1px solid silver;">Keterangan</td>
                        <td style="width:7%;border-bottom:1px solid silver;">Tanggal</td>
                        <td style="width:7%;border-bottom:1px solid silver;">Status</td>
                    </tr>';
                    
                    foreach($myquery->result_array() as $mydetail) :

                        if ($mydetail["Status"] == 0) {
                            $MyStatus = "In Process";
                        }else{
                            $MyStatus = "Delivered";
                        }
                        
                        $html .='
                        <tr>
                            <td style="text-align:center;padding-top: 5px;padding-bottom: 5px;">'.$nomor_urut_detail++.'</td>
                            <td>'.$mydetail['Keterangan'].'</td>
                            <td>'.tgl_baru($mydetail['Tanggal']).'</td>
                            <td>'.$MyStatus .'</td>
                        </tr>';

                    endforeach;
                $html .='</table>

            </td>
        </tr>';

    endif;

} 

endforeach;

$html .='</table>';


$mpdf->WriteHTML($html);

// $mpdf->Output('REPORT (PROCUREMENT) - TRACKING KO - ' . date('ymdHis') . '.pdf', 'I');
$mpdf->Output('REPORT (PROCUREMENT) - TRACKING KO - ' . date('ymdHis') . '.pdf', 'D');
