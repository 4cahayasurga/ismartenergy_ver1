<script type="text/javascript">

	var UrlFileIni = "<?=site_url('Entry/RAPFrm/')?>";
	$(document).ready(function(){

		$('.select2').select2();

		BindAlokasi();

		$('#Form_CreateRAP #Tipe').focus(); 

		$(document).on('change','#Form_CreateRAP #Tipe',function(){
			TipeBerganti();
		});

		$("#Form_CreateRAP input[name='Vol']").on('input', function(e) 
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

		$("#Form_CreateRAP input[name='Vol']").focusin(function()
		{
			if (this.value=='0.000' || this.value=='0') {
				$(this).val('');
			}else{
				$(this).val(this.value.split(",").join(""));
			}
		});

		$("#Form_CreateRAP input[name='Vol']").focusout(function()
		{
			if (this.value=='') {
				$(this).val('0.000');
			}else{
				var Vol = this.value;
				Vol = Vol.split(",").join("");
				Vol = parseFloat(this.value);
				Vol = Number(Vol).toFixed(3);
				Vol = FormatVol_Buatan(Vol);
				$(this).val(Vol);
			}
		});

		$("#Form_CreateRAP input[name='HrgSatuan'], #Form_CreateRAP input[name='HrgRAB']").on('input', function(e) 
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

		$("#Form_CreateRAP input[name='HrgSatuan'], #Form_CreateRAP input[name='HrgRAB']").focusin(function()
		{
			if (this.value=='0.00' || this.value=='0') {
				$(this).val('');
			}else{
				$(this).val(this.value.split(",").join(""));
			}
		});

		$("#Form_CreateRAP input[name='HrgSatuan'], #Form_CreateRAP input[name='HrgRAB']").focusout(function()
		{
			if (this.value=='') {
				$(this).val('0');
			}else{
				var HrgSatuan = this.value;
				HrgSatuan = HrgSatuan.split(",").join("");
				HrgSatuan = parseFloat(this.value);
				HrgSatuan = Number(HrgSatuan).toFixed(2);
				HrgSatuan = FormatVol_Buatan_HrgSatuan(HrgSatuan);
				$(this).val(HrgSatuan);
			}
		});

		$(document).on('keyup','#Form_CreateRAP #KdRAP',function(){
			var KdRAP 	= this.value;
			KdRAP 		= KdRAP.replace(/"|'|`|,/g,'');
			$(this).val(KdRAP);
		})

		$(document).on('submit','#Form_CreateRAP',function(event){
			event.preventDefault();

			if ($('#Form_CreateRAP #Tipe option:selected').val()=='') {
				alert('Belum pilih Tipe.');
				return false;
			}

			if ($('#Form_CreateRAP #Tipe option:selected').val()=='Detail') {

				if ($('#Form_CreateRAP #Header option:selected').val()=='0') {
					alert('Belum pilih Header.');
					return false;
				}
			}

			if($('#Form_CreateRAP #KdRAP').val()==''){
				alert('Kode RAP belum di-isi');
				return false;
			}else{
				if ($('#Form_CreateRAP #KdRAP').val().substr(-1)=='.') {
					alert('Kode RAP tidak boleh Titik (.) di akhir karakter');
					$('#Form_CreateRAP #KdRAP').focus();
					return false;
				}
			}

			if($('#Form_CreateRAP #Uraian').val()==''){
				alert('Uraian Pekerjaan belum di-isi');
				return false;
			}

			jQuery.ajax({
				url 		: UrlFileIni+'SaveRAP',
				type 		: 'POST',
				dataType 	: 'html',
				data 		: $('#Form_CreateRAP').serialize(),
				beforeSend 	: function(){
					$('#btnSimpan').attr('disabled',true);
					$('#btnSimpan').text('Loading...');
				},
				complete: function(xhr, textStatus) {
					$('#btnSimpan').attr('disabled',false);
					$('#btnSimpan').text('Simpan');
				},
				success: function(data, textStatus, xhr) {

					if (data=='berhasil') {
						UrlFileIni = UrlFileIni+'index/'+$('#Form_CreateRAP').attr('data-JobNo')+'/'+$('#Form_CreateRAP').attr('data-Alokasi');
						window.location.replace(UrlFileIni+'');
					}else{
						alert(data);
					}
					// alert(data);
					// console.log(data);
					// alert(data);
				},
				error: function(xhr, textStatus, errorThrown) {
					alert('Something Wrong');
				}
			});
			

		})

	});

function BindAlokasi(){
	var Alokasi = $('#Form_CreateRAP #Alokasi option:selected').val();
	if (Alokasi=='B') {
		$('.table_HrgRAB').css('display','');
	}else{
		$('.table_HrgRAB').css('display','none');
	}
}

function TipeBerganti(){

	var Tipe = $('#Form_CreateRAP #Tipe option:selected').val();
	if (Tipe=='Detail') {
		$('#Form_CreateRAP #Uom').attr('disabled',false);
		$('#Form_CreateRAP #Vol').attr('disabled',false);
		$('#Form_CreateRAP #HrgSatuan').attr('disabled',false);
		$('#Form_CreateRAP #HrgRAB').attr('disabled',false);
	}else{

		$('#Form_CreateRAP #Uom').attr('disabled',true);
		$('#Form_CreateRAP #Uom').val('');

		$('#Form_CreateRAP #Vol').attr('disabled',true);
		$('#Form_CreateRAP #Vol').val('0.000');

		$('#Form_CreateRAP #HrgSatuan').attr('disabled',true);
		$('#Form_CreateRAP #HrgSatuan').val('0');

		$('#Form_CreateRAP #HrgRAB').attr('disabled',true);
		$('#Form_CreateRAP #HrgRAB').val('0');

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
	return("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
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
