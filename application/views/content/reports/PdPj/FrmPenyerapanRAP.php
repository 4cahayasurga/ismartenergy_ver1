<!-- <?php
		// error_reporting(0);
		// print_rr($query1);
		// exit;


		?> -->
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1><?php echo $judul ?></h1>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>
	<section class="content-header">
		<a onclick="history.back()" class="btn btn-warning btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
	</section>

	<!-- Main content -->
	<section class="content-header">
		<div class="row" id="MOD-IN">
			<div class="col-md-12">
				<div class="box box-warning">
					<div class="box-header with-border">
						<div class="box box-solid">
							<div class="box-header with-border">
								<div class="box-header bg-blue-gradient">
									<h3 class="box-title"></h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
										<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
									</div>
								</div>
							</div>

							<div class="box-body">
								<!-- <div class="row"> -->
								<form id="myform">

									<div class="form-group row">
										<label for="inputName" class="col-sm-2 col-form-label" style="font-size:10px !important;">JobNo</label>
										<div class="col-sm-3">
											<select class="form-control" name="JobNo" id="JobNo" required>
												<option value="">-- Pilih Job.No --</option>
												<?php foreach ($Job as $job) : ?>
													<option value="<?php echo $job->JobNo ?>"><?php echo $job->JobNo ?> - <?php echo $job->JobNm ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>

									<div class="form-group row">
										<label for="inputName" class="col-sm-2 col-form-label" style="font-size:10px !important;">Alokasi</label>
										<div class="col-sm-3">
											<select class="form-control" name="Alokasi" id="Alokasi" required>
												<option value="">-- Pilih Alokasi --</option>
												<?php foreach ($AksesAlokasi as $alokasi) : ?>
													<option value="<?php echo $alokasi->Alokasi ?>"><?php echo $alokasi->Alokasi ?> -<?php echo $alokasi->Keterangan ?> </option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>

									<div class="form-group row">
										<div class="col-sm-2">
											<label for="inputName" class="col-sm-4 col-form-label" style="font-size:10px !important;">Tanggal PD</label>
										</div>
										<div class="col-sm-3">
											<label style="font-size:10px !important;">Dari Tgl</label>
											<input type="date" style="font-size:10px !important;" name="DariTgl" id="DariTgl" value="" class="form-control datepicker">
										</div>
										<div class="col-sm-3">
											<label style="font-size:10px !important;">Sampai Tgl</label>
											<input style="font-size:10px !important;" type="date" name="SampaiTgl" id="SampaiTgl" value="" class="form-control">
										</div>
									</div>

									<div class="form-group row">
										<div class="col-sm-6">
											<label for="inputName" class="col-sm-4 col-form-label" style="font-size:10px !important;"></label>
											<input type="checkbox" name="AllPeriode" id="AllPeriode">
											<label for="inputName" class="col-sm-4 col-form-label" style="font-size:10px !important;">Print Incl Detail PD</label>
										</div>
									</div>

									<div class="form-group row">
										<label for="inputName" class="col-sm-2 col-form-label"></label>
										<div class="col-sm-5">
											<button type="submit" id="cari" class="btn btn-primary"><i class="fa fa-search"></i></button>
											<button type="submit" id="print" class="btn btn-warning"><i class="fa fa-print"></i></button>
										</div>

									</div>

								</form>
							</div>

							<div class="col-md-12">
								<table id="TblQuery1" class="table table-bordered table-striped table-hover table-condensed">
									<thead>
										<tr>
											<th>No</th>
											<th>Tanggal</th>
											<th>Form</th>
											<th>No PD</th>
											<th>Job Name</th>
											<th>Nama Penerima</th>
											<th>Rekening Penerima</th>
											<th>Bank Penerima</th>
											<th>Nilai Transfer</th>
											<th>Bank Pengirim</th>
											<th>NO Cek/BG</th>
											<th>Rekening Pengirim</th>
											<th>No Kontrak</th>
											<th>Keterangan</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="col-md-12">
								<!-- <div class="panel panel-default"> -->
								<!-- <div class="panel-body"> -->
								<table id="TblQuery1" class="table table-bordered table-striped table-hover table-condensed">
									<thead>
										<tr>
											<th></th>
											<th>Kode RAP</th>
											<th>Uraian</th>
											<th>Uom</th>
											<th>Vol</th>
											<th>Harga Satuan</th>
											<th>Total</th>
											<th>Sudah Terserap</th>
											<th>%</th>
											<th>Sisa RAP</th>
										</tr>
									</thead>
									<tbody>
										<!-- <?php
												$totalserap = 0;
												$persentase = 0;
												$persentase1 = 0;
												$Total = 0;
												$persen = 0;
												$cepe = 0;

												?> -->
										<?php
										$cepe = 0.01;
										$Vol_var = 0;
										$HrgSatuan_var = 0;
										$totalserap = 0;

										?>

										<!-- <?php foreach ($query1 as $q1) {

													$Vol_var = KhususDecimal($q1->Vol);
													$HrgSatuan_var = round($q1->HrgSatuan);
													$cepe = 0.01;
													$totalserap = round($q1->TotalTerserap);
													$Total = $Vol_var * $HrgSatuan_var;
													$persentase = $totalserap / ($Total ?: 1);
													$persentase = $persentase * $cepe;
													$sisa = $Total - $totalserap;

												?> -->
										<tr data-toggle="collapse" data-target="#demo1<?php echo $q1->KdRAP ?>" class="accordion-toggle">
											<!-- <td><button class="btn btn-default btn-xs"><span class="fa fa-plus-circle"></span></button></td>
												<td><?php echo $q1->KdRAP ?></td>
												<td><?php echo $q1->Uraian ?></td>
												<td><?php echo $q1->Uom ?></td>
												<td><?php echo KhususDecimal($q1->Vol) ?></td>
												<td><?php echo number_format($q1->HrgSatuan) ?></td>
												<td><?php echo number_format($Total) ?></td>
												<td><?php echo number_format($q1->TotalTerserap) ?></td>
												<td><?php echo $persentase ?></td>
												<td><?php echo number_format($sisa) ?></td> -->
										</tr>

										<tr>
											<td colspan="12" class="hiddenRow">
												<div class="accordian-body collapse" id="demo1<?php echo $q1->KdRAP ?>">
													<div class="nav-tabs-custom">

														<div class="tab-content" id="pills-tabContent">
															<div class="tab-pane fade show active" id="UraianPD<?php echo $q1->KdRAP ?>" role="tabpanel" aria-labelledby="pills-home-tab">
																<table id="TblQueryUraianPD" class="table table-bordered table-striped table-hover table-condensed">
																	<thead>
																		<tr>
																			<th>No Ref</th>
																			<th>Uraian</th>
																			<th>Vol</th>
																			<th>UOM</th>
																			<th>Hrg Satuan</th>
																			<th>Total</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php foreach ($DetailPenyerapan as $dp) : ?>
																			<tr>
																				<td><?= $dp->NoRef ?></td>
																				<td><?= $dp->Uraian ?></td>
																				<td><?= $dp->Vol ?></td>
																				<td><?= $dp->Uom ?></td>
																				<td><?= $dp->HrgSatuan ?></td>
																				<td><?= $dp->Vol ?> * <?= $dp->HrgSatuan ?></td>
																			</tr>
																		<?php endforeach; ?>
																	</tbody>
																</table>
															</div>

														</div>
													</div>
												</div>
											</td>
										</tr>
										<!-- <?php } ?> -->
									</tbody>

								</table>
								<!-- </div> -->

								<!-- </div> -->

							</div>
							<!-- </div> -->


						</div>
					</div>
				</div>
			</div>
		</div>
</div>
</section>
</div>
