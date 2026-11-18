<style type="text/css">
	body {
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
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

	.huruf16 {
		font-size: 16px !important;
	}

	.marginatas5 {
		margin-top: 5px !important;
	}

	.myFont {
		font-size: 12px !important;
	}
</style>


<div class="content-wrapper">

	<section class="content-header">
		<h1><u><?= $title ?></u></h1>
	</section>

	<section class="content">

		<div class="box box-primary">
			<div class="box-header">

				<div class="row">
					<div class="col-sm-12">
						<?= ($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
						<div class="table-responsive">
							<form id="MyForm_RAP" data-JobNo="<?= $JobNo ?>" data-Alokasi="<?= $Alokasi ?>" data-Versi="<?= $Versi ?>">

								<table width="100%" border="0" class="huruf13">
									<tr>
										<td width="100px">Job No</td>
										<td width="10px" class="text-center">:</td>
										<td width="500px">
											<select id="JobNo" name="JobNo" width="400px" class="form-control input-sm select2 huruf13">
												<option value="">:: Pilih Job ::</option>
												<?php foreach ($ListJob as $row) : ?>
													<option value="<?= $row->JobNo ?>" <?= ($JobNo == $row->JobNo) ? 'selected' : '' ?>><?= $row->JobNo . ' - ' . $row->JobNm ?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5 huruf13">
									<tr>
										<td width="100px">Alokasi</td>
										<td width="10px" class="text-center">:</td>
										<td width="300px">
											<select id="Alokasi" name="Alokasi" width="100px" class="form-control input-sm huruf13">
												<option value="">:: Pilih Alokasi ::</option>
												<?php foreach ($ListAlokasi as $row) : ?>
													<option value="<?= $row->Alokasi ?>" <?= ($Alokasi == $row->Alokasi) ? 'selected' : '' ?>><?= $row->Alokasi . ' - ' . $row->Keterangan ?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5 huruf12">
									<tr>
										<td width="100px">Versi</td>
										<td width="10px" class="text-center">:</td>
										<td width="150px">
											<select id="Versi" name="Versi" width="100px" class="form-control input-sm ">
												<option value="0.0">0.0</option>
											</select>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5 huruf12">
									<tr>
										<td width="100px"></td>
										<td width="10px" class="text-center"></td>
										<td width="150px">
											<button type="submit" class="btn btn-default" id="btnFilter">FILTER</button>
										</td>
										<td></td>
									</tr>
								</table>



							</form>

						</div>
					</div>
				</div>

			</div>
			<div class="box-body">

			</div>
			<div class="overlay loading_halaman" style="display:none;">
				<i class="fa fa-spinner fa-spin"></i>
			</div>
		</div>

		<div class="tampil_table_RAP_disini">

		</div>



	</section>
</div>

<div class="clearfix"></div>




<div class="modal fade" id="Modal_TambahVersi">
	<div class="modal-dialog">
		<form id="Form_TambahVersi">
			<div class="modal-content">

				<div class="modal-header">
					<button class="close" data-dismiss="modal"><span>&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Tambah Versi</h4>
				</div>

				<div class="modal-body">
					<div class="col-sm-12">
						<table width="100%" border="0">
							<tr>
								<td width="150px">Perubahan</td>
								<td width="20px" class="text-center">:</td>
								<td width="100px">
									<input type="radio" name="PerubahanVersi" id="RdoMajor" value="Major">
									<label for="RdoMajor" style="font-weight:normal;">Major</label>
								</td>
								<td width="100px" class="text-center">
									<input type="radio" name="PerubahanVersi" id="RdoMinor" value="Minor">
									<label for="RdoMinor" style="font-weight:normal;">Minor</label>
								</td>
								<td></td>
							</tr>
						</table>

						<table width="100%" border="0" style="margin-top:5px !important;">
							<tr>
								<td width="150px">Versi Terkini</td>
								<td width="20px" class="text-center">:</td>
								<td width="100px">
									<input type="text" name="VersiTerkini" id="VersiTerkini">
								</td>
								<td></td>
							</tr>
						</table>

						<table width="100%" border="0" style="margin-top:5px !important;">
							<tr>
								<td width="150px">Rubah Versi Menjadi</td>
								<td width="20px" class="text-center">:</td>
								<td width="100px">
									<input type="text" name="RubahVersiMenjadi" id="RubahVersiMenjadi">
								</td>
								<td></td>
							</tr>
						</table>

					</div>
				</div>

				<div class="modal-footer">

					<input type="hidden" name="JobNo" id="JobNo_Versi">
					<input type="hidden" name="Alokasi" id="Alokasi_Versi">

					<button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
					<button type="submit" class="btn btn-primary pull-right btnSimpanVersi">Simpan</button>
				</div>

			</div>
		</form>
	</div>
</div>