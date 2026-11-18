<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
			<label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $jb->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $jb->JobNm ?></label>
			<br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $jb->Lokasi ?></label>

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<a href="<?= site_url('job/sub_job/' . $jb->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
		<!-- <a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a> -->
	</section>

	<!-- <Section class="content"> -->
	<section class="content-header">
		<div class="row" id="MOD-IN">
			<div class="col-md-12">
				<div class="box box-warning">
					<div class="box-header with-border">
						<div class="box box-solid">
							<div class="box-header with-border">
								<div class="box-header bg-blue-gradient">
									<h3 class="box-title">LIST KO / PO </h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
										<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
									</div>
								</div>
							</div>

							<div class="box-body">
								<table id="tblAddKO" class="table table-bordered table-striped text-center">
									<thead>
										<tr>
											<th>No. KO</th>
											<th>Tgl KO</th>
											<th>Penerima Kontrak</th>
											<th>Tipe KO</th>
											<th>Addendum/ <br> Revisi Ke</th>
											<th>Total KO (Rp)</th>
											<th>KO Approved By</th>
											<th>KO Approved ON</th>
											<th>Keterangan</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($ListKO as $lk => $value) : ?>
											<tr>
												<td><?= $value->NoKO ?></td>
												<td><?= $value->TglKO ?></td>
												<td><?= $value->VendorNm ?></td>
												<td><?= $value->KategoriId ?></td>
												<td><?= $value->AddendumKe ?></td>
												<td><?= number_format($value->SubTotal) ?></td>
												<td><?= $value->ApprovedBy ?></td>
												<td><?= $value->TimeApproved ?></td>
												<td>
													<a type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target=".bd-example-modal-lg<?php echo $value->NoKO ?>">Addendum</a>
													<a href="<?php echo base_url('job/update_data1/' . $value->NoKO) ?>" class="btn btn-warning badge btn-xs" title="SELECT">
														Select
													</a>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>

							<?php foreach ($ListKO as $lk => $value) : ?>
								<div class="modal fade bd-example-modal-lg<?php echo $value->NoKO ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg">
										<div class="modal-content">
											<div class="modal-header">
												<button class="close" data-dismiss="modal"><span>&times;</span></button>
												<center>
													<h3 class="modal-title" id="myModalLabel">ADDENDUM KONTRAK</h3>
												</center>
											</div>
											<div class="modal-body">
												<div class="form-group">
													<div class="form-group row">
														<label class="col-lg-2 col-form-label">ADDENDUM KE</label>
														<div class="col-sm-3">
															<input type="text" name="AddendumKe" id="AddendumKe" class="form-control">
														</div>
														<label class="col-lg-2 col-form-label">ALASAN ADDENDUM</label>
														<div class="col-sm-3">
															<input type="text" name="AlasanAddendum" id="AlasanAddendum" class="form-control">
														</div>
													</div>

												</div>
											</div>
											<table class="table table-bordered table-striped text-center">
												<thead>
													<tr>
														<th>No. KO</th>
														<th>Tgl KO</th>
														<th>Penerima Kontrak</th>
														<th>Tipe KO</th>
														<th>Addendum/ <br> Revisi Ke</th>
														<th>Total KO (Rp)</th>
														<th>KO Approved By</th>
														<th>KO Approved ON</th>
														<th>Alasan Addendum</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($ListAddmKO as $lk => $value) : ?>
														<tr>
															<td><?= $value->NoKO ?></td>
															<td><?= $value->TglKO ?></td>
															<td><?= $value->VendorNm ?></td>
															<td><?= $value->KategoriId ?></td>
															<td><?= $value->AddendumKe ?></td>
															<td><?= number_format($value->SubTotal) ?></td>
															<td><?= $value->ApprovedBy ?></td>
															<td><?= $value->TimeApproved ?></td>
															<td><?= $value->AlasanAddendum ?></td>
															<td>
																<a type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target=".bd-example-modal-lg<?php echo $value->NoKO ?>">Addendum</a>
																<a data-target="" data-toggle="modal" class="btn btn-warning btn-xs">Select</a>
															</td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							<?php endforeach; ?>


						</div>
					</div>
				</div>
			</div>
	</section>





	<script>
		$(document).ready(function() {
			$('#tblAddKO').DataTable();
		});
	</script>

	<script>
		$(document).idle({
			onIdle: function() {
				window.location = "/logout.php";
			},
			idle: 10000
		});
	</script>
</div>