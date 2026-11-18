<script type="text/javascript">
	var TipeFormGlobal = $('#FormPD_Lapangan').attr('data-TipeForm');
	var NoKOGlobal = $('#FormPD_Lapangan').attr('data-NoKO');
	var NoPJSebelumnyaGlobal = $('#FormPD_Lapangan').attr('data-NoPJSebelumnya');
	// alert(NoKOGlobal)

	$(document).ready(function(){



		$('.select2').select2();

		TampilInvoice(1);

		get_tr_UraianPD();
		// GetSaldo();
		// if (NoPJSebelumnyaGlobal!='') {
		// 	$('#NoPJSebelumnya').val(NoPJSebelumnyaGlobal).trigger('change');
		// 	panggil_Saldo();
		// }
		// BindPJ();
		GetSaldo();
		get_Invoice_AddPD_Update();
		
		// BindPJ();


		$('#TglPD').on('change',function(){
			var TglPD = this.value;
			var JobNo = $('#FormPD_Lapangan').attr('data-JobNo');

			$.ajax({
				url: "<?=site_url('Entry/PDPJ/EntryPDLap_C/dapat_data_ketika_TglPD_berubah_js')?>",
				type: 'POST',
				dataType: 'json',
				data: {JobNo : JobNo, TglPD : TglPD},
				beforeSend : function(){
					$('#TglPD').attr('disabled',true);
				}, complete : function(){
					$('#TglPD').attr('disabled',false);
				}
			})
			.done(function(data) {
				if (data.status=='berhasil') {
					$('#FormPD_Lapangan #PrdAwal').val(data.PrdAwal);
					$('#FormPD_Lapangan #PrdAkhir').val(data.PrdAkhir);
					$('#FormPD_Lapangan #Minggu').val(data.Minggu);
				}else{
					alert('Something Wrong');
				}
			})
			.fail(function() {
				alert('something wrong');
			});


		});


		$('#TipeForm').on('change',function(){
			var TipeForm = this.value;
			$('#NoTagihan').val('');

			if (TipeForm=='') {
				TampilInvoice(0);
				$('#NoKO').html('');
				$('#NoKO').html('<option value="">:: Pilih Salah Satu ::</option>');
				$('#NoKO').attr('disabled',true);


				$('#VendorId').val('');
				$('#Nama').val('');
				$('#Alamat').val('');
				$('#Telepon').val('');
				$('#NPWP').val('');
				$('#NoRek').val('');
				$('#AtasNama').val('');
				$('#Bank').val('').trigger('change');
			}else{
				$('#NoKO').attr('disabled',false);
				BindKO();
				BindPJ();
			}

			$('#NoKO').trigger('change');

		});

		$('#NoKO').on('change',function(){
			// alert(NoKO)
			var NoKO 		= this.value;
			var JobNo 		= $('#FormPD_Lapangan').attr('data-JobNo');

			$('#NoTagihan').val('');

			$('#VendorId').val('');
			$('#Nama').val('');
			$('#Alamat').val('');
			$('#Telepon').val('');
			$('#NPWP').val('');
			$('#NoRek').val('');
			$('#AtasNama').val('');
			$('#Bank').val('').trigger('change');

			$('#tampil_table').html('');
			$('#tampil_Invoice').html('');

			if (NoKO!='') {

				var Alokasi 	= $('#FormPD_Lapangan').attr('data-Alokasi');
				var Aksi 		= $('#FormPD_Lapangan').attr('data-Aksi');
				var TipePD 		= $('#FormPD_Lapangan').attr('data-TipePD');
				var TipeForm	= $('#TipeForm option:selected').val();
				var NoPD		= $('#NoPD').val();

				jQuery.ajax({
					url: "<?=site_url('Entry/PDPJ/EntryPDLap_C/NoKO_Berganti')?>",
					type: 'POST',
					dataType: 'JSON',
					data: {
						NoKO : NoKO,
						JobNo : JobNo,
						Alokasi : Alokasi,
						Aksi : Aksi,
						TipePD : TipePD,
						TipeForm : TipeForm,
						NoPD : NoPD,
					},
					beforeSend : function(){
						$('#NoKO').attr('disabled',true);
					}, complete : function(){
						$('#NoKO').attr('disabled',false);
					},
					success: function(data, textStatus, xhr) {
						
						$('#VendorId').val(data.VendorId);
						$('#Nama').val(data.Nama);
						$('#Alamat').val(data.Alamat);
						$('#Telepon').val(data.Telepon);
						$('#NPWP').val(data.NPWP);

						$('#NoRek').val(data.NoRek);
						$('#AtasNama').val(data.AtasNama);
						$('#Bank').val(data.Bank).trigger('change');

						get_tr_UraianPD();
						get_Invoice_AddPD_Update();
					},
					error: function(xhr, textStatus, errorThrown) {
						alert('something wrong');
					}
				});
				
			}

			Get_SubTotal();

		});

		$('#NoPJSebelumnya').on('change',function(){
			var NoPJSebelumnya = this.value;

			if (NoPJSebelumnya=='') {
				$('#Saldo').val(0);
				Get_Total();
				return false;
			}

			jQuery.ajax({
				url: "<?=site_url('Entry/PDPJ/EntryPDLap_C/NoPJSebelumnya_Berganti')?>",
				type: 'POST',
				dataType: 'HTML',
				data: {
					NoPJSebelumnya : NoPJSebelumnya,
				},
				beforeSend : function(){
					$('#NoPJSebelumnya').attr('disabled',true);
				}, complete : function(){
					$('#NoPJSebelumnya').attr('disabled',false);
				},
				success: function(data, textStatus, xhr) {
					$('#Saldo').val(data);
					Get_Total();
				},
				error: function(xhr, textStatus, errorThrown) {
					alert('something wrong');
				}
			});

		});


		$(document).on('click', '#select_all', function() {          	
			$(".emp_checkbox").prop("checked", this.checked);
			$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
		});

		$(document).on('click', '.emp_checkbox', function() {		
			if ($('.emp_checkbox:checked').length == $('.emp_checkbox').length) {
				$('#select_all').prop('checked', true);
			} else {
				$('#select_all').prop('checked', false);
			}
			$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");

		});

		$('#delete_records').on('click', function(e) { 

			var employee = [];  
			var NoKO = $('#NoKO option:selected').val();
			var TipeForm = $('#TipeForm option:selected').val();

			if (NoKO.substr(0,2)=='KO' && TipeForm=='05B') {
				alert('Tipe Form 05B tidak dapat di-hapus');
				return false;
			}

			$(".emp_checkbox:checked").each(function() {  
				employee.push($(this).attr('data-NoUrut'));
			});	
			if(employee.length <=0)  {  
				alert("belum di-centang");  
			}else{
				WRN_PROFILE_DELETE = "Anda yakin ingin hapus data ini?";  
				var checked = confirm(WRN_PROFILE_DELETE);  
				if(checked == true) {			
					var selected_values = employee.join(",");
					var NoKO = $('#NoKO option:selected').val();
					var JobNo = $('#FormPD_Lapangan').attr('data-JobNo');
					var Aksi = $('#FormPD_Lapangan').attr('data-Aksi');
					var NoPD = $('#NoPD').val();

					$.ajax({
						url: '<?= site_url('Entry/PDPJ/EntryPDLap_C/aksi_hapus_detail_multiple') ?>',
						type: 'POST',
						dataType: 'html',
						data: {
							'NoKO': NoKO,
							'JobNo' : JobNo,
							'NoUrut' : selected_values,
							'NoPD' : NoPD,
							'Aksi' : Aksi,
						},
						beforeSend : function(){
							$(this).text('Loading...').attr('disabled',true);
						}, complete : function(){
							$(this).text('HAPUS URAIAN').attr('disabled',false);
						}
					})
					.done(function(data) {
						if(data == 'berhasil'){
							toastr.success('', 'Deleted');
						}else if(data == 'gagal'){
							toastr.info('', 'Failed');
						}else{
							toastr.error('', 'Error');
						}

						get_tr_UraianPD();
					})
					.fail(function() {
						alert('Something Wrong');
					})
					.always(function() {
						// tampilkan_tbl_PdDtl();
					});


				}  
			}

		});


		$("#Form_Uraian_ADD input[name='HrgSatuan'], #Form_Uraian_EDIT input[name='HrgSatuan']").on('input', function(e) 
		{
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

		$("#Form_Uraian_ADD input[name='HrgSatuan'], #Form_Uraian_EDIT input[name='HrgSatuan']").focusin(function()
		{
			if (this.value=='0.00' || this.value=='0') {
				$(this).val('');
			}
		});

		$("#Form_Uraian_ADD input[name='HrgSatuan'], #Form_Uraian_EDIT input[name='HrgSatuan']").focusout(function()
		{
			if (this.value=='') {
				$(this).val('0.00');
			}else{
				var HrgSatuan = parseFloat(this.value);
				HrgSatuan = Number(HrgSatuan).toFixed(2);
				HrgSatuan = FormatVol_Buatan_HrgSatuan(HrgSatuan);
				$(this).val(HrgSatuan);
			}


		});

		$("#Form_Uraian_ADD input[name='Vol'], #Form_Uraian_EDIT input[name='Vol']").on('input', function(e) 
		{
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

		$("#Form_Uraian_ADD input[name='Vol'], #Form_Uraian_EDIT input[name='Vol']").focusin(function()
		{
			if (this.value=='0.000' || this.value=='0') {
				$(this).val('');
			}
		});

		$("#Form_Uraian_ADD input[name='Vol'], #Form_Uraian_EDIT input[name='Vol']").focusout(function()
		{
			if (this.value=='') {
				$(this).val('0.000');
			}else{
				var Vol = parseFloat(this.value);
				Vol = Number(Vol).toFixed(3);
				Vol = FormatVol_Buatan(Vol);
				$(this).val(Vol);
			}


		});


		// == START SIMPAN URAIAN
		$('#Form_Uraian_ADD').submit(function(event){
			event.preventDefault();

			var KdRAP = $('#Form_Uraian_ADD #KdRAP option:selected').val();
			var TipeForm = $('#TipeForm option:selected').val();
			var RAP_Header = KdRAP.split('|');
			RAP_Header = RAP_Header[0];

			if(KdRAP==''){
				alert('Belum ada RAP yang di-pilih');
				return false;
			}else{
				var RAP_Header = KdRAP.split('|');
				RAP_Header = RAP_Header[0];

				if ( (TipeForm!='04B' && TipeForm!='05B' && TipeForm!='01E') && RAP_Header=='Header' ) {
					alert("RAP tidak boleh Tipe Header ");
					return false;
				}
			}

			if ($('#Form_Uraian_ADD #Uraian').val()=='') {
				alert('Uraian belum di-isi');
				return false;
			}

			if ($('#Form_Uraian_ADD #Uom').val()=='') {
				alert('Satuan belum di-isi');
				return false;
			}

			var data_save = $('#Form_Uraian_ADD').serializeArray();
			
			var JobNo = $('#FormPD_Lapangan').attr('data-JobNo');
			var NoPD = $('#NoPD').val();
			var NoKO = $('#NoKO option:selected').val();
			var Aksi = $('#FormPD_Lapangan').attr('data-Aksi');
			

			data_save.push({ name: "JobNo", value: JobNo });
			data_save.push({ name: "NoPD", value: NoPD });
			data_save.push({ name: "NoKO", value: NoKO });
			data_save.push({ name: "Aksi", value: Aksi });

			$.ajax({
				url: "<?=site_url('Entry/PDPJ/EntryPDLap_C/Simpan_Uraian_Ke_DB')?>",
				type: 'POST',
				dataType: 'HTML',
				data: data_save,
				beforeSend : function(){
					$(this).attr('disabled',true);
				},complete : function(){
					$(this).attr('disabled',false);
				}
			})
			.done(function(data) {
				if(data == 'berhasil'){
					toastr.success('', 'Saved Success');
				}else if(data == 'gagal'){
					toastr.info('', 'Failed');
				}else{
					toastr.error('', 'Error');
				}

				$('#myModal').modal('hide');
				get_tr_UraianPD();
			})
			.fail(function() {
				alert('Something Wrong');
			});

		});
		// == END SIMPAN URAIAN

		// == START UPDATE URAIAN
		$('#Form_Uraian_EDIT').submit(function(event){
			event.preventDefault();

			var KdRAP = $('#Form_Uraian_EDIT #KdRAP option:selected').val();
			var TipeForm = $('#TipeForm option:selected').val();
			var RAP_Header = KdRAP.split('|');
			RAP_Header = RAP_Header[0];

			if(KdRAP==''){
				alert('Belum ada RAP yang di-pilih');
				return false;
			}else{
				var RAP_Header = KdRAP.split('|');
				RAP_Header = RAP_Header[0];

				if ( (TipeForm!='04B' && TipeForm!='05B' && TipeForm!='01E') && RAP_Header=='Header' ) {
					alert("RAP tidak boleh Tipe Header ");
					return false;
				}
			}

			if ($('#Form_Uraian_EDIT #Uraian').val()=='') {
				alert('Uraian belum di-isi');
				return false;
			}

			if ($('#Form_Uraian_EDIT #Uom').val()=='') {
				alert('Satuan belum di-isi');
				return false;
			}

			var data_save = $('#Form_Uraian_EDIT').serializeArray();
			
			var JobNo = $('#FormPD_Lapangan').attr('data-JobNo');
			var NoPD = $('#NoPD').val();
			var NoKO = $('#NoKO option:selected').val();
			var Aksi = $('#FormPD_Lapangan').attr('data-Aksi');
			
			data_save.push({ name: "JobNo", value: JobNo });
			data_save.push({ name: "NoPD", value: NoPD });
			data_save.push({ name: "NoKO", value: NoKO });
			data_save.push({ name: "Aksi", value: Aksi });
			data_save.push({ name: "TipeForm", value: TipeForm });

			$.ajax({
				url: "<?=site_url('Entry/PDPJ/EntryPDLap_C/Update_Uraian_Ke_DB')?>",
				type: 'POST',
				dataType: 'HTML',
				data: data_save,
				beforeSend : function(){
					$(this).attr('disabled',true);
				},complete : function(){
					$(this).attr('disabled',false);
					
				}
			})
			.done(function(data) {
				if(data == 'berhasil'){
					toastr.success('', 'Update Success');
					$('#Modal_EditUraian').modal('hide');
					get_tr_UraianPD();
				}else{
					alert(data);
				}
			})
			.fail(function() {
				alert('Something Wrong');
			});

		});
		// == END UPDATE URAIAN


		// == WILAYAH INVOICE
		$(document).on('click', '#select_all_Invoice', function() {          	
			$(".emp_checkbox_Invoice").prop("checked", this.checked);
			Hitung_Invoice_TotalTercentang();
		});

		$(document).on('click', '.emp_checkbox_Invoice', function() {		
			if ($('.emp_checkbox_Invoice:checked').length == $('.emp_checkbox_Invoice').length) {
				$('#select_all_Invoice').prop('checked', true);
				
			} else {
				$('#select_all_Invoice').prop('checked', false);
				
			}
			Hitung_Invoice_TotalTercentang();

		});
		// END WILAYAH INVOICE
		$('#FormPD_Lapangan').submit(function(event){
			event.preventDefault();

			// == JIKA PD LAPANGAN maka aktifkan di bawah ini
			// if ($('#NoRef').val()=='') {
			// 	alert('No. BAP/Ref Lapangan	belum diisi.');
			// 	$('#NoRef').focus();
			// 	return false;
			// }

			var JobNo = $('#JobNo').val();
			var Alokasi = $('#Alokasi').val();

			if ($('#Alokasi').val()=='') {
				alert('Alokasi belum di-pilih.');
				return false;
			}

			if ($('#TipeForm').val()=='') {
				alert('Tipe form belum di-pilih.');
				$('#TipeForm').focus();
				return false;
			}

			if ($('#NoKO').val()=='') {
				alert('Belum pilih No Kontrak/PO.');
				return false;
			}

			if ($('#Deskripsi').val()=='') {
				alert('Deskripsi belum di-isi.');
				return false;
			}

			if ($('#TglPD').val()=='') {
				alert('Tanggal Permintaan belum di-isi.');
				return false;
			}



			if (Validasi_BuktiPendukung_Checkbox()=='' && $('#TipeForm').val()=='05C' ) {
				alert('Belum pilih bukti pendukung.');
				return false;
			}

			if ($('#Bank option:selected').val()=='') {
				alert('Bank rekening penerima belum di-pilih.');
				return false;
			}

			if ($('#mytable_uraianPD > tbody > tr').length==0) {
				alert('belum input uraian permintanaan dana');
				return false;
			}


			if ($('#NoKO').val().substring(0,2)=='KO') {

				if (Validasi_BuktiPendukung_Checkbox()=='') {
					alert('Belum pilih bukti pendukung.');
					return false;
				}


				// == START PENGECEKAN INVOICE
				var TmpPrice = 0;
				var MarginError_Invoice = 0;
				var JumlahYangDiChecked = 0;

				if ($('#mytable_Invoice tbody').find('tr').length > 0 ) {

					$('#mytable_Invoice > tbody > tr').each(function(index,tr){

						if ($(this).find('.emp_checkbox_Invoice').is(':checked')==false) {
							return true;
						}else{
							JumlahYangDiChecked = JumlahYangDiChecked + 1;
						}

						TotalBayar_Class = ($(this).find(".TotalBayar_Class").val()).split(',').join('').split('$').join('');
						TotalPayment_Class = ($(this).find(".TotalPayment_Class").val()).split(',').join('').split('$').join('');
						Total_DariTblInvoice = ($(this).find(".Total_DariTblInvoice").text()).split(',').join('').split('$').join('');
						OriginalPayment_Class = ($(this).find(".OriginalPayment_Class").val()).split(',').join('').split('$').join('');
						InvNo = $(this).find(".InvNo_Class").text();

						TotalBayar_Class = parseFloat(TotalBayar_Class);
						TotalPayment_Class = parseFloat(TotalPayment_Class); 
						Total_DariTblInvoice = parseFloat(Total_DariTblInvoice);
						OriginalPayment_Class = parseFloat(OriginalPayment_Class);

						TmpPrice = TotalBayar_Class;

						if (TmpPrice + (TotalPayment_Class - OriginalPayment_Class) > Total_DariTblInvoice) {
							MarginError_Invoice = MarginError_Invoice+1;
							alert('Total bayar invoice '+InvNo+' melebihi total invoice.');
							return false;
						}

					});
				}

				if (TipeForm=='04B' || TipeForm=='05B') {
					if (JumlahYangDiChecked==0) {
						alert("Belum pilih invoice");
						return false;
					}
				}

				if (MarginError_Invoice>0) {
					return false;
				}
			}


			var data_save = $('#FormPD_Lapangan').serializeArray();
			var Aksi = $('#FormPD_Lapangan').attr('data-Aksi');
			var TipePD = $('#FormPD_Lapangan').attr('data-TipePD');

			data_save.push({ name: "Aksi", value: Aksi });
			data_save.push({ name: "TipePD", value: TipePD });


			$.ajax({
				url: "<?=site_url('Entry/PDPJ/EntryPDLap_C/Simpan_PD_Baru')?>",
				type: 'POST',
				dataType: 'html',
				data: data_save,
				beforeSend : function(){
					loading_halaman('tampil');
					$('#btnSimpanPD').attr('disabled',true);
				}
			})
			.done(function(data) {

				
				if (data=='berhasil') {
					window.location.href="<?=site_url('Entry/PDPJ/EntryPDLap_C/index/')?>"+JobNo+'/'+Alokasi;
				}else{
					$('#btnSimpanPD').attr('disabled',false);
					loading_halaman('tutup');
					alert(data);
				}
				
			})
			.fail(function() {
				loading_halaman('tutup');
			});

		});


	});

function TipeForm_Ganti(){
	var TipeForm = $('#TipeForm option:selected').val();
	$('#NoTagihan').val('');

	if (TipeForm=='') {
		TampilInvoice(0);
		$('#NoKO').html('');
		$('#NoKO').html('<option value="">:: Pilih Salah Satu ::</option>');
		$('#NoKO').attr('disabled',true);


		$('#VendorId').val('');
		$('#Nama').val('');
		$('#Alamat').val('');
		$('#Telepon').val('');
		$('#NPWP').val('');
		$('#NoRek').val('');
		$('#AtasNama').val('');
		$('#Bank').val('').trigger('change');
	}else{
		$('#NoKO').attr('disabled',false);
		BindKO();
		BindPJ();
	}

	$('#NoKO').trigger('change');
}

function Hitung_Invoice_TotalTercentang(){

	var TotalCentang = 0;
	var NoTagihan = "";


	if ($('#mytable_Invoice tbody').find('tr').length > 0 ) {

		$('#mytable_Invoice > tbody > tr').each(function(index,tr){
			var TotalBayar = 0;

			IsChecked = $(this).find('.emp_checkbox_Invoice');
			if ($(this).find('.emp_checkbox_Invoice').is(':checked')==true) {
				TotalBayar = ($(this).find(".TotalBayar_Class").val()).split(',').join('').split('$').join('');

				if (NoTagihan=='') {
					NoTagihan = InvNo = $(this).find('.InvNo_Class').text();
				}else{
					NoTagihan = NoTagihan+';'+$(this).find('.InvNo_Class').text();
				}
				
				// console.log(TotalBayar+' \n'); 
			}else{
				TotalBayar = 0;
			}

			TotalBayar = parseFloat(TotalBayar);
			TotalCentang += TotalBayar;
		});
	}

	if (NoTagihan.length>100) {
		NoTagihan = NoTagihan.substring(0,100);
	}

	$('#TotalCentang').val(FormatVol_Buatan(TotalCentang));
	$('#NoTagihan').val(NoTagihan);
}

function get_Invoice_AddPD_Update(){

	var TipeForm = $('#TipeForm option:selected').val();
	var NoKO = $('#NoKO option:selected').val();
	var JobNo = $('#FormPD_Lapangan').attr('data-JobNo');
	var Aksi = $('#FormPD_Lapangan').attr('data-Aksi');
	var Nama = $('#Nama').val();
	var NoPD = $('#NoPD').val();

	$.ajax({
		url: "<?=site_url('Entry/PDPJ/EntryPDLap_C/get_Invoice_AddPD_Update')?>",
		type: 'POST',
		dataType: 'HTML',
		data: {JobNo : JobNo, TipeForm : TipeForm, NoKO : NoKO, Aksi : Aksi, Nama : Nama,NoPD : NoPD},
		beforeSend : function(){
			loading_halaman('tampil');
		}, complete : function(){
			loading_halaman('tutup');
		}
	})
	.done(function(data) {
		$('#tampil_Invoice').html(data);

		
	})
	.fail(function() {
		alert('Something Wrong');
	});
}

function loading_halaman(flag='tutup'){
	if (flag=='tutup') {
		$('#loading_halaman').hide();
	}else{
		$('#loading_halaman').show();
	}
}

function Validasi_BuktiPendukung_Checkbox(){
	var ListCB = "";
	ListCB = ($('input[name="INV"]').is(':checked')==true) ? "INV" : "";
	ListCB = ($('input[name="SJ"]').is(':checked')==true) ? (ListCB=="") ? "SJ" : ListCB+","+"SJ" : ListCB;
	ListCB = ($('input[name="PO"]').is(':checked')==true) ? (ListCB=="") ? "PO" : ListCB+","+"PO" : ListCB;
	ListCB = ($('input[name="FP"]').is(':checked')==true) ? (ListCB=="") ? "FP" : ListCB+","+"FP" : ListCB;
	ListCB = ($('input[name="FP"]').is(':checked')==true) ? (ListCB=="") ? "BAP" : ListCB+","+"BAP" : ListCB;
	ListCB = ($('input[name="BAOP"]').is(':checked')==true) ? (ListCB=="") ? "BAOP" : ListCB+","+"BAOP" : ListCB;

	return ListCB;
}

function btnTambahUraian(){

	var NoUrut = $('#Form_Uraian_ADD #NoUrut').val();
	var NoKO = $('#NoKO option:selected').val();
	var Alokasi = $('#AlokasiList option:selected').text();
	


	$('#Form_Uraian_ADD #NoUrut').val('');

	$('#Form_Uraian_ADD #Alokasi').val('');
	$('#Form_Uraian_ADD #Alokasi').val(Alokasi);
	$('#Form_Uraian_ADD #Alokasi').attr(Alokasi);

	$('#Form_Uraian_ADD #Uraian').val('');
	$('#Form_Uraian_ADD #Vol').val(0);

	$('#Form_Uraian_ADD #Uom').val('');
	$('#Form_Uraian_ADD #HrgSatuan').val(0);
	if (NoKO=='') {
		alert('belum pilih No.KO');
		return false;
	}

	// if (siblings('selector')) {}

	get_KdRAP(NoUrut,'NEW');

	$('#myModal').modal('show');
}

function btn_EditUraian(NoUrut){
	var Alokasi = $('#AlokasiList option:selected').text();
	var KdRAP = $('#btnUpdate_ID_'+NoUrut).attr('data-KdRAP');
	var Uraian = $('#btnUpdate_ID_'+NoUrut).attr('data-Uraian');
	var Uom = $('#btnUpdate_ID_'+NoUrut).attr('data-Uom');
	var Vol = $('#btnUpdate_ID_'+NoUrut).attr('data-Vol');
	var HrgSatuan = $('#btnUpdate_ID_'+NoUrut).attr('data-HrgSatuan');

	var TipeForm = $('#TipeForm option:selected').val();
	var NoKO = $('#NoKO option:selected').val();

	$('#Form_Uraian_EDIT #NoUrut').val(NoUrut);
	$('#Form_Uraian_EDIT #Alokasi').val(Alokasi);
	get_KdRAP(NoUrut,'UPD',KdRAP);
	$('#Form_Uraian_EDIT #Uraian').val(Uraian);
	$('#Form_Uraian_EDIT #Uom').val(Uom);
	$('#Form_Uraian_EDIT #Vol').val(Vol);
	$('#Form_Uraian_EDIT #HrgSatuan').val(HrgSatuan);

	if (NoKO.substr(0,2)=='KO' && TipeForm=='05B' ) {
		$('#Form_Uraian_EDIT #Uom').prop('readonly',true);
		$('#Form_Uraian_EDIT #HrgSatuan').prop('readonly',true);
	}else{
		$('#Form_Uraian_EDIT #Uom').prop('readonly',false);
		$('#Form_Uraian_EDIT #HrgSatuan').prop('readonly',false);
	}

	$('#Modal_EditUraian').modal('show');
}


function get_KdRAP(NoUrut="",flag='',KdRAP=''){
	var TipeForm = $('#TipeForm option:selected').val();
	var JobNo = $('#FormPD_Lapangan').attr('data-JobNo');
	var Aksi = $('#FormPD_Lapangan').attr('data-Aksi');
	var Alokasi = $('#Alokasi').val();
	var TipePD = $('#FormPD_Lapangan').attr('data-TipePD');
	var NoPD = $('#NoPD').val();
	var NoKO = $('#NoKO option:selected').val();
	// alert(KdRAP)

	if (NoKO=='') {
		alert('belum pilih No.KO');
		return false;
	}

	$.ajax({
		url: "<?=site_url('Entry/PDPJ/EntryPDLap_C/get_KdRAP')?>",
		type: 'POST',
		dataType: 'HTML',
		data: {
			JobNo : JobNo, 
			TipeForm : TipeForm, 
			Aksi : Aksi, 
			TipePD : TipePD, 
			NoKO : NoKO, 
			Alokasi : Alokasi, 
			NoPD : NoPD,
			NoUrut : NoUrut,
			KdRAP : KdRAP,
		},
		beforeSend : function(){
			$('#btnAddUraian').attr('disabled',true);
		},complete : function(){
			$('#btnAddUraian').attr('disabled',false);
		}
	})
	.done(function(data) {

		if(flag=='UPD'){
			$('#Form_Uraian_EDIT #KdRAP').html('');
			$('#Form_Uraian_EDIT #KdRAP').html(data);

		}else{
			$('#Form_Uraian_ADD #KdRAP').html('');
			$('#Form_Uraian_ADD #KdRAP').html(data);
		}


	})
	.fail(function() {
		alert('Something Wrong');
	});


}

function get_tr_UraianPD(){
	var JobNo 		= $('#FormPD_Lapangan').attr('data-JobNo');
	var Alokasi 	= $('#FormPD_Lapangan').attr('data-Alokasi');
	var Aksi 		= $('#FormPD_Lapangan').attr('data-Aksi');
	var TipePD 		= $('#FormPD_Lapangan').attr('data-TipePD');
	var TipeForm	= $('#TipeForm option:selected').val();
	var NoKO		= $('#NoKO option:selected').val();
	var NoPD		= $('#NoPD').val();

	// alert(JobNo+' - '+Alokasi+' - '+Aksi+' - '+TipePD+' - '+TipeForm+' - '+NoKO+' - '+NoPD);
	// return false;

	jQuery.ajax({
		url: "<?=site_url('Entry/PDPJ/EntryPDLap_C/get_tr_UraianPD')?>",
		type: 'POST',
		dataType: 'html',
		data: {
			NoKO : NoKO,
			JobNo : JobNo,
			Alokasi : Alokasi,
			Aksi : Aksi,
			TipePD : TipePD,
			TipeForm : TipeForm,
			NoPD : NoPD,
		},
		beforeSend : function(){
			$('#loading_halaman').show();
		}, complete : function(){
			$('#loading_halaman').hide();
		},
		success: function(data, textStatus, xhr) {

			$('#tampil_table').html('');
			$('#tampil_table').html(data);
			Get_SubTotal();
		},
		error: function(xhr, textStatus, errorThrown) {
			alert('something wrong');
		}
	});

}

function Get_SubTotal(){
	var SubTotal = 0;
	if ($('#mytable_uraianPD tbody').find('tr').length > 0 ) {
		$('#mytable_uraianPD > tbody > tr').each(function(index,tr){
			var Vol = 0;
			var HrgSatuan = 0 ;

			Vol = ($(this).find(".VolClass").text()).split(',').join('').split('$').join('');
			HrgSatuan = ($(this).find(".HrgSatuanClass").text()).split(',').join('').split('$').join('');

			Vol = parseFloat(Vol);
			HrgSatuan = parseFloat(HrgSatuan);


			SubTotal += Vol * HrgSatuan;
		});
	}

	$('#SubTotal').val(FormatVol_Buatan(SubTotal));
	Get_Total();
}

function Get_Total(){
	var Total = 0;
	var SubTotal = ($('#SubTotal').val().split(',').join('').split('$').join(''));
	var Saldo = ($('#Saldo').val().split(',').join('').split('$').join(''));

	SubTotal = parseFloat(SubTotal);
	Saldo = parseFloat(Saldo);
	Total = SubTotal - Saldo;
	$('#TotalPermintaan').val(FormatVol_Buatan(Total));
}

function BindKO(){
	var JobNo 		= $('#FormPD_Lapangan').attr('data-JobNo');
	var Alokasi 	= $('#FormPD_Lapangan').attr('data-Alokasi');
	var Aksi 		= $('#FormPD_Lapangan').attr('data-Aksi');
	var TipePD 		= $('#FormPD_Lapangan').attr('data-TipePD');
	var TipeForm	= $('#TipeForm option:selected').val();

	jQuery.ajax({
		url: "<?=site_url('Entry/PDPJ/EntryPDLap_C/BindKO')?>",
		type: 'POST',
		dataType: 'HTML',
		data: {
			JobNo : JobNo,
			Alokasi : Alokasi,
			Aksi : Aksi,
			TipePD : TipePD,
			TipeForm : TipeForm,
		},
		beforeSend : function(){
				// $('#NoKO').attr('disabled',true);
			$('#loading_halaman').show();
		}, complete : function(){
				// $('#NoKO').attr('disabled',false);
			$('#loading_halaman').hide();
			TampilInvoice(1);
		},
		success: function(data, textStatus, xhr) {

			$('#NoKO').html('');
			$('#NoKO').html(data);
			// $('#NoKO').val(NoKOGlobal);
			// get_tr_UraianPD();

		},
		error: function(xhr, textStatus, errorThrown) {
			alert('something wrong');
		}
	});

}

function BindPJ(){
	var JobNo 		= $('#FormPD_Lapangan').attr('data-JobNo');
	var Alokasi 	= $('#FormPD_Lapangan').attr('data-Alokasi');
	var Aksi 		= $('#FormPD_Lapangan').attr('data-Aksi');
	var TipePD 		= $('#FormPD_Lapangan').attr('data-TipePD');
	var TipeForm	= $('#TipeForm option:selected').val();

	

	jQuery.ajax({
		url: "<?=site_url('Entry/PDPJ/EntryPDLap_C/BindPJ')?>",
		type: 'POST',
		dataType: 'HTML',
		data: {
			JobNo : JobNo,
			Alokasi : Alokasi,
			Aksi : Aksi,
			TipePD : TipePD,
			TipeForm : TipeForm,
			NoPJSebelumnyaGlobal : NoPJSebelumnyaGlobal,
		},
		beforeSend : function(){
			$('#NoPJSebelumnya').attr('disabled',true);
		}, complete : function(){
			$('#NoPJSebelumnya').attr('disabled',false);

		},
		success: function(data, textStatus, xhr) {
			$('#NoPJSebelumnya').html('');
			$('#NoPJSebelumnya').html(data);
			// alert(data)
			// panggil_Saldo();
			if (NoPJSebelumnyaGlobal!='') {
				$('#NoPJSebelumnya').val(NoPJSebelumnyaGlobal).trigger('change');

			}
			// if ($('#FormPD_Lapangan').attr('data-ApprovedByAK') !='') {
			// 	$('#NoPJSebelumnya').prop('disabled','disabled');
			// }
			// 



		},
		error: function(xhr, textStatus, errorThrown) {
			alert('something wrong');
		}
	});


}

// function PJGantiOtomatis(){
// 	if (true) {}
// }

function panggil_Saldo(){
	var JobNo 		= $('#FormPD_Lapangan').attr('data-JobNo');
	var Alokasi 	= $('#FormPD_Lapangan').attr('data-Alokasi');
	var Aksi 		= $('#FormPD_Lapangan').attr('data-Aksi');
	var TipePD 		= $('#FormPD_Lapangan').attr('data-TipePD');
	var TipeForm	= $('#TipeForm option:selected').val();

	var TglPD	= $('#TglPD').val();
	var NoPD	= $('#NoPD').val();

	jQuery.ajax({
		url: "<?=site_url('Entry/PDPJ/EntryPDLap_C/GetSaldo')?>",
		type: 'POST',
		dataType: 'HTML',
		data: {
			JobNo : JobNo,
			Alokasi : Alokasi,
			Aksi : Aksi,
			TipePD : TipePD,
			TipeForm : TipeForm,
			NoPD : NoPD,
		},
		beforeSend : function(){
			$('#loading_halaman').show();
		}, complete : function(){
			$('#loading_halaman').hide();
		},
		success: function(data, textStatus, xhr) {
				// alert(data);
			$('#NoPJSebelumnya').val(data).trigger('change');
		},
		error: function(xhr, textStatus, errorThrown) {
			alert('something wrong');
		}
	});

}

function GetSaldo(){
	var JobNo 		= $('#FormPD_Lapangan').attr('data-JobNo');
	var Alokasi 	= $('#FormPD_Lapangan').attr('data-Alokasi');
	var Aksi 		= $('#FormPD_Lapangan').attr('data-Aksi');
	var TipePD 		= $('#FormPD_Lapangan').attr('data-TipePD');
	var TipeForm	= $('#TipeForm option:selected').val();

	var TglPD	= $('#TglPD').val();
	var NoPD	= $('#NoPD').val();

	$('#NoPJSebelumnya').html('');
	$('#TxtNoPJSebelumnya').val('');
	$('#Saldo').val(0);

	if(TglPD>='2019-12-12' || (Alokasi!='B' && Alokasi!='C')  ){

		BindPJ();

	}else{
			// alert('disini');
	}


}

function TampilInvoice(flag=0){
	if (flag==0) {
		$('.tab_invoice').hide();
		$('#tab_2').hide();
	}else{
		$('.tab_invoice').show();
		$('#tab_2').show();
	}
}


function FormatVol_Buatan(num)
{
	var str = num.toString().replace("", ""), parts = false, output = [], i = 1, formatted = null;
	if(str.indexOf(".") > 0) {
		parts = str.split(".");
		str = parts[0];
	}
	str = str.split("").reverse();
	for(var j = 0, len = str.length; j < len; j++) {
		if(str[j] != ",") {
			output.push(str[j]);
			if(i%3 == 0 && j < (len - 1)) {
				output.push(",");
			}
			i++;
		}
	}
	formatted = output.reverse().join("");
	return("" + formatted + ((parts) ? "." + parts[1].substr(0, 3) : ""));
}

function FormatVol_Buatan_HrgSatuan(num)
{
	var str = num.toString().replace("", ""), parts = false, output = [], i = 1, formatted = null;
	if(str.indexOf(".") > 0) {
		parts = str.split(".");
		str = parts[0];
	}
	str = str.split("").reverse();
	for(var j = 0, len = str.length; j < len; j++) {
		if(str[j] != ",") {
			output.push(str[j]);
			if(i%3 == 0 && j < (len - 1)) {
				output.push(",");
			}
			i++;
		}
	}
	formatted = output.reverse().join("");
	return("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
}

function FormatUang_TotalBayar_Class(num)
{
	var str = num.toString().replace("", ""), parts = false, output = [], i = 1, formatted = null;
	if(str.indexOf(".") > 0) {
		parts = str.split(".");
		str = parts[0];
	}
	str = str.split("").reverse();
	for(var j = 0, len = str.length; j < len; j++) {
		if(str[j] != ",") {
			output.push(str[j]);
			if(i%3 == 0 && j < (len - 1)) {
				output.push(",");
			}
			i++;
		}
	}
	formatted = output.reverse().join("");
	return("" + formatted);
}

</script>