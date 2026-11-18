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
		<a href="<?= site_url('Report/MenuRptOperasi') ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
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
									<div class="col-sm-5">
										<label for="" class="col-sm-2 col-form-label">Tahun</label>
										<div class="col-sm-4">
											<select class="form-control" name="tahun">
												<?php $tahun = date('Y');
												for ($i = '2021'; $i < $tahun + 7; $i++) { ?>
													<option value="<?php echo $i ?>"><?php echo $i ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>

								<div class="form-group row">
									<div class="col-sm-5">
										<label for="" class="col-sm-2 col-form-label">Periode</label>
										<div class="col-sm-8">
											<select name="periode" id="periode" class="form-control">
												<?php foreach ($Periode as $per) : ?>
													<option value=""><?php echo $per->Tgl1 ?> - <?php echo $per->Tgl2 ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
								</div>
								<button type="submit" class="btn btn-xs btn-success">PREVIEW</button>

								<!-- <div class="input-group col-sm-6">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control pull-right" id="datePeriode">
								</div> -->
							</div>

						</div>
					</div>
				</div>
			</div>
	</section>



	<script>
		$(document).ready(function() {
			// $('#tblFrmAbsen').DataTable();

			//Date range picker
			$('#datePeriode').daterangepicker();

			$('#datePeriode').on('apply.daterangepicker', function(ev, picker) {
				// console.log(picker.startDate.format('YYYY-MM-DD'));
				// console.log(picker.endDate.format('YYYY-MM-DD'));

				var startDate = picker.startDate.format('YYYY-MM-DD');
				var endDate = picker.endDate.format('YYYY-MM-DD');

				getTableAtt(startDate, endDate)
			});
		})

		// $(document).idle({
		// 	onIdle: function() {
		// 		window.location = "/logout.php";
		// 	},
		// 	idle: 10000
		// });
	</script>
</div>


<!-- /.container-fluid -->
</section>

</section>



<!-- </Section> -->
