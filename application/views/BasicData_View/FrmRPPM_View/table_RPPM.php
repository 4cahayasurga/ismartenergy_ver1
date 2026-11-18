<style type="text/css">
	.bg-merah {
		background-color: rgba(255, 0, 0, 0.3);
		font-weight: bold;
	}

	.bg-hijau {
		background-color: rgba(0, 255, 0, 0.3);
		font-weight: bold;
	}

	.panel-heading-nav {
		border-bottom: 0;
		padding: 10px 0 0;
	}

	.panel-heading-nav .nav {
		padding-left: 10px;
		padding-right: 10px;
	}

	#table_history thead tr th {
		text-align: center;
	}
</style>



<div class="panel panel-default">
	<div class="panel-heading panel-heading-nav">
		<ul class="nav nav-tabs">
			<li role="presentation" class="<?= $flag_tab_Main ?>">
				<a href="#one" aria-controls="one" role="tab" data-toggle="tab">Main</a>
			</li>
			<li role="presentation" class="<?= $flag_tab_History ?>" <?= $RPPMHistory_Akses ?>>
				<a href="#two" aria-controls="two" role="tab" data-toggle="tab">History</a>
			</li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane fade <?= ($flag_tab_Main == 'active') ? 'in active' : '' ?>" id="one">
				<div class="row tampilan_table_RPPM">
					<div class="col-sm-12">
						<div class="table-responsive">
							<table width="100%" border="0" style="border-collapse:collapse;margin-bottom: 10px !important;">
								<tr>
									<td>
										<a href="<?= site_url('BasicData/FrmRPPM_C/RPPM_MultipleForm/' . $JobNo . '/' . $Alokasi) ?>" class="btn btn-primary btn-md">TAMBAH DATA</a>
										<a href="<?= site_url('BasicData/FrmRPPM_C/export_to_excel/' . $JobNo . '/' . $Alokasi . '/' . $MingguKe . '/' . $PrdAwal . '/' . $PrdAkhir) ?>" id="btnExportExcel" class="btn btn-success btn-md">Export to Excel <i class="fa fa-file-excel-o"></i></a>
									</td>
								</tr>
							</table>
							<hr>
							<table class="table table-bordered table-striped table-hover font12" width="100%" border="0" id="mytable" border="1" style="margin-top:5px !important;">
								<thead style="vertical-align:middle;">
									<tr class="bg-gray color-pallete" style="vertical-align:middle;font-size: 12px !important;">
										<th rowspan="3" style="vertical-align:middle;width:20px;">No</th>
										<th width="60px" class="text-center" rowspan="3" style="vertical-align:middle;">Kode RAP</th>
										<th class="text-center" rowspan="3" style="vertical-align:middle;">Uraian Pekerjaan</th>
										<th width="100px" class="text-center" rowspan="3" style="vertical-align:middle;">Satuan</th>
										<th width="100px" class="text-center" rowspan="3" style="vertical-align:middle;">Volume</th>
										<th width="100px" class="text-center" rowspan="3" style="vertical-align:middle;">Harga Satuan Kontrak (Rp)</th>
										<th width="100px" class="text-center" rowspan="3" style="vertical-align:middle;">Total Harga Kontrak (Rp)</th>
										<th width="100px" class="text-center" colspan="6" style="vertical-align:middle;">Prestasi Pekerjaan</th>
									</tr>
									<tr class="bg-gray color-pallete">
										<th width="150px" class="text-center" colspan="2">Yang Lalu</th>
										<th width="150px" class="text-center" colspan="2">Sekarang</th>
										<th width="150px" class="text-center" colspan="2">S/D Sekarang</th>
									</tr>
									<tr class="bg-gray color-pallete">
										<th class="text-center">Volume</th>
										<th class="text-center">Bobot (%)</th>

										<th class="text-center">Volume</th>
										<th class="text-center">Bobot (%)</th>

										<th class="text-center">Volume</th>
										<th class="text-center">Bobot (%)</th>
									</tr>

								</thead>
								<tbody>
									<?php
									foreach ($dataRAP as $row) :
									?>
										<tr>
											<td><?= $row->NoUrut ?></td>
											<td><?= $row->KdRAP ?></td>
											<td><?= $row->Uraian ?></td>
											<td><?= $row->Uom ?></td>
											<td class="text-right"><?= UbahDecimal_Nol_Tiga($row->Vol) ?></td>
											<td class="text-right"><?= UbahDecimal_Nol_Dua($row->HrgRAB) ?></td>
											<td class="text-right"><?= UbahDecimal_Nol_Dua($row->JumlahHarga) ?></td>

											<!-- START DI UBAH PADA TANGGAL 14 SEPTEMBER 2023 -->

											<td class="text-right <?= ($row->Minggu_YangLalu != 0) ? 'bg-merah' : '' ?>"><?= UbahDecimal_Nol_Tiga($row->Minggu_YangLalu) ?></td>

											<td class="text-right <?= ($row->BobotPersen_YangLalu != 0) ? 'bg-hijau' : '' ?> "><?= UbahDecimal_Nol_Tiga($row->BobotPersen_YangLalu) ?></td>


											<td class="text-right <?= ($row->Minggu_SaatIni != 0) ? 'bg-merah' : '' ?> "><?= UbahDecimal_Nol_Tiga($row->Minggu_SaatIni) ?></td>

											<td class="text-right <?= ($row->BobotPersen_SaatIni != 0) ? 'bg-hijau' : '' ?> "><?= UbahDecimal_Nol_Tiga($row->BobotPersen_SaatIni) ?></td>


											<td class="text-right <?= ($row->Minggu_SampaiDgnSekarang != 0) ? 'bg-merah' : '' ?> "><?= UbahDecimal_Nol_Tiga($row->Minggu_SampaiDgnSekarang) ?></td>

											<td class="text-right <?= ($row->BobotPersen_SampaiDgnSekarang != 0) ? 'bg-hijau' : '' ?> "><?= UbahDecimal_Nol_Tiga($row->BobotPersen_SampaiDgnSekarang) ?></td>


										</tr>
									<?php endforeach ?>
								</tbody>
								<tfoot>

									<tr class="text-bold bg-gray color-pallete">
										<td colspan="4">TOTAL</td>
										<td class="text-right"><!--?='sum = '.UbahDecimal_Nol_Tiga($sumFooter_Vol)?--></td>
										<td class="text-right"><!--?='sum = '.UbahDecimal_Nol_Dua($sumFooter_HrgSatuan)?--></td>
										<td class="text-right"><?= 'sum = ' . UbahDecimal_Nol_Dua($sumFooter_TtlKontrak) ?></td>

										<td class="text-right"><!--?='sum = '.UbahDecimal_Nol_Tiga($sumFooter_VolLalu)?--></td>
										<td class="text-right"><?= 'sum = ' . UbahDecimal_Nol_Tiga($sumFooter_BobotLalu) ?></td>

										<td class="text-right"><!--?='sum = '.UbahDecimal_Nol_Tiga($sumFooter_VolSkrg)?--></td>
										<td class="text-right"><?= 'sum = ' . UbahDecimal_Nol_Tiga($sumFooter_BobotSkrg) ?></td>

										<td class="text-right"><!--?='sum = '.UbahDecimal_Nol_Tiga($sumFooter_VolsdSkrg)?--></td>
										<td class="text-right"><?= 'sum = ' . UbahDecimal_Nol_Tiga($sumFooter_BobotsdSkrg) ?></td>
									</tr>
								</tfoot>

							</table>
						</div>
					</div>
				</div>
			</div>

			<div role="tabpanel" class="tab-pane fade <?= ($flag_tab_History == 'active') ? 'in active' : '' ?>" id="two" <?= $RPPMHistory_Akses ?>>
				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive">

							<table width="100%" border="0" style="border-collapse:collapse;margin-bottom: 10px !important;">
								<tr>
									<td>
										<button type="button" class="btn btn-danger btn-md" id="hapus_rppm">Hapus History</button>


									</td>
								</tr>
							</table>
							<hr>

							<table class="table table-bordered table-striped table-hover" border="0" id="table_history">
								<thead>
									<tr style="color:White;background-color:#5D7B9D;font-weight:bold;text-align:center;">
										<th colspan="5"></th>
										<th colspan="4" class="text-center" style="color:White;background-color:#5D7B9D;font-weight:bold;">Periode</th>
										<th></th>
										<th></th>
										<th rowspan="2">Aksi</th>
										<th style="display:none;"></th>

									</tr>
									<tr style="color:White;background-color:#5D7B9D;font-weight:bold;text-align:center;">
										<th>No</th>
										<th><input type="checkbox" id="select_all"></th>
										<th>Kode RAP</th>
										<th>Uraian</th>
										<th>Uom</th>
										<th>Periode Awal</th>
										<th>Periode Akhir</th>
										<th>Minggu Ke</th>
										<th>Tgl Pengakuan</th>
										<th>Bobot Persen</th>
										<th>Volume Input</th>
										<th style="display:none;"></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$Norut = 0;
									foreach ($HistoryData as $myrow) :
										$Norut++;
									?>
										<tr>
											<td><?= $Norut ?></td>
											<td><input type="checkbox" class="emp_checkbox" data-LedgerNo="<?= $myrow->LedgerNo ?>" data-PrdAwal="<?= $myrow->PrdAwal ?>" data-PrdAkhir="<?= $myrow->PrdAkhir ?>" data-Minggu="<?= $myrow->Minggu ?>" data-TglPengakuan="<?= $myrow->TglPengakuan ?>"></td>
											<td><?= htmlspecialchars($myrow->KdRAP) ?></td>
											<td><?= htmlspecialchars($myrow->Uraian) ?></td>
											<td><?= htmlspecialchars($myrow->Uom) ?></td>
											<td><?= ($myrow->PrdAwal == '' || $myrow->PrdAwal == NULL || $myrow->PrdAwal == '1900-01-01') ? '' : tgl_baru($myrow->PrdAwal) ?></td>
											<td><?= ($myrow->PrdAkhir == '' || $myrow->PrdAkhir == NULL || $myrow->PrdAkhir == '1900-01-01') ? '' : tgl_baru($myrow->PrdAkhir) ?></td>
											<td><?= $myrow->Minggu ?></td>
											<td><?= tgl_baru($myrow->TglPengakuan) ?></td>

											<td class="text-right" id="bobot_awal"><?= UbahDecimal_Nol_Lima($myrow->Bobot_Persen) ?></td>
											<td class="text-right"><?= UbahDecimal_Nol_Tiga($myrow->VolumeInputan) ?></td>
											<td class="text-center ">
												<button type="button" class="btn btn-link btn-md btnUpdateRPPM" data-NoUrut="<?= $Norut ?>" data-KdRAP="<?= htmlspecialchars($myrow->KdRAP) ?>" data-Uraian="<?= htmlspecialchars($myrow->Uraian) ?>" data-Uom="<?= htmlspecialchars($myrow->Uom) ?>" data-PrdAwal="<?= htmlspecialchars($myrow->PrdAwal) ?>" data-PrdAkhir="<?= htmlspecialchars($myrow->PrdAkhir) ?>" data-Minggu="<?= htmlspecialchars($myrow->Minggu) ?>" data-TglPengakuan="<?= htmlspecialchars($myrow->TglPengakuan) ?>" data-Bobot_Persen="<?= UbahDecimal_Nol_Tiga($myrow->Bobot_Persen) ?>" data-VolumeInputan="<?= UbahDecimal_Nol_Tiga($myrow->VolumeInputan) ?>" data-LedgerNo="<?= htmlspecialchars($myrow->LedgerNo) ?>" data-Vol="<?= $myrow->Vol ?>" data-HrgSatuan="<?= UbahDecimal_Nol_Dua($myrow->HrgSatuan) ?>" data-HrgRAB="<?= UbahDecimal_Nol_Dua($myrow->HrgRAB) ?>">UPDATE</button>
											</td>

										</tr>
									<?php endforeach ?>
								</tbody>
								<tfoot>
									<tr style="color:White;background-color:#5D7B9D;font-weight:bold;">
										<th colspan="9"></th>
										<th class="text-right"><?= $sum_Bobot_Persen ?></th>
										<th class="text-right"><?= $sum_VolumeInputan ?></th>
										<th class="text-right" style="display:none;"></th>
										<th></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
</div>

<div id="modalUpdate" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<form id="UpdateFormRPPM">


				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Update Form RPPM</h4>
				</div>
				<div class="modal-body">

					<div class="col-sm-12">
						<div class="table-responsive">
							<table width="100%" border="0">
								<tr>
									<td width="120px">No.</td>
									<td class="text-center" width="10px">:</td>
									<td width="30px">
										<input type="text" id="NoUrut_Update" name="NoUrut_Update" size="2" readonly>
									</td>
									<td></td>
								</tr>

							</table>
							<table width="100%" border="0" style="margin-top:5px;">
								<tr>
									<td width="120px">Kode RAP</td>
									<td class="text-center" width="10px">:</td>
									<td class="200px">
										<input type="text" id="KdRAP_Update" name="KdRAP_Update" class="form-control" readonly>
									</td>
								</tr>
							</table>
							<table width="100%" border="0" style="margin-top:5px;">
								<tr>
									<td width="120px">Uraian</td>
									<td class="text-center" width="10px">:</td>
									<td class="200px">
										<textarea class="form-control" id="Uraian_Update" name="Uraian_Update" readonly></textarea>
									</td>
								</tr>
							</table>
							<table width="100%" border="0" style="margin-top:5px;">
								<tr>
									<td width="120px">Satuan</td>
									<td class="text-center" width="10px">:</td>
									<td class="200px">
										<input type="text" id="Uom_Update" name="Uom_Update" class="form-control" readonly>
									</td>
								</tr>
							</table>


							<table width="100%" border="0" style="margin-top:5px;" hidden>
								<tr>
									<td width="120px">Hrg RAB</td>
									<td class="text-center" width="10px">:</td>
									<td class="200px">
										<input type="text" id="HrgRAB_Update" name="HrgRAB_Update" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" style="margin-top:5px;" hidden>
								<tr>
									<td width="120px">Hrg Satuan</td>
									<td class="text-center" width="10px">:</td>
									<td class="200px">
										<input type="text" id="HrgSatuan_Update" name="HrgSatuan_Update" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" style="margin-top:5px;">
								<tr>
									<td width="120px">Periode</td>
									<td class="text-center" width="10px">:</td>
									<td class="200px">
										<input type="date" id="PrdAwal_Update" name="PrdAwal_Update" class="form-control">
									</td>
									<td width="50px" class="text-center">s.d</td>
									<td class="200px">
										<input type="date" id="PrdAkhir_Update" name="PrdAkhir_Update" class="form-control">
									</td>
									<td width="30px" class="text-center"></td>
									<td width="80px" class="text-left">Minggu Ke : </td>
									<td width="80px">
										<input type="text" id="Minggu_Update" name="Minggu_Update" class="form-control text-right">
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" style="margin-top:5px;">
								<tr>
									<td width="120px">Tgl Pengakuan</td>
									<td class="text-center" width="10px">:</td>
									<td width="245px">
										<input type="date" id="TglPengakuan_Update" name="TglPengakuan_Update" class="form-control">
									</td>

									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" style="margin-top:5px;">
								<tr>
									<td width="120px">Volume Input</td>
									<td class="text-center" width="10px">:</td>
									<td width="245px">
										<input type="text" id="VolumeInputan_Update" name="VolumeInputan_Update" class="form-control text-right VolumeInputan_Update" autocomplete="off">
									</td>

									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" style="margin-top:5px;">
								<tr>
									<td width="120px">Bobot Persen (%)</td>
									<td class="text-center" width="10px">:</td>
									<td width="245px">
										<input type="text" id="Bobot_Persen_Update" name="Bobot_Persen_Update" class="form-control text-right" autocomplete="off">
									</td>

									<td></td>
								</tr>
							</table>



						</div>
					</div>

				</div>
				<div class="modal-footer">
					<input type="hidden" name="LedgerNo_Update" id="LedgerNo_Update" readonly>
					<!-- <input type="text" name="HrgSatuan_Update" id="HrgSatuan_Update" readonly> -->

					<input type="hidden" name="JobNo" value="<?= $JobNo ?>">
					<input type="hidden" name="Alokasi" value="<?= $Alokasi ?>">
					<input type="hidden" name="TotalHargKontrakBaru" value="0">
					<input type="hidden" name="TotalHargKontrakSatuanBaru" value="0">
					<!-- SUM Harga RAB -->
					<input type="hidden" name="SumHargaRAB2" value="<?= $sumFooter_TtlKontrak ?>">
					<!-- bobot RAB -->
					<input type="hidden" name="BobotRABBB" value="0">
					<input type="hidden" value="0" name="VolRAP">
					<input type="hidden" value="0" name="HrgSatuanFromRAP">
					<input type="hidden" value="0" name="HrgRABFromRAP">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="btnUpdate">Update</button>
				</div>
			</form>
		</div>

	</div>
</div>


<div id="ModalSaya" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Entry RPPM</h4>
			</div>
			<div class="modal-body" id="ModalBodySaya">
				<form id="FormAddRPPM">
					<div class="table-responsive">

						<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
							<tr>
								<td width="150px">Job No</td>
								<td class="text-center" width="30px">:</td>
								<td width="200px">
									<input type="text" id="JobNo" class="form-control input-sm" disabled>
								</td>
								<td></td>
							</tr>
						</table>

						<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
							<tr>
								<td width="150px">Alokasi</td>
								<td class="text-center" width="30px">:</td>
								<td width="200px">
									<input type="text" id="Alokasi" class="form-control input-sm" disabled>
								</td>
								<td></td>
							</tr>
						</table>

						<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
							<tr>
								<td width="150px">Periode</td>
								<td class="text-center" width="30px">:</td>
								<td width="100px">
									<input type="date" name="PrdAwal" id="PrdAwal" class="form-control">
								</td>
								<td class="text-center" width="30px">s.d</td>
								<td width="100px">
									<input type="date" name="PrdAkhir" id="PrdAkhir" class="form-control">
								</td>
								<td width="80px" style="padding-left:5px;">Minggu Ke</td>
								<td width="100px">
									<input type="text" name="Minggu" id="Minggu" class="form-control" placeholder="0">
								</td>
								<td></td>
							</tr>
						</table>

						<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
							<tr>
								<td width="150px">Tgl Pengakuan</td>
								<td class="text-center" width="30px">:</td>
								<td width="100px">
									<input type="date" name="TglPengakuan" id="TglPengakuan" class="form-control">
								</td>

								<td></td>
							</tr>
						</table>

						<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas" id="dynamic_field">
							<tr>
								<td width="150px">Kode RAP</td>
								<td class="text-center" width="30px">:</td>
								<td>

									<select name="KdRAP" id="KdRAP" class="form-control input-sm select2" width="100%" required>
										<option value="" data-Uom="" data-Vol="0" data-HrgRAB="0" data-TotalHargaKontrak="0" data-Header="" data-Uraian="" data-Tipe="">:: pilih salah satu ::</option>
										<?php foreach ($dataRAP as $data) : ?>
											<option value="<?= $data->KdRAP ?>" data-Uom="<?= $data->Uom ?>" data-Vol="<?= UbahDecimal_Nol_Tiga($data->Vol) ?>" data-HrgRAB="<?= UbahDecimal_Nol_Dua($data->HrgRAB) ?>" data-HrgRABDUA="<?= $data->HrgRAB ?>" data-TotalHargaKontrak="<?= UbahDecimal_Nol_Dua($data->JumlahHarga) ?>" data-Header="<?= $data->Header ?>" data-Uraian="<?= $data->Uraian ?>" data-Tipe="<?= $data->Tipe ?>"><?= $data->KdRAP . ' - ' . $data->Uraian ?></option>
										<?php endforeach ?>
									</select>

								</td>
							</tr>
						</table>



						<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
							<tr>
								<td width="150px">Uraian</td>
								<td class="text-center" width="30px">:</td>
								<td>
									<textarea name="Uraian" id="Uraian" class="form-control input-sm" readonly></textarea>
								</td>
								<!-- <td></td> -->
							</tr>
						</table>

						<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
							<tr>
								<td width="150px">Satuan</td>
								<td class="text-center" width="30px">:</td>
								<td width="200px">
									<input type="text" name="Uom" id="Uom" class="form-control input-sm" readonly>
								</td>
								<td></td>
							</tr>
						</table>

						<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
							<tr>
								<td width="150px">Volume</td>
								<td class="text-center" width="30px">:</td>
								<td width="200px">
									<input type="text" name="Vol" id="Vol" class="form-control input-sm text-right" readonly>
								</td>
								<td></td>
							</tr>
						</table>

						<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
							<tr>
								<td width="150px">Harga Satuan Kontrak (Rp)</td>
								<td class="text-center" width="30px">:</td>
								<td width="200px">
									<input type="text" name="HrgRAB" id="HrgRAB" class="form-control input-sm text-right" readonly>
								</td>
								<td></td>
							</tr>
						</table>

						<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
							<tr>
								<td width="150px">Total Satuan Kontrak (Rp)</td>
								<td class="text-center" width="30px">:</td>
								<td width="200px">
									<input type="text" id="TotalHargaKontrak" class="form-control input-sm text-right" readonly>
								</td>
								<td></td>
							</tr>
						</table>

						<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
							<tr>
								<td width="150px">Bobot (%)</td>
								<td class="text-center" width="30px">:</td>
								<td width="200px">
									<input type="text" name="Bobot_Persen" id="Bobot_Persen" class="form-control input-sm text-right" value="0" required>

								</td>
								<td class="text-red" style="font-size:16px !important;">&nbsp;&nbsp;&nbsp;*gunakan Titik (.) untuk decimal pada bobot</td>
								<td></td>
							</tr>
						</table>

						<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
							<tr>
								<td width="150px">Volume Input</td>
								<td class="text-center" width="30px">:</td>
								<td width="200px">
									<input type="text" name="VolumeInputan" id="VolumeInputan" class="form-control input-sm text-right" value="0" required>
								</td>
								<td></td>
							</tr>
						</table>

						<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
							<tr>
								<td width="150px">Total Harga (Rp)</td>
								<td class="text-center" width="30px">:</td>
								<td width="200px">
									<input type="text" name="TotalHarga" id="TotalHarga" class="form-control input-sm text-right" value="0" readonly>
								</td>
								<td></td>
							</tr>
						</table>

						<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
							<tr>
								<td width="150px"></td>
								<td class="text-center" width="30px">:</td>
								<td width="200px">

									<input type="" name="JobNo" value="<?= $JobNo ?>">
									<input type="" name="Header" id="Header">
									<input type="" name="Tipe" id="Tipe">
									<input type="" name="Alokasi" value="<?= $Alokasi ?>">

									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									<button type="submit" class="btn btn-primary btnsave">Save</button>
								</td>
								<td></td>
							</tr>
						</table>




					</div>
				</form>
			</div>
		</div>

	</div>
	<!-- </div> -->

	<script type="text/javascript">
		$(document).ready(function() {


			$(document).on('click', '#select_all', function() {
				$(".emp_checkbox").prop("checked", this.checked);
				$("#select_count").html($("input.emp_checkbox:checked").length + " Selected");
			});

			$(document).on('click', '.emp_checkbox', function() {
				if ($('.emp_checkbox:checked').length == $('.emp_checkbox').length) {
					$('#select_all').prop('checked', true);
				} else {
					$('#select_all').prop('checked', false);
				}
				$("#select_count").html($("input.emp_checkbox:checked").length + " Selected");

			});

			$('#hapus_rppm').on('click', function(e) {

				var employee = [];


				$(".emp_checkbox:checked").each(function() {
					employee.push($(this).attr('data-LedgerNo'));
				});
				if (employee.length <= 0) {
					alert("belum di-centang");
				} else {
					WRN_PROFILE_DELETE = "Anda yakin ingin hapus data ini?";
					var checked = confirm(WRN_PROFILE_DELETE);
					if (checked == true) {
						var selected_values = employee.join(",");
						// alert(selected_values)
						// var NoKO = $('#NoKO option:selected').val();
						// var JobNo = $('#FormPD_Keuangan').attr('data-JobNo');

						$.ajax({
								url: '<?= site_url('BasicData/FrmRPPM_C/HapusRPPM') ?>',
								type: 'POST',
								dataType: 'html',
								data: {
									'LedgerNo': selected_values,
								},
								beforeSend: function() {
									$(this).text('Loading...').attr('disabled', true);
								},
								complete: function() {
									$(this).text('Hapus History').attr('disabled', false);
								}
							})
							.done(function(data) {
								// alert(data);
								// return false;
								if (data == 'berhasil') {
									toastr.success('', 'Deleted');
								} else if (data == 'gagal') {
									toastr.info('', 'Failed');
								} else {
									toastr.error('', 'Error');
								}

								table_RPPM('1');
							})
							.fail(function() {
								alert('Terjadi kesalahan saat menghapus data');
							});


					}
				}

			});

			var BobotAsli;
			var VolAsli;
			var HrgRAB;
			var HrgSatuanFromRAP;
			var HrgRABFromRAP;
			$(document).on('click', '.btnUpdateRPPM', function() {
				var NoUrut = $(this).attr('data-NoUrut');
				var KdRAP = $(this).attr('data-KdRAP');
				var Uom = $(this).attr('data-Uom');
				var Uraian = $(this).attr('data-Uraian');
				var PrdAwal = $(this).attr('data-PrdAwal');
				var PrdAkhir = $(this).attr('data-PrdAkhir');
				var Minggu = $(this).attr('data-Minggu');
				var TglPengakuan = $(this).attr('data-TglPengakuan');
				var Bobot_Persen = $(this).attr('data-Bobot_Persen');
				var VolumeInputan = $(this).attr('data-VolumeInputan');
				var LedgerNo = $(this).attr('data-LedgerNo');
				var HrgSatuan = $(this).attr('data-HrgSatuan');
				HrgRAB = $(this).attr('data-HrgRAB');
				BobotAsli = $(this).attr('data-BobotAsli');
				// VolAsli = $(this).attr('data-Vol');

				// console.log('No Urut : '+NoUrut+'\n'+'KdRAP : '+KdRAP+'\n'+'Uraian : '+Uraian+'\n'+'PrdAwal : '+PrdAwal+'\n'+'PrdAkhir : '+PrdAkhir+'\n'+'Minggu : '+Minggu+'\n'+'TglPengakuan : '+TglPengakuan+'\n'+'Bobot_Persen : '+Bobot_Persen+'\n'+'VolumeInputan : '+VolumeInputan+'\n');


				$('#UpdateFormRPPM #NoUrut_Update').val(NoUrut);
				$('#UpdateFormRPPM #KdRAP_Update').val(KdRAP);
				$('#UpdateFormRPPM #Uraian_Update').val(Uraian);
				$('#UpdateFormRPPM #Uom_Update').val(Uom);

				$('#UpdateFormRPPM #PrdAwal_Update').val(PrdAwal);
				$('#UpdateFormRPPM #PrdAkhir_Update').val(PrdAkhir);
				$('#UpdateFormRPPM #Minggu_Update').val(Minggu);

				$('#UpdateFormRPPM #TglPengakuan_Update').val(TglPengakuan);
				$('#UpdateFormRPPM #Bobot_Persen_Update').val(Bobot_Persen);
				$('#UpdateFormRPPM #VolumeInputan_Update').val(VolumeInputan);
				$('#UpdateFormRPPM #LedgerNo_Update').val(LedgerNo);

				$('#UpdateFormRPPM #HrgRAB_Update').val(HrgRAB);
				$('#UpdateFormRPPM #HrgSatuan_Update').val(HrgSatuan);

				var JobNo = $('#JobNo').val();
				var Alokasi = $('#Alokasi').val();

				$.ajax({
					type: "POST",
					url: "<?= base_url('BasicData/FrmRPPM_C/GetBobotRAB2'); ?>",
					data: {
						KdRAP: KdRAP,
						JobNo: JobNo,
						Alokasi: Alokasi,
					},
					success: function(response) {
						const res = JSON.parse(response)
						BobotAsli = res.BobotRAB;
						VolAsli = res.VolRAP;
						HrgSatuanFromRAP = res.HargaSatuan
						HrgRABFromRAP = res.HargaRAB
						$("#UpdateFormRPPM [name='TotalHargKontrakBaru']").val(res.TotalHargaKontrak);
						$("#UpdateFormRPPM [name='TotalHargKontrakSatuanBaru']").val(res.TotalHargaKontrakSatuan);
						$("#UpdateFormRPPM [name='HrgSatuan_Update']").val(res.HargaSatuan);
						$("#UpdateFormRPPM [name='VolRAP']").val(VolAsli);
						$("#UpdateFormRPPM [name='HrgSatuanFromRAP']").val(HrgSatuanFromRAP);
						$("#UpdateFormRPPM [name='HrgRABFromRAP']").val(HrgRABFromRAP);
					},
					error: function(error) {
						console.log("Error:", error);
					}


				})


				$('#modalUpdate').modal('show');
			});

			$("#UpdateFormRPPM #VolumeInputan_Update, #UpdateFormRPPM #Bobot_Persen_Update").on('input', function(e) {
				var inputValue = $(this).val();
				var filteredValue = inputValue.replace(/[^0-9.-]/g, '');

				if (filteredValue.indexOf('-') > 0) {
					filteredValue = filteredValue.replace(/-/g, '');
					filteredValue = '-' + filteredValue;
				}

				if (filteredValue.indexOf('.') == 0) {
					filteredValue = '0' + filteredValue;
				}

				var parts = filteredValue.split('.');
				if (parts[1] && parts[1].length > 3) {
					parts[1] = parts[1].substr(0, 3);
					filteredValue = parts.join('.');
				}

				$(this).val(filteredValue);
			});

			$("#UpdateFormRPPM #VolumeInputan_Update, #UpdateFormRPPM #Bobot_Persen_Update").focusin(function() {
				if (this.value == '0.000' || this.value == '0') {
					$(this).val('');
				}
			});

			$("#UpdateFormRPPM #VolumeInputan_Update, #UpdateFormRPPM #Bobot_Persen_Update").focusout(function() {
				const noUrut = $('#NoUrut_Update').val()
				if (this.value == '') {
					$(this).val('0.000');
				} else {
					var VolUpdate = parseFloat(this.value);
					VolUpdate = Number(VolUpdate).toFixed(3);
					VolUpdate = FormatVol_Buatan(VolUpdate);
					$(this).val(VolUpdate);
				}

				// cek bobot RAB2
				var HrgRABBB = $("[name='TotalHargKontrakBaru']").val()
				var SumHargaRABBB = $("[name='SumHargaRAB2']").val()
				var BobotTTT = ((HrgRABBB / SumHargaRABBB) * 100).toFixed(3)
				// var BobotTTT = HrgRABBB
				$("[name='BobotRABBB']").val(BobotTTT)
				// cek bobot RAB2

				GetBobotPersen(noUrut)

			});

			function GetBobotPersen(NoUrut) {
				var param_BobotPersen = 0;
				var Bobot = BobotAsli.toString()
				var VolumeInputan = ($('#VolumeInputan_Update').val()).split(',').join('').split('$').join('');
				var BobotRAB = (Bobot).split(',').join('').split('$').join('');
				VolumeInputan = parseFloat(VolumeInputan);
				BobotRAB = parseFloat(BobotRAB);
				BobotRAB = BobotRAB.toFixed(3)
				VolRAP = parseFloat(VolAsli);
				if (VolumeInputan == 0 || VolumeInputan == '') {
					param_BobotPersen = 0;
				} else {
					param_BobotPersen = (VolumeInputan / VolRAP) * BobotRAB;
				}
				// console.log('Volume Inputan : ' + VolumeInputan + '\n Bobot RAB : ' + BobotRAB + '\n Vol : ' + VolRAP + '\n');
				// console.log(param_BobotPersen)
				$('#Bobot_Persen_Update').val(param_BobotPersen.toFixed(3));
				// $('#Bobot_Persen_'+NoUrut).val(Pembulatan(param_BobotPersen));

			}

			$('#UpdateFormRPPM').submit(function(event) {
				event.preventDefault();

				if ($('#UpdateFormRPPM #TglPengakuan_Update').val() == '') {
					toastr.info('', 'TglPengakuan masih kosong');

					return false;
				}

				if ($('#UpdateFormRPPM #Minggu_Update').val() == '' || $('#UpdateFormRPPM #Minggu_Update').val() == 0 || $('#UpdateFormRPPM #Minggu_Update').val() == '0') {
					toastr.info('', 'Minggu masih Kosong / Nol ');
					return false;
				}

				if ($('#UpdateFormRPPM #Bobot_Persen_Update').val() == '' || $('#UpdateFormRPPM #Bobot_Persen_Update').val() == 0 || $('#UpdateFormRPPM #Bobot_Persen_Update').val() == '0') {
					toastr.info('', 'Bobot masih Kosong / Nol ');
					return false;
				}

				jQuery.ajax({
					url: "<?= site_url('BasicData/FrmRPPM_C/AksiUpdateRPPM') ?>",
					type: 'POST',
					dataType: 'html',
					data: $(this).serialize(),
					beforeSend: function() {
						$('#UpdateFormRPPM #btnUpdate').text('Loading...').attr('disabled', true);
					},
					complete: function(xhr, textStatus) {
						$('#UpdateFormRPPM #btnUpdate').text('Update').attr('disabled', false);
					},
					success: function(data, textStatus, xhr) {
						// return false;

						// $('#myModal').modal('hide');
						if (data == 'berhasil') {
							window.location.href = "<?= site_url('BasicData/FrmRPPM_C') ?>";
							// toastr.success('','Updated');
						} else {
							toastr.error('', 'Gagal Update Data');
							// alert('Gagal Update Data');
						}

					},
					error: function(xhr, textStatus, errorThrown) {
						alert('Something Wrong');
					}
				});


			})

		})

		$(function() {

			$('#mytable').DataTable({
				pageLength: 50,
				columnDefs: [{
						targets: 0,
						visible: false
					},
					{
						targets: 1,
						orderable: false
					},
					{
						targets: 2,
						orderable: false
					},
				],
				order: [
					[0, 'asc']
				],
				fixedHeader: {
					header: true,
					footer: true
				}
			});


			$('.select2').select2({
				dropdownCssClass: "font12",
			});


			$("#btnTambah").on("click", function() {
				var JobNo = $(this).attr('data-JobNo');
				var JobNm = $(this).attr('data-JobNm');

				var Alokasi = $(this).attr('data-Alokasi');
				var KeteranganAlokasi = $(this).attr('data-KeteranganAlokasi');
				$('#ModalSaya').modal('show');

				$('#FormAddRPPM #JobNo').val(JobNo + ' - ' + JobNm);
				$('#FormAddRPPM #Alokasi').val(Alokasi + ' - ' + KeteranganAlokasi);

				$('#FormAddRPPM #KdRAP').val('').trigger('change');
				$('#FormAddRPPM #Uraian').val('');
				$('#FormAddRPPM #Vol').val(0);
				$('#FormAddRPPM #HrgRAB').val(0);
				$('#FormAddRPPM #TotalHargaKontrak').val(0);

				$('#FormAddRPPM #VolumeInputan').val(0);
				$('#FormAddRPPM #TotalHarga').val(0);

				$('#FormAddRPPM #Header').val('');
				$('#FormAddRPPM #Tipe').val('');

				// == START DITAMBAHKAN 25 AGUSTUS 2023 ==
				$('#FormAddRPPM #PrdAwal').val('');
				$('#FormAddRPPM #PrdAkhir').val('');
				$('#FormAddRPPM #TglPengakuan').val('');
				$('#FormAddRPPM #Minggu').val(0);
				$('#FormAddRPPM #Bobot_Persen').val(0);
				// == END DITAMBAHKAN 25 AGUSTUS 2023 ==

			});

			$("#FormAddRPPM #KdRAP").on("change", function() {
				var Uom = $('#FormAddRPPM #KdRAP option:selected').attr('data-Uom');
				var Vol = $('#FormAddRPPM #KdRAP option:selected').attr('data-Vol');
				var HrgRAB = $('#FormAddRPPM #KdRAP option:selected').attr('data-HrgRAB');
				var TotalHargaKontrak = $('#FormAddRPPM #KdRAP option:selected').attr('data-TotalHargaKontrak');
				var Header = $('#FormAddRPPM #KdRAP option:selected').attr('data-Header');
				var Uraian = $('#FormAddRPPM #KdRAP option:selected').attr('data-Uraian');

				var Tipe = $('#FormAddRPPM #KdRAP option:selected').attr('data-Tipe');

				$('#FormAddRPPM #Uraian').val('');
				$('#FormAddRPPM #Vol').val(0);
				$('#FormAddRPPM #HrgRAB').val(0);
				$('#FormAddRPPM #TotalHargaKontrak').val(0);

				$('#FormAddRPPM #VolumeInputan').val(0);
				$('#FormAddRPPM #TotalHarga').val(0);

				$('#FormAddRPPM #Uraian').val(Uraian);
				$('#FormAddRPPM #Uom').val(Uom);
				$('#FormAddRPPM #Vol').val(Vol);
				$('#FormAddRPPM #HrgRAB').val(HrgRAB);
				$('#FormAddRPPM #TotalHargaKontrak').val(TotalHargaKontrak);

				$('#FormAddRPPM #Header').val('');
				$('#FormAddRPPM #Header').val(Header);

				$('#FormAddRPPM #Tipe').val('');
				$('#FormAddRPPM #Tipe').val(Tipe);

				// == START DITAMBAHKAN 25 AGUSTUS 2023 ==
				// $('#FormAddRPPM #PrdAwal').val('');
				// $('#FormAddRPPM #PrdAkhir').val('');
				// $('#FormAddRPPM #TglPengakuan').val('');
				$('#FormAddRPPM #Minggu').val(0);
				$('#FormAddRPPM #Bobot_Persen').val(0);
				// == END DITAMBAHKAN 25 AGUSTUS 2023 ==


			});

			var formatUang_Nol_Tiga = function(num) {
				var str = num.toString().replace("", ""),
					parts = false,
					output = [],
					i = 1,
					formatted = null;
				if (str.indexOf(".") > 0) {
					parts = str.split(".");
					str = parts[0];
				}
				str = str.split("").reverse();
				for (var j = 0, len = str.length; j < len; j++) {
					if (str[j] != ",") {
						output.push(str[j]);
						if (i % 3 == 0 && j < (len - 1)) {
							output.push(",");
						}
						i++;
					}
				}
				formatted = output.reverse().join("");
				return ("" + formatted + ((parts) ? "." + parts[1].substr(0, 3) : ""));
			};

			$("#FormAddRPPM #VolumeInputan, #FormAddRPPM #Bobot_Persen").on('input', function(e) {
				var inputValue = $(this).val();
				var filteredValue = inputValue.replace(/[^0-9.-]/g, '');

				if (filteredValue.indexOf('-') > 0) {
					filteredValue = filteredValue.replace(/-/g, '');
					filteredValue = '-' + filteredValue;
				}

				if (filteredValue.indexOf('.') == 0) {
					filteredValue = '0' + filteredValue;
				}

				var parts = filteredValue.split('.');
				if (parts[1] && parts[1].length > 3) {
					parts[1] = parts[1].substr(0, 3);
					filteredValue = parts.join('.');
				}

				$(this).val(filteredValue);
			});

			$("#FormAddRPPM #VolumeInputan, #FormAddRPPM #Bobot_Persen").focusin(function() {
				if (this.value == '0.000' || this.value == '0') {
					$(this).val('');
				}
			});

			$("#FormAddRPPM #VolumeInputan, #FormAddRPPM #Bobot_Persen").focusout(function() {
				if (this.value == '') {
					$(this).val('0.000');
				} else {
					var Vol = parseFloat(this.value);
					Vol = Number(Vol).toFixed(3);
					Vol = FormatVol_Buatan(Vol);
					$(this).val(Vol);
				}
			});



			$("#FormAddRPPM").on("submit", function(event) {
				event.preventDefault();

				// if ($('#FormAddRPPM #PrdAwal').val() == '') {
				// 	toastr.info('','Periode masih kosong');
				// 	return false;
				// }

				// if ($('#FormAddRPPM #PrdAkhir').val() == '') {
				// 	toastr.info('','Periode masih kosong');
				// 	return false;
				// }

				if ($('#FormAddRPPM #TglPengakuan').val() == '') {
					toastr.info('', 'TglPengakuan masih kosong');
					return false;
				}

				if ($('#FormAddRPPM #Minggu').val() == '' || $('#FormAddRPPM #Minggu').val() == 0 || $('#FormAddRPPM #Minggu').val() == '0') {
					toastr.info('', 'Minggu masih Kosong / Nol ');
					return false;
				}

				if ($('#FormAddRPPM #Bobot_Persen').val() == '' || $('#FormAddRPPM #Bobot_Persen').val() == 0 || $('#FormAddRPPM #Bobot_Persen').val() == '0') {
					toastr.info('', 'Bobot masih Kosong / Nol ');
					return false;
				}

				SimpanRPPM();
			});

		});



		function SimpanRPPM() {

			var Header = $('#FormAddRPPM #KdRAP option:selected').attr('data-Header');
			var Tipe = $('#FormAddRPPM #KdRAP option:selected').attr('data-Tipe');
			if (Tipe == 'Header') {
				alert('tidak dapat menyimpan Kode RAP Tipe Header');
				// Swal.fire('Alert!','tidak dapat menyimpan Kode RAP Tipe Header','info');
				return false;
			}

			var JobNo = $('#btnTambah').attr('data-JobNo');
			var Alokasi = $('#btnTambah').attr('data-Alokasi');

			jQuery.ajax({
				url: "<?= site_url('BasicData/FrmRPPM_C/SimpanRPPM') ?>",
				type: 'POST',
				dataType: 'HTML',
				data: $('#FormAddRPPM').serialize(),
				beforeSend: function() {

					$('.btnsave').attr('disabled', true);
				},
				complete: function(xhr, textStatus) {

					$('.btnsave').attr('disabled', false);
					$('#ModalSaya').modal('hide');

				},
				success: function(data, textStatus, xhr) {

					// alert(data);
					// console.log(data);
					// return false;

					if (data == 'berhasil') {

						window.location.href = "<?= site_url('BasicData/FrmRPPM_C/index/') ?>" + JobNo + '/' + Alokasi;
					} else {
						// Swal.fire('Alert!','terjadi kesalahan','error');
						alert('terjadi kesalahan');
					}


				},
				error: function(xhr, textStatus, errorThrown) {
					// Swal.fire('Alert!','terjadi kesalahan','error');
					alert('terjadi kesalahan');
				}
			});
		}

		function HitungTotalHarga() {

			var HrgRAB = $('#FormAddRPPM #KdRAP option:selected').attr('data-HrgRABDUA');
			var VolumeInputan = $('#FormAddRPPM #VolumeInputan').val();

			jQuery.ajax({
				url: "<?= site_url('BasicData/FrmRPPM_C/HitungTotalHarga') ?>",
				type: 'POST',
				dataType: 'HTML',
				data: {
					'HrgRAB': HrgRAB,
					'VolumeInputan': VolumeInputan
				},
				beforeSend: function() {

				},
				complete: function(xhr, textStatus) {

				},
				success: function(data, textStatus, xhr) {
					$('#TotalHarga').val(data);
				},
				error: function(xhr, textStatus, errorThrown) {
					// Swal.fire('Alert!','terjadi kesalahan','error');
					alert('terjadi kesalahan');
				}
			});
		}

		function FormatVol_Buatan(num) {
			var str = num.toString().replace("", ""),
				parts = false,
				output = [],
				i = 1,
				formatted = null;
			if (str.indexOf(".") > 0) {
				parts = str.split(".");
				str = parts[0];
			}
			str = str.split("").reverse();
			for (var j = 0, len = str.length; j < len; j++) {
				if (str[j] != ",") {
					output.push(str[j]);
					if (i % 3 == 0 && j < (len - 1)) {
						output.push(",");
					}
					i++;
				}
			}
			formatted = output.reverse().join("");
			return ("" + formatted + ((parts) ? "." + parts[1].substr(0, 3) : ""));
		}

		$('#btnExportExcel').click(function(e) {
			e.preventDefault()
			var url = $(this).attr('href')
			var urlPath = url.split('/')
			const prdAwal = urlPath[urlPath.length - 1]
			const prdAkhir = urlPath[urlPath.length - 2]
			const MingguKe = urlPath[urlPath.length - 3]

			if (prdAwal == '' || prdAkhir == '' || MingguKe == '0') {
				Swal.fire('Error!', 'Form tidak valid', 'error');
			} else {
				window.location.href = url
			}
		})
	</script>