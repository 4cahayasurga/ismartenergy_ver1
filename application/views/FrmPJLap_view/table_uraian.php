<div class="box">
	<div class="box-body">
		<div class="col-sm-12">
			<div class="table-responsive">
				<table id="TableUraian" border="1" width="100%" style="border-collapse: collapse;" class="table table-bordered table-striped table-hover font11">
					<thead>
						<tr class="bg-gray">
							<th class="text-center" width="3%" >No.</th>
							<th class="text-center" width="10%" >Kode RAP</th>
							<th class="text-center" width="25%" >Uraian</th>
							<!-- <th class="text-center" width="5%" >Vol</th> -->
							
							<!-- <th class="text-center" width="10%" >Harga Satuan (Rp)</th> -->
							<!-- <th class="text-center" width="10%" >Jumlah Harga (Rp)</th> -->

							<th class="text-center" width="5%" >PJ Vol</th>
							<th class="text-center"  width="5%" >Sat</th>
							<th class="text-center" width="10%" >PJ Harga Satuan (Rp)</th>
							<th class="text-center" width="10%" >PJ Jumlah Harga (Rp)</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$Nomor = 1;
						$JumlahHarga = 0;
						$SubTotal = 0;
						$PjSubTotal = 0;
						$PjJumlahHarga = 0;

						foreach($dataUraian as $rowUraian) {

							$JumlahHarga = $rowUraian->Vol * $rowUraian->HrgSatuan;
							$SubTotal += $JumlahHarga;

							$PjJumlahHarga = $rowUraian->PjVol * $rowUraian->PjHrgSatuan;
							$PjSubTotal += $PjJumlahHarga;

							?>
							<tr>
								<td class="text-center"><?=$Nomor++?></td>
								<td><?=$rowUraian->KdRAP.' - '.$rowUraian->UraianKdRAP?></td>
								<td><?=$rowUraian->Uraian?></td>
								<td class="text-right"><?=number_format($rowUraian->PjVol, 2, '.', ',')?></td>
								<td><?=$rowUraian->Uom?></td>
								<td class="text-right"><?=number_format($rowUraian->PjHrgSatuan, 2, '.', ',')?></td>
								<td class="text-right"><?=number_format($PjJumlahHarga, 2, '.', ',')?></td>
								<td class="text-center" width="5%">

									<button type="button" onclick="SelectUraian(<?=$rowUraian->NoUrut?>)" id="BtnSelect_<?=$rowUraian->NoUrut?>" data-NoPD="<?=$rowUraian->NoPD?>" class="btn btn-default btn-md font10" 
										data-NoUrut="<?=$rowUraian->NoUrut?>"
										data-KdRAP="<?=$rowUraian->KdRAP?>"
										data-UraianKdRAP="<?=$rowUraian->UraianKdRAP?>"
										data-Uraian="<?=$rowUraian->Uraian?>"
										data-Vol="<?=number_format($rowUraian->Vol, 2, '.', ',')?>"
										data-Uom="<?=$rowUraian->Uom?>"
										data-HrgSatuan="<?=number_format($rowUraian->HrgSatuan, 2, '.', ',')?>"


										data-PjNoUrut="<?=$rowUraian->NoUrut?>"
										data-PjKdRAP="<?=$rowUraian->KdRAP?>"
										data-PjUraian="<?=$rowUraian->PjUraian?>"
										data-PjVol="<?=number_format($rowUraian->PjVol, 2, '.', ',')?>"
										data-PjUom="<?=$rowUraian->Uom?>"
										data-PjHrgSatuan="<?=number_format($rowUraian->PjHrgSatuan, 2, '.', ',')?>"

										>SELECT</button>
									</td>
								</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr class="bg-gray text-bold">
								<!-- <td colspan="7" class="text-right font13">Total = <?=number_format($SubTotal, 2, '.', ',')?></td> -->
								<td colspan="6" class="text-right font13">Total Pertanggungjawaban</td>
								<td class="text-right font13"><?=number_format($PjSubTotal, 2, '.', ',')?></td>
								<td></td>
							</tr>
						</tfoot>
					</table>

					<?php
					$LblOverride = '';
					$SaldoUbah = 0;
					$Tampil_RemarkOverrideSaldo = 'none';

					if ($OverrideSaldo == '1') {
						$LblOverride = '1';
						$SaldoUbah = $Saldo;
						$Tampil_RemarkOverrideSaldo = '';
					}

					if ($LblOverride == '') {
						$SaldoUbah = ($TotalPD - $PjSubTotal);
					}
					
					 

					?>

					<input type="hidden" name="TotalPJ" value="<?=$PjSubTotal?>">
					<input type="hidden" name="Saldo" value="<?=$SaldoUbah?>">

					

					<table width="100%" class="font13" border="0" style="display:<?=$Tampil_RemarkOverrideSaldo?>;">
						
						<tr>
							<th width="1000px" class="text-right" style="padding-right:10px;vertical-align: top;">Remark Edit Saldo</th>
							<td width="" class="text-right">
								<textarea name="RemarkOverrideSaldo" id="RemarkOverrideSaldo" class="form-control input-sm font13" placeholder="Remark Edit Saldo" rows="3"  ></textarea>
							</td>
							<td width="80px" style="border-left:0px !important;"></td>
						</tr>

						
					</table>
					<p></p>


					<table width="100%" class="font13" border="0">
						
						<tr>
							<th width="77%" class="text-right" style="padding-right:10px;">Dana yang tersedia dilapangan</th>
							<td width="17%" class="text-right">
								<input type="text" name="DanaTersediaDiLapangan" value="<?=number_format($TotalPD, 2, '.', ',')?>" class="form-control input-sm font12 text-right" readonly>
							</td>
							<td style="border-left:0px !important;"></td>
						</tr>

						<tr>
							<th width=" 77%" class="text-right" style="padding-right:10px;padding-top:5px;">Saldo</th>
							<td width="17%" class="text-right" style="padding-top:5px;">
								<!-- <input type="text" name="DanaTersediaDiLapangan" value="<?=number_format($Saldo_Edit, 2, '.', ',')?>" class="form-control input-sm font12 text-right" readonly> -->

								<input type="text" value="<?=number_format($SaldoUbah, 2, '.', ',')?>" class="form-control input-sm font12 text-right" disabled>

								<input type="hidden" name="Saldo" value="<?=$SaldoUbah?>" class="form-control input-sm font12 text-right" readonly>

							</td>
							<td style="border-left:0px !important;"></td>
						</tr>
						
					</table>

					<br>
				</div>
			</div>
		</div>
	</div>

	<script>
		$(function(){
			$('#TableUraian').DataTable({
				paging : false
			});	
		});
	</script>