<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
			<label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $appspr->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $appspr->JobNm ?></label>
			<br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $appspr->Lokasi ?></label>

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<a href="<?= site_url('job/sub_job/' . $appspr->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
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
									<h3 class="box-title"> </h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
										<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
									</div>
								</div>
							</div>

							<div class="box-body">
								<div class="form-group">
									<div class="box-body table-responsive no-padding">
										<?php echo $this->session->flashdata('pesan') ?>
										<?php
										if (!empty($this->session->flashdata('message'))) {
											echo $this->session->flashdata('message');
										}
										?>
										<table id="DataSpr" class="table table-bordered table-striped" border="1" cellspacing="2" width="100%">
											<thead>
												<tr>
													<th style="background-color: orange">
														<center>No.</center>
													</th>
													<th style="background-color: orange;">
														<center> Job No</center>
													</th>
													<th style="background-color: orange;">
														<center> No. SPR</center>
													</th>
													<th style="background-color: orange;">
														<center> Tgl SPR</center>
													</th>
													<th style="background-color: orange;">
														<center> Kepada</center>
													</th>
													<th style="background-color: orange;">
														<center> Untuk Pekerjaan</center>
													</th>
													<th style="background-color: orange;">
														<center> SPR Approve By</center>
													</th>
													<th style="background-color: orange;">
														<center> SPR Approve On</center>
													</th>
													<th style="background-color: orange;"></th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1;
												foreach ($GetSpr as $spr) : ?>
													<tr>
														<td><?= $no++ ?></td>
														<td><?php echo $spr->JobNo ?></td>
														<td><?php echo $spr->NoSPR ?></td>
														<td><?php echo $spr->TglSPR ?></td>
														<td><?php echo $spr->Kepada ?></td>
														<td><?php echo $spr->UtkPekerjaan ?></td>
														<td><?php echo $spr->ApprovedBy ?></td>
														<td><?php echo $spr->TimeApproved ?></td>

														<td> 
															<a type="button" data-JobNo="<?=$spr->JobNo?>" data-NoSPR="<?=$spr->NoSPR?>"  class="badge badge-xs btn-danger">SELECT</a>

															<a href="#" type="submit" class="badge badge-xs btn-instagram">UNAPPROVED</a>
														</td>

														<!-- <td> <a href="<?php echo base_url('job/editapprvspr/'. $spr->NoSPR) ?>" type="submit" class="badge badge-xs btn-danger">SELECT</a>
															  <a href="#" type="submit" class="badge badge-xs btn-instagram">UNAPPROVED</a>
														</td> -->
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>


									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
	</section>



	<script>
		$(document).idle({
			onIdle: function() {
				window.location = "/logout.php";
			},
			idle: 10000
		});
	</script>

	<script>
		$(document).ready(function() {
			$('#DataSpr').DataTable();
		})
	</script>


</div>


<!-- /.container-fluid -->
</section>

</section>



<!-- </Section> -->
