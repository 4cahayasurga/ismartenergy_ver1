<?php error_reporting(0) ?>
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
								<form action="<?= site_url('Report/PKET') ?>" method="POST">
									<div class="col-sm-2">
										<select class="form-control" name="Tahun">
											<option value="">--pilih tahun--</option>
											<?php $tahun = date('Y');
											for ($i = 2020; $i < $tahun + 7; $i++) { ?>
												<option value="<?php echo $i ?>"><?php echo $i ?></option>
											<?php } ?>
										</select>
									</div>
									<button type="submit" class="btn btn-google" title="Preview"><i class="fa fa-eye"></i></button>
									<button type="submit" class="btn btn-success" title="Export"><i class="fa fa-file"></i></button>
								</form>
							</div>

							<div class="box-body">
								<table id="TblRekap" class="table table-bordered table-striped text-center">
									<thead>
										<tr>
											<th>Job No</th>
											<th>Job Name</th>
											<th>Netto Kontrak Induk</th>
											<th>% Share</th>
											<th>Netto Share</th>
											<th>Total Alokasi A (Rp)</th>
											<th>Total Alokasi B (Rp)</th>
											<th>Total Alokasi C (Rp)</th>
											<th>Termin Induk (Rp)</th>
											<th>Termin Member (Rp)</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($Rekap as $R) : ?>
											<tr>
												<td><?= $R->JobNo ?></td>
												<td><?= $R->JobNm ?></td>
												<td><?= number_format($R->Netto) ?></td>
												<td><?= round($R->PersenKSO) ?></td>
												<td><?= number_format($R->NettoShare) ?></td>
												<td><?= number_format($R->AlokasiA) ?></td>
												<td><?= number_format($R->AlokasiB) ?></td>
												<td><?= number_format($R->AlokasiC) ?></td>
												<td><?= number_format($R->TerminInduk) ?></td>
												<td><?= number_format($R->TerminMember) ?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>


						</div>
					</div>
				</div>
			</div>
	</section>


	<script>
		$(document).ready(function() {
			$('#TblRekap').DataTable();
		});
	</script>



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
