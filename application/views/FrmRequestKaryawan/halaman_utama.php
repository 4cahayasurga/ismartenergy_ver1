<style>
	.font_12 {
		font-size: 12px !important;
	}

	.jarak {
		margin-top: 10px;
	}

	#tbl_tambah tr {
		padding-top: 10px !important;
	}

	.swal2-popup {
		font-size: 1.4rem !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	#example {
		font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
	}

	#example thead tr {
		border-radius: 20px;
	}

	#example thead tr:first-child th {
		text-align: center;
		justify-items: center;
		background-color: #e5e9f0;
		text-wrap: wrap;
		border: 1px solid #adb5bd;
	}

	#example thead tr:last-child th {
		justify-items: center;
		text-align: center;
		background-color: #eceff4;
		text-wrap: wrap;
		border: 1px solid #d8dee9;
	}

	#example tbody tr td {
		border: 1px solid #eceff4;
	}

	.loading {
		display: flex;
		justify-content: center;
	}

	.modal-dialog {
		background-color: yellow;
	}

	.modal-content {
		margin-top: 50px;
		width: 100%;
		height: 90vh;
		background-color: #fff;
	}

	.modal-body {
		height: 75vh;
		overflow: hidden;
	}

	@keyframes show-modal {
		0% {
			opacity: 0;
		}

		50% {
			opacity: 0.5;
		}

		100% {
			opacity: 1;
		}
	}
</style>
<div class="content-wrapper">
	<div class="flashdata" data-flash="<?= $this->session->flashdata('message') ?>"></div>
	<section class="content-header">
		<h1>
			<?= ($judul) ? $judul : '' ?>
		</h1>
		<!-- <p style="font-style: italic; margin-top: 10px;">silahkan pilih pelamar dibawah ini..</p> -->
		<p style="font-style: italic; margin-top: 10px;"></p>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('Welcome') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Form Pelamar</li>
		</ol>

		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
			Tambah Permintaan Karyawan
		</button>

	</section>

	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-body">
						<div class="table-responsive table-example">
							<table class="table font_12 table-bordered table-striped table-hover table-hover" data-id="example" id="example">
								<thead>
									<tr>
										<th>No</th>
										<th>Posisi yang Dibutuhkan</th>
										<th>Jumlah Karyawan</th>
										<th>Penempatan</th>
										<th>Waktu Siap Kerja</th>
										<th>Durasi Waktu Bekerja / Kontrak</th>
										<th>Kualifikasi Khusus</th>
										<th>Status</th>

									</tr>
								</thead>
								<tbody>
									<?php
									$NoUrut = 1;
									foreach ($allData as $data) : ?>
										<tr>
											<td class="text-center"><?= $NoUrut++ ?></td>
											<td><?= $data['Posisi'] ?></td>
											<td class="text-center"><?= $data['Jumlah'] ?></td>
											<td><?= $data['Penempatan'] ?></td>
											<td class="text-center"><?= date('d-M-Y', strtotime($data['TglSiapKerja'])) ?></td>
											<td><?= $data['DurasiKontrak'] ?></td>
											<td><?= $data['Kualifikasi'] ?></td>
											<td class="text-center ">
												<?php if ($data['Status'] == 'request') : ?>
													<span style="background: #fff85b; padding: 5px; border-radius: 2px;">Pending</span>
												<?php elseif ($data['Status'] == 'open') : ?>
													<span style="background: #38d9a9; padding: 5px; border-radius: 2px;">Open</span>
												<?php elseif ($data['Status'] == 'close') : ?>
													<span style="background: #d8dee9; padding: 5px; border-radius: 2px;">Close</span>
												<?php endif; ?>
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
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Form Tambah Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="MyFormTambah" method="POST" action="<?= site_url('FrmReqKaryawan/create_request') ?>">
				<div class="modal-body">
					<div class="table-responsive font_12">

						<table border="0" width="100%" style="border-collapse: collapse;">
							<tr>
								<td style="width:30%;">Posisi Yang Di Butuhkan</td>
								<td style="width:2%;padding-left: 5px;padding-right: 5px;">:</td>
								<td style="width:60%;">
									<input type="text" name="Posisi" id="Posisi" class="form-control input-sm font_12" placeholder="Posisi" required>
								</td>
								<td></td>
							</tr>
						</table>

						<table border="0" width="100%" style="border-collapse: collapse;" class="jarak">
							<tr>
								<td style="width:30%;">Jumlah Permintaan Karyawan</td>
								<td style="width:2%;padding-left: 5px;padding-right: 5px;">:</td>
								<td style="width:60%;">
									<input type="text" name="JumlahPermintaan" id="JumlahPermintaan" class="form-control input-sm font_12" placeholder="Jumlah permintaan" required>
								</td>
								<td></td>
							</tr>
						</table>

						<table border="0" width="100%" style="border-collapse: collapse;" class="jarak">
							<tr>
								<td style="width:30%;">Penempatan</td>
								<td style="width:2%;padding-left: 5px;padding-right: 5px;">:</td>
								<td style="width:60%;">
									<input type="text" name="Penempatan" id="Penempatan" class="form-control input-sm font_12" placeholder="Lokasi penempatan" required>
								</td>
								<td></td>
							</tr>
						</table>

						<table border="0" width="100%" style="border-collapse: collapse;" class="jarak">
							<tr>
								<td style="width:30%;">Tanggal Bergabung Yang Dibutuhkan </td>
								<td style="width:2%;padding-left: 5px;padding-right: 5px;">:</td>
								<td style="width:60%;">
									<input type="date" name="WaktuBergabung" id="WaktuBergabung" class="form-control input-sm font_12">
								</td>
								<td></td>
							</tr>
						</table>

						<table border="0" width="100%" style="border-collapse: collapse;" class="jarak">
							<tr>
								<td style="width:30%;" class="font_12">Durasi Waktu Kontrak</td>
								<td style="width:2%;padding-left: 5px;padding-right: 5px;">:</td>
								<td style="width:60%;">

									<input type="text" name="DurasiKontrak" id="DurasiKontrak" class="form-control">

								</td>
								<td></td>
							</tr>
						</table>

						<!-- <table border="0" width="100%" style="border-collapse: collapse;" class="jarak">
							<tr>
								<td style="width:30%;">Jurusan</td>
								<td style="width:2%;padding-left: 5px;padding-right: 5px;">:</td>
								<td style="width:60%;">

									<select name="Jurusan" id="Jurusan" class="form-control input-sm ukuran_huruf js-example-basic-single" required>
										<option value="">-- Pilih Jurusan --</option>
										<?php foreach ($ListJurusan as $my_jurusan) : ?>
											<option value="<?= $my_jurusan->NamaJurusan ?>"><?= $my_jurusan->NamaJurusan ?></option>
										<?php endforeach; ?>
									</select>

								</td>
								<td></td>
							</tr>
						</table> -->


						<!-- <table border="0" width="100%" style="border-collapse: collapse;" class="jarak">
							<tr>
								<td style="width:30%;">Media</td>
								<td style="width:2%;padding-left: 5px;padding-right: 5px;">:</td>
								<td style="width:60%;"> 
									<input type="text" name="Media" id="Media" class="form-control input-sm font_12" placeholder="Masukkan Media">
								</td>
								<td></td>
							</tr>
						</table> -->


						<table border="0" width="100%" style="border-collapse: collapse;" class="jarak">
							<tr>
								<td style="width:30%;">Kualifikasi Khusus</td>
								<td style="width:2%;padding-left: 5px;padding-right: 5px;">:</td>
								<td style="width:60%;">
									<textarea class="form-control input-sm font_12" placeholder="Kualifikasi Khusus" name="Kualifikasi" id="Kualifikasi"></textarea>
								</td>
								<td></td>
							</tr>
						</table>

					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	$(function() {
		var flashdata = $('.flashdata').data('flash')
		if (flashdata != '') {
			Swal.fire({
				title: 'Berhasil',
				text: flashdata,
				icon: 'success',
			})
		}

		$('#example').dataTable()

		$('.btn-vote').click(function(e) {
			e.preventDefault()
			Swal.fire({
				title: 'Apa anda yakin?',
				text: 'Anda tidak dapat membatalkan pilihan setelah memilih pelamar',
				showConfirmButton: true,
				showCancelButton: true,
				confirmButtonText: 'Ya!',
				cancelButtonText: 'Batal',
			}).then((result) => {
				if (result.value == true) {
					$(this).closest('form').submit()
				}
			})
		})

		$('.btn-lihatCV').click(function(e) {
			e.preventDefault()
			var url = $(this).data('url');
			$('#myModal #imgModalCv').attr('src', url);
			//    tampilkan modal
			$('#myModal').css('display', 'block');
		})

		$('.close-modal').click(function() {
			$('#myModal').css('display', 'none')
		})

	})
</script>