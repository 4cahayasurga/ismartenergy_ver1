<style type="text/css">
	.ukuran_10px {
		font-size: 10px !important;
		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	}
</style>

<div class="content-wrapper">

	<section class="content">



		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">

					<table width="100%" border="0" class="ukuran_10px">
						<tr>
							<td style="font-size:14px !important;font-weight: bold;"><?= strtoupper($judul); ?></td>
							<td class="text-right" style="font-size:12px !important;font-weight: bold;"><?= $dataproyek->JobNo . ' - ' . $dataproyek->JobNm ?></td>
						</tr>
						<tr>
							<td></td>
							<td style="font-weight: bold;font-size:12px !important;" class="text-right"><?= $dataproyek->Lokasi ?></td>
						</tr>
					</table>

				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

				<div class="table-responsive">
					<table width="100%" border="0" class="ukuran_10px" style="margin-bottom: 10px !important;">
						<tr>
							<td width="6%">
								<a href="<?= site_url('job/sub_job/' . $dataproyek->JobNo) ?>" class="btn btn-warning ukuran_10px"><i class="fa fa-arrow-left"></i> Kembali</a>
							</td>
							<td>
								<a href="<?= site_url('job/datakontrak/' . $dataproyek->JobNo) ?>" class="btn btn-primary ukuran_10px">NEXT TO DATA KONTRAK &nbsp <i class="fa fa-arrow-right"></i></a>
							</td>
							<td></td>

						</tr>
					</table>
				</div>

			</div>
		</div>


		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="box">
						<div class="box-body">

							<section class="content-header">
								<div class="row">
									<div class="col-md-6">
										<div class="box box-warning">
											<div class="box-header with-border">
												<div class="box-body">
													<div class="form-group">
														<label>Job No</label>
														<input type="text" name="JobNo" value="<?php echo $dataproyek->JobNo ?>" id="JobNo" class="form-control input-sm" readonly>
														<label>Nama Proyek</label>
														<input type="text" name="JobNm" value="<?php echo $dataproyek->JobNm ?>" id="JobNm" class="form-control input-sm" readonly>
														<label>Lokasi</label>
														<input type="text" name="Lokasi" value="<?php echo $dataproyek->Lokasi ?>" id=" Lokasi" class="form-control input-sm" readonly>
														<label>Status Proyek</label>
														<input type="text" name="StatusJob" id="StatusJob" class="form-control input-sm" value="<?php echo $dataproyek->StatusJob ?>" readonly>

														<label>Kontraktor</label>
														<input type="text" name="CompanyId" id="CompanyId" class="form-control input-sm" value="<?php echo $dataproyek->CompanyId ?>" readonly>
														<p></p>
														<!-- <label class="form-label" for="customFile">Logo Company</label> -->
														<!-- <textarea name="Logo" id="Logo" class="form-control input-sm"><?php echo $dataproyek->Logo ?></textarea> -->
														<!-- <input type="file" name="logo_saya" > -->
														<img style="border:0px !important;" src="<?= $foto_logo_company ?>" class="img img-thumbnail" width="120" height="200">


													</div>
												</div>
											</div>
										</div>
									</div>
									<script type="text/javascript">
										$(document).ready(function() {
											logo_saya.onchange = evt => {
												const [file] = logo_saya.files
												if (file) {
													blah.src = URL.createObjectURL(file)
												}
											}
										});
									</script>
									<div class="col-md-6">
										<div class="box box-warning">
											<div class="box-header with-border">
												<div class="box-body">
													<div class="box-body" ;>
														<form role="form">
															<div class="form-group">
																<label>Deskripsi</label>
																<textarea class="form-control input-sm" rows="5" name="Deskripsi" readonly><?php echo $dataproyek->Deskripsi ?></textarea>
																<label>Instansi/Balai*</label>
																<input type="text" class="form-control input-sm" name="Instansi" value="<?php echo $dataproyek->Instansi ?>" id=" Instansi" readonly>

																<label>Kategori</label>
																<input type="text" name="Kategori" id="Kategori" class="form-control input-sm" value="<?php echo $dataproyek->Kategori ?>" readonly>
																</p>
																<label>Sistem Kontrak</label>
																<select class="form-control input-sm" name="SistemKontrak" id="SistemKontrak" readonly>
																	<?php foreach (['SYC', 'MYC'] as $row => $value) : ?>
																		<option <?= ($dataproyek->SistemKontrak == $value ? 'selected' : '') ?> value="<?= $value ?>"><?= $value ?></option>
																	<?php endforeach ?>
																	<!-- <option value="SYC">SYC</option>
																	<option value="MYC">MYC</option> -->
																</select>
																<p>
																	<label>No Rekening Pengeluaran Pusat</label>
																</p>
																<div class="col-md-6">
																	<input type="text" name="RekPengeluaranPusat" class="form-control input-sm" placeholder="No Rekening Pengeluaran Pusat" value="<?php echo $dataproyek->RekPengeluaranPusat ?>" readonly>
																</div>
																<div class="col-md-6">
																	<input type="text" name="BankPengeluaranPusat" class="form-control input-sm" placeholder="Nama Bank Pengeluaran Pusat" value="<?php echo $dataproyek->BankPengeluaranProyek ?>" readonly>
																</div>
																</p>

																<label>No Rekening Pengeluaran Proyek</label>
																</p>
																<div class="col-md-6">
																	<input type="text" name="RekPengeluaranProyek" class="form-control input-sm" placeholder="No Rekening Pengeluaran Proyek" value="<?php echo $dataproyek->RekPengeluaranProyek ?>" readonly>
																</div>
																<div class="col-md-6">
																	<input type="text" name="BankPengeluaranProyek" class="form-control input-sm" placeholder="Nama Bank Pengeluaran Proyek" value="<?php echo $dataproyek->BankPengeluaranProyek ?>" readonly>
																</div>
															</div>


													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" data-target="#modal-UDP" data-toggle="modal" class="btn btn-success pull-right"><i class="fa fa-check"> UPDATE DATA PROYEK</i></button>
								</div>
								</form>
						</div>
	</section>
</div>
</div>
</div>
</div>



<div class="modal fade bd-example-modal-lg" id="modal-UDP">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="Modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Update Data Proyek</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<form action="<?php echo base_url('Job/UpdateDataProyek') ?>" method="POST" enctype="multipart/form-data">
						<div class="col-md-6">
							<div class="box box-warning">
								<div class="box-header with-border">
									<div class="box-body">
										<div class="form-group">
											<label>Job No</label>
											<input type="text" name="JobNo" value="<?php echo $dataproyek->JobNo ?>" id="JobNo" class="form-control input-sm">
											<label>Nama Proyek</label>
											<input type="text" name="JobNm" value="<?php echo $dataproyek->JobNm ?>" id="JobNm" class="form-control input-sm">
											<label>Lokasi</label>
											<input type="text" name="Lokasi" value="<?php echo $dataproyek->Lokasi ?>" id=" Lokasi" class="form-control input-sm">
											<label>Status Proyek</label>
											<select class="form-control input-sm" name="StatusJob" id="StatusJob">
												<option value="Pelaksanaan">Pelaksanaan</option>
												<option value="Pemeliharaan">Pemeliharaan (Pra FHO)</option>
												<option value="Close">Close (FHO)</option>
											</select>
											<label>Kontraktor</label>
											<input type="text" name="CompanyId" id="CompanyId" class="form-control input-sm" value="<?php echo $dataproyek->CompanyId ?>">
											<label class="form-label" for="customFile">Input Logo Company</label>
											<input type="file" class="form-control input-sm" name="Logo" id="Logo" accept="image/*">
											<!-- <textarea class="form-control input-sm"><?php echo $dataproyek->Logo ?></textarea> -->

										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="box box-warning">
								<div class="box-header with-border">
									<div class="box-body">
										<div class="form-group">

											<label>Deskripsi</label>
											<textarea class="form-control input-sm" rows="5" name="Deskripsi"><?php echo $dataproyek->Deskripsi ?></textarea>
											<label>Instansi/Balai*</label>
											<input type="text" class="form-control input-sm" name="Instansi" value="<?php echo $dataproyek->Instansi ?>" id=" Instansi" required>

											<label>Kategori</label>
											<select class="form-control input-sm" name="Kategori" id="Kategori">
												<?php foreach (['Primary', 'Secondary'] as $row => $value) : ?>
													<option value="<?= $value ?>" <?= ($dataproyek->Kategori == $value) ? 'selected' : '' ?>><?= $value ?></option>
												<?php endforeach; ?>
											</select>
											</p>
											<label>Sistem Kontrak</label>
											<select class="form-control input-sm" name="SistemKontrak" id="SistemKontrak">
												<?php foreach (['SYC', 'MYC'] as $row => $value) : ?>
													<option <?= ($dataproyek->SistemKontrak == $value ? 'selected' : '') ?> value="<?= $value ?>"><?= $value ?></option>
												<?php endforeach ?>
												<!-- <option value="SYC">SYC</option>
												<option value="MYC">MYC</option> -->
											</select>
											<p>
												<label>No Rekening Pengeluaran Pusat</label>
											</p>
											<div class="col-md-6">
												<input type="text" name="RekPengeluaranPusat" class="form-control input-sm" placeholder="No Rekening Pengeluaran Pusat" value="<?php echo $dataproyek->RekPengeluaranPusat ?>">
											</div>
											<div class="col-md-6">
												<input type="text" name="BankPengeluaranPusat" class="form-control input-sm" placeholder="Nama Bank Pengeluaran Pusat" value="<?php echo $dataproyek->BankPengeluaranProyek ?>">
											</div>
											</p>

											<label>No Rekening Pengeluaran Proyek</label>
											</p>
											<div class="col-md-6">
												<input type="text" name="RekPengeluaranProyek" class="form-control input-sm" placeholder="No Rekening Pengeluaran Proyek" value="<?php echo $dataproyek->RekPengeluaranProyek ?>">
											</div>
											<div class="col-md-6">
												<input type="text" name="BankPengeluaranProyek" class="form-control input-sm" placeholder="Nama Bank Pengeluaran Proyek" value="<?php echo $dataproyek->BankPengeluaranProyek ?>">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-success pull-right"><i class="fa fa-check"> SIMPAN</i></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>




</section>
</div>

<div class="modal fade" id="modal_foto">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header mymodalheader">
				<h3>Klik Salah Satu Foto</h3>
			</div>
			<div class="modal-body body_foto">



			</div>


		</div>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		$('.btn_ok').click(function() {
			var ok = $(this).attr('data-OK');
			$('#logo_saya').val(ok);
		});
	});

	function PilihFoto() {
		$('.body_foto').html('');
		jQuery.ajax({
			url: '<?= site_url("LogoList/PilihFoto") ?>',
			type: 'POST',
			dataType: 'html',
			data: {
				flag: 'flag',
			},
			beforeSend: function() {
				$('.pilih_foto').attr('disabled', true);
			},
			success: function(data, textStatus, xhr) {
				$('.pilih_foto').attr('disabled', false);

				$('.body_foto').html(data);
				$('#modal_foto').modal('show');
			},
			error: function(xhr, textStatus, errorThrown) {
				alert('error');
			}
		});
	}
</script>
