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

	<!-- <section class="content-header">
		<a href="#" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
		<a href="#" data-toggle="modal" class="btn btn-success" type="button"><i class="fa fa-check"></i> Simpan</a>
	</section> -->

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

								<div class="col-md-12">
									<a href="<?= site_url('K3/C_ToolBox/TambahData/') ?>" class="btn bg-primary btn-flat btn-sm ">Tambah Data</a>

									<table class="table table-responsive table-hover">
										<thead>

											<tr>
												<th>No. </th>
												<th>Kd. Surat</th>
												<th>Kode Proyek</th>
												<th>Nama Proyek</th>
												<th>Pembahasan Meeting</th>
												<th>Nama Peserta</th>
												<th>#</th>
											</tr>
										</thead>
										<tbody>

											<?php $no = 1;
											foreach ($K3ToolBox as $tb) : ?>
												<tr>
													<td><?= $no++ ?></td>
													<td><?= $tb['KdSurat'] ?></td>
													<td><?= $tb['JobNo'] ?></td>
													<td><?= $tb['JobNm'] ?></td>
													<td><?= $tb['Keterangan'] ?></td>
													<td style="float: left;"><?= Data_Peserta($tb['KdSurat']) ?></td>
													<td>
														<a href="<?= site_url('K3/C_ToolBox/EditData/'. $tb['LedgerNo']. '/' . $tb['KdSurat']) ?>" type="button" class="btn btn-xs btn-primary" title='Edit'><i class="fa fa-pencil"></i></a>
														<a href="#" type="button" class="btn btn-xs btn-facebook" title='Print'><i class="fa fa-print"></i></a>
													</td>
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
	</section>

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
