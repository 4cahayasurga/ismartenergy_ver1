<script type="text/javascript" src="<?= base_url('assets/jquery.mask.min.js') ?>"></script>
<script type="text/javascript">
	var JobNo = $('#myform').attr('data-JobNo');
	var UrlTetap = "<?=site_url('Entry/Procurement/Invoice/')?>";
	var Aksi = $('#myform').attr('data-Aksi');

	$(document).ready(function(){


		$(document).on('change','#myform #NoKO_txt',function(){
			NoKO_Berganti();
		});

		$('.select2').select2();

		$('.js-example-basic-multiple').select2({dropdownAutoWidth : true});

		$("#table1").dataTable({
			"order": [[ 0, 'desc' ]],
			"pageLength": 100,
			"columnDefs": [{ "visible": false, "targets": 0 },
				{ "targets": 1, "orderable" : false }
				]
		});


		$('#myform #FPNo').mask('099.099-99.09999999', {
			placeholder: "___.___-__.________"
		});

		// -- WILAYAH TOTAL
		$(document).on('input','#myform #Total',function(){
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

		$(document).on('focusin','#myform #Total',function(){
			if (this.value=='0.00' || this.value=='0') {
				$(this).val('');
			}else{
				$(this).val(this.value.split(",").join(""));
			}
		});

		$(document).on('focusout','#myform #Total',function(){
			if (this.value=='') {
				$(this).val(0);
			}else{
				var ValIni = this.value;
				ValIni = ValIni.split(",").join("");
				ValIni = parseFloat(ValIni);
				ValIni = Format_NilaiKontrak(ValIni);
				$(this).val(addCommas(ValIni));
			}

			PengecekanPPN();
		});

		// -- WILAYAH PPN
		$(document).on('input','#myform #PPN',function(){
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

		$(document).on('focusin','#myform #PPN',function(){
			if (this.value=='0.00' || this.value=='0') {
				$(this).val('');
			}else{
				$(this).val(this.value.split(",").join(""));
			}
		});

		$(document).on('focusout','#myform #PPN',function(){
			if (this.value=='') {
				$(this).val(0);
			}else{
				var ValIni = this.value;
				ValIni = ValIni.split(",").join("");
				ValIni = parseFloat(ValIni);
				ValIni = Format_NilaiKontrak(ValIni);
				$(this).val(addCommas(ValIni));
			}
		});

		// -- Wilayah PPH
		$(document).on('input','#myform #PPH',function(){
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

		$(document).on('focusin','#myform #PPH',function(){
			if (this.value=='0.00' || this.value=='0') {
				$(this).val('');
			}else{
				$(this).val(this.value.split(",").join(""));
			}
		});

		$(document).on('focusout','#myform #PPH',function(){
			if (this.value=='') {
				$(this).val(0);
			}else{
				var ValIni = this.value;
				ValIni = ValIni.split(",").join("");
				ValIni = parseFloat(ValIni);
				ValIni = Format_NilaiKontrak(ValIni);
				$(this).val(addCommas(ValIni));
			}
		});

		$(document).on('focusout','#myform #FPNo',function(){
			PengecekanPPN();
		});

		$('#myform select[name="NoSJ"]').change(function(){
			NoSJ_Berganti();
		});

		// -- WILAYAH TABLE ORDER
		$(document).on('click', '#select_all', function() {          	
			$(".emp_checkbox").prop("checked", this.checked);
			$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
			
			CekTotalCentang();
		});

		$(document).on('click', '.emp_checkbox', function() {		
			if ($('.emp_checkbox:checked').length == $('.emp_checkbox').length) {
				$('#select_all').prop('checked', true);

			} else {
				$('#select_all').prop('checked', false);
			}
			$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
			
			CekTotalCentang();
			
		});

		// -- WILAYAH TOTAL
		$(document).on('input','.VolInputClass',function(){
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

		$(document).on('focusin','.VolInputClass',function(){
			if (this.value=='0.000' || this.value=='0') {
				$(this).val('');
			}else{
				$(this).val(this.value.split(",").join(""));
			}
		});

		$(document).on('focusout','.VolInputClass',function(){
			if (this.value=='') {
				$(this).val(0);
			}else{
				var ValIni = this.value;
				ValIni = ValIni.split(",").join("");
				ValIni = parseFloat(ValIni);
				// ValIni = FormatVol(ValIni);
				$(this).val(FormatVol(ValIni));
			}

			CekTotalCentang();

		});

		// $('#myform input[name="InvNo"]').tooltip({'trigger':'focus', 'title': 'Password tooltip'});

		// -- SELECT SISA SJ
		$('#myform #SisaInvoice').change(function(){
			SisaInvoice_Berganti();
		});


		// - AKSI BUTTON SIMPAN
		$(document).on('submit','#myform',function(event){
			event.preventDefault();

			
			// alert($('#myform .TotalBarang').val()+' - '+$('#myform input[name="Total"]').val());
			// return false;

			var NoKO 	= $('#myform input[name="NoKO"] option:selected').val();
			var InvNo 	= $('#myform input[name="InvNo"]').val();
			var InvDate = $('#myform input[name="InvDate"]').val();
			var DueDate = $('#myform input[name="DueDate"]').val();

			var FPNo 	= $('#myform input[name="FPNo"]').val();
			var FPDate 	= $('#myform input[name="FPDate"]').val();
			var PPN 	= $('#myform input[name="PPN"]').val();
			var Total 	= $('#myform input[name="Total"]').val();

			if (NoKO=='') {
				alert('belum pilih No.KO');
				return false;
			}

			if (InvNo=='') {
				alert('Nomor Invoice belum di-isi');
				$('#myform input[name="InvNo"]').focus();
				return false;
			}

			if (DueDate < InvDate) {
				alert('Jatuh Tempo < Tgl Invoice.');
				$('#myform input[name="DueDate"]').focus();
				return false;
			}

			if (FPNo!='' || FPDate!='' ) {
				if (PPN==0) {
					alert('PPN belum diisi.');
					$('#myform input[name="FPNo"]').focus();
					return false;
				}
			}

			if (Total==0) {
				alert('Total belum diisi.');
				$('#myform input[name="FPNo"]').focus();
				return false;
			}


			if ($('div.tampil_tableSJClass').is(':empty')) {
				return false;
			}

			if (CekVolInvoice()==false) {
				alert('Vol Masuk > Vol Invoice');
				return false;
			}

			if ($('#myform #NoSJ option:selected').val() !='' ) {
				if ($('#tableSJ tbody tr').length > 0) {
					var TotalTagihan = ($('#myform input[name="Total"]').val()).split(',').join('');
					var TotalCentang = ($('#myform input[name="TotalCentang"]').val()).split(',').join('');
					TotalTagihan = parseFloat(TotalTagihan);
					TotalCentang = parseFloat(TotalCentang);

					if (TotalTagihan > TotalCentang) {
						alert('Total Tagihan > Total Centang');
						return false;
					}

				}
				// alert($('#tableSJ tbody tr').length);
				// return false;
			}

			// return false;

			var data_save = $('#myform').serializeArray();
			data_save.push({name : "Aksi", value : Aksi  });

			$.ajax({
				url: UrlTetap+'AksiSimpanInvoice',
				type: 'POST',
				dataType: 'html',
				data: data_save,
			})
			.done(function(data) {
				// console.clear();
				// console.log(data);
				// // alert(data);
				// return false;
				if (data=='berhasil') {
					window.location.href=UrlTetap+'index/'+JobNo;
					$('#myform #btnSimpan').attr('disabled',false);
				}else{
					alert(data);
					$('#myform #btnSimpan').attr('disabled',false);
					return false;
				}
			})
			.fail(function() {
				alert('something error');
			});
			

		});

	});

function SisaInvoice_Berganti(){
	var NoKO = $('#myform #NoKO_txt option:selected').val();
	var NoSJ_SisaInvoice = $('#myform #SisaInvoice option:selected').val();

	if (NoSJ_SisaInvoice=='') {
		$('#myform #tampil_SisaInvoice').html('');
		return false;
	}

	$.ajax({
		url: UrlTetap+'/table_SisaInvoice',
		type: 'post',
		dataType: 'html',
		data: {NoKO, JobNo, NoSJ_SisaInvoice},
	})
	.done(function(data) {
		$('#myform #tampil_SisaInvoice').html(data);
		// console.log(data);
		
	})
	.fail(function() {
		alert('something wrong');
	});
}

function ListSisaInvoice(){
	var NoKO = $('#myform #NoKO_txt option:selected').val();

	if (NoKO=='') {
		$('#myform #tampil_SisaInvoice').html('');
		$('#myform #SisaInvoice').html('<option value="">:: Pilih Salah Satu ::</option>');
		return false;
	}

	$.ajax({
		url: UrlTetap+'/ListSisaInvoice',
		type: 'post',
		dataType: 'html',
		data: {NoKO, JobNo},
	})
	.done(function(data) {
		$('#myform #SisaInvoice').html('');
		$('#myform #SisaInvoice').html(data);
		
	})
	.fail(function() {
		alert('something wrong');
	});
	
}

function CekVolInvoice(){
	if ($('#tableSJ tbody tr').length > 0) {
		var No1 = 1;
		$('#tableSJ tbody tr ').each(function(index,tr) {

			$(this).find('td input:checked').each(function () {

				var LedgerNo 	= ($(this).attr('data-LedgerNo'));
				var VolBrgMasuk	= parseFloat(($('#VolBrgMasuk_Text_ID_'+LedgerNo).text()).split(',').join(''));
				
				var VolInput	= parseFloat(($('#VolInput_ID_'+LedgerNo).val()).split(',').join(''));

				if (VolInput > VolBrgMasuk) {
					$('#peringatan_ID_'+LedgerNo).addClass('has-error')
					// $('#text_peringatan_ID_'+LedgerNo).show();
				}else{
					$('#peringatan_ID_'+LedgerNo).removeClass('has-error');
					// $('#text_peringatan_ID_'+LedgerNo).hide();
				}

				return false;

			});

		});

	}
}

function CekTotalCentang(){
	var selected = new Array();
	var Input = 0;
	var sumTotal = 0;
	if ($('#tableSJ tbody tr').length > 0) {
		var No1 = 1;
		$('#tableSJ tbody tr ').each(function(index,tr) {

			$(this).find('td input:checked').each(function () {

				var LedgerNo 	= ($(this).attr('data-LedgerNo'));
				var VolBrgMasuk	= parseFloat(($('#VolBrgMasuk_Text_ID_'+LedgerNo).text()).split(',').join(''));
				var HrgSatuan	= parseFloat(($('#HrgSatuan_Text_ID_'+LedgerNo).text()).split(',').join(''));
				var VolInput	= parseFloat(($('#VolInput_ID_'+LedgerNo).val()).split(',').join(''));

				sumTotal += VolInput * HrgSatuan;

			});

		});

	}

	$('.TotalCentang').val(addCommas(sumTotal.toFixed()));

}

function NoKO_Berganti(){
	var NoKO 		= $('#myform #NoKO_txt option:selected').val();
	var TglKO 		= $('#myform #NoKO_txt option:selected').attr('data-TglKO');
	var VendorNm 	= $('#myform #NoKO_txt option:selected').attr('data-VendorNm');
	var TotalKO 	= $('#myform #NoKO_txt option:selected').attr('data-TotalKO');

	$('#myform #TglKO').val(TglKO);
	$('#myform #VendorNm').val(VendorNm);
	$('#myform #TotalKO').val(TotalKO);
	$('#myform input[name="Total"]').val(TotalKO);

	if (NoKO!='') {
		$('#myform input[name="Total"]').val(TotalKO);
		PengecekanPPN();
	}else{
		$('#myform input[name="Total"]').val(0);
		$('#myform input[name="PPN"]').val(0);
	}

	$('#listorder').html('');
	// $('#myform #tampil_SisaInvoice').html('');

	List_SJ();
	// ListSisaInvoice();
}

function List_SJ(){
	var NoKO 		= $('#myform #NoKO_txt option:selected').val();

	if (NoKO =='') {
		$('#myform select[name="NoSJ"]').attr('readonly',true);
		$('#myform select[name="NoSJ"]').html('').append('<option value="">:: Pilih Salah Satu ::</option>');
		return false;
	}else{
		$('#myform select[name="NoSJ"]').attr('readonly',false);
	}

	$.ajax({
		url: UrlTetap+'/List_SJ',
		type: 'POST',
		dataType: 'html',
		data: {
			NoKO,
			JobNo,
			Aksi
		}
	})
	.done(function(response) {
		$('#myform select[name="NoSJ"]').html(response);
	})
	.fail(function() {
		// console.log("error");
		alert('something wrong');
	}).always(function(){
		// ListSisaInvoice()
	});



}

function NoSJ_Berganti(){
	var NoKO 		= $('#myform #NoKO_txt option:selected').val();
	var NoSJ 		= $('#myform select[name="NoSJ"] option:selected').val();

	if (NoSJ=='') {
		$('#myform #listorder').html('');
		return false;
	}

	$.ajax({
		url: UrlTetap+'/NoSJ_Berganti',
		type: 'POST',
		dataType: 'html',
		data: {
			NoKO,
			JobNo,
			Aksi,
			NoSJ
		}
	})
	.done(function(response) {
		$('#myform #listorder').html(response);
	})
	.fail(function() {
		alert('something wrong');
	});

}

function ValidasiTotalDenganCentang(){

	if ($('div.tampil_tableSJClass').is(':empty')) {
		return false;
	}

	var Total = JadikanFloat($('#myform input[name="Total"]').val());
	var TotalCentang = JadikanFloat($('.TotalCentang').val());

	if(Total > TotalCentang){
		return 'lebihbesar';

	}else if(Total < TotalCentang){
		return 'lebihkecil';
	}
}

function PengecekanPPN(){
	var Hitung = 0;
	var Total = JadikanFloat($('#myform input[name="Total"]').val());
	var FPNo  = $('#myform input[name="FPNo"]').val();
	if (Total==0) {
		$('#myform input[name="PPN"]').val(0);
	}

	if (FPNo!='') {
		Hitung = Total - (Total/1.1);
		Hitung = parseFloat(Hitung);
		Hitung = Format_NilaiKontrak(Hitung);
		$('#myform input[name="PPN"]').val(addCommas(Hitung));
	}else{
		$('#myform input[name="PPN"]').val(0);
	}
}


function addCommas(nStr) {
	nStr += '';
	var x = nStr.split('.');
	var x1 = x[0];
	var x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function Format_NilaiKontrak(num)
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
	return("" + formatted );
}

function FormatVol(num)
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

function JadikanFloat(ValIni){
	ValIni = ValIni.split(",").join("");
	ValIni = parseFloat(ValIni);
	return ValIni;
}


</script>