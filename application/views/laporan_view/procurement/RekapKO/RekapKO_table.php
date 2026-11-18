
<style type="text/css">
	.jarak{
		padding-top: 10px;
		padding-bottom: 10px;
	}

	.jarak_TD{
		padding-top: 10px;
		padding-bottom: 10px;
	}
	.jarak_detail_ble{
		padding-top: 5px;
		padding-bottom: 5px;
	}
	.font_10{
		font-size: 10px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
	.font_12{
		font-size: 12px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
	.font_14{
		font-size: 14px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
	.font_16{
		font-size: 16px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
	.tbl_ble tr th{
		padding-top: 5px !important;
		padding-bottom: 5px !important;
		font-size: 10px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
	.tbl_ble tr td{
		padding-top: 5px !important;
		padding-bottom: 5px !important;
		font-size: 10px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
</style>
<div class="row">
	<div id="col-sm-12">
		<div class="box box-primary">
			<div class="box-header">
				<a href="<?=site_url('Laporan/Procurement/FrmRekapKO/RekapKO_ExportPDF/'.$JobNo.'/'.$DariTgl.'/'.$SampaiTgl.'/'.$AllPeriode)?>" class="btn btn-danger btn-md" target="_blank">Export PDF <i class="fa fa-print"></i></a>
			</div>
			<div class="box-body">

				<div class="table-responsive">
					
					<table width="100%" border="0" id="mytable" class="table table-striped table-hover table-condensed">

						<thead>
							<tr>
								<th class="jarak font_12" style="border-bottom:1px solid black;">No.</th>
								<th class="jarak font_12" style="border-bottom:1px solid black;" >No KO</th>
								<th class="jarak font_12" style="border-bottom:1px solid black;" >Tanggal</th>
								<th class="jarak font_12" style="border-bottom:1px solid black;" >Vendor</th>
								<th class="jarak font_12" style="border-bottom:1px solid black;" >Kategori</th>
								<th class="text-right jarak font_12" style="border-bottom:1px solid black;" >Nilai KO (Rp)</th>
								<th class="text-right jarak font_12" style="border-bottom:1px solid black;" >Telah Terbayar (Rp)</th>
								<th class="text-right jarak font_12" style="border-bottom:1px solid black;" >Sisa Pembayaran (Rp)*</th>
								<th class="jarak font_12" style="border-bottom:1px solid black;padding-left:5px !important;" >Status Closed</th>
							</tr>
						</thead>
						<tr>
							<th class="jarak font_12" style="border-bottom:0px solid black;"></th>
							<th class="jarak font_12" style="border-bottom:0px solid black;" ></th>
							<th class="jarak font_12" style="border-bottom:0px solid black;" ></th>
							<th class="jarak font_12" style="border-bottom:0px solid black;" ></th>
							<th class="jarak font_12" style="border-bottom:0px solid black;text-align: left;" >Grand Total</th>
							<th class="text-right jarak font_12" style="border-bottom:0px solid black;text-align: right;background-color: #00BFFF;transpa" ><?=number_format($SUM_TotalKO)?></th>
							<th class="text-right jarak font_12" style="border-bottom:0px solid black;background-color: #7CFC00;" ><?=number_format($SUM_TotalTerbayar)?></th>
							<th class="text-right jarak font_12" style="border-bottom:0px solid black;background-color: #FF4500;" ><?=number_format($SUM_SisaPembayaran)?></th>
							<th class="jarak font_12" style="border-bottom:0px solid black;padding-left:5px !important;" ></th>
						</tr>
						<tbody>
							<?php
							$SUMTotalKO = 0;
							$SUMTotal_Terbayar = 0;
							foreach($arrayKO as $arrayKO){ 
								$SUMTotalKO += $arrayKO->TotalKO;
								?>
								<tr>
									<td><?=$arrayKO->NoUrut?></td>
									<td><?=$arrayKO->NoKO?></td>
									<td><?=tgl_baru($arrayKO->TglKO)?></td>
									<td><?=$arrayKO->VendorNm?></td>
									<td><?=$arrayKO->KategoriId?></td>
									<td class="text-right jarak font_10" ><?=number_format($arrayKO->TotalKO)?></td>
									<td class="text-right jarak font_10"><?=number_format($arrayKO->TotalTerbayar)?></td>
									<td class="text-right jarak font_10"><?=number_format($arrayKO->SisaPembayaran)?></td>
									<td><?=$arrayKO->ClosedBy?></td>
								</tr>

								<?php if ($arrayKO->BLE > 0) : ?>
									<tr>
										<td></td>
										<td colspan="8">
											<table class="jarak_5 tbl_ble" width="50%" border="0" style="border-collapse:collapse;border:1px solid silver;">
												<tr>
													<th style="width:30px;border-bottom:1px solid silver;text-align: center;" class="font_12">No.</th>
													<th style="width:150px;border-bottom:1px solid silver;" class="font_12">No PD</th>
													<th style="width:100px;border-bottom:1px solid silver;" class="font_12">Tgl Bayar</th>
													<th style="width:100px;border-bottom:1px solid silver;" class="text-right font_12">Nilai (Rp)</th>
													<th  style="padding-left:10px !important;border-bottom:1px solid silver;width:200px;" class="font_12" >Keterangan</th>
													<th></th>
												</tr>
												<?php 
												$subtotal_BLE = 0;
												foreach($arrayKO->DataTerbayar as $dataTerbayar) { 
													$subtotal_BLE += $dataTerbayar['Amount'];

													?>
													<tr>
														<td style="text-align: center;" class="font_10"><?=$dataTerbayar['NoUrutTerbayar']?></td>
														<td class="font_10"><?=$dataTerbayar['NoPD']?></td>
														<td><?=tgl_baru($dataTerbayar['TglBayar'])?></td>
														<td class="text-right font_10" ><?=number_format($dataTerbayar['Amount'])?></td>
														<td style="padding-left:10px !important;" class="font_10" ><?=$dataTerbayar['Keterangan']?></td>
													</tr>

													
												<?php } ?>
												<tr>
													<td colspan="3"></td>
													<td style="text-align:right;border-top:1px solid silver;"><?=number_format($subtotal_BLE)?></td>
												</tr>
											</table>
										</td>
									</tr>
									<?php
									$SUMTotal_Terbayar += $subtotal_BLE;
									?>
								<?php endif ?>


								<?php if ($arrayKO->CekKontrak > 0) : ?>
									<tr style="padding-top:10px !important;">
										<td style="padding-top:10px !important;"></td>
										<td colspan="8" style="padding-top:10px !important;">
											<table class="jarak_5 tbl_ble" width="50%" border="0" style="border-collapse:collapse;border:1px solid silver;padding-top:20px;">
												<tr>
													<th colspan="5" style="padding-left:5px;"><u>Daftar Pengurangan Nilai Kontrak</u></th>
												</tr>
												<tr>
													<th style="width:30px;border-bottom:1px solid silver;text-align: center;" class="font_12" >No.</th>
													<th style="width:150px;border-bottom:1px solid silver;" class="font_12" >No KO</th>
													<th style="width:100px;border-bottom:1px solid silver;" class="font_12" >Tanggal</th>
													<th style="width:100px;border-bottom:1px solid silver;" class="text-right font_12">Nilai KO (Rp)</th>
													<th></th>
												</tr>

												<?php
												$subtotal_Kontrak = 0;
												foreach($arrayKO->DataKontrak as $dataKontrak) { 
													$subtotal_Kontrak += $dataKontrak['TtlKO'];
													?>
													<tr>
														<td class="font_10" style="text-align: center;"><?=$dataKontrak['NoUrutKontrak']?></td>
														<td class="font_10"><?=$dataKontrak['NoKO']?></td>
														<td class="font_10"><?=tgl_baru($dataKontrak['TglKO'])?></td>
														<td class="text-right font_10" ><?=number_format($dataKontrak['TtlKO'])?></td>
													</tr>
												<?php } ?>

												<tr>
													<td colspan="3"></td>
													<td style="text-align:right;border-top:1px solid silver;"><?=number_format($subtotal_Kontrak)?></td>
												</tr>
												
											</table>
										</td>
									</tr>

								<?php endif; ?>


							<?php } ?>


						</tbody>
						<!-- <tfoot>
							<tr>
								<th colspan="9"><?=number_format($SUMTotal_Terbayar)?></th>
							</tr>
						</tfoot> -->
					</table>

				</div>


			</div>
		</div>
	</div>
</div>