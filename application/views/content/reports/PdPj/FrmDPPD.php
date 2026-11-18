<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<a onclick="history.back()" class="btn btn-warning btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<!-- <a href="#add" data-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah</a>
						<br /><br /> -->
							<div class="box-body">
								<!-- <div class="row"> -->
								<form id="myform">

									<div class="form-group row">
										<label for="inputName" class="col-sm-2 col-form-label" style="font-size:10px !important;">JobNo</label>
										<div class="col-sm-3">
											<select class="form-control" name="JobNo" id="JobNo" required>
												<option value="">-- Pilih Job.No --</option>
												<?php foreach ($Job as $job) : ?>
													<option value="<?php echo $job->JobNo ?>"><?php echo $job->JobNo ?> -<?php echo $job->JobNm ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>

									<div class="form-group row">
										<label for="inputName" class="col-sm-2 col-form-label" style="font-size:10px !important;">Alokasi</label>
										<div class="col-sm-3">
											<select class="form-control" name="Alokasi" id="Alokasi" required>
												<option value="">-- Pilih Alokasi --</option>
												<?php foreach ($AksesAlokasi as $data) : ?>
													<option value="<?php echo $data->Alokasi ?>"><?php echo $data->Alokasi ?> - <?php echo $data->Keterangan ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>

									<div class="form-group row">
										<div class="col-sm-2">
											<label for="inputName" class="col-sm-4 col-form-label" style="font-size:10px !important;">Periode</label>
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
											<label for="inputName" class="col-sm-4 col-form-label" style="font-size:10px !important;">ALL Periode</label>
										</div>
									</div>

									<div class="form-group row">
										<label for="inputName" class="col-sm-2 col-form-label"></label>
										<div class="col-sm-10">
											<button type="submit" id="cari" class="btn btn-primary"><i class="fa fa-search"></i></button>
											<button type="submit" id="Print" class="btn btn-warning"><i class="fa fa-print"></i></button>
										</div>
									</div>

								</form>
							</div>
						</div>
						<!-- <div class="container"> -->
						<div class="col-md-12">
							<div class="container">
								<div class="col-md-12">
									<div class="panel panel-default">
										<div class="panel-heading">
											Job No :
											</p>
											Alokasi :
											</p>
											Periode :
										</div>
										<div class="panel-body">
											<table class="table table-condensed table-striped">
												<thead>
													<tr>
														<th></th>
														<th>No PD</th>
														<th>Tgl PD</th>
														<th>Deskripsi</th>
														<th>Prd Awal</th>
														<th>Prd Akhir</th>
														<th>No KO</th>
														<th>Nama</th>
														<th>Total PD</th>
														<th>Tgl Bayar</th>
														<th>Via</th>
														<th>Rek Pengirim</th>
														<th>Rek Penerima</th>
														<th>No PJ</th>
														<th>Tgl PJ</th>
														<th>Total PJ</th>
														<th>Tipe Form</th>
													</tr>
												</thead>

												<tbody>
													<?php foreach ($query1 as $q1) { ?>
														<tr data-toggle="collapse" data-target="#demo1<?php echo $q1->NoPD ?>" class="accordion-toggle">
															<td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span></button></td>
															<td><?= $q1->NoPD ?></td>
															<td><?= $q1->TglPD ?></td>
															<td><?= $q1->Deskripsi ?></td>
															<td><?= $q1->PrdAwal ?></td>
															<td><?= $q1->PrdAkhir ?></td>
															<td><?= $q1->NoKO ?></td>
															<td><?= $q1->Nama ?></td>
															<td><?= number_format($q1->TotalPD) ?></td>
															<td><?= $q1->TglBayar ?></td>
															<td><?= $q1->CaraBayar ?></td>
															<td><?= $q1->Bank ?> / <?= $q1->AtasNama ?> / <?= $q1->NoRek ?> </td>
															<td><?= $q1->Bank ?> / <?= $q1->AtasNama ?> / <?= $q1->NoRek ?></td>
															<td><?= $q1->NoPJ ?></td>
															<td><?= $q1->TglPJ ?></td>
															<td><?= number_format($q1->TotalPJ)  ?></td>
															<td><?= $q1->TipeForm ?></td>
														</tr>

														<tr>
															<td colspan="12" class="hiddenRow">
																<div class="accordian-body collapse" id="demo1<?php echo $q1->NoPD ?>">
																	<table class="table table-striped">
																		<thead>
																			<tr>
																				<th>No</th>
																				<th>KdRAP</th>
																				<th>Uraian</th>
																				<th>Vol</th>
																				<th>Uom</th>
																				<th>Hrg Satuan</th>
																				<th>Sub Total</th>
																				<th>Uraian PJ</th>
																				<th>Vol</th>
																				<th>Uom</th>
																				<th>Hrg Satuan</th>
																				<th>Sub Total</th>
																			</tr>
																		</thead>

																		<tbody>
																			<?php $no = 1;
																			foreach ($UraianPD as $upd) : ?>
																				<tr>
																					<td><?= $no++ ?></td>
																					<td><?= $upd->KdRAP ?></td>
																					<td><?= $upd->Uraian ?></td>
																					<td><?= $upd->Vol ?></td>
																					<td><?= $upd->Vol ?></td>
																					<td><?= $upd->Vol ?></td>
																					<td><?= $upd->Vol ?></td>
																					<td><?= $upd->Vol ?></td>
																					<td><?= $upd->Vol ?></td>
																					<td><?= $upd->Vol ?></td>
																					<td><?= $upd->Vol ?></td>
																				</tr>
																			<?php endforeach; ?>
																		</tbody>
																	</table>

																</div>
															</td>
														</tr>

													<?php } ?>

												</tbody>
											</table>
										</div>

									</div>

								</div>
							</div>




						</div>
						<!-- </div> -->


					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<!-- <script>
$(document).ready(function() {
$('#TblQuery1').DataTable();
});
</script> -->
