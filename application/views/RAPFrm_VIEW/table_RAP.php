<style type="text/css">
	#mytable thead tr th {
		color: White;
		background-color: #5D7B9D;
		font-weight: bold;
		text-align: center;
		vertical-align: middle;
		font-size: 13px !important;
	}

	#mytable tfoot tr th {
		color: White;
		background-color: #5D7B9D;
		font-weight: bold;
		text-align: right;
		/*		vertical-align: middle;*/
		font-size: 13px !important;
	}
</style>

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="row">
			<div class="col-sm-12">

				<table width="100%" border="1" style="display:none;">
					<tr>
						<td width="100px">
							<a href="<?= site_url('Entry/RAPFrm/CreateExcel') ?>" class="btn btn-md btn-success">EXPORT TO EXCEL</a>
						</td>
						<td width="10px"></td>
						<td width="100px">
							<button class="btn btn-md btn-success"  onclick="modal_import_excel()" >IMPORT FROM EXCEL</button>
						</td>
						<td></td>
					</tr>
				</table>

				<table width="100%" border="0">
					<tr>
						<td width=" 100px">
							<a href="<?= site_url('Entry/RAPFrm/CreateNewRAP/' . $JobNo . '/' . $Alokasi . '/' . $VersiStrip . '/' . $TipeRAP) ?>" class="btn btn-md btn-default">TAMBAH DATA <i class="fa fa-plus"></i></a>
						</td>
						<td width="10px"></td>
						<td width="100px">
							<button type="button" class="btn btn-md btn-default btnTambahVersi" data-JobNo="<?= $JobNo ?>" data-Alokasi="<?= $Alokasi ?>" data-Versi="<?= $Versi ?>" data-TipeRAP="<?= $TipeRAP ?>" data-VersiTerkini="<?= $VersiTerkini ?>">TAMBAH VERSI <i class="fa fa-plus"></i></button>
						</td>
						<td width="10px"></td>
						<td width="100px">
							<a href="<?=$Url_ExportExcel?>" class="btn btn-md btn-success" style="display:;">Export To Excel <i class="fa fa-file-excel-o"></i></a>
						</td>
						<td width="10px"></td>
						<td width="100px">
							<button class="btn btn-md btn-success" style="display:none;" onclick="modal_import_excel()">IMPORT FROM EXCEL</button>
						</td>
						<td></td>
					</tr>
				</table>
				<hr>

				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover" id="mytable">
						<thead>
							<tr>
								<td colspan="4" class="text-center" style="border:1px solid;"></td>
								<td colspan="3" class="text-center" style="border:1px solid;font-weight: bold;">RAP</td>
								<td colspan="3" class="text-center" style="border:1px solid;font-weight: bold;">RAB</td>
								<td colspan="5" class="text-center" style="border:1px solid;"></td>
							</tr>
						</thead>
						<thead>
							<tr>
								<th>Kode RAP</th>
								<th>Uraian</th>
								<th>Sat</th>
								<th>Vol</th>
								<th style="background-color:#3399FF;width:80px;">Hrg Satuan <br>(Rp)</th>
								<th style="background-color:#3399FF;width:200px;">Jumlah Harga (Rp)</th>
								<th style="background-color:#3399FF;width:120px;">Bobot (%)</th>

								<th style="background-color:#33CC33;width:80px;">Hrg Satuan <br>(Rp)</th>
								<th style="background-color:#33CC33;width:200px;">Jumlah Harga (Rp)</th>
								<th style="background-color:#33CC33;width:120px;">Bobot (%)</th>

								<th>RAP / RAB (%)</th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$NoUrut = 1;

							$sumBobotRAP = 0;
							$sumBobotRAB = 0;

							$sumJumlahHarga_RAP = 0;
							$sumJumlahHarga_RAB = 0;

							$sum_BobotRAP_Dibagi_BobotRAB = 0;
							$flagUPD_OR_SEE = ($TipeRAP != 'RAP') ? 'SEE' : 'UPD';

							foreach ($ArrayBuatan as $row) :
								$TextBold = '';
								$warnaRAP = "background-color:#A1DCF2;";
								$warnaRAB = "background-color:#99FF99;";
								$TextBold = ($row->Tipe == 'Header' && $row->Header == '0') ? 'text-bold' : '';

								if ($row->Tipe != 'Header') {
									$sumBobotRAP += (float) $row->BobotRAP;
									$sumBobotRAB += (float) $row->BobotRAB;

									$sumJumlahHarga_RAP += (float) $row->Vol * $row->HrgSatuan;
									$sumJumlahHarga_RAB += (float) $row->Vol * $row->HrgRAB;

									$sum_BobotRAP_Dibagi_BobotRAB += (float) $row->BobotRAP_DiBagi_BobotRAB;
								}



								?>
								<tr>
									<td class="<?= $TextBold ?>"><?= htmlspecialchars($row->KdRAP) ?></td>
									<td class="<?= $TextBold ?>"><?= htmlspecialchars($row->Uraian) ?></td>
									<td class="<?= $TextBold ?>"><?= htmlspecialchars($row->Uom) ?></td>

									<td class="text-right <?= $TextBold ?>">
										<?= ($row->Tipe == 'Header' && ($row->Header == 0 || $row->Header == '0')) ? '' : VolDecimal_Nol_Tiga($row->Vol) ?>

									</td>

									<!-- -- WILAYAH HARGA RAP -->
									<td class="text-right <?= $TextBold ?>" style="<?= $warnaRAP ?>">
										<?= ($row->Tipe == 'Header' && ($row->Header == 0 || $row->Header == '0')) ? '' : VolDecimal_Nol_Dua($row->HrgSatuan) ?>

									</td>

									<td class="text-right <?= $TextBold ?>" style="<?= $warnaRAP ?>">
										<?= VolDecimal_Nol_Dua($row->JumlahHargaRAP) ?>

									</td>

									<td class="text-right <?= $TextBold ?>" style="<?= $warnaRAP ?>">
										<?= htmlspecialchars($row->BobotRAP) . '%' ?>

									</td>

									<!-- -- WILAYAH HARGA RAB -- -->
									<td class="text-right <?= $TextBold ?>" style="<?= $warnaRAB ?>">
										<?= ($row->Tipe == 'Header' && ($row->Header == 0 || $row->Header == '0')) ? '' : VolDecimal_Nol_Dua($row->HrgRAB) ?>
									</td>

									<td class="text-right <?= $TextBold ?>" style="<?= $warnaRAB ?>" <?= $TextBold ?>>
										<?= VolDecimal_Nol_Dua($row->JumlahHargaRAB) ?>
									</td>

									<td class="text-right <?= $TextBold ?>" style="<?= $warnaRAB ?>">
										<?= htmlspecialchars($row->BobotRAB) . '%' ?>
									</td>


									<td class="text-right <?= $TextBold ?>"><?= htmlspecialchars($row->BobotRAP_DiBagi_BobotRAB) . '%' ?></td>

									<td class="text-center">
										<a href="<?= site_url('Entry/RAPFrm/UpdateRAP/' . $JobNo . '/' . $Alokasi . '/' . $VersiStrip . '/' . $TipeRAP . '/' . $flagUPD_OR_SEE . '/' . $row->LedgerNo) ?>" class="btn btn-md btn-link text-bold">SELECT</a>
									</td>
									<td class="text-center">
										<button type="button" class="btn btn-md btn-link text-bold btnDelete" data-LedgerNo="<?= $row->LedgerNo ?>" data-Versi="<?= $row->Versi ?>" data-TipeRAP="<?= $TipeRAP ?>" data-JobNo="<?= $row->JobNo ?>" data-Alokasi="<?= $row->Alokasi ?>" data-KdRAP="<?= $row->KdRAP ?>" data-Tipe="<?= $row->Tipe ?>" id="btnDelete_ID_<?= $row->LedgerNo ?>" data-Uraian="<?= $row->Uraian ?>">DELETE</button>
									</td>
									<td>
										<!-- <button type="button" class="btn btn-md btn-link text-bold btnNaikTurun" id="btnUP_ID_<?= $row->NoUrut ?>" data-FlagBtn="btnUp" data-NoUrut="<?= $row->NoUrut ?>" data-KdRAP="<?= $row->KdRAP ?>" data-JobNo="<?= $row->JobNo ?>" data-TipeRAP="<?= $TipeRAP ?>" >&#x25B2; --- <?= $row->NoUrut ?></button> -->

										<button type="button" class="btn btn-md btn-link text-bold btnUpKlik" id="btnUpKlik_ID_<?= $row->NoUrut ?>" data-KdRAP="<?= $row->KdRAP ?>" data-NoUrut="<?= $row->NoUrut ?>" data-Tipe="<?= $row->Tipe ?>">&#x25B2;</button>
									</td>
									<td>
										<!-- <button type="button" class="btn btn-md btn-link text-bold btnNaikTurun" id="btnDown_ID_<?= $row->NoUrut ?>" data-FlagBtn="btnDown" data-NoUrut="<?= $row->NoUrut ?>" data-KdRAP="<?= $row->KdRAP ?>" data-JobNo="<?= $row->JobNo ?>" data-TipeRAP="<?= $TipeRAP ?>">&#x25BC; -- <?= $row->NoUrut ?></button> -->
										<button type="button" class="btn btn-md btn-link text-bold btnDownKlik" id="btnDownKlik_ID_<?= $row->NoUrut ?>" data-KdRAP="<?= $row->KdRAP ?>" data-NoUrut="<?= $row->NoUrut ?>" data-Tipe="<?= $row->Tipe ?>">&#x25BC;</button>
									</td>

								</tr>
							<?php endforeach ?>
						</tbody>
						<tfoot>
							<tr>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th>sum = <?= number_format($sumJumlahHarga_RAP) ?></th>
								<th>sum = <?= $sumBobotRAP ?>%</th>
								<th></th>
								<th>sum = <?= number_format($sumJumlahHarga_RAB) ?></th>
								<th>sum = <?= $sumBobotRAB ?>%</th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="box-body">

	</div>
</div>


<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Import Data RAP From Excel</h4>
			</div>

			<form action="<?= base_url('Entry/RAPFrm/import_excel'); ?>" method="post" enctype="multipart/form-data">

				<div class="modal-body">
					<div class="col-md-12">
						<div class="form-group">
							<label>Pilih File Excel</label>
							<input type="hidden" name="JobNo" value="<?=$JobNo?>">
							<input type="hidden" name="Alokasi" value="<?=$Alokasi?>">
							<input type="hidden" name="Versi" value="<?=$Versi?>">
							<input type="hidden" name="TipeRAP" value="<?=$TipeRAP?>">

							<input type="file" name="fileExcel" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<span>download template import disini <a href="<?=site_url('Entry/RAPFrm/lakukan_download')?>">Download</a> </span>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button class='btn btn-success' type="submit">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
							Import		
						</button>
					</div>
				</div>

			</form>

		</div>

	</div>
</div>