<?php
$mpdfConfig = array(
	'mode' => 'utf-8',
	'format' => 'A4',
	'orientation' => 'L',
	'setAutoBottomMargin' => 'stretch',
	'setAutoTopMargin' => 'stretch',
	'margin_left' => 4,
    'margin_right' => 4,
    'margin_header' => 3,
    'margin_footer' => 0,

);
$stylesheet = file_get_contents('css/bootstrap-3.3.7.min.css');
$stylesheet_new = file_get_contents('mycss/TrackingKO.css');


$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle('TrackingKO KO|| '.$this->config->item('Company').' ');
$mpdf->use_kwt = true;


$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HEADER_CSS);
// $mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HTML_CSS);
// $mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::FOOTER_);

$mpdf->SetHTMLHeader($this->load->view('laporan_view/procurement/TrackingKO/pdf_atas', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('laporan_view/procurement/TrackingKO/pdf_bawah', NULL, TRUE));



$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;
$no = 1;


$html = '';

$html .='
	<table width="100%" border="0" id="mytable" style="border-collapse:collapse;border:0px solid" class="font_10">
		<tr>
			<th style="border-bottom:1px solid;padding-top:5px;padding-bottom:5px;text-align:center;width:20px;" >No.</th>
			<th style="border-bottom:1px solid;width:100px;" >No KO</th>
			<th style="border-bottom:1px solid;width:100px;" >Tanggal</th>
			<th style="border-bottom:1px solid;width:200px;" >Nilai KO (Rp)</th>
			<th style="border-bottom:1px solid;" >Vendor</th>
			
		</tr>';

	foreach($arrayTracking AS $data) {
		$html .='
			<tr>
				<td style="padding-top:5px;padding-bottom:5px;text-align:center;width:20px;" >'.$data->NoUrut.'</td>
				<td  >'.$data->NoKO.'</td>
				<td  >'.tgl_baru($data->TglKO).'</td>
				<td  >'.number_format($data->TotalKO).'</td>
				<td  >'.$data->VendorNm.'</td>
			</tr>';

		if ($data->JumlahDetail > 0):
			$html .='<tr>
						<td></td>
						<td colspan="4">
							<table width="70%" border="0" style="border-collapse:collapse;border:1px solid;">
								<tr>
									<th style="text-align:center;padding-left:3px;padding-right: 3px;width: 5%;padding-top:5px;padding-bottom:5px;border-bottom:1px solid;">No.</th>
									<th style="width: 10%;border-bottom:1px solid;" >No KO</th>
									<th style="border-bottom:1px solid;" >Keterangan</th>
									<th style="width: 10%;border-bottom:1px solid;">Tanggal</th>
									<th style="width: 10%;border-bottom:1px solid;">Status</th>
								</tr>';

								$NoUrutDetail = 1;
								foreach($data->arrayDetail_Tracking as $detail) :
									$html .='
									<tr>
										<td style="text-align:center;padding-top:5px;padding-bottom:5px;">'.$NoUrutDetail++.'</td>
										<td>'.$detail['NoKO'].'</td>
										<td>'.$detail['Keterangan'].'</td>
										<td>'.tgl_baru($detail['Tanggal']).'</td>
										<td>'.$detail['Status'].'</td>
									</tr>
									';
								endforeach;

						$html .='
							</table>
					</tr>';

		endif;

	}


	
$html .='
	</table>
';


$mpdf->WriteHTML($html);
// exit;

$mpdf->Output('TrackingKO' . date('ymdHis') . '.pdf', 'I');
