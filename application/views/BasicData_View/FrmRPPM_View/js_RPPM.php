
<script type="text/javascript">
	$(document).ready(function(){

		$('.select2').select2();

		if ($('#MyForm #JobNo option:selected').val() !='' && $('#Alokasi option:selected').val() !='') {
			GetListMinggu();
			table_RPPM();
		}

		$('#MyForm #JobNo').change(function(){
			$('#table_RPPM').html('');
			if ($('#MyForm #Alokasi option:selected').val() !='') {
				GetListMinggu();
			}
		})

		$('#MyForm #Alokasi').change(function(){
			$('#table_RPPM').html('');
			if ($('#MyForm #JobNo option:selected').val() !='') {
				GetListMinggu();
			}
		});

		$( "#btnTampilkan" ).on( "click", function() {
			if ($('#JobNo option:selected').val() == '') {
				alert('belum pilih job');
				// Swal.fire('Alert!','belum pilih job','info');
				return false;
			}

			if ($('#Alokasi option:selected').val() == '') {
				alert('belum pilih alokasi');
				// Swal.fire('Alert!','belum pilih alokasi','info');
				return false;
			}

			table_RPPM();
		} );

		$('#Minggu').on('change',function(){
			$('#table_RPPM').html('');
			if($('#Minggu').val() ==''){
				$('#PrdAwal').val('');
				$('#PrdAkhir').val('');
				return false;
			} 

			dapatkan_periode();
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

		

	});

	function GetListMinggu(){

		var JobNo = $('#MyForm #JobNo option:selected').val();
		var Alokasi = $('#MyForm #Alokasi option:selected').val();
		// alert(Alokasi)

		if (JobNo=='' || Alokasi=='') {
			return false;
		}

		jQuery.ajax({
			url: "<?=site_url('BasicData/FrmRPPM_C/GetListMinggu')?>",
			type: 'POST',
			dataType: 'HTML',
			data: {
				'JobNo' : JobNo,
				'Alokasi' : Alokasi,
			},
			beforeSend : function(){
				$('#MyForm #JobNo').attr('disabled',true);
				$('#MyForm #Alokasi').attr('disabled',true);
				$('#MyForm #Minggu').html('');
			},
			complete: function(xhr, textStatus) {
				$('#MyForm #JobNo').attr('disabled',false);
				$('#MyForm #Alokasi').attr('disabled',false);
			},
			success: function(data, textStatus, xhr) {
				$('#MyForm #Minggu').html(data);
				// alert(data);
				// alert(data)
				// $('#MyForm #Minggu').html(data);
				// $('#Header').append(data);
				// console.log(data);


			},
			error: function(xhr, textStatus, errorThrown) {
				alert('terjadi kesalahan');
				// Swal.fire('Alert!','terjadi kesalahan','error');
			}
		});

		// jQuery.ajax({
		// 	url: "<?=site_url('BasicData/FrmRPPM_C/GetListMinggu')?>",
		// 	type: 'POST',
		// 	dataType: 'HTML',
		// 	data: {JobNo : JobNo, Alokasi : Alokasi}
		// 	beforeSend : function(){
		// 		$('#JobNo').attr('disabled',true);
		// 		$('#Alokasi').attr('disabled',true);
		// 		$('#MyForm #Minggu').html('');
		// 	},
		// 	complete: function(xhr, textStatus) {
		// 		$('#JobNo').attr('disabled',false);
		// 		$('#Alokasi').attr('disabled',false);
		// 	},
		// 	success: function(data, textStatus, xhr) {
		// 		alert(data)
		// 	},
		// 	error: function(xhr, textStatus, errorThrown) {
		// 	// Swal.fire('Alert!','terjadi kesalahan','error');
		// 		alert('terjadi kesalahan');
		// 	}	
		// });
	}

	function dapatkan_periode()
	{
		var JobNo = $('#JobNo option:selected').val();
		var Minggu = $('#Minggu option:selected').val();

		jQuery.ajax({
			url: "<?=site_url('BasicData/FrmRPPM_C/dapatkan_periode')?>",
			type: 'POST',
			dataType: 'JSON',
			data: {
				JobNo : JobNo, 
				Minggu : Minggu,
			},
			beforeSend : function(){
				$('#Minggu').attr('disabled',true);
			},
			complete: function(xhr, textStatus) {
				$('#Minggu').attr('disabled',false);
			},
			success: function(data, textStatus, xhr) {
				if (data.status=='periode_tidak_ada') {
					alert('periode tidak ditemukan');
					return false;
				}
				$('#PrdAwal').val(data.PrdAwal);
				$('#PrdAkhir').val(data.PrdAkhir);

			},
			error: function(xhr, textStatus, errorThrown) {
				alert('terjadi kesalahan');
				// Swal.fire('Alert!','terjadi kesalahan','error');
			}
		});

	}

	function reload_Table(){
		table_RPPM();
	}

	function table_RPPM(flag_tab=''){
		// PengecekanMinggu();
		var JobNo = $('#JobNo option:selected').val();
		var JobNm = $('#JobNo option:selected').attr('data-JobNm');
		var Alokasi = $('#Alokasi option:selected').val();
		var Keterangan = $('#Alokasi option:selected').attr('data-Keterangan');
		var Minggu = $('#Minggu option:selected').val();
		var PrdAwal = $('#PrdAwal').val();
		var PrdAkhir = $('#PrdAkhir').val();

		// alert(flag_tab)


		jQuery.ajax({
			url: "<?=site_url('BasicData/FrmRPPM_C/table_RPPM')?>",
			type: 'POST',
			dataType: 'HTML',
			data: {
				'JobNo' : JobNo, 'JobNm' : JobNm,
				'Alokasi' : Alokasi, 'Keterangan' : Keterangan,
				'Minggu' : Minggu,
				'PrdAwal' : PrdAwal,
				'PrdAkhir' : PrdAkhir,
				'flag_tab' : flag_tab
			},
			beforeSend : function(){
				$('.loading_box').show();
				$('#table_RPPM').html('');
			},
			complete: function(xhr, textStatus) {
				$('.loading_box').hide();
			},
			success: function(data, textStatus, xhr) {
				$('#table_RPPM').html(data);
				// alert(data)
				// $('#Header').append(data);
				// console.log(data);


			},
			error: function(xhr, textStatus, errorThrown) {
				alert('terjadi kesalahan');
				// Swal.fire('Alert!','terjadi kesalahan','error');
			}
		});
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