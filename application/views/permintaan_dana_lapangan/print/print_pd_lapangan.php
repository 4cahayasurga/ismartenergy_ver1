
<?php

$mpdfConfig = array(
    'mode' => 'utf-8',
    'format' => 'A4',
    // 'margin_header' => 0,
    // 'margin-left' => '1cm',
    // 'margin_footer' => 0,
    'orientation' => 'L',
    'setAutoBottomMargin' => 'stretch',
    'setAutoTopMargin' => 'stretch',

);
$stylesheet = file_get_contents('css/bootstrap-3.3.7.min.css');
$stylesheet_new = file_get_contents('css_baru.css');


$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle('PDF Permintaan Dana || '.$this->config->item('Company'));
$mpdf->use_kwt = true;



$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('permintaan_dana_lapangan/print/myheader_print_pd_lapangan', NULL, TRUE), 'O');

$mpdf->SetHTMLFooter($this->load->view('permintaan_dana_lapangan/print/myfooter_print_pd_lapangan', NULL, TRUE));

$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;
$html = '';


$html .= '
<div class="row">

    <div class="col-xs-6 row">
        <table width="100%" class="table table-borderless table-striped table-hover table-condensed row" style="page-break-inside: avoid !important">
            <thead>
                <tr id="myTr">
                    <th width="10px" >No.</th>
                    <th width="20px">Kode</th>
                    <th>Uraian</th>
                    <th>Vol</th>
                    <th>Sat</th>
                    <th>Harga (Rp)</th>
                    <th style="border-right:5px solid #95a5a6;">Jumlah (Rp)</th>

                </tr>
            </thead>
            <tbody>';

$no = 1;
foreach ($detailPD as $detail) :
    $html .= '
        <tr>
            <td style="vertical-align: top;">' . $no++ . '</td>
            <td style="vertical-align: top;">' . $detail['KdRAP'] . '</td>
            <td style="vertical-align: top;">' . $detail['Uraian'] . '</td>
            <td style="vertical-align: top;">' . new_decimal($detail['Vol']) . '</td>
            <td style="vertical-align: top;">' . $detail['Uom'] . '</td>
            <td style="vertical-align: top;">' . UbahGabunganUang($detail['HrgSatuan']) . '</td>
            <td style="vertical-align: top;" style="border-right:5px solid #95a5a6;">' . UbahGabunganUang($detail['JumlahHarga']) . '</td>
        </tr>
    ';
endforeach;



$html .= '
            </tbody>
        </table>
    </div>

    <div class="col-xs-6 row" >
        <table width="100%" class="table table-borderless table-striped table-hover table-condensed row" style="page-break-inside: avoid !important">
            <thead>
                <tr id="myTr" >
                    <th width="10px">No.</th>
                    <th width="20px">Kode</th>
                    <th>Uraian</th>
                    <th>Vol</th>
                    <th>Sat</th>
                    <th>Harga (Rp)</th>
                    <th>Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>';

                if(count($array_PJ) < 1 ){
                    $html.='
                    <tr>
                        <th colspan="7" class="text-center"></th>
                    </tr>';
                }else{

                    foreach($array_PJ as $dataPJ){
                        $html.='
                            <tr>
                            <td style="vertical-align: top;">' . $dataPJ['NoUrut']. '</td>
                            <td style="vertical-align: top;">' . $dataPJ['KdRAP'] . '</td>
                            <td style="vertical-align: top;">' . $dataPJ['PjUraian'] . '</td>
                            <td style="vertical-align: top;">' . new_decimal($dataPJ['PjVol']) . '</td>
                            <td style="vertical-align: top;">' . $dataPJ['Uom'] . '</td>
                            <td style="vertical-align: top;">' . UbahGabunganUang($dataPJ['PjHrgSatuan']) . '</td>
                            <td style="vertical-align: top;" style="border-right:5px solid #95a5a6;">' . UbahGabunganUang($dataPJ['PJJumlahHarga']) . '</td>
                        </tr>';

                    }
                    
                }
                $atur_tampil_SubTotalPJ = ($NoPJ_cek == 0) ? '<th colspan="2" style="text-align:right;" ></th>' : '<th colspan="2" style="text-align:right;" >' . UbahGabunganUang_new($SubTotal_PJ) . '</th>';

                // $atur_tampil_DanaTersediaDiLapangan_PJ = ($NoPJ_cek == 0) ? '<th style="vertical-align: top;text-align: right;padding-top: 10px;" ></th>' : '<th style="vertical-align: top;text-align: right;padding-top: 10px;" >'.UbahGabunganUang_new($DanaTersediaDiLapangan_PJ).'</th>';
                $atur_tampil_DanaTersediaDiLapangan_PJ = ($NoPJ_cek == 0) ? '<th colspan="2" style="text-align:right;" ></th>' : '<th colspan="2" style="text-align:right;" >' . UbahGabunganUang_new($DanaTersediaDiLapangan_PJ) . '</th>';

                $atur_tampil_Saldo_saya_PJ = ($NoPJ_cek == 0) ? '<th colspan="2" style="text-align:right;border-bottom:1px solid;" ></th>' : '<th colspan="2" style="text-align:right;border-bottom:1px solid;" >' . UbahGabunganUang_new($Saldo_saya_PJ) . '</th>';

                

                // $atur_tampil_Saldo_saya_PJ = ($NoPJ_cek == 0) ? '<th style="vertical-align: top;text-align: right;border-bottom:0px solid #2c3e50;padding-top: 10px;" ></th>' : '<th style="vertical-align: top;text-align: right;border-bottom:1px solid #2c3e50;padding-top: 10px;" >'.UbahGabunganUang_new($Saldo_saya_PJ).'</th>';
            $html .='</tbody>
        </table>
    </div>

</div>

<div class="row" >
    <div class="col-xs-6 row">

        <table style="width:100%;border-collapse: collapse;" border="0">
            <tr>
                <th style="width:70px;vertical-align: top;">Keterangan : </th>
                <td style="width:230px;border-bottom:1px solid #2c3e50;">' . $data['Keterangan'] . '</td>
                <th width="60px" style="vertical-align: top;">Sub Total : </th>
                <th colspan="2" style="text-align:right;">' . UbahGabunganUang_new($SubTotal_PD) . '</th>
            </tr>

            <tr>
                <th colspan="2" style="text-align:right;padding-top: 10px;padding-bottom: 3px;">Saldo Sebelumnya dari No.</th>
                <td width="80px" style="border-bottom:1px solid #2c3e50;padding-top: 10px;">'.$NoPJSebelumnya_Saya.'</td>
                <th colspan="2" style="text-align:right;padding-top: 10px;">' . UbahGabunganUang_new($Saldo_Saya) . '</th>
            </tr>
            <tr>
                <th colspan="3" style="text-align:right;padding-right: 50px;padding-top: 10px;">Total Permintaan : </th>
                <th colspan="2" width="80px" style="text-align:right;border-bottom:1px solid #2c3e50;padding-top: 10px;">' . UbahGabunganUang_new($TotalPermintaan) . '</th>
            </tr>

        </table>

    </div>


    <div class="col-xs-6 row">
        <table style="width:100%;border-collapse: collapse;" border="0">
            <tr>
                <th style="vertical-align: top;text-align: right;">Total Pertanggungjawaban : </th>
                '.$atur_tampil_SubTotalPJ.'
            </tr>
            <tr>
                <th style="vertical-align: top;text-align: right;padding-top: 10px;">Dana yang tersedia di lapangan : </th>
                '.$atur_tampil_DanaTersediaDiLapangan_PJ.'
            </tr>
            <tr>
                <th style="vertical-align: top;text-align: right;padding-top: 10px;">Saldo : </th>
                '.$atur_tampil_Saldo_saya_PJ.'
            </tr>
        </table>
    </div>

</div>';

$mpdf->WriteHTML($html);

// exit;
$Company = $this->config->item('Company');
// $mpdf->Output(' '.$Company.' -PD-LAPANGAN-' . date('ymdHis') . '.pdf', 'I');
$mpdf->Output(' '.$Company.' -PD-'.$Dari.'-' . date('ymdHis') . '.pdf', 'D');
