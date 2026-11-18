<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
			<label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $FormKO->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $FormKO->JobNm ?></label>
			<br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $FormKO->Lokasi ?></label>

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<a href="<?= site_url('job/sub_job/' . $FormKO->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
		<!-- <a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a> -->
	</section>

	<!-- <Section class="content"> -->
	<section class="content-header">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-warning">
					<div class="box-header with-border">
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<div class="box-body">
						<!-- <div class="form-group row">
							<label class="col-sm-1 col-form-label">Search</label>
							<div class="col-sm-3">
								<input type="text" name="Cari" id="Cwari" class="form-control">
							</div>
							<button type="button" class="btn btn-default"><i class="fa fa-search"></i>Cari</button>
						</div> -->
						<div class="box-body">
							<div class="form-group row">
								<a href="<?= site_url('Job/FormEntryKO/' . $FormKO->JobNo) ?>" type="button" class="btn btn-dropbox"><i class="fa fa-plus"></i> &nbsp; TAMBAH KONTRAK</a>
								<a href="<?= site_url('Job/FormEntryPO/' . $FormKO->JobNo) ?>" type="button" class="btn btn-dropbox"><i class="fa fa-plus"></i> &nbsp; TAMBAH PO</a>
								<a href="<?= site_url('Job/FormEntryKomix/' . $FormKO->JobNo) ?>#" type="button" class="btn btn-dropbox"><i class="fa fa-plus"></i> &nbsp; TAMBAH KOMIX</a>
							</div>
						</div>

						<div class="box-body table-responsive no-no-padding">
							<table id="TblKO" class="table table-hover" border="1" cellspacing="2" width="100%"">
							<thead>
								<tr>
									<th>No</th>
									<th>No. KO</th>
									<th>Tgl KO</th>
									<th>Vendor</th>
									<th>Tipe KO</th>
									<th>Add/Revisi Ke</th>
									<th>Total KO</th>
									<th>KO Approved By</th>
									<th>KO Approved On</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php $no = 1;
								foreach ($KO as $ko) :
								?>
								<tr>
									<td><?php echo $no++ ?></td>
									<td><?php echo $ko->NoKO ?></td>
									<td><?php echo $ko->TglKO ?></td>
									<td><?php echo $ko->VendorNm ?></td>
									<td><?php echo $ko->KategoriId ?></td>
									<td><?php echo $ko->AddendumKe ?></td>
									<td><?php echo number_format($ko->SubTotal) ?></td>
									<td><?php echo $ko->ApprovedBy ?></td>
									<td><?php echo $ko->TimeApproved ?></td>
									<td>
										<a href=" <?php echo base_url('job/FrmSelectKO/' . $ko->NoKO) ?>" data-toggle=" modal" class="btn btn-primary btn-xs">SELECT</a>
								<a data-target="" data-toggle="modal" class="btn btn-primary btn-xs">PRINT</a>
								<a data-target="" data-toggle="modal" class="btn btn-primary btn-xs">PDF</a>
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

	<script>
		$(document).ready(function() {
			$('#TblKO').DataTable();
		})
	</script>


	<script>
		$(document).idle({
			onIdle: function() {
				window.location = " /logout.php";
			},
			idle: 10000
		});
	</script>
</div>


<!-- /.container-fluid -->
</section>

</section>



<!-- </Section> -->
