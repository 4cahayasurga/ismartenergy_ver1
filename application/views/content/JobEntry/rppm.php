<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?php echo $judul ?> <p></p>
			<label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $rppm->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $rppm->JobNm ?></label>
			<br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $rppm->Lokasi ?></label>
		</h1>
		<!-- <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?php echo $title ?></li>
        </ol> -->
	</section>

	<section class="content-header">

		<a href="<?= site_url('job/sub_job/' . $rppm->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>


	</section>

	<!-- <Section class="content"> -->
	<section class="content">
		<!-- <div class="row"> -->
		<!-- <div class="col-md-14"> -->
		<div class="box box-solid">
			<div class="box-header with-border">
				<div class="box-header bg-purple-gradient">
					<h3 class="box-title">Realisasi Pelaksanaan Progress Mingguan </h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
					</div>
				</div>
			</div>

			<!-- /.box-header -->
			<div class="box-body">
				<div class="form-group">
					<a href="#modal-rppm" data-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Data</a>
					<p>

					<div class="box-body table-responsive no-padding">
						<table id="example1" class="table table-hover table-bordered table-striped">
							<col>
							<colgroup span="3"></colgroup>
							<colgroup span="3"></colgroup>
							<tr>
								<td rowspan="2">
									<center>No</center>
								</td>
								<td rowspan="2">
									<center>Nama Penawar / Uraian Kegiatan</center>
								</td>

								<td rowspan="2">
									<center>Sat</center>
								</td>
								<th colspan="4" scope="colgroup">
									<center>Kontrak</center>
								</th>
								<th colspan="5" scope="colgroup">
									<center>S/D Periode Minggu Lalu</center>
								</th>
								<th colspan="5" scope="colgroup">
									<center>Periode Ini</center>
								</th>
								<th colspan="4" scope="colgroup">
									<center>S/D Periode ini</center>
								</th>
								<td rowspan="2">
									<center>Keterangan</center>
								</td>
							</tr>
							<tr>
								<th scope="col">
									<center>Volume</center>
								</th>
								<th scope="col">
									<center>Harga Satuan <p> (Rupiah)</center>
								</th>
								<th scope="col">
									<center>Total Harga <p> (Rupiah)</center>
								</th>
								<th scope="col">
									<center>Bobot</center>
								</th>
								<th scope="col">
									<center>Volume</center>
								</th>
								<th scope="col">
									<center>Harga Satuan <p> (Rupiah)</center>
								</th>
								<th scope="col">
									<center>Total Harga <p> (Rupiah)</center>
								</th>
								<th scope="col">
									<center>Bobot</center>
								</th>
								<th scope="col">
									<center>Tgl Periode</center>
								</th>
								<th scope="col">
									<center>Volume</center>
								</th>
								<th scope="col">
									<center>Harga Satuan <p> (Rupiah)</center>
								</th>
								<th scope="col">
									<center>Total Harga <p> (Rupiah)</center>
								</th>
								<th scope="col">
									<center>Bobot</center>
								</th>
								<th scope="col">
									<center>Tgl Periode</center>
								</th>

								<th scope="col">
									<center>Volume</center>
								</th>
								<th scope="col">
									<center>Harga Satuan <p> (Rupiah)</center>
								</th>
								<th scope="col">
									<center>Total Harga <p> (Rupiah)</center>
								</th>
								<th scope="col">
									<center>Bobot</center>
								</th>
							</tr>

							<?php $i = '1'   ?>

							<tr>
								<td><?php echo $i++ ?></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td>
									<center><a href="#" class="btn btn-success" title="Edit Data"><i class="fa fa-edit"></i></a>&nbsp;
										<!-- <a onclick="return confirm('Yakin Hapus..?')" href="#" class="btn btn-danger" title="Hapus Data"><i class="fa fa-trash"></i></a> -->

								</td>
							</tr>

						</table>
					</div>
				</div>
				<div class="col-sm-6">

				</div>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- </div> -->

		<div class="modal fade " id="modal-rppm">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Entry RPPM</h4>
					</div>
					<div class="modal-body">
						<div class="col-md-6">
							<h4 class="modal-title">Kolom Kontrak</h4>
							<br>
							<p>
								<label>Nama Penawar / Uraian Kegiatan</label>
								<input type="text" name="UraianPekerjaan" id="UraianPekerjaan" class="form-control">
								<label>Satuan</label>
								<input type="text" name="Sat" id="Sat" class="form-control">
								<label>Volume</label>
								<input type="text" name="Volume_rap" id="Volume_rap" class="form-control">
								<label>Harga Satuan</label>
								<input type="text" name="HargaSat_rap" id="HargaSat_rap" class="form-control">
								<label>Total Harga</label>
								<input type="text" name="TotalHar_rap" id="TotalHar_rap" class="form-control">
								<label>Bobot</label>
								<input type="text" name="Bobot_rap" id="Bobot_rap" class="form-control">
						</div>

						<div class="col-md-6">
							<h4 class="modal-title">Periode Mingguan</h4>
							<br>

							<div class="col-md-6">
								<div class="form-group">
									<label>Periode </label>
									<input type="date" class="form-control" name="NpwpProyek" id="NpwpProyek">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Sampai </label>
									<input type="date" class="form-control" name="NpwpProyek" id="NpwpProyek">
								</div>
							</div>
							</p>
							<label>Volume</label>
							<input type="text" name="Volume_rap" id="Volume_rap" class="form-control">
							<label>Harga Satuan</label>
							<input type="text" name="HargaSat_rap" id="HargaSat_rap" class="form-control">
							<label>Total Harga</label>
							<input type="text" name="TotalHar_rap" id="TotalHar_rap" class="form-control">
							<label>Bobot</label>
							<input type="text" name="Bobot_rap" id="Bobot_rap" class="form-control">
							<label>Kategori Periode</label>
							<select name="KategoriPeriode" class="form-control">
								<option value="Periode Sekarang">Periode Sekarang</option>
								<option value="lalu">Periode Minggu Lalu</option>
								<option value="simpan">simpan</option>
							</select>
						</div>

						<button type="button" class="btn btn-primary">Save changes</button>

						<!-- </div> -->

						<!-- <div class="modal-footer" style="margin-top: 100px">
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>                  
              </div> -->
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
		</div>
		<!-- </div>  -->


		<div class="col-md-14" hidden>
			<div class="box box-solid">
				<div class="box box-header with-border">
					<div class="box-header bg-red-gradient">
						<h3 class="box-title">EMON / PROGRESS FISIK</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="form-group">
							<a href="javascript:void(0)" onclick="ShowModalTambah()" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Data</a>
							<p>
							<div class="box-body table-responsive no-padding">
								<table id="progressfisik" class="table table-hover table-bordered table-striped">
									<thead>
										<tr>
											<th hidden></th>
											<th>No.</th>
											<th>Tahun</th>
											<th>Bulan</th>
											<th>Rencana Fisik <p>Komulatif (%)</th>
											<th>Realisasi Fisik <p> Komulatif (%)</th>
											<th>Realisasi Keuangan <p> Komulatif (%)</th>
											<th>Rencana Fisik <p>Tahun Berjalan(%)</th>
											<th>Realisasi Fisik <p> Tahun Berjalan (%)</th>
											<th>Realisasi Keuangan <p>Tahun Berjalan(%)</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php $no = 1;
										foreach ($Progress as $pg) :
										?>
											<tr>
												<td hidden><?php echo $pg->LedgerNo ?></td>
												<td><?php echo $no++; ?></td>
												<td><?php echo $pg->Tahun; ?></td>
												<td><?php echo date("F", mktime(0, 0, 0, $pg->Bulan, 10)); ?></td>
												<td><?php echo $pg->RencanaK; ?></td>
												<td><?php echo $pg->RealisasiK; ?></td>
												<td><?php echo $pg->RealisasiKeuK; ?></td>
												<td><?php echo $pg->RencanaTB; ?></td>
												<td><?php echo $pg->RealisasiTB; ?></td>
												<td><?php echo $pg->RealisasiKeuTB; ?></td>
												<td>
													<a onclick="EditRppm(<?= $pg->LedgerNo ?>)" class="btn btn-xs btn-warning waves-effect waves-light"><i class="fa fa-pencil" title="Edit Data"></i> Edit</a>
													<button onclick="hapus(<?= $pg->LedgerNo ?>)" type="button" class="btn btn-xs btn-danger waves-effect waves-light"><i class="fa fa-trash"></i> Hapus</button>
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
		<!-- </div> -->




		<div class="modal fade" id="MyModal">
			<div class="modal-dialog modal-lg ">
				<div class="modal-content">
					<div class="modal-header modal-info">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="MyModalTitle"></h4>
					</div>
					<div class="modal-body" id="MyModalBody">

					</div>

				</div>
			</div>
		</div>




	</section>

	<script>
		// $(document).idle({
		// 	onIdle: function() {
		// 		window.location = "/logout.php";
		// 	},
		// 	idle: 10000
		// });

		var table;
		var JobNo = '<?= $rppm->JobNo ?>';
		var JobNm = '<?= $rppm->JobNm ?>';
		// $(document).ready(function() {

		// 	$.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
		// 		return {
		// 			"iStart": oSettings._iDisplayStart,
		// 			"iEnd": oSettings.fnDisplayEnd(),
		// 			"iLength": oSettings._iDisplayLength,
		// 			"iTotal": oSettings.fnRecordsTotal(),
		// 			"iFilteredTotal": oSettings.fnRecordsDisplay(),
		// 			"iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
		// 			"iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
		// 		};
		// 	};
		// 	table = $("#mytableprogressfisik").dataTable({
		// 		"responsive": true,
		// 		"lengthChange": true,
		// 		"autoWidth": false,
		// 		initComplete: function() {
		// 			var api = this.api();
		// 			$('#mytableprogressfisik_filter input')
		// 				.off('.DT')
		// 				.on('keyup.DT', function(e) {
		// 					if (e.keyCode == 13) {
		// 						api.search(this.value).draw();
		// 					}
		// 				});
		// 		},
		// 		processing: true,
		// 		serverSide: true,
		// 		ajax: {
		// 			"url": "<?= site_url('Rppm/get_json_progress_fisik/') ?>" + JobNo,
		// 			"type": "POST",
		// 		},
		// 		columns: [{
		// 				"data": "TimeEntry",
		// 				"orderable": false,
		// 				"searchable": false
		// 			},
		// 			{
		// 				"data": "Tahun",
		// 				"orderable": false,
		// 				"searchable": true
		// 			},
		// 			{
		// 				"data": "Bulan",
		// 				"orderable": false,
		// 				"searchable": true
		// 			},
		// 			{
		// 				"data": "RencanaK",
		// 				"orderable": false,
		// 				"searchable": false
		// 			},
		// 			{
		// 				"data": "RealisasiK",
		// 				"orderable": false,
		// 				"searchable": false
		// 			},
		// 			{
		// 				"data": "RealisasiKeuK",
		// 				"orderable": false,
		// 				"searchable": false
		// 			},
		// 			{
		// 				"data": "RencanaTB",
		// 				"orderable": false,
		// 				"searchable": false
		// 			},
		// 			{
		// 				"data": "RealisasiTB",
		// 				"orderable": false,
		// 				"searchable": false
		// 			},
		// 			{
		// 				"data": "RealisasiKeuTB",
		// 				"orderable": false,
		// 				"searchable": false
		// 			},
		// 			{
		// 				"data": "view",
		// 				"orderable": false
		// 			},

		// 		],
		// 		order: [
		// 			[1, 'Desc']
		// 		],
		// 		rowCallback: function(row, data, iDisplayIndex) {
		// 			var info = this.fnPagingInfo();
		// 			var page = info.iPage;
		// 			var length = info.iLength;
		// 			var index = page * length + (iDisplayIndex + 1);
		// 			$('td:eq(0)', row).html(index);
		// 		}
		// 	});

		// })



		function reload_table() {
			setTimeout(function() {
				$('#mytableprogressfisik').DataTable().ajax.reload();
			}, 1000);
		}


		function ShowModalTambah() {
			$('#MyModalTitle').html('Tambah Data Progress Fisik <br><label>' + JobNo + ' - ' + JobNm + '</label>');
			$('#MyModal').modal('show');
			$.ajax({
				url: "<?= site_url('Rppm/GetFormProgressFisik') ?>",
				type: "POST",
				dataType: "HTML",
				data: {
					type: "Tambah",
					JobNo: JobNo,
					JobNm: JobNm
				},
				success: function(data) {

					$('#MyModalBody').html(data);
				}
			})


		}

		function EditRppm(LedgerNo) {
			$.ajax({
				url: "<?= site_url('Rppm/GetFormProgressFisik') ?>",
				type: "POST",
				data: {
					'type': 'Edit',
					"LedgerNo": LedgerNo
				},
				success: function(response) {


					$('#MyModalTitle').html('EDIT DATA PROGRESS FISIK');
					$('#MyModal').modal('show');
					$('#MyModalBody').html(response);

				},
				error: function(jqXHR, exception) {
					var msg = '';
					if (jqXHR.status === 0) {
						msg = 'Not connect.\n Verify Network.';
					} else if (jqXHR.status == 404) {
						msg = 'Requested page not found. [404]';
					} else if (jqXHR.status == 500) {
						msg = 'Internal Server Error [500].';
					} else if (exception === 'parsererror') {
						msg = 'Requested JSON parse failed.';
					} else if (exception === 'timeout') {
						msg = 'Time out error.';
					} else if (exception === 'abort') {
						msg = 'Ajax request aborted.';
					} else {
						msg = 'Uncaught Error.\n' + jqXHR.responseText;
					}
					console.log(msg);
				},
			})
		}

		function hapus(LedgerNo) {
			Swal({
				title: 'Anda yakin?',
				text: "Apakah yakin ingin menghapus data ini?",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Yakin',
				cancelButtonColor: '#d33',

			}).then((result) => {
				if (result.value) {
					$.ajax({
						url: "<?= base_url('Rppm/Hapus/') ?>" + LedgerNo,
						type: "POST",
						success: function(response) {
							if (response == 'success') {
								$.toast({
									heading: 'Berhasil',
									text: 'Data Telah Dihapus',
									position: 'top-right',
									showHideTransition: 'slide',
									icon: 'success'
								})
							} else {
								$.toast({
									heading: 'Terjadi Kesalahan',
									text: 'Gagal Menghapus Data',
									position: 'top-right',
									showHideTransition: 'slide',
									icon: 'error'
								})
							}
							reload_table();
						},
						error: function(jqXHR, textStatus, errorThrown) {
							alert('Error deleting data');
						}
					});
				}
			});
		}
	</script>
</div>


<!-- /.container-fluid -->
</section>



</section>



<!-- </Section> -->
