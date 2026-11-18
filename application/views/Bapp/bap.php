<style>
	.mykonten {
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
		font-size: x-small !important;
		font-size: 10px;
	}

	.ukuran_select2 {
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
		/*font-family: Arial, Helvetica, sans-serif;*/
	}

	.atur_ukuran {
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}

	input[type="text"] {
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}

	input[type="date"] {
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}

	textarea {
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}

	label {
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}
</style>

<div class="content-wrapper">

	<section class="content-header">

		<table width="100%" border="0">
			<tr>
				<td style="font-size: 14px !important;" class="text-bold atur_ukuran"><?= (isset($title)) ? $title : '' ?></td>
				<td style="font-size: 12px !important;" class="text-bold text-right atur_ukuran"><?= $dipa->JobNo . ' - ' . $dipa->JobNm . '<br />' . $dipa->Lokasi ?></td>
			</tr>
			<tr>
				<td>
					<a href="<?= site_url('Bapp/Index/' . $dipa->JobNo) ?>" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
					<!-- <a href="<?= site_url('job/tatakelola/' . $dipa->JobNo) ?>" data-toggle="modal" class="btn btn-primary btn-sm"> NEXT TO TATA KELOLA &nbsp <i class="fa fa-arrow-right"></i></a> -->

				</td>
				<td style="text-align: right;padding-top: 10px !important;">
					<a href="<?= site_url('job/sub_job/' . $pdpj->JobNo) ?>" class="btn btn-sm bg-gray hidden"><i class="fa fa-arrow-left"></i> Back</a>
				</td>
			</tr>
		</table>

	</section>



	<section class="content">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>


		<div class="box box-primary">
			<div class="box-header with-border">
				<!-- <h1 class="box-title" style="font-size: larger;"><strong>Berita Acara Pembayaran Termin</strong></h1>
				<p></p> -->
				<div class="container-fluid">
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-body">
									<!-- <a href="<?= site_url('job/sub_job') ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a> -->
									<!-- <a data-target="#TambahDPRencanaTermin" data-toggle="modal" class="btn btn-flickr"><i class="fa fa-plus"></i> Input Uang Muka Rencana Termin</a> -->
									<!-- <a data-target="#TambahRencanaTermin" data-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Data Rencana Termin</a> -->
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="box-body">
					<form action="<?= site_url('Bapp/Simpan') ?>" method="post">
						<div class="form-group col-md-6">
							<table class=" table" style="width: 100%;">
								<tr>
									<th>Tgl BAPT *</th>
									<td style="width: 10px;">:</td>
									<td><input type="date" name="TglBAPT" id="TglBAPT" class="form-control" require></td>
									<input type="text" name="JobNo" id="JobNo" class="form-control hidden" value="<?= $dipa->JobNo; ?>">
								</tr>
								<tr>
									<th>No BAPT *</th>
									<td style="width: 10px;">:</td>
									<td colspan="3"><input type="text" name="NoBAPT" id="NoBAPT" class="form-control" require></td>
								</tr>
								<tr>
									<th>Pembayaran Termin Ke *</th>
									<td>:</td>
									<td><input type="text" name="TerminKe" id="TerminKe" class="form-control" require><span style="font-size: x-small;">*Input Angka Saja</span></td>
								</tr>
								<tr>
									<th>Prestasi Pekerjaan s/d Saat Ini (%) </th>
									<td>:</td>
									<td><input type="text" name="PersenPekerjaan" id="PersenPekerjaan" class="form-control" onkeyup="bapt()"><span style="font-size: x-small;"><strong>*input sampai 3 digit di belakang koma</strong></span></td>
									<td><input type="text" name="NilaiKontrak" id="NilaiKontrak" class="form-control" value="<?= number_format($KontrakBruto->Bruto); ?>" readonly></td>
									<td><input type="text" name="HasilSi" id="HasilSi" class="form-control" readonly></td>
								</tr>
								<tr>
									<th>Prestasi Pekerjaan s/d Yang Lalu (%) </th>
									<td>:</td>
									<td><input type="text" name="PersenPekerjaanLalu" id="PersenPekerjaanLalu" class="form-control" placeholder="Dalam Persen (%)" onkeyup="bapt()"><span style="font-size: x-small;"><strong>*input sampai 3 digit di belakang koma</strong></span></td>
									<td><input type="text" name="NilaiKontrakB" id="NilaiKontrakB" class="form-control" value="<?= number_format($KontrakBruto->Bruto); ?>" readonly></td>
									<td><input type="text" name="Jumlahll" id="Jumlahll" class="form-control" placeholder="Jumlah" readonly></td>
								</tr>
								<hr>
								<tr>
									<th colspan="4">Hasil</th>
									<td><input type="text" name="HasilBap" id="HasilBap" class="form-control" placeholder="HasilBAP" readonly></td>
								</tr>
								<tr>
									<td colspan="5" style="background-color: gray;"></td>
								</tr>
								<tr>
									<th>Retensi </th>
									<td>:</td>
									<td><input type="text" name="PersenRetensi" id="PersenRetensi" class="form-control" placeholder="Dalam Persen (%)" onkeyup="bapt()"><span style="font-size: x-small;"><strong>*input sampai 3 digit di belakang koma</strong></span></td>
									<td><input type="text" name="HasilBAP" id="HasilBAP" class="form-control" placeholder="Hasil BAP" readonly></td>
									<td><input type="text" name="JmlRetensi" id="JmlRetensi" class="form-control" placeholder="Jumlah" readonly></td>
								</tr>
								<tr>
									<th>Angsuran UM </th>
									<td>:</td>
									<td><input type="text" name="PersenUM" id="PersenUM" class="form-control" placeholder="Dalam Persen (%)" onkeyup="bapt()"><span style="font-size: x-small;"><strong>*input sampai 3 digit di belakang koma</strong></span></td>
									<td><input type="text" name="HasilBAPUM" id="HasilBAPUM" class="form-control" placeholder="Hasil BAP" readonly></td>
									<td><input type="text" name="JmlPotum" id="JmlPotum" class="form-control" placeholder="Jumlah Pemotong" readonly></td>
								</tr>
								<tr>
									<th colspan="4">Hasil</th>
									<td><input type="text" name="JumlahPemotong" id="JumlahPemotong" class="form-control" placeholder="Jumlah Pemotong" readonly></td>
								</tr>
								<tr>
									<td colspan="5" style="background-color: gray;"></td>
								</tr>
								<tr>
									<th>Jumlah yang Di Bayarkan </th>
									<td>:</td>
									<td><strong><input type="text" name="HasilBAPF" id="HasilBAPF" class="form-control" placeholder="Hasil BAP" readonly></strong></td>
									<td><strong><input type="text" name="JmlPotUMF" id="JmlPotUMF" class="form-control" placeholder="Jumlah Pemotong" readonly></strong></td>
									<td><strong><input type="text" name="Total" id="Total" class="form-control" placeholder="Total" readonly></strong></td>
								</tr>
							</table>
						</div>


						<div class="form-group col-md-6">
							<table class=" table" style="width: 100%;">
								<tr>
									<th colspan="3">Perhitungan Pembayaran</th>
								</tr>
								<tr>
									<th>Nilai Pekerjaan Fisik S/d BAP Ini </th>
									<td>:</td>
									<td><input type="text" name="NilaiPekSi" id="NilaiPekSi" class="form-control" style="text-align: right;" readonly></td>
								</tr>
								<tr>
									<th>Nilai Pekerjaan Fisik s/d BAP yang Lalu </th>
									<td>:</td>
									<td><input type="text" name="NilaiPekLL" id="NilaiPekLL" class="form-control" placeholder="Dalam Persen (%)" style="text-align: right;" readonly></td>
								</tr>
								<tr>
									<th>Jumlah Pembayaran Fisik BAP Ini </th>
									<td>:</td>
									<td><strong><input type="text" name="JmlPemayaranBAP" id="JmlPemayaranBAP" class="form-control" placeholder="Dalam Persen (%)" style="text-align: right;" readonly></strong></td>
								</tr>
								<tr>
									<td colspan="5" style="background-color: gray;"></td>
								</tr>
								<tr>
									<th colspan="3">Potongan - Potongan</th>

								</tr>

								<tr>
									<th>Jaminan Pekerjaan (Retensi 5%) </th>
									<td>:</td>
									<td><input type="text" name="HPersenRetensi" id="HPersenRetensi" class="form-control" placeholder="Dalam Persen (%)" style="text-align: right;" readonly></td>
								</tr>
								<tr>
									<th>Pengembalian UM (...) </th>
									<td>:</td>
									<td><input type="text" name="HPersenUM" id="HPersenUM" class="form-control" placeholder="Dalam Persen (%)" style="text-align: right;" readonly></td>
								</tr>
								<tr>
									<th>Jumlah Potongan</th>
									<td>:</td>
									<td><strong><input type="text" name="HJumlahPemotong" id="HJumlahPemotong" class="form-control" style="text-align: right;" readonly></strong></td>
								</tr>
								<tr>
									<td colspan="5" style="background-color: gray;"></td>
								</tr>
								<tr>
									<th>Jumlah Pembayaran BA Ini </th>
									<td>:</td>
									<td><strong><input type="text" name="HHasilBAP" id="HHasilBAP" class="form-control" placeholder="Hasil BAP" style="text-align: right;" readonly></strong></td>
								</tr>
								<tr>
									<th style="background-color: yellow;"><label for="" style="font-size:x-large;"><input type="checkbox" name="cbPPH[]" id="cbPPH" onkeyup="bapt()" value="1">&nbsp;&nbsp; Hitung PPn</label></th>
								</tr>
								<tr>
									<th>PPN 11%</th>
									<td>:</td>
									<td><strong><input type="text" name="PPN" id="PPN" class="form-control" placeholder="PPN 11%" style="text-align: right;" readonly></strong></td>
								</tr>
								<tr>
									<th>Jumlah Pembayaran BA ini (DPP)</th>
									<td>:</td>
									<td><strong><input type="text" name="bapdpp" id="bapdpp" class="form-control" placeholder="DPP" style="text-align: right;" readonly></strong></td>
								</tr>
								<tr>
									<th>PPH 2.65%</th>
									<td>:</td>
									<td><strong><input type="text" name="PPH" id="PPH" class="form-control" placeholder="PPH" style="text-align: right;" readonly></strong></td>
								</tr>
								<tr>
									<th>Jumlah Pembayaran BA ini (NETTO)</th>
									<td>:</td>
									<td><strong><input type="text" name="Netto" id="Netto" class="form-control" placeholder="Netto" style="text-align: right;" readonly></strong></td>
								</tr>
							</table>

							<div class="modal-footer">
								<!-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button> -->
								<button type="submit" class="btn btn-primary">SIMPAN</button>
							</div>

					</form>
				</div>
			</div>



			<!-- <div class="modal fade" id="TambahDPRencanaTermin">
				<div class="modal-dialog" style="width: 40%;">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">DATA ENTRY TERMIN</h4>
							<p hidden><?= $this->session->userdata('MIS_LOGGED_NAME'); ?></p>
						</div>

						<form action="<?= site_url('Job/addDPtermin')  ?>" method="post">
							<div class="modal-body">
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Jenis Termin</label>
									<div class="col-sm-7">
										<select name="JenisTermin" class="form-control">
											<option value="">--Pilih Jenis Termin--</option>
											<option value="Uang Muka">Uang Muka</option>
											<option value="Termin">Termin</option>
											<option value="Retensi">Retensi</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Rencana Tgl Jatuh Tempo</label>
									<div class="col-sm-7">
										<input type="date" name="tglRencanaTermin" class="form-control" id="TglRencanaTermin">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Uraian</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="UraianTermin" placeholder="Uraian Termin" required>
										<input type="text" name="Budget" id="Budget" value="<?php echo $ttlDipa->Budget ?>" hidden>
										<?php foreach ($tbldipa as $dp) : ?>
											<input type="text" name="JobNo" id="JobNo" value="<?php echo $dp->JobNo ?>" hidden>
										<?php endforeach; ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Presentasi Rencana</label>
									<label style="align-content: center;">
										<h4 style="align-content: center;">%</h4>
									</label>
									<div class="col-sm-3">
										<input type="text" name="TxtPersentase" id="TxtPersentase" class="form-control" onkeyup="Rtermin()">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Nominal Rencana (A)</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="TxtA" id="TxtA" onkeyup="toDecimal(this,Rtermin())" value="0">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Potongan Uang Muka (B = A x n%)</label>
									<label style="align-content: center;">
										<h4 style="align-content: center;">%</h4>
									</label>
									<div class="col-sm-3">
										<input type="text" class="form-control" name="TxtD" id="TxtD" value="0">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label"></label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="TxtUM" id="TxtUM" value="0" onkeyup="Rtermin()">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Retensi (C = A x 5%) &nbsp <input type="checkbox" name="cbRetensi[]" alt="Checkbox" id="cbRetensi" onkeyup="Rtermin()" value="1"></label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="TxtE" id="TxtE" value="0" onkeyup="Rtermin()">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Total Potongan (D = B + C)</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="TxtF" id="TxtF" onkeyup="Rtermin()" value="0">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Pembayaran Fisik (E = A - D)*Inc PPN</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="TxtG" id="TxtG" value="0" onkeyup="Rtermin()">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">PPN (F = (E/1.11) x 11%) &nbsp <input type="checkbox" name="cbPPN[]" alt="Checkbox" id="cbPPN" onkeyup="Rtermin()" value="2"></label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="TxtH" id="TxtH" value="0" onkeyup="Rtermin()">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Total Nett Exc. PPN (G = E- F)</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="TxtI" id="TxtI" value="0" onkeyup="Rtermin()">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">PPH (H = (E/1.1) x 2.65%) &nbsp <input type="checkbox" name="cbPPH[]" alt="Checkbox" id="cbPPH" onkeyup="Rtermin()" value="3"></label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="TxtJ" id="TxtJ" value="0" onkeyup="Rtermin()">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Netto Exc. PPN & PPH (I = G-H)</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="TxtK" id="TxtK" value="0" onkeyup="Rtermin()">
									</div>
								</div>

								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
									<button type="submit" class="btn btn-primary">Save changes</button>
								</div>
							</div>
						</form>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog --
			</div> -->


			<!-- <div class="modal fade" id="TambahRencanaTermin">
				<div class="modal-dialog" style="width: 40%;">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">DATA ENTRY TERMIN</h4>
						</div>

						<form action="<?= site_url('Job/TambahTermin') ?>" method="post">
							<div class="modal-body">
								<form class="form-horizontal">
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Jenis Termin</label>
										<div class="col-sm-7">
											<select name="JenisTermin1" class="form-control">
												<option value="">--Pilih Jenis Termin--</option>
												<option value="Uang Muka">Uang Muka</option>
												<option value="Termin">Termin</option>
												<option value="Retensi">Retensi</option>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Rencana Tgl Jatuh Tempo</label>
										<div class="col-sm-7">
											<input type="date" name="tglRencanaTermin1" class="form-control" id="TglRencanaTermin1">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Uraian</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" name="UraianTermin1" placeholder="Uraian Termin" onkeyup="this.value = this.value.toUpperCase()" required>
											<input type="text" name="Budget1" id="Budget1" value="<?php echo $ttlDipa->Budget ?>" hidden>
											<?php foreach ($tbldipa as $dp) : ?>
												<input type="text" name="JobNo1" id="JobNo1" value="<?php echo $dp->JobNo ?>" hidden>
											<?php endforeach; ?>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Presentasi Rencana</label>
										<label style="align-content: center;">
											<h4 style="align-content: center;">%</h4>
										</label>
										<div class="col-sm-3">
											<input type="text" name="TxtPersentase1" id="TxtPersentase1" class="form-control" onkeyup="Rtermin1()">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Nominal Rencana (A)</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" name="TxtA1" id="TxtA1" onkeyup="toDecimal(this,Rtermin1())" value="0">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Potongan Uang Muka (B=Axn%)</label>
										<label style="align-content: center;">
											<h4 style="align-content: center;">%</h4>
										</label>
										<div class="col-sm-3">
											<?php foreach ($GetBruto as $gb) : ?>
												<input type="text" class="form-control" name="TxtD1" id="TxtD1" value="<?php echo $gb->Persentase ?>">
											<?php endforeach; ?>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label"></label>
										<div class="col-sm-7">
											<input type="text" class="form-control" name="TxtUM1" id="TxtUM1" value="0" onkeyup="Rtermin1()">
										</div>
									</div>
									<div class="form-group row">

										<label class="col-sm-4 col-form-label">Retensi (C=Ax5%) &nbsp <input type="checkbox" name="cbRetensi1[]" id="cbRetensi1" onkeyup="Rtermin1()" value="1"></label>

										<div class="col-sm-7">
											<input type="text" class="form-control" name="TxtE1" id="TxtE1" value="0" onkeyup="Rtermin1()">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Total Potongan (D=B+C)</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" name="TxtF1" id="TxtF1" onkeyup="Rtermin1()" value="0">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Pembayaran Fisik (E=A-D)*Inc PPN</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" name="TxtG1" id="TxtG1" value="0" onkeyup="Rtermin1()">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">PPN (F=(E/1.11) x11%) &nbsp <input type="checkbox" name="cbPPN1[]" alt="Checkbox" id="cbPPN1" onkeyup="toDecimal(this, Rtermin1())" value="2"></label>
										<div class="col-sm-7">
											<input type="text" class="form-control" name="TxtH1" id="TxtH1" value="0" onkeyup="toDecimal(this, Rtermin1())">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Total Nett Exc. PPN (G = E- F)</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" name="TxtI1" id="TxtI1" value="0" onkeyup="Rtermin1()">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">PPH (H = (E/1.11)x2.65%)&nbsp<input type="checkbox" name="cbPPN1[]" alt="Checkbox" id="cbPPN1" onkeyup="Rtermin()" value="3"></label>
										<div class="col-sm-7">
											<input type="text" class="form-control" name="TxtJ1" id="TxtJ1" value="0" onkeyup="Rtermin1()">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Netto Exc. PPN & PPH (I = G-H) </label>
										<div class="col-sm-7">
											<input type="text" class="form-control" name="TxtK1" id="TxtK1" value="0" onkeyup="Rtermin1()">
										</div>
									</div>

									<div class="modal-footer">
										<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
										<button type="Submit" class="btn btn-primary">Save changes</button>
									</div>
								</form>
							</div>
						</form>
					</div>
					<!-- /.modal-content --
				</div>
				<!-- /.modal-dialog --
			</div> -->

			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
			</div>
		</div>

</div>
</div>




</section>



<script src="<?= base_url('assets/plugins/numeral/numeral.js') ?>"></script>
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="http://code.jquery.com/jquery-2.1.4.js"></script>

<script type="text/javascript">
	function NKTermin() {
		$(document).ready(function() {
			$("#TxtBruto").keyup(function() {
				var bttermin = parseInt($("#TxtBruto").val());
				var ppntermin = parseInt($("#TxtPPN").val());
				var pphtermin = parseInt($("#TxtPPH").val());
				var netto = bttermin - ppntermin - pphtermin;
				$("#TxtNetto").val(netto);
			});

		});
	}

	function bapt() {

		//BAP sekarang **********************************************
		var cv = $('#NilaiKontrak').val();
		cv_si = parseInt(cv.replace(/,/g, ''));

		//pakai 3 digit di belakang koma
		var psi = $('#PersenPekerjaan').val();
		persen_si = parseInt(psi.replace('.', ''));
		//end 3 digit di belakang koma

		// persen_si = parseInt(psi.replace(/,/g,''));
		// persen_si = parseInt($('#PersenPekerjaan').val());

		hasil_si = (cv_si * (persen_si / 1000)) / 100;
		$('#HasilSi').val(numeral(hasil_si).format(0.0));
		// $('#NilaiPekSi').val(numeral(hasil_si).format(0.0));

		//BAP lalu ***************************************************
		var cvb = $('#NilaiKontrakB').val();
		cv_si = parseInt(cvb.replace(/,/g, ''));

		//pakai 3 digit di belakang koma
		var pll = $('#PersenPekerjaanLalu').val();
		persen_ll = parseInt(pll.replace('.', ''));
		//end 3 digit di belakang koma

		// persen_ll = parseInt($('#PersenPekerjaanLalu').val());

		hasil_ll = (cv_si * (persen_ll / 1000)) / 100;
		$('#Jumlahll').val(numeral(hasil_ll).format(0.0));
		// $('#NilaiPekLL').val(numeral(hasil_ll).format(0.0));

		//hasil *******************************************************
		var C = hasil_si - hasil_ll;
		$('#HasilBap').val(numeral(C).format(0.0));
		// $('#JmlPemayaranBAP').val(numeral(C).format(0.0));

		//retensi ********************************************************
		$('#HasilBAP').val(numeral(C).format(0.0));
		// PersenRetensi = parseInt($('#PersenRetensi').val());

		//pakai 3 digit di belakang koma
		var prtnsi = $('#PersenRetensi').val();
		PersenRetensi = parseInt(prtnsi.replace('.', ''));
		//end 3 digit di belakang koma

		NilaiRetensi = (C * (PersenRetensi / 1000)) / 100;
		$('#JmlRetensi').val(numeral(NilaiRetensi).format(0.0));

		//Angsuran UM
		$('#HasilBAPUM').val(numeral(C).format(0.0));
		PersenUM = parseInt($('#PersenUM').val());

		//pakai 3 digit di belakang koma
		var prum = $('#PersenUM').val();
		PersenUM = parseInt(prum.replace('.', ''));
		//end 3 digit di belakang koma

		NilaiUM = (C * (PersenUM / 1000)) / 100;
		$('#JmlPotum').val(numeral(NilaiUM).format(0.0));

		var D = NilaiRetensi + NilaiUM;
		$('#JumlahPemotong').val(numeral(D).format(0.0));



		//Summary 1

		$('#HasilBAPF').val(numeral(C).format(0.0));
		$('#JmlPotUMF').val(numeral(D).format(0.0));
		var T = C - D;
		$('#Total').val(numeral(T).format(0.0));

		//sisi kanan 
		$('#HHasilBAP').val(numeral(T).format(0.0));
		//perhitungan pembayaran 
		$('#NilaiPekSi').val(numeral(hasil_si).format(0.0));
		$('#NilaiPekLL').val(numeral(hasil_ll).format(0.0));
		$('#JmlPemayaranBAP').val(numeral(C).format(0.0));

		//Potongan-potongan
		$('#HPersenRetensi').val(numeral(NilaiRetensi).format(0.0));
		$('#HPersenUM').val(numeral(NilaiUM).format(0.0));
		$('#HJumlahPemotong').val(numeral(D).format(0.0));

		//Pajak-pajak
		$("input[value=1]").on("change", function(evt) {
			if ($(this).prop("checked")) {

				$('#HHasilBAP').val(numeral(T).format(0.0));
				ppnbagi = 1.11;
				ppnpers = 11 / 100;
				NilaiPPn = (T / ppnbagi) * ppnpers;
				$('#PPN').val(numeral(NilaiPPn).format(0.0));

				var DPP = T - NilaiPPn;
				$('#bapdpp').val(numeral(DPP).format(0.0));

				var PPH = DPP * (2.65 / 100);
				$('#PPH').val(numeral(PPH).format(0.0));

				var netto = T - NilaiPPn - PPH;
				$('#Netto').val(numeral(netto).format(0.0));
			} else {
				$('#HHasilBAP').val(numeral(T).format(0.0));
				ppnbagi = 0;
				ppnpers = 0;
				NilaiPPn = 0;
				$('#PPN').val(numeral(NilaiPPn).format(0.0));

				var DPP = T - NilaiPPn;
				$('#bapdpp').val(numeral(DPP).format(0.0));

				var PPH = 0;
				$('#PPH').val(numeral(PPH).format(0.0));

				var netto = T - NilaiPPn - PPH;
				$('#Netto').val(numeral(netto).format(0.0));
			}
		})

	}
</script>
