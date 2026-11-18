<?php
error_reporting(0);
$foto = '';
if (isset($karyawan['Foto'])) {

	if ($foto !== NULL || $foto !== '') {
		$foto = 'assets/foto_karyawan/' . $karyawan['Foto'];
	} else {
		$foto = 'assets/dist/img/default_foto.jpg';
	}
} else {
	$foto = 'assets/dist/img/default_foto.jpg';
}

?>

<style type="text/css">
	#table_saya {
		font-family: Times, "Times New Roman", Georgia, serif;
		font-size: 12px !important;
	}

	#table_saya th {
		padding-top: 10px !important;
		padding-bottom: 10px !important;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('Welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>


	<section class="content-header" id="section_saya">
		<?= ($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
		<div class="row">

			<div class="col-md-3">

				<div class="box box-primary">
					<div class="box-body box-profile">
						<a href="javascript:void(0)" id="ubah_foto" data-NIK="<?= $karyawan['NIK'] ?>"><img class="profile-user-img img-responsive img-circle" src="<?php echo base_url($foto) ?>" alt="User profile picture"></a>

						<h3 class="profile-username text-center"><?= strtoupper($karyawan['Nama']) ?></h3>
						<p class="text-muted text-center"><?= strtoupper($karyawan['Jabatan']) ?></p>

						<table border="0" width="100%" id="table_saya">
							<tr>
								<th>NIK</th>
								<td class="text-right text-blue"><?= $karyawan['NIK'] ?></td>
							</tr>
							<tr>
								<th>Tempat, Tanggal Lahir</th>
								<td class="text-right text-blue"><?= $karyawan['TmpLahir'] . ', ' . tgl_indo($karyawan['TglLahir']) ?></td>
							</tr>
							<tr>
								<th>Warga Negara</th>
								<td class="text-right text-blue"><?= ($karyawan['WN'] == NULL) ? 'INDONESIA' : $karyawan['NIK'] ?></td>
							</tr>
							<tr>
								<th>Status Pernikahan</th>
								<td class="text-right text-blue"><?= $karyawan['StsNikah'] ?></td>
							</tr>
							<tr>
								<th>Agama</th>
								<td class="text-right text-blue"><?= $karyawan['Agama'] ?></td>
							</tr>
							<tr>
								<th>Alamat KTP</th>
								<td class="text-right text-blue"><?= $karyawan['Alamat'] ?></td>
							</tr>
							<tr>
								<th>Provinsi</th>
								<td class="text-right text-blue"><?= $karyawan['Provinsi'] ?></td>
							</tr>
							<tr>
								<th>Kota</th>
								<td class="text-right text-blue"><?= $karyawan['Kota'] ?></td>
							</tr>
							<tr>
								<th>Email</th>
								<td class="text-right text-blue"><?= $karyawan['Email'] ?></td>
							</tr>
							<tr>
								<th>No. Telepon</th>
								<td class="text-right text-blue"><?= $karyawan['NoTelp'] ?></td>
							</tr>
						</table>

					</div>

				</div>


			</div>

			<div class="col-md-9">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#identitas" data-toggle="tab" aria-expanded="true">Identitas Personal</a></li>
						<li class=""><a href="#keluarga" data-toggle="tab" aria-expanded="false">Data Keluarga</a></li>
						<li><a href="#kontakdarurat" data-toggle="tab">Emergency Contact</a></li>
						<li><a href="#pendidikan" data-toggle="tab">Data Pendidikan</a></li>
						<li><a href="#keterampilan" data-toggle="tab">Data Keterampilan</a></li>
						<li><a href="#pekerjaan" data-toggle="tab">Data Riwayat Pekerjaan</a></li>
						<li><a href="#kehadiran" data-toggle="tab">Data Kehadiran</a></li>
						<li><a href="#slipgaji" data-toggle="tab">Slip Gaji</a></li>
						<li><a href="#permintaancuti" data-toggle="tab">Data Request Cuti</a></li>
					</ul>

					<div class="tab-content">
						<div class="tab-pane active" id="identitas">
							<div class="box-body">
								<div class="col-lg-9">

									<table style="width: 100%;font-size:10px !important;" border="0">
										<tr>
											<td style="width:20%;">KTP</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="text" class="form-control input-sm KTP" placeholder="KTP" name="KTP" id="KTP" required>
											</td>
										</tr>
										<tr>
											<td style="width:20%;">Berlaku Dari</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="date" class="form-control input-sm col-sm-2 TglKtp1" name="TglKtp1" id="TglKtp1" style="width:50%;">
												<input type="date" class="form-control input-sm TglKtp2" name="TglKtp2" id="TglKtp2" style="width:50%;">
											</td>
										</tr>
										<tr>
											<td style="width:20%;">Diterbitkan Oleh</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="text" class="form-control input-sm Penerbit" placeholder="Penerbit" name="Penerbit" id="Penerbit" required>
											</td>
										</tr>

										<tr>
											<td style="width:20%;">Pasport</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="text" class="form-control input-sm Pasport" placeholder="Pasport" name="Pasport" id="Pasport" required>
											</td>
										</tr>
										<tr>
											<td style="width:20%;">Berlaku Dari</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="date" class="form-control input-sm col-sm-2 TglKtp1" name="TglKtp1" id="TglKtp1" style="width:50%;">
												<input type="date" class="form-control input-sm TglKtp2" name="TglKtp2" id="TglKtp2" style="width:50%;">
											</td>
										</tr>
										<tr>
											<td style="width:20%;">Diterbitkan Oleh</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="text" class="form-control input-sm Penerbit" placeholder="Penerbit" name="Penerbit" id="Penerbit" required>
											</td>
										</tr>

										<tr>
											<td style="width:20%;">No NPWP</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="text" class="form-control input-sm NPWP" placeholder="NPWP" name="NPWP" id="NPWP" required>
											</td>
										</tr>
										<tr>
											<td style="width:20%;">Berlaku Dari</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="date" class="form-control input-sm col-sm-2 TglKtp1" name="TglKtp1" id="TglKtp1" style="width:50%;">
												<input type="date" class="form-control input-sm TglKtp2" name="TglKtp2" id="TglKtp2" style="width:50%;">
											</td>
										</tr>
										<tr>
											<td style="width:20%;">Diterbitkan Oleh</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="text" class="form-control input-sm Penerbit" placeholder="Penerbit" name="Penerbit" id="Penerbit" required>
											</td>
										</tr>

										<tr>
											<td style="width:20%;">No KK</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="text" class="form-control input-sm NoKK" placeholder="NoKK" name="NoKK" id="NoKK" required>
											</td>
										</tr>
										<tr>
											<td style="width:20%;">Berlaku Dari</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="date" class="form-control input-sm col-sm-2 TglKtp1" name="TglKtp1" id="TglKtp1" style="width:50%;">
												<input type="date" class="form-control input-sm TglKtp2" name="TglKtp2" id="TglKtp2" style="width:50%;">
											</td>
										</tr>
										<tr>
											<td style="width:20%;">Diterbitkan Oleh</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="text" class="form-control input-sm Penerbit" placeholder="Penerbit" name="Penerbit" id="Penerbit" required>
											</td>
										</tr>

										<tr>
											<td style="width:20%;">SIM A</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="text" class="form-control input-sm SIMA" placeholder="SIMA" name="SIMA" id="SIMA" required>
											</td>
										</tr>
										<tr>
											<td style="width:20%;">Berlaku Dari</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="date" class="form-control input-sm col-sm-2 TglKtp1" name="TglKtp1" id="TglKtp1" style="width:50%;">
												<input type="date" class="form-control input-sm TglKtp2" name="TglKtp2" id="TglKtp2" style="width:50%;">
											</td>
										</tr>
										<tr>
											<td style="width:20%;">Diterbitkan Oleh</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="text" class="form-control input-sm Penerbit" placeholder="Penerbit" name="Penerbit" id="Penerbit" required>
											</td>
										</tr>

										<tr>
											<td style="width:20%;">SIM B</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="text" class="form-control input-sm SIMB" placeholder="SIMB" name="SIMB" id="SIMB" required>
											</td>
										</tr>
										<tr>
											<td style="width:20%;">Berlaku Dari</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="date" class="form-control input-sm col-sm-2 TglKtp1" name="TglKtp1" id="TglKtp1" style="width:50%;">
												<input type="date" class="form-control input-sm TglKtp2" name="TglKtp2" id="TglKtp2" style="width:50%;">
											</td>
										</tr>
										<tr>
											<td style="width:20%;">Diterbitkan Oleh</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="text" class="form-control input-sm Penerbit" placeholder="Penerbit" name="Penerbit" id="Penerbit" required>
											</td>
										</tr>

										<tr>
											<td style="width:20%;">SIM C</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="text" class="form-control input-sm SIMC" placeholder="SIMC" name="SIMC" id="SIMC" required>
											</td>
										</tr>
										<tr>
											<td style="width:20%;">Berlaku Dari</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="date" class="form-control input-sm col-sm-2 TglKtp1" name="TglKtp1" id="TglKtp1" style="width:50%;">
												<input type="date" class="form-control input-sm TglKtp2" name="TglKtp2" id="TglKtp2" style="width:50%;">
											</td>
										</tr>
										<tr>
											<td style="width:20%;">Diterbitkan Oleh</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="text" class="form-control input-sm Penerbit" placeholder="Penerbit" name="Penerbit" id="Penerbit" required>
											</td>
										</tr>

										<tr>
											<td style="width:20%;">Bank</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="text" class="form-control input-sm Bank" placeholder="Bank" name="Bank" id="Bank" required>
											</td>
										</tr>
										<tr>
											<td style="width:20%;">Atas Nama</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="text" class="form-control input-sm AtasNama" placeholder="AtasNama" name="AtasNama" id="AtasNama" required>
											</td>
										</tr>
										<tr>
											<td style="width:20%;">No Rekening</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="text" class="form-control input-sm NoRek" placeholder="NoRek" name="NoRek" id="NoRek" required>
											</td>
										</tr>



									</table>

								</div>

							</div>

						</div>
						<div class="tab-pane" id="keluarga">

						</div>

						<div class="tab-pane" id="kontakdarurat">

						</div>

						<div class="tab-pane" id="pendidikan">

						</div>
						<div class="tab-pane" id="keterampilan">

						</div>
						<div class="tab-pane" id="pekerjaan">

						</div>
						<div class="tab-pane" id="kehadiran">
							<div class="box-body">

								<div class="col-lg-9">
									<table style="width: 100%;font-size:10px !important;" border="0">
										<tr>
											<td style="width:20%;">Periode</td>
											<td class="atur_titik_dua">:</td>
											<td>
												<input type="date" class="form-control input-sm PeriodeAbsen" placeholder="PeriodeAbsen" name="PeriodeAbsen" id="PeriodeAbsen" style="width:60%;">
											</td>
										</tr>
									</table>
								</div>

								<div class="col-lg-12">
									<div class="box-body">
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover table-condesed" id="mytable">
												<thead>
													<tr>
														<th>Tgl Absensi</th>
														<th>Jam Masuk</th>
														<th>Jam Pulang</th>
														<th>Status</th>
														<th>Keterangan</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="slipgaji">

						</div>
						<div class="tab-pane" id="permintaancuti">
							<div class="box-body">
								<table style="width: 100%;font-size:10px !important;" border="0">
								<tr>
									<td>
										<button type="submit" class="btn btn-instagram">Ajukan Cuti</button>
									</td>
								</tr>
									<tr>
										<td style="width:20%;">SISA CUTI</td>
										<td class="atur_titik_dua">:</td>
										<td>
											<input type="text" class="form-control input-sm sisacuti" placeholder="Sisa Cuti Anda" name="sisacuti" id="sisacuti" style="width:60%;">
										</td>
									</tr>
								</table>
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover table-condesed" id="mytable">
										<col>
										<colgroup span="3"></colgroup>
										<colgroup span="3"></colgroup>
										<tr>
											<th rowspan="2">
												<center>#</center>
											</th>
											<th rowspan="2">
												<center>Dari Tgl</center>
											</th>
											<th rowspan="2">
												<center>Sampai Tgl</center>
											</th>
											<th rowspan="2">
												<center>Tipe Request</center>
											</th>
											<th rowspan="2">
												<center>Karyawan Pengganti</center>
											</th>
											<th colspan="3" scope="colgroup">
												<center>ATASAN</center>
											</th>
											<th colspan="7" scope="colgroup">
												<center>HRD</center>
											</th>
										</tr>
										<tr>

											<th scope="col">Status</th>
											<th scope="col">Approve/Reject Oleh</th>
											<th scope="col">Waktu Approve/Reject</th>
											<th scope="col">Status</th>
											<th scope="col">Approve/Reject Oleh</th>
											<th scope="col">Waktu Approve/Reject Oleh</th>
										</tr>
										<tbody></tbody>
									</table>
								</div>
							</div>

						</div>



					</div>

				</div>

			</div>




		</div>


	</section>
</div>

<div class="modal fade" id="modal_uraian">
	<div class="modal-dialog" id="modal_dialog_uraian">
		<div class="modal-content">

			<div class="modal-header">
				<h4 class="modal-title" id="modal_title_uraian"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="modal_body_uraian">



			</div>
			<div class="box" style="border:none !important;">
				<div class="box-header">
				</div>
				<div class="box-body">
				</div>
				<div class="overlay" style="display: none;">
					<i class="fa fa-refresh fa-spin"></i>
				</div>

			</div>



		</div>

	</div>

</div>

<script>
	$(function() {
		$('#ubah_foto').click(function() {

			var NIK = $(this).attr('data-NIK');

			$('#modal_uraian').modal('show');
			$.ajax({
					url: '<?= site_url('Kepegawaian/ubah_foto') ?>',
					type: 'POST',
					dataType: 'html',
					data: {
						'NIK': NIK
					},
					beforeSend: function() {
						$('.overlay').show();
					}
				})
				.done(function(data) {
					$('.overlay').hide();
					$('#modal_body_uraian').html(data);
					// console.log("success");
				})
				.fail(function() {
					// console.log("error");
				})
				.always(function() {
					// console.log("complete");
				});



		})
	})

	$(document).idle({
		onIdle: function() {
			window.location = "/logout.php";
		},
		idle: 10000
	});
</script>




<!-- </Section> -->
