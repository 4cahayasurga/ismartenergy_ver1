<style type="text/css">
	.tbl_rap {
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		/*white-space: nowrap;*/
	}

	.atur_th {
		font-size: 12px !important;
		font-weight: bold;
		padding-top: 10px !important;
		padding-bottom: 10px !important;
	}

	/*	.tbl_rap thead th{
		
		font-size: 12px !important;
		font-weight: bold;
	}*/

	.tbl_rap tbody td {
		font-size: 11px;
		padding-bottom: 5px !important;
		padding-top: 5px !important;
		border: 1px solid #000;
	}

	table tbody .sumFooter td {
		border-top: 1px solid #000;
		background-color: #00bfff;
		text-align: right;
		font-weight: bold;
		font-size: 12px;
	}

	.table_detail tbody tr td:nth-child(2) {
		width: 20px;
	}

	.table_detail tbody tr td:nth-child(3) {
		width: 25px;
	}


	.table_detail tbody tr td:last-child {
		border-left: 1px solid #000;
	}
</style>

<div class="row">
	<div class="col-sm-12">

		<div class="box">
			<div class="box-header">
				<a href="<?= site_url('Laporan/RptPenyerapanRAP/pdf_rap/' . $JobNo . '/' . $Alokasi . '/' . $dari_tgl . '/' . $sampai_tgl) ?>" target="_blank" class="btn btn-sm btn-danger btn_print">PRINT PDF <i class="fa fa-print"></i></a>
				<a href="<?= site_url('Laporan/RptPenyerapanRAP/exportExcel/' . $JobNo . '/' . $Alokasi . '/' . $dari_tgl . '/' . $sampai_tgl . '/' . $checked) ?>" class="btn btn-success btn-sm">Export Excel <i class="fa fa-file-excel-o"></i></a>
			</div>

			<div class="box-body">

				<div class="table-responsive">

					<table class="table table-bordered table-striped table-hover table-condensed tbl_rap">
						<thead>
							<tr class="bg-gray">
								<th class="atur_th" style="width:4%; border-left: 0.5px solid #000; border-top: 0.5px solid #000;">No</th>
								<th class="atur_th" style="width:6%; border-left: 0.5px solid #000; border-top: 0.5px solid #000;">Kode RAP</th>
								<th class="atur_th" style="width:20% !important; border-left: 0.5px solid #000; border-top: 0.5px solid #000;">Uraian</th>
								<th class="atur_th" style="border-left: 0.5px solid #000; border-top: 0.5px solid #000;">Uom</th>
								<th class="atur_th" style="text-align: right; border-left: 0.5px solid #000; border-top: 0.5px solid #000;">Vol</th>
								<th class="atur_th" style="text-align: right; border-left: 0.5px solid #000; border-top: 0.5px solid #000;">Harga Satuan</th>
								<th class="atur_th" style="text-align: right; border-left: 0.5px solid #000; border-top: 0.5px solid #000;">Total</th>
								<th class="atur_th" style="text-align: right; border-left: 0.5px solid #000; border-top: 0.5px solid #000;">Sudah Terserap</th>
								<th class="atur_th" style="text-align: right; border-left: 0.5px solid #000; border-top: 0.5px solid #000;">%</th>
								<th class="atur_th" style="text-align: right; border-left: 0.5px solid #000; border-top: 0.5px solid #000;">Sisa RAP</th>
							</tr>
						</thead>

						<tbody>

							<?php
							$TipeHeader = '';
							$KdRAP_header = '';
							$TtlTerserap = 0;
							$i = 1;

							$sumHrgSatuan = 0;
							$sumTotal = 0;
							$sumSdhTerserap = 0;
							$sumSisaRAP = 0;
							foreach ($dataRAP as $dataRAP) :
								$sisaRAP = $dataRAP['Total'] - $dataRAP['SudahTerserap'];

								$TipeHeader = ($dataRAP['Tipe'] == 'Header') ? 'text-bold' : '';
								$sumHrgSatuan += $dataRAP['HrgSatuan'];
								$sumTotal += $dataRAP['Total'];
								$sumSdhTerserap += $dataRAP['SudahTerserap'];
								$sumSisaRAP += $sisaRAP;
							?>
								<tr class="<?= $checked == 'true' ? 'bg-blue' : '' ?> <?= $TipeHeader ?>">
									<td style="border-left: 0.5px solid #000; text-align:left;"><?= $i++; ?></td>
									<td style="border-left: 0.5px solid #000; text-align:left;"><?= $dataRAP['KdRAP'] ?></td>
									<td style="border-left: 0.5px solid #000;"><?= $dataRAP['Uraian'] ?></td>
									<td style="border-left: 0.5px solid #000;"><?= $dataRAP['Uom'] ?></td>
									<td style="border-left: 0.5px solid #000; text-align: right;"><?= $dataRAP['Vol'] ?></td>
									<td style="border-left: 0.5px solid #000; text-align: right;"><?= UangBiasa($dataRAP['HrgSatuan']); ?></td>
									<td style="border-left: 0.5px solid #000; text-align: right;"><?= UangBiasa($dataRAP['Total']) ?></td>
									<td style="border-left: 0.5px solid #000; text-align: right;"><?= UangBiasa($dataRAP['SudahTerserap']) ?></td>
									<td style="border-left: 0.5px solid #000; text-align: right;">
										<?php
										$percentage = ($dataRAP['Total'] != 0) ? ($dataRAP['SudahTerserap'] / $dataRAP['Total']) * 100 : 0;
										echo round($percentage, 3);
										?>
									</td>
									<td style="text-align: right; border-left: 0.5px solid #000;"><?= UangBiasa($sisaRAP); ?></td>
								</tr>
								<?php if ($checked == 'true') : ?>
									<tr>
										<td colspan="5">
											<table class="table table-bordered table-striped table-hover table_detail">
												<thead>
													<tr>
														<th></th>
														<th colspan="5"><b>LIST DETAIL PD</b></th>
													</tr>
													<tr class="row-title-detail">
														<th class="" style="border-right: 0.5px solid #000;"></th>
														<th class="" style="background: #d8dee9; border-left:0.5px solid #000; border-top: 0.5px solid #000; border-left:0.5px solid #000;">No</th>
														<th class="" style="background: #d8dee9; border-left:0.5px solid #000; border-top: 0.5px solid #000;">KdRAP</th>
														<th class="" style="background: #d8dee9; border-left:0.5px solid #000; border-top: 0.5px solid #000;">Uraian</th>
														<th class="" style="background: #d8dee9; border-left:0.5px solid #000; border-top: 0.5px solid #000;">No PJ</th>
														<th class="" style="background: #d8dee9; border-left:0.5px solid #000; border-top: 0.5px solid #000; border-right:0.5px solid #000;">Pj Harga Satuan</th>
													</tr>
												</thead>
												<tbody>
													<?php $no = 1;
													foreach ($dataRAP['detailBarang'] as $row2) : ?>
														<tr class="row-detail">
															<td style="border-right: 0.5px solid #000;"></td>
															<td><?= $no++ ?></td>
															<td><?= $row2['KdRAP'] ?></td>
															<td><?= $row2['Uraian'] ?></td>
															<td><?= $row2['NoPJ'] ?></td>
															<td style="border-right: 0.5px solid #000;"><?= UangBiasa($row2['PjHrgSatuan']) ?></td>
														</tr>
													<?php endforeach; ?>
													<tr>
														<td></td>
														<td style="border-top: 0.5px solid #000;"></td>
														<td style="border-top: 0.5px solid #000;"></td>
														<td style="border-top: 0.5px solid #000;"></td>
														<td style="border-top: 0.5px solid #000;"></td>
														<td style="border-top: 0.5px solid #000;"></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
						<tr class="sumFooter">
							<td colspan="5" style="text-align:center; border: 1px solid #000;">SUM</td>
							<td style="border-bottom: 1px solid #000; border-right: 1px solid #00bfff;"><?= UangBiasa($sumHrgSatuan) ?></td>
							<td style="border-bottom: 1px solid #000; border-right: 1px solid #00bfff;"><?= UangBiasa($sumTotal) ?></td>
							<td style="border-bottom: 1px solid #000; border-right: 1px solid #00bfff;"><?= UangBiasa($sumSdhTerserap) ?></td>
							<td style="border-bottom: 1px solid #000; border-right: 1px solid #00bfff;"></td>
							<td style="border-bottom: 1px solid #000; border-right: 1px solid #00bfff;"><?= UangBiasa($sumSisaRAP) ?></td>
						</tr>
					</table>
				</div>

			</div>
		</div>

	</div>
</div>

<script type="text/javascript">
	$(function() {
		$('.tbl_rap').DataTable({
			'ordering': false,
			lengthMenu: [
				[10, 25, 50, 100, -1],
				[10, 25, 50, 100, 'All']
			],
		});
	});
</script>