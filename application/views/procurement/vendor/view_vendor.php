<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<button onclick="history.back()" class="btn btn-sm btn-link" style="background-color:#34495e;color:white;"><i class="fa fa-arrow-left"></i> Back</button>
	</section>

	<section class="content-header">

		<div class="row" id="MOD-IN">

			<div class="col-sm-12">

				<div class="box">
					<div class="box-header">
						<a href="javascript:void(0)" class="btn btn-sm btn-success" onclick="tambah_vendor()">Tambah Vendor <i class="fa fa-plus"></i></a>
						<a href="javascript:void(0)" class="btn btn-sm btn-info" onclick="reload_vendor()">Refresh</a>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover table-condesed" id="mytable">
								<thead>
									<tr>
										<th>No.</th>
										<th>#</th>
										<th>Vendor Id</th>
										<th>Nama</th>
										<th>Alamat</th>
										<th>Kota/Kabupaten</th>
										<th>Propinsi</th>
										<th>Kategori</th>
										<th>Bidang Usaha</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
					<div class="overlay loadingsaya" style="display: none;">
						<i class="fa fa-refresh fa-spin"></i>
					</div>
				</div>


			</div>



	</section>

</div>



</section>

</section>

<div class="modal fade" id="modal_tambah">
	<div class="modal-dialog modal-lg" id="modal_dialog_tambah">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal_title_tambah">Form Entry Vendor</h5>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="modal_body_tambah">

				<div class="box" style="border:none;">

					<div class="box-body">

						<style>
							.atur_titik_dua {
								width: 2%;
								padding-left: 3px;
								padding-right: 3px;
								text-align: center;
							}

							td {
								padding-top: 5px;
								padding-bottom: 5px;

							}
						</style>

						<form id="form_saya">

							<div class="col-lg-6">

								<table style="width: 100%;font-size:10px !important;" border="0">
									<tr>
										<td style="width:20%;">Nama</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<input type="text" class="form-control input-sm VendorNm" placeholder="Nama" name="VendorNm" id="VendorNm_tambah" required>
										</td>
									</tr>
									<tr>
										<td>Alamat</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<textarea name="Alamat" id="Alamat_tambah" class="form-control input-sm" placeholder="Alamat"></textarea>
										</td>
									</tr>
									<tr>
										<td>Kota/Kabupaten</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<input type="text" class="form-control input-sm" placeholder="Isi Kota/Kab" name="Kota" id="Kota_tambah">
										</td>
									</tr>
									<tr>
										<td>Provinsi</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<input type="text" class="form-control input-sm" placeholder="Isi Provinsi" name="Propinsi" id="Propinsi_tambah">
										</td>
									</tr>

									<tr>
										<td>Fax</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<input type="text" class="form-control input-sm" placeholder="Isi Fax" name="Fax" id="Fax_tambah">
										</td>
									</tr>

									<tr>
										<td>NPWP</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<input type="text" class="form-control input-sm" placeholder="Isi NPWP" name="NPWP" id="NPWP_tambah">
										</td>
									</tr>

									<tr>
										<td>Contact Person</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<input type="text" class="form-control input-sm" placeholder="Isi Contact Person" name="ContactPerson" id="ContactPerson_tambah">
										</td>
									</tr>

									<tr>
										<td>Kategori</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<select name="Kategori" id="Kategori_tambah" class="form-control input-sm" required>
												<option value="">-- Pilih Kategori --</option>
												<option value="EXPEDISI">EXPEDISI</option>
												<option value="MATERIAL">MATERIAL</option>
												<option value="PEMBORONG">PEMBORONG</option>
												<option value="PERALATAN">PERALATAN</option>
												<option value="SUBKON">SUBKON</option>
												<option value="TENAGA">TENAGA</option>
											</select>
										</td>
									</tr>

									<tr>
										<td></td>
										<td class="atur_titik_dua"></td>
										<td style="padding-left:3px;">
											<div class="form-check form-check-inline">
												<input class="form-check-input" type="checkbox" name="PKP" id="PKP_tambah" value="1">
												<label class="form-check-label" for="PKP_tambah">PKP</label>
											</div>
										</td>
									</tr>



								</table>

							</div>

							<div class="col-lg-6">

								<table style="width: 100%;font-size:10px !important;" border="0">

									<tr>
										<td>Telepon</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<div class="row">
												<div class="col-sm-4">

													<div class="form-group">
														<label for="Telepon1">Telepon 1</label>
														<input type="text" class="form-control input-sm" placeholder="Telepon 1" name="Telepon1" id="Telepon1_tambah">
													</div>
												</div>

												<div class="col-sm-4">
													<div class="form-group">
														<label for="Telepon2">Telepon 2</label>
														<input type="text" class="form-control input-sm" placeholder="Telepon 2" name="Telepon2" id="Telepon2_tambah">
													</div>
												</div>

												<div class="col-sm-4">
													<div class="form-group">
														<label for="Telepon3">Telepon 3</label>
														<input type="text" class="form-control input-sm" placeholder="Telepon 3" name="Telepon3" id="Telepon3_tambah">
													</div>
												</div>



											</div>
										</td>
									</tr>

									<tr>
										<td>Email</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<div class="row">
												<div class="col-sm-4">

													<div class="form-group">
														<label for="Email1">Email 1</label>
														<input type="email" class="form-control input-sm" placeholder="Email 1" name="Email1" id="Email1_tambah">
													</div>
												</div>

												<div class="col-sm-4">
													<div class="form-group">
														<label for="Email2">Email 2</label>
														<input type="email" class="form-control input-sm" placeholder="Email 2" name="Email2" id="Email2_tambah">
													</div>
												</div>

												<div class="col-sm-4">
													<div class="form-group">
														<label for="Email3">Email 3</label>
														<input type="email" class="form-control input-sm" placeholder="Email 3" name="Email3" id="Email3_tambah">
													</div>
												</div>



											</div>
										</td>
									</tr>

									<tr>
										<td>Bidang Usaha</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<input type="text" class="form-control input-sm" placeholder="Isi Bidang Usaha" name="BidangUsaha" id="BidangUsaha_tambah">
										</td>
									</tr>


									<tr>
										<td colspan="3" class="bg-blue-gradient" style="font-size: 15px !important;">Data Bank Vendor</td>
									</tr>

									<tr>
										<td style="width: 20%;">No. Rekening</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<input type="text" class="form-control input-sm" placeholder="No. Rekening" name="NoRek" id="NoRek_tambah">
										</td>
									</tr>

									<tr>
										<td>Atas Nama</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<input type="text" class="form-control input-sm" placeholder="Atas Nama" name="AtasNama" id="AtasNama_tambah">
										</td>
									</tr>

									<tr>
										<td>Bank</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<select name="Bank" id="Bank_tambah" class="form-control input-sm select2">
												<option value="">-- Pilih Bank --</option>
												<?php foreach (list_data_bank() as $bank) : ?>
													<option value="<?= $bank['Bank'] ?>"><?= $bank['Bank'] ?></option>
												<?php endforeach; ?>
											</select>
										</td>
									</tr>
								</table>
							</div>


							<div class="col-lg-12">
								<hr style="border:1px solid black;">
								<button type="button" class="btn btn-md  pull-left bg-gradient-secondary" data-dismiss="modal">Batal</button>
								<button type="submit" class="btn btn-md btn-primary pull-right">Simpan</button>
							</div>





						</form>



					</div>
					<div class="overlay loading_modal" style="display:none;">
						<i class="fa fa-refresh fa-spin"></i>
					</div>


				</div>



			</div>
		</div>

	</div>

</div>

<div class="modal fade" id="modal_edit">
	<div class="modal-dialog modal-lg" id="modal_dialog_edit">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal_title_edit">Form Entry Vendor</h5>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="modal_body_edit">

				<div class="box" style="border:none;">

					<div class="box-body">

						<style>
							.atur_titik_dua {
								width: 2%;
								padding-left: 3px;
								padding-right: 3px;
								text-align: center;
							}

							td {
								padding-top: 5px;
								padding-bottom: 5px;

							}
						</style>

						<form id="form_saya_edit">

							<div class="col-lg-6">

								<table style="width: 100%;font-size:10px !important;" border="0">
									<tr>
										<td style="width:20%;">Nama</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<input type="text" class="form-control input-sm VendorNm" placeholder="Nama" name="VendorNm" id="VendorNm_edit" required>
										</td>
									</tr>
									<tr>
										<td>Alamat</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<textarea name="Alamat" id="Alamat_edit" class="form-control input-sm" placeholder="Alamat"></textarea>
										</td>
									</tr>
									<tr>
										<td>Kota/Kabupaten</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<input type="text" class="form-control input-sm" placeholder="Isi Kota/Kab" name="Kota" id="Kota_edit">
										</td>
									</tr>
									<tr>
										<td>Provinsi</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<input type="text" class="form-control input-sm" placeholder="Isi Provinsi" name="Propinsi" id="Propinsi_edit">
										</td>
									</tr>

									<tr>
										<td>Fax</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<input type="text" class="form-control input-sm" placeholder="Isi Fax" name="Fax" id="Fax_edit">
										</td>
									</tr>

									<tr>
										<td>NPWP</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<input type="text" class="form-control input-sm" placeholder="Isi NPWP" name="NPWP" id="NPWP_edit">
										</td>
									</tr>

									<tr>
										<td>Contact Person</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<input type="text" class="form-control input-sm" placeholder="Isi Contact Person" name="ContactPerson" id="ContactPerson_edit">
										</td>
									</tr>

									<tr>
										<td>Kategori</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<select name="Kategori" id="Kategori_edit" class="form-control input-sm" required>
												<option value="">-- Pilih Kategori --</option>
												<option value="EXPEDISI">EXPEDISI</option>
												<option value="MATERIAL">MATERIAL</option>
												<option value="PEMBORONG">PEMBORONG</option>
												<option value="PERALATAN">PERALATAN</option>
												<option value="SUBKON">SUBKON</option>
												<option value="TENAGA">TENAGA</option>
											</select>
										</td>
									</tr>

									<tr>
										<td></td>
										<td class="atur_titik_dua"></td>
										<td style="padding-left:3px;">
											<div class="form-check form-check-inline">
												<input class="form-check-input" type="checkbox" name="PKP" id="PKP_edit" value="1">
												<label class="form-check-label" for="PKP_edit">PKP</label>
											</div>
										</td>
									</tr>



								</table>

							</div>

							<div class="col-lg-6">

								<table style="width: 100%;font-size:10px !important;" border="0">

									<tr>
										<td>Telepon</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<div class="row">
												<div class="col-sm-4">

													<div class="form-group">
														<label for="Telepon1">Telepon 1</label>
														<input type="text" class="form-control input-sm" placeholder="Telepon 1" name="Telepon1" id="Telepon1_edit">
													</div>
												</div>

												<div class="col-sm-4">
													<div class="form-group">
														<label for="Telepon2">Telepon 2</label>
														<input type="text" class="form-control input-sm" placeholder="Telepon 2" name="Telepon2" id="Telepon2_edit">
													</div>
												</div>

												<div class="col-sm-4">
													<div class="form-group">
														<label for="Telepon3">Telepon 3</label>
														<input type="text" class="form-control input-sm" placeholder="Telepon 3" name="Telepon3" id="Telepon3_edit">
													</div>
												</div>



											</div>
										</td>
									</tr>

									<tr>
										<td>Email</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<div class="row">
												<div class="col-sm-4">

													<div class="form-group">
														<label for="Email1">Email 1</label>
														<input type="email" class="form-control input-sm" placeholder="Email 1" name="Email1" id="Email1_edit">
													</div>
												</div>

												<div class="col-sm-4">
													<div class="form-group">
														<label for="Email2">Email 2</label>
														<input type="email" class="form-control input-sm" placeholder="Email 2" name="Email2" id="Email2_edit">
													</div>
												</div>

												<div class="col-sm-4">
													<div class="form-group">
														<label for="Email3">Email 3</label>
														<input type="email" class="form-control input-sm" placeholder="Email 3" name="Email3" id="Email3_edit">
													</div>
												</div>



											</div>
										</td>
									</tr>

									<tr>
										<td>Bidang Usaha</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<input type="text" class="form-control input-sm" placeholder="Isi Bidang Usaha" name="BidangUsaha" id="BidangUsaha_edit">
										</td>
									</tr>


									<tr>
										<td colspan="3" class="bg-blue-gradient" style="font-size: 15px !important;">Data Bank Vendor</td>
									</tr>

									<tr>
										<td style="width: 20%;">No. Rekening</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<input type="text" class="form-control input-sm" placeholder="No. Rekening" name="NoRek" id="NoRek_edit">
										</td>
									</tr>

									<tr>
										<td>Atas Nama</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<input type="text" class="form-control input-sm" placeholder="Atas Nama" name="AtasNama" id="AtasNama_edit">
										</td>
									</tr>

									<tr>
										<td>Bank</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<select name="Bank" id="Bank_edit" class="form-control input-sm select2">
												<option value="">-- Pilih Bank --</option>
												<?php foreach (list_data_bank() as $bank) : ?>
													<option value="<?= $bank['Bank'] ?>"><?= $bank['Bank'] ?></option>
												<?php endforeach; ?>
											</select>
										</td>
									</tr>
								</table>
							</div>


							<div class="col-lg-12">
								<hr style="border:1px solid black;">
								<input type="hidden" name="VendorId" id="VendorId_edit">
								<button type="button" class="btn btn-md  pull-left bg-gradient-secondary" data-dismiss="modal">Batal</button>
								<button type="submit" class="btn btn-md btn-primary pull-right">Simpan</button>
							</div>





						</form>



					</div>
					<div class="overlay loading_modal_edit" style="display:none;">
						<i class="fa fa-refresh fa-spin"></i>
					</div>


				</div>



			</div>
		</div>

	</div>

</div>



<script>
	$(document).ready(function() {

		$.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
			return {
				"iStart": oSettings._iDisplayStart,
				"iEnd": oSettings.fnDisplayEnd(),
				"iLength": oSettings._iDisplayLength,
				"iTotal": oSettings.fnRecordsTotal(),
				"iFilteredTotal": oSettings.fnRecordsDisplay(),
				"iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
				"iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
			};
		};

		var table = $("#mytable").dataTable({
			"responsive": true,
			// "lengthChange": false,
			"autoWidth": false,
			scrollX: true,
			// scrollY: 200,
			scrollCollapse: true,
			scroller: true,
			initComplete: function() {
				var api = this.api();
				$('#mytable_filter input')
					.off('.DT')
					.on('keyup.DT', function(e) {
						if (e.keyCode == 13) {
							api.search(this.value).draw();
						}
					});
			},
			processing: true,
			serverSide: true,
			ajax: {
				"url": "<?= site_url('Vendor_new/dapat_list_data_vendor') ?>",
				"type": "POST",

			},
			columns: [{
					"data": "TimeEntry",
					"orderable": false,
					"searchable": false,
				},
				{
					"data": "aksi",
					"orderable": false,
					"searchable": false,
					"render": function(data, type, row) {
						return '<button type="button" class="btn btn-xs btn-warning" onClick="edit_vendor(\'' + row.VendorId + '\')" ><i class="fa fa-edit"></i> Edit</button>';
					}
				},
				{
					"data": "VendorId",
					"orderable": false,
					"searchable": false,
				},
				{
					"data": "VendorNm",
					"orderable": false,
					"searchable": false,
				},
				{
					"data": "Alamat",
					"orderable": false,
					"searchable": false,
				},
				{
					"data": "Kota",
					"orderable": false,
					"searchable": false,
				},
				{
					"data": "Propinsi",
					"orderable": false,
					"searchable": false,
				},
				{
					"data": "Kategori",
					"orderable": false,
					"searchable": false,
				},
				{
					"data": "BidangUsaha",
					"orderable": false,
					"searchable": false,
				},


			],
			order: [
				[0, 'desc']
			],
			rowCallback: function(row, data, iDisplayIndex) {
				var info = this.fnPagingInfo();
				var page = info.iPage;
				var length = info.iLength;
				var index = page * length + (iDisplayIndex + 1);
				$('td:eq(0)', row).html(index);
			}
		});


		$('.select2').select2({
			'width': '100%',

		})

		$('#form_saya').submit(function(e) {
			e.preventDefault();

			$.ajax({
				type: "post",
				url: "<?= site_url('Vendor_new/aksi_simpan_vendor') ?>",
				data: $(this).serialize(),
				dataType: "html",
				beforeSend: function() {
					$('.loading_modal').show();
				},
				success: function(data) {
					$('.loading_modal').hide();

					if (data == 'berhasil') {
						$.toast({
							text: 'data telah ditambahkan',
							position: 'bottom-right',
							stack: false,
							hideAfter: 1000,
							icon: 'success',

						})
					}

					if (data == 'gagal') {
						$.toast({
							text: 'data gagal dihapus',
							position: 'bottom-right',
							stack: false,
							hideAfter: 1000,
							icon: 'error',

						})
					}

					$('#modal_tambah').modal('hide');

					reload_vendor();

				},
				error: function() {
					$('.loading_modal').hide();
					$.toast({
						text: 'Terjadi Kesalahan',
						position: 'bottom-right',
						stack: false,
						hideAfter: 1000,
						icon: 'error',

					})
				}
			});
		})

		$('#form_saya_edit').submit(function(e) {
			e.preventDefault();

			$.ajax({
				type: "post",
				url: "<?= site_url('Vendor_new/aksi_edit_vendor') ?>",
				data: $(this).serialize(),
				dataType: "html",
				beforeSend: function() {
					$('.loading_modal_edit').show();
				},
				success: function(data) {
					$('.loading_modal_edit').hide();

					if (data == 'berhasil') {
						$.toast({
							text: 'data telah diubah',
							position: 'bottom-right',
							stack: false,
							hideAfter: 1000,
							icon: 'success',

						})
					}

					if (data == 'gagal') {
						$.toast({
							text: 'data gagal diubah',
							position: 'bottom-right',
							stack: false,
							hideAfter: 1000,
							icon: 'error',

						})
					}

					$('#modal_edit').modal('hide');
					reload_vendor();

				},
				error: function() {
					$('.loading_modal_edit').hide();
					$.toast({
						text: 'Terjadi Kesalahan',
						position: 'bottom-right',
						stack: false,
						hideAfter: 1000,
						icon: 'error',

					})
				}
			});
		})




	})

	function tambah_vendor() {
		bersihkan_form();
		$('#modal_tambah').modal('show');
	}

	function edit_vendor(VendorId) {

		$.ajax({
			type: "post",
			url: "<?= site_url('Vendor_new/dapat_data_vendor') ?>",
			data: {
				'VendorId': VendorId
			},
			dataType: "json",
			beforeSend: function() {
				$('.loadingsaya').show();
			},
			success: function(data) {
				$('.loadingsaya').hide();


				if (data == 'gagal') {
					$('.loadingsaya').hide();
					$.toast({
						text: 'Terjadi Kesalahan',
						position: 'bottom-right',
						stack: false,
						hideAfter: 1000,
						icon: 'error',

					})
					return false;
				}

				bersihkan_form_edit();
				$('#modal_edit').modal('show');

				$('#VendorNm_edit').val(data.VendorNm);
				$('#Alamat_edit').val(data.Alamat);
				$('#Kota_edit').val(data.Kota);
				$('#Propinsi_edit').val(data.Propinsi);
				$('#Fax_edit').val(data.Fax);
				$('#NPWP_edit').val(data.NPWP);
				$('#ContactPerson_edit').val(data.ContactPerson);
				$('#Kategori_edit').val(data.Kategori).change();

				$('#PKP_edit').prop('checked', (data.PKP == 1) ? true : false);

				$('#Telepon1_edit').val(data.Telepon1);
				$('#Telepon2_edit').val(data.Telepon2);
				$('#Telepon3_edit').val(data.Telepon3);
				$('#Email1_edit').val(data.Email1);
				$('#Email2_edit').val(data.Email2);
				$('#Email3_edit').val(data.Email3);
				$('#BidangUsaha_edit').val(data.BidangUsaha);

				$('#NoRek_edit').val(data.NoRek);
				$('#AtasNama_edit').val(data.AtasNama);
				$('#Bank_edit').val(data.Bank).change();

				$('#VendorId_edit').val(data.VendorId);


			},
			error: function() {
				$('.loadingsaya').hide();
				$.toast({
					text: 'Terjadi Kesalahan',
					position: 'bottom-right',
					stack: false,
					hideAfter: 1000,
					icon: 'error',

				})
			}
		});

	}

	function bersihkan_form() {
		//tambah
		$('#VendorNm_tambah').val('');
		$('#Alamat_tambah').val('');
		$('#Kota_tambah').val('');
		$('#Propinsi_tambah').val('');
		$('#Fax_tambah').val('');
		$('#NPWP_tambah').val('');
		$('#ContactPerson_tambah').val('');
		$('#Kategori_tambah').val('').change();
		$('#PKP_tambah').prop('checked', false);

		$('#Telepon1_tambah').val('');
		$('#Telepon2_tambah').val('');
		$('#Telepon3_tambah').val('');
		$('#Email1_tambah').val('');
		$('#Email2_tambah').val('');
		$('#Email3_tambah').val('');
		$('#BidangUsaha_tambah').val('');

		$('#NoRek_tambah').val('');
		$('#AtasNama_tambah').val('');
		$('#Bank_tambah').val('').change();

	}

	function bersihkan_form_edit() {
		//edit
		$('#VendorNm_edit').val('');
		$('#Alamat_edit').val('');
		$('#Kota_edit').val('');
		$('#Propinsi_edit').val('');
		$('#Fax_edit').val('');
		$('#NPWP_edit').val('');
		$('#ContactPerson_edit').val('');
		$('#Kategori_edit').val('').change();
		$('#PKP_edit').prop('checked', false);

		$('#Telepon1_edit').val('');
		$('#Telepon2_edit').val('');
		$('#Telepon3_edit').val('');
		$('#Email1_edit').val('');
		$('#Email2_edit').val('');
		$('#Email3_edit').val('');
		$('#BidangUsaha_edit').val('');

		$('#NoRek_edit').val('');
		$('#AtasNama_edit').val('');
		$('#Bank_edit').val('').change();

		$('#VendorId_edit').val('');
	}


	function reload_vendor() {
		$('#mytable').DataTable().ajax.reload();
	}
</script>