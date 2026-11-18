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
		<a href="<?= site_url('Report/MenuRptOperasi') ?>" class="btn btn-warning btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
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
								<div class="form-group row">

									<label for="" class="col-sm-1 col-form-label">JobNo</label>
									<label for="" class="col-sm-1">:</label>
									<div class="col-sm-3">
										<select name="JobNo" id="JobNo" class="form-control">
											<?php foreach ($Job as $jb) : ?>
												<option value="<?php echo $jb->JobNo ?>" class="form-control input-sm select2"><?php echo $jb->JobNo ?> - <?php echo $jb->JobNm ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="" class="col-sm-1 col-form-label">Tahun</label>
									<label for="" class="col-sm-1">:</label>
									<div class="col-sm-3">
										<input type="number" name="Tahun" id="Tahun" class="form-control">
									</div>
								</div>
								<button type="submit" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> &nbsp; PREVIEW</button>
								<button type="submit" class="btn btn-warning btn-xs"><i class="fa fa-file"></i> &nbsp; EXPORT</button>
								<p>
								<div class="form-group">
									<table id="TblVendor" class="table table-responsive table-bordered table-striped" border="1" cellpadding="2" width="100%">
										<thead>
											<tr>
												<th rowspan="2">
													<center>Job No</center>
												</th>
												<th rowspan="2">
													<center>Job Name</center>
												</th>
												<th rowspan="2">
													<center>Jenis</center>
												</th>
												<th rowspan="2">
													<center>Bulan</center>
												</th>
												<th colspan="2">
													<center>Rencana</center>
												</th>
												<th colspan="2">
													<center>Realisasi</center>
												</th>
											</tr>
											<tr>
												<th>Persentase(%)</th>
												<th>Netto(Rp)</th>
												<th>Persentase(%)</th>
												<th>Netto(Rp)</th>
											</tr>
										</thead>

										<tbody>
											<!-- <?php foreach ($Vendor as $V) : ?> -->
											<tr>


											</tr>
											<!-- <?php endforeach; ?> -->
										</tbody>
									</table>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
	</section>

	<script>
		$(document).ready(function() {
			$('.select2').select2({
				'theme': 'classic',
				'width': '100%',
			});
			var table = $('#TblVendor').DataTable({
				lenghtChange: false,
				buttons: ['pdf', 'excel', 'print']
			});
			table.buttons().container()
				.appendTo('#TblVendor_wrapper .col-sm-6:eq(0)');
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
