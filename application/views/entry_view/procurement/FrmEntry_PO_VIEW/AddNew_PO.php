<style type="text/css">
	body {
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
	}

	.huruf13 {
		font-size: 12px !important;
	}

	.huruf13 {
		font-size: 13px !important;
	}

	.jarak {
		padding-top: 3px;
	}

	.jarak_margin {
		margin-top: 3px;
	}

	#table_order {
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
	}

	#table_order thead tr th {
		text-align: center;
		vertical-align: middle;
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
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1><u><?= $title ?></u></h1>

	</section>
	<section class="content">
		<a href="<?= site_url('Kontrak_po/index/' . $JobNo_redirect) ?>" class="btn btn-danger text-bold"><i class="fa fa-arrow-left"></i> BACK</a>
		<form id="FormPO" data-JobNo_redirect="<?= $JobNo_redirect ?>">
			<div class="box box-primary" style="margin-top:3px;">

				<!-- == START FORM PO -->
				<div class="box-header">

					<div class="row">


						<div class="col-sm-4 ">
							<table border="0" width="100%" style="margin-bottom:5px;">
								<tr>
									<th class="text-center " bgcolor="silver" style="padding-top:5px;padding-bottom: 5px;font-size: 14px;">PENERIMA PO</th>
								</tr>
							</table>

							<table border="0" width="100%" class="huruf13">
								<tr>
									<td width="50px">ID</td>
									<td width="10px" class="text-center">:</td>
									<td>

										<select name="VendorId" id="VendorId" class="form-control input-sm select2" required>
											<option value="">:: Pilih Salah Satu ::</option>
											<?php foreach ($dataVendor as $rowVendor) : ?>
												<option value="<?= $rowVendor->VendorId ?>"><?= $rowVendor->VendorNm ?> - <?= $rowVendor->VendorId ?></option>
											<?php endforeach ?>
										</select>
									</td>
								</tr>

								<tr>
									<td width="50px" class="jarak">Nama</td>
									<td width="10px" class="text-center jarak">:</td>
									<td class="jarak">
										<input type="text" name="TxtNama" id="TxtNama" class="form-control input-sm" placeholder="Nama" readonly>
									</td>
								</tr>

								<tr>
									<td width="50px" class="jarak">Alamat</td>
									<td width="10px" class="text-center jarak">:</td>
									<td class="jarak">
										<input type="text" name="TxtAlamat" id="TxtAlamat" class="form-control input-sm" placeholder="Alamat" readonly>
									</td>
								</tr>

								<tr>
									<td width="50px" class="jarak">Telepon</td>
									<td width="10px" class="text-center jarak">:</td>
									<td class="jarak">
										<input type="text" name="TxtTelepon" id="TxtTelepon" class="form-control input-sm" placeholder="Telepon" readonly>
									</td>
								</tr>

								<tr>
									<td width="50px" class="jarak">NPWP</td>
									<td width="10px" class="text-center jarak">:</td>
									<td class="jarak">
										<input type="text" name="TxtNPWP" id="TxtNPWP" class="form-control input-sm" placeholder="NPWP" readonly>
									</td>
								</tr>

								<tr>
									<td width="50px" class="jarak">UP</td>
									<td width="10px" class="text-center jarak">:</td>
									<td class="jarak">
										<input type="text" name="TxtUP" id="TxtUP" class="form-control input-sm" placeholder="Isi Disini" readonly>
									</td>
								</tr>

								<tr style="display:none;">
									<td width="50px" class="jarak">Label PPN</td>
									<td width="10px" class="text-center jarak">:</td>
									<td class="jarak">
										<input type="text" id="LblPPN" class="form-control input-sm" placeholder="Isi Disini" readonly>
									</td>
								</tr>

								<tr>
									<td width="100px">Bidang Usaha</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="TxtUsaha" id="TxtUsaha" class="form-control input-sm" placeholder="Isi Disini" readonly>
									</td>
								</tr>


							</table>
						</div>


						<div class="col-sm-4 "> <!-- == START DETAIL -->

							<table border="0" width="100%" style="margin-bottom:5px;">
								<tr>
									<th class="text-center " bgcolor="silver" style="padding-top:5px;padding-bottom: 5px;font-size: 14px;">DETAIL</th>
								</tr>
							</table>
							<table border="0" width="100%" class="huruf13">
								<tr>
									<td width="100px">Tgl PO</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="date" name="TxtTglKO" id="TxtTglKO" class="form-control input-sm" value="<?= date('Y-m-d') ?>" required>
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="huruf13 jarak_margin">
								<tr>
									<td width="100px">No. PO</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="TxtNoKO" id="TxtNoKO" class="form-control input-sm" readonly>
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="huruf13 jarak_margin">
								<tr>
									<td width="100px">Job No</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm" value="<?= $this->LblJobNo . ' - ' . $JobNm ?>" readonly>
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="huruf13 jarak_margin">
								<tr>
									<td width="100px">No. SPR</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<select name="NoSPR" id="NoSPR" class="form-control input-sm select2">
											<option value="">:: Pilih Salah Satu ::</option>
											<?php foreach ($ListSPR as $rowSPR) : ?>
												<option value="<?= $rowSPR->NoSPR ?>"><?= $rowSPR->NoSPR ?> - <?= $rowSPR->UtkPekerjaan ?></option>
											<?php endforeach ?>
										</select>
									</td>
								</tr>
							</table>


							<table border="0" width="100%" class="huruf13 jarak_margin">
								<tr>
									<td width="100px">Kategori Usaha</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="TxtKategori" id="TxtKategori" class="form-control input-sm" placeholder="Isi Disini" readonly>
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="huruf13 jarak_margin">
								<tr>
									<td width="100px">Kategori Vendor</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<select name="KatVen" id="KatVen" class="form-control" required>
											<option value="">--pilih Kategori--</option>
											<option value="Material">Material</option>
											<option value="Upah">Upah</option>
											<option value="Alat">Alat</option>
											<option value="Borong">Borong</option>
										</select>
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="huruf13 jarak_margin">
								<tr>
									<td width="100px">Sistem Pembayaran</td>
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



						</div> <!-- == END DETAIL -->



						<div class="col-sm-4 "> <!-- == START LOKASI PENGIRIMAN -->

							<table border="0" width="100%" style="margin-bottom:5px;">
								<tr>
									<th class="text-center" bgcolor="silver" style="padding-top:5px;padding-bottom: 5px;font-size: 14px;">LOKASI PENGIRIMAN</th>
								</tr>
							</table>
							<table border="0" width="100%" class="huruf13">
								<tr>
									<td width="100px">Nama</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="TxtNamaKirim" id="TxtNamaKirim" class="form-control input-sm" MaxLength="100">
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="huruf13 jarak_margin">
								<tr>
									<td width="100px">Alamat</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<textarea name="TxtAlamatKirim" id="TxtAlamatKirim" class="form-control input-sm" MaxLength="255"></textarea>
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="huruf13 jarak_margin">
								<tr>
									<td width="100px">Telepon</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="TxtTeleponKirim" id="TxtTeleponKirim" class="form-control input-sm" maxlength="20">
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="huruf13 jarak_margin">
								<tr>
									<td width="100px">Tgl Pengiriman</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="date" name="TxtTglKirim" id="TxtTglKirim" class="form-control input-sm">
									</td>
								</tr>
							</table>
							<hr style="border:1px solid silver;">

							<table border="0" width="100%" class="huruf13 jarak_margin">
								<tr>
									<td width="100px"></td>
									<td width="10px" class="text-center"></td>
									<td class="text-right">
										<input type="checkbox" id="CheckBoxMA" name="CheckBoxMA" value="1" class="huruf13">
										<label for="CheckBoxMA" style="font-weight: normal;"> Material Approval</label>

										<input type="checkbox" id="CheckBoxRAP" name="CheckBoxRAP" value="1">
										<label for="CheckBoxRAP" style="font-weight: normal;"> RAP</label>
									</td>
								</tr>
							</table>


						</div> <!-- == END LOKASI PENGIRIMAN -->

					</div>

					<div class="row">
						<div class="col-sm-12">
							<hr style="border:1px solid black;">
							<button type="submit" class="btn btn-default btn-md pull-right" id="btnSimpanPO">SIMPAN</button>
						</div>
					</div>

				</div>


				<div class="box-body">


					<div id="tampil_disini">

					</div>



					<br>

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

			</div>
		</form> <!-- == END FORM PO -->


	</section>

</div>




<div class="clearfix"></div>

<script type="text/javascript" src="<?= base_url('assets/select2_baru.js') ?>"></script>
<script type="text/javascript">
	$(document).ready(function() {

		NoSPR_Berganti();

		$('.select2').select2({
			dropdownCssClass: 'huruf13',
			width: '100%'
		});

		$('#FormPO #VendorId').on('change', function() {
			Vendor_Berganti();
		});

		$('#FormPO #NoSPR').on('change', function() {
			NoSPR_Berganti();
		});

		$('#FormPO').submit(function(event) {
			event.preventDefault();



			if ($('#FormPO #VendorId option:selected').val() == '') {
				alert('Belum pilih ID.');
				return false;
			}

			if ($('#TxtAlamatKirim').val() == '' || $('#TxtNamaKirim').val() == '' || $('#TxtTeleponKirim').val() == '') {
				alert('Harap lengkapi lokasi pengiriman.');
				return false;
			}

			if ($('#CheckBoxRAP').is(':checked') == false) {
				alert('RAP belum dicentang.');
				return false;
			}

			if (Validasi_SyaratPembayaran() == "") {
				alert('Belum pilih syarat pembayaran.');
				return false;
			}

			if ($('#table_order tbody tr').length < 1) {
				alert('Belum input order list.');
				return false;
			}


			var JobNo_redirect = $(this).attr('data-JobNo_redirect');
			var data_save = $(this).serializeArray();
			var SubTotal = $('.SubTotalClass').text();
			data_save.push({
				name: 'SubTotal',
				value: SubTotal
			});

			jQuery.ajax({
				url: "<?= site_url('Entry/Procurement/FrmEntryPO_C/SimpanPO') ?>",
				type: "POST",
				dataType: "JSON",
				data: data_save,
				beforeSend: function() {
					$('#btnSimpanPO').attr('disabled', true);
				},
				complete: function(xhr, textStatus) {
					$('#btnSimpanPO').attr('disabled', false);
				},
				success: function(data, textStatus, xhr) {
					console.log(data);
					// return false;
					// alert(data.pesan);

					if (data.pesan == 'berhasil') {
						window.location.href = "<?= site_url('Kontrak_po/index/') ?>" + JobNo_redirect;
						return false;
					} else {
						alert(data.pesan);
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					alert('error');
				}
			});

		})






	});




	function Validasi_SyaratPembayaran() {
		var data_CB = '';

		data_CB = ($('#CBInvoice').is(':checked') == true) ? (data_CB == "") ? "INV" : data_CB + ",INV" : data_CB;
		data_CB = ($('#CBFP').is(':checked') == true) ? (data_CB == "") ? "FP" : data_CB + ",FP" : data_CB;
		data_CB = ($('#CBSJ').is(':checked') == true) ? (data_CB == "") ? "SJ" : data_CB + ",SJ" : data_CB;
		data_CB = ($('#CBPO').is(':checked') == true) ? (data_CB == "") ? "PO" : data_CB + ",PO" : data_CB;
		data_CB = ($('#CBBayar').is(':checked') == true) ? (data_CB == "") ? "BAP" : data_CB + ",BAP" : data_CB;
		data_CB = ($('#CBFisik').is(':checked') == true) ? (data_CB == "") ? "BAOP" : data_CB + ",BAOP" : data_CB;

		return data_CB;
	}

	function Vendor_Berganti() {
		$('#CbOverride').attr('checked', false);
		$('#CbOverride').attr('disabled', true);
		$('#TxtKeterangan').val('');

		var VendorId = $('#FormPO #VendorId option:selected').val();

		$.ajax({
				url: "<?= site_url('Entry/Procurement/FrmEntryPO_C/Vendor_Berganti') ?>",
				type: "POST",
				dataType: "JSON",
				data: {
					VendorId: VendorId
				},
				beforeSend: function() {
					$('#FormPO #VendorId').attr('disabled', true);
				},
				complete: function() {
					$('#FormPO #VendorId').attr('disabled', false);
				}
			})
			.done(function(data) {



				$('#FormPO #TxtNama').val('');
				$('#FormPO #TxtAlamat').val('');
				$('#FormPO #TxtTelepon').val('');
				$('#FormPO #TxtNPWP').val('');
				$('#FormPO #TxtUP').val('');
				$('#FormPO #TxtKategori').val('');
				$('#FormPO #TxtUsaha').val('');
				$('#FormPO #LblPPN').val('');

				get_NPWP();

				$('#FormPO #TxtNama').val(data.TxtNama);
				$('#FormPO #TxtAlamat').val(data.TxtAlamat);
				$('#FormPO #TxtTelepon').val(data.TxtTelepon);
				$('#FormPO #TxtNPWP').val(data.TxtNPWP);
				$('#FormPO #TxtUP').val(data.TxtUP);
				$('#FormPO #TxtKategori').val(data.TxtKategori);
				$('#FormPO #TxtUsaha').val(data.TxtUsaha);


				if (data.PKP == 1) {
					$('#CbOverride').attr('disabled', false);
					$('#FormPO #LblPPN').val('PPN');
				} else {
					$('#CbOverride').attr('disabled', true);

				}




			})
			.fail(function() {
				alert('Terjadi Kesalahan');
			})
			.always(function() {

			});

	}


	function get_DataPengiriman(NoSPR) {
		var NoSPR = $('#FormPO #NoSPR option:selected').val();
		if (NoSPR == '') {
			$('#TxtNamaKirim').val('');
			$('#TxtAlamatKirim').val('');
			$('#TxtTeleponKirim').val('');
			$('#TxtJadwalPengiriman').val('');
			return false;
		}

		$.ajax({
				url: "<?= site_url('Entry/Procurement/FrmEntryPO_C/get_DataPengiriman') ?>",
				type: "POST",
				dataType: 'JSON',
				data: {
					NoSPR: NoSPR
				},
			})
			.done(function(data) {
				$('#TxtNamaKirim').val(data.TxtNamaKirim);
				$('#TxtAlamatKirim').val(data.TxtAlamatKirim);
				$('#TxtTeleponKirim').val(data.TxtTeleponKirim);
				$('#TxtJadwalPengiriman').val(data.TxtJadwalPengiriman);
			})
			.fail(function() {
				alert('Error');
			});
	}

	function get_NPWP() {
		var TxtPPN = $('#TxtPPN').val();


		$.ajax({
				url: "<?= site_url('Entry/Procurement/FrmEntryPO_C/get_NPWP') ?>",
				type: "POST",
				dataType: 'HTML',
				data: {
					TxtPPN: TxtPPN
				},
			})
			.done(function(data) {
				// alert(data);
				$('#TxtKeterangan').val(data);
			})
			.fail(function() {
				alert('Error');
			});

	}


	function NoSPR_Berganti() {
		$('#FormPO #TxtNamaKirim').val('');
		$('#FormPO #TxtAlamatKirim').val('');
		$('#FormPO #TxtTeleponKirim').val('');
		$('#FormPO #TxtJadwalPengiriman').val('');

		$('#TxtDiscPersen').val(0);
		$('#TxtDiscNominal').val(0);
		$('#TxtPPN').val(0);
		$('#TxtTotal').val(0);

		var NoSPR = $('#FormPO #NoSPR option:selected').val();

		// if (NoSPR!='') {
		// 	$('.btnTambahOrder').addClass('hidden');
		// }else{
		// 	$('.btnTambahOrder').removeClass('hidden');
		// }

		$('#tampil_disini').html('');
		$.ajax({
				url: "<?= site_url('Entry/Procurement/FrmEntryPO_C/NoSPR_Berganti') ?>",
				type: "POST",
				dataType: "HTML",
				data: {
					NoSPR: NoSPR
				},
				beforeSend: function() {
					$('#FormPO #NoSPR').attr('disabled', true);
				},
				complete: function() {
					$('#FormPO #NoSPR').attr('disabled', false);
				}
			})
			.done(function(data) {
				$('#tampil_disini').html(data);
			}).fail(function() {
				alert('Terjadi Kesalahan');
			})
			.always(function() {
				get_DataPengiriman(NoSPR);
				CekStatusPPN();


			});

		// Vendor_Berganti();




	}

	function CekStatusPPN() {

		if ($('#FormPO #LblPPN').val() == 'PPN') {
			$('#CbOverride').attr('disabled', false);
		} else {
			$('#CbOverride').attr('disabled', true);
		}
	}
</script>
