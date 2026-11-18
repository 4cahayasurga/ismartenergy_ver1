<div class="col-xs-12 huruf_8" style="text-align:right;border: 0px solid;" ><?= $gabung ?> page {PAGENO} of {nbpg}</div>

<u>PINDAH DANA</u>

<!-- <div class="col-xs-12 huruf_10" style="text-align:left;border: 1px solid;" > -->
	<table width="100%" border="0" style="border-collapse:collapse;" class="huruf_10 jarak">
		<tr>
			<td style="width: 10%;" class="huruf_10">No Ref : </td>
			<td style="border-bottom: 1px solid black;width: 20%;"><?=$mydata->LedgerNo?></td>
			<td></td>
		</tr>
	</table>
	<table width="100%" border="0" style="border-collapse:collapse;" class="huruf_10 jarak">
		<tr>
			<td style="width: 10%;padding-top:5px;" class="huruf_10">Tanggal : </td>
			<td style="border-bottom: 1px solid black;width: 20%;padding-top:5px;"><?=tgl_baru($mydata->TglBayar)?></td>
			<td></td>
		</tr>
	</table>
	<table width="100%" border="0" style="border-collapse:collapse;" class="huruf_10 jarak">
		<tr>
			<td style="width: 10%;padding-top:5px;" class="huruf_10">Job No : </td>
			<td style="border-bottom: 1px solid black;width: 40%;padding-top:5px;"><?=$mydata->JobNo.' - '.$mydata->JobNm?></td>
			<td></td>
		</tr>
	</table>
	<?php
	$Var_CaraBayar = '';
	if ($mydata->CaraBayar == 'TRF') {
		$Var_CaraBayar = 'TRANSFER';

	}

	if ($mydata->CaraBayar == 'CG') {
		$Var_CaraBayar = 'CEK/GIRO';
		$Var_CaraBayar .= ($mydata->NoCG !='') ? ' - '.$mydata->NoCG : '';
					// echo 'CEK/GIRO '.($mydata->NoCG == NULL || $mydata->NoCG == '') ? '' : ' - '.$mydata->NoCG;
	}
	if($mydata->CaraBayar == 'CASH'){
		$Var_CaraBayar = 'TUNAI';
		$Var_CaraBayar .= ($mydata->NmPenerimaTunai !='') ? ' - '.$mydata->NmPenerimaTunai : '';
					// echo 'TUNAI '.($mydata->NmPenerimaTunai == NULL || $mydata->NmPenerimaTunai == '') ? '' : ' - '.$mydata->NmPenerimaTunai;
	}

	?>
	<table width="100%" border="0" style="border-collapse:collapse;" class="huruf_10 jarak">
		<tr>
			<td style="width: 10%;padding-top:5px;" class="huruf_10">Via : </td>
			<td style="border-bottom: 1px solid black;width: 40%;padding-top:5px;"><?=$Var_CaraBayar?></td>
			<td></td>
		</tr>
	</table>
	<table width="100%" border="0" style="border-collapse:collapse;" class="huruf_10 jarak">
		<tr>
			<td style="width: 10%;padding-top:5px;" class="huruf_10">Nominal : </td>
			<td style="border-bottom: 1px solid black;width: 40%;padding-top:5px;"><?=number_format($mydata->Amount)?></td>
			<td></td>
		</tr>
	</table>
	<p></p>

	<div class="row">
		<div class="col-xs-5" style="border:1px solid black;">
			<table width="100%" border="0" style="border-collapse:collapse;" class="huruf_10 jarak">
				<tr style="padding-bottom: 10px;">
					<td style="border-bottom: 1px solid black;" colspan="2" class="huruf_12 ">Rekening Pengirim  </td>
				</tr>
				<tr>
					<td width="25%" class="huruf_10" style="padding-top: 10px;">No. Rekening : </td>
					<td style="border-bottom: 1px solid black;padding-top: 10px;"><?=($mydata->RekId = 0 ) ? '-' : $mydata->RekKirim ?></td>
				</tr>
				<tr>
					<td width="25%" class="huruf_10" style="padding-top: 10px;">Atas Nama : </td>
					<td style="border-bottom: 1px solid black;padding-top: 10px;"><?=($mydata->RekId = 0 ) ? '-' : $mydata->NamaKirim ?></td>
				</tr>
				<tr>
					<td width="25%" class="huruf_10" style="padding-top: 10px;">Bank : </td>
					<td style="border-bottom: 1px solid black;padding-top: 10px;"><?=($mydata->RekId = 0 ) ? '-' : $mydata->BankKirim ?></td>
				</tr>
				<tr>
					<td width="25%" class="huruf_10" style="padding-top: 10px;"></td>
					<td style="border-bottom: 0px solid black;padding-top: 10px;"></td>
				</tr>
			</table>
		</div>
		<div class="col-xs-5" style="border:1px solid black;">
			<table width="100%" border="0" style="border-collapse:collapse;" class="huruf_10 jarak">
				<tr>
					<td style="border-bottom: 1px solid black;" colspan="2" class="huruf_12">Rekening Penerima  </td>
				</tr>
				<tr>
					<td width="25%" class="huruf_10" style="padding-top: 10px;">No. Rekening : </td>
					<td style="border-bottom: 1px solid black;padding-top: 10px;"><?=($mydata->NoRek =='' || $mydata->NoRek ==NULL ) ? '-' : $mydata->NoRek ?></td>
				</tr>
				<tr>
					<td width="25%" class="huruf_10" style="padding-top: 10px;">Atas Nama : </td>
					<td style="border-bottom: 1px solid black;padding-top: 10px;"><?=($mydata->AtasNama =='' || $mydata->AtasNama ==NULL ) ? '-' : $mydata->AtasNama ?></td>
				</tr>
				<tr>
					<td width="25%" class="huruf_10" style="padding-top: 10px;">Bank : </td>
					<td style="border-bottom: 1px solid black;padding-top: 10px;"><?=($mydata->Bank =='' || $mydata->Bank ==NULL ) ? '-' : $mydata->Bank ?></td>
				</tr>
				<tr>
					<td width="25%" class="huruf_10" style="padding-top: 10px;"></td>
					<td style="border-bottom: 0px solid black;padding-top: 10px;"></td>
				</tr>

			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-5" style="border:0px solid black;">
			<table width="100%" border="0" style="border-collapse:collapse;" class="huruf_10 jarak">
				<tr>
					<td style="border-bottom: 0px solid black;padding-bottom: 10px;" colspan="2" class="huruf_10">Dibuat Oleh  </td>
					
					
				</tr>
				<tr>
					<td width="20%" style="border: 1px solid black;height: 30px;" class="huruf_10"></td>
					<td></td>
				</tr>

				
			</table>
		</div>
		<div class="col-xs-5" style="border:0px solid black;">
			<table width="100%" border="0" style="border-collapse:collapse;" class="huruf_10 jarak">
				<tr>
					<td style="border-bottom: 0px solid black;text-align: right;padding-bottom: 10px;" colspan="2" class="huruf_10">Diperiksa Oleh  </td>
					
					
				</tr>
				<tr>
					
					<td></td>
					<td width="20%" style="border: 1px solid black;height: 30px;" class="huruf_10"></td>
				</tr>
			</table>
		</div>
	</div>
	

	


<!-- </div> -->