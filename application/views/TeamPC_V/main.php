<?php
error_reporting(E_ALL);
?>
<style type="text/css">
	.huruf8 {
		font-size: 9px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	.huruf10 {
		font-size: 10px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	.huruf11 {
		font-size: 11px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	.huruf12 {
		font-size: 12px !important;
	}

	.huruf13 {
		font-size: 13px !important;
	}

	.huruf14 {
		font-size: 14px !important;
	}

	.huruf15 {
		font-size: 15px !important;
	}

	.jarak {
		padding-top: 5px !important;
		padding-bottom: 5px !important;
	}

	.jarak_baru {
		padding-top: 10px !important;
	}

	.tengah {
		vertical-align: middle;
	}

	#Form_PJLapangan {
		font-size: 13px !important;
	}

	#mytable thead tr th {
		font-size: 12px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-weight: bold;
	}

	#mytable tbody tr td {
		font-size: 12px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	#mytable tfoot {
		font-size: 13px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-weight: bold;
	}
</style>

<div class="content-wrapper">

	<section class="content-header">
		<h1><?= $judul ?></h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active"><?= $judul ?></li>
		</ol>
	</section>

	<section class="content">
		<?= ($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
		<div class="box box-primary">
			<div class="box-header">
				<div class="row">
					<div class="col-sm-8">

						<form action="<?= site_url('TeamPC_C/SimpanTeamPC') ?>" method="POST">

							<table width="100%" border="0">
								<tr>
									<td width="150px">Job No</td>
									<td width="10px" class="text-center">:</td>
									<td width="300px">
										<select name="JobNo" id="JobNo" class="form-control select2" required>
											<option value="">:: PILIH JOB ::</option>
											<?php foreach ($JobList as $rowJob) : ?>
												<option value="<?= $rowJob->JobNo ?>"><?= $rowJob->JobNo . ' - ' . $rowJob->JobNm ?></option>
											<?php endforeach ?>
										</select>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" style="margin-top:5px;">
								<tr>
									<td width="150px">Koordinator Project</td>
									<td width="10px" class="text-center">:</td>
									<td width="300px">
										<select name="Kopro" class="form-control select2">
											<option value="">:: PILIH KARYAWAN ::</option>
											<?php foreach ($KaryawanList as $rowKaryawan) : ?>
												<option value="<?= $rowKaryawan->Nama ?>"><?= $rowKaryawan->NIK . ' - ' . $rowKaryawan->Nama ?></option>
											<?php endforeach ?>
										</select>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" style="margin-top:5px;">
								<tr>
									<td width="150px">Project Manager</td>
									<td width="10px" class="text-center">:</td>
									<td width="300px">
										<select name="ProjectMgr" class="form-control select2">
											<option value="">:: PILIH KARYAWAN ::</option>
											<?php foreach ($KaryawanList as $rowKaryawan) : ?>
												<option value="<?= $rowKaryawan->Nama ?>"><?= $rowKaryawan->NIK . ' - ' . $rowKaryawan->Nama ?></option>
											<?php endforeach ?>
										</select>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" style="margin-top:5px;">
								<tr>
									<td width="150px">Karyawan PC</td>
									<td width="10px" class="text-center">:</td>
									<td width="300px">
										<select name="KaryawanPC" class="form-control select2">
											<option value="">:: PILIH KARYAWAN ::</option>
											<?php foreach ($KaryawanList as $rowKaryawan) : ?>
												<option value="<?= $rowKaryawan->Nama ?>"><?= $rowKaryawan->NIK . ' - ' . $rowKaryawan->Nama ?></option>
											<?php endforeach ?>
										</select>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" style="margin-top:5px;">
								<tr>
									<td width="150px">Karyawan PO</td>
									<td width="10px" class="text-center">:</td>
									<td width="300px">
										<select name="KaryawanPO" class="form-control select2">
											<option value="">:: PILIH KARYAWAN ::</option>
											<?php foreach ($KaryawanList as $rowKaryawan) : ?>
												<option value="<?= $rowKaryawan->Nama ?>"><?= $rowKaryawan->NIK . ' - ' . $rowKaryawan->Nama ?></option>
											<?php endforeach ?>
										</select>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" style="margin-top:5px;">
								<tr>
									<td width="80px"></td>
									<td width="10px" class="text-center"></td>
									<td width="300px">
										<button type="submit" class="btn btn-default btn-md">SIMPAN</button>
									</td>
									<td></td>
								</tr>
							</table>

						</form>
						<hr>
						<table class="table table-bordered table-striped table-hover" id="mytable">
							<thead>
								<tr>
									<th>Job Name</th>
									<th>Lokasi</th>
									<th>Nama Kordinator Project</th>
									<th>Nama Kepala Project</th>
									<th>Nama PIC Project Control</th>
									<th>Nama PIC Purchase Order</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($tableList as $row) : ?>
									<tr>
										<td><?= $row->JobNo . ' - ' . $row->JobNm ?></td>
										<td><?= $row->Lokasi ?></td>
										<td><?= $row->Kopro ?></td>
										<td><?= $row->ProjectMgr ?></td>
										<td><?= $row->TeamPC ?></td>
										<td><?= $row->TeamPO ?></td>
										<td><a type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal<?= $row->JobNo ?>"><i class="fa fa-pencil"></i></a></td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>

					</div>
				</div>
			</div>

			<form action="<?= site_url('TeamPC_C/editPIC') ?>" method="post">
				<?php foreach ($tableList as $row) ?>
				<div class="modal fade" id="exampleModal<?= $row->JobNo ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">

								<table width="100%" border="0" style="margin-top:5px;">
									<tr>
										<td width="300px">Job No</td>
										<td width="10px" class="text-center">:</td>
										<td width="500px">
											<input type="text" name="JobNo" id="JobNo" class="form-control" value="<?= $row->JobNo ?>">
											<!-- <select name="JobNo" class="form-control select2">
												<!-- <option value="">:: PILIH JOB ::</option> -->
											<?php foreach ($JobList as $rowJob) : ?>
												<option <?= ($row->JobNo == $rowJob ? 'selected' : '') ?> $rowJob="<?= $rowJob ?>"><?= $rowJob ?></option>
											<?php endforeach ?>
											</select> -->
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" style="margin-top:5px;">
									<tr>
										<td width="300px">Project Manager</td>
										<td width="10px" class="text-center">:</td>
										<td width="500px">
											<select name="ProjectMgr" class="form-control select2">
												<option value="">:: PILIH KARYAWAN ::</option>
												<?php foreach ($KaryawanList as $rowKaryawan) : ?>
													<option value="<?= $rowKaryawan->Nama ?>"><?= $rowKaryawan->NIK . ' - ' . $rowKaryawan->Nama ?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" style="margin-top:5px;">
									<tr>
										<td width="300px">Karyawan PC</td>
										<td width="10px" class="text-center">:</td>
										<td width="500px">
											<select name="KaryawanPC" class="form-control select2">
												<option value="">:: PILIH KARYAWAN ::</option>
												<?php foreach ($KaryawanList as $rowKaryawan) : ?>
													<option value="<?= $rowKaryawan->Nama ?>"><?= $rowKaryawan->NIK . ' - ' . $rowKaryawan->Nama ?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" style="margin-top:5px;">
									<tr>
										<td width="300px">Karyawan PO</td>
										<td width="10px" class="text-center">:</td>
										<td width="500px">
											<select name="KaryawanPO" class="form-control select2">
												<option value="">:: PILIH KARYAWAN ::</option>
												<?php foreach ($KaryawanList as $rowKaryawan) : ?>
													<option value="<?= $rowKaryawan->Nama ?>"><?= $rowKaryawan->NIK . ' - ' . $rowKaryawan->Nama ?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td></td>
									</tr>
								</table>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary">Save changes</button>
							</div>
						</div>
					</div>
				</div>
				<?php T_ENDFOREACH ?>
			</form>

			<!-- ==START BOX BODY -->
			<div class="box-body">
				<div id="tablePJLapangan"></div>
			</div>
			<!-- END BOX BODY -->

			<div class="overlay" id="GlobalLoading" style="display:none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>

		</div>
</div>
</section>


</div>


<div class="clearfix"></div>


<script src="<?= base_url('assets/select2_baru.js') ?>"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.select2').select2();
		$('#mytable').DataTable();
	});
</script>
