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
$stylesheet_new = file_get_contents('css/css_penyerapan_rap.css');
// $stylesheet_new = file_get_contents('css/css_dppd.css');


$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle('REPORT - PENYERAPAN RAP || ' . $this->config->item('Company'));
$mpdf->use_kwt = true;


$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('laporan_view/penyerapan_rap/pdf_rap/pdf_rap_atas', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('laporan_view/penyerapan_rap/pdf_rap/pdf_rap_bawah', NULL, TRUE));



$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;
$html = '';

// batas
$no = 1;

$html .= '

	<table border="0" style="border-collapse:collapse;" width="100%"  class="atur_huruf">
		<thead>
			<tr >
				<th style="border-bottom:1px solid black;padding-top:10px;padding-bottom:10px;width:5%;color:red !important;" class="atur_huruf">Kode RAP</th>
				<th style="border-bottom:1px solid black;width:35%;">Uraian</th>
				<th style="border-bottom:1px solid black;width:5%;">Uom</th>
				<th style="border-bottom:1px solid black;text-align: right;width:5%;">Vol</th>
				<th style="border-bottom:1px solid black;text-align: right;width:10%;">Harga Satuan</th>
				<th style="border-bottom:1px solid black;text-align: right;width:10%;">Total</th>
				<th style="border-bottom:1px solid black;text-align: right;width:10%;">Sudah Terserap</th>
				<th style="border-bottom:1px solid black;text-align: right;width:10%;">%</th>
				<th style="border-bottom:1px solid black;text-align: right;width:10%;">Sisa RAP</th>
				<th></th>
			</tr>
		</thead>
		<tbody>';

$TipeHeader = '';
$KdRAP_header = '';
$TtlTerserap = 0;

$sumHrgSatuan = 0;
$sumTotal = 0;
$sumSdhTerserap = 0;
$sumSisaRAP = 0;
foreach ($dataRAP as $RAP) :

	$TipeHeader = ($RAP['Tipe'] == 'Header') ? 'text-bold' : '';

	$persen = ($RAP['Total'] != 0) ? ($RAP['SudahTerserap'] / $RAP['Total']) * 100 : 0;
	$sisaRAP = $RAP['Total'] - $RAP['SudahTerserap'];

	$sumHrgSatuan += $RAP['HrgSatuan'];
	$sumTotal += $RAP['Total'];
	$sumSdhTerserap += $RAP['SudahTerserap'];
	$sumSisaRAP += $sisaRAP;


	$html .= '
			<tr style="" class="' . $TipeHeader . '" >
				<td style="text-align:left;padding-top:5px;padding-bottom:5px;">' . $RAP['KdRAP'] . '</td>
				<td style="">' . $RAP['Uraian'] . '</td>
				<td >' . $RAP['Uom'] . '</td>
				<td style="text-align: right;">' . $RAP['Vol'] . '</td>
				<td style="text-align: right;">' . UangBiasa($RAP['HrgSatuan']) . '</td>
				<td style="text-align: right;">' . UangBiasa($RAP['Total']) . '</td>
				<td style="text-align: right;">' . UangBiasa($RAP['SudahTerserap']) . '</td>
				<td style="text-align: right;">' . UangBiasa($persen) . '</td>
				<td style="text-align: right;">' . UangBiasa($sisaRAP) . '</td>
				<td></td>
			</tr>
			';

endforeach;
$html .= '
			<tr class="sumFooter">
				<td colspan="4" style="text-align:center;">SUM</td>
				<td>' . UangBiasa($sumHrgSatuan) . '</td>
				<td>' . UangBiasa($sumTotal) . '</td>
				<td>' . UangBiasa($sumSdhTerserap) . '</td>
				<td></td>
				<td>' . UangBiasa($sumSisaRAP) . '</td>
			</tr>
		';

$html .= '</tbody>
	</table>';

// $html .= '
// <table border="0" width="100%"  class="atur_huruf">
//     <thead>
//         <tr>
//             <th style="border-bottom:1px solid black;padding-top:5px;padding-bottom:5px;" class="atur_huruf">No PD</th>
//             <th style="border-bottom:1px solid black;" >Tgl PD</th>
//             <th style="border-bottom:1px solid black;" width="20%">Deskripsi</th>
//             <th style="border-bottom:1px solid black;">Prd Awal</th>
//             <th style="border-bottom:1px solid black;">Prd Akhir</th>
//             <th style="border-bottom:1px solid black;">No KO</th>
//             <th style="border-bottom:1px solid black;">Nama</th>
//             <th style="border-bottom:1px solid black;">Total PD</th>
//             <th style="border-bottom:1px solid black;">Tgl Bayar</th>
//             <th style="border-bottom:1px solid black;">Via</th>
//             <th style="border-bottom:1px solid black;">Rek Pengirim</th>
//             <th style="border-bottom:1px solid black;">Rek Penerima</th>
//             <th style="border-bottom:1px solid black;">No PJ</th>
//             <th style="border-bottom:1px solid black;">Tgl PJ</th>
//             <th style="border-bottom:1px solid black;">Total PJ</th>
//             <th style="border-bottom:1px solid black;">Tipe Form</th>
//         </tr>
//     </thead>
//     <tbody>';

//     foreach ($data_dppd as $mydata) :
//         $RekPenerima = $mydata['Bank'] . ' / ' . $mydata['AtasNama'] . ' / ' . $mydata['NoRek'];

//     $html .= '
//         <tr>
//             <td class="huruf_td">' . $mydata['NoPD'] . '</td>
//             <td class="huruf_td">' . TglIndoGarisMiring_tanpa_waktu($mydata['TglPD']) . '</td>
//             <td class="huruf_td" >' . $mydata['Deskripsi'] . '</td>
//             <td class="huruf_td">' . TglIndoGarisMiring_tanpa_waktu($mydata['PrdAwal']) . '</td>
//             <td class="huruf_td">' . TglIndoGarisMiring_tanpa_waktu($mydata['PrdAkhir']) . '</td>
//             <td class="huruf_td">' . $mydata['NoKO'] . '</td>
//             <td class="huruf_td">' . $mydata['Nama'] . '</td>
//             <td class="huruf_td">' . UbahGabunganUang($mydata['TotalPD']) . '</td>
//             <td class="huruf_td">' . TglIndoGarisMiring_tanpa_waktu($mydata['TglBayar']) . '</td>
//             <td class="huruf_td">' . $mydata['CaraBayar'] . '</td>
//             <td class="huruf_td">' . dapat_rekening($mydata['RekId']) . '</td>
//             <td class="huruf_td">' . $RekPenerima . '</td>
//             <td class="huruf_td">' . $mydata['NoPJ'] . '</td>
//             <td class="huruf_td">' . TglIndoGarisMiring_tanpa_waktu($mydata['TglPJ']) . '</td>
//             <td class="huruf_td">' . UbahGabunganUang($mydata['TotalPJ']) . '</td>
//             <td class="huruf_td">' . $mydata['TipeForm'] . '</td>          
//         </tr>';

//     endforeach;

// $html .='</tbody>
//     </table>';





$mpdf->WriteHTML($html);
// $mpdf->Output('REPORT - PENYERAPAN RAP - ' . date('ymdHis') . '.pdf', 'I');
$mpdf->Output('REPORT - PENYERAPAN RAP - ' . date('ymdHis') . '.pdf', 'I');
// $mpdf->Output('REPORT - DAFTAR PERMINTAAN DANA & PERTANGGUNGJAWABAN DANA (DPPD) - ' . date('ymdHis') . '.pdf', 'D');
