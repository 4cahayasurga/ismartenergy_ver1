<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
			<label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $k3->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $k3->JobNm ?></label>
			<br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $k3->Lokasi ?></label>

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<a href="<?= site_url('Job/sub_job/' . $k3->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
		<!-- <a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a> -->
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
								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#TambahData">
									Tambah Data
								</button>
								<br>
								<table id="example" class="table table-striped table-bordered" style="width:100%">
									<thead>
										<tr>
											<th>Periode</th>
											<th>Kasus Kematian</th>
											<th>Cidera Dengan Hilang Waktu Kerja(FR)</th>
											<th>Tingkat Keparahan (SR)</th>
											<th>Kasus Kecelakaan Kerja (IR)</th>
											<th>Non Conformity Open</th>
											<th>Non Conformity Close</th>
											<th>#</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($DataK3 as $K3) : ?>
											<tr>
												<td><?= $K3->Periode; ?></td>
												<td><?= $K3->KasusKematian; ?></td>
												<td><?= $K3->FR;  ?></td>
												<td><?= $K3->SR;  ?></td>
												<td><?= $K3->IR;  ?></td>
												<td><?= $K3->NcOpen; ?></td>
												<td><?= $K3->NcClose; ?></td>
												<td>
													<button type="button" data-toggle="modal" data-target="#EditData<?php echo $K3->LedgerNo; ?>" class="btn btn-success badge btn-xs">Select</button>
													<!-- <a href="<?= site_url('FrmResumeK3/Delete/' . $K3->LedgerNo); ?>" onclick="return confirm('Yaqin Hapus Data..?')" type="submit" class="btn btn-danger badge btn-xs">Delete</a> -->
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
	</section>

	<!-- Modal Tambah Data -->
	<div class="modal fade" id="TambahData" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form action="<?php echo base_url('FrmResumeK3/Simpan') ?>" method="post" enctype="multipart/form-data">
					<div class="modal-body">
						<input type="text" name="JobNo" id="JobNo" class="form-control hidden" value="<?= $k3->JobNo ?>">
						<table class="table table-striped table-bordered" style="width:100%">
							<tr>
								<td colspan="3" style="background-color:burlywood;"> Lagging Indicator</td>
							</tr>
							<tr>
								<td>Periode</td>
								<td>:</td>
								<td><input type="date" class="form-control" name="PeriodeK3" id="PeriodeK3"></td>
							</tr>
							<tr>
								<td>Kasus Kematian</td>
								<td>:</td>
								<td><input type="text" class="form-control" name="KasusKematian" id="KasusKematian"></td>
							</tr>
							<tr>
								<td>Cidera Dengan Jumlah Hilang Waktu Kerja (FR)</td>
								<td>:</td>
								<td><input type="text" name="FR" id="FR" class="form-control"></td>
							</tr>
							<tr>
								<td>Tingkat Keparahan (SR)</td>
								<td>:</td>
								<td><input type="text" name="SR" id="SR" class="form-control"></td>
							</tr>
							<tr>
								<td>Kasus Kecelakaan Kerja (IR)</td>
								<td>:</td>
								<td><input type="text" name="IR" id="IR" class="form-control"></td>
							</tr>
						</table>

						<table class="table table-striped table-bordered" style="width:100%">
							<tr>
								<td colspan="3" style="background-color:burlywood;"> Ketidaksesuaian / Non Conformity</td>
							</tr>
							<tr>
								<td>Non Conformity Open</td>
								<td>:</td>
								<td><input type="text" class="form-control" name="NCO" id="NCO"></td>
							</tr>
							<tr>
								<td>Non Conformity Close</td>
								<td>:</td>
								<td><input type="text" name="NCC" id="NCC" class="form-control"></td>
							</tr>
							< </table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save changes</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Modal Edit Data -->
	<?php foreach ($DataK3 as $K3) : ?>
	<div class="modal fade" id="EditData<?php echo $K3->LedgerNo; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form action="<?php echo base_url('FrmResumeK3/Simpan') ?>" method="post" enctype="multipart/form-data">
					<div class="modal-body">
						<input type="text" name="JobNo" id="JobNo" class="form-control" value="<?= $k3->JobNo ?>">
						<table class="table table-striped table-bordered" style="width:100%">
							<tr>
								<td colspan="3" style="background-color:burlywood;"> Lagging Indicator</td>
							</tr>
							<tr>
								<td>Periode</td>
								<td>:</td>
								<td><input type="date" class="form-control" name="PeriodeK3" id="PeriodeK3" value="<?php echo $K3->Periode; ?>"></td>
							</tr>
							<tr>
								<td>Kasus Kematian</td>
								<td>:</td>
								<td><input type="text" class="form-control" name="KasusKematian" id="KasusKematian" value="<?php echo $K3->KasusKematian; ?>"></td>
							</tr>
							<tr>
								<td>Cidera Dengan Jumlah Hilang Waktu Kerja (FR)</td>
								<td>:</td>
								<td><input type="text" name="FR" id="FR" class="form-control" value="<?php echo $K3->FR; ?>"></td>
							</tr>
							<tr>
								<td>Tingkat Keparahan (SR)</td>
								<td>:</td>
								<td><input type="text" name="SR" id="SR" class="form-control" value="<?php echo $K3->SR; ?>"></td>
							</tr>
							<tr>
								<td>Kasus Kecelakaan Kerja (IR)</td>
								<td>:</td>
								<td><input type="text" name="IR" id="IR" class="form-control" value="<?php echo $K3->IR; ?>"></td>
							</tr>
						</table>

						<table class="table table-striped table-bordered" style="width:100%">
							<tr>
								<td colspan="3" style="background-color:burlywood;"> Ketidaksesuaian / Non Conformity</td>
							</tr>
							<tr>
								<td>Non Conformity Open</td>
								<td>:</td>
								<td><input type="text" class="form-control" name="NCO" id="NCO" value="<?php echo $K3->NcOpen; ?>"></td>
							</tr>
							<tr>
								<td>Non Conformity Close</td>
								<td>:</td>
								<td><input type="text" name="NCC" id="NCC" class="form-control" value="<?php echo $K3->NcClose; ?>"></td>
							</tr>
							< </table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save changes</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php endforeach; ?>



</div>

<script>
	$(document).ready(function() {
		$('#example').DataTable();
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


<!-- /.container-fluid -->
</section>

</section>



<!-- </Section> -->
