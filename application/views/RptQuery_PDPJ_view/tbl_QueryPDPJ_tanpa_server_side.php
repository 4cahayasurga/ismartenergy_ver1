<style type="text/css">
	.tengah{
		text-align: center;
	}


	.sepuluh{
		font-size: 11px;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
</style>

<div class="row " style="padding-top:20px;">
	<div class="col-sm-12" >
		<form action="<?=site_url('RptQuery_PDPJ/ExcelExport_QueryPDPJ')?>" method="POST">
			<div class="table-responsive">
				<p></p>

				<button type="submit" class="btn btn-success btn-md btn_excel" >Export Excel</button>
				<input type="hidden" name="JobNo" value="<?=$JobNo?>" >
				<input type="hidden" name="Alokasi" value="<?=$Alokasi?>" >
				<table id="tbl_outstanding_pc" class="table table-borderless table-striped table-hover table-condensed" width="100%" border="0" style="white-space: nowrap;"  >
					<thead>
						<tr class="bg-gray-active sepuluh">
							<th class="text-center" data-orderable="false" style="text-align: center;padding-left: 20px;">
								<input type="checkbox" class="check-AllP text-white text-center">
							</th>
							<th>No.</th>

							<th>Job No</th>
							<th >No PD</th>
							<!-- <th>Alokasi</th> -->
							<th>Tgl PD</th>

							<th>No Ref. Lapangan</th>
							<th>Alokasi</th>
							<th>Tipe Form</th>
							<th>Deskripsi</th>

							<th>No. KO</th>
							<th class="text-right">Total KO (Rp)</th>
							<th>No. Tagihan</th>
							<th>Rekening Penerima</th>

							<th>Atas Nama</th>
							<th class="text-right" >Total PD (Rp)</th>
							<th>Tgl Bayar</th>
							<th>Via</th>

							<th class="text-right" >Total Pembayaran (Rp)</th>
							<th>Rekening Pengirim</th>
							<th>No PJ</th>
							<th>Tgl PJ</th>
							<th class="text-right">Total PJ (Rp)</th>
							<th class="text-right">Saldo (Rp)</th>
						</tr>
					</thead>
					<tbody>

						<?php 
						$nomor = 1;
						$TotalKO = 0;
						$Saldo1 = 0;

						$sumTotalPD = 0;
						$sumTotalPembayaran = 0;
						$sumTotalPJ = 0;
						foreach ($ListData_QueryPDPJ as $key){ ?>

							<?php

							$sumTotalPD += $key->TotalPD;
							$sumTotalPembayaran += $key->BLEAmount;
							$sumTotalPJ += $key->TotalPJ;

							if ( ($key->NoKO !='' OR  $key->NoKO !=NULL OR $key->NoKO !='0') && substr($key->NoKO, 0, 2) == 'KO' ) {

								$SubTotal = $key->SubTotal;
								$DiscAmount = $key->DiscAmount;
								$PPN = $key->PPN;
								$TotalKO = ($SubTotal - $DiscAmount + $PPN);
							}

							?>



							<tr class="sepuluh">
								<td class="text-center">
									<input type="checkbox" class="print_checkbox" id="LedgerNo" name="LedgerNo[]" value="<?=$key->LedgerNo?>">
									<input type="hidden" name="JobNo1" value="<?=$key->JobNo1?>" >
								</td>
								<td><?=$nomor++;?></td>

								<td><?=$key->JobNo1?></td>
								<td><?=$key->NoPD?></td>
								<!-- <td><?=$key->Alokasi?></td> -->
								<td><?=date('d-M-Y',strtotime($key->TglPD))?></td>

								<td><?=$key->NoRef?></td>
								<td><?=$key->Alokasi?></td>
								<td><?=$key->TipeForm?></td>
								<td><?=$key->Deskripsi?></td>

								<td><?=$key->NoKO?></td>
								<td class="text-right"><?=number_format($TotalKO)?></td>
								<td><?=$key->NoTagihan?></td>
								<td><?=$key->NoRek?></td>

								<td><?=$key->AtasNama?></td>
								<td class="text-right" ><?=number_format($key->TotalPD)?></td>
								<td><?=($key->TglBayar == '1900-01-01' OR $key->TglBayar == NULL OR $key->TglBayar == '') ? '' : date('d-M-Y',strtotime($key->TglBayar)) ?></td>
								<td><?=$key->JenisTrf?></td>

								<td class="text-right" ><?=number_format($key->BLEAmount)?></td>
								<td><?=$key->RekId?></td>
								<td><?=$key->NoPJ?></td>
								<td><?=($key->TglPJ == '1900-01-01' OR $key->TglPJ == NULL OR $key->TglPJ == '') ? '' : date('d-M-Y',strtotime($key->TglPJ)) ?></td>
								<td class="text-right"><?=number_format($key->TotalPJ)?></td>
								<td class="text-right">
									<?php

									if ($key->NoPJ == NULL OR $key->NoPJ == '') {
										$Saldo1 = 0;
									}else{
										$TtlPD = $key->TotalPD;
										$TtlPJ = $key->TotalPJ;
										$Saldo1 = $TtlPD + $TtlPJ;
									}	

									echo number_format($Saldo1);

								?></td>
							</tr>
						</tbody>


					<?php } ?>
					<tfoot>
						<tr>

							<th colspan="15" class="text-right">Sum=<?=number_format($sumTotalPD)?></th>
							<th colspan="3" class="text-right">Sum=<?=number_format($sumTotalPembayaran)?></th>
							<th colspan="4" class="text-right">Sum=<?=number_format($sumTotalPJ)?></th>
							<th></th>
						</tr>
					</tfoot>

				</table>


			</div>
		</form>


	</div>
</div>

<script src="<?=base_url('assets/select2_baru.js')?>"></script>


<script type="text/javascript">
	$(document).ready(function() {

		var table = $('#tbl_outstanding_pc').DataTable( {
			orderable : true,
			paging: false,
			scrollY:    400,
			scrollX:        true,
			scrollCollapse: true,
			fixedColumns:   true,
			processing : true,
			"columnDefs": [ 
				// { "targets": 0, "orderable": false }, 
				// { "targets": 1, "orderable": false },
				// { "targets": 2, "orderable": false },
				],
				order: [[ 1, 'asc' ]],
			} );

		// alert($('.print_checkbox:checked').length);

		cek_checkbox();

		$('.print_checkbox').click(function() {
			if ($(this).is(':checked')) {
				$(this).closest('tr').addClass('removeRowP');
				cek_checkbox();
			} else {
				$(this).closest('tr').removeClass('removeRowP');
				cek_checkbox();
			}
			
		});
		$('.check-AllP').click(function() {
			$('.print_checkbox').prop('checked', this.checked);
			if ($(this).is(':checked')) {
				$('.checkP').addClass('removeRowP');
				cek_checkbox();
			} else {
				$('.checkP').removeClass('removeRowP');
				cek_checkbox();
			}
		});

		



	} );

	function cek_checkbox(){
		var checkbox = $('.print_checkbox:checked');

		if (checkbox.length > 0) {
			$('.btn_excel').attr('disabled',false);
		}else{
			$('.btn_excel').attr('disabled',true);
		}
	}


</script>
