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

$mpdf->SetTitle('Rekap Hutang KO');
$mpdf->use_kwt = true;

$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_baru, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('laporan_view/procurement/pdf_atas_HutangKO', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('laporan_view/procurement/pdf_bawah_HutangKO', NULL, TRUE));

$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;

$html = '';

$html .='

<table width="100%" border="0" style="border-collapse:collapse;font-size:10px;" id="table_saya">
    <thead>
        <tr>

            <th style="text-align:center;" colspan="5"></th>
                    
            <th style="text-align:center;color:black;border-bottom:1px solid;" colspan="2">RAP</th>
            <th width="2px"></th>

            
            <th style="text-align:center;color:black;border-bottom:1px solid;" colspan="2">Total KO dlm Periode</th>
            <th width="2px"></th>
            
            <th colspan="2" style="text-align:center;color:black;border-bottom:1px solid;">Total KO</th>
            <th width="2px"></th>
            
            <th colspan="2" style="text-align:center;color:black;border-bottom:1px solid;">Total Pembayaran KO dlm periode ini</th>
            <th width="2px"></th>
            
            <th colspan="2" style="text-align:center;color:black;border-bottom:1px solid;">Total Pembayaran KO</th>
            <th width="2px"></th>
            
            <th colspan="2" style="text-align:center;color:black;border-bottom:1px solid;">Saldo KO</th>
            <th width="2px"></th>
            <th style="text-align:center;"></th>

        </tr>
        <tr>

            <th class="text-center" style="color:black;" >No</th>
            <th class="text-left" style="color:black;">Job No</th>
            <th class="text-left" style="color:black;width:100px;">Job Name</th>
            <th class="text-center" style="color:black;">Tanggal PHO</th>

            <th class="text-right"  style="width:7%;color:black;border-bottom:1px solid;">Netto Porsi Kontrak (Rp)</th>

            <th class="text-right" style="color:black;border-bottom:1px solid;">Rp</th>
            <th class="text-right" style="color:black;border-bottom:1px solid;">% thd Netto Kontrak</th>
            <th></th>

            <th class="text-right" style="color:black;border-bottom:1px solid;">Rp</th>
            <th class="text-right" style="color:black;border-bottom:1px solid;">% thd RAP</th>
            <th></th>

            <th class="text-right" style="color:black;border-bottom:1px solid;">Rp</th>
            <th class="text-right" style="color:black;border-bottom:1px solid;">% thd RAP</th>
            <th></th>
            <th class="text-right" style="color:black;border-bottom:1px solid;">Rp</th>
            <th class="text-right" style="color:black;border-bottom:1px solid;">% thd RAP</th>
            <th></th>
            <th class="text-right" style="color:black;border-bottom:1px solid;">Rp</th>
            <th class="text-right" style="color:black;border-bottom:1px solid;">% thd RAP</th>
            <th></th>
            <th class="text-right" style="color:black;border-bottom:1px solid;">Rp</th>
            <th class="text-right" style="color:black;border-bottom:1px solid;">% thd RAP</th>
            <th></th>
            <th class="text-right" style="color:black;border-bottom:1px solid;">Total Hutang Invoice (Rp)</th>

        </tr>
    </thead>

    <tbody>';


        $nomor = 1;
        $SubTotal = 0;

        $SubTotal_RAP = 0;
        $SubTotal_RAP_Persen = 0;

        $SubTotal_KO2 = 0;
        $SubTotal_KO2_Persen = 0;

        $SubTotal_KO1 = 0;
        $SubTotal_KO1_Persen = 0;

        $SubTotal_BLE1 = 0;
        $SubTotal_BLE1_Persen = 0;

        $SubTotal_BLE2 = 0;
        $SubTotal_BLE2_Persen = 0;

        $SubTotal_SaldoKO = 0;
        $SubTotal_SaldoKO_Persen = 0;

        $SubTotal_Invoice2 = 0;


        $GrandTotal = 0;
        $GrandTotal_RAP = 0;
        $GrandTotal_KO2 = 0;
        $GrandTotal_KO1 = 0;
        $GrandTotal_BLE1 = 0;
        $GrandTotal_BLE2 = 0;
        $GrandTotal_Invoice2 = 0;
        $GrandTotal_SaldoKO = 0;

        foreach($dataHutangKO as $dataHutangKO){
            
            $SubTotal += $dataHutangKO['NettKontrak'];
            

            $SubTotal_RAP += $dataHutangKO['RAP'];
            $SubTotal_RAP_Persen += $dataHutangKO['RAP_Persen'];
            

            $SubTotal_KO1 += $dataHutangKO['KO1'];
            $SubTotal_KO1_Persen += $dataHutangKO['KO1_Persen'];

            $SubTotal_KO2 += $dataHutangKO['KO2'];
            $SubTotal_KO2_Persen += $dataHutangKO['KO2_Persen'];
            
            
            $SubTotal_BLE1 += $dataHutangKO['BLE1'];
            $SubTotal_BLE1_Persen += $dataHutangKO['BLE1_Persen'];

            $SubTotal_BLE2 += $dataHutangKO['BLE2'];
            $SubTotal_BLE2_Persen += $dataHutangKO['BLE2_Persen'];

            $SubTotal_SaldoKO += $dataHutangKO['SaldoKO'];
            $SubTotal_SaldoKO_Persen += $dataHutangKO['SaldoKO_Persen'];

            $SubTotal_Invoice2 += $dataHutangKO['Invoice2'];


            $GrandTotal += $SubTotal;
            $GrandTotal_RAP += $SubTotal_RAP;
            $GrandTotal_KO1 += $SubTotal_KO1;
            $GrandTotal_KO2 += $SubTotal_KO2;
            $GrandTotal_BLE1 += $SubTotal_BLE1;
            $GrandTotal_BLE2 += $SubTotal_BLE2;
            $GrandTotal_SaldoKO += $SubTotal_SaldoKO;
            $GrandTotal_Invoice2 += $SubTotal_Invoice2;

                            

            $html .='
            <tr>
                <td style="color:black;font-weight: bold;padding-top:10px;padding-bottom:5px;" colspan="24">TAHUN '.date('Y',strtotime($dataHutangKO['TglPHO'])).'</td>
            </tr>
            <tr >
                <td class="text-center" style="padding-left:5px;padding-right:5px;padding-top:10px;">'.$nomor++.'</td>
                <td class="text-left" style="padding-left:5px;padding-right:5px;">'.$dataHutangKO['JobNo'].'</td>
                <td class="text-left" style="padding-left:5px;padding-right:5px;">'.$dataHutangKO['JobNm'].'</td>
                <td class="text-center" style="padding-left:5px;padding-right:5px;">'.$dataHutangKO['TglPHO'].'</td>
                <td class="text-right" style="padding-left:5px;padding-right:5px;border-bottom:1px solid;">'.number_format($dataHutangKO['NettKontrak']).'</td>
                <td class="text-right" style="padding-left:5px;padding-right:5px;border-bottom:1px solid;">'.number_format($dataHutangKO['RAP']).'</td>
                <td class="text-right" style="border-bottom:1px solid;">'.uang_koma_genap_decimal_kedua($dataHutangKO['RAP_Persen']).'%'.'</td>
                <td></td>
                <td class="text-right" style="border-bottom:1px solid;">'.number_format($dataHutangKO['KO2']).'</td>
                <td class="text-right" style="border-bottom:1px solid;">'.uang_koma_genap_decimal_kedua($dataHutangKO['KO2_Persen']).'%'.'</td>
                <td></td>

                <td class="text-right" style="border-bottom:1px solid;">'.number_format($dataHutangKO['KO1']).'</td>
                <td class="text-right" style="border-bottom:1px solid;">'.uang_koma_genap_decimal_kedua($dataHutangKO['KO1_Persen']).'%'.'</td>
                <td></td>

                <td class="text-right" style="border-bottom:1px solid;">'.number_format($dataHutangKO['BLE2']).'</td>
                <td class="text-right" style="border-bottom:1px solid;">'.uang_koma_genap_decimal_kedua($dataHutangKO['BLE2_Persen']).'%'.'</td>
                <td></td>

                <td class="text-right" style="border-bottom:1px solid;">'.number_format($dataHutangKO['BLE1']).'</td>
                <td class="text-right" style="border-bottom:1px solid;">'.uang_koma_genap_decimal_kedua($dataHutangKO['BLE1_Persen']).'%'.'</td>
                <td></td>

                <td class="text-right" style="border-bottom:1px solid;">'.number_format($dataHutangKO['SaldoKO']).'</td>
                <td class="text-right" style="border-bottom:1px solid;">'.uang_koma_genap_decimal_kedua($dataHutangKO['SaldoKO_Persen']).'%'.'</td>
                <td></td>

                <td class="text-right" style="border-bottom:1px solid;">'.number_format($dataHutangKO['Invoice2']).'</td>

            </tr>';

    
        }
        $html .='</tbody>';


    $html .='<tfoot>';


    $html .='<tr style="padding-top:10px;">
                <td colspan="4" style="text-align: right;font-weight: bold;padding-top:5px;padding-bottom:5px;">SUB TOTAL</td>
                <td style="text-align: right;text-decoration: underline;font-weight: bold;border-bottom:1px solid;">'.number_format($SubTotal).'</td>

                <td style="text-align: right;text-decoration: underline;font-weight: bold;border-bottom:1px solid;">'.number_format($SubTotal_RAP).'</td>
                <td></td>
                <td></td>

                <td style="text-align: right;text-decoration: underline;font-weight: bold;border-bottom:1px solid;">'.number_format($SubTotal_KO2).'</td>
                <td></td>
                <td></td>

                <td style="text-align: right;text-decoration: underline;font-weight: bold;border-bottom:1px solid;">'.number_format($SubTotal_KO1).'</td>
                <td></td>
                <td></td>

                <td style="text-align: right;text-decoration: underline;font-weight: bold;border-bottom:1px solid;">'.number_format($SubTotal_BLE2).'</td>
                <td></td>
                <td></td>

                <td style="text-align: right;text-decoration: underline;font-weight: bold;border-bottom:1px solid;">'.number_format($SubTotal_BLE1).'</td>
                <td></td>
                <td></td>

                <td style="text-align: right;text-decoration: underline;font-weight: bold;border-bottom:1px solid;">'.number_format($SubTotal_SaldoKO).'</td>
                <td></td>
                <td></td>

                <td style="text-align: right;text-decoration: underline;font-weight: bold;border-bottom:1px solid;">'.number_format($SubTotal_Invoice2).'</td>
                

            </tr>
            <tr>
                <th style="padding-top: 10px;" colspan="4" class="text-right">GRAND TOTAL</th>
                <th class="text-right" style="border-bottom:1px solid;">'.number_format($GrandTotal).'</th>

                <th class="text-right" style="border-bottom:1px solid;">'.number_format($GrandTotal_RAP).'</th>
                <th class="text-right"></th>
                <th></th>

                <th class="text-right" style="border-bottom:1px solid;">'.number_format($GrandTotal_KO2).'</th>
                <th class="text-right"></th>
                <th></th>

                <th class="text-right" style="border-bottom:1px solid;">'.number_format($GrandTotal_KO1).'</th>
                <th class="text-right"></th>
                <th></th>

                <th class="text-right" style="border-bottom:1px solid;">'.number_format($GrandTotal_BLE2).'</th>
                <th class="text-right"></th>
                <th></th>

                <th class="text-right" style="border-bottom:1px solid;">'.number_format($GrandTotal_BLE1).'</th>
                <th class="text-right"></th>
                <th></th>

                <th class="text-right" style="border-bottom:1px solid;">'.number_format($GrandTotal_SaldoKO).'</th>
                <th class="text-right"></th>
                <th></th>

                <th class="text-right" style="border-bottom:1px solid;">'.number_format($GrandTotal_Invoice2).'</th>
            </tr>

            ';



$html .='</tfoot>';



$html .='</table>';

$mpdf->WriteHTML($html);

// $mpdf->Output('REKAP-HUTANGKO - ' . date('ymdHis') . '.pdf', 'I');
$mpdf->Output('REKAP-HUTANGKO - ' . date('ymdHis') . '.pdf', 'I');