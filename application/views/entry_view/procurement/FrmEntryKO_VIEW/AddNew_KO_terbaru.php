<style type="text/css">
	body{
		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
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

	#table_input_rap{
		width: 100%;
		border-collapse: collapse;
		margin-top: 5px;
	}
	.superscript {
		font-size: 0.7em; /* Atur ukuran font huruf kecil */
		vertical-align: super; /* Letakkan huruf kecil di atas */
	}

	.readonly_buatan{
		background-color: #f0f0f0; border: 1px solid #ccc; color: #777;
	}

	#tableRincianKoDtl{
		margin-top: 10px;
		border-collapse: collapse;
		border: 1px solid silver;   
	}

	#tableRincianKoDtl thead tr th{
		border-collapse: collapse;
		border: 1px solid silver;
		background-color: #bdc3c7;
		padding-top : 5px;
		padding-bottom: 5px;
		text-align: center;
		font-size: 12px;
		text-align: center;
	}
	#tableRincianKoDtl tbody tr td{

		padding-top : 5px;
		padding-bottom: 5px;
		font-size: 12px;
		padding-right : 2px;
		padding-left : 2px;
		border:none;
	}
	#tableRincianKoDtl tfoot tr td{
		background-color: #bdc3c7;
		font-weight: bold;
		padding-top : 5px;
		padding-bottom: 5px;
		font-size: 12px;
		border:none;
	}

	.td_rap{
		min-width: 50px;
		max-width: 700px;
	}

	.ukuran_default_tulisan{
		font-size: 12px;
		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
	}
	input[type="text"].JumlahHarga_subrow, input[type="text"].HrgSatuan_subrow {
		border: none;
		background: transparent; 
		outline: none;
		padding: 0;
		margin: 0;
		text-align: right;
	}
	input[type="text"].Vol_subrow {
		width: 100%;
		text-align: right;
	}

	.hilangkan_border_kanan {
		border-right: none;
	}

	.hilangkanborderatas {
		border-top: none;
	}


	.tooltip {
		position: relative;
		display: inline-block;
	}

	.tooltip .tooltiptext {
		visibility: hidden;
		width: 120px;
		background-color: #555;
		color: #fff;
		text-align: center;
		border-radius: 6px;
		padding: 5px;
		position: absolute;
		z-index: 1;
		bottom: 125%;
		left: 50%;
		margin-left: -60px;
		opacity: 0;
		transition: opacity 0.3s;
	}

	.tooltip .tooltiptext::after {
		content: "";
		position: absolute;
		top: 100%;
		left: 50%;
		margin-left: -5px;
		border-width: 5px;
		border-style: solid;
		border-color: #555 transparent transparent transparent;
	}

	.tooltip:hover .tooltiptext {
		visibility: visible;
		opacity: 1;
	}

	.border-red {
		border: 1px solid red;
	}
	.custom-dropdown-width .select2-dropdown {
		width: 200px;
	}

	#floating-box {
		position: absolute;
		top: 50px; /* Atur posisi vertikal sesuai keinginan Anda */
		left: 50px; /* Atur posisi horizontal sesuai keinginan Anda */
		z-index: 9999; /* Pastikan z-index lebih tinggi dari latar belakang */
		cursor: move; /* Kursor berubah menjadi ikon yang sesuai saat digeser */
	}

	#table_input_rap tr td{
		padding-top: 5px;
	}


</style>

<div class="content-wrapper">

	<section class="content-header">
		<h1><u><?= $title ?></u></h1>
	</section>

	<section class="content">

		<a href="<?= site_url('Kontrak_po/index/' . $JobNo_redirect) ?>" class="btn btn-danger "><i class="fa fa-arrow-left"></i> BACK</a>

		<form id="FormKO" style="margin-top:3px;" data-JobNo_redirect="<?= $JobNo_redirect ?>">

			<div class="row" style="margin-top: 10px;margin-bottom:10px;">
				<div class="col-md-12 text-right">
					<button type="button" class="btn btn-primary btn-md btn_simpan_all">SIMPAN</button>
				</div>
			</div>

			


			<div class="row">
				<div class="col-md-6">
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title">Penerima Kontrak</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
								</button>
							</div>
						</div>
						<div class="box-body">

							<div class="col-md-6">
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

							<div class="col-sm-6">
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
											<select name="SistemPembayaran" id="SistemPembayaran" class="form-control" require>
												<option value="">--Pilih System Pembayaran--</option>
												<option value="Back To Back Termin">Back To Back Termin</option>
												<option value="Non Back To Back Termin">Non Back To Back Termin</option>
											</select>
										</td>
									</tr>
								</table>

							</div>

						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title">Persyaratan & Sanksi</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
								</button>
							</div>
						</div>
						<div class="box-body">
							<div class="col-sm-6">
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

							<div class="col-sm-6">
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

						</div>
					</div>
				</div>

				

				
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title">Rincian Pekerjaan</h3>
							
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
								</button>
							</div>
							<br>
							
						</div>
						<div class="box-body">
							<div class="row">

								<div class="col-md-12">
									<button type="button" class="btn btn-md btn-danger" id="showFloatingBox">Add New </button>

									<div class="table-responsive">
										<table id="tableRincianKoDtl" width="100%" border="1" style="border-collapse:collapse;">
											<thead>
												<tr>
													<th style="width: 30px;max-width: 30px;">No.</th>
													<th style="width: 50px;max-width: 50px;" >Kode RAP</th>
													<th style="width: 400px;max-width: 400px;">Uraian</th>
													<th style="width: 50px;max-width: 50px;">Sat</th>
													<th style="width: 80px;max-width: 80px;">Vol</th>
													<th style="width: 100px;max-width: 100px;">Hrg Satuan <sup class="superscript">Rp</sup></th>
													<th style="width: 100px;max-width: 100px;">Jumlah Harga <sup class="superscript">Rp</sup></th>
													<th style="width: 80px;max-width: 80px;">PPN <sup class="superscript">%</sup></th>
													<th style="width: 80px;max-width: 80px;">Jumlah PPN  <sup class="superscript">Rp</sup></th>
													<th style="width: 80px;max-width: 80px;">Ttl PPN <sup class="superscript">Rp</sup></th>
													<th hidden>Tipe</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody id="tbodyRincian">

											</tbody>
											<tfoot>
												<tr>
													<td colspan="6">Sub Total : </td>
													<td class="text-right TxtSubTotal_JumlahHarga" >0</td>
													<td></td>
													<td></td>
													<td class="text-right TxtSubTotal_TotalPPNNominal">0</td>
													<td></td>
												</tr>
											</tfoot>
										</table>
									</div>

								</div>

								<br>

							</div>

							<div class="row" style="margin-top:5px;">
								<div class="col-md-6"></div>
								<div class="col-md-4 pull-right">
									<div class="form-group">
										<label class="control-label col-sm-4" for="email" style="padding-top: 7px;text-align: right;">Dikurang KO:</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" id="TxtPotongKO" value="<?=$TxtPotongKO?>">
										</div>
									</div>
								</div>
							</div>

							<div class="row" style="margin-top:5px;">
								<div class="col-md-6"></div>
								<div class="col-md-4 pull-right">
									<div class="form-group">
										<label class="control-label col-sm-4" for="email" style="padding-top: 7px;text-align: right;">Total Pengurang:</label>
										<div class="col-sm-6">
											<input type="text" class="form-control text-right" id="TxtTtlPengurang" value="<?=$TxtTtlPengurang?>" readonly>
										</div>
									</div>
								</div>
							</div>

							<div class="row" style="margin-top:5px;">
								<div class="col-md-6"></div>
								<div class="col-md-4 pull-right">
									<div class="form-group">
										<label class="control-label col-sm-4" for="email" style="padding-top: 7px;text-align: right;">Grand Total:</label>

										<div class="col-sm-6">
											<input type="text" class="form-control text-right" id="TxtTotal" value="<?=$TxtTotal?>" readonly>
											<span class="small text-danger">(Sub Total - Total Pengurang + Total PPN Nominal)</span>
										</div>

									</div>
								</div>
							</div>


						</div>
					</div>
				</div>
			</div>

			
			<div class="row" style="margin-top: 10px;">
				<div class="col-md-12 text-right">
					<button type="button" class="btn btn-primary btn-md btn_simpan_all">SAVE</button>
				</div>
			</div>



		</form>



		<div id="floating-box" style="display: none;position: fixed;">
			<div class="box" style="border:1px solid blue;" >
				<div class="box-header with-border bg-primary">
					<h3 class="box-title" style="color:white;">Entry Form</h3>
					<div class="box-tools pull-right" >
						<button type="button" class="btn btn-box-tool close-btn"><i class="fa fa-close" style="color:white;font-weight: bold;"></i>
						</button>
					</div>

				</div>
				<div class="box-body">
					<div class="table-responsive">
						<table id="table_input_rap">
							<tr class="jarak_atas">
								<td width="150px">Kode RAP</td>
								<td width="10px" class="text-center">:</td>
								<td width="400px"style="max-width:400px;" >
									<select id="KdRAP" name="KdRAP" class="form-control select2" ><?=$BindRAP?></select>
								</td>
								<td></td>
							</tr>

							<tr style="display:none;" class="jarak_atas">
								<td width="100px">Tipe</td>
								<td width="10px" class="text-center">:</td>
								<td width="300px" >
									<textarea name="Tipe" id="Tipe" class="form-control"></textarea>
								</td>
								<td></td>
							</tr>

							<tr class="jarak_atas">
								<td width="100px">Uraian</td>
								<td width="10px" class="text-center">:</td>
								<td width="300px" >
									<textarea class="form-control" name="Uraian" id="Uraian"  maxlength="255" required></textarea>
								</td>
								<td></td>
							</tr>

							<tr class="jarak_atas">
								<td width="100px">Sat</td>
								<td width="10px" class="text-center">:</td>
								<td width="300px" >
									<input type="text" name="Uom" id="Uom" maxlength="10">
								</td>
								<td></td>
							</tr>

							<tr class="jarak_atas">
								<td width="100px">Vol</td>
								<td width="10px" class="text-center">:</td>
								<td width="300px" >
									<input type="text" name="Vol" id="Vol" value="0" >
								</td>
								<td></td>
							</tr>

							<tr class="jarak_atas">
								<td width="100px">Hrg Satuan <sup class="superscript">Rp</sup></td>
								<td width="10px" class="text-center">:</td>
								<td width="300px" >
									<input type="text" name="HrgSatuan" id="HrgSatuan" value="0"  >
								</td>
								<td></td>
							</tr>

							<tr class="jarak_atas">
								<td width="100px">Jumlah Harga <sup class="superscript">Rp</sup></td>
								<td width="10px" class="text-center">:</td>
								<td width="300px" >
									<input type="text" name="JumlahHarga" id="JumlahHarga" value="0" readonly>
								</td>
								<td></td>
							</tr>

							<tr class="jarak_atas">
								<td width="100px">PPN Persen <sup class="superscript">%</sup></td>
								<td width="10px" class="text-center">:</td>
								<td width="300px" >
									<input type="text" id="PPNPersen" name="PPNPersen" class="PPNPersen" value="0"  >
								</td>
								<td></td>
							</tr>

							<tr class="jarak_atas">
								<td width="100px">PPN Nominal <sup class="superscript">Rp</sup></td>
								<td width="10px" class="text-center">:</td>
								<td width="300px" >
									<input type="text" name="PPNNominal" id="PPNNominal" value="0" class="readonly_buatan" readonly >
								</td>
								<td></td>
							</tr>

							<tr class="jarak_atas">
								<td width="100px">Total PPN Nominal <sup class="superscript">Rp</sup></td>
								<td width="10px" class="text-center">:</td>
								<td width="300px" >
									<input type="text" name="TotalPPNNominal" id="TotalPPNNominal" class="readonly_buatan" value="0" readonly>
								</td>
								<td></td>
							</tr>

							<tr class="jarak_atas">
								<td width="100px"></td>
								<td width="10px" class="text-center">:</td>
								<td width="300px" >
									<button type="button" id="btnAdd" class="btn btn-primary">Add</button>
								</td>
								<td></td>
							</tr>

						</table>
					</div>
				</div>

			</div>

		</div>

	</section>
</div>

<script type="text/javascript">
	var myurl = "<?=site_url('Entry/Procurement/FrmEntryKO_C/')?>";
	var myurl_2 = "<?=site_url('Kontrak_po/index/')?>";
	var JobNo = "<?=$JobNo?>";
	var Alokasi = "<?=$Alokasi?>";
	var globalArray = [];
	var kodeRAPs = [];
	var vol_ketik;
	var ArrayBaru = [];
	var opsiYangDihapus = [];


	$(document).ready(function(){

		var rowNum = 1;

		$('.datePeriode').daterangepicker();

		$('#showFloatingBox').on('click', function() {
			$('#floating-box').toggle();

			$('#table_input_rap #KdRAP').select2({
				dropdownParent: $('#floating-box') 
			});
			// $('#floating-box').show();
		});


		$('.close-btn').on('click', function() {
			$('#floating-box').hide();
		});

		var $draggableElement = $('#floating-box');
		var $boxHeader = $draggableElement.find('.box-header');
		var isDragging = false;
		var offsetX, offsetY;

		$boxHeader.on('mousedown', function(e) {
			isDragging = true;
			var $floatingBox = $('#floating-box');

			offsetX = e.clientX - $floatingBox.offset().left;
			offsetY = e.clientY - $floatingBox.offset().top;

			$(document).on('mousemove', function(e) {
				if (isDragging) {
					$floatingBox.offset({
						top: e.clientY - offsetY,
						left: e.clientX - offsetX
					});
				}
			});

			$(document).on('mouseup', function() {
				isDragging = false;
				$(document).off('mousemove');
				$(document).off('mouseup');
			});
		});


		$('#table_input_rap #KdRAP').on('change',function(){

			$('#table_input_rap #Uraian').val('');
			$('#table_input_rap #Tipe').val('');
			$('#table_input_rap #Uraian').val('');
			$('#table_input_rap #Uom').val('');
			$('#table_input_rap #Vol').val('0');
			$('#table_input_rap #HrgSatuan').val('0');
			$('#table_input_rap #JumlahHarga').val('0');

			var selectedOption = $(this).find('option:selected');
			var Tipe = selectedOption.attr('data-Tipe');
			var Uraian = selectedOption.attr('data-Uraian');
			var Uom = selectedOption.attr('data-Uom');
			var Vol = selectedOption.attr('data-Vol');
			var HrgSatuan = selectedOption.attr('data-HrgSatuan');
			var JumlahHarga = selectedOption.attr('data-JumlahHarga');

			$('#table_input_rap #Tipe').val(Tipe);
			// $('#table_input_rap #Uraian').val(Uraian);
			// $('#table_input_rap #Uom').val(Uom);
			// $('#table_input_rap #Vol').val(Vol);
			// $('#table_input_rap #HrgSatuan').val(HrgSatuan);
			// $('#table_input_rap #JumlahHarga').val(JumlahHarga);

		});

		if ($('#FormKO #VendorId').val() != '') {
			Vendor_Berganti();
		}

		$('#table_input_rap #KdRAP').select2({
			dropdownCssClass: 'custom-dropdown-width'
		});

		$('.select2').select2({
			// width: '100%',
			// dropdownAutoWidth: true,
			dropdownCssClass: 'custom-dropdown-width'
		});

		$('#FormKO #VendorId').change(function() {
			Vendor_Berganti();
		});

		untukPPNPersen($('#table_input_rap #PPNPersen'));
		UntukHargaSatuan($('#table_input_rap #HrgSatuan'));
		UntukVol($('#table_input_rap #Vol'));

		vol_ketik = $('.vol_ketik');

		$('#btnAdd').on('click', function() {

			var flag_btn_disabled = 0;

			if ($('#table_input_rap #KdRAP option:selected').val() == '') {
				alert('Belum ada RAP yang di-pilih');
				return false;
			}

			if ($('#table_input_rap #Uraian').val() == '') {
				alert('Uraian belum di-isi.');
				return false;
			}

			if ($('#table_input_rap #Vol').val() == '' || $('#table_input_rap #Vol').val() == '0' || $('#table_input_rap #Vol').val() == '0.000') {
				alert('Volume belum di-isi.');
				return false;
			}

			if ($('#table_input_rap #Uom').val() == '') {
				alert('Satuan belum di-isi.');
				return false;
			}


			var KdRAPValue = $('#table_input_rap #KdRAP').val();
			var TipeValue = $('#table_input_rap #Tipe').val();
			var UraianValue = $('#table_input_rap #Uraian').val();
			var UomValue = $('#table_input_rap #Uom').val();
			var VolValue = $('#table_input_rap #Vol').val();
			var HrgSatuanValue = $('#table_input_rap #HrgSatuan').val();
			var JumlahHargaValue = $('#table_input_rap #JumlahHarga').val();
			var PPNPersenValue = $('#table_input_rap #PPNPersen').val();
			var PPNNominalValue = $('#table_input_rap #PPNNominal').val();
			var TotalPPNNominalValue = $('#table_input_rap #TotalPPNNominal').val();

			var data = {
				KdRAP: KdRAPValue,
				Tipe: TipeValue,
				Uraian: UraianValue,
				Uom: UomValue,
				Vol: VolValue,
				HrgSatuan: HrgSatuanValue,
				JumlahHarga: JumlahHargaValue,
				PPNPersen: PPNPersenValue,
				PPNNominal: PPNNominalValue,
				TotalPPNNominal: TotalPPNNominalValue
			};

			populateTable(data);
			ClearDataForm();
			getSubTotal()

		});


		$(document).on('click', '.btn-add-subrow', function() {

			var rowId = $(this).closest('tr').attr('rowId');
			var rowInduk = $(this).closest('.rowInduk');

			var Uom_Induk = rowInduk.find('td:eq(3)').text();
			var HrgSatuan_Induk = rowInduk.find('td:eq(5)').text();

			var $elementDiv = rowInduk.find('td:eq(11)').find('div.divtglPengirimanInduk');
			
			// alert(aaaa);
			// return false;
			// alert(aaaa.find('.inites').val());
			// $('tr.rowInduk[rowId="' + rowData.KdRAP + '"]').find('.tglPengirimanInduk').hide();

			var subrow = `
			<tr rowId="${rowId}" style="border:0px;" class="subrow" data-parentrowid="${rowId}">
			<td></td>
			<td></td>
			<td></td>
			<td></td>

			<td hidden><input type="hidden" name="KdRAP_subrow[]" value="${rowId}" ></td>
			<td class="text-right"><input type="text" name="Vol_subrow[]" class="Vol_subrow" value="0"></td>
			<td class="text-right"><input type="text" name="HrgSatuan_subrow[]" class="HrgSatuan_subrow" value="${HrgSatuan_Induk}" readonly></td>
			<td class="text-right"><input type="text" name="JumlahHarga_subrow[]" class="JumlahHarga_subrow" value="0" readonly></td>
			<td class="text-center"><button type="button" class="btn btn-danger btn-sm btn-hapus-subrow"><i class="fa fa-trash"></i></button></td>
			<td></td>

			<td colspan="2">

			<div class="row">
			<div class="col-md-4">
			<label>Tgl Pengiriman</label>
			<div class="input-group-addon">
			<i class="fa fa-calendar"></i>
			</div>
			<input type="text" class="form-control input-sm pull-right datePeriode TglPengiriman_subrow" name="TglPengiriman_subrow[]">
			</div>

			<div class="col-md-4">
			<label>Tgl Pembayaran</label>
			<div class="input-group-addon">
			<i class="fa fa-calendar"></i>
			</div>
			<input type="text" class="form-control input-sm pull-right datePeriode TglPembayaran_subrow" name="TglPembayaran_subrow[]">
			</div>
			</div>


			</td>


			</tr>`;

			var subrows = $('tr.subrow[data-parentrowid="' + rowId + '"]');
			var subrows_total = $('tr.subrow-total[data-subrow_total="' + rowId + '"]');

			if (subrows.length === 0) {
				$(this).closest('tr').after(subrow);
			} else {
				subrows.last().after(subrow);
			}

			if (subrows.length>=0 ) {
				$elementDiv.hide();
			}else{
				$elementDiv.show();
			}



			$('tr.subrow[data-parentrowid="' + rowId + '"]').find('.datePeriode').daterangepicker({
				opens: 'left', 
				startDate: new Date(),
				endDate: moment().add(1, 'months').toDate(), 
				locale: {
					format: 'YYYY-MM-DD', 
					separator: ' to ', 
					applyLabel: 'Pilih', 
					cancelLabel: 'Batal', 
					fromLabel: 'Dari', 
					toLabel: 'Hingga', 
					customRangeLabel: 'Pilih Rentang Tanggal' 
				}
			}).on('apply.daterangepicker', function(ev, picker) {
				$(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
				$(this).closest('.input-group').find('.btn.applyBtn').text('Pilih');
			});


			AddBaris_TotalDariSubrow.call(this);
			HitungTotal_SubRow(rowId);


		});

		$(document).on('click', '.btn-hapus-subrow', function() {
			var rowId = $(this).closest('tr').attr('rowId');

			$(this).closest('.subrow').remove();
			HitungTotal_SubRow(rowId);
			hapus_juga_row_total(rowId);

			var rowInduk = $('tr.rowInduk[rowId="' + rowId + '"]');
			// console.log(rowInduk);
			var $elementDiv = rowInduk.find('td:eq(11)').find('div.divtglPengirimanInduk');
			// console.log($elementDiv);
			var subrows = $('tr.subrow[data-parentrowid="' + rowId + '"]');
				// console.log(subrows);
			if (subrows.length===0 ) {
				$elementDiv.show();
				// console.log('aaa');
			}else{
				// console.log('sss');
				$elementDiv.hide();
			}



		});

		// -- Vol Induk
		$(this).on('input','.InputTxtVolInduk', function(event) {
			$(this).val(formatVolume($(this).val()));
		});

		$(document).on('focus', '.InputTxtVolInduk', function() {


			flag='vol';

			if (flag=='vol' && ($(this).val()=='0.000' || $(this).val()=='0' ) ) {
				$(this).val('');
				return;
			}

			var value = $(this).val();
			var newValue = value.replace(/,/g, '');

			if (flag=='vol') {
				newValue = handleTyping(newValue);
			}

			$(this).val(newValue);


		});

		$(document).on('focusout', '.InputTxtVolInduk', function() {

			var volValue = $(this).val();
			if (volValue === '' || isNaN(volValue)) {
				$(this).val('0');
			} else {
				var flag = 'vol'; 
				if (volValue == '') {
					if (flag == 'vol') {
						$(this).val('0.000');
					}
				} else {
					if (flag == 'vol') {
						var vol = parseFloat(volValue);
						vol = Number(vol).toFixed(3);
						vol = formatNumberWithCommas(vol);
						$(this).val(vol);
					}
				}
			}

			KalkulasiVolInduk_dengan_HrgSatuan(this);
			getSubTotal();

		});


		// -- PPN Persen
		$(this).on('input','.InputTxtPpnPersenInduk',function(event){
			var inputValue = $(this).val();

			if (inputValue < -100) {
				$(this).val(-100);
				return false;
			}
			if (inputValue > 100) {
				$(this).val(100);
				return false;
			}

			var filteredValue = inputValue.replace(/[^0-9.-]/g, '');

			var parts = filteredValue.split('.');
			if (parts.length > 1) {
				parts[1] = parts[1].substr(0, 2);
				filteredValue = parts.join('.');
			}

			if (filteredValue.indexOf('-') > 0) {
				filteredValue = filteredValue.replace(/-/g, '');
				filteredValue = '-' + filteredValue;
			}

			$(this).val(filteredValue);
		});

		$(document).on('focus','.InputTxtPpnPersenInduk',function(){
			if (this.value == '0') {
				$(this).val('');
			}
		});

		$(document).on('focusout', '.InputTxtPpnPersenInduk', function() {

			var ppnValue = $(this).val();
			if (ppnValue === '' || isNaN(ppnValue)) {
				$(this).val('0');
			} 

			HitungPPNNominal(this);
			getSubTotal();

		});

		$(document).on('click', '.btn-hapus-row-ini', function() {
			var rowId = $(this).closest('tr').attr('rowId');
			var duplicateExists = false;
			$('#tableRincianKoDtl tbody tr').each(function() {

				var rowParentRowId = $(this).attr('data-parentrowid');

				if (rowParentRowId === rowId) {
					duplicateExists = true;
					return false; 
				}
			});

			if (duplicateExists) {
				alert('hapus detail dari baris ini terlebih dahulu');

			} else {
				$(this).closest('tr').remove();
				updateRowNumbers(); 
			}



			pengembalianKodeRAP();
			getSubTotal();


		});

		$(this).on('input','.Vol_subrow', function(event) {
			$(this).val(formatVolume($(this).val()));
		});

		$(document).on('focus', '.Vol_subrow', function() {
			flag='vol';

			if (flag=='vol' && ($(this).val()=='0.000' || $(this).val()=='0' ) ) {
				$(this).val('');
				return;
			}

			if (flag=='hrgsatuan' && $(this).val()=='0') {
				$(this).val('');
				return;
			}

			var value = $(this).val();
			var newValue = value.replace(/,/g, '');

			if (flag=='vol') {
				newValue = handleTyping(newValue);
			}

			$(this).val(newValue);


		});

		$(document).on('focusout', '.Vol_subrow', function() {
			var rowId = $(this).closest('tr').attr('data-parentrowid');
			var volValue = $(this).val();
			if (volValue === '' || isNaN(volValue)) {
				$(this).val('0');
			} else {
				var flag = 'vol'; 
				if (volValue == '') {
					if (flag == 'vol') {
						$(this).val('0.000');
					}
				} else {
					if (flag == 'vol') {
						var vol = parseFloat(volValue);
						vol = Number(vol).toFixed(3);
						vol = formatNumberWithCommas(vol);
						$(this).val(vol);
					}
				}
			}

			calculateSubrowTotal(this);
			HitungTotal_SubRow(rowId);
		});

		$(document).on('click','.btn_simpan_all',function(){
			var ArrayPenampung = [];
			var ArrayUtama = [];

			var VendorId 	= $('#VendorId option:selected').val();
			var TglKO 		= $('#TglKO').val();
			var TxtUsaha 	= $('#TxtUsaha').val();
			var KatVen 		= $('#KatVen option:selected').val();
			var SistemPembayaran = $('#SistemPembayaran option:selected').val();

			var TxtSubTotal_JumlahHarga = $('.TxtSubTotal_JumlahHarga').text();
			TxtSubTotal_JumlahHarga = JadikanParseFloat(TxtSubTotal_JumlahHarga);

			var TxtSubTotal_TotalPPNNominal = $('.TxtSubTotal_TotalPPNNominal').text();
			TxtSubTotal_TotalPPNNominal = JadikanParseFloat(TxtSubTotal_TotalPPNNominal);

			var CheckBoxK3 =  ($('#CheckBoxK3').is(':checked')) ? 1 : 0;
			var TxtSyaratTeknis = $('#TxtSyaratTeknis').val();
			var TxtJadwalPengiriman = $('#TxtJadwalPengiriman').val();
			var TxtJadwalPembayaran = $('#TxtJadwalPembayaran').val();
			
			var TxtSyaratPembayaran = Validasi_SyaratPembayaran();
			var TxtSanksi = $('#TxtSanksi').val();
			var TxtKeterangan = $('#TxtKeterangan').val();
			var KategoriId	= 'Kontrak';
			var tableContent = $('#tableRincianKoDtl tbody');


			if (VendorId=='') {
				alert('belum pilih ID');
				return false;
			}

			if (KatVen=='') {
				alert('Belum pilih Kategori Vendor.');
				return false;
			}

			if (SistemPembayaran==='') {
				alert('Belum pilih Sistem Pembayaran.');
				return false;
			}

			if (TxtSyaratPembayaran==='') {
				alert('Belum pilih syarat pembayaran.');
				return false;
			}

			if (tableContent.find('tr').length === 0) {
				alert('Belum input rincian pekerjaan.');
				return false;
			}


			var mydata = {
				VendorId 				: VendorId,
				TglKO 					: TglKO,
				JobNo 					: JobNo,
				SubTotal 				: TxtSubTotal_JumlahHarga,
				PPN 					: TxtSubTotal_TotalPPNNominal,
				K3 						: CheckBoxK3,
				SyaratTeknis 			: TxtSyaratTeknis,
				JadwalPengiriman 		: TxtJadwalPengiriman,
				JadwalPembayaran 		: TxtJadwalPembayaran,
				SyaratPembayaran 		: TxtSyaratPembayaran,
				Sanksi 					: TxtSanksi,
				Keterangan 				: TxtKeterangan,
				KategoriId 				: 'Kontrak',
				BeginningTotal 			: TxtSubTotal_JumlahHarga,
				KategoriVendor 			: KatVen,
				SystemPembayaran 		: SistemPembayaran,

			};

			tableContent.find('tr.rowInduk').each(function() {
				var rowData 		= {};
				var columns 		= $(this).find('td');

				var VolInduk_param 		= columns.eq(4).find('.InputTxtVolInduk').val();
				var PpnPersen_param 	= columns.eq(7).find('.InputTxtPpnPersenInduk').val();
				var hargasatuan_param 	= JadikanParseFloat(columns.eq(5).text());
				var JumlahHarga_param 	= JadikanParseFloat(columns.eq(6).text());
				var ppnnominal_param 	= JadikanParseFloat(columns.eq(8).text());
				var ttlppnnominal_param = JadikanParseFloat(columns.eq(9).text());

				var elementTglPengiriman = columns.eq(11).find('.tglPengirimanInduk').val();
				elementTglPengiriman = elementTglPengiriman.split(' to ');

				var PeriodePengiriman1_Induk = elementTglPengiriman[0];
				var PeriodePengiriman2_Induk = elementTglPengiriman[1];

				var elementTglPembayaran = columns.eq(11).find('.tglPembayaranInduk').val();
				elementTglPembayaran = elementTglPembayaran.split(' to ');

				var PeriodePembayaran1_Induk = elementTglPembayaran[0];
				var PeriodePembayaran2_Induk = elementTglPembayaran[1];
				

				rowData.nomor 			= columns.eq(0).text();
				rowData.KdRAP 			= columns.eq(1).text();
				rowData.Uraian 			= columns.eq(2).text();
				rowData.Uom 			= columns.eq(3).text();
				rowData.Vol 			= VolInduk_param;
				rowData.HrgSatuan 		= hargasatuan_param;
				rowData.JumlahHarga 	= JumlahHarga_param;
				rowData.PPNPersen 		= PpnPersen_param;
				rowData.PPNNominal 		= ppnnominal_param;
				rowData.TotalPPNNominal = ttlppnnominal_param;
				rowData.Tipe 			= columns.eq(10).text();

				rowData.PeriodePengiriman1_Induk = PeriodePengiriman1_Induk;
				rowData.PeriodePengiriman2_Induk = PeriodePengiriman2_Induk;

				rowData.PeriodePembayaran1_Induk = PeriodePembayaran1_Induk;
				rowData.PeriodePembayaran2_Induk = PeriodePembayaran2_Induk;


				var findSubRows 		= $('tr.subrow[data-parentrowid="' + rowData.KdRAP + '"]');
				var findSubRows_Total 	= $('tr.subrow-total[data-subrow_total="' + rowData.KdRAP + '"]');

				var subRowsArray = [];

				if (findSubRows.length>0) {

					findSubRows_Total.each(function() {
						var getColumns = $(this).find('td.txt_total_vol_subrow');
						var getColumns_total = $(this).find('td.txt_total_subrow');
						var txt_total_vol_subrow = 0;
						var txt_total_subrow = 0;
						txt_total_vol_subrow = getColumns.text();
						txt_total_subrow = getColumns_total.text();

						var peringatan_subrow = 0;

						if (Cek_Uom_LS(columns.eq(3).text())===false) {

							if (validasiVolInduk_dengan_total_VolSubRow(VolInduk_param,txt_total_vol_subrow)==='lebihbesar') {
								$(this).addClass('marked-row');

								var $popoverTrigger = $(this).find('td.txt_total_vol_subrow');

								$(this).find('td.txt_total_vol_subrow').addClass('border-red');

								$popoverTrigger.attr('data-toggle', 'popover');
								$popoverTrigger.attr('title', 'Vol > Vol Induk');

								$popoverTrigger.popover({
									trigger: 'manual', 
									container: 'body', 
									placement: 'right', 
									template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3></div>'
								}).popover('show');

								$('html, body').animate({
									scrollTop: $popoverTrigger.offset().top
								}, 1000);

								peringatan_subrow = 1;
							} else {
								$(this).removeClass('marked-row');
								$(this).find('td.txt_total_vol_subrow').removeClass('border-red');
								$(this).find('td.txt_total_vol_subrow').popover('destroy');
								peringatan_subrow = 0;
							}
						}

						if (Validasi_JumlahHargaInduk_dengan_JumlahHargaSubRow(columns.eq(6).text(),txt_total_subrow)==='lebihbesar') {
							$(this).addClass('marked-row');

							var $popoverTrigger = $(this).find('td.txt_total_subrow');

							$(this).find('td.txt_total_subrow').addClass('border-red');

							$popoverTrigger.attr('data-toggle', 'popover');
							$popoverTrigger.attr('title', 'Total > Jumlah Harga Induk');

							$popoverTrigger.popover({
								trigger: 'manual', 
								container: 'body', 
								placement: 'right', 
								template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3></div>'
							}).popover('show');

							$('html, body').animate({
								scrollTop: $popoverTrigger.offset().top
							}, 1000);

							peringatan_subrow = 1;
						} else {
							$(this).removeClass('marked-row');
							$(this).find('td.txt_total_subrow').removeClass('border-red');
							$(this).find('td.txt_total_subrow').popover('destroy');
							peringatan_subrow = 0;
						}


						if (peringatan_subrow===1) {
							return false;
						}


					});

					findSubRows.each(function() {
						var subRowData = {};
						var subColumns = $(this).find('td');

						subRowData.KdRAP_subrow 		= columns.eq(1).text();

						subRowData.Uom_subrow 			= columns.eq(3).text();
						subRowData.Vol_subrow 			= JadikanParseFloat(subColumns.find('.Vol_subrow').val());
						subRowData.HrgSatuan_subrow 	= JadikanParseFloat(subColumns.find('.HrgSatuan_subrow').val());
						subRowData.JumlahHarga_subrow 	= JadikanParseFloat(subColumns.find('.JumlahHarga_subrow').val());
						subRowData.TglPengiriman_subrow = subColumns.find('.TglPengiriman_subrow').val();
						subRowData.TglPembayaran_subrow = subColumns.find('.TglPembayaran_subrow').val();
						subRowsArray.push(subRowData);
					});
				}

				rowData.subrows = subRowsArray;
				ArrayPenampung.push(rowData);
			});

mydata.RincianPekerjaan = ArrayPenampung;
ArrayUtama.push(mydata);

// var btn = $(this);
// var btnText = btn.find('.btn-text');
// var btnIcon = btn.find('.btn-icon');

$(this).attr('disabled',true).text('Loading...');

        // Tampilkan ikon loading dan sembunyikan teks tombol
// btnText.hide();
// btnIcon.show();


$.ajax({
	url: myurl+'CreateNewKO_To_Database',
	type: 'POST',
	dataType: 'json',
	data: {mydata :ArrayUtama },
	beforeSend : function(){
	},
	success: function(data) {
		// console.log(response);
		// alert(response)
		// btnIcon.hide();
		// btnText.show();
		$(this).attr('disabled',false).text('Simpan');
		if (data.pesan == 'berhasil') {
			window.location.href = myurl_2+JobNo;
			return false;
		} else {
			alert(data.pesan);
		}
	},
	error: function(xhr, status, error) {
		console.error('Terjadi kesalahan:', status, error);
		$(this).attr('disabled',false).text('Simpan');
		// btnIcon.hide();
		// btnText.show();
	}
});
});




function Vendor_Berganti() {
	var VendorId = $('#FormKO #VendorId option:selected').val();
	$.ajax({
		url: myurl+'Vendor_Berganti',
		type: "POST",
		dataType: 'JSON',
		data: {
			VendorId: VendorId
		},
		beforeSend: function() {
			$('#FormKO #VendorId').attr('disabled', true);
		},
		complete: function() {
			$('#FormKO #VendorId').attr('disabled', false);
		}
	})
	.done(function(data) {

				// KOSONGKAN TERLEBIH DAHULU
		$('#FormKO #TxtNama').val('');
		$('#FormKO #TxtAlamat').val('');
		$('#FormKO #TxtTelepon').val('');
		$('#FormKO #TxtNPWP').val('');

		$('#FormKO #TxtKategori').val('');
		$('#FormKO #TxtUsaha').val('');

				// ISI DARI DATA YANG DI DAPAT
		$('#FormKO #TxtNama').val(data.TxtNama);
		$('#FormKO #TxtAlamat').val(data.TxtAlamat);
		$('#FormKO #TxtTelepon').val(data.TxtTelepon);
		$('#FormKO #TxtNPWP').val(data.TxtNPWP);

		$('#FormKO #TxtKategori').val(data.TxtKategori);
		$('#FormKO #TxtUsaha').val(data.TxtUsaha);
	})
	.fail(function() {
		alert('Error');
	});

}

function populateTable(rowData){
	var tbodyRincian = $('#tableRincianKoDtl tbody');
	var rowHtml = `<tr rowId="${rowData.KdRAP}" class="rowInduk">
	<td class="text-center ">${rowNum}</td> 
	<td class="text-left">${rowData.KdRAP}</td>
	<td>${rowData.Uraian}</td>
	<td class="text-center">${rowData.Uom}</td>
	<td class="text-right MyVolInduk"><input type="text" class="text-right InputTxtVolInduk" value="${rowData.Vol}" /></td>
	<td class="text-right">${rowData.HrgSatuan}</td>
	<td class="text-right TxtJumlahHargaInduk">${rowData.JumlahHarga}</td>
	<td class="text-right MyPPNPersenInduk"><input type="text" class="text-right InputTxtPpnPersenInduk" value="${rowData.PPNPersen}" /></td>
	<td class="text-right MyPPNNominalInduk">${rowData.PPNNominal}</td>
	<td class="text-right MyTtlPPNNominalInduk">${rowData.TotalPPNNominal}</td>
	
	<td hidden>${rowData.Tipe}</td>
	<td class="text-center">

	<div class="row">

	<div class="col-md-4 divtglPengirimanInduk" >
	<label class="">Tgl Pengiriman</label>
	<div class="input-group-addon ">
	<i class="fa fa-calendar"></i>
	</div>
	<input type="text" class="form-control input-sm pull-right datePeriode tglPengirimanInduk " >
	</div>

	<div class="col-md-4 divtglPengirimanInduk " >
	<label class="">Tgl Pembayaran</label>
	<div class="input-group-addon ">
	<i class="fa fa-calendar"></i>
	</div>
	<input type="text" class="form-control input-sm pull-right datePeriode tglPembayaranInduk ">
	</div>

	<div class="col-md-4 text-center" style="vertical-align:middle;">
	<button type="button" class="btn btn-primary btn-sm btn-add-subrow"><i class="fa fa-plus"></i></button>
	<button type="button" class="btn btn-danger btn-sm btn-hapus-row-ini"><i class="fa fa-minus"></i></button>
	</div>
	</div>
	</td>


	
	</tr>`;
	tbodyRincian.append(rowHtml);
	pengecekanDuplicate_KodeRAP();
	updateRowNumbers();

	$('tr.rowInduk[rowId="' + rowData.KdRAP + '"]').find('.datePeriode').daterangepicker({
		opens: 'left', 
		startDate: new Date(),
		endDate: moment().add(1, 'months').toDate(), 
		locale: {
			format: 'YYYY-MM-DD', 
			separator: ' to ', 
			applyLabel: 'Pilih', 
			cancelLabel: 'Batal', 
			fromLabel: 'Dari', 
			toLabel: 'Hingga', 
			customRangeLabel: 'Pilih Rentang Tanggal' 
		}
	}).on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
		$(this).closest('.input-group').find('.btn.applyBtn').text('Pilih');
	});


	// cekTglPengiriman()
}

function Cek_Uom_LS(input) {
	var lowercaseInput = input.toLowerCase();

	if (lowercaseInput.indexOf('ls') !== -1) {
		return true;
	} else if (lowercaseInput.startsWith('ls')) {
		return true;
	}else{
		return false;
	}

}

function validasiVolInduk_dengan_total_VolSubRow(VolInduk,TotalVolSubRow){
	var VolInduk_1 = JadikanParseFloat(VolInduk);
	var TotalVolSubRow_1 = JadikanParseFloat(TotalVolSubRow);
	if (TotalVolSubRow_1>VolInduk_1) {
		return 'lebihbesar';
	}else{
		return 'lebihkecil';
	}

}

function Validasi_JumlahHargaInduk_dengan_JumlahHargaSubRow(JumlahHargaInduk,JumlahHargaSubRow){
	var JumlahHargaInduk_1 = JadikanParseFloat(JumlahHargaInduk);
	var JumlahHargaSubRow_1 = JadikanParseFloat(JumlahHargaSubRow);
	if (JumlahHargaSubRow_1>JumlahHargaInduk_1) {
		return 'lebihbesar';
	}else{
		return 'lebihkecil';
	}

}

function JadikanParseFloat(volValue){

	var nilai = parseFloat(volValue.replace(/,/g, ''));

	if (isNaN(nilai)) {
		return 0;
	}else{
		return nilai;
	}
}

function AddBaris_TotalDariSubrow(){
	var rowId = $(this).closest('tr').attr('rowId');
	var subrows = $('tr.subrow[data-parentrowid="' + rowId + '"]');
	var baris_row_total = $('tr.subrow-total[data-subrow_total="' + rowId + '"]');

	var html_subrow_total = `
	<tr rowId="${rowId}" style="border:1px solid silver;background-color:#ecf0f1;color:black;" class="subrow-total" data-subrow_total="${rowId}">
	<td colspan="4" class="text-right text-bold">Total</td>
	<td class="text-right text-bold txt_total_vol_subrow">0</td>
	<td></td>
	<td class="text-right text-bold txt_total_subrow">0</td>
	<td colspan="4"></td>
	</tr>`;

	if (baris_row_total.length===0) {
		subrows.last().after(html_subrow_total);
	}

}

function HitungTotal_SubRow(rowId){

	var txt_total_subrow = 0;
	var txt_total_vol_subrow = 0;

	if ($('tr.subrow[data-parentrowid="' + rowId + '"]').length>0) {
		$('tr.subrow[data-parentrowid="' + rowId + '"]').each(function() {
			$(this).find('input[name="JumlahHarga_subrow[]"]').each(function(){
				var value = $(this).val();
				value = value.replace(/,/g, '');
				value = parseFloat(value);
				txt_total_subrow += value;
			});
		});

		$('tr.subrow[data-parentrowid="' + rowId + '"]').each(function() {
			$(this).find('input[name="Vol_subrow[]"]').each(function(){
				var value = $(this).val();
				value = value.replace(/,/g, '');
				value = parseFloat(value);
				txt_total_vol_subrow += value;
			});
		});

		$('tr.subrow-total[data-subrow_total="' + rowId + '"]').find('.txt_total_subrow').text(formatNumberWithCommas(txt_total_subrow));

		$('tr.subrow-total[data-subrow_total="' + rowId + '"]').find('.txt_total_vol_subrow').text(Format_Angka_Khusus_Volume(txt_total_vol_subrow));
	}

}

function Format_Angka_Khusus_Volume(value){
	var vol = parseFloat(value);
	vol = Number(vol).toFixed(3);
	vol = formatNumberWithCommas(vol);
	return vol;
}

function hapus_juga_row_total(rowId){
	if ($('tr.subrow[data-parentrowid="' + rowId + '"]').length===0){
		$('tr.subrow-total[data-subrow_total="' + rowId + '"]').remove();
	}
}


function KalkulasiVolInduk_dengan_HrgSatuan(element){
	var volValue = $(element).val();
	var hrgSatuanValue = $(element).closest('tr').find('td').eq(5).text();
	var PPNPersenValue = $(element).closest('tr').find('td').eq(7).find('.InputTxtPpnPersenInduk').val();
	volValue = parseFloat(volValue.replace(/,/g, ''));
	hrgSatuanValue = parseFloat(hrgSatuanValue.replace(/,/g, ''));
	if (isNaN(volValue) || isNaN(hrgSatuanValue)) {
		$(element).closest('tr').find('td').eq(6).text('0');
	} else {
		var jumlahHarga = volValue * hrgSatuanValue;
		var ppnNominal = hrgSatuanValue * (PPNPersenValue / 100);
		var totalPpnNominal = jumlahHarga * (PPNPersenValue / 100);

		$(element).closest('tr').find('td').eq(6).text(formatNumberWithCommasAndRound(jumlahHarga));
		$(element).closest('tr').find('td').eq(8).text(formatNumberWithCommasAndRound(ppnNominal));


		$(element).closest('tr').find('td').eq(9).text(formatNumberWithCommasAndRound(totalPpnNominal));
	}

}

function HitungPPNNominal(element){
	var ppnNominal 		= 0;
	var totalppn 		= 0;

	var jumlahharga 	= $(element).closest('tr').find('td').eq(6).text();
	var hargasatuan 	= $(element).closest('tr').find('td').eq(5).text();
	var ppnpersen 		= $(element).val();

	jumlahharga 		= parseFloat(jumlahharga.replace(/,/g, ''));

	ppnNominal 			= hargasatuan * (ppnpersen/100);
	totalppn 			= jumlahharga * (ppnpersen/100);

	$(element).closest('tr').find('td').eq(8).text(formatNumberWithCommasAndRound(ppnNominal));
	$(element).closest('tr').find('td').eq(9).text(formatNumberWithCommasAndRound(totalppn));
}


function calculateSubrowTotal(element) {
	var volValue = $(element).val();
	var hrgSatuanValue = $(element).closest('tr').find('.HrgSatuan_subrow').val();
	volValue = parseFloat(volValue.replace(/,/g, ''));
	hrgSatuanValue = parseFloat(hrgSatuanValue.replace(/,/g, ''));
	if (isNaN(volValue) || isNaN(hrgSatuanValue)) {
		$(element).closest('tr').find('.JumlahHarga_subrow').val('0');
	} else {
		var jumlahHarga = volValue * hrgSatuanValue;
		$(element).closest('tr').find('.JumlahHarga_subrow').val(formatNumberWithCommasAndRound(jumlahHarga));
	}
}

function updateRowNumbers() {
	var rowCount = 1;
	$('#tableRincianKoDtl tbody tr.rowInduk').each(function() {
		$(this).find('td:first').text(rowCount); 
		rowCount++;
	});
	rowNum = rowCount;
}

function pengecekanDuplicate_KodeRAP(){
	$('#tableRincianKoDtl tbody tr').each(function() {
		var rowId = $(this).attr('rowId').trim();
		kodeRAPs.push(rowId);
		opsiYangDihapus.push($('#table_input_rap #KdRAP option[value="' + rowId + '"]').detach());
	});
}

function pengembalianKodeRAP(){
	opsiYangDihapus.forEach(function(opsi) {
		$('#table_input_rap #KdRAP').append(opsi);
	});
}

function UntukVol(inputElement) {
	inputElement.on('input', function(e) {
		$(this).val(formatVolume($(this).val()));
	});

	inputElement.focusin(function() {
		handleFocus.call(this, 'vol');
	});

	inputElement.focusout(function() {
		handleBlur.call(this, 'vol');
		calculateTotal()
	});
}

function UntukHargaSatuan(inputElement) {
	inputElement.on('input', function(e) {
		var filteredValue = formatInputValue($(this).val());
		$(this).val(filteredValue);
	});

	inputElement.focusin(function() {
		handleFocus.call(this, 'hrgsatuan');
	});

	inputElement.focusout(function() {
		handleBlur.call(this, 'hrgsatuan');
		calculateTotal()
	});
}

function calculateTotal() {
	var volValue = $('#table_input_rap #Vol').val();
	volValue = volValue ? volValue.replace(/,/g, '') : '';
	volValue = volValue === '' || volValue === '0' || volValue === '0.000' ? 0 : parseFloat(volValue);

	var hrgSatuanValue = $('#table_input_rap #HrgSatuan').val();
	hrgSatuanValue = hrgSatuanValue ? hrgSatuanValue.replace(/,/g, '') : '';
	hrgSatuanValue = hrgSatuanValue === '' || hrgSatuanValue === '0' ? 0 : parseFloat(hrgSatuanValue);
            // console.log(volValue+' - '+hrgSatuanValue);
	if (volValue === 0 || hrgSatuanValue === 0) {
		$('#table_input_rap #JumlahHarga').val(0);
	} else {
		var total = volValue * hrgSatuanValue;
		$('#table_input_rap #JumlahHarga').val(formatNumberWithCommas(total));
	}

	calculatePPN()
}

function calculatePPN() {
	var ppnPersen = parseFloat($('#table_input_rap #PPNPersen').val());
	var hrgSatuan = parseFloat($('#table_input_rap #HrgSatuan').val().replace(/,/g, ''));
	var jumlahHarga = parseFloat($('#table_input_rap #JumlahHarga').val().replace(/,/g, ''));

	if (isNaN(ppnPersen) || isNaN(hrgSatuan) || isNaN(jumlahHarga)) {
		$('#table_input_rap #PPNNominal').val(0);
		$('#table_input_rap #TotalPPNNominal').val(0);
		return;
	}

	var ppnNominal = hrgSatuan * (ppnPersen / 100);
	var totalPpnNominal = jumlahHarga * (ppnPersen / 100);

			// $('#table_input_rap #PPNNominal').val(formatNumberWithCommasAndRound(ppnNominal));
			// $('#table_input_rap #TotalPPNNominal').val(formatNumberWithCommasAndRound(totalPpnNominal));
			// $('#table_input_rap #PPNNominal').val(ppnNominal);

	totalPpnNominal = Math.round(totalPpnNominal);
	$('#table_input_rap #PPNNominal').val(formatNumberWithCommasAndRound(ppnNominal));
	$('#table_input_rap #TotalPPNNominal').val(totalPpnNominal.toLocaleString());
}

function untukPPNPersen(inputElement) {
	inputElement.on('input', function(e) {
		var inputValue = $(this).val();

		if (inputValue < -100) {
			$(this).val(-100);
			return false;
		}
		if (inputValue > 100) {
			$(this).val(100);
			return false;
		}

		var filteredValue = inputValue.replace(/[^0-9.-]/g, '');

		var parts = filteredValue.split('.');
		if (parts.length > 1) {
			parts[1] = parts[1].substr(0, 2);
			filteredValue = parts.join('.');
		}

		if (filteredValue.indexOf('-') > 0) {
			filteredValue = filteredValue.replace(/-/g, '');
			filteredValue = '-' + filteredValue;
		}

		$(this).val(filteredValue);
	});

	inputElement.focusin(function() {
		if (this.value == '0') {
			$(this).val('');
		}
	});

	inputElement.focusout(function() {
		if (this.value == '') {
			$(this).val('0');
		}

		calculatePPN();
	});
}

function formatVolume(inputValue) {
	var filteredValue = inputValue.replace(/[^0-9.-]/g, '');

	if (filteredValue.indexOf('-') > 0) {
		filteredValue = filteredValue.replace(/-/g, '');
		filteredValue = '-' + filteredValue;
	}

	if (filteredValue.indexOf('.') === 0) {
		filteredValue = '0' + filteredValue;
	}

	var parts = filteredValue.split('.');
	if (parts[1] && parts[1].length > 3) {
		parts[1] = parts[1].substr(0, 3);
		filteredValue = parts.join('.');
	}

	return filteredValue;
}

function formatInputValue(inputValue) {
	var filteredValue = inputValue.replace(/[^0-9.-]/g, '');

	if (filteredValue.indexOf('-') > 0) {
		filteredValue = filteredValue.replace(/-/g, '');
		filteredValue = '-' + filteredValue;
	}

	if (filteredValue.indexOf('.') === 0) {
		filteredValue = '0' + filteredValue;
	}

	var parts = filteredValue.split('.');
	if (parts[1] && parts[1].length > 2) {
		parts[1] = parts[1].substr(0, 2);
		filteredValue = parts.join('.');
	}

	return filteredValue;
}

function handleFocus(flag='') {
	if (flag=='vol' && ($(this).val()=='0.000' || $(this).val()=='0' ) ) {
		$(this).val('');
		return;
	}

	if (flag=='hrgsatuan' && $(this).val()=='0') {
		$(this).val('');
		return;
	}

	var value = $(this).val();
	var newValue = value.replace(/,/g, '');

	if (flag=='vol') {
		newValue = handleTyping(newValue);
	}

	$(this).val(newValue);
}

function handleTyping(value) {
	var parts = value.split('.');
	var integerValue = parts[0];
	var decimalValue = parts[1];

	if (decimalValue && decimalValue.length === 3 && parseInt(decimalValue) === 0) {
		return integerValue;
	} else {

		var newValue = integerValue;
		if (decimalValue) {
			newValue += '.' + decimalValue;
		}
		return newValue;
	}
}

function handleBlur(flag) {
	if (this.value=='' ) {

		if (flag=='vol') {
			$(this).val('0.000');
		}
		if (flag=='hrgsatuan') {
			$(this).val(0);
		}

	} else {

		if (flag=='vol') {
			var vol = parseFloat(this.value);
			vol = Number(vol).toFixed(3);
			vol = formatNumberWithCommas(vol);
			$(this).val(vol);
		}

		if (flag=='hrgsatuan') {
			var hargaSatuan = parseFloat(this.value);
			hargaSatuan = Math.round(hargaSatuan);
			hargaSatuan = formatNumberWithCommas(hargaSatuan);
			$(this).val(hargaSatuan);
		}
	}

}

function formatNumberWithCommas(num, decimalPlaces) {
	var parts = num.toString().split(".");
	parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	if (decimalPlaces && parts[1]) {
		parts[1] = parts[1].substr(0, decimalPlaces);
	}
	return parts.join(".");
}

function formatNumberWithCommasAndRound(num) {
	var numberString = num.toString().replace(/[^0-9.]/g, '');
	var parts = numberString.split('.');
	var integerPart = parts[0];
	var decimalPart = parts[1] || '';

	var integerWithCommas = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
	if (decimalPart !== '') {
		var roundedDecimal = Math.round(parseFloat('0.' + decimalPart) * 100) / 100;
		if (roundedDecimal === 1) {
			integerWithCommas = (parseInt(integerWithCommas) + 1).toString();
			roundedDecimal = 0;
		}
		return integerWithCommas + (roundedDecimal !== 0 ? '.' + roundedDecimal.toString().slice(2) : '');
	} else {
		return integerWithCommas;
	}
}

function sanitizeInput(input) {
	return encodeURIComponent(input);
}


function ClearDataForm(){
	$('#table_input_rap #Uraian').val('');
	$('#table_input_rap #Tipe').val('');
	$('#table_input_rap #Uraian').val('');
	$('#table_input_rap #Uom').val('');
	$('#table_input_rap #Vol').val('0');
	$('#table_input_rap #HrgSatuan').val('0');
	$('#table_input_rap #JumlahHarga').val('0');
	$('#table_input_rap #PPNPersen').val('0');
	$('#table_input_rap #PPNNominal').val('0');
	$('#table_input_rap #TotalPPNNominal').val('0');
}

function getSubTotal(){
	var tableContent = $('#tableRincianKoDtl tbody tr');

	var SubTotalJumlahHarga = 0;
	var SubTotalTtlPPN = 0;

	$('#tableRincianKoDtl tbody tr.rowInduk').each(function() {
		var JumlahHarga_Induk = $(this).find('td').eq(6).text();
		var TotalPPNNominal_Induk = $(this).find('td').eq(9).text();

		var JumlahHarga_Induk_1 = parseFloat(JumlahHarga_Induk.replace(/,/g, ''));
		var TotalPPNNominal_Induk_1 = parseFloat(TotalPPNNominal_Induk.replace(/,/g, ''));

		SubTotalJumlahHarga += JumlahHarga_Induk_1;
		SubTotalTtlPPN += TotalPPNNominal_Induk_1;

	});

	$('.TxtSubTotal_JumlahHarga').text(formatNumberWithCommas(SubTotalJumlahHarga));
	$('.TxtSubTotal_TotalPPNNominal').text(formatNumberWithCommas(SubTotalTtlPPN));
	getGrandTotal();

}


function getGrandTotal(){
	var Hitung = 0;

	var TxtSubTotal_JumlahHarga = $('.TxtSubTotal_JumlahHarga').text();
	var TxtSubTotal_TotalPPNNominal = $('.TxtSubTotal_TotalPPNNominal').text();
	var TxtTtlPengurang = $('#TxtTtlPengurang').val();

	var TxtSubTotal_JumlahHarga_1 = parseFloat(TxtSubTotal_JumlahHarga.replace(/,/g, ''));
	var TxtSubTotal_TotalPPNNominal = parseFloat(TxtSubTotal_TotalPPNNominal.replace(/,/g, ''));
	var TxtTtlPengurang_1 = parseFloat(TxtTtlPengurang.replace(/,/g, ''));

	Hitung = (TxtSubTotal_JumlahHarga_1 + TxtSubTotal_TotalPPNNominal) - TxtTtlPengurang_1;
	$('#TxtTotal').val(formatNumberWithCommas(Hitung));
}

function Validasi_SyaratPembayaran(){
	var data_CB = "";

	if ($('#CBInvoice').is(':checked')) {
		data_CB = (data_CB == "") ? "INV" : data_CB + "," + "INV";
	}
	if ($('#CBSJ').is(':checked')) {
		data_CB = (data_CB == "") ? "SJ" : data_CB + "," + "SJ";
	}
	if ($('#CBFP').is(':checked')) {
		data_CB = (data_CB == "") ? "FP" : data_CB + "," + "FP";
	}
	if ($('#CBBayar').is(':checked')) {
		data_CB = (data_CB == "") ? "BAP" : data_CB + "," + "BAP";
	}
	if ($('#CBPO').is(':checked')) {
		data_CB = (data_CB == "") ? "PO" : data_CB + "," + "PO";
	}
	if ($('#CBFisik').is(':checked')) {
		data_CB = (data_CB == "") ? "BAOP" : data_CB + "," + "BAOP";
	}

	return data_CB;
}


});




</script>
