<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
			<label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $mosout->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $mosout->JobNm ?></label>
			<br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $mosout->Lokasi ?></label>

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<a href="<?= site_url('job/sub_job/' . $mosout->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
		<!-- <a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a> -->
	</section>

	<!-- <Section class="content"> -->


	<section class="content-header">
		<div class="row" id="MOS-OUT">
			<div class="col-md-12">
				<div class="box box-warning">
					<div class="box-header with-border">
						<div class="box-header bg-red-gradient">
							<h3 class="box-title">Material OUT </h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
							</div>
						</div>
					</div>

					<div class="box-body">
						<div class="form-group">
							<div class="box-body">
								<div class="form-group">
									<a href="#modal-EntryMOS-Out" data-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Data</a>
									<p>
									<div class="box-body table-responsive no-padding">
										<table id="example1" class="table table-hover table-bordered table-striped">
											<tr>
												<th style="background-color: aliceblue;">Tgl Pemakaian Material / Barang</th>
												<th style="background-color: aliceblue;">Nama Material / Barang</th>
												<th style="background-color: aliceblue;">Satuan</th>
												<th style="background-color: aliceblue;">QTY</th>
												<th style="background-color: aliceblue;">Keterangan</th>
												<th style="background-color: aliceblue;">Approved By</th>
												<th style="background-color: aliceblue;">Tgl Approved</th>
												<th style="background-color: aliceblue;"></th>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--         </div>
                </div>
            </div>
 -->

					<div class="modal" tabindex="-1" role="dialog" id="modal-EntryMOS-Out">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title">Entry Pemakaian Barang / Material</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<!-- <div class="col-md-10">                                   -->
									<br>
									<p>
										<label>Job No</label>
										<input type="text" name="JobNo" id="JobNo" class="form-control" value="<?php echo $mosout->JobNo ?> - <?php echo $mosout->JobNm ?>">
										<label>Tgl Pemakaian Material / Barang </label>
										<input type="date" name="TglKeluarBarang" id="NoMos" class="form-control" required="required">
										<label>Nama Barang / Material</label>
										<select name="KdRAP" id="KdRAP" class="form-control select2">
											<option value="">--- Pilih Uraian ---</option>
											<?php foreach($selectMos as $sm): ?>
												<option value=""><?= $sm->KdRAP; ?> - <?= $sm->Uraian; ?></option>
												<?php endforeach; ?>
										</select>									

										<label>Qty Pemakaian</label>
										<input type="text" name="QtyPakai" id="QtyPakai" class="form-control" required="required">
										<label>Satuan</label>
										<input type="text" name="Uom" id="Uom" class="form-control">
										<label>Keterangan</label>
										<textarea class="form-control" name="Keterangan_brgKeluar" required="required"></textarea>

										<!-- </div> -->

									<div class="modal-footer">
										<button type="button" class="btn btn-primary">Save</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>

								</div>

							</div>
						</div>
					</div>

	</section>

	<script>
		$(document).ready(function() {
			$('#example1').DataTable();
		})

		function tampil_detail_mos(value) {
			var NoKO = value;
			var JobNo = $('.myko :selected').attr('data-myjobno');
			// alert(JobNo);
			// return false;
			$('#tbl_detail_ko').html('');


			$.ajax({
				type: "POST",
				url: "<?= site_url('Job/tbl_detail_ko') ?>",
				data: {
					'NoKO': NoKO,
					'JobNo': JobNo
				},
				dataType: "html",
				beforeSend: function() {
					$('.mos_tambah_loading').show();
				},
				success: function(response) {
					$('.mos_tambah_loading').hide();
					$('#tbl_detail_ko').html(response);
				},
				error: function() {
					$.toast({
						heading: 'Information',
						text: 'Terjadi Kesalahan',
						icon: 'error',
						loader: true,
						loaderBg: '#9EC600'
					})
					return false;
				}
			});


		}
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
