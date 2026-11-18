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
$stylesheet_baru = file_get_contents('mycss/css_QueryTermin.css');

$mpdf->SetTitle('Query Termin (Report)  ');
$mpdf->use_kwt = true;

$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_baru, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('report_view/operasi/query_termin/pdf_header', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('report_view/operasi/query_termin/pdf_footer', NULL, TRUE));

$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;


$html = '';

$html .= '
<table width="100%" border="0" id="table_QHutangKO">

		<tr>
			<th colspan="4" style="border-bottom:0px solid black;"></th>
			<th colspan="2" style="text-align:center;border-bottom:1px solid black;padding-top:5px;padding-bottom:5px;">Rencana</th>
			<th colspan="2" style="text-align:center;border-bottom:1px solid black;padding-top:5px;padding-bottom:5px;">Realisasi</th>
		</tr>
		<tr >

			<th style="vertical-align:middle;text-align:center;padding-top:10px;padding-bottom:10px;border-bottom:1px solid black;">Job No</th>
			<th style="vertical-align:middle;text-align:center;padding-top:10px;padding-bottom:10px;border-bottom:1px solid black;">Job Name</th>
			<th  style="vertical-align:middle;text-align:center;border-bottom:1px solid black;">Jenis</th>
			<th  style="vertical-align:middle;text-align:center;border-bottom:1px solid black;">Bulan</th>
			<th style="vertical-align:middle;text-align:center;border-bottom:1px solid black;">Persentase (%)</th>
			<th style="vertical-align:middle;text-align:center;border-bottom:1px solid black;">Netto (Rp)</th>
			<th style="vertical-align:middle;text-align:center;border-bottom:1px solid black;">Persentase (%)</th>
			<th style="vertical-align:middle;text-align:center;border-bottom:1px solid black;">Netto (Rp)</th>
		</tr>
		';
		$Bruto = 0;

		$TglSatu = $Tahun.'-01-01';
		$TglDua = $Tahun.'-12-31';


		$TglBuatan = '';
		$PrdAwal = '';
		$PrdAkhir = '';


		$Persentase1 = 0;
		$Netto1 = 0;
		$Persentase2 = 0;
		$Netto2 = 0;

		$Jenis = '';
		$Counter = 0;

		$SUM_Persentase1=0;
		$SUM_Netto1=0;
		$SUM_Persentase2=0;
		$SUM_Netto2=0;

		$query = $this->db->query("SELECT JobNo, JobNm, Bruto, Netto FROM Job WHERE JobNo IN ('$JobNo') AND StatusJob='Pelaksanaan' ORDER BY JobNo")->result_array();

		foreach($query as $data) {
			$Bruto = $data['Netto'];

			$Jenis = 'Uang Muka';
		$queryRencana = $this->db->query("
			SELECT Persentase, Bruto, TglRencana 
			FROM RencanaTermin 
			WHERE JobNo='$JobNo' AND 
			TglRencana>='$TglSatu' AND 
			TglRencana<='$TglDua' AND 
			Jenis='UM' ");

		if ($queryRencana->num_rows() > 0 ) {
		
			$myrow = $queryRencana->row();
			$Persentase1 = $myrow->Persentase;
			$Netto1 = $myrow->Bruto;
			$TglBuatan = $myrow->TglRencana;
			$Counter = date('m',strtotime($myrow->TglRencana));

		}


		$querRealisasi = $this->db->query("SELECT ISNULL(SUM(TerminInduk),0) as 'TotalTermin' FROM TerminInduk WHERE JobNo='$JobNo' AND TglCair>='$TglSatu' AND TglCair<='$TglDua' AND Jenis='UM' ");

		if ($querRealisasi->num_rows() > 0 ) {
			$rowRealisasi = $querRealisasi->row_array();
			$Persentase2 = ($rowRealisasi['TotalTermin'] / $Bruto) * 100;
			$Netto2 = $rowRealisasi['TotalTermin'];
		}

		$SUM_Persentase1 += $Persentase1;
		$SUM_Netto1 += $Netto1;
		$SUM_Persentase2 += $Persentase2;
		$SUM_Netto2 += $Netto2;

			$html .= '
			<tr>
				<td style="padding-top:10px;padding-bottom:10px;">'.$JobNo.'</td>
				<td>'.$JobNm.'</td>
				<td>'.$Jenis.'</td>
				<td>'.BulanIndonesia($Counter).'</td>
				<td style="vertical-align:middle;text-align:right;">'.new_decimal($Persentase1).'</td>
				<td style="vertical-align:middle;text-align:right;">'.new_decimal($Netto1).'</td>
				<td style="vertical-align:middle;text-align:right;">'.new_decimal($Persentase2).'</td>
				<td style="vertical-align:middle;text-align:right;">'.new_decimal($Netto2).'</td>
			</tr>';


			//awal Termin jenis termin
			$Persentase1 = 0;
			$Netto1 = 0;
			$Persentase2 = 0;
			$Netto2 = 0;
			$Counter = 0;
			$Jenis = 'Termin';


			for ($Counter = 1; $Counter <= 12; $Counter++) {

				$PrdAwal = $Tahun.'-'.$Counter.'-01';
				$PrdAkhir = lastOfMonth($Tahun,$Counter);

				$queryRencanaTermin = $this->db->query("
					SELECT Persentase, Netto 
					FROM RencanaTermin 
					WHERE JobNo='$JobNo' AND
					TglRencana>='$PrdAwal' AND TglRencana<='$PrdAkhir' AND Jenis='Termin' ");

				if ($queryRencanaTermin->num_rows() > 0 ) {

					$dataTermin = $queryRencanaTermin->row_array();
					$Persentase1 = $dataTermin['Persentase'];
					$Netto1 = $dataTermin['Netto'];
				}else{
					$Persentase1 = 0;
					$Netto1 = 0;
				}

				$queryRealisasiTermin = $this->db->query("
					SELECT ISNULL(SUM(TerminInduk),0) AS 'TotalTermin' FROM TerminInduk WHERE JobNo='$JobNo' AND TglCair>='$PrdAwal' AND TglCair<='$PrdAkhir' AND Jenis='Termin' ");

				if ($queryRealisasiTermin->num_rows() > 0 ) {
					$rowRealisasi = $queryRealisasiTermin->row_array();
					$Persentase2 = ($rowRealisasi['TotalTermin'] / $Bruto) * 100;
					$Netto2 = $rowRealisasi['TotalTermin'];
				}

				$SUM_Persentase1 += $Persentase1;
							$SUM_Netto1 += $Netto1;
							$SUM_Persentase2 += $Persentase2;
							$SUM_Netto2 += $Netto2;


				$html .= '
				<tr>
					<td style="padding-top:10px;padding-bottom:10px;">'.$JobNo.'</td>
					<td>'.$JobNm.'</td>
					<td>'.$Jenis.'</td>
					<td>'.BulanIndonesia($Counter).'</td>
					<td style="vertical-align:middle;text-align:right;">'.new_decimal($Persentase1).'</td>
					<td style="vertical-align:middle;text-align:right;">'.new_decimal($Netto1).'</td>
					<td style="vertical-align:middle;text-align:right;">'.new_decimal($Persentase2).'</td>
					<td style="vertical-align:middle;text-align:right;">'.new_decimal($Netto2).'</td>
				</tr>';

			}

		}

    




$html .='
	<tfoot>
		<tr>
			<th colspan="4" style="border-top:1px solid black;"></th>
			<th style="border-top:1px solid black;text-align: right;">SUM='.new_decimal($SUM_Persentase1).'</th>
			<th style="border-top:1px solid black;text-align: right;">SUM='.new_decimal($SUM_Netto1).'</th>
			<th style="border-top:1px solid black;text-align: right;">SUM='.new_decimal($SUM_Persentase2).'</th>
			<th style="border-top:1px solid black;text-align: right;">SUM='.new_decimal($SUM_Netto2).'</th>
		</tr>
	</tfoot>
</table>';

// exit;

$mpdf->WriteHTML($html);

// $mpdf->Output('REPORT - QUERY-TERMIN - ' . date('ymdHis') . '.pdf', 'I');
$mpdf->Output('REPORT - QUERY-TERMIN - ' . date('ymdHis') . '.pdf', 'D');