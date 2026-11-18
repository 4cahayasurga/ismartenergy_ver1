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
$stylesheet_new = file_get_contents('mycss/RekapKO.css');


$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle('Rekap KO|| '.$this->Company.' ');
$mpdf->use_kwt = true;


$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HEADER_CSS);
// $mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HTML_CSS);
// $mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::FOOTER_);

$mpdf->SetHTMLHeader($this->load->view('laporan_view/procurement/RekapKO/pdf_atas', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('laporan_view/procurement/RekapKO/pdf_bawah', NULL, TRUE));



$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;
$no = 1;


$html = '';

$html .='
<br>
<table width="100%" border="0" style="border-collapse:collapse;" class="huruf font_10" id="tbl_atas" >
	<thead>
		<tr>
			<th class="text-center" style="width:3%;">No</th>
			<th style="width:8%;">No KO</th>
			<th style="width:8%;">Tanggal</th>
			<th style="width:28%;" >Vendor</th>
			<th style="width:9%;">Kategori</th>
			<th class="text-right" style="width:12%;"  >Nilai KO (Rp)</th>
			<th class="text-right" style="width:12%;" >Telah Terbayar (Rp)</th>
			<th class="text-right" style="width:12%;" >Sisa Pembayaran (Rp)*</th>
			<th style="padding-left:5px;">Status Closed</th>
		</tr>

		<tr>
			<td colspan="4"></td>
			<td style="font-weight:bold;padding-top:6px;padding-bottom:6px;">Grand Total</td>
			<td class="text-right" style="font-weight:bold;padding-top:6px;padding-bottom:6px;background-color:#00BFFF;">'.number_format($SUM_TotalKO).'</td>
			<td class="text-right" style="font-weight:bold;padding-top:6px;padding-bottom:6px;background-color:#7CFC00;">'.number_format($SUM_TotalTerbayar).'</td>
			<td class="text-right" style="font-weight:bold;padding-top:6px;padding-bottom:6px;background-color:#FF4500;">'.number_format($SUM_SisaPembayaran).'</td>
			<td></td>
		</tr>
	</thead>
	
';

$SUMTotalKO = 0;
$SUMTotal_Terbayar = 0;
foreach($arrayKO as $arrayKO):
	$SUMTotalKO += $arrayKO->TotalKO;
$html .='
	<tbody>
		<tr>
			<td class="text-center">'.$arrayKO->NoUrut.'</td>
			<td>'.$arrayKO->NoKO.'</td>
			<td>'.tgl_baru($arrayKO->TglKO).'</td>
			<td>'.$arrayKO->VendorNm.'</td>
			<td>'.$arrayKO->KategoriId.'</td>
			<td class="text-right">'.number_format($arrayKO->TotalKO).'</td>
			<td class="text-right">'.number_format($arrayKO->TotalTerbayar).'</td>
			<td class="text-right">'.number_format($arrayKO->SisaPembayaran).'</td>
			<td style="padding-left:5px;">'.$arrayKO->ClosedBy.'</td>
		</tr>
		';

		if ($arrayKO->BLE > 0) {
			$html .='
			<tr>
				<td></td>
				<td colspan="7">
					<table  style="border-collapse: collapse;width: 70%;border:1px solid;" >

						<tr>
							<th style="text-align:center;width:5%;border-bottom:1px solid black;padding-top:7px;padding-bottom:7px;">No.</th>
							<th style="width:15%;border-bottom:1px solid black;" >No. PD</th>
							<th style="width:10%;border-bottom:1px solid black;">Tgl Bayar</th>
							<th style="text-align:right;width:15%;border-bottom:1px solid black;">Nilai KO (Rp)</th>
							<th style="padding-left:5px;border-bottom:1px solid black;width:30%;">Keterangan</th>
							<th></th>
						</tr>
						';

						$subtotal_BLE = 0;
						foreach($arrayKO->DataTerbayar as $dataTerbayar) { 
							$subtotal_BLE += $dataTerbayar['Amount'];
							$html .='
							<tr>
								<td style="text-align:center;padding-top:5px;padding-bottom:5px;">'.$dataTerbayar['NoUrutTerbayar'].'</td>
								<td>'.$dataTerbayar['NoPD'].'</td>
								<td>'.tgl_baru($dataTerbayar['TglBayar']).'</td>
								<td class="text-right font_10" >'.number_format($dataTerbayar['Amount']).'</td>
								<td style="padding-left:5px !important;" >'.$dataTerbayar['Keterangan'].'</td>
								<td></td>
							</tr>

							';
						}
						$html .='
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td style="text-align:right;border-top:1px solid black;">'.number_format($subtotal_BLE).'</td>
							<td></td>
							<td></td>
						</tr>';

			$html .='			
					</table>
				</td>
			</tr>
			';

		}

		if ($arrayKO->CekKontrak > 0) {
			$html .='
			<tr>
				<td></td>
				<td colspan="7">
					<table  style="border-collapse: collapse;width: 70%;border:1px solid;" >

						<tr>
							<th style="text-align:center;width:5%;border-bottom:1px solid black;padding-top:7px;padding-bottom:7px;">No.</th>
							<th style="width:15%;border-bottom:1px solid black;" >No. Kontrak</th>
							<th style="width:10%;border-bottom:1px solid black;">Tanggal</th>
							<th style="text-align:right;width:15%;border-bottom:1px solid black;">Nilai KO (Rp)</th>
							<th></th>
						</tr>
						';

						$subtotal_Kontrak = 0;
						foreach($arrayKO->DataKontrak as $dataKontrak) { 
							$subtotal_Kontrak += $dataKontrak['TtlKO'];
							$html .='
							<tr>
								<td style="text-align:center;padding-top:5px;padding-bottom:5px;">'.$dataKontrak['NoUrutKontrak'].'</td>
								<td>'.$dataKontrak['NoKO'].'</td>
								<td>'.tgl_baru($dataKontrak['TglKO']).'</td>
								<td class="text-right font_10" >'.number_format($dataKontrak['TtlKO']).'</td>
								<td></td>
							</tr>

							';
						}
						$html .='
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td style="text-align:right;border-top:1px solid black;">'.number_format($subtotal_Kontrak).'</td>
							<td></td>
						</tr>';

			$html .='			
					</table>
				</td>
			</tr>
			';

		}

$html .='
	</tbody>
';

endforeach;


$html .='
</table>
<p></p>
<p class="font_10" style="font-weight:bold;">* Exclude KO yg sdh closed.</p>
';

$mpdf->WriteHTML($html);
// exit;

$mpdf->Output('RekapKO_' . date('ymdHis') . '.pdf', 'I');
