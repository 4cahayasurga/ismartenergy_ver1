<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
			<label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $Pembatalanko->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $Pembatalanko->JobNm ?></label>
			<br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $Pembatalanko->Lokasi ?></label>

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<a href="<?= site_url('job/sub_job/' . $Pembatalanko->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
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
									<h3 class="box-title"> </h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
										<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
									</div>
								</div>
							</div>

							<div class="box-body">
								<table id="tblCancelKO" class="table table-bordered table-striped text-center">
									<thead>
										<tr>
											<th>No</th>
											<th>No. KO</th>
											<th>Tgl KO</th>
											<th>Nama Vendor</th>
											<th>Tipe KO</th>
											<th>Total KO (Rp)</th>
											<th>Telah Terbayar (Rp)</th>
											<th>Alasan Batal</th>
											<th>Canceled By</th>
											<th>Canceled On</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php $no = 1;
										foreach ($QueryKO as $qk) : ?>
											<tr>
												<td><?php echo $no++ ?></td>
												<td><?php echo $qk->NoKO ?></td>
												<td><?php echo $qk->TglKO ?></td>
												<td><?php echo $qk->VendorNm ?></td>
												<td><?php echo $qk->KategoriId ?></td>
												<td><?php echo number_format($qk->SubTotal) ?></td>
												<td><?php echo number_format($qk->PaymentAmount) ?></td>
												<td><?php echo $qk->CancelReason ?></td>
												<td><?php echo $qk->CanceledBy ?></td>
												<td><?php echo $qk->TimeCancel ?></td>
												<td>
													<a href="<?php echo base_url('job/ViewDetailPembatalanKO/' . $qk->NoKO) ?>" class="btn btn-success badge btn-xs" title="SELECT">Select</a>
													<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#batalPO<?php echo $qk->NoKO ?>">
														Cancel PO
													</button>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>

							<?php foreach ($QueryKO as $qk) : ?>
								<div class="modal fade" id="batalPO<?php echo $qk->NoKO ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<center>
													<h3 class="modal-title" id="exampleModalLongTitle">PEMBATALAN PO / KO</h3>
												</center>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<center>
													<h4><label for="">Anda Yakin ingin membatalkan PO Berikut..?</label></h4>
													<br>
													<h5><?php echo $qk->NoKO ?> - <?php echo $qk->VendorNm ?><br></h5>
													<h5>Total PO = Rp. <?php echo number_format($qk->SubTotal) ?></h5>
												</center>
												<label for="">Alasan Reject : </label>
												<textarea name="AlasanBatal" id="" cols="100" rows="5"></textarea>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												<button type="button" class="btn btn-primary">Save changes</button>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach; ?>

						</div>
					</div>
				</div>
			</div>
	</section>
</div>

	<script>
		$(document).ready(function() {
			$('#tblCancelKO').DataTable();
		});
	</script>
