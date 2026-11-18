<?php

$mpdfConfig = array(
	'mode' => 'utf-8',
	'format' => 'A4',
	'margin_header' => 7,

	'margin_footer' => 5,
	'orientation' => 'P',
	'setAutoBottomMargin' => 'stretch',
	'setAutoTopMargin' => 'stretch',

);
$stylesheet = file_get_contents('css/bootstrap-3.3.7.min.css');
$stylesheet_new = file_get_contents('css_bantuan_ko.css');

$Company = $this->config->item('Company');

$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle(' '.$Company.'  ||  KONTRAK');
$mpdf->use_kwt = true;

$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HEADER_CSS);


$mpdf->SetHTMLHeader($this->load->view('procurement/kontrak_po/print/print_header', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('procurement/kontrak_po/print/print_footer', NULL, TRUE));


$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;

$html = '';

$html .= '
<div class="row" >

	<div class="col-sm-12 row" >

		

		<table width="100%" border="0" style="page-break-inside: avoid !important">
		
			<tr style="border-bottom:1px solid black;">
				<th style="text-align:center;width:5%;border-bottom:1px solid black;" >No.</th>
				<th style="text-align:center;width:5%;border-bottom:1px solid black;">Kode</th>
				<th style="text-align:center;width:30%;border-bottom:1px solid black;">Uraian</th>
				<th style="text-align:center;width:5%;border-bottom:1px solid black;">Vol</th>
				<th style="text-align:center;width:5%;border-bottom:1px solid black;">Sat</th>
				<th style="text-align:center;width:10%;border-bottom:1px solid black;">Harga (Rp)</th>
				<th style="text-align:center;width:10%;border-bottom:1px solid black;">Jumlah PPN (Rp)</th>
				<th style="text-align:center;width:15%;border-bottom:1px solid black;">Jumlah (Rp)</th>
			</tr>';
$no = 1;

foreach ($data_detail as $data_detail) :
	$NoUrut = $data_detail['NoUrut'];

	$html .= '
			<tr>
				<td style="text-align:center;width:5%;padding-top:10px;padding-bottom:10px;">' . $no++ . '.</td>
				<td style="text-align:left;width:100px;">' . strtoupper($data_detail['KdRAP']) . '</td>
				<td style="text-align:left;">' . konversi_BR_ke_enter(strtoupper($data_detail['Uraian'])) . '</td>
				<td style="text-align:right;">' . strtoupper(number_format($data_detail['Vol'])) . '</td>
				<td style="text-align:center;">' . strtoupper($data_detail['Uom']) . '</td>
				<td style="text-align:right;">' . strtoupper(number_format($data_detail['HrgSatuan'])) . '</td>
				<td style="text-align:right;">' . strtoupper(number_format($data_detail['TotalNominalPPN'])) . '</td>
				<td style="text-align:right;">' . strtoupper(number_format($data_detail['JumlahHarga'])) . '</td>
			</tr>

			';

endforeach;

$html .= '
			<tr>
				<th colspan="3" style="padding-top:10px;padding-bottom:10px;border-top:1px solid black;"><i>Terbilang : ' . ucwords(terbilang($data_ko['Total'])) . ' </i></th>
				<th colspan="4" style="text-align:right;border-top:1px solid black;">Sub Total :</th>
				<th style="text-align:right;border-top:1px solid black;">' . number_format($data_ko['SubTotal']) . '</th>
			</tr>

			<tr>
				
				<th colspan="7" style="text-align:right;">PPN:</th>
				<th style="text-align:right;border-bottom:1px solid black;;">' . $sum_TotalNominalPPN . '</th>
			</tr>

			<tr>
				<th colspan="3" style="padding-top:10px;padding-bottom:10px;border-top:1px;"><i></i></th>
				<th colspan="4" style="text-align:right;">Total:</th>
				// <th style="text-align:right;border-bottom:1px solid black;border-top:1px solid black;">' . number_format($data_ko['Total']) .  '</th>
			</tr>
			
			';

$html .= '
		</table>

	</div>';

$checked = '';

$INV = (CekBuktiPendukung($data_ko['SyaratPembayaran'], 'INV') == TRUE) ? "checked='checked' " : '';
$FP = (CekBuktiPendukung($data_ko['SyaratPembayaran'], 'FP') == TRUE) ? "checked='checked' " : '';
$SJ = (CekBuktiPendukung($data_ko['SyaratPembayaran'], 'SJ') == TRUE) ? "checked='checked' " : '';
$PO = (CekBuktiPendukung($data_ko['SyaratPembayaran'], 'PO') == TRUE) ? "checked='checked' " : '';
$BAP = (CekBuktiPendukung($data_ko['SyaratPembayaran'], 'BAP') == TRUE) ? "checked='checked' " : '';
$BAOP = (CekBuktiPendukung($data_ko['SyaratPembayaran'], 'BAOP') == TRUE) ? "checked='checked' " : '';
$K3 = ($data_ko['K3'] == 1) ? 'checked="checked"' : '';

$html .= '


	<table width="100%" border="0">
		<tr>
			<th style="text-align:left;background-color:#bdc3c7;font-size:10px;">Persyatan & Sanksi</th>
		</tr>
	</table>

	<div class="col-xs-6 row">
		<table width="100%" border="0">
			<tr>
				<td>1. Wajib memenuhi persyaratan Kesehatan & Keselamatan Kerja (K3) <input type="checkbox" class="form-control input-sm" style="font-size: 9px;" ' . $K3 . ' ></td>
				<td>

			</tr>

			<tr>
				<td style="padding-top:10px;">2. Syarat Teknis</td>
			</tr>
			<tr>
				<td style="padding-left:10px;border-bottom:1px solid black;padding-top:5px;line-height: 1.5;vertical-align:top;height:90px;"><small style="padding-left:20px;">' . konversi_BR_ke_enter(strtoupper($data_ko['SyaratTeknis'])) . '</small></td>
			</tr>

			<tr>
				<td style="padding-top:10px;">3. Jadwal Pengiriman</td>
			</tr>
			<tr>
				<td style="padding-left:10px;border-bottom:1px solid black;line-height: 1.5;vertical-align:top;height:90px;"><small style="padding-left:20px;padding-top:5px;">' . konversi_BR_ke_enter(strtoupper($data_ko['JadwalPengiriman'])) . '</small></td>
			</tr>

			<tr>
				<td style="padding-top:10px;">4. Jadwal Pembayaran</td>
			</tr>
			<tr>
				<td style="padding-left:10px;border-bottom:1px solid black;line-height: 1.5;vertical-align:top;height:90px;"><small style="padding-left:20px;padding-top:5px;line-height: 1.5;">' . konversi_BR_ke_enter(strtoupper($data_ko['JadwalPembayaran'])) . '</small></td>
			</tr>

		</table>
		
	</div>

	<div class="col-xs-5 row">

		<table width="100%" border="0">

			<tr>
				<td style="padding-top:10px;" colspan="2">5. Syarat Pembayaran</td>
			</tr>


			<tr>
				<td style="padding-top:5px;padding-bottom:5px;padding-left:11px;">
					<label><input type="checkbox" class="form-control input-sm"  style="font-size: 9px;" ' . $INV . ' > Invoice/Kwitansi</label>
				</td>
				<td>
					<label><input type="checkbox" class="form-control input-sm"  style="font-size: 9px;" ' . $FP . '> Faktur/Pajak</label>
				</td>
			</tr>
			
			<tr>
				<td style="padding-top:5px;padding-bottom:5px;padding-left:11px;">
					<label><input type="checkbox" class="form-control input-sm"  style="font-size: 9px;" ' . $SJ . '> Surat Jalan/Tanda Terima Lapangan</label>
				</td>
				<td>
					<label><input type="checkbox" class="form-control input-sm"  style="font-size: 9px;" ' . $PO . '> Copy PO</label>
				</td>
			</tr>

			<tr>
				<td colspan="2" style="padding-top:5px;padding-bottom:5px;padding-left:11px;">
					<label><input type="checkbox" class="form-control input-sm" style="font-size: 9px;" ' . $BAP . '> Berita Acara Pembayaran</label>
				</td>
				
			</tr>

			<tr>
				<td colspan="2" style="padding-top:5px;padding-bottom:5px;padding-left:11px;" >
					<label><input type="checkbox" class="form-control input-sm" style="font-size: 9px;" ' . $BAOP . '> Berita Acara Opname Pekerjaan</label>
				</td>
			</tr>

			


		</table>

		<table width="100%" border="0">
			<tr>
				<td style="padding-top:10px;">6. Sanksi</td>
			</tr>
			<tr>
				<td style="padding-left:10px;border-bottom:1px solid black;padding-top:5px;line-height: 1.5;vertical-align:top;height:102px;"><small style="padding-left:20px;">' . konversi_BR_ke_enter(strtoupper($data_ko['Sanksi'])) . '</small></td>
			</tr>

			<tr>
				<td style="padding-top:10px;">7. Keterangan Lainnya</td>
			</tr>
			<tr>
				<td style="padding-left:10px;border-bottom:1px solid black;padding-top:5px;line-height: 1.5;vertical-align:top;height:90px;"><small style="padding-left:20px;">' . konversi_BR_ke_enter(strtoupper($data_ko['Keterangan'])) . '</small></td>
			</tr>

		</table>
	</div>

</div>


';




// die;
$mpdf->WriteHTML($html);

// exit;
// $mpdf->Output('MDH-KONTRAK-' . date('ymdHis') . '.pdf', 'I');
$mpdf->Output(' '.$Company.' -KONTRAK-' . date('ymdHis') . '.pdf', 'I');
