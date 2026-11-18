<style type="text/css">
	body {
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
	}

	.font12 {
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size: 12px !important;
	}

	.swal2-popup {
		font-size: 1.6rem !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	select[readonly].select2-hidden-accessible+.select2-container {
		pointer-events: none;
		touch-action: none;
	}

	select[readonly].select2-hidden-accessible+.select2-container .select2-selection {
		background: #eee;
		box-shadow: none;
	}

	select[readonly].select2-hidden-accessible+.select2-container .select2-selection__arrow,
	select[readonly].select2-hidden-accessible+.select2-container .select2-selection__clear {
		display: none;
	}

	.jarak_atas {
		margin-top: 5px !important;
	}

	.select2-container {
		width: 100% !important;
		padding: 0;
	}
</style>

<div class="content-wrapper">

	<section class="content-header">
		<h1><?= ($judul) ? $judul : 'Judul belum diatur'  ?></h1>
		<ol class="breadcrumb">

		</ol>
	</section>

	<section class="content">
		<a href="<?= site_url('Job/sub_job/' . $JobNo) ?>" class="btn btn-default btn-md text-bold"><i class="fa fa-arrow-left"></i> BACK</a>
		<?= ($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
		<div class="box box-primary">
			<div class="box-header"></div>
			<div class="box-body">
				<div class="row">
					<div class="col-sm-12">

						<div class="table-responsive">

							<?= ($this->session->flashdata('message2')) ? '<p class="text-danger">' . $this->session->flashdata('message2') . '</p>' : '' ?>
							<form id="MyForm">
								<table width="100%" border="0" class="font12">
									<tr>
										<td width="150px">Job No.</td>
										<td class="text-center" width="10px">:</td>
										<td width="30%">
											<select name="JobNo" id="JobNo" class="form-control input-sm font12 select2" required>
												<option value="">-- Pilih Job --</option>
												<?php foreach ($ListJob as $row) : ?>
													<option value="<?= $row['JobNo'] ?>" data-JobNm="<?= $row['JobNm'] ?>" <?= ($row['JobNo'] == $JobNo) ? 'selected' : '' ?>><?= $row['JobNo'] . ' - ' . $row['JobNm'] ?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="font12 jarak_atas">
									<tr>
										<td width="150px">Alokasi</td>
										<td class="text-center" width="10px">:</td>
										<td width="30%">
											<select name="Alokasi" id="Alokasi" class="form-control input-sm font12 select2" required>
												<option value="">-- Pilih Alokasi --</option>
												<?php foreach ($ListAlokasi as $row) : ?>
													<option value="<?= $row['Alokasi'] ?>" data-Keterangan="<?= $row['Keterangan'] ?>" <?= ($row['Alokasi'] == $Alokasi) ? 'selected' : '' ?>><?= $row['Alokasi'] . ' - ' . $row['Keterangan'] ?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td></td>
									</tr>
								</table>


								<table width="100%" border="0" class="font12 jarak_atas">
									<tr>
										<td width="150px">Minggu Ke</td>
										<td class="text-center" width="10px">:</td>
										<td width="150px">
											<select name="Minggu" id="Minggu" class="form-control input-sm font12 select2">
												<option value=""></option>

											</select>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="font12 jarak_atas" id="tbl_periode">
									<tr>
										<td width="150px">Periode</td>
										<td class="text-center" width="10px">:</td>
										<td width="10%">
											<input type="date" name="PrdAwal" id="PrdAwal" class="form-control" readonly>
										</td>
										<td width="40px" class="text-center">s.d</td>
										<td width="10%">
											<input type="date" name="PrdAkhir" id="PrdAkhir" class="form-control" readonly>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="font12 jarak_atas">
									<tr>
										<td width="150px"></td>
										<td class="text-center" width="10px"></td>
										<td width="30%">
											<button type="button" class="btn btn-primary" id="btnTampilkan">Tampilkan</button>
										</td>
										<td></td>
									</tr>
								</table>
							</form>

						</div>


					</div>
				</div>
			</div>

			<div class="overlay loading_box" style="display:none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>
		</div>


		<div id="table_RPPM">

		</div>

	</section>

</div>