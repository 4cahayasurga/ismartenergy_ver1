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
								<button type="submit" class="btn btn-dropbox">EXPORT</button>

							</div>

							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-body">
										<table id="TblQuery1" class="table table-bordered table-striped table-hover table-condensed">
											<thead>
												<tr>
													<th>Uraian</th>
													<th>U.O.M</th>
													<th>Hrg Satuan (Rp)</th>
													<th>Vendor</th>
													<th>No KO</th>
													<th>Tgl KO</th>
													<th>Job Name</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($query as $pg) : ?>
												<tr>
													<td><?= $pg->Uraian ?></td>
													<td><?= $pg->Uom ?></td>
													<td><?= number_format($pg->HrgSatuan) ?></td>
													<td><?= $pg->VendorNm ?></td>
													<td><?= $pg->NoKO ?></td>
													<td><?= $pg->TglKO ?></td>
													<td><?= $pg->JobNm ?></td>
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
		$(function() {
			$("#TblQuery1").DataTable({
				"responsive": true,
				"lengthChange": false,
				"autoWidth": false,
				"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
			}).buttons().container().appendTo('#TblQuery1_wrapper .col-md-6:eq(0)');
			$('#example2').DataTable({
				"paging": true,
				"lengthChange": false,
				"searching": false,
				"ordering": true,
				"info": true,
				"autoWidth": false,
				"responsive": true,
			});
		});
	</script>
</div>


<!-- /.container-fluid -->
</section>

</section>



<!-- </Section> -->
