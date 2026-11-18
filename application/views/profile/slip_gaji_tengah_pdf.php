<?php
$mpdfConfig = array(
    'mode' => 'utf-8',
    'format' => 'A4',
    'orientation' => 'P',
    // 'margin-left' => 0,
    // 'margin-right' => 0,
    // 'margin-top' => -5,
    // 'margin-bottom' => 0,
    // 'margin_header' => 0,
    'margin_header' => 5,
    'margin_footer' => 5,
    'orientation' => 'P',
    'margin_left' => 7,
    'margin_right' => 7,
    'setAutoBottomMargin' => 'stretch',
    'setAutoTopMargin' => 'stretch',

);


$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$stylesheet = file_get_contents('css/bootstrap-3.3.7.min.css');
$stylesheet_baru = file_get_contents('mycss/css_slipgaji.css');

$mpdf->SetTitle(' SLIP GAJI ');
$mpdf->use_kwt = true;

$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_baru, \Mpdf\HTMLParserMode::HEADER_CSS);

// $mpdf->SetHTMLHeader($this->load->view('profile/slip_gaji_atas_pdf', NULL, TRUE), 'O');
// $mpdf->SetHTMLFooter($this->load->view('profile/slip_gaji_bawah_pdf', NULL, TRUE));

$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;

$html = '';

$html .= '
<table width="100%" border="0" style="display:;" >

<tr>
	<td style="width:20%;text-align: center;border-bottom:1px solid;padding-bottom:5px;">

		<img class="img-responsive col-xs-12"  style="max-width: 200px;max-height: 200px;text-align: center !!important;padding-top: 20px !important;padding-bottom: 20px !important;"  src="'.$LogoPT.'"  />

	</td>
	<td style="width:80%;text-align: center;border-bottom:1px solid;" class="huruf_16">SLIP GAJI '.$BulanKata." ".$PrdTahun.'</td>
</tr>

<tr>
	<td style="width:100%;padding-top: 5px;padding-bottom: 5px;color:red;font-weight: bold;" class="huruf" colspan="2">'.$NamaKaryawan.'</td>
</tr>

</table>';

$html .= '
<table width="100%" border="0" style="display:;">
	<tr>
		<td style="width:50%;padding-top: 3px;padding-bottom: 3px;" class="huruf">'.$info_payroll['Jabatan'].' / NIK : '.$info_payroll['NIK'].'</td>
		<td style="width:50%;padding-top: 3px;padding-bottom: 3px;" class="huruf">Gaji Pokok</td>
	</tr>
	<tr>
		<td style="width:50%;padding-top: 3px;padding-bottom: 3px;color:black;font-weight: bold;" class="huruf_14">'.strtoupper($NamaPT).'</td>
		<td style="width:50%;padding-top: 3px;padding-bottom: 3px;color:black;font-weight: bold;" class="huruf_14">'.'Rp '.UangBiasa($info_payroll['GajiPokok']).'</td>
	</tr>

</table>
';

$html .='

<div class="row" style="display:;">

			<div class="col-xs-6">

				<div class="col-sm-12" >

					<table width="100%" border="0" class="huruf">
						<tr>
							<td style="padding-top: 3px;padding-bottom: 3px;background-color: #95a5a6;color:red;border:1px solid red;" >TUNJANGAN</td>
						</tr>
					</table>

					<p></p>
					<table width="100%" border="0" class="huruf">

						<tr>
							<td style="width: 35%;padding-top: 3px;padding-bottom: 3px;padding-left:10px !important;" >Tunjangan Kesehatan</td>
							<td style="width: 2%;text-align: center;">:</td>
							<td style="text-align:right;">Rp '.UangBiasa($info_payroll['TunjanganKesehatan']).'</td>
						</tr>

						<tr>
							<td style="width: 35%;padding-top: 3px;padding-bottom: 3px;padding-left:10px !important;" >Tunjangan Jabatan</td>
							<td style="width: 2%;text-align: center;" >:</td>
							<td style="text-align:right;" >Rp '.UangBiasa($info_payroll['TunjanganJabatan']).'</td>
						</tr>

						<tr>
							<td style="width: 35%;padding-top: 3px;padding-bottom: 3px;padding-left:10px !important;" >Tunjangan Operasional</td>
							<td style="width: 2%;text-align: center;" >:</td>
							<td style="text-align:right;" >Rp '.UangBiasa($info_payroll['TunjanganOperasional']).'</td>
						</tr>

					</table>


				</div>

			</div>

			<div class="col-md-6">

				<div class="col-sm-12">

					<table width="100%" border="0" class="huruf">
						<tr>
							<td style="padding-top: 3px;padding-bottom: 3px;background-color: #95a5a6;color:red;border:1px solid red;" >&nbsp;POTONGAN</td>
						</tr>
					</table>
					<p></p>

					<table width="100%" border="0" class="huruf">

						<tr>
							<td style="width: 35%;padding-top: 3px;padding-bottom: 3px;padding-left:10px !important;" >PPh 21</td>
							<td style="width: 2%;text-align: center;">:</td>
							<td style="text-align:right;">RpRp '.UangBiasa($info_payroll['PPh21']).'</td>
						</tr>

						<tr>
							<td style="width: 35%;padding-top: 3px;padding-bottom: 3px;padding-left:10px !important;">Jaminan Hari Tua</td>
							<td style="width: 2%;text-align: center;">:</td>
							<td style="text-align:right;">Rp '.UangBiasa($info_payroll['JHTEmployee']).'</td>
						</tr>

						<tr>
							<td style="width: 35%;padding-top: 3px;padding-bottom: 3px;padding-left:10px !important;">Jaminan Pensiun</td>
							<td style="width: 2%;text-align: center;">:</td>
							<td style="text-align:right;">Rp '.UangBiasa($info_payroll['JPEmployee']).'</td>
						</tr>

						<tr>
							<td style="width: 35%;padding-top: 3px;padding-bottom: 3px;padding-left:10px !important;">Potongan Kedisplinan</td>
							<td style="width: 2%;text-align: center;">:</td>
							<td style="text-align:right;">Rp '.UangBiasa($info_payroll['PotonganKedisiplinan']).'</td>
						</tr>

						<tr>
							<td style="width: 35%;padding-top: 3px;padding-bottom: 3px;padding-left:10px !important;">Potongan THP</td>
							<td style="width: 2%;text-align: center;">:</td>
							<td style="text-align:right;">Rp '.UangBiasa($info_payroll['PotHP']).'</td>
						</tr>

					</table>
					<p></p>

				</div>


			</div>

			<div class="col-sm-12">
				<table width="100%" border="0" class="huruf_14">
					<tr>
						<th class="huruf_14" style="padding-top: 3px;padding-bottom: 3px;background-color: #95a5a6;color:red;">&nbsp;&nbsp;&nbsp;&nbsp;TAKE HOME PAY</th>
						<th class="huruf_14" style="padding-top: 3px;padding-bottom: 3px;text-align: right;background-color: #95a5a6;color:red;">Rp '.UangBiasa($info_payroll['GajiNetto']).'&nbsp;&nbsp;&nbsp;&nbsp;</th>
					</tr>
				</table>
			</div>


		</div>


';

// exit;

$mpdf->WriteHTML($html);

// $mpdf->Output('SLIP GAJI - '.$BulanKata.' '.$PrdTahun.' - ' . date('ymdHis') . '.pdf', 'I');
$mpdf->Output('SLIP GAJI - '.$BulanKata.' '.$PrdTahun.' - ' . date('ymdHis') . '.pdf', 'D');