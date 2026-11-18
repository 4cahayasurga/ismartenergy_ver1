<style>
	#my_table_pilih {
		/* font-family: 'Open Sans', 'Roboto', 'Lato', 'Helvetica', 'Montserrat'; */
		font-size: 12px !important;
		width: 50% !important;
	}
</style>

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
								<div class="row">
									<div class="col-sm-12">
										<div class="card">
											<div class="overlay dark" id="my_loading" style="display: none;">
												<i class="fas fa-2x fa-sync-alt"></i>
											</div>
											<div class="card-body">
												<form id="my_form_query">

													<table class="table table-striped table hover table-borderless table-condensed" id="my_table_pilih">
														<tr>
															<th>Tgl Entry</th>
															<td style="text-align: center;">:</td>
															<td>
																<input type="date" name="Tgl1" id="Tgl1" class="form-control">
															</td>
															<th>S/D</th>
															<td>
																<input type="date" name="Tgl2" id="Tgl2" class="form-control">
															</td>
														</tr>
														<tr style="width: 80px">
															<th>Job.No</th>
															<td style="text-align: center;">:</td>
															<td>
																<select class="form-control form-control-sm-5" name="JobNo" id="my_job_no" required>
																	<option value="">-- Pilih salah satu --</option>
																	<option value="semua">SEMUA JOB</option>
																	<?php
																	foreach ($Job as $job) : ?>
																		<option value="<?= $job->JobNo ?>"><?= $job->JobNo ?> - <?= $job->JobNm ?></option>
																	<?php endforeach; ?>
																</select>
															</td>
														</tr>
														<tr>
															<th>Vendor</th>
															<td style="text-align: center;">:</td>
															<td>
																<select class="form-control form-control-sm-5" name="Vendor" id="Vendor" required>
																	<option value="">All Vendor</option>

																</select>
															</td>
														</tr>
														<tr>
															<th>Sort By</th>
															<td style="text-align: center;">:</td>
															<td>
																<select class="form-control form-control-sm-5" name="ShortBy" id="ShortBy" required>
																	<option value="">By Vendor</option>
																	<option value="">By Project</option>
																</select>
															</td>
														</tr>



														<tr>
															<th></th>
															<td></td>
															<td>

																<button type="submit" id="btn-view" class="btn btn-primary btn-sm"><i class="fa fa-search"></i>&nbsp;VIEW</button>

															</td>
														</tr>

													</table>
												</form>

											</div>
										</div>
									</div>
								</div>
								<div class="tampil_table"></div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(function() {

		$('#my_job_no').select2({
			'width': '100%'
		});

		$('#my_form_query').submit(function(e) {
			e.preventDefault();

			$('#my_loading').hide();
			$('.tampil_table').html('');

			// var jumlah_data = $('#jumlah_data').val();
			// if (jumlah_data == '' || jumlah_data == 0) {
			// 	toastr.info('jumlah data wajib di isi');
			// }

			$.ajax({
				type: "POST",
				url: "<?= site_url('RekapPDJaminan/dapatkan_table') ?>",
				data: $(this).serialize(),
				dataType: "HTML",
				beforeSend: function() {
					$('#my_loading').show();
				},
				success: function(response) {
					$('#my_loading').hide();
					if (response == 1) {
						toastr.info('Terjadi Kesalahan');
						return false;
					}
					$('.tampil_table').html(response);

					// if (response == 'no') {
					// 	toastr.info('Terjadi Kesalahan');
					// 	return false;
					// } else {
					// 	$('.tampil_table').html(response);
					// }

				},
				error: function() {
					toastr.danger('Terjadi kesalahan');
				}
			});

		})
	})
</script>
