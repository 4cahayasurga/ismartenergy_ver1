<style type="text/css">
	body {
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
	}

	.huruf12 {
		font-size: 12px !important;
	}

	.huruf13 {
		font-size: 13px !important;
	}

	.jarak {
		padding-top: 5px;
	}

	.jarak_margin {
		margin-top: 5px;
	}

	.jarak5 {
		margin-top: 5px;
	}

	#table_rincian thead tr th {

		font-size: 13px !important;
		text-align: center;
		padding-top: 5px;
		padding-bottom: 5px;
		color: White;
		background-color: #5D7B9D;
		font-weight: bold;
	}

	.huruf13 {
		font-size: 13px !important;
	}

	.huruf14 {
		font-size: 14px !important;
	}

	#table_rincian tbody tr td {
		font-size: 13px !important;

	}

	#table_rincian tfoot tr th {
		font-size: 13px !important;
		padding-top: 5px;
		padding-bottom: 5px;
		color: White;
		background-color: #5D7B9D;
		font-weight: bold;
	}

	fieldset.scheduler-border {
		border: 1px groove #ddd !important;
		padding: 0 1.4em 1.4em 1.4em !important;
		margin: 0 0 1.5em 0 !important;
		-webkit-box-shadow: 0px 0px 0px 0px #000;
		box-shadow: 0px 0px 0px 0px #000;
	}

	legend.scheduler-border {
		font-size: 1.2em !important;
		font-weight: bold !important;
		text-align: left !important;
		width: auto;
		padding: 0 10px;
		border-bottom: none;
	}

	.select2-container {
		width: 100% !important;
	}

	.tengah_ngambang {
		vertical-align: middle;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1><u><?= $title ?></u></h1>
	</section>
	<section class="content">

		<a href="<?= site_url('Kontrak_po/index/' . $JobNo_redirect) ?>" class="btn btn-danger "><i class="fa fa-arrow-left"></i> BACK</a>

		<form id="FormKO" style="margin-top:3px;" data-JobNo_redirect="<?= $JobNo_redirect ?>">
			<div class="box box-primary">
				<div class="box-header">

					<div class="row">

						<div class="col-sm-4 ">
							<table border="0" width="100%">
								<tr>
									<th height="25px" class="text-center huruf12" bgcolor="silver">PENERIMA KONTRAK</th>
								</tr>
							</table>

							<table border="0" width="100%" class="huruf12 jarak_margin">
								<tr>
									<td width="50px">ID</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<select name="VendorId" id="VendorId" class="form-control input-sm select2">
											<option value="">:: Pilih Salah Satu ::</option>
											<?php foreach ($dataVendor as $rowVendor) : ?>
												<option value="<?= $rowVendor->VendorId ?>" <?= ($rowVendor->VendorId == $data_KoHdr->Txt_VendorId) ? 'selected' : '' ?>><?= $rowVendor->VendorNm ?></option>
											<?php endforeach ?>
										</select>
									</td>
								</tr>

								<tr>
									<td width="50px" class="jarak">Nama</td>
									<td width="10px" class="text-center jarak">:</td>
									<td class="jarak">
										<input type="text" id="TxtNama" class="form-control input-sm" placeholder="Nama" disabled>
									</td>
								</tr>

								<tr>
									<td width="50px" class="jarak">Alamat</td>
									<td width="10px" class="text-center jarak">:</td>
									<td class="jarak">
										<input type="text" id="TxtAlamat" class="form-control input-sm" placeholder="Alamat" disabled>
									</td>
								</tr>

								<tr>
									<td width="50px" class="jarak">Telepon</td>
									<td width="10px" class="text-center jarak">:</td>
									<td class="jarak">
										<input type="text" id="TxtTelepon" class="form-control input-sm" placeholder="Telepon" disabled>
									</td>
								</tr>

								<tr>
									<td width="50px" class="jarak">NPWP</td>
									<td width="10px" class="text-center jarak">:</td>
									<td class="jarak">
										<input type="text" id="TxtNPWP" class="form-control input-sm" placeholder="NPWP" disabled>
									</td>
								</tr>


							</table>
						</div>

						<div class="col-sm-4 ">
							<table border="0" width="100%" class="huruf12 jarak_margin">
								<tr>
									<td width="100px">Tgl Kontrak</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="date" name="TglKO" id="TglKO" class="form-control input-sm" value="<?= date('Y-m-d') ?>" required>
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="huruf12 jarak_margin">
								<tr>
									<td width="100px">No. Kontrak</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="NoKO" id="NoKO" class="form-control input-sm" disabled>
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="huruf12 jarak_margin">
								<tr>
									<td width="100px">Job No</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm" value="<?= $JobNo_redirect . ' - ' . $JobNm ?>" disabled>
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="huruf12 jarak_margin" hidden>
								<tr>
									<td width="100px">Kategori Usaha</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" id="TxtKategori" class="form-control input-sm" placeholder="Isi Disini" disabled>
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="huruf12 jarak_margin">
								<tr>
									<td width="100px">Bidang Usaha</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" id="TxtUsaha" class="form-control input-sm" placeholder="Isi Disini" disabled>
									</td>
								</tr>
							</table>
							<table border="0" width="100%" class="huruf12 jarak_margin">
								<tr>
									<td width="100px">Kategori Vendor</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<select name="KatVen" id="KatVen" class="form-control" require>
											<option value="">--pilih Kategori--</option>
											<option value="Material">Material</option>
											<option value="Upah">Upah</option>
											<option value="Alat">Alat</option>
											<option value="Borong">Borong</option>
											<option value="Asset">Asset</option>
										</select>
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="huruf12 jarak_margin">
								<tr>
									<td width="100px">System Pembayaran</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<select name="SistemPembayaran" id="SistemPembayaran" class="form-control" require onchange="enabledisabletext()">
											<option value="">--Pilih System Pembayaran--</option>
											<option value="Back To Back Termin">Back To Back Termin</option>
											<option value="Non Back To Back Termin">Non Back To Back Termin</option>
										</select>
									</td>
								</tr>
							</table>

						</div>

					</div>
					<div class="row">
						<div class="col-sm-8">
							<hr style="border:1px solid black;">
							<button type="submit" class="btn btn-success  color-palette btn-md pull-right " id="btnSimpanPO">SIMPAN</button>
							<!-- <button type="button" class="btn btn-default  color-palette btn-md pull-right disabled " id="btnLoading" style="display:none;">LOADING...</button> -->
						</div>
					</div>

				</div>

				<div class="box-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="table-responsive">

								<table border="0" width="100%">
									<tr>
										<td align="center" bgcolor="silver" style="height:30px; font-weight:bold;">R I N C I A N&nbsp; &nbsp;P E K E R J A A N</td>
									</tr>
									<tr>
										<td class="jarak">
											<button type="button" class="btn btn-md btn-success color-palette" id="btnAdd_RincianPekerjaan">TAMBAH RINCIAN <i class="fa fa-plus"></i> </button>

											<button type="button" class="btn btn-md btn-danger btnCancel_RincianPekerjaan" style="display:none;">BATAL</button>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<br>


					<!-- == START FORM TAMBAH KODTL -->
					<fieldset class="scheduler-border" style="display:none;">
						<legend class="scheduler-border">FORM TAMBAH RINCIAN PEKERJAAN</legend>
						<div class="col-sm-12" id="form_tambah">

							<div class="row">


								<div class="col-sm-6">

									<table width="100%">
										<tr>
											<td>
												<label>Kode RAP</label>
												<select id="KdRAP_Add" class="form-control input-sm huruf13 select2">
													<option value="">:: Pilih ::</option>
													<?php foreach ($ListRAP as $row) : ?>
														<option value="<?= $row->KdRAP ?>"><?= $row->KdRAP ?> - <?= $row->Uraian ?></option>
													<?php endforeach ?>
												</select>
											</td>
											<td></td>
										</tr>
										<tr>
											<td>
												<label>Uraian</label>
												<textarea id="Uraian_Add" class="form-control" placeholder="Isi Disini" rows="4" cols="50"></textarea>
											</td>
											<td></td>
										</tr>
									</table>

									<div class="col-sm-12 row">
										<table width="100%" border="0" style="margin-top:10px;">
											<tr>
												<td width="120px"><label>Tgl Pelaksanaan</label></td>
												<td width="20px" class="text-center">:</td>
												<td width="100px">
													<input type="date" name="DrTgl_Pelaksanaan" id="DrTgl_Pelaksanaan" class="form-control">
												</td>
												<td class="text-center" width="30px">s.d</td>
												<td width="100px">
													<input type="date" name="SpTgl_Pelaksanaan" id="SpTgl_Pelaksanaan" class="form-control">
												</td>
												<td></td>
											</tr>
										</table>

										<table width="100%" border="0" style="margin-top:10px;">
											<tr>
												<td width="120px"><label>Tgl Pembayaran</label></td>
												<td width="20px" class="text-center">:</td>
												<td width="100px">
													<input type="date" name="DrTgl_Pembayaran" id="DrTgl_Pembayaran" class="form-control">
												</td>
												<td class="text-center" width="30px">s.d</td>
												<td width="100px">
													<input type="date" name="SpTgl_Pembayaran" id="SpTgl_Pembayaran" class="form-control">
												</td>
												<td></td>
											</tr>
										</table>
									</div>


								</div>

								<div class="col-sm-6">
									<table width="100%">
										<tr>
											<td width="49%">
												<label>Satuan</label>
												<input id="Uom_Add" class="form-control input-sm huruf13" placeholder="Isi Disini" maxlength="15">
											</td>
											<td width="2%"></td>
											<td width="49%">

												<label>Volume</label>
												<input id="Vol_Add" class="form-control input-sm huruf13 text-right Vol_Tambah" maxlength="13" value="0.000">
											</td>
										</tr>
									</table>
								</div>

								<div class="col-sm-6">
									<table width="100%">
										<tr>
											<td width="49%">
												<label>Harga Satuan (Rp)</label>
												<input id="HrgSatuan_Add" class="form-control input-sm huruf13 text-right HrgSatuan_Tambah" value="0">
											</td>
											<td width="2%"></td>
											<td width="49%">
												<label>Jumlah Harga(Rp)</label>
												<input id="JumlahHarga_Add" class="form-control input-sm huruf13 text-right JumlahHarga_Tambah" value="0" readonly>
											</td>
										</tr>
									</table>
								</div>

								<div class="col-sm-6">
									<table width="100%">
										<tr>
											<td width="49%">
												<label>PPN Persen (%)</label>
												<input id="PPN_Persen_Add" class="form-control input-sm huruf13 text-right PPN_Persen_Tambah" value="0">
											</td>
											<td width="2%"></td>
											<td>
												<label>PPN Nominal (Rp)</label>
												<input id="PPN_Nominal_Add" class="form-control input-sm huruf13 text-right PPN_Nominal_Tambah" value="0" readonly>
											</td>

											<td>
												<label>Total PPN Nominal (Rp)</label>
												<input id="TOTAL_PPN_Nominal_Add" class="form-control input-sm huruf13 text-right T_PPN_Nominal_Tambah" value="0" readonly>
											</td>
										</tr>
									</table>
								</div>



							</div>

							<div class="row">
								<div class="col-sm-12">
									<hr style="border:1px solid silver;">
									<table width="100%" border="0">
										<tr>
											<td>
												<button type="button" class="btn btn-md btn-danger btnCancel_RincianPekerjaan" style="display:none;">BATAL</button>
											</td>

											<td class="text-right">
												<button type="button" class="btn btn-primary btnSave_tambah" data-flag="btnSave_tambah_dan_tutup">Add & Tutup Form</button>
												<button type="button" class="btn btn-primary btnSave_tambah" data-flag="btnSave_tambah">Add</button>
											</td>
										</tr>
									</table>
								</div>
							</div>





						</div>
					</fieldset>
					<!-- == END FORM TAMBAH KODTL -->

					<div class="row">
						<div class="col-sm-12">
							<div class="table-responsive">

								<table class="table table-bordered table-striped table-hover table_rincian" border="0" width="100%" id="table_rincian" style="margin-top:10px;">
									<thead>
										<tr>
											<th style="width:60px !important;" class="text-center" rowspan="2">No.</th>
											<th class="text-center tengah_ngambang" rowspan="2">Kode RAP</th>
											<th width="300px" rowspan="2">Uraian</th>
											<th class="text-center tengah_ngambang" rowspan="2">Vol</th>
											<th class="text-center" rowspan="2">Sat</th>
											<th class="text-center" rowspan="2">Harga Satuan (Rp)</th>
											<th class="text-center" rowspan="2">Jumlah Harga (Rp)</th>

											<th rowspan="2">PPN (%)</th>
											<th rowspan="2">PPN Nominal</th>
											<th rowspan="2">Total PPN Nominal</th>
											<th class="text-center" colspan="2">Tgl Pelaksanaan</th>
											<th class="text-center" colspan="2">Tgl Pembayaran</th>
											<th style="width:100px !important;" class="text-center"></th>
										</tr>
										<tr>

											<th>Dari Tgl</th>
											<th>Sampai Tgl</th>
											<th>Dari Tgl</th>
											<th>Sampai Tgl</th>
											<th></th>
										</tr>
									</thead>
									<tbody>


									</tbody>
									<tfoot class="tfootClass">

										<tr>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th class="text-center ">Sub Total</th>
											<th class="text-right SubTotalClass">0</th>
											<th></th>
											<th></th>
											<th class="text-right SubTotal_PPN_Class">0</th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
										</tr>
									</tfoot>
								</table>

								<br>
								<table width="100%" border="0" class="jarak5">
									<tr>
										<td width="70%"></td>
										<td width="10%" class="text-center text-bold">Dikurangi KO</td>
										<td width="20%">
											<input type="text" name="TxtPotongKO" id="TxtPotongKO" class="form-control input-sm text-right" readonly>
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="jarak5">
									<tr>
										<td width="70%"></td>
										<td width="10%" class="text-center text-bold">Total Pengurang</td>
										<td width="20%">
											<input type="text" name="TxtTtlPengurang" id="TxtTtlPengurang" class="form-control input-sm text-right huruf14 text-bold TxtTtlPengurang" value="<?= number_format(0) ?>" readonly>
										</td>
									</tr>
								</table>


								<table width="100%" border="0" class="jarak5">
									<tr>
										<td width="50%"></td>
										<td width="30%" class="text-center text-bold">Grand Total <small style="text-decoration:normal;">(Sub Total - Total Pengurang + Total PPN Nominal )</small></td>
										<td width="20%">
											<input type="text" name="TxtPPN" id="TxtPPN" class="form-control input-sm text-right huruf14 text-bold hidden" value="0" readonly>
											<input type="text" name="TxtTotal" id="TxtTotal" class="form-control input-sm text-right huruf14 text-bold" value="0" readonly>
										</td>
									</tr>
								</table>

							</div>
						</div>
					</div>


				</div>

				<div class="box-footer">


					<div class="row"> <!-- == START PERSYARATAN DAN SANKSI -->
						<div class="col-sm-8">
							<table width="100%" border="0">
								<tr>
									<td align="center" bgcolor="silver" style="height:20px; font-weight:bold;padding-top:5px;padding-bottom: 5px;">Persyaratan &amp; Sanksi</td>
								</tr>
							</table>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-4">
							<table width="100%" border="0">
								<tr>
									<td>1. Wajib memenuhi persyaratan Kesehatan & Keselamatan Kerja (K3) </td>
									<td><input type="checkbox" id="CheckBoxK3" name="CheckBoxK3" value="1" class="huruf13"></td>
								</tr>
							</table>
							<table width="100%" border="0" style="margin-top: 5px;">
								<tr>
									<td>2. Syarat Teknis </td>

								</tr>
								<tr>
									<td><textarea name="TxtSyaratTeknis" id="TxtSyaratTeknis" class="form-control input-sm" rows="4" Width="100%" MaxLength="255"></textarea></td>
								</tr>
							</table>

							<table width="100%" border="0" style="margin-top: 5px;">
								<tr>
									<td>3. Jadwal Pengiriman </td>

								</tr>
								<tr>
									<td><textarea name="TxtJadwalPengiriman" id="TxtJadwalPengiriman" class="form-control input-sm" rows="4" Width="100%" MaxLength="255"></textarea></td>
								</tr>
							</table>

							<table width="100%" border="0" style="margin-top: 5px;">
								<tr>
									<td>4. Jadwal Pembayaran </td>

								</tr>
								<tr>
									<td><textarea name="TxtJadwalPembayaran" id="TxtJadwalPembayaran" class="form-control input-sm" rows="4" Width="100%" MaxLength="255"></textarea></td>
								</tr>
							</table>



						</div>

						<div class="col-sm-4">
							<br>

							<table width="100%" border="0" style="margin-top: 10px;">
								<tr>
									<td>5. Syarat Pembayaran</td>

								</tr>
								<tr>
									<td width="50%">
										<input type="checkbox" id="CBInvoice" name="INV" value="INV" class="huruf13">
										<label for="CBInvoice" style="font-weight: normal;" class="huruf13">Invoice/Kwitansi</label>
									</td>
									<td width="50%">
										<input type="checkbox" id="CBFP" name="FP" value="FP" class="huruf13">
										<label for="CBFP" style="font-weight: normal;" class="huruf13">Faktur Pajak</label>
									</td>
								</tr>
								<tr>
									<td width="50%">
										<input type="checkbox" id="CBSJ" name="SJ" value="SJ" class="huruf13">
										<label for="CBSJ" style="font-weight: normal;" class="huruf13">Surat Jalan/Tanda Terima Lapangan</label>
									</td>
									<td width="50%">
										<input type="checkbox" id="CBBayar" name="BAP" value="BAP" class="huruf13">
										<label for="CBBayar" style="font-weight: normal;" class="huruf13">Berita Acara Pembayaran</label>
									</td>
								</tr>

								<tr>
									<td width="50%">
										<input type="checkbox" id="CBPO" name="PO" value="PO" class="huruf13">
										<label for="CBPO" style="font-weight: normal;" class="huruf13">Copy PO</label>
									</td>
									<td width="50%">
										<input type="checkbox" id="CBFisik" name="BAOP" value="BAOP" class="huruf13">
										<label for="CBFisik" style="font-weight: normal;" class="huruf13">Berita Acara Opname Pekerjaan</label>
									</td>
								</tr>
							</table>
							<table width="100%" border="0" style="margin-top: 5px;">
								<tr>
									<td>6. Sanksi</td>

								</tr>
								<tr>
									<td><textarea name="TxtSanksi" id="TxtSanksi" class="form-control input-sm" rows="4" Width="100%" MaxLength="255"></textarea></td>
								</tr>
							</table>

							<table width="100%" border="0" style="margin-top: 5px;">
								<tr>
									<td>7. Keterangan Lainnya </td>
								</tr>
								<tr>
									<td><textarea name="TxtKeterangan" id="TxtKeterangan" class="form-control input-sm" rows="4" Width="100%" MaxLength="255"></textarea></td>
								</tr>
							</table>
						</div>


					</div> <!-- == END PERSYARATAN DAN SANKSI -->



				</div>
		</form>

	</section>
</div>
