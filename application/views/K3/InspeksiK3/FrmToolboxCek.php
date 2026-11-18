<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<!-- <p></p>
			<label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $invoice->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $invoice->JobNm ?></label>
            <br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $invoice->Lokasi ?></label> -->

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<a href="<?= site_url('K3/C_ToolBox/Index/') ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>

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
								<div class="col-md-12">

									<table border="5" class="table table-hover table-bordered" width="100%">
										<tr>
											<th rowspan="3"><img src="<?= site_url('assets/dist/img/MDH 86X84.png') ?>" alt=""></th>
											<th colspan="3">
												<center>PT. MINARTA DUTAHUTAMA</center>
											</th>

										</tr>
										<tr>
											<th colspan="3">
												<center>FORMULIR TOOLBOX MEETING</center>
											</th>

										</tr>
										<tr>
											<th style="width: 30%;">
												<center> MDH-FF-SMK3-PRO-06-06 Revisi : 00 Tgl. Terbit : 10.01.2023</center>
											</th>
											<th>
												<center> Revisi : 00</center>
											</th>
											<th>
												<center> Tgl. Terbit : 10.01.2023</center>
											</th>

										</tr>
									</table>
								</div>

								<div class="col-md-6">
									<form action="<?= site_url('K3/C_ToolBox/simpandatahdr') ?>" method="post">
										<table class="table table-responsive table-hover table-bordered" border="2">
											<tr>
												<th>Nomor Surat </th>
												<th>:</th>
												<th><input type="text" name="NoSurat" id="NoSurat" class="form-control" value="MDH-FTM-<?= $NoSurat; ?>"></th>
											</tr>
										</table>
										<div class="col-md-12">
											<button class="btn btn-dropbox" data-toggle="modal" data-target="#exampleModal">Tambah Peserta</button>

											<table class="table table-hover">
												<thead>
													<tr>
														<th>No. </th>
														<th>Nama Peserta</th>
														<th>Divisi / Job Description</th>
													</tr>
												</thead>
												<tbody>
													<?php $no = 1;
													foreach ($DataMember as $dm) : ?>
														<tr>
															<td><?= $no++; ?></td>
															<td><?= $dm->NamaPeserta; ?></td>
															<td><?= $dm->DivisiJabatan; ?></td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
										<table class="table table-responsive table-hover table-bordered" border="5">
											<tr>
												<th>Nama Proyek</th>
												<th>:</th>
												<th><select name="JobNo" id="JobNo" class="form-control input-sm" required>
														<option value="">-- Pilih Job --</option>
														<?php foreach ($list_job_akses as $job_param) : ?>
															<option value="<?= $job_param['JobNo'] ?>" <?= ($JobNo == $job_param['JobNo']) ? 'selected' : '' ?>><?= $job_param['JobNo'] . ' - ' . $job_param['JobNm'] ?></option>
														<?php endforeach ?>
													</select></th>
											</tr>


											<tr>
												<th>Hari / Tanggal </th>
												<th>:</th>
												<th><input type="date" name="tgl" id="tgl" class="form-control"></th>
											</tr>
											<tr>
												<th>Pukul </th>
												<th>:</th>
												<th><input type="time" name="jam" id="jam" class="form-control"></th>
												<!-- <th><input type="text" name="KdUrut" id="KdUrut" class="form-control " value="<?= $tambah_satu ?>"></th> -->
											</tr>
											<tr>
												<td colspan="3">
													<textarea name="topik" id="topik" class="form-control" cols="5" rows="5" required="required">Tool Box Talk Topik : </textarea>
												</td>
											</tr>
											<!-- <tr>
											<th>Nama Peserta</th>
											<th>:</th>
											<th><select name="NamaPeserta" id="NamaPeserta" class="form-control">
													<option value=""></option>
												</select></th>
										</tr> -->
										</table>
										<div class="modal-footer">
											<button type="submit" class="btn btn-success"><i class="fa fa-check"></i>&nbsp;Simpan</button>
											<!-- <a href="" data-toggle="modal" class="btn btn-success" type="submit"><i class="fa fa-check"></i> Simpan</a> -->
										</div>
									</form>

								</div>





								<!-- Modal -->
								<form action="<?= site_url('K3/C_ToolBox/simpandata') ?>" method="post">
									<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLabel">Tambah Peserta</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<table class="table table-responsive table-hover">
														<tr>
															<th>Nama Peserta</th>
															<td>:</td>
															<td><input type="text" name="NamaPeserta" id="NamaPeserta" class="form-control"></td>
															<td><input type="text" name="KdSurat" id="KdSurat" value="MDH-FTM-<?= $NoSurat; ?>"></td>
														</tr>
														<tr>
															<th>Divisi / Job Description</th>
															<td>:</td>
															<td><input type="text" name="Divisi" id="Divisi" class="form-control"></td>
														</tr>
													</table>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
													<button type="submit" class="btn btn-primary">Simpan</button>
												</div>
											</div>
										</div>
									</div>
								</form>


							</div>

						</div>
					</div>
				</div>
			</div>
	</section>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

	<script src="<?= base_url('assets/select2_baru.js') ?>"></script>
	<script type="text/javascript">
		$(document).ready(function() {


			if ($('#JobNo :selected').val() != null || $('#JobNo :selected').val() != '') {
				// table_invoice();
			}

			$('#JobNo').select2({
				'width': '100%',
				'dropdownCssClass': "huruf_10"
			});

			$('#btn_search').click(function() {
				table_invoice();
			});

			$('#JobNo').change(function() {
				table_invoice();
			});



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
