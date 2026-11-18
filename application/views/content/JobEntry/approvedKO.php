<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
			<!-- <label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $mos->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $mos->JobNm ?></label>
            <br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $mos->Lokasi ?></label> -->

		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<!-- <a href="<?= site_url('job/sub_job/' . $jb->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a> -->
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
									<h3 class="box-title"> LIST KONTRAK / PURCHASE ORDER </h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
										<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
									</div>
								</div>
							</div>

							<div class="box-body">
								<div class="form-group">
									<style type="text/css">
										table tbody tr td button {
											margin-left: 5px;
										}
									</style>

									<!-- Main content -->

									<section class="content">

										<div class="row">

											<div class="col-sm-12">

												<div class="card">
													<div class="card-body">
														<div class="row">
															<div class="col-md-12">
																<div class="nav-tabs-custom">
																	<ul class="nav nav-tabs">
																		<li class="active"><a href="#tab_waiting" data-toggle="tab">Waiting Approve</a></li>
																		<li><a href="#Approved" data-toggle="tab">APPROVED</a></li>
																	</ul>
																	<div class="tab-content">
																		<div class="tab-pane active" id="tab_waiting">
																			<div class="box-body">
																				<div class="row">
																					<div class="col-xs-12">
																						<div class="box">
																							<br>
																							<table id="waitAppKO" class="table table-bordered table-striped text-center" width="100%" cellspacing="0">
																								<thead>
																									<tr>
																										<th>No</th>
																										<th>Nama Proyek</th>
																										<th>No. KO</th>
																										<th>Tgl KO</th>
																										<th>Vendor</th>
																										<th>Tipe KO</th>
																										<th>Total KO (RP)</th>
																										<th>KO Approved By</th>
																										<th>KO Approved ON</th>
																										<th></th>
																									</tr>
																								</thead>
																								<tbody>
																									<?php $no = 1;
																									foreach ($WapproveKO as $wta) :
																									?>
																										<tr>
																											<td><?php echo $no++ ?></td>
																											<td><?php echo $wta->JobNm ?></td>
																											<td><?php echo $wta->NoKO ?></td>
																											<td><?php echo $wta->TglKO ?></td>
																											<td><?php echo $wta->VendorNm ?></td>
																											<td><?php echo $wta->KategoriId ?></td>
																											<td><?php echo number_format($wta->SubTotal) ?></td>
																											<td><?php echo $wta->ApprovedBy ?></td>
																											<td><?php echo $wta->TimeApproved ?></td>
																											<td>
																												<a class="btn btn-success badge btn-xs" data-toggle="modal" data-target="#ModalApp<?php echo $wta->NoKO; ?>" title="APPROVED">
																													<i class="fa fa-solid fa-check"></i>
																												</a>
																												<a href="<?php echo base_url('job/update_data/' . $wta->NoKO) ?>" class="btn btn-warning badge btn-xs" title="SELECT">
																													<i class="fa fa-solid fa-align-justify"></i>
																												</a>
																												<!-- <a data-target="#BtnApprove<?php echo $wta->NoKO; ?>" data-toggle=" modal" class="badge btn-primary btn-xs" title="APPROVED"><i class="fa fa-solid fa-check"></i></a>
																												<a data-target="" data-toggle="modal" class="badge btn-warning btn-xs" title="SELECT"><i class="fa fa-solid fa-align-justify"></i></a> -->
																											</td>
																										</tr>
																									<?php endforeach; ?>
																								</tbody>
																							</table>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>

																		<?php foreach ($WapproveKO as $wta) : ?>
																			<div class="modal fade" id="ModalApp<?php echo $wta->NoKO; ?>">
																				<div class="modal-dialog">
																					<div class="modal-content">
																						<!-- header-->
																						<div class="modal-header">
																							<button class="close" data-dismiss="modal"><span>&times;</span></button>
																							<center>
																								<h4 class="modal-title" id="myModalLabel">Approval Confirmation</h4>
																							</center>
																						</div>
																						<!--body-->
																						<div class="modal-body">
																							<center>
																								<h4>Anda yakin Approve data berikut ?</h4>
																								<br>
																								<?php echo $wta->NoKO ?> - <?php echo $wta->VendorNm ?><br>
																								<h5>Total KO = Rp. <?php echo number_format($wta->SubTotal) ?></h5>
																							</center>
																						</div>
																						<!--footer-->
																						<div class="modal-footer">
																							<button type="submit" class="btn btn-success">Yakin</button>
																							<button class="btn btn-danger" data-dismiss="modal">Tutup</button>
																						</div>
																					</div>
																				</div>
																			</div>
																		<?php endforeach; ?>

																		<div class="tab-pane" id="Approved">
																			<div class="box-body">
																				<div class="row">
																					<div class="col-xs-12">
																						<div class="box">
																							<br>
																							<table id="AppKO" class="table table-bordered table-striped text-center" width="100%" cellspacing="0">
																								<thead>
																									<tr>
																										<th>No</th>
																										<th>Job No</th>
																										<th>Nama Proyek</th>
																										<th>No. KO</th>
																										<th>Tgl KO</th>
																										<th>Vendor</th>
																										<th>Tipe KO</th>
																										<th>Total KO (Rp)</th>
																										<th>KO Approved By</th>
																										<th>KO Approved On</th>
																										<th>Aksi</th>
																									</tr>
																								</thead>
																								<tbody>
																									<?php $no = 1;
																									foreach ($approveKOyes as $aky) : ?>
																										<tr>
																											<td><?php echo $no++ ?></td>
																											<td><?php echo $aky->JobNo ?></td>
																											<td><?php echo $aky->JobNm ?></td>
																											<td><?php echo $aky->NoKO ?></td>
																											<td><?php echo $aky->TglKO ?></td>
																											<td><?php echo $aky->VendorNm ?></td>
																											<td><?php echo $aky->KategoriId ?></td>
																											<td><?php echo number_format($aky->SubTotal) ?></td>
																											<td><?php echo $aky->ApprovedBy ?></td>
																											<td><?php echo $aky->TimeApproved ?></td>
																											<td>
																												<a class="btn btn-success badge btn-xs" data-toggle="modal" data-target="#ModalUnApp<?php echo $aky->NoKO ?>" title="UNAPPROVED"><i class="fa fa-backward"></i></a>
																												<a href="<?php echo base_url('job/update_data/' . $wta->NoKO) ?>" class="btn btn-warning badge btn-xs" title="SELECT">
																													<i class="fa fa-solid fa-align-justify"></i>
																												</a>
																											</td>
																										</tr>
																									<?php endforeach; ?>
																								</tbody>
																							</table>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>

																		<?php foreach ($approveKOyes as $aky) : ?>
																			<div class="modal fade" id="ModalUnApp<?php echo $aky->NoKO ?>">
																				<div class="modal-dialog">
																					<div class="modal-content">
																						<!-- header-->
																						<div class="modal-header">
																							<button class="close" data-dismiss="modal"><span>&times;</span></button>
																							<center>
																								<h3 class="modal-title" id="myModalLabel">UNAPPROVED!!!</h3>
																							</center>
																						</div>
																						<!--body-->
																						<div class="modal-body">
																							<center>
																								<h4>Anda yakin UnApprove data berikut ?</h4>
																								<br>
																								<?php echo $aky->NoKO ?> - <?php echo $aky->VendorNm ?><br>
																								<h5>Total KO = Rp. <?php echo number_format($aky->SubTotal) ?></h5>
																							</center>
																						</div>
																						<!--footer-->
																						<div class="modal-footer">
																							<button type="submit" class="btn btn-success">Yakin</button>
																							<button class="btn btn-danger" data-dismiss="modal">Tutup</button>
																						</div>
																					</div>
																				</div>
																			</div>
																		<?php endforeach; ?>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>

										</div>

								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</section>

	<script>
		$(document).ready(function() {
			$('#waitAppKO, #AppKO').DataTable();

			//Date range picker
			$('#datePeriode').daterangepicker();

			$('#datePeriode').on('apply.daterangepicker', function(ev, picker) {
				// console.log(picker.startDate.format('YYYY-MM-DD'));
				// console.log(picker.endDate.format('YYYY-MM-DD'));

				var startDate = picker.startDate.format('YYYY-MM-DD');
				var endDate = picker.endDate.format('YYYY-MM-DD');

				getTableApproveKO(startDate, endDate)
			});
		})

		// function getTableAtt(startDate, endDate) {
		// 	$.ajax({
		// 			url: '<?= site_url('Absensicon/getTableAtt') ?>',
		// 			type: 'POST',
		// 			dataType: 'HTML',
		// 			data: {
		// 				"startDate": startDate,
		// 				"endDate": endDate,
		// 				// "versi_rap": versi_rap
		// 			},
		// 			beforeSend: function() {
		// 				$('.loading-att').show();
		// 			}
		// 		})
		// 		.done(function(response) {
		// 			$('.loading-att').hide();
		// 			$('.table-att').html(response);

		// 		})
		// 		.fail(function() {
		// 			// console.log("error");
		// 		})
		// 		.always(function() {
		// 			// console.log("complete");
		// 		});
		// }
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


<!-- /.container-fluid -->
</section>

</section>



<!-- </Section> -->
