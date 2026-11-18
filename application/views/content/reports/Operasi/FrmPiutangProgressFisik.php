<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<a href="#" class="btn btn-warning" onclick="history.back()"><i class="fa fa-arrow-left"></i> Kembali</a>
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
									<h3 class="box-title"></h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
										<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
									</div>
								</div>
							</div>

							<div class="box-body">
								<div class="col-md-12">
									<!-- <div class="panel panel-default"> -->
									<!-- <div class="panel-body"> -->
									<table id="TblQuery1" class="table table-bordered table-striped table-hover table-condensed">
										<thead>
											<tr>
												<th rowspan="2">
													<center>JobNo</center>
												</th>
												<th rowspan="2">
													<center>JobNm</center>
												</th>
												<th rowspan="2">
													<center>Kategori</center>
												</th>
												<th colspan="3">
													<center>RPPM</center>
												</th>
												<th colspan="2">
													<center>Termin</center>
												</th>
												<th colspan="2">
													<center>Sisa Yg Harus Ditagih</center>
												</th>
											</tr>
											<tr>
												<th>Tgl RPPM</th>
												<th>%</th>
												<th>Rp</th>
												<th>%</th>
												<th>Rp</th>
												<th>%</th>
												<th>Rp</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($piutangprogress as $pg) { ?>
												<tr>
													<td><?= $pg->JobNo ?></td>
													<td><?= $pg->JobNm ?></td>
													<td><?= $pg->Kategori ?></td>
													<td><?= $pg->DateRPPM ?></td>
													<td><?= round($pg->PersenRPPM, 2) ?></td>
													<td><?= number_format($pg->RpRPPM) ?></td>
													<td><?= round($pg->PersenTermin, 2) ?></td>
													<td><?= number_format($pg->RpTermin) ?></td>
													<td><?= round(($pg->PersenRPPM - $pg->PersenTermin), 2) ?></td>
													<td><?= number_format($pg->RpRPPM - $pg->RpTermin) ?></td>
												</tr>
											<?php } ?>
										</tbody>

									</table>
									<!-- </div> -->

									<!-- </div> -->

								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
	</section>

	<script>
		$(document).ready(function() {
			$('#TblQuery1').DataTable();
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


<!-- /.container-fluid -->
</section>

</section>



<!-- </Section> -->
