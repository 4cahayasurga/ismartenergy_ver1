<?php
$mpdfConfig = array(
	'mode' => 'utf-8',
	'format' => 'A4',
	'orientation' => 'L',
	'setAutoBottomMargin' => 'stretch',
	'setAutoTopMargin' => 'stretch',
	'margin_left' => 8,
    'margin_right' => 8,
    'margin_header' => 5,
    'margin_footer' => 5,

);
$stylesheet = file_get_contents('css/bootstrap-3.3.7.min.css');
$stylesheet_new = file_get_contents('mycss/PD_baru.css');



$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle('Permintaan Dana ('.strtoupper($Dari).') || '.$this->Company.' ');
$mpdf->use_kwt = true;


$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HEADER_CSS);
// $mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HTML_CSS);
// $mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::FOOTER_);

$mpdf->SetHTMLHeader($this->load->view('MY_PD/PD_PDF_ALL_view/header_pdf', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('MY_PD/PD_PDF_ALL_view/footer_pdf', NULL, TRUE));



$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;
$html = '';

// batas
$no = 1;

$Keterangan = '';
$Keterangan = ($dataPD['Keterangan'] == '' || $dataPD['Keterangan'] == null ) ? '-' : $dataPD['Keterangan'];


$html .='
<div class="col-xs-6 row">';

	$html .='
	<table  id="tbl_saya" border="0">';

	$html .='
		<thead>
			<tr >
				<th class="untuk_border_bottom" style="width:4% !important;">No</th>
				<th class="untuk_border_bottom" style="width:8% !important;">Kode</th>
				<th class="untuk_border_bottom" style="width:35% !important;">Uraian</th>
				<th class="untuk_border_bottom" style="width:8% !important;text-align:right;">Vol</th>
				<th class="untuk_border_bottom" style="width:7% !important;">Sat</th>
				<th class="untuk_border_bottom" style="text-align:right;">Harga (Rp)</th>
				<th class="untuk_border_bottom" style="text-align:right;">Jumlah (Rp)</th>
				
			</tr>
		</thead>
		<tbody>';
			foreach($array_PdDtl as $hanya_detail_PD):

			$html .='
			<tr>
				<td class="text-center" style="vertical-align:top !important;">'.$hanya_detail_PD['NoUrut'].'</td>
				<td style="vertical-align:top !important;">'.$hanya_detail_PD['KdRAP'].'</td>
				<td style="vertical-align:top !important;">'.$hanya_detail_PD['Uraian'].'</td>
				<td style="vertical-align:top !important;" class="text-right">'.uang_koma_genap_decimal_kedua($hanya_detail_PD['Vol']).'</td>
				<td style="vertical-align:top !important;">&nbsp;&nbsp;&nbsp;'.$hanya_detail_PD['Uom'].'</td>
				<td style="vertical-align:top !important;" class="text-right">'.uang_koma_genap_decimal_kedua($hanya_detail_PD['HrgSatuan']).'</td>
				<td style="vertical-align:top !important;" class="text-right">'.uang_koma_genap_decimal_kedua($hanya_detail_PD['JumlahHarga']).'</td>
			</tr>';

		endforeach;

			

			// $html .='
			// <tr>
			// 	<td colspan="7" style="border-top:1px solid;" ></td>				
			// </tr>';

		$html.='
		</tbody>';

	


	$html .='
	</table>';

	

$html .='
</div>';

//batas -----------------------------------------------------------------------------------------------

$html .='
<div class="col-6">';

	$html .='
	<table  id="tbl_saya" border="0">';

	$html .='
		<thead>
			<tr >
				<th class="untuk_border_bottom" style="width:4% !important;">No</th>
				<th class="untuk_border_bottom" style="width:8% !important;">Kode</th>
				<th class="untuk_border_bottom" style="width:35% !important;">Uraian</th>
				<th class="untuk_border_bottom" style="width:8% !important;text-align:right;">Vol</th>
				<th class="untuk_border_bottom" style="width:7% !important;">Sat</th>
				<th class="untuk_border_bottom" style="text-align:right;">Harga (Rp)</th>
				<th class="untuk_border_bottom" style="text-align:right;">Jumlah (Rp)</th>
				
			</tr>
		</thead>
		<tbody>';

		$NoUrutPJ = 1;
		foreach($array_PdDtl_PJ as $hanya_detail_PD_PJ):

			$html .='
			<tr>
				<td class="text-center" style="vertical-align:top !important;">'.cek_pj_saya($NoPJ,$hanya_detail_PD_PJ['NoUrut']).'</td>
				<td style="vertical-align:top !important;" >'.cek_pj_saya($NoPJ,$hanya_detail_PD_PJ['KdRAP']).'</td>
				<td style="vertical-align:top !important;" >'.cek_pj_saya($NoPJ,$hanya_detail_PD_PJ['PjUraian']).'</td>
				<td style="vertical-align:top !important;" class="text-right">'.cek_pj_saya($NoPJ,uang_koma_genap_decimal_kedua($hanya_detail_PD_PJ['PjVol'])).'</td>
				<td style="vertical-align:top !important;" >&nbsp;&nbsp;&nbsp;'.cek_pj_saya($NoPJ,$hanya_detail_PD_PJ['Uom']).'</td>
				<td style="vertical-align:top !important;" class="text-right">'.cek_pj_saya($NoPJ,uang_koma_genap_decimal_kedua($hanya_detail_PD_PJ['PjHrgSatuan'])).'</td>
				<td style="vertical-align:top !important;" class="text-right">'.cek_pj_saya($NoPJ,uang_koma_genap_decimal_kedua($hanya_detail_PD_PJ['PjJumlahHarga'])).'</td>
			</tr>';

		endforeach;


			// $html .='
			// <tr>
			// 	<td colspan="7" style="border-top:1px solid;" ></td>				
			// </tr>';

		$html.='
		</tbody>';

	$html .='
	</table>';

	


$html .='
</div>';


$html .='
<div class="col-xs-12 row">
	<div class="col-xs-6 row" >

		<table id="tbl_saya" border="0" style="margin-top:5px;margin-bottom:10px;">
			<tr>
				<td style="border-top:1px solid;" ></td>
				</tr>
		</table>

		<table id="tbl_saya" border="0" style="margin-top:5px;margin-bottom:10px;">
			<tr>
				<th width="13%">Keterangan :</th>
				<td width="40%" class="untuk_border_bottom_saja ">'.$Keterangan.'</td>
				<th width="13%">&nbsp;&nbsp;Sub Total :</th>
				<td class="text-right ">'.uang_koma_genap_decimal_kedua($SubTotal_PdDtl).'</td>		
			</tr>
		</table>

		<table id="tbl_saya" border="0" style="margin-top:5px;margin-bottom:10px;">
			<tr>
				<th width="13%"></th>
				<th width="40%" class="text-right ">Saldo sebelumnya dari No. </th>
				<th width="20%" class="untuk_border_bottom_saja">'.$NoPJSebelumnya.'</th>
				<td class="text-right ">'.uang_koma_genap_decimal_kedua($Saldo).'</td>		
			</tr>
		</table>

		<table id="tbl_saya" border="0" >
			<tr>
				<th width="13%" class=""></th>
				<th width="50%" class="text-right padding_atas">Total Permintaan Dana : </th>
				<td width="10%" class=""></td>
				<td class="text-right " style="border-bottom: 3px double black;">'.uang_koma_genap_decimal_kedua($TotalPermintaan).'</td>		
			</tr>
		</table>

	</div>

	<div class="col-6"  >

		<table id="tbl_saya" border="0" style="margin-top:5px;margin-bottom:10px;">
			<tr>
				<td style="border-top:1px solid;" ></td>
				</tr>
		</table>

		<table id="tbl_saya" border="0" style="margin-top:5px;margin-bottom:10px;">
			<tr>
				<td width="30%"></td>
				<th class="text-center" >Total Pertanggungjawaban :</th>
				<td class="text-right">'.cek_pj_saya($NoPJ,uang_koma_genap_decimal_kedua($SubTotal_PdDtl_PJ)).'</td>	
			</tr>
		</table>

		<table id="tbl_saya" border="0" style="margin-top:5px;margin-bottom:10px;">
			<tr>
				<td width="30%"></td>
				<th class="text-center">Dana yang tersedia di lapangan :</th>
				<td class="text-right">'.cek_pj_saya($NoPJ,uang_koma_genap_decimal_kedua($SubTotal_PdDtl)).'</td>	
			</tr>
		</table>

		<table id="tbl_saya" border="0">
			<tr>
				<td width="30%"></td>
				<th class="text-center">Saldo :</th> 
				<td class="text-right" style="border-bottom: 3px double black;">'.cek_pj_saya($NoPJ,uang_koma_genap_decimal_kedua($Saldo_PJ)).'</td>	
			</tr>
		</table>

	</div>

</div>
';

// ========================================================== batas ==============================================================================
// die;
$mpdf->WriteHTML($html);

// $mpdf->Output('Permintaan Dana ('.strtoupper($Dari).') - ' . date('ymdHis') . '.pdf', 'I');
$mpdf->Output('Permintaan Dana ('.strtoupper($Dari).') - ' . date('ymdHis') . '.pdf', 'I');
