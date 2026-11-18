<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
			<!-- <label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $mos->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $mos->JobNm ?></label>
            <br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $mos->Lokasi ?></label> -->

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<!-- <a href="<?= site_url('job/sub_job/' . $Vendor->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a> -->
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
									<h3 class="box-title">DAFTAR VENDOR </h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
										<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
									</div>
								</div>
							</div>
							<div class="box-body">
								<a data-toggle="modal" data-target="#TambahVendor" type="button" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp; TAMBAH</a>
								<p>
								<div class="form-group">
									<table id="TblVendor" class="table table-responsive table-bordered table-striped" border="1" cellpadding="2" width="100%">
										<thead>
											<tr>
												<th>Vendor Id</th>
												<th>Nama</th>
												<th>Alamat</th>
												<th>Kota/Kabupaten</th>
												<th>Propinsi</th>
												<th>Kategori</th>
												<th>Bidang Usaha</th>
												<th>Aksi</th>
											</tr>
										</thead>

										<tbody>
											<?php foreach ($Vendor as $V) : ?>
												<tr>
													<td><?php echo $V->VendorId ?></td>
													<td><?php echo $V->VendorNm ?></td>
													<td><?php echo $V->Alamat ?></td>
													<td><?php echo $V->Kota ?></td>
													<td><?php echo $V->Propinsi ?></td>
													<td><?php echo $V->Kategori ?></td>
													<td><?php echo $V->BidangUsaha ?></td>
													<td>
														<a class="badge bg-blue-active btn-danger" data-target="#EditVendor<?php echo $V->VendorId; ?>" data-toggle="modal"><i class="fa fa-edit"></i>&nbspEDIT</a>
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

	<div class="modal fade bd-example-modal-lg" id="TambahVendor">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4>Tambah Vendor</h4>
				</div>

				<div class="modal-body">
					<div class="col-sm-6">
						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Nama</label>
							<label for="" class="col-sm-1">:</label>
							<div class="col-sm-8">
								<input type="text" name="Nama" id="Nama" class="form-control">
								<input type="text" name="VendorID" id="VendorId" value="<?php echo $VendorId; ?>" hidden>
							</div>
						</div>
						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Alamat</label>
							<label for="" class="col-sm-1">:</label>
							<div class="col-sm-8">
								<textarea name="Alamat" id="Alamat" cols="30" rows="3" class="form-control"></textarea>
							</div>
						</div>
						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Kota/Kabupaten</label>
							<label for="" class="col-sm-1">:</label>
							<div class="col-sm-8">
								<input type="text" name="Kota" id="Kota" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Fax</label>
							<label for="" class="col-sm-1">:</label>
							<div class="col-sm-8">
								<input type="text" name="Fax" id="Fax" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">NPWP</label>
							<label for="" class="col-sm-1">:</label>
							<div class="col-sm-8">
								<input type="text" name="NPWP" id="NPWP" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Contact Person</label>
							<label for="" class="col-sm-1">:</label>
							<div class="col-sm-8">
								<input type="text" name="Conper" id="Conper" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Kategori</label>
							<label for="" class="col-sm-1">:</label>
							<div class="col-sm-8">
								<select name="Kategori" id="Kategori" class="form-control">
									<option value="">Pilih Kategori</option>
									<option value="EXPEDISI">EXPEDISI</option>
									<option value="MATERIAL">MATERIAL</option>
									<option value="PEMBORONG">PEMBORONG</option>
									<option value="PERALATAN">PERALATAN</option>
									<option value="SUBKON">SUBKON</option>
									<option value="TENAGA">TENAGA</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">PKP</label>
							<label for="" class="col-sm-1">:</label>
							<div class="col-sm-8">
								<label>
									<input type="checkbox" value="1">
								</label>
							</div>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Telepon/HP</label>
							<label for="" class="col-sm-1">:</label>
							<div class="col-sm-8">
								<input type="text" name="Telepon1" id="Telepon1" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Telepon/HP</label>
							<label for="" class="col-sm-1">:</label>
							<div class="col-sm-8">
								<input type="text" name="Telepon2" id="Telepon2" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Telepon/HP</label>
							<label for="" class="col-sm-1">:</label>
							<div class="col-sm-8">
								<input type="text" name="Telepon3" id="Telepon3" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Propinsi</label>
							<label for="" class="col-sm-1">:</label>
							<div class="col-sm-8">
								<input type="text" name="Propinsi" id="Propinsi" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Email</label>
							<label for="" class="col-sm-1">:</label>
							<div class="col-sm-8">
								<input type="text" name="Email1" id="Email1" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Email</label>
							<label for="" class="col-sm-1">:</label>
							<div class="col-sm-8">
								<input type="text" name="Email2" id="Email2" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Email</label>
							<label for="" class="col-sm-1">:</label>
							<div class="col-sm-8">
								<input type="text" name="Email3" id="Email3" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Bidang Usaha</label>
							<label for="" class="col-sm-1">:</label>
							<div class="col-sm-8">
								<input type="text" name="BidangUsaha" id="BidangUsaha" class="form-control">
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-7">
					<div class="box-header bg-blue-gradient">
						<h3 class="box-title">DAFTAR VENDOR </h3>
					</div>
					<div class="modal-body">
						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">No Rekening</label>
							<label for="" class="col-sm-1">:</label>
							<div class="col-sm-8">
								<input type="text" name="NoRek" id="NoRek" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Atas Nama</label>
							<label for="" class="col-sm-1">:</label>
							<div class="col-sm-8">
								<input type="text" name="AtasNama" id="AtasNama" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label for="" class="col-sm-2 col-form-label">Bank</label>
							<label for="" class="col-sm-1">:</label>
							<div class="col-sm-8">
								<select name="Bank" id="Bank" class="form-control">
									<option value="">-- Pilih Bank --</option>
									<?php foreach ($Bank as $Bank) { ?>
										<option value="<?= $Bank->Bank ?>"><?= $Bank->Bank ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-success">SIMPAN</button>
					<button type="button" class="btn btn-danger">BATAL</button>
				</div>
			</div>
		</div>
	</div>

	<?php foreach ($Vendor as $V) : ?>
		<div class="modal fade bd-example-modal-lg" id="EditVendor<?php echo $V->VendorId; ?>">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h4>Edit Vendor</h4>
					</div>

					<div class="modal-body">
						<div class="col-sm-6">
							<div class="form-group row">
								<input type="tel" name="VendorId" value="<?php echo $V->VendorId; ?>">
								<label for="" class="col-sm-2 col-form-label">Nama</label>
								<label for="" class="col-sm-1">:</label>
								<div class="col-sm-8">
									<input type="text" name="Nama" id="Nama" class="form-control" value="<?php echo $V->VendorNm ?>">
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-2 col-form-label">Alamat</label>
								<label for="" class="col-sm-1">:</label>
								<div class="col-sm-8">
									<textarea name="Alamat" id="Alamat" cols="30" rows="3" class="form-control"><?php echo $V->Alamat ?></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-2 col-form-label">Kota/Kabupaten</label>
								<label for="" class="col-sm-1">:</label>
								<div class="col-sm-8">
									<input type="text" name="Kota" id="Kota" class="form-control" value="<?php echo $V->Kota ?>">
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-2 col-form-label">Fax</label>
								<label for="" class="col-sm-1">:</label>
								<div class="col-sm-8">
									<input type="text" name="Fax" id="Fax" class="form-control" value="<?php echo $V->Fax ?>">
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-2 col-form-label">NPWP</label>
								<label for="" class="col-sm-1">:</label>
								<div class="col-sm-8">
									<input type="text" name="NPWP" id="NPWP" class="form-control" value="<?php echo $V->NPWP ?>">
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-2 col-form-label">Contact Person</label>
								<label for="" class="col-sm-1">:</label>
								<div class="col-sm-8">
									<input type="text" name="Conper" id="Conper" class="form-control" value="<?php echo $V->AtasNama ?>">
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-2 col-form-label">Kategori</label>
								<label for="" class="col-sm-1">:</label>
								<div class="col-sm-8">
									<select name="Kategori" id="Kategori" class="form-control">
										<?php foreach (['EXPEDISI', 'MATERIAL', 'PEMBORONG', 'PERALATAN', 'SUBKON', 'TENAGA'] as $row => $value) : ?>
											<option <?= ($V->Kategori == $value ? 'selected' : '') ?> value="<?= $value ?>"><?= $value ?></option>
										<?php endforeach ?>
										<!-- <option value="">Pilih Kategori</option>
										<option value="EXPEDISI">EXPEDISI</option>
										<option value="MATERIAL">MATERIAL</option>
										<option value="PEMBORONG">PEMBORONG</option>
										<option value="PERALATAN">PERALATAN</option>
										<option value="SUBKON">SUBKON</option>
										<option value="TENAGA">TENAGA</option> -->
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-2 col-form-label">PKP</label>
								<label for="" class="col-sm-1">:</label>
								<div class="col-sm-8">
									<label>
										<input type="checkbox" value="1" <?= ($V->PKP == 1) ? 'checked' : '' ?>>
									</label>
								</div>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group row">
								<label for="" class="col-sm-2 col-form-label">Telepon/HP</label>
								<label for="" class="col-sm-1">:</label>
								<div class="col-sm-8">
									<input type="text" name="Telepon1" id="Telepon1" class="form-control" value="<?php echo $V->Telepon1 ?>">
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-2 col-form-label">Telepon/HP</label>
								<label for="" class="col-sm-1">:</label>
								<div class="col-sm-8">
									<input type="text" name="Telepon2" id="Telepon2" class="form-control" value="<?php echo $V->Telepon2 ?>">
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-2 col-form-label">Telepon/HP</label>
								<label for="" class="col-sm-1">:</label>
								<div class="col-sm-8">
									<input type="text" name="Telepon3" id="Telepon3" class="form-control" value="<?php echo $V->Telepon3 ?>">
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-2 col-form-label">Propinsi</label>
								<label for="" class="col-sm-1">:</label>
								<div class="col-sm-8">
									<input type="text" name="Propinsi" id="Propinsi" class="form-control" value="<?php echo $V->Propinsi ?>">
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-2 col-form-label">Email</label>
								<label for="" class="col-sm-1">:</label>
								<div class="col-sm-8">
									<input type="text" name="Email1" id="Email1" class="form-control" value="<?php echo $V->Email1 ?>">
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-2 col-form-label">Email</label>
								<label for="" class="col-sm-1">:</label>
								<div class="col-sm-8">
									<input type="text" name="Email2" id="Email2" class="form-control" value="<?php echo $V->Email2 ?>">
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-2 col-form-label">Email</label>
								<label for="" class="col-sm-1">:</label>
								<div class="col-sm-8">
									<input type="text" name="Email3" id="Email3" class="form-control" value="<?php echo $V->Email3 ?>">
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-2 col-form-label">Bidang Usaha</label>
								<label for="" class="col-sm-1">:</label>
								<div class="col-sm-8">
									<input type="text" name="BidangUsaha" id="BidangUsaha" class="form-control" value="<?php echo $V->BidangUsaha ?>">
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-7">
						<div class="box-header bg-blue-gradient">
							<h3 class="box-title">DAFTAR VENDOR </h3>
						</div>
						<div class="modal-body">
							<div class="form-group row">
								<label for="" class="col-sm-2 col-form-label">No Rekening</label>
								<label for="" class="col-sm-1">:</label>
								<div class="col-sm-8">
									<input type="text" name="NoRek" id="NoRek" class="form-control" value="<?php echo $V->NoRek ?>">
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-2 col-form-label">Atas Nama</label>
								<label for="" class="col-sm-1">:</label>
								<div class="col-sm-8">
									<input type="text" name="AtasNama" id="AtasNama" class="form-control" value="<?php echo $V->AtasNama ?>">
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-2 col-form-label">Bank</label>
								<label for="" class="col-sm-1">:</label>
								<div class="col-sm-8">
									<input type="text" name="Bank" id="Bank" class="form-control" value="<?php echo $V->Bank ?>">
									<!-- <select name="Bank" id="Bank" class="form-control"> -->

									<!-- <?php foreach ($Bank as $value) : ?>
											<option <?= ($V->Bank == $value ? 'selected' : '') ?> value="<?= $value ?>"><?= $value ?></option>
										<?php endforeach ?> -->


									<!-- <option value="<?php echo $V->Bank ?>"><?php echo $V->Bank ?></option> -->

									<!-- </select> -->
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-success">SIMPAN</button>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
	<script>
		$(document).ready(function() {
			var table = $('#TblVendor').DataTable({
				lenghtChange: false,
				buttons: ['pdf', 'excel', 'print']
			});
			table.buttons().container()
				.appendTo('#TblVendor_wrapper .col-sm-6:eq(0)');
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