<script type="text/javascript">

	var a = <?=json_encode($forSelectKdRAP)?>;
	$(document).ready(function(){

		$('.select2').select2();

		$('.MingguKeClass').focusin(function(event) {
			if (this.value=='0.00' || this.value=='0') {
				$(this).val('');
			}
		});

		$('.MingguKeClass').focusin(function(event) {
			if (this.value=='0.00' || this.value=='0') {
				$(this).val('');
			}
		});

		$(".MingguKeClass").focusout(function()
		{
			if (this.value=='') {
				$(this).val('0');
			}
		});

		$('#PrdAkhir').change(function(){
			$('#TglPengakuan').val(this.value);
		});

		var maxField = 10;
		var addButton = $('.add_button');
		var wrapper = $('.field_wrapper');
		var x = 1; 


		$(document).on('click','.add_button',function(){
			if(x < maxField){ 
				x++; 
				var fieldHTML ='';

				fieldHTML +='<tr id="rowid_'+x+'">';
				fieldHTML +='<td class="text-center">'+x+'</td>';

				fieldHTML +='<td width="400px">';
				fieldHTML +='<input type="hidden" name="NoUrut[]" id="NoUrut_'+x+'" value="'+x+'" class="form-control input-sm NoUrutClass" disabled >';
				fieldHTML +='<select name="KdRAP[]" id="KdRAP_'+x+'" class="form-control input-sm  KdRAPClass" data-NoUrut="'+x+'">';
				fieldHTML +='<option value="" >:: Pilih Salah Satu ::</option>';
				$.each (a, function (bb) {
					fieldHTML +='<option value="'+a[bb].KdRAP+'" data-Uraian="'+a[bb].Uraian+'" data-Uom="'+a[bb].Uom+'" data-Vol="'+a[bb].Vol+'" data-HrgSatuan="'+a[bb].HrgSatuan+'" data-Tipe="'+a[bb].Tipe+'" data-Header="'+a[bb].Header+'" data-HrgRAB="'+a[bb].HrgRAB+'" data-TotalHargaKontrak="'+a[bb].TotalHargaKontrak+'" data-TotalHargaKontrak_Satuan="'+a[bb].TotalHargaKontrak_Satuan+'" data-BobotRAB="'+a[bb].BobotRAB+'"  >'+a[bb].KdRAP+' - '+a[bb].Uraian+'</option>';
				});
				fieldHTML +='</select>';
				fieldHTML +='</td>';

				fieldHTML +='<td hidden><input type="text" name="BobotRAB[]" id="BobotRAB_'+x+'" class="form-control input-sm BobotRABClass" readonly></td>';

				fieldHTML +='<td><textarea name="Uraian[]" id="Uraian_'+x+'" class="form-control" readonly></textarea><td>';
				fieldHTML +='<input type="text" name="Uom[]" id="Uom_'+x+'" class="form-control input-sm UomClass" readonly>';
				fieldHTML +='</td>';

				fieldHTML +='<td hidden ><input type="text" name="Vol[]" id="Vol_'+x+'" class="form-control input-sm VolClass" readonly></td>';

				fieldHTML +='<td hidden ><input type="text" name="HrgRAB[]" id="HrgRAB_'+x+'" class="form-control input-sm HrgRABClass" readonly></td>';

				fieldHTML +='<td  hidden><input type="text" name="HrgSatuan[]" id="HrgSatuan_'+x+'" class="form-control input-sm HrgSatuanClass" readonly></td>';

				fieldHTML +='<td  hidden><input type="text" name="TotalHargaKontrak[]" id="TotalHargaKontrak_'+x+'" class="form-control input-sm TotalHargaKontrakClass" readonly></td>';

				fieldHTML +='<td  hidden><input type="text" name="TotalHargaKontrak_Satuan[]" id="TotalHargaKontrak_Satuan_'+x+'" class="form-control input-sm TotalHargaKontrak_SatuanClass" readonly></td>';

				fieldHTML +='<td><input type="text" name="VolumeInputan[]" id="VolumeInputan_'+x+'" class="form-control input-sm text-right VolumeInputanClass" data-NoUrut="'+x+'" value="0.000"></td>';

				fieldHTML +='<td><input type="text" name="Bobot_Persen[]" id="Bobot_Persen_'+x+'" class="form-control input-sm text-right Bobot_PersenClass" data-NoUrut="'+x+'" value="0.000" readonly></td>';

				

				fieldHTML +='<td  hidden><input type="text" name="TotalHarga[]" id="TotalHarga_'+x+'" class="form-control input-sm text-right TotalHargaClass" value="0" ></td>';

				fieldHTML +='<td hidden ><input type="text" name="TotalHarga_Satuan[]" id="TotalHarga_Satuan_'+x+'" class="form-control input-sm text-right TotalHarga_SatuanClass" value="0" ></td>';

				fieldHTML +='<td  hidden><input type="text" name="Tipe[]" id="Tipe_'+x+'" class="form-control input-sm text-right TipeClass"  ></td>';

				fieldHTML +='<td  hidden><input type="text" name="Header[]" id="Header_'+x+'" class="form-control input-sm text-right HeaderClass"  ></td>';

				fieldHTML +='<td class="text-center"><a href="javascript:void(0);" class="btn btn-danger remove_button" data-NoUrut="'+x+'"  ><i class="fa fa-minus"></i></a></td>';


				fieldHTML +='</tr>';

				$(wrapper).append(fieldHTML);

				$('#KdRAP_'+x).select2();

				$("#Bobot_Persen_"+x+", #VolumeInputan_"+x+"").on('input', function(e) 
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
				$("#Bobot_Persen_"+x+", #VolumeInputan_"+x+"").focusin(function()
				{
					if (this.value=='0.000' || this.value=='0') {
						$(this).val('');
					}
				});
				$("#Bobot_Persen_"+x+", #VolumeInputan_"+x+"").focusout(function()
				{
					// var NoUrut = $(this).attr('data-NoUrut');
					// alert('NoUrut');
					if (this.value=='') {
						$(this).val('0.000');
					}else{
						var mybobotpersen = parseFloat(this.value);
						mybobotpersen = Number(mybobotpersen).toFixed(3);
						mybobotpersen = FormatVol_Buatan(mybobotpersen);
						$(this).val(mybobotpersen);
					}


					var TotalHarga = 0;
					var TotalHarga_Satuan = 0;

					var KdRAP = $('#KdRAP_'+x+' option:selected').val();
					if (KdRAP=='') {
						TotalHarga = 0;
					}else{
						var VolumeInputan = ($('#VolumeInputan_'+x).val()).split(',').join('').split('$').join('');
						var HrgRAB = ($('#HrgRAB_'+x).val()).split(',').join('').split('$').join('');
						var HrgSatuan = ($('#HrgSatuan_'+x).val()).split(',').join('').split('$').join('');

						HrgRAB = parseFloat(HrgRAB);
						HrgSatuan = parseFloat(HrgSatuan);
						VolumeInputan = parseFloat(VolumeInputan);

						TotalHarga 			= HrgRAB * VolumeInputan;
						TotalHarga_Satuan 	= HrgSatuan * VolumeInputan;
					}


					$('#TotalHarga_'+x).val(FormatVol_Buatan(TotalHarga));
					$('#TotalHarga_Satuan_'+x).val(FormatVol_Buatan(TotalHarga_Satuan));

					// GetBobotPersen(x);

					// --HITUNG bobot persen

					var param_BobotPersen = 0;

					var KdRAP_New = $('#KdRAP_'+x+' option:selected').val();
					if (KdRAP_New=='') {
						param_BobotPersen = 0;
					}else{
						var VolumeInputan_New = ($('#VolumeInputan_'+x).val()).split(',').join('').split('$').join('');
						var BobotRAB_New = ($('#BobotRAB_'+x).val()).split(',').join('').split('$').join('');
						var VolRAP_New = ($('#Vol_'+x).val()).split(',').join('').split('$').join('');

						VolumeInputan_New = parseFloat(VolumeInputan_New);
						BobotRAB_New = parseFloat(BobotRAB_New);
						VolRAP_New = parseFloat(VolRAP_New);
						if (VolumeInputan_New==0 || VolumeInputan_New=='') {
							param_BobotPersen = 0;
						}else{
							param_BobotPersen = (VolumeInputan_New / VolRAP_New) * BobotRAB_New;
						}

						// console.log('Volume Inputan : '+VolumeInputan+'\n Bobot RAB : '+BobotRAB+'\n Vol : '+VolRAP+'\n');
					}

					$('#Bobot_Persen_'+x).val(param_BobotPersen.toFixed(3));
					// $('#Bobot_Persen_'+x).val(param_BobotPersen);
					// $('#Bobot_Persen_'+x).val(Pembulatan(param_BobotPersen));


				});

				

			}else{
				alert('A maximum of '+maxField+' fields are allowed to be added. ');
			}


		});

$(document).on('click', '.remove_button', function(e){
	$('#rowid_'+x).remove();
	x--;
});

$(document).on('change','.KdRAPClass',function(){
			// KdRAPBerganti($(this).attr('data-NoUrut'));
	var NoUrut = $(this).attr('data-NoUrut');
	var Uraian = $(this).find('option:selected').attr('data-Uraian');
	var Uom = $(this).find('option:selected').attr('data-Uom');
	var Vol = $(this).find('option:selected').attr('data-Vol');
	var HrgSatuan = $(this).find('option:selected').attr('data-HrgSatuan');
	var HrgRAB = $(this).find('option:selected').attr('data-HrgRAB');
	var TotalHargaKontrak = $(this).find('option:selected').attr('data-TotalHargaKontrak');

	var TotalHargaKontrak_Satuan = $(this).find('option:selected').attr('data-TotalHargaKontrak_Satuan');

	var Tipe = $(this).find('option:selected').attr('data-Tipe');
	var Header = $(this).find('option:selected').attr('data-Header');

	// -- BobotRAB ADD DATE : 12 DESEMBER 2023
	var BobotRAB = $(this).find('option:selected').attr('data-BobotRAB');

	$('#Uraian_'+NoUrut).val(Uraian);
	$('#Uom_'+NoUrut).val(Uom);
	$('#Vol_'+NoUrut).val(Vol);
	$('#HrgRAB_'+NoUrut).val(HrgRAB);
	$('#HrgSatuan_'+NoUrut).val(HrgSatuan);
	$('#TotalHargaKontrak_'+NoUrut).val(TotalHargaKontrak);
	$('#TotalHargaKontrak_Satuan_'+NoUrut).val(TotalHargaKontrak_Satuan);

	$('#BobotRAB_'+NoUrut).val(BobotRAB);

	$('#Tipe_'+NoUrut).val(Tipe);
	$('#Header_'+NoUrut).val(Header);

	GetTotalHarga(NoUrut)
	GetBobotPersen(NoUrut);

});

$(".Bobot_PersenClass, .VolumeInputanClass").on('input', function(e) 
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

$(".Bobot_PersenClass, .VolumeInputanClass").focusin(function()
{
	if (this.value=='0.000' || this.value=='0') {
		$(this).val('');
	}
});

$(".Bobot_PersenClass, .VolumeInputanClass").focusout(function()
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
	GetTotalHarga(NoUrut);
	GetBobotPersen(NoUrut);
});



$('#myform').submit(function(event){
	event.preventDefault();

	var PrdAwal = $('#PrdAwal').val();
	var PrdAkhir = $('#PrdAkhir').val();
	var MingguKe = $('#MingguKe').val();
	var TglPengakuan = $('#TglPengakuan').val();

	if (MingguKe=='' || (MingguKe==0 || MingguKe=='0') ) {
		alert('Minggu belum di-isi / 0');
		$('#MingguKe').focus();
		return false;
	}

	if (TglPengakuan=='') {
		alert('Tgl Pengakuan belum di-isi');
		$('#TglPengakuan').focus();
		return false;
	}


	var flag_salah = 0;
	$('#tblsaya tbody tr').each(function() {
		var NoUrut = $( this ).find('.NoUrutClass').val();

		var KdRAP = $( this ).find('.KdRAPClass').val();

		var Bobot_Persen = $( this ).find('input[name="Bobot_Persen[]"]').val();
		var VolumeInputan = $( this ).find('input[name="VolumeInputan[]"]').val();
		var Vol = $( this ).find('input[name="VolumeInputan[]"]').val();
		var Tipe = $( this ).find('input[name="Tipe[]"]').val();


		if (KdRAP=='') {
			alert('KdRAP No '+NoUrut+' belum di-isi');
			$('#KdRAP_'+NoUrut).select2('focus');
			$('#KdRAP_'+NoUrut).select2('open');

			flag_salah = 1;
			return false;
		}

		if (Tipe=='Header') {
			alert('Tidak dapat menyimpan Kode RAP Tipe Header No.'+NoUrut+' ');
			$('#KdRAP_'+NoUrut).select2('focus');
			$('#KdRAP_'+NoUrut).select2('open');
			flag_salah = 1;
			return false;
		}

		// if (Bobot_Persen=='' || (Bobot_Persen==0 || Bobot_Persen=='0') ) {
		// 	alert('Bobot Persen No '+NoUrut+' belum di-isi / 0');
		// 	$('#Bobot_Persen_'+NoUrut).focus();
		// 	flag_salah = 1;
		// 	return false;
		// }

		if (VolumeInputan=='' || (VolumeInputan==0 || VolumeInputan=='0') ) {
			alert('Volume Input No '+NoUrut+' belum di-isi / 0');
			$('#VolumeInputan_'+NoUrut).focus();
			flag_salah = 1;
			return false;
		}


		var VolumeInputan_New 	= ($( this ).find('input[name="VolumeInputan[]"]').val()).split(',').join('').split('$').join('');
		var VolRAP 				= ($( this ).find('input[name="Vol[]"]').val()).split(',').join('').split('$').join('');
		var Bobot_Persen_New 	= ($( this ).find('input[name="Bobot_Persen[]"]').val()).split(',').join('').split('$').join('');

		VolumeInputan_New = parseFloat(VolumeInputan_New);
		VolRAP = parseFloat(VolRAP);

		if (VolumeInputan_New > VolRAP) {
			alert('Volume input > Volume RAP');
			$('#VolumeInputan_'+NoUrut).focus();
			flag_salah = 1;
			return false;
		}

		if (Bobot_Persen > 100) {
			alert('Max Bobot <= 100');
			$('#Bobot_Persen_'+NoUrut).focus();
			flag_salah = 1;
			return false;
		}

		// if (true) {}


		// alert(VolumeInputan_New+' - '+VolRAP);



		
	});

	if (flag_salah == 1) {
		return false;
	}
	// return false;

	jQuery.ajax({
		url: "<?=site_url('BasicData/FrmRPPM_C/SimpanMultipleInput_RPPM')?>",
		type: 'POST',
		dataType: 'html',
		data: $(this).serialize(),
		beforeSend : function(){
			$('#btnSimpan').text('Loading...').attr('disabled',true);
		},
		complete: function(xhr, textStatus) {
			$('#btnSimpan').text('Simpan').attr('disabled',false);
		},
		success: function(data, textStatus, xhr) {
			// console.log(data);
			// return false;

			if (data=='berhasil') {
				window.location.href="<?=site_url('BasicData/FrmRPPM_C')?>";
			}else{
				alert(data);
			}

		},
		error: function(xhr, textStatus, errorThrown) {
			alert('Something Wrong');
		}
	});
});


});


function Pembulatan(nilai){
	return Math.round(nilai * 1000) / 1000;
}

function GetTotalHarga(NoUrut=''){
	var TotalHarga = 0;
	var TotalHarga_Satuan = 0;

	var KdRAP = $('#KdRAP_'+NoUrut+' option:selected').val();
	if (KdRAP=='') {
		TotalHarga = 0;
	}else{
		var VolumeInputan = ($('#VolumeInputan_'+NoUrut).val()).split(',').join('').split('$').join('');
		var HrgRAB = ($('#HrgRAB_'+NoUrut).val()).split(',').join('').split('$').join('');
		var HrgSatuan = ($('#HrgSatuan_'+NoUrut).val()).split(',').join('').split('$').join('');

		HrgRAB = parseFloat(HrgRAB);
		HrgSatuan = parseFloat(HrgSatuan);
		VolumeInputan = parseFloat(VolumeInputan);

		TotalHarga 			= HrgRAB * VolumeInputan;
		TotalHarga_Satuan 	= HrgSatuan * VolumeInputan;
	}
	
	$('#TotalHarga_'+NoUrut).val(FormatVol_Buatan(TotalHarga));
	$('#TotalHarga_Satuan_'+NoUrut).val(FormatVol_Buatan(TotalHarga_Satuan));
	
}

function GetBobotPersen(NoUrut){
	var param_BobotPersen = 0;
	var KdRAP = $('#KdRAP_'+NoUrut+' option:selected').val();

	var KdRAP = $('#KdRAP_'+NoUrut+' option:selected').val();
	if (KdRAP=='') {
		param_BobotPersen = 0;
	}else{
		var VolumeInputan = ($('#VolumeInputan_'+NoUrut).val()).split(',').join('').split('$').join('');
		var BobotRAB = ($('#BobotRAB_'+NoUrut).val()).split(',').join('').split('$').join('');
		var VolRAP = ($('#Vol_'+NoUrut).val()).split(',').join('').split('$').join('');
		VolumeInputan = parseFloat(VolumeInputan);
		BobotRAB = parseFloat(BobotRAB);
		VolRAP = parseFloat(VolRAP);
		if (VolumeInputan==0 || VolumeInputan=='') {
			param_BobotPersen = 0;
		}else{
			param_BobotPersen = (VolumeInputan / VolRAP) * BobotRAB;
		}

		// console.log('Volume Inputan : '+VolumeInputan+'\n Bobot RAB : '+BobotRAB+'\n Vol : '+VolRAP+'\n');
	}

	$('#Bobot_Persen_'+NoUrut).val(param_BobotPersen.toFixed(3));
	// $('#Bobot_Persen_'+NoUrut).val(Pembulatan(param_BobotPersen));

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



</script>