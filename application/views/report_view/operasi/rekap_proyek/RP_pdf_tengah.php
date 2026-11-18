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
$stylesheet_baru = file_get_contents('mycss/css_RekapProyek.css');

$mpdf->SetTitle('REPORT ( REKAP PROYEK ) ');
$mpdf->use_kwt = true;

$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_baru, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('report_view/operasi/rekap_proyek/RP_pdf_atas', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('report_view/operasi/rekap_proyek/RP_pdf_bawah', NULL, TRUE));

$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;


$html = '
<table width="100%" border="0" id="table_QHutangKO" style="font-size:9px;">

	<tr>

		<th style="vertical-align:middle;text-align:center;padding-top:10px;padding-bottom:10px;border-bottom:1px solid black;width:5%;">Job No</th>
		<th style="vertical-align:middle;text-align:center;padding-top:10px;padding-bottom:10px;border-bottom:1px solid black;width:10%;">Job Name</th>
		<th style="vertical-align:middle;text-align:center;border-bottom:1px solid black;width:13%;">Netto Kontrak Induk (Rp)</th>
		<th style="vertical-align:middle;text-align:center;border-bottom:1px solid black;width:5%;">% Share</th>
		<th style="vertical-align:middle;text-align:center;border-bottom:1px solid black;width:10%;">Netto Share (Rp)</th>
		<th style="vertical-align:middle;text-align:center;border-bottom:1px solid black;width:13%;">Total Alokasi A (Rp)</th>
		<th style="vertical-align:middle;text-align:center;border-bottom:1px solid black;width:13%;">Total Alokasi B (Rp)</th>
		<th style="vertical-align:middle;text-align:center;border-bottom:1px solid black;width:13%;">Total Alokasi C (Rp)</th>
		<th style="vertical-align:middle;text-align:center;border-bottom:1px solid black;width:13%;">Termin Induk (Rp)</th>
		<th style="vertical-align:middle;text-align:center;border-bottom:1px solid black;width:13%;">Termin Member (Rp)</th>
	</tr>';

	$no = 1;
	$SUM_Netto = 0;
	$SUM_NettoShare = 0;
	$SUM_AlokasiA = 0;
	$SUM_AlokasiB = 0;
	$SUM_AlokasiC = 0;
	$SUM_TerminInduk = 0;
	$SUM_TerminMember = 0;

	foreach($dapatRP as $mydata):

		$SUM_Netto += $mydata['Netto']; 
		$SUM_NettoShare += $mydata['NettoShare'];
		$SUM_AlokasiA += $mydata['AlokasiA'];
		$SUM_AlokasiB += $mydata['AlokasiB'];
		$SUM_AlokasiC += $mydata['AlokasiC'];
		$SUM_TerminInduk += $mydata['TerminInduk'];
		$SUM_TerminMember += $mydata['TerminMember'];

		$html .='
		<tr style="font-size:9px;">
			<td style="padding-top: 10px;padding-bottom: 5px;text-align:center;"   >'.$mydata['JobNo'].'</td>
			<td style="padding-left: 5px;">'.$mydata['JobNm'].'</td>
			<td style="text-align:right;">'.uang_koma_genap_decimal($mydata['Netto']).'</td>
			<td style="text-align:right;">'.new_decimal($mydata['PersenKSO']).'</td>
			<td style="text-align:right;">'.uang_koma_genap_decimal($mydata['NettoShare']).'</td>
			<td style="text-align:right;">'.uang_koma_genap_decimal($mydata['AlokasiA']).'</td>
			<td style="text-align:right;">'.uang_koma_genap_decimal($mydata['AlokasiB']).'</td>
			<td style="text-align:right;">'.uang_koma_genap_decimal($mydata['AlokasiC']).'</td>
			<td style="text-align:right;">'.uang_koma_genap_decimal($mydata['TerminInduk']).'</td>
			<td style="text-align:right;">'.uang_koma_genap_decimal($mydata['TerminMember']).'</td>
		</tr>';

	endforeach;

	$html .='
		<tr style="border-top:1px solid black;">
			<td colspan="3" style="text-align:right;font-weight: bold;padding-top: 15px;padding-bottom: 10px;border-top:1px solid black;">'.'SUM = '.uang_koma_genap_decimal($SUM_Netto).'</td>
			<td colspan="2" style="text-align:right;font-weight: bold;border-top:1px solid black;">'.'SUM = '.uang_koma_genap_decimal($SUM_NettoShare).'</td>
			<td style="text-align:right;font-weight: bold;border-top:1px solid black;">'.'SUM = '.uang_koma_genap_decimal($SUM_AlokasiA).'</td>
			<td style="text-align:right;font-weight: bold;border-top:1px solid black;">'.'SUM = '.uang_koma_genap_decimal($SUM_AlokasiB).'</td>
			<td style="text-align:right;font-weight: bold;border-top:1px solid black;">'.'SUM = '.uang_koma_genap_decimal($SUM_AlokasiC).'</td>
			<td style="text-align:right;font-weight: bold;border-top:1px solid black;">'.'SUM = '.uang_koma_genap_decimal($SUM_TerminInduk).'</td>
			<td style="text-align:right;font-weight: bold;border-top:1px solid black;">'.'SUM = '.uang_koma_genap_decimal($SUM_TerminMember).'</td>
	</tr>';




$html .='</table>';




// exit;

$mpdf->WriteHTML($html);

// $mpdf->Output('Report - REKAP PROYEK -  ' . date('ymdHis') . '.pdf', 'I');
$mpdf->Output('Report - REKAP PROYEK -  ' . date('ymdHis') . '.pdf', 'I');