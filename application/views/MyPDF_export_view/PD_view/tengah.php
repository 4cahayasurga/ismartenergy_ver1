<?php
$mpdfConfig = array(
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_header' => 0,
    'margin_header' => 5,
    'margin_footer' => 5,
    'orientation' => 'L',
    'margin_left' => 0,
    'margin_right' => 0,
    'setAutoBottomMargin' => 'stretch',
    'setAutoTopMargin' => 'stretch',

);


$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$stylesheet = file_get_contents('css/bootstrap-3.3.7.min.css');
$stylesheet_baru = file_get_contents('css/css_pdf_permintaan_dana.css');

$mpdf->SetTitle('PEMBAYARAN PERMINTAAN DANA (KEUANGAN) || '.$this->Company);
$mpdf->use_kwt = true;

$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_baru, \Mpdf\HTMLParserMode::HEADER_CSS);


$mpdf->SetHTMLHeader($this->load->view('MyPDF_export_view/PD_view/pdf_atas', NULL, TRUE), 'O');
// $mpdf->SetHTMLFooter($this->load->view('Payment_Permintaan_Dana/print/Payment_pdf_bawah', NULL, TRUE));

$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;
$html ='';

$html .='

<div class="row">

	<div class="col-xs-6" style="border:0px solid green;">

		<table  class="tbl_pd" border="0">
			<thead>
				<tr style="padding-top:10px;">
					<th style="width:4%;text-align:center;padding-top:5px;padding-bottom:5px;">No</th>
					<th style="width:7%;text-align:left;" >Kode</th>
					<th>Uraian</th>
					<th style="text-align:right;width:5%;padding-right:3px;">Vol</th>
					<th style="text-align:left;width:5%;padding-left:3px;" >Sat</th>
					<th style="text-align:right;width:12%;padding-right:3px;">Harga (Rp)</th>
					<th style="text-align:right;width:12%;padding-right:3px;">Jumlah (Rp)</th>
				</tr>
			</thead>
			<tbody>';

			foreach($detailPD as $mydetail){
				// $html .=' '.$mydetail->NoPD.' ';
				$html .='
				<tr>
					<td style="width:4%;text-align:center;padding-top:10px;padding-bottom:10px;">'.$mydetail->NoUrut.'</td>
					<td>'.$mydetail->KdRAP.'</td>
					<td>'.$mydetail->Uraian.'</td>
					<td style="text-align:right;width:5%;padding-right:3px;">'.UbahGabunganUang($mydetail->Vol).'</td>
					<td style="text-align:left;width:5%;padding-left:3px;" >'.$mydetail->Uom.'</td>
					<td style="text-align:right;width:12%;padding-right:3px;">'.UbahGabunganUang($mydetail->HrgSatuan).'</td>
					<td style="text-align:right;width:12%;padding-right:3px;">'.UbahGabunganUang($mydetail->JumlahHarga).'</td>
				</tr>';
				$nomor++;
			}

			$html .='
			</tbody>
		</table>

	</div>

	<div class="col-xs-5" style="border:0px solid red;">

		<table  class="tbl_pd" border="0">
			<thead>
				<tr style="padding-top:10px;">
					<th style="width:4%;text-align:center;padding-top:5px;padding-bottom:5px;">No</th>
					<th style="width:7%;text-align:left;" >Kode</th>
					<th>Uraian</th>
					<th style="text-align:right;width:5%;padding-right:3px;">Vol</th>
					<th style="text-align:left;width:5%;padding-left:3px;" >Sat</th>
					<th style="text-align:right;width:12%;padding-right:3px;">Harga (Rp)</th>
					<th style="text-align:right;width:12%;padding-right:3px;">Jumlah (Rp)</th>
				</tr>
			</thead>
			<tbody>';

				$nomor=1;
				foreach($detailPD as $mydetail){
					$html .='
					<tr>
						<td style="width:4%;text-align:center;padding-top:10px;padding-bottom:10px;">'.$mydetail->NoUrut.'</td>
						<td>'.$mydetail->KdRAP.'</td>
						<td>'.$mydetail->Uraian.'</td>
						<td style="text-align:right;width:5%;padding-right:3px;">'.UbahGabunganUang($mydetail->Vol).'</td>
						<td style="text-align:left;width:5%;padding-left:3px;" >'.$mydetail->Uom.'</td>
						<td style="text-align:right;width:12%;padding-right:3px;">'.UbahGabunganUang($mydetail->HrgSatuan).'</td>
						<td style="text-align:right;width:12%;padding-right:3px;">'.UbahGabunganUang($mydetail->JumlahHarga).'</td>
					</tr>';
					$nomor++;
				}

			$html .='
			</tbody>
		</table>


	</div>

</div><br></br>';

$html .='
<div class="col-xs-6" style="border:0px solid green;">
	
	<table  class="tbl_pd_bawah" border="0">
		<tr>
			<td style="width:11%;">Keterangan :</td>
			<td style="border-bottom:1px solid;">'.$dataPD->Keterangan.'</td>
			<td width="2%"></td>
			<td style="width:11%;">Sub Total :</td>
			<td style="border-bottom:1px solid;text-align:right;">'.$dataPD->Keterangan.'</td>
		</tr>
	</table>

	<table  class="tbl_pd_bawah" border="0">
		<tr>
			<td style="width:11%;"></td>
			<td style="border-bottom:1px solid;text-align:right;">Saldo Sebelumnya dari No. </td>
			<td width="2%"></td>
			<td style="width:11%;">Sub Total :</td>
			<td style="border-bottom:1px solid;text-align:right;">'.$dataPD->Keterangan.'</td>
		</tr>
	</table>

</div>

<div class="col-xs-5" style="border:0px solid red;">

</div>';

$mpdf->WriteHTML($html);

$mpdf->Output('tes.pdf', 'I');