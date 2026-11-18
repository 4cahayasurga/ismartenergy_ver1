<?php
$html = '';

$mpdfConfig = array(
    'mode' => 'utf-8',
    'format' => 'A4',
    // 'margin_header' => 0,
    // 'margin_footer' => 0,
    'orientation' => 'L',
    'setAutoBottomMargin' => 'stretch',
    'setAutoTopMargin' => 'stretch',

);
$stylesheet = file_get_contents('css/bootstrap-3.3.7.min.css');
$stylesheet_new = file_get_contents('css_baru.css');


$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle('PRATENDER || PD');
$mpdf->use_kwt = true;


$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('PD_lap/myheader_print_pd_keu', NULL, TRUE), 'O');

$mpdf->SetHTMLFooter($this->load->view('PD_lap/myfooter_print_pd_keu', NULL, TRUE));
$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;
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
$SubTotal = 0;
foreach ($detail_pd as $detail) :
    $SubTotal += $detail['JumlahHarga'];
    $html .= '
                    <tr>
                        <td style="vertical-align: top;">' . $no++ . '</td>
                        <td style="vertical-align: top;">' . $detail['KdRAP'] . '</td>
                        <td style="vertical-align: top;">' . $detail['Uraian'] . '</td>
                        <td style="vertical-align: top;">' . PecahAngka($detail['Vol']) . '</td>
                        <td style="vertical-align: top;">' . $detail['Uom'] . '</td>
                        <td style="vertical-align: top;">' . UbahGabunganUang($detail['HrgSatuan']) . '</td>
                        <td style="vertical-align: top;" style="border-right:5px solid #95a5a6;">' . UbahGabunganUang($detail['JumlahHarga']) . '</td>
                    </tr>
                ';
endforeach;
$HitungTotalPermintaan =  $SubTotal - $SaldoSebelumnya;

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
                        <tbody>
                            <tr>
                                <th colspan="7" class="text-center"></th>
                            </tr>
                        </tbody>
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
                            <th colspan="2" style="text-align:right;">' . UbahGabunganUang($SubTotal) . '</th>
                        </tr>
            
                        <tr>
                            <th colspan="2" style="text-align:right;padding-top: 10px;padding-bottom: 3px;">Saldo Sebelumnya dari No.</th>
                            <td width="80px" style="border-bottom:1px solid #2c3e50;padding-top: 10px;">' . $NoPD . '</td>
                            <th colspan="2" style="text-align:right;padding-top: 10px;">' . UbahGabunganUang($SaldoSebelumnya) . '</th>
                        </tr>
                        <tr>
                            <th colspan="3" style="text-align:right;padding-right: 50px;padding-top: 10px;">Total Permintaan : </th>
                            <th colspan="2" width="80px" style="text-align:right;border-bottom:1px solid #2c3e50;padding-top: 10px;">' . UbahGabunganUang($HitungTotalPermintaan) . '</th>
                        </tr>
            
                    </table>
            
                </div>
            
            
                <div class="col-xs-6 row">
                    <table style="width:100%;border-collapse: collapse;" border="0">
                        <tr>
                            <th style="vertical-align: top;text-align: center;">Total Pertanggungjawaban : <br>Dana yang tersedia di lapangan : <br>Saldo : </th>
                        </tr>
                    </table>
                </div>
            
            </div>
            
            ';

// Write some HTML code:
// $mpdf->WriteHTML('Hello Worldssss');

// Output a PDF file directly to the browser
$mpdf->WriteHTML($html);

// exit;
$Company = $this->config->item('Company');
$mpdf->Output(''.$Company.'-PD_lapangan-' . date('ymdHis') . '.pdf', 'I');
