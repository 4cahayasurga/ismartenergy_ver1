<script type="text/javascript">
	var UrlSaya = "<?=site_url('Entry/PDPJ/EntryPJKeu/')?>";

	$(document).ready(function(){
		CekAlokasi();
		tablePJKeu();
		// GetSaldo();
		// GetTotal();

		$(document).on('click','#btnUraianSamaDenganPD',function(){
			var NoPD = $('#FormPJ_Keuangan #NoPD').val();
			// alert(NoPD);
			// return false;

			if (!confirm('Anda yakin ?')) {
				return false;
			}

			jQuery.ajax({
				url: UrlSaya+'/Aksi_UraianSamaDenganPD',
				type: 'POST',
				dataType: "html",
				data: {NoPD: NoPD},
				beforeSend : function(){
					$(this).text('Loading...').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$(this).text('Isi Uraian Apabila = PD').attr('disabled',true);
					
				},
				success: function(data, textStatus, xhr) {
					if (data=='berhasil') {
						toastr.success('Success');
					}else{
						toastr.error('Failed!');
					}
					tablePJKeu();

				},
				error: function(xhr, textStatus, errorThrown) {
					alert('Something Wrong');
				}
			});
		});

		// -- CONTOL URAIAN TAMBAH
		$("#formAddUraian #HrgSatuan").on('input', function(e) 
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

		$("#formAddUraian #HrgSatuan").focusin(function()
		{
			if (this.value=='0') {
				$(this).val('');
			}
		});

		$("#formAddUraian #HrgSatuan").focusout(function()
		{
			if (this.value=='') {
				$(this).val(0);
			}else{
				var HrgSatuan = parseFloat(this.value);
				HrgSatuan = Number(HrgSatuan).toFixed();
				HrgSatuan = FormatVol_Buatan_HrgSatuan(HrgSatuan);
				$(this).val(HrgSatuan);
			}
		});



		$(document).on('click','#btnAddUraian',function(){
			// $('#formAddUraian')[0].reset();
			$("#formAddUraian #Action").val('NEW');
			$("#formAddUraian #No").val('');
			$("#formAddUraian #Uraian").val('');
			$("#formAddUraian #KdRAP").html('');
			$("#formAddUraian #KdRAP").append('<option value="C.00">Pengembalian Saldo</option>');
			var Vol = 1.000;
			$("#formAddUraian #Vol").val(Vol.toFixed(3));
			$("#formAddUraian #Uom").val('Ls');
			$("#formAddUraian #HrgSatuan").val(0);

			$('#modalAdd').modal('show');
		});


		$(document).on('submit','#formAddUraian',function(e){
			e.preventDefault();

			if ($('#formAddUraian #Uraian').val()=='') {
				alert('Uraian belum di-isi.');
				$('#formAddUraian #Uraian').focus();
				return false;
			}

			if ($('#formAddUraian #HrgSatuan').val()=='' || $('#formAddUraian #HrgSatuan').val()==0 ) {
				alert('Harga satuan belum di-isi.');
				$('#formAddUraian #HrgSatuan').focus();
				return false;
			}

			var NoUrut_Uraian = 1;
			if ($("#formAddUraian #Action").val()=='NEW') {
				
				if ($('#mytable_uraianPD > tbody > tr').length==0) {
					NoUrut_Uraian = 1;
				}else{
					
					NoUrut_Uraian = parseInt($('#mytable_uraianPD > tbody > tr').length) + 1;
				}
			}

			var data = $(this).serializeArray();
			data.push({name: 'NoUrut', value: NoUrut_Uraian});
			data.push({name: 'NoPD', value: $('#FormPJ_Keuangan #NoPD').val()});

			jQuery.ajax({
				url: UrlSaya+'AddNewUraian',
				type: 'POST',
				dataType: 'html',
				data: data,
				beforeSend : function(){
					$('#btnSimpanUraian').text('Loading...').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('#btnSimpanUraian').text('Simpan').attr('disabled',false);
				},
				success: function(data, textStatus, xhr) {
					if (data=='berhasil') {
						toastr.success('Saved');
					}else{
						toastr.success('Failed!');
					}

					tablePJKeu();
					$('#modalAdd').modal('hide');
				},
				error: function(xhr, textStatus, errorThrown) {
					alert('Something Wrong');
				}
			});
		})



		// -- CONTOL URAIAN UPDATE
		$("#formUpdateUraian #PjHrgSatuan").on('input', function(e) 
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

		$("#formUpdateUraian #PjHrgSatuan").focusin(function()
		{
			if (this.value=='0') {
				$(this).val('');
			}
		});

		$("#formUpdateUraian #PjHrgSatuan").focusout(function()
		{
			if (this.value=='') {
				$(this).val(0);
			}else{
				var HrgSatuan = parseFloat(this.value);
				HrgSatuan = Number(HrgSatuan).toFixed();
				HrgSatuan = FormatVol_Buatan_HrgSatuan(HrgSatuan);
				$(this).val(HrgSatuan);
			}
		});

		$("#formUpdateUraian #PjVol").on('input', function(e) 
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

		$("#formUpdateUraian #PjVol").focusin(function()
		{
			if (this.value=='0.000' || this.value=='0') {
				$(this).val('');
			}
		});

		$("#formUpdateUraian #PjVol").focusout(function()
		{
			var NoUrut = $(this).attr('data-NoUrut');

			if (this.value=='') {
				$(this).val('0.000');
			}else{
				var Vol = parseFloat(this.value);
				Vol = Number(Vol).toFixed(3);
				Vol = FormatVol_Buatan(Vol);
				$(this).val(Vol);
			}
		});

		$(document).on('submit','#formUpdateUraian',function(e){
			e.preventDefault();

			if ($('#formUpdateUraian #PjUraian').val()=='') {
				alert('Uraian belum di-isi.');
				$('#formUpdateUraian #PjUraian').focus();
				return false;
			}

			if ($('#formUpdateUraian #PjHrgSatuan').val()=='' || $('#formUpdateUraian #PjHrgSatuan').val()==0 ) {
				alert('Harga satuan belum di-isi.');
				$('#formUpdateUraian #PjHrgSatuan').focus();
				return false;
			}

			var data = $(this).serializeArray();
			data.push({name: 'NoUrut', value: $('#formUpdateUraian #No').val()});
			data.push({name: 'NoPD', value: $('#FormPJ_Keuangan #NoPD').val()});

			jQuery.ajax({
				url: UrlSaya+'UpdateUraian',
				type: 'POST',
				dataType: 'html',
				data: data,
				beforeSend : function(){
					$('#btnUpdateUraian').text('Loading...').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('#btnUpdateUraian').text('Update').attr('disabled',false);
				},
				success: function(data, textStatus, xhr) {

					if (data=='berhasil') {
						toastr.success('Updated');
					}else{
						toastr.error('Failed!');
					}

					tablePJKeu();
					$('#modalUpdate').modal('hide');
				},
				error: function(xhr, textStatus, errorThrown) {
					alert('Something Wrong');
				}
			});
		})


		// -- PROSES UPDATE PJ
		$(document).on('submit','#FormPJ_Keuangan',function(e){
			e.preventDefault();

			var Url_Batal 	= $('#FormPJ_Keuangan').attr('data-Url_Batal');

			if ($('#FormPJ_Keuangan #TglPJ').val()=='') {
				alert('Tgl permintaan belum di-isi.');
				$('#FormPJ_Keuangan #TglPJ').focus();
				return false;
			}

			if ($('#FormPJ_Keuangan #TglPJ').val() < $('#FormPJ_Keuangan #TglPD').val() ) {
				alert('Tgl PJ lebih kecil dari Tgl Permintaan.');
				$('#FormPJ_Keuangan #TglPJ').focus();
				return false;
			}

			if (Validasi_BuktiPendukung_Checkbox()=='') {
				alert('Belum pilih bukti pendukung.');
				return false;
			}

			var flag_berhenti = 0;

			if ($('#mytable_uraianPD tbody').find('tr').length == 0 ) {
				alert('data tidak ditemukan');
				flag_berhenti = 1;
				return false
			}

			if ($('#mytable_uraianPD tbody').find('tr').length > 0 ) {
				$('#mytable_uraianPD > tbody  > tr').each(function(index, tr) { 
					if ($(this).find(".PjUraian_Class").val()=='') {
						alert('No. '+$(this).find(".NoUrut_Class").val()+' belum isi PJ.');
						flag_berhenti = 1;
						return false;
					}
					
				});
			}

			if (flag_berhenti==1) {
				return false;
			}

			var data 	= $(this).serializeArray();
			var NoPD 	= $('#FormPJ_Keuangan #NoPD').val();
			var NoPJ 	= "PJ"+NoPD.substring(2);
			var TotalPJ = ($(this).find(".TotalPertanggungJawaban_Class").text()).split(',').join('').split('$').join('');
			TotalPJ = parseFloat(TotalPJ);

			data.push({name: 'NoPD', value: NoPD});
			data.push({name: 'NoPJ', value: NoPJ});
			data.push({name: 'TotalPJ', value : TotalPJ});

			jQuery.ajax({
				url: UrlSaya+'UpdatePD_To_Database',
				type: 'POST',
				dataType: 'html',
				data: data,
				beforeSend : function(){
					$('#btnUpdatePD').text('Loading...').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('#btnUpdatePD').text('Update').attr('disabled',false);
				},
				success: function(data, textStatus, xhr) {
					if (data=='berhasil') {
						// toastr.success('Updated');
						// alert(Url_Batal);
						window.location.href=Url_Batal;
					}else{
						toastr.error('Failed!');
					}

				},
				error: function(xhr, textStatus, errorThrown) {
					alert('Something Wrong');
				}
			});
		})




	});



function CekBelumIsiPJ(){

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

function btnSelect(NoUrut){
	var ID = $('#btnSelect_'+NoUrut);
	var KdRAP = ID.attr('data-KdRAP');
	var Uraian = ID.attr('data-Uraian');
	var Vol = ID.attr('data-Vol');
	var Uom = ID.attr('data-Uom');
	var HrgSatuan = ID.attr('data-HrgSatuan');
	var NoPJ = ID.attr('data-NoPJ');
	var PjUraian = ID.attr('data-PjUraian');
	var PjVol = ID.attr('data-PjVol');
	var PjHrgSatuan = ID.attr('data-PjHrgSatuan');

	$('#formUpdateUraian #Action').val('UPD');
	$('#formUpdateUraian #No').val(NoUrut);
	$('#formUpdateUraian #KdRAP').val(KdRAP);
	$('#formUpdateUraian #Uraian').val(Uraian);
	$('#formUpdateUraian #Vol').val(Vol);
	$('#formUpdateUraian #Uom').val(Uom);
	$('#formUpdateUraian #HrgSatuan').val(HrgSatuan);

	$('#formUpdateUraian #PjUraian').val(PjUraian);
	$('#formUpdateUraian #PjVol').val(PjVol);
	$('#formUpdateUraian #PjHrgSatuan').val(PjHrgSatuan);

	$('#modalUpdate').modal('show');

		// alert(KdRAP)


		// data-KdRAP="<?=$row->KdRAP?>" 
		// data-Uraian="<?=$row->Uraian?>"
		// data-Vol="<?=UbahDecimal_Nol_Tiga($row->Vol)?>"
		// data-Uom="<?=$row->Uom?>"
		// data-HrgSatuan="<?=number_format($row->HrgSatuan)?>"
		// data-NoPJ="<?=$row->NoPJ?>"
		// data-PjUraian="<?=$row->PjUraian?>"
		// data-PjVol="<?=UbahDecimal_Nol_Tiga($row->PjVol)?>" 
		// data-PjHrgSatuan="<?=number_format($row->PjHrgSatuan)?>"
}

// function btnUraianSamaDenganPD(){
// 	var NoPD = $('#FormPJ_Keuangan #NoPD').val();
// 	// alert(NoPD);
// 	// return false;

// 	jQuery.ajax({
// 		url: UrlSaya+'/Aksi_UraianSamaDenganPD',
// 		type: 'POST',
// 		dataType: "html",
// 		data: {NoPD: NoPD},
// 		beforeSend : function(){
// 			$('#btnUraianSamaDenganPD').text('Loading...').attr('disabled',true);
// 		},
// 		complete: function(xhr, textStatus) {
// 			$('#btnUraianSamaDenganPD').text('Isi Uraian Apabila = PD').attr('disabled',true);
// 			tablePJKeu();
// 		},
// 		success: function(data, textStatus, xhr) {
// 			if (data=='berhasil') {
// 				toastr.success('Success');
// 			}else{
// 				toastr.error('Failed!');
// 			}

// 		},
// 		error: function(xhr, textStatus, errorThrown) {
// 			alert('Something Wrong');
// 		}
// 	});
// }

function tablePJKeu(){
	var NoPD = $('#FormPJ_Keuangan #NoPD').val();

	jQuery.ajax({
		url: UrlSaya+'/tampilkan_tablePJKeu',
		type: 'POST',
		dataType: "html",
		data: {NoPD: NoPD},
		beforeSend : function(){
			$('#tampil_table').html('<i class="fa fa-spinner fa-spin" style="font-size:20px !important"></i> ');
		},
		complete: function(xhr, textStatus) {
		},
		success: function(data, textStatus, xhr) {
			$('#tampil_table').html(data);
			GetSaldo();

		},
		error: function(xhr, textStatus, errorThrown) {
			alert('Something Wrong');
		}
	});
}

function CekAlokasi(){
	if($('#FormPJ_Keuangan').attr('data-Alokasi')=='C'){
		$('#btnAddUraian').attr('disabled',false);
	}else{
		$('#btnAddUraian').attr('disabled',true);
	}
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
	return("" + formatted );
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

function GetSaldo(){

	var Saldo 			= 0;
	var TotalPJ 		= 0;
	var DanaDiLapangan 	= 0;

	TotalPJ 			= ($('.TotalPertanggungJawaban_Class').text()).split(',').join('').split('$').join('');
	DanaDiLapangan 		= ($('#DanaDiLapangan').val()).split(',').join('').split('$').join('');
		// alert(TotalPJ);

	TotalPJ 			= parseFloat(TotalPJ);
	DanaDiLapangan 		= parseFloat(DanaDiLapangan);
	Saldo 				= DanaDiLapangan - TotalPJ;

		// console.log(TotalPJ+' - '+DanaDiLapangan);

	$('#Saldo').val(FormatVol_Buatan_HrgSatuan(Saldo));

}

function GetTotal(){
	var Total = 0;
		// var DanaDiLapangan

	if ($('#mytable_uraianPD tbody').find('tr').length > 0 ) {
		$('#mytable_uraianPD > tbody  > tr').each(function(index, tr) { 

			var PjVol = ($(this).find(".PjVol_tableClass").val()).split(',').join('').split('$').join('');
			var PjHrgSatuan = ($(this).find(".PjHrgSatuan_tableClass").val()).split(',').join('').split('$').join('');

			PjVol = parseFloat(PjVol);
			PjHrgSatuan = parseFloat(PjHrgSatuan);
			Total += PjVol * PjHrgSatuan;
		});
	}


	$('.TotalPertanggungJawaban_Class').text(Total);

}

</script>