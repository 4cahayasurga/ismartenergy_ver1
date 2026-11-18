
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />

	<title>Rekap OutStanding / Approved PD</title>
	<style type="text/css">

		@page {
			/*margin: 2cm;*/
			margin-top: 0 auto;
		}

		body {
			font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
			margin: 0.5cm 0;
			/*text-align: justify;*/
		}

		#header,
		#footer {
			position: fixed;
			left: 0;
			right: 0;
			color: #aaa;
			font-size: 0.9em;
		}

		#header {
			top: 0;
			border-bottom: 0.1pt solid #aaa;
		}

		#footer {
			bottom: 0;
			border-top: 0.1pt solid #aaa;
		}

		#header table,
		#footer table {
			width: 100%;
			border-collapse: collapse;
			border: none;
		}

		#header td,
		#footer td {
			padding: 0;
			width: 50%;
		}

		.page-number {

			text-align: center;
		}

		.page-number:before {

			content: "Page " counter(page);
		}

		hr {
			page-break-after: always;
			border: 0;
		}

		#tbl_saya thead th{
			padding-top: 5px;
			padding-bottom: 5px;
			padding-left: 2px;
			padding-right: 2px;
		}

		#tbl_saya tbody td{
			padding-top: 5px;
			padding-bottom: 5px;
			padding-left: 3px;
		}

	</style>

</head>

<body>

	<div id="header">
		<table>
			<tr>
				<td style="font-size: 14px !important;">REKAP OUTSTANDING / APPROVED PDF</td>
				<td style="text-align: right;font-size: 8px !important;"><?=$printed_by?></td>
			</tr>
		</table>
	</div>

	<div id="footer" style="font-size:10px !important;">
		<div class="page-number"></div>
	</div>
	
	<p></p>
	<table width="100%" border="0" style="border-collapse:collapse;font-size: 12px !important;padding-top: 10px !important;">
		<tr> 
			<td width="100px" style="padding-top:3px !important;">Periode</td>
			<td width="5px" style="text-align:center;padding-top:3px !important;">:</td>
			<td width="300px" style="padding-left:5px;padding-top:3px !important;"><?=date('d-M-Y',strtotime($PrdAwal))?> s.d <?=$PrdAkhir?></td>
			<td></td>
		</tr>
		<tr style="padding-top:5px !important;">
			<td width="100px" style="padding-top:3px !important;">By</td>
			<td width="5px" style="text-align:center;padding-top:3px !important;">:</td>
			<td width="300px" style="padding-left:5px;padding-top:3px !important;"><?=($By == 'PC') ? 'Project Control' : 'Keuangan'  ?></td>
			<td></td>
		</tr>
		<tr>
			<td width="100px" style="padding-top:3px !important;">Type</td>
			<td width="5px" style="text-align:center;padding-top:3px !important;">:</td>
			<td width="300px" style="padding-left:5px;padding-top:3px !important;"><?=$Type?></td>
			<td></td>
		</tr>
	</table>

	<table id="tbl_saya" width="100%" border="1" style="border-collapse:collapse;font-size: 10px !important;margin-right: 1.3cm;padding-top: 10px !important;">
		<thead>
			<tr>
				<th style="text-align:left;width: 50px !important;">Job No</th>
				<th style="text-align:left;width: 200px !important;">Job Name</th>
				<th style="text-align:left;width: 70px !important;">No PD</th>
				<th style="text-align:center;width: 15px !important;">Alokasi</th>
				<th style="text-align:center;width: 50px !important;">Tipe Form</th>
				<th style="text-align:left;width: 120px !important;">Atas Nama</th>

				<th style="text-align:left;width: 120px !important;">Rekening Penerima</th>
				<th style="text-align:left;width: 45px !important;">No KO</th>
				<th style="text-align:left;width: 80px !important;text-align:right;">Total PD (Rp)</th>
				<th style="text-align:left;width: 70px !important;">Tgl PD</th>
				<th style="text-align:left;width: 70px !important;">Jth Tempo</th>
				<th style="text-align:left;width: ;">Dekripsi</th>
				<th style="text-align:left;width: 100px !important;">Approved By</th>

			</tr>
		</thead>
		<tbody>
			<?php 
			$TtlTrf = 0;
			$Saldo = 0;
			$sum_TotalPD = 0;
			foreach($data_outstanding_pc as $mydata) : 

				if ($By == 'KEU' && $Type == 'OutStanding' && ($mydata->Alokasi == 'B' || $mydata->Alokasi == 'C' || $mydata->Alokasi == 'L'  ) ) {

					if ($mydata->ApprovedByKT  == NULL || $mydata->ApprovedByKT == '' ) {
						continue;
					}	

				}

				if (!in_array($mydata->Alokasi, $arrayAlokasi)) {
					continue;
				}

				if (!in_array($mydata->JobNo1, $arrayJob)) {
					continue;
				}

				// cari saldo
				if (  ($mydata->TglPD >= '12/12/19' || $mydata->TglPD >= '2019-12-12') || ($mydata->Alokasi <> 'B'  && $mydata->Alokasi <> 'C') ) {

					//query_no_pj_Sebelumnya
					$query_NoPJSebelumnya = $this->db->query("SELECT NoPJSebelumnya FROM PdHdr WHERE NoPD='$mydata->NoPD' ");
					if ($query_NoPJSebelumnya->num_rows() > 0) {
						$row = $query_NoPJSebelumnya->row();
						if ($row->NoPJSebelumnya == NULL || $row->NoPJSebelumnya == '' ) {
							$Saldo = 0;
						}
					}else{
						$query_saldo = $this->db->query("SELECT Saldo FROM PdHdr WHERE NoPJ='$mydata->NoPJSebelumnya' ");
						if ($query_saldo->num_rows() > 0 ) {
							$row_saldo = $query_saldo->row();
							$Saldo = $row_saldo->Saldo;
						}
					}

				}else{
					$ceksaldo_kedua = $this->db->query("
						SELECT TOP 1 NoPJ,Saldo 
						FROM PdHdr 
						WHERE NoPD<'$mydata->NoPD' AND JobNo='$mydata->JobNo' AND 
						TipeForm='$mydata->TipeForm' AND KSO='$mydata->KSO' AND RejectBy IS NULL 
						ORDER BY NoPD DESC	");
					if ($ceksaldo_kedua->num_rows() > 0 ) {
						$row_cek_saldo = $ceksaldo_kedua->row();
						$Saldo = $row_cek_saldo->Saldo;
					}
				}

				$TtlTrf = $mydata->TotalPD - $Saldo;

				$sum_TotalPD += $TtlTrf;

				?>
				<tr>
					<td style="text-align:left;"><?=$mydata->JobNo1?></td>
					<td><?=$mydata->JobNm.' - '.$mydata->CompanyId?></td>
					<td><?=$mydata->NoPD?></td>
					<td style="text-align:center;" ><?=$mydata->Alokasi?></td>
					<td style="text-align:center;"><?=$mydata->TipeForm?></td>
					<td><?=$mydata->AtasNama?></td>

					<td><?=$mydata->NoRek?></td>
					<td><?=$mydata->NoKO?></td>
					<td style="text-align:right;"><?=uang_koma_genap_decimal($TtlTrf)?></td>
					<td><?=date('d-M-Y',strtotime($mydata->TglPD))?></td>
					<td><?=date('d-M-Y',strtotime($mydata->DueDate))?></td>
					<td><?=$mydata->Deskripsi?></td>
					<td><?=$mydata->ApprovedBy?></td>


				<?php endforeach; ?>
			</tbody>
		</table>



	</body>
	</html>
