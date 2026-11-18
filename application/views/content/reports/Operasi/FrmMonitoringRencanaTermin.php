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
								<div class="col-sm-2">
									<select class="form-control" name="tahun">
										<option value="">--pilih tahun--</option>
										<?php $tahun = date('Y');
										for ($i = '2021'; $i < $tahun + 7; $i++) { ?>
											<option value="<?php echo $i ?>"><?php echo $i ?></option>
										<?php } ?>
									</select>
								</div>
								<button type="submit" class="btn btn-google"><i class="fa fa-eye"></i></button>
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