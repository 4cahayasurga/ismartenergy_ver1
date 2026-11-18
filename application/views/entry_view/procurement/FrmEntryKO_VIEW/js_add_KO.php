<script type="text/javascript">
	$(document).ready(function() {

		if ($('#FormKO #VendorId').val() != '') {
			Vendor_Berganti();
		}

		$('.select2').select2({
			width: '100%',
			dropdownAutoWidth: true,
		});

		$('#FormKO #VendorId').change(function() {
			Vendor_Berganti();
		});

		$('.VolMengetik').on('input', function(e) {
			var inputValue = $(this).val();
			var filteredValue = inputValue.replace(/[^0-9.-]/g, '');

			if (filteredValue.indexOf('-') > 0) {
				filteredValue = filteredValue.replace(/-/g, '');
				filteredValue = '-' + filteredValue;
			}

			if (filteredValue.indexOf('.') == 0) {
				filteredValue = '0' + filteredValue;
			}

			var parts = filteredValue.split('.');
			if (parts[1] && parts[1].length > 3) {
				parts[1] = parts[1].substr(0, 3);
				filteredValue = parts.join('.');
			}

			$(this).val(filteredValue);
		});

		$('.VolMengetik').focusin(function() {
			if (this.value == '0.000' || this.value == '0') {
				$(this).val('');
			}
		});

		$('.VolMengetik').focusout(function() {
			if (this.value == '') {
				$(this).val('0.000');
			} else {
				Vol = this.value;
				Vol = Number(Vol).toFixed(3);
				Vol = FormatVol_Buatan(Vol);
				$(this).val(Vol);
			}

			get_JumlahHarga_Per_Row();

		});

		$('.HrgSatuanMengetik').on('input', function(e) {
			var inputValue = $(this).val();
			var filteredValue = inputValue.replace(/[^0-9.-]/g, '');

			if (filteredValue.indexOf('-') > 0) {
				filteredValue = filteredValue.replace(/-/g, '');
				filteredValue = '-' + filteredValue;
			}

			if (filteredValue.indexOf('.') == 0) {
				filteredValue = '0' + filteredValue;
			}

			var parts = filteredValue.split('.');
			if (parts[1] && parts[1].length > 2) {
				parts[1] = parts[1].substr(0, 2);
				filteredValue = parts.join('.');
			}

			$(this).val(filteredValue);
		});

		$('.HrgSatuanMengetik').focusin(function() {
			if (this.value == '0') {
				$(this).val('');
			}
		});

		$('.HrgSatuanMengetik').focusout(function() {


			if (this.value == '') {
				$(this).val('0');
			} else {
				var HrgSatuan = parseFloat(this.value);

				HrgSatuan = Math.round(HrgSatuan);
				HrgSatuan = addCommas(HrgSatuan);
				$(this).val(HrgSatuan);
			}

			get_JumlahHarga_Per_Row();
		});


		// ==PERSEN START
		$('.PPN_Persen_Mengetik').on('input', function(e) {
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

			if (filteredValue.indexOf('-') > 0) {
				filteredValue = filteredValue.replace(/-/g, '');
				filteredValue = '-' + filteredValue;
			}


			if (filteredValue.indexOf('.') == 0) {
				filteredValue = '0' + filteredValue;
			}


			var parts = filteredValue.split('.');
			if (parts[1] && parts[1].length > 2) {
				parts[1] = parts[1].substr(0, 2);
				filteredValue = parts.join('.');
			}

			$(this).val(filteredValue);
		});

		$('.PPN_Persen_Mengetik').focusin(function() {

			if (this.value == '0') {
				$(this).val('');
			}

		});

		$('.PPN_Persen_Mengetik').focusout(function() {
			if (this.value == '') {
				$(this).val('0');
			} else {
				$(this).val(FormatVol_Buatan($(this).val()));
			}


			get_JumlahHarga_Per_Row();

		});
		// == PERSEN END

		$('#btnAdd_RincianPekerjaan').click(function() {
			$('.scheduler-border').show();
			$("#btnAdd_RincianPekerjaan").hide();
			$('#FormKO .btnCancel_RincianPekerjaan').show();
			bersihkan_form_tambah_KoDtl()
		});

		$('.btnCancel_RincianPekerjaan').click(function() {
			$('.scheduler-border').hide();
			$("#btnAdd_RincianPekerjaan").show();
			$('#FormKO .btnCancel_RincianPekerjaan').hide();
			bersihkan_form_tambah_KoDtl()
		});

		$('.Vol_Tambah').on('input', function(e) {
			var inputValue = $(this).val();
			var filteredValue = inputValue.replace(/[^0-9.-]/g, '');

			if (filteredValue.indexOf('-') > 0) {
				filteredValue = filteredValue.replace(/-/g, '');
				filteredValue = '-' + filteredValue;
			}

			if (filteredValue.indexOf('.') == 0) {
				filteredValue = '0' + filteredValue;
			}

			var parts = filteredValue.split('.');
			if (parts[1] && parts[1].length > 3) {
				parts[1] = parts[1].substr(0, 3);
				filteredValue = parts.join('.');
			}

			$(this).val(filteredValue);
		});

		$('.Vol_Tambah').focusin(function() {
			if (this.value == '0.000' || this.value == '0') {
				$(this).val('');
			}
		});

		$('.Vol_Tambah').focusout(function() {
			if (this.value == '') {
				$(this).val('0.000');
			} else {
				var Vol = parseFloat(this.value);
				Vol = Number(Vol).toFixed(3);
				Vol = FormatVol_Buatan(Vol);
				$(this).val(Vol);
			}
			get_JumlahHarga_Tambah();


		});

		$('.HrgSatuan_Tambah').on('input', function(e) {
			var inputValue = $(this).val();
			var filteredValue = inputValue.replace(/[^0-9.-]/g, '');

			if (filteredValue.indexOf('-') > 0) {
				filteredValue = filteredValue.replace(/-/g, '');
				filteredValue = '-' + filteredValue;
			}

			if (filteredValue.indexOf('.') == 0) {
				filteredValue = '0' + filteredValue;
			}

			var parts = filteredValue.split('.');
			if (parts[1] && parts[1].length > 2) {
				parts[1] = parts[1].substr(0, 2);
				filteredValue = parts.join('.');
			}

			$(this).val(filteredValue);
		});

		$('.HrgSatuan_Tambah').focusin(function() {
			if (this.value == '0') {
				$(this).val('');
			}
		});

		$('.HrgSatuan_Tambah').focusout(function() {
			if (this.value == '') {
				$(this).val('0');
			} else {
				var HrgSatuan = parseFloat(this.value);

				HrgSatuan = Math.round(HrgSatuan);
				HrgSatuan = addCommas(HrgSatuan);
				$(this).val(HrgSatuan);

				get_JumlahHarga_Tambah();
			}

		});

		$('.PPN_Persen_Tambah').on('input', function(e) {
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

			if (filteredValue.indexOf('-') > 0) {
				filteredValue = filteredValue.replace(/-/g, '');
				filteredValue = '-' + filteredValue;
			}


			if (filteredValue.indexOf('.') == 0) {
				filteredValue = '0' + filteredValue;
			}


			var parts = filteredValue.split('.');
			if (parts[1] && parts[1].length > 2) {
				parts[1] = parts[1].substr(0, 2);
				filteredValue = parts.join('.');
			}

			$(this).val(filteredValue);
		});

		$('.PPN_Persen_Tambah').focusin(function() {
			if (this.value == '0') {
				$(this).val('');
			}

		});

		$('.PPN_Persen_Tambah').focusout(function() {
			if (this.value == '') {
				$(this).val('0');
			} else {
				$(this).val(FormatVol_Buatan($(this).val()));
			}

			get_JumlahHarga_Tambah();

		});

		$('.btnSave_tambah').click(function() {

			$(this).attr('disabled', true);

			if ($('#KdRAP_Add option:selected').val() == '') {
				alert('Belum ada RAP yang di-pilih');
				$(this).attr('disabled', false);
				return false;
			}

			if ($('#Uraian_Add').val() == '') {
				alert('Uraian belum di-isi.');
				$(this).attr('disabled', false);
				return false;
			}

			if ($('#Vol_Add').val() == '' || $('#Vol_Add').val() == '0' || $('#Vol_Add').val() == '0.000') {
				alert('Volume belum di-isi.');
				$(this).attr('disabled', false);
				return false;
			}

			if ($('#Uom_Add').val() == '') {
				alert('Satuan belum di-isi.');
				$(this).attr('disabled', false);
				return false;
			}

			if ($(this).attr('data-flag') == 'btnSave_tambah_dan_tutup') {
				$('.scheduler-border').hide();
				$("#btnAdd_RincianPekerjaan").show();
				$('.btnCancel_RincianPekerjaan').hide();

			}

			var mydata = '';

			var NoUrut = 0;

			if ($('.table_rincian tbody tr').length == 0) {
				NoUrut = parseInt(1);
			} else {
				NoUrut = $('.table_rincian tbody tr:last td:first input').val();
				NoUrut = parseInt(NoUrut) + 1;
			}

			var KdRAP = $('#form_tambah #KdRAP_Add').val();
			var Uraian = $('#form_tambah #Uraian_Add').val();
			var Vol = $('#form_tambah #Vol_Add').val();
			var Uom = $('#form_tambah #Uom_Add').val();
			var HrgSatuan = $('#form_tambah #HrgSatuan_Add').val();
			var JumlahHarga = $('#form_tambah #JumlahHarga_Add').val();

			var PPN_Persen = $('#form_tambah #PPN_Persen_Add').val();
			var PPN_Nominal = $('#form_tambah #PPN_Nominal_Add').val();
			var T_PPN_Nominal = $('#form_tambah #TOTAL_PPN_Nominal_Add').val();

			var DrTgl_Pelaksanaan = $('#form_tambah #DrTgl_Pelaksanaan').val();
			var SpTgl_Pelaksanaan = $('#form_tambah #SpTgl_Pelaksanaan').val();

			var DrTgl_Pembayaran = $('#form_tambah #DrTgl_Pembayaran').val();
			var SpTgl_Pembayaran = $('#form_tambah #SpTgl_Pembayaran').val();

			mydata += '<tr class="trID_' + NoUrut + '" data-NoUrut="' + NoUrut + '">';

			mydata += '<td class="text-center"><input type="text" name="NoUrut_tr[]" id="NoUrut_tr_ID' + NoUrut + '" value="' + NoUrut + '" style="display:;width:100% !important;text-align: center;"  readonly></td>'

			mydata += '<td><input type="text" name="KdRAP_tr[]" id="KdRAP_tr_ID' + NoUrut + '" value="' + KdRAP + '" style="display:;width:100% !important;" readonly></td>';

			mydata += '<td ><textarea name="Uraian_tr[]" id="Uraian_tr_ID' + NoUrut + '"  placeholder="Isi Disini"  style="width:100% !important;">' + Uraian + '</textarea></td>';

			mydata += '<td class="text-right"><input name="Vol_tr[]" id="Vol_tr_ID' + NoUrut + '" data-NoUrut="' + NoUrut + '"  class=" VolMengetik text-right" maxlength="10" value="' + Vol + '" style="width:100% !important;"></td>';

			mydata += '<td class="text-center"><input name="Uom_tr[]" id="Uom_tr_ID' + NoUrut + '" class="" placeholder="Isi Disini" maxlength="15" value="' + Uom + '" style="width:100% !important;"></td>';

			mydata += '<td class="text-right"><input name="HrgSatuan_tr[]" id="HrgSatuan_tr_ID' + NoUrut + '" data-NoUrut="' + NoUrut + '" class="HrgSatuanMengetik text-right" value="' + HrgSatuan + '" style="width:100% !important;"></td>';

			mydata += '<td class="text-right"><input id="JumlahHarga_tr_ID' + NoUrut + '" class="JumlahHargaClass text-right" value="' + JumlahHarga + '" style="width:100% !important;" readonly></td>';

			mydata += '<td class="text-right"><input name="PPN_Persen_tr[]" id="PPN_Persen_tr_ID' + NoUrut + '" data-NoUrut="' + NoUrut + '" class="PPN_Persen_Mengetik text-right" value="' + PPN_Persen + '" style="width:100% !important;"></td>';

			mydata += '<td class="text-right"><input name="PPN_Nominal_tr[]" id="PPN_Nominal_tr' + NoUrut + '" data-NoUrut="' + NoUrut + '>" class="PPN_Nominal_Mengetik text-right" value="' + PPN_Nominal + '" style="width:100% !important;" readonly></td>';

			mydata += '<td class="text-right"><input name="T_PPN_Nominal_tr[]" id="T_PPN_Nominal_tr' + NoUrut + '" data-NoUrut="' + NoUrut + '>" class="T_PPN_Nominal_Mengetik text-right" value="' + T_PPN_Nominal + '" style="width:100% !important;" readonly></td>';

			mydata += '<td class="text-right"><input type="date" name="DrTgl_Pelaksanaan[]" id="DrTgl_Pelaksanaan_tr' + NoUrut + '" data-NoUrut="' + NoUrut + '" class="text-right" value="' + DrTgl_Pelaksanaan + '"></td>';

			mydata += '<td class="text-right"><input type="date" name="SpTgl_Pelaksanaan[]" id="SpTgl_Pelaksanaan_tr' + NoUrut + '" data-NoUrut="' + NoUrut + '" class="text-right" value="' + SpTgl_Pelaksanaan + '" ></td>';

			mydata += '<td class="text-right"><input type="date" name="DrTgl_Pembayaran[]" id="DrTgl_Pembayaran_tr' + NoUrut + '" data-NoUrut="' + NoUrut + '" class="text-right" value="' + DrTgl_Pembayaran + '" ></td>';

			mydata += '<td class="text-right"><input type="date" name="SpTgl_Pembayaran[]" id="SpTgl_Pembayaran_tr' + NoUrut + '" data-NoUrut="' + NoUrut + '" class="text-right" value="' + SpTgl_Pembayaran + '" ></td>';

			mydata += '<td class="text-center"><button type="button" class="btn btn-link text-bold" id="btnHapusID_' + NoUrut + '" onclick="btnHapus(' + NoUrut + ')" style="width:100% !important;" ><u>HAPUS</u></button></td>';

			mydata += '</tr>';

			$('.table_rincian > tbody').append(mydata);

			bersihkan_form_tambah_KoDtl();

			$(this).attr('disabled', false);

			toastr.success('', 'Add Success');

			if ($(this).attr('data-flag') == 'btnSave_tambah_dan_tutup') {
				$('.scheduler-border').hide();
				$("#btnAdd_RincianPekerjaan").show();
				$('#FormKO .btnCancel_RincianPekerjaan').hide();

			}


			get_JumlahHarga_Per_Row();

			$('.VolMengetik').on('input', function(e) {
				var inputValue = $(this).val();
				var filteredValue = inputValue.replace(/[^0-9.-]/g, '');

				if (filteredValue.indexOf('-') > 0) {
					filteredValue = filteredValue.replace(/-/g, '');
					filteredValue = '-' + filteredValue;
				}

				if (filteredValue.indexOf('.') == 0) {
					filteredValue = '0' + filteredValue;
				}

				var parts = filteredValue.split('.');
				if (parts[1] && parts[1].length > 3) {
					parts[1] = parts[1].substr(0, 3);
					filteredValue = parts.join('.');
				}

				$(this).val(filteredValue);
			});

			$('.VolMengetik').focusin(function() {
				if (this.value == '0.000' || this.value == '0') {
					$(this).val('');
				}
			});

			$('.VolMengetik').focusout(function() {
				if (this.value == '') {
					$(this).val('0.000');
				} else {
					Vol = this.value;
					Vol = Number(Vol).toFixed(3);
					Vol = FormatVol_Buatan(Vol);
					$(this).val(Vol);
				}

				get_JumlahHarga_Per_Row();

			});

			$('.HrgSatuanMengetik').on('input', function(e) {
				var inputValue = $(this).val();
				var filteredValue = inputValue.replace(/[^0-9.-]/g, '');

				if (filteredValue.indexOf('-') > 0) {
					filteredValue = filteredValue.replace(/-/g, '');
					filteredValue = '-' + filteredValue;
				}

				if (filteredValue.indexOf('.') == 0) {
					filteredValue = '0' + filteredValue;
				}

				var parts = filteredValue.split('.');
				if (parts[1] && parts[1].length > 2) {
					parts[1] = parts[1].substr(0, 2);
					filteredValue = parts.join('.');
				}

				$(this).val(filteredValue);
			});

			$('.HrgSatuanMengetik').focusin(function() {
				if (this.value == '0') {
					$(this).val('');
				}
			});

			$('.HrgSatuanMengetik').focusout(function() {


				if (this.value == '') {
					$(this).val('0');
				} else {
					var HrgSatuan = parseFloat(this.value);

					HrgSatuan = Math.round(HrgSatuan);
					HrgSatuan = addCommas(HrgSatuan);
					$(this).val(HrgSatuan);
				}

				get_JumlahHarga_Per_Row();
			});

			// ==PERSEN START
			$('.PPN_Persen_Mengetik').on('input', function(e) {
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

				if (filteredValue.indexOf('-') > 0) {
					filteredValue = filteredValue.replace(/-/g, '');
					filteredValue = '-' + filteredValue;
				}


				if (filteredValue.indexOf('.') == 0) {
					filteredValue = '0' + filteredValue;
				}


				var parts = filteredValue.split('.');
				if (parts[1] && parts[1].length > 2) {
					parts[1] = parts[1].substr(0, 2);
					filteredValue = parts.join('.');
				}

				$(this).val(filteredValue);
			});

			$('.PPN_Persen_Mengetik').focusin(function() {

				if (this.value == '0') {
					$(this).val('');
				}

			});

			$('.PPN_Persen_Mengetik').focusout(function() {
				if (this.value == '') {
					$(this).val('0');
				} else {
					$(this).val(FormatVol_Buatan($(this).val()));
				}


				get_JumlahHarga_Per_Row();

			});
			// == PERSEN END
		})


		$('#FormKO').submit(function(e) {
			e.preventDefault();

			if ($('#FormKO #VendorId option:selected').val() == '') {
				alert('Belum pilih ID.');
				return false;
			}

			if ($('#FormKO #KatVen option:selected').val() == '') {
				alert('Belum pilih Kategori Vendor.');
				return false;
			}

			if ($('#FormKO #SistemPembayaran option:selected').val() == '') {
				alert('Belum pilih Sistem Pembayaran.');
				return false;
			}

			if (Validasi_SyaratPembayaran() == "") {
				alert('Belum pilih syarat pembayaran.');
				return false;
			}

			if ($('#table_rincian tbody tr').length < 1) {
				alert('Belum input rincian pekerjaan.');
				return false;
			}

			var JobNo_redirect = $(this).attr('data-JobNo_redirect');
			var data_save = $(this).serializeArray();
			var SubTotal = $('.SubTotalClass').text();
			data_save.push({
				name: 'SubTotal',
				value: SubTotal
			});

			var TextButton = $('#btnSimpanPO').text();



			jQuery.ajax({
				url: "<?= site_url('Entry/Procurement/FrmEntryKO_C/SimpanKO') ?>",
				type: "POST",
				dataType: "JSON",
				// dataType: "HTML",
				data: data_save,
				beforeSend: function() {
					$('#btnSimpanPO').attr('disabled', true).hide();
					$('#btnLoading').attr('disabled', true).show();
				},
				complete: function(xhr, textStatus) {
					$('#btnSimpanPO').attr('disabled', false).show();
					$('#btnLoading').attr('disabled', false).hide();
				},
				success: function(data, textStatus, xhr) {
					// alert(data);
					// console.log(data)
					// return false;

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

		});


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

	function HapusRow(NoUrut) {
		$(this).closest('tr').remove();
	}

	function btnHapus(NoUrut = '') {
		$('#table_rincian > tbody > tr.trID_' + NoUrut).remove();
		toastr.success('', 'Removed!');
		get_JumlahHarga_Per_Row();
	}


	function get_JumlahHarga_Tambah() {
		var JumlahHarga = 0;
		var PPN_Nominal = 0;
		var T_PPN_Nominal = 0;

		var Vol = ($('.Vol_Tambah').val()).split(',').join('').split('$').join('');
		var HrgSatuan = ($('.HrgSatuan_Tambah').val()).split(',').join('').split('$').join('');
		var PPN_Persen = $('.PPN_Persen_Tambah').val();

		Vol = Number(Vol);
		HrgSatuan = Number(HrgSatuan);
		PPN_Persen = Number(PPN_Persen);

		JumlahHarga = Vol * HrgSatuan;
		JumlahHarga = Number(JumlahHarga);
		JumlahHarga = parseFloat(JumlahHarga);
		JumlahHarga = Math.round(JumlahHarga);
		JumlahHarga = addCommas(JumlahHarga);

		PPN_Nominal = HrgSatuan * (PPN_Persen / 100);
		PPN_Nominal = Number(PPN_Nominal);
		PPN_Nominal = parseFloat(PPN_Nominal);
		PPN_Nominal = Math.round(PPN_Nominal);
		PPN_Nominal = addCommas(PPN_Nominal);

		T_PPN_Nominal = (HrgSatuan * (PPN_Persen / 100)) * Vol;
		T_PPN_Nominal = Number(T_PPN_Nominal);
		T_PPN_Nominal = parseFloat(T_PPN_Nominal);
		T_PPN_Nominal = Math.round(T_PPN_Nominal);
		T_PPN_Nominal = addCommas(T_PPN_Nominal);

		$('.JumlahHarga_Tambah').val(JumlahHarga);
		$('.PPN_Nominal_Tambah').val(PPN_Nominal);
		$('.T_PPN_Nominal_Tambah').val(T_PPN_Nominal);

		get_JumlahHarga_Per_Row();
	}

	function get_JumlahHarga_Per_Row() {
		$('.table_rincian tbody tr').each(function() {
			// alert('aaa')
			var JumlahHarga = 0;
			var PPN_Nominal = 0;
			var T_PPN_Nominal = 0;
			var NoUrut = $(this).attr('data-NoUrut');

			var Vol = ($('#Vol_tr_ID' + NoUrut).val()).split(',').join('').split('$').join('');
			var HrgSatuan = ($('#HrgSatuan_tr_ID' + NoUrut).val()).split(',').join('').split('$').join('');
			var PPN_Persen = $('#PPN_Persen_tr_ID' + NoUrut).val();
			// alert(PPN_Persen);
			Vol = Number(Vol);
			HrgSatuan = Number(HrgSatuan);
			PPN_Persen = Number(PPN_Persen);

			JumlahHarga = Vol * HrgSatuan;
			JumlahHarga = Number(JumlahHarga);
			JumlahHarga = parseFloat(JumlahHarga);
			JumlahHarga = Math.round(JumlahHarga);
			JumlahHarga = addCommas(JumlahHarga);

			PPN_Nominal = HrgSatuan * (PPN_Persen / 100);
			PPN_Nominal = Number(PPN_Nominal);
			PPN_Nominal = parseFloat(PPN_Nominal);
			PPN_Nominal = Math.round(PPN_Nominal);
			PPN_Nominal = addCommas(PPN_Nominal);

			T_PPN_Nominal = (HrgSatuan * (PPN_Persen / 100)) * Vol;
			T_PPN_Nominal = Number(T_PPN_Nominal);
			T_PPN_Nominal = parseFloat(T_PPN_Nominal);
			T_PPN_Nominal = Math.round(T_PPN_Nominal);
			T_PPN_Nominal = addCommas(T_PPN_Nominal);

			$('#JumlahHarga_tr_ID' + NoUrut).val(JumlahHarga);
			$('#PPN_Nominal_tr' + NoUrut).val(PPN_Nominal);
			$('#T_PPN_Nominal_tr' + NoUrut).val(T_PPN_Nominal);

		});
		get_SubTotal();
	}

	function get_SubTotal() {

		var SubTotal = 0;
		var SubTotal_PPN_Class = 0;
		$('.table_rincian tbody tr').each(function() {

			var NoUrut = $(this).attr('data-NoUrut');

			var Vol = ($('#Vol_tr_ID' + NoUrut).val()).split(',').join('').split('$').join('');
			var HrgSatuan = ($('#HrgSatuan_tr_ID' + NoUrut).val()).split(',').join('').split('$').join('');
			var PPN_Persen = $('#PPN_Persen_tr_ID' + NoUrut).val();

			Vol = parseFloat(Vol);
			HrgSatuan = parseFloat(HrgSatuan);
			PPN_Persen = parseFloat(PPN_Persen);

			SubTotal += Vol * HrgSatuan;
			SubTotal_PPN_Class += (HrgSatuan * (PPN_Persen / 100)) * Vol;
		});

		SubTotal = parseFloat(SubTotal);
		SubTotal = Math.round(SubTotal);
		SubTotal = addCommas(SubTotal);

		SubTotal_PPN_Class = parseFloat(SubTotal_PPN_Class);
		SubTotal_PPN_Class = Math.round(SubTotal_PPN_Class);
		SubTotal_PPN_Class = addCommas(SubTotal_PPN_Class);

		$('.SubTotalClass').text(SubTotal);
		$('.SubTotal_PPN_Class').text(SubTotal_PPN_Class);
		get_GrandTotal();
	}

	function get_GrandTotal() {
		var SubTotal = 0;
		var TxtTtlPengurang = 0;
		var TxtTotal = 0;

		var SubTotal_PPN_Class = 0;

		SubTotal = ($('.SubTotalClass').text()).split(',').join('').split('$').join('');
		TxtTtlPengurang = ($('.TxtTtlPengurang').val()).split(',').join('').split('$').join('');
		SubTotal_PPN_Class = ($('.SubTotal_PPN_Class').text()).split(',').join('').split('$').join('');

		SubTotal = parseFloat(SubTotal);
		SubTotal_PPN_Class = parseFloat(SubTotal_PPN_Class);
		TxtTtlPengurang = parseFloat(TxtTtlPengurang);



		TxtTotal = (SubTotal - TxtTtlPengurang) + SubTotal_PPN_Class;

		TxtTotal = parseFloat(TxtTotal);
		TxtTotal = Math.round(TxtTotal);
		TxtTotal = addCommas(TxtTotal);

		$('#TxtTotal').val(TxtTotal);
		$('#TxtPPN').val(SubTotal_PPN_Class);
	}


	function Vendor_Berganti() {
		var VendorId = $('#FormKO #VendorId option:selected').val();
		$.ajax({
				url: "<?= site_url('Entry/Procurement/FrmEntryKO_C/Vendor_Berganti') ?>",
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

	function addCommas(nStr) {
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}

	function FormatVol_Buatan(num) {
		var str = num.toString().replace("", ""),
			parts = false,
			output = [],
			i = 1,
			formatted = null;
		if (str.indexOf(".") > 0) {
			parts = str.split(".");
			str = parts[0];
		}
		str = str.split("").reverse();
		for (var j = 0, len = str.length; j < len; j++) {
			if (str[j] != ",") {
				output.push(str[j]);
				if (i % 3 == 0 && j < (len - 1)) {
					output.push(",");
				}
				i++;
			}
		}
		formatted = output.reverse().join("");
		return ("" + formatted + ((parts) ? "." + parts[1].substr(0, 3) : ""));
	}

	function bersihkan_form_tambah_KoDtl() {
		$("#form_tambah #KdRAP_Add").val('').trigger('change');
		$("#form_tambah #Uom_Add").val('');
		$("#form_tambah #Vol_Add").val('0.000');
		$("#form_tambah #Uraian_Add").val('');
		$("#form_tambah #HrgSatuan_Add").val(0);
		$("#form_tambah #JumlahHarga_Add").val(0);

		$("#form_tambah #PPN_Persen_Add").val(0);
		$("#form_tambah #PPN_Nominal_Add").val(0);

		$("#form_tambah #DrTgl_Pelaksanaan").val('');
		$("#form_tambah #SpTgl_Pelaksanaan").val('');

		$("#form_tambah #DrTgl_Pembayaran").val('');
		$("#form_tambah #SpTgl_Pembayaran").val('');
	}
</script>
