<?php
// error_reporting(E_ALL);
$mpdfConfig = array(
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_header' => 0,
    'margin_header' => 5,
    'margin_footer' => 5,
    'orientation' => 'L',
    'margin_left' => 3,
    'margin_right' => 3,
    'setAutoBottomMargin' => 'stretch',
    'setAutoTopMargin' => 'stretch',

);


$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$stylesheet = file_get_contents('css/bootstrap-3.3.7.min.css');
$stylesheet_baru = file_get_contents('css/css_daftar_harga.css');

$mpdf->SetTitle('REKAP DAFTAR HARGA || '.$this->Company);
$mpdf->use_kwt = true;

$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_baru, \Mpdf\HTMLParserMode::HEADER_CSS);


$mpdf->SetHTMLHeader($this->load->view('report_view/procurement/daftar_harga/pdf_atas', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('report_view/procurement/daftar_harga/pdf_bawah', NULL, TRUE));

$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;
$html ='';

$html .='
<table width="100%" border="0" id="table_QHutangKO">
	
	<thead>
		<tr>
			<th style="text-align:center;width:2%;">No</th>
			<th style="text-align:left;width:35%;">Uraian</th>
			<th style="text-align:left;width:5%;padding-left:10px;padding-right:10px;">U.O.M</th>
			<th style="text-align:right;width:5%;padding-left:10px;padding-right:10px;" >Vol</th>
			<th style="text-align:right;width:7%;padding-left:10px;padding-right:10px;" >Harga Satuan (Rp)</th>
			<th style="text-align:right;width:7%;padding-left:10px;padding-right:10px;" >Total Harga (Rp)</th>
			<th style="text-align:left;width:13%;padding-left:10px;padding-right:10px;">Vendor</th>
			<th style="text-align:left;width:7%;padding-left:10px;padding-right:10px;">No KO</th>
			<th style="text-align:left;width:7%;padding-left:10px;padding-right:10px;">Tgl KO</th>
			<th style="text-align:left;">Job Name</th> 
		</tr>
	</thead>
	<tbody>';

	$nomor_urut = 1;
	foreach($dataHrg as $dftHrg) :

		$html.='
		<tr>
			<td class="text-center">'.$nomor_urut.'</td>
			<td style="text-align:left;">'.$dftHrg->Uraian.'</td>
			<td style="text-align:left;width:5%;padding-left:10px;padding-right:10px;">'.$dftHrg->Uom.'</td>
			<td style="text-align:right;width:5%;padding-left:10px;padding-right:10px;">'.$dftHrg->Vol.'</td>
			<td style="text-align:right;width:7%;padding-left:10px;padding-right:10px;">'.UangBiasa($dftHrg->HrgSatuan).'</td>
			<td style="text-align:right;width:7%;padding-left:10px;padding-right:10px;">'.UangBiasa($dftHrg->TotalHarga).'</td>
			<td style="text-align:left;width:13%;padding-left:10px;padding-right:10px;">'.$dftHrg->VendorNm.'</td>
			<td style="text-align:left;width:7%;padding-left:10px;padding-right:10px;">'.$dftHrg->NoKO.'</td>
			<td style="text-align:left;width:7%;padding-left:10px;padding-right:10px;">'.tgl_baru_tahun_pendek($dftHrg->TglKO).'</td>
			<td style="text-align:left;">'.$dftHrg->JobNm.'</td>
		</tr>';

		$nomor_urut++;
	endforeach;

$html .='
	</tbody>
</table>';

// exit;
$mpdf->WriteHTML($html);

// $mpdf->Output('Rekap-Daftar-Harga-' . date('ymdHis') . '.pdf', 'I');
$mpdf->Output('Rekap-Daftar-Harga-' . date('ymdHis') . '.pdf', 'D');