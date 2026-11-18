<style>
	#table_saya{
		font-size: 12px;
		font-family: sans-serif;
		white-space: nowrap;
	}

	#table_saya thead tr th{
		padding-top: 10px;
		padding-bottom: 10px;
	}

	#table_saya tbody tr td{
		padding-top: 10px;
		padding-bottom: 10px;
		text-decoration: underline;
		/*font-weight: bold;*/
	}

	#table_saya tfoot tr:first-child td{
		padding-top: 15px;
		padding-bottom: 10px;
		font-weight: bold;
		text-decoration: underline;
	}
	#table_saya tfoot tr:nth-of-type(2) th{
		padding-top: 15px;
		padding-bottom: 10px;
		font-weight: bold;
		text-decoration: underline;
	}

</style>

<div class="col-12 ">

	<div class="box box-primary">

		<div class="box-body">

			<div class="table-responsive">

				<table width="100%" border="0">
					<tr>
						<td>
							<a href="<?=site_url('Laporan/Procurement/Rekap_Hutang_KO/PdfExport_HutangKO/'.$JobNo.'/'.$DariTgl.'/'.$SampaiTgl)?>" class="btn btn-sm btn-danger" target="_blank" >PDF <i class="fa fa-print"></i></a>

							<a href="<?=site_url('Laporan/Procurement/Rekap_Hutang_KO/ExcelHutangKO/'.$JobNo.'/'.$DariTgl.'/'.$SampaiTgl)?>" class="btn btn-sm btn-success" target="_blank" >Excel <i class="fa fa-upload"></i></a>

							<p></p>
						</td>
					</tr>
				</table>

				<table id="table_saya"  class="table table-striped table-bordered table-condensed table-hover" border="1" width="100%" data-JobNo="<?= $JobNo ?>" data-DariTgl="<?= $DariTgl ?>" data-SampaiTgl="<?= $SampaiTgl ?>" data-ordering="false">
					<thead >
						<tr style="background-color:#34495e;color:white;">
							<th style="text-align:center;" colspan="5"></th>
							

							<!-- RAP -->
							<th style="text-align:center;" colspan="2">RAP</th>

							<!-- Total KO dlm periode ini -->
							<th style="text-align:center;" colspan="2">Total KO dlm Periode</th>

							<!-- Total KO -->
							<th colspan="2" style="text-align:center;">Total KO</th>

							<!-- Total Pembayaran KO dlm periode ini -->
							<th colspan="2" style="text-align:center;">Total Pembayaran KO dlm periode ini</th>

							<!-- Total Pembayaran KO -->
							<th colspan="2" style="text-align:center;">Total Pembayaran KO</th>

							<!-- Saldo -->
							<th colspan="2" style="text-align:center;">Saldo KO</th>

							<th style="text-align:center;"></th>
						</tr>


						<tr style="background-color:#7f8c8d;color:white;">
							<td style="text-align:center;">No.</td>
							<td style="text-align:center;">Job.No</td>
							<td style="text-align:center;">Job Nm</td>
							<td style="text-align:center;">Tanggal PHO</td>
							<td style="text-align:center;">Netto Porsi Kontrak (Rp)</td>
							

							<!-- RAP -->
							<td style="text-align:center;">Rp</td>
							<td style="text-align:center;">% thd netto kontrak</td>

							<!-- Total KO dlm periode ini -->
							<td style="text-align:center;">Rp</td>
							<td style="text-align:center;">% thd RAP</td>

							<!-- Total KO -->
							<td style="text-align:center;">Rp</td>
							<td style="text-align:center;">% thd RAP</td>

							<!-- Total Pembayaran KO dlm periode ini -->
							<td style="text-align:center;">Rp</td>
							<td style="text-align:center;">% thd RAP</td>

							<!-- Total Pembayaran KO -->
							<td style="text-align:center;">Rp</td>
							<td>% thd RAP</td>

							<!-- Saldo -->
							<td style="text-align:center;">Rp</td>
							<td style="text-align:center;">% thd RAP</td>

							<td style="text-align:center;">Total Hutang Invoice (Rp)</td>
						</tr>
					</thead>
					
					<tbody>
						<?php
						$nomor = 1;
						$SubTotal = 0;

						$SubTotal_RAP = 0;
						$SubTotal_RAP_Persen = 0;

						$SubTotal_KO2 = 0;
						$SubTotal_KO2_Persen = 0;

						$SubTotal_KO1 = 0;
						$SubTotal_KO1_Persen = 0;

						$SubTotal_BLE1 = 0;
						$SubTotal_BLE1_Persen = 0;

						$SubTotal_BLE2 = 0;
						$SubTotal_BLE2_Persen = 0;

						$SubTotal_SaldoKO = 0;
						$SubTotal_SaldoKO_Persen = 0;

						$SubTotal_Invoice2 = 0;


						$GrandTotal = 0;
						$GrandTotal_RAP = 0;
						$GrandTotal_KO2 = 0;
						$GrandTotal_KO1 = 0;
						$GrandTotal_BLE1 = 0;
						$GrandTotal_BLE2 = 0;
						$GrandTotal_Invoice2 = 0;
						$GrandTotal_SaldoKO = 0;




						foreach($dataHutangKO as $dataHutangKO){
							
							$SubTotal += $dataHutangKO['NettKontrak'];
							

							$SubTotal_RAP += $dataHutangKO['RAP'];
							$SubTotal_RAP_Persen += $dataHutangKO['RAP_Persen'];
							

							$SubTotal_KO1 += $dataHutangKO['KO1'];
							$SubTotal_KO1_Persen += $dataHutangKO['KO1_Persen'];

							$SubTotal_KO2 += $dataHutangKO['KO2'];
							$SubTotal_KO2_Persen += $dataHutangKO['KO2_Persen'];
							
							
							$SubTotal_BLE1 += $dataHutangKO['BLE1'];
							$SubTotal_BLE1_Persen += $dataHutangKO['BLE1_Persen'];

							$SubTotal_BLE2 += $dataHutangKO['BLE2'];
							$SubTotal_BLE2_Persen += $dataHutangKO['BLE2_Persen'];

							$SubTotal_SaldoKO += $dataHutangKO['SaldoKO'];
							$SubTotal_SaldoKO_Persen += $dataHutangKO['SaldoKO_Persen'];

							$SubTotal_Invoice2 += $dataHutangKO['Invoice2'];


							$GrandTotal += $SubTotal;
							$GrandTotal_RAP += $SubTotal_RAP;
							$GrandTotal_KO1 += $SubTotal_KO1;
							$GrandTotal_KO2 += $SubTotal_KO2;
							$GrandTotal_BLE1 += $SubTotal_BLE1;
							$GrandTotal_BLE2 += $SubTotal_BLE2;
							$GrandTotal_SaldoKO += $SubTotal_SaldoKO;
							$GrandTotal_Invoice2 += $SubTotal_Invoice2;

							?>

							<tr>
								<td style="border:0px solid black;font-size: 14px !important;text-decoration: none;" colspan="18" class="text-bold">TAHUN <?=date('Y',strtotime($dataHutangKO['TglPHO']));?></td>
							</tr>
							<tr>
								<td style="text-decoration: none;"><?=$nomor++;?></td>
								<td style="text-decoration: none;"><?=$dataHutangKO['JobNo']?></td>
								<td style="text-decoration: none;"><?=$dataHutangKO['JobNm']?></td>
								<td style="text-decoration: none;"><?=$dataHutangKO['TglPHO']?></td>
								<td style="text-align:right;"><?=number_format($dataHutangKO['NettKontrak'])?></td>
								<td style="text-align:right;" ><?=number_format($dataHutangKO['RAP'])?></td>
								<td style="text-align:right;" ><?=uang_koma_genap_decimal_kedua($dataHutangKO['RAP_Persen']).'%'?></td>
								<td style="text-align:right;" ><?=number_format($dataHutangKO['KO2'])?></td>
								<td style="text-align:right;" ><?=uang_koma_genap_decimal_kedua($dataHutangKO['KO2_Persen']).'%'?></td>
								<td style="text-align:right;" ><?=number_format($dataHutangKO['KO1'])?></td>
								<td style="text-align:right;" ><?=uang_koma_genap_decimal_kedua($dataHutangKO['KO1_Persen']).'%'?></td>
								<td style="text-align:right;" ><?=number_format($dataHutangKO['BLE2'])?></td>
								<td style="text-align:right;" ><?=uang_koma_genap_decimal_kedua($dataHutangKO['BLE2_Persen']).'%'?></td>
								<td style="text-align:right;" ><?=number_format($dataHutangKO['BLE1'])?></td>
								<td style="text-align:right;" ><?=uang_koma_genap_decimal_kedua($dataHutangKO['BLE1_Persen']).'%'?></td>
								<td style="text-align:right;" ><?=number_format($dataHutangKO['SaldoKO'])?></td>
								<td style="text-align:right;" ><?=uang_koma_genap_decimal_kedua($dataHutangKO['SaldoKO_Persen']).'%'	?></td>
								<td style="text-align:right;" ><?=number_format($dataHutangKO['Invoice2'])?></td>

							</tr>
						<?php } ?>

					</tbody>
					<tfoot>
						<tr>
							<td colspan="4" class="text-right">SUBTOTAL</td>
							<td class="text-right"><?=number_format($SubTotal)?></td>

							<!-- //Sub Total RAP -->
							<td class="text-right"><?=number_format($SubTotal_RAP)?></td>
							<td class="text-right"></td>

							<!-- //SubTotal KO2 -->
							<td class="text-right"><?=number_format($SubTotal_KO2)?></td>
							<td class="text-right"></td>

							<!-- //SubTotal KO1 -->
							<td class="text-right"><?=number_format($SubTotal_KO1)?></td>
							<td class="text-right"></td>

							<!-- //SubTotal BLE2 -->
							<td class="text-right"><?=number_format($SubTotal_BLE2)?></td>
							<td class="text-right"></td>

							<!-- //SubTotal BLE1 -->
							<td class="text-right"><?=number_format($SubTotal_BLE1)?></td>
							<td class="text-right"></td>

							<!-- //SubTotal SaldoKO  -->
							<td class="text-right"><?=number_format($SubTotal_SaldoKO)?></td>
							<td class="text-right"></td>

							<td class="text-right"><?=number_format($SubTotal_Invoice2)?></td>

						</tr>

						<tr>
							<th colspan="4" class="text-right">GRAND TOTAL</th>
							<th class="text-right"><?=number_format($GrandTotal)?></th>

							<!-- //Sub Total RAP -->
							<th class="text-right"><?=number_format($GrandTotal_RAP)?></th>
							<th class="text-right"></th>

							<!-- //GrandTotal KO2 -->
							<th class="text-right"><?=number_format($GrandTotal_KO2)?></th>
							<th class="text-right"></th>

							<!-- //GrandTotal KO1 -->
							<th class="text-right"><?=number_format($GrandTotal_KO1)?></th>
							<th class="text-right"></th>

							<!-- //GrandTotal BLE2 -->
							<th class="text-right"><?=number_format($GrandTotal_BLE2)?></th>
							<th class="text-right"></th>

							<!-- //GrandTotal BLE1 -->
							<th class="text-right"><?=number_format($GrandTotal_BLE1)?></th>
							<th class="text-right"></th>

							<!-- //GrandTotal SaldoKO  -->
							<th class="text-right"><?=number_format($GrandTotal_SaldoKO)?></th>
							<th class="text-right"></th>

							<th class="text-right"><?=number_format($GrandTotal_Invoice2)?></th>
						</tr>
					</tfoot>

					

				</table>

			</div>


		</div>

		<div class="overlay dark loading_table" style="display: none;">
			<i class="fa fa-refresh fa-spin"></i>
		</div>

	</div>

</div>



<script>

	$(document).ready(function(){
		// $('#table_saya').DataTable();
	});


</script>