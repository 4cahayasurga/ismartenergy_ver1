
<div class="row">
	<div class="col-xs-12" >
		<div class="box">
			

			<div class="table-responsive" style="margin-left:20px !important;margin-right:20px !important;">
				<table  class="table" style="border-collapse: collapse;"  >
					<thead>
						<tr>
							<th colspan="4" style="border-bottom:0px solid black;padding-top:10px;padding-bottom:10px;">
								<!-- 			<button class="btn btn-danger btn-sm" data-JobNo="<?=$JobNo?>" data-Tahun="<?=$Tahun?>"  id="mybtn_termin" >Export PDF <i class="fa fa-print"></i></button> -->
								<a href="<?=site_url('MyReport/Operasi/QueryTermin_Report/QTerminPDF/'.$JobNo.'/'.$Tahun.'')?>" class="btn btn-danger btn-sm" target="_blank" >Export PDF <i class="fa fa-print"></i></a>
							</th>
							<th colspan="2" style="text-align:center;border-bottom:1px solid black;padding-top:5px;padding-bottom:5px;">Rencana</th>
							<th colspan="2" style="text-align:center;border-bottom:1px solid black;padding-top:5px;padding-bottom:5px;">Realisasi</th>
						</tr>
						<tr>
							<!-- <th rowspan="2" style="vertical-align: middle;">No.</th> -->
							<th rowspan="2" style="vertical-align:middle;text-align:center;padding-top:10px;padding-bottom:10px;border-bottom:1px solid black;">Job No</th>
							<th rowspan="2" style="vertical-align:middle;text-align:center;padding-top:10px;padding-bottom:10px;border-bottom:1px solid black;">Job Name</th>
							<th rowspan="2" style="vertical-align:middle;text-align:center;padding-top:10px;padding-bottom:10px;border-bottom:1px solid black;">Jenis</th>
							<th rowspan="2" style="vertical-align:middle;text-align:center;padding-top:10px;padding-bottom:10px;border-bottom:1px solid black;">Bulan</th>
							<th style="vertical-align:middle;text-align:center;padding-top:10px;padding-bottom:10px;border-bottom:1px solid black;">Persentase (%)</th>
							<th style="vertical-align:middle;text-align:center;padding-top:10px;padding-bottom:10px;border-bottom:1px solid black;">Netto (Rp)</th>
							<th style="vertical-align:middle;text-align:center;padding-top:10px;padding-bottom:10px;border-bottom:1px solid black;">Persentase (%)</th>
							<th style="vertical-align:middle;text-align:center;padding-top:10px;padding-bottom:10px;border-bottom:1px solid black;">Netto (Rp)</th>
						</tr>
					</thead>
					<tbody>
						<?php

						$no = 1;
						$html = '';
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


							//awal Termin jenis uang muka
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

							$Persentase1 = is_nan($Persentase1) ? 0 : $Persentase1;
							$Netto1 = is_nan($Netto1) ? 0 : $Netto1;



							$Persentase2 = is_nan($Persentase2) ? 0 : $Persentase2;
							$Netto2 = is_nan($Netto2) ? 0 : $Netto2;

							$warna_persentase1_header = '';
							$warna_netto1_header = '';

							$warna_persentase2_header = '';
							$warna_netto2_header = '';

							if ($Persentase1 < 0 ) {
								$warna_persentase1_header = 'class="text-red"';
							}else if($Persentase1 == 0){
								$warna_persentase1_header = 'class="text-green"';
							}else{
								$warna_persentase1_header = 'class="text-yellow"';
							}

							if ($Netto1 < 0 ) {
								$warna_netto1_header = 'class="text-red"';
							}else if($Netto1 == 0){
								$warna_netto1_header = 'class="text-green"';
							}else{
								$warna_netto1_header = 'class="text-yellow"';
							}

							if ($Persentase2 < 0 ) {
								$warna_persentase2_header = 'class="text-red"';
							}else if($Persentase2 == 0){
								$warna_persentase2_header = 'class="text-green"';
							}else{
								$warna_persentase2_header = 'class="text-yellow"';
							}

							if ($Netto2 < 0 ) {
								$warna_netto2_header = 'class="text-red"';
							}else if($Netto2 == 0){
								$warna_netto2_header = 'class="text-green"';
							}else{
								$warna_netto2_header = 'class="text-yellow"';
							}

							$html .= '
							<tr>
							<td style="padding-top:10px;padding-bottom:10px;">'.$JobNo.'</td>
							<td>'.$data['JobNm'].'</td>
							<td>'.$Jenis.'</td>
							<td>'.BulanIndonesia($Counter).'</td>
							<td style="text-align: right;" '.$warna_persentase1_header.'>'.new_decimal($Persentase1).'</td>
							<td style="text-align: right;" '.$warna_netto1_header.'>'.new_decimal($Netto1).'</td>
							<td style="text-align: right;" '.$warna_persentase2_header.' >'.new_decimal($Persentase2).'</td>
							<td style="text-align: right;" '.$warna_netto2_header.'>'.uang_koma_genap_decimal($Netto2).'</td>
							</tr>';

							//akhir Termin jenis uang muka


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

								$Persentase1 = is_nan($Persentase1) ? 0 : $Persentase1;
								$Netto1 = is_nan($Netto1) ? 0 : $Netto1;

								$Persentase2 = is_nan($Persentase2) ? 0 : $Persentase2;
								$Netto2 = is_nan($Netto2) ? 0 : $Netto2;
								// $warna_text_kalau_tidak_nol = ($Persentase2 == 0) ? 'class="text-red"' : 'class="text-green"';



								$warna_persentase1_kaki = '';
								$warna_netto1_kaki = '';

								$warna_persentase2_kaki = '';
								$warna_netto2_kaki = '';

								if ($Persentase1 < 0 ) {
									$warna_persentase1_kaki = 'class="text-red"';
								}else if($Persentase1 == 0){
									$warna_persentase1_kaki = 'class="text-green"';
								}else{
									$warna_persentase1_kaki = 'class="text-yellow"';
								}

								if ($Netto1 < 0 ) {
									$warna_netto1_kaki = 'class="text-red"';
								}else if($Netto1 == 0){
									$warna_netto1_kaki = 'class="text-green"';
								}else{
									$warna_netto1_kaki = 'class="text-yellow"';
								}

								if ($Persentase2 < 0 ) {
									$warna_persentase2_kaki = 'class="text-red"';
								}else if($Persentase2 == 0){
									$warna_persentase2_kaki = 'class="text-green"';
								}else{
									$warna_persentase2_kaki = 'class="text-yellow"';
								}

								if ($Netto2 < 0 ) {
									$warna_netto2_kaki = 'class="text-red"';
								}else if($Netto2 == 0){
									$warna_netto2_kaki = 'class="text-green"';
								}else{
									$warna_netto2_kaki = 'class="text-yellow"';
								}

								$html .= '
								<tr>
								<td style="padding-top:10px;padding-bottom:10px;">'.$JobNo.'</td>
								<td>'.$data['JobNm'].'</td>
								<td>'.$Jenis.'</td>
								<td >'.BulanIndonesia($Counter).'</td>
								<td style="text-align: right;" '.$warna_persentase1_kaki.' >'.new_decimal($Persentase1).'</td>
								<td style="text-align: right;" '.$warna_netto1_kaki.' >'.uang_koma_genap_decimal($Netto1).'</td>
								<td style="text-align: right;" '.$warna_persentase2_kaki.'>'.new_decimal($Persentase2).'</td>
								<td style="text-align: right;" '.$warna_netto2_kaki.' >'.uang_koma_genap_decimal($Netto2).'</td>
								</tr>';

							}

							echo $html;

						}

						?>
						
					</tbody>
					<tfoot>
						<?php
						$SUM_Persentase1 = is_nan($SUM_Persentase1) ? 0 : $SUM_Persentase1;
						$SUM_Netto1 = is_nan($SUM_Netto1) ? 0 : $SUM_Netto1;

						$SUM_Persentase2 = is_nan($SUM_Persentase2) ? 0 : $SUM_Persentase2;
						$SUM_Netto2 = is_nan($SUM_Netto2) ? 0 : $SUM_Netto2;

						$warna_sum_persentase1 = '';
						$warna_sum_netto1 = '';

						$warna_sum_persentase2 = '';
						$warna_sum_netto2 = '';



						if ($SUM_Persentase1 < 0 ) {
							$warna_sum_persentase1 = 'class="text-red"';
						}else if($SUM_Persentase1 == 0){
							$warna_sum_persentase1 = 'class="text-green"';
						}else{
							$warna_sum_persentase1 = 'class="text-yellow"';
						}

						if ($SUM_Netto1 < 0 ) {
							$warna_sum_netto1 = 'class="text-red"';
						}else if($SUM_Netto1 == 0){
							$warna_sum_netto1 = 'class="text-green"';
						}else{
							$warna_sum_netto1 = 'class="text-yellow"';
						}

						if ($SUM_Persentase2 < 0 ) {
							$warna_sum_persentase2 = 'class="text-red"';
						}else if($SUM_Persentase2 == 0){
							$warna_sum_persentase2 = 'class="text-green"';
						}else{
							$warna_sum_persentase2 = 'class="text-yellow"';
						}


						if ($SUM_Netto2 < 0 ) {
							$warna_sum_netto2 = 'class="text-red"';
						}else if($SUM_Netto2 == 0){
							$warna_sum_netto2 = 'class="text-green"';
						}else{
							$warna_sum_netto2 = 'class="text-yellow"';
						}


						?>
						<tr>
							<th colspan="4" style="border-top:1px solid black;"></th>

							<th style="border-top:1px solid black;text-align: right;padding-top:10px;padding-bottom:10px;" <?=$warna_sum_persentase1?> ><?='Sum = '.new_decimal($SUM_Persentase1)?></th>

							<th style="border-top:1px solid black;text-align: right;" <?=$warna_sum_netto1?>  ><?='Sum = '.uang_koma_genap_decimal($SUM_Netto1)?></th>

							<th style="border-top:1px solid black;text-align: right;" <?=$warna_sum_persentase2?>  ><?='Sum = '.new_decimal($SUM_Persentase2)?></th>

							<th style="border-top:1px solid black;text-align: right;" <?=$warna_sum_netto2?> ><?='Sum = '.uang_koma_genap_decimal($SUM_Netto2)?></th>
						</tr>
						
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('#mybtn_termin').click(function(){
			var JobNo = $(this).attr('data-JobNo');
			var Tahun = $(this).attr('data-Tahun');

			alert(JobNo+Tahun);
		});
	})
</script>
