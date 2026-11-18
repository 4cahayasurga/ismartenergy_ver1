<script type="text/javascript">
	$(document).ready(function(){

		// -- start modal edit addendum
		$('#FormEditAddendum input[name="Bruto"], #FormEditAddendum input[name="PaguBruto"], #FormEditAddendum input[name="PaguNetto"], #FormEditAddendum input[name="KontrakNetto"]').on('input', function(e) 
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

		$('#FormEditAddendum input[name="Bruto"], #FormEditAddendum input[name="PaguBruto"], #FormEditAddendum input[name="PaguNetto"], #FormEditAddendum input[name="KontrakNetto"]').focusin(function()
		{
			if (this.value=='0.00' || this.value=='0') {
				$(this).val('');
			}else{
				$(this).val(this.value.split(",").join(""));
			}
		});

		$('#FormEditAddendum input[name="Bruto"], #FormEditAddendum input[name="PaguBruto"], #FormEditAddendum input[name="PaguNetto"], #FormEditAddendum input[name="KontrakNetto"]').focusout(function()
		{
			if (this.value=='') {
				$(this).val('0.00');
			}else{
				var NilaiKontrak = this.value;
				NilaiKontrak = NilaiKontrak.split(",").join("");
				NilaiKontrak = parseFloat(NilaiKontrak);
				// NilaiKontrak = Number(NilaiKontrak);
				NilaiKontrak = Format_NilaiKontrak(NilaiKontrak);
				$(this).val(addCommas(NilaiKontrak));
			}

		});

		// -- end modal edit addendum

		$('#FormDataKontrak input[name="NilaiKontrak"]').on('input', function(e) 
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

		$('#FormDataKontrak input[name="NilaiKontrak"]').focusin(function()
		{
			if (this.value=='0.00' || this.value=='0') {
				$(this).val('');
			}else{
				$(this).val(this.value.split(",").join(""));
			}
		});

		$('#FormDataKontrak input[name="NilaiKontrak"]').focusout(function()
		{
			if (this.value=='') {
				$(this).val('0.00');
			}else{
				var NilaiKontrak = this.value;
				NilaiKontrak = NilaiKontrak.split(",").join("");
				NilaiKontrak = parseFloat(NilaiKontrak);
				// NilaiKontrak = Number(NilaiKontrak);
				NilaiKontrak = Format_NilaiKontrak(NilaiKontrak);
				$(this).val(addCommas(NilaiKontrak));
			}

			Hitung_All()
			// Hitungan_All_Terbaru();
		});

		$(document).on('change','#FormDataKontrak input[name="PrdAwal"]',function(){
			Hitung_All()
			// Hitungan_All_Terbaru();
		});

		$('#FormDataKontrak input[name="Sumberdana"]').keyup(function(){
			Hitung_All();
		})


		$('#FormModal_DataKontrak input[name="Bruto"]').on('input', function(e) 
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

		$('#FormModal_DataKontrak input[name="Bruto"]').focusin(function()
		{
			if (this.value=='0.00' || this.value=='0') {
				$(this).val('');
			}else{
				$(this).val(this.value.split(",").join(""));
			}
		});

		$('#FormModal_DataKontrak input[name="Bruto"]').focusout(function()
		{
			if (this.value=='') {
				$(this).val('0.00');
			}else{
				var NilaiKontrak = this.value;
				NilaiKontrak = NilaiKontrak.split(",").join("");
				NilaiKontrak = parseFloat(NilaiKontrak);
				// NilaiKontrak = Number(NilaiKontrak);
				NilaiKontrak = Format_NilaiKontrak(NilaiKontrak);
				$(this).val(addCommas(NilaiKontrak));
			}

			Hitung_All_Modal_DataKontrak()
		});


		

		$('#FormModal_DataKontrak input[name="SumberDana"]').keyup(function(){
			Hitung_All_Modal_DataKontrak();
		})

	});



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

	function cek_nilai_PPN(){
		var PrdAwal 	= $('#FormDataKontrak input[name="PrdAwal"]').val();
		var JobNo 		= $('#FormDataKontrak input[name="JobNo"]').val();

		var Txt_PPN = 0;

		if (JobNo.substring(0, 2)<'23' && PrdAwal.split("-")[0]<'2023') {
			Txt_PPN = 0.1;
		}else{
			Txt_PPN = 0.11;
		}

		return parseFloat(Txt_PPN);
	}

	function Hitungan_All_Terbaru(){
		var JobNo 	= $('#FormDataKontrak input[name="JobNo"]').val();
		var PrdAwal 		= $('#FormDataKontrak input[name="PrdAwal"]').val();

		var NilaiKontrak 	= $('#FormDataKontrak input[name="NilaiKontrak"]').val();
		NilaiKontrak = NilaiKontrak.split(",").join("");
		NilaiKontrak = parseFloat(NilaiKontrak);


		var PPN_Dari_Pemerintah = 0;
		var PPh_Dari_Pemerintah = 0;
		var HitungPenawaranNetto = 0;
		var PPN_Rupiah = 0;



		// if (PrdAwal.split('-')[0]>='2023') {
		// 	PPN_Dari_Pemerintah = parseFloat(1.1);
		// 	PPh_Dari_Pemerintah = parseFloat(0.0265);
		// }else{
		// 	PPN_Dari_Pemerintah = parseFloat(1);
		// 	PPh_Dari_Pemerintah = parseFloat(0.97);
		// }

		// if (NilaiKontrak > 0) {
		// 	PPN_Rupiah = (NilaiKontrak/PPN_Dari_Pemerintah) * PPh_Dari_Pemerintah;
		// }else{
		// 	PPN_Rupiah = 0;
		// }

		// alert(HitungPenawaranNetto);


		// var PPN_Dari_Pemerintah
		// if (NilaiKontrak ) {}
	}

	function Hitung_All(){
		var JobNo 			= $('#FormDataKontrak input[name="JobNo"]').val();
		var PrdAwal 		= $('#FormDataKontrak input[name="PrdAwal"]').val();
		var SumberDana 		= $('#FormDataKontrak input[name="Sumberdana"]').val();
		var NilaiKontrak 	= $('#FormDataKontrak input[name="NilaiKontrak"]').val();
		var HitungPenawaranNetto = 0;
		var HitungNettoKontrak = 0;

		// if (PrdAwal.split('-')[0]<'2023') {
		// 	alert('apa');
		// }else{
		// 	alert('ga');
		// }

		// return false;

		// var get_Nilai_PPN = cek_nilai_PPN();
		// alert(NilaiKontrak)
		NilaiKontrak = NilaiKontrak.split(",").join("");
		NilaiKontrak = parseFloat(NilaiKontrak);

		if (NilaiKontrak>0) {
			if (SumberDana!='LOAN') {
				HitungPenawaranNetto 	= NilaiKontrak/1.1;

				if (parseInt(PrdAwal.split('-')[0]) < 2023) {
					HitungNettoKontrak = (NilaiKontrak/1.1) * 0.97;
				}else{
					HitungNettoKontrak = (NilaiKontrak/1.1) * 0.0265;
				}
				
			}else{
				HitungPenawaranNetto = NilaiKontrak;
				HitungNettoKontrak = NilaiKontrak;
			}
			
		}else{
			HitungPenawaranNetto = 0;
			HitungNettoKontrak = 0;
		}

		// console.log(Format_NilaiKontrak(HitungPenawaranNetto));
		$('#FormDataKontrak input[name="PenawaranNetto"]').val(Format_NilaiKontrak(HitungPenawaranNetto));
		$('#FormDataKontrak input[name="KontrakNetto"]').val(Format_NilaiKontrak(HitungNettoKontrak));


		// var PPN_Rupiah = 0;
		// var PPh_Rupiah = 0;

		// if (NilaiKontrak>0) {

		// 	if (SumberDana !='LOAN') {
		// 		PPN_Rupiah = (NilaiKontrak * 11)/100;
		// 	}else{
		// 		PPN_Rupiah = 0;
		// 	}


		// }else{
		// 	PPN_Rupiah = 0;
		// }

		// if (NilaiKontrak>0) {
		// 	if (SumberDana !='LOAN') {
		// 		PPh_Rupiah = (NilaiKontrak * 2.65)/100;
		// 	}else{
		// 		PPh_Rupiah = 0;
		// 	}

		// }else{
		// 	PPh_Rupiah = 0;
		// }

		// HitungPenawaranNetto = NilaiKontrak - PPN_Rupiah;
		// HitungNettoKontrak = (NilaiKontrak - PPN_Rupiah) + PPh_Rupiah

		// // $('#FormDataKontrak #PPN_Rupiah').val(Format_NilaiKontrak(PPN_Rupiah));
		// // $('#FormDataKontrak #PPh_Rupiah').val(Format_NilaiKontrak(PPh_Rupiah));
		// $('#FormDataKontrak input[name="PenawaranNetto"]').val(Format_NilaiKontrak(HitungPenawaranNetto));
		// $('#FormDataKontrak input[name="KontrakNetto"]').val(Format_NilaiKontrak(HitungNettoKontrak));


		// alert(JobNo.substring(0, 2));
	}

	function Hitung_All_Modal_DataKontrak(){
		var JobNo 			= $('#FormModal_DataKontrak input[name="JobNo"]').val();
		var PrdAwal 		= $('#FormModal_DataKontrak input[name="PrdAwal"]').val();
		var SumberDana 		= $('#FormModal_DataKontrak input[name="SumberDana"]').val();
		var NilaiKontrak 	= $('#FormModal_DataKontrak input[name="Bruto"]').val();

		var HitungPenawaranNetto = 0;
		var HitungNettoKontrak = 0;

		NilaiKontrak = NilaiKontrak.split(",").join("");
		NilaiKontrak = parseFloat(NilaiKontrak);

		if (NilaiKontrak>0) {
			if (SumberDana!='LOAN') {
				HitungPenawaranNetto 	= NilaiKontrak/1.1;

				if (parseInt(PrdAwal.split('-')[0]) < 2023) {
					HitungNettoKontrak = (NilaiKontrak/1.1) * 0.97;
				}else{
					HitungNettoKontrak = (NilaiKontrak/1.1) * 0.0265;
				}
				
			}else{
				HitungPenawaranNetto = NilaiKontrak;
				HitungNettoKontrak = NilaiKontrak;
			}
			
		}else{
			HitungPenawaranNetto = 0;
			HitungNettoKontrak = 0;
		}

		// console.log(Format_NilaiKontrak(HitungPenawaranNetto));
		$('#FormModal_DataKontrak input[name="PenawaranNetto"]').val(Format_NilaiKontrak(HitungPenawaranNetto));
		$('#FormModal_DataKontrak input[name="KontrakNetto"]').val(Format_NilaiKontrak(HitungNettoKontrak));


		// NilaiKontrak = NilaiKontrak.split(",").join("");
		// NilaiKontrak = parseFloat(NilaiKontrak);


		// var PPN_Rupiah = 0;
		// var PPh_Rupiah = 0;

		// // alert(NilaiKontrak)

		// if (NilaiKontrak>0) {

		// 	if (SumberDana !='LOAN') {
		// 		PPN_Rupiah = (NilaiKontrak * 11)/100;
		// 	}else{
		// 		PPN_Rupiah = 0;
		// 	}


		// }else{
		// 	PPN_Rupiah = 0;
		// }

		// // alert(PPN_Rupiah)

		// if (NilaiKontrak>0) {
		// 	if (SumberDana !='LOAN') {
		// 		PPh_Rupiah = (NilaiKontrak * 2.65)/100;
		// 	}else{
		// 		PPh_Rupiah = 0;
		// 	}

		// }else{
		// 	PPh_Rupiah = 0;
		// }

		// HitungPenawaranNetto = NilaiKontrak - PPN_Rupiah;
		// HitungNettoKontrak = (NilaiKontrak - PPN_Rupiah) + PPh_Rupiah

		// // $('#FormModal_DataKontrak #PPN_Rupiah').val(Format_NilaiKontrak(PPN_Rupiah));
		// // $('#FormModal_DataKontrak #PPh_Rupiah').val(Format_NilaiKontrak(PPh_Rupiah));
		// $('#FormModal_DataKontrak input[name="PenawaranNetto"]').val(Format_NilaiKontrak(HitungPenawaranNetto));
		// $('#FormModal_DataKontrak input[name="KontrakNetto"]').val(Format_NilaiKontrak(HitungNettoKontrak));


		// alert(JobNo.substring(0, 2));
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

</script>