<script type="text/javascript">
	$(document).ready(function(){

		$('.select2').select2();
		// loading_halaman_show(0);

		if ($('#formPDKeuangan #JobNo option:selected').val()!='' && $('#formPDKeuangan #Alokasi option:selected').val()!='' ) {
			tampil_table_PD_Keuangan();
		}

		$('#formPDKeuangan #JobNo').on('change',function(){
			var JobNo = $('#formPDKeuangan #JobNo option:selected').val();
			var Alokasi = $('#formPDKeuangan #Alokasi option:selected').val();

			if (JobNo=='') {
				return false;
			}

			if (Alokasi=='') {
				return false;
			}

			tampil_table_PD_Keuangan()
		});

		$('#formPDKeuangan #Alokasi').on('change',function(){
			var JobNo = $('#formPDKeuangan #JobNo option:selected').val();
			var Alokasi = $('#formPDKeuangan #Alokasi option:selected').val();

			if (JobNo=='') {
				return false;
			}

			if (Alokasi=='') {
				return false;
			}

			tampil_table_PD_Keuangan()
		});

		$('#formPDKeuangan #CB_PD_MIX').on('change',function(){
			var JobNo = $('#formPDKeuangan #JobNo option:selected').val();
			var Alokasi = $('#formPDKeuangan #Alokasi option:selected').val();

			if (JobNo=='') {
				return false;
			}

			if (Alokasi=='') {
				return false;
			}

			tampil_table_PD_Keuangan()
		});

		$('#formPDKeuangan').submit(function(event){
			event.preventDefault();
			tampil_table_PD_Keuangan();
		})


	});

	function btnApprove(NoUrut){
		var NoPD = $('#btnApproveID'+NoUrut).attr('data-NoPD');
		var Deskripsi = $('#btnApproveID'+NoUrut).attr('data-Deskripsi');
		var TxtApprove = $('#btnApproveID'+NoUrut).attr('data-TxtApprove');

		jQuery.ajax({
			url: "<?=site_url('Entry/PDPJ/EntryPDKeu_C/CekStatusApprovePD')?>",
			type: 'POST',
			dataType: 'html',
			data: {NoPD: NoPD},
			beforeSend : function(){
				$('#btnApproveID'+NoUrut).attr('disabled',true);
			},
			complete: function(xhr, textStatus) {
				$('#btnApproveID'+NoUrut).attr('disabled',false);
			},
			success: function(data, textStatus, xhr) {
				if (data=='lanjut') {
					// if (confirm('Anda yakin ingin '+TxtApprove+' data berikut ini?\n'+NoPD+' - '+Deskripsi)) {
					lakukanApprove(NoUrut)
					// }
				}else{
					alert(data);
					return false;
				}
			},
			error: function(xhr, textStatus, errorThrown) {
				alert('Something wrong');
			}
		});
		
	}

	function lakukanApprove(NoUrut){
		var NoPD = $('#btnApproveID'+NoUrut).attr('data-NoPD');
		var Deskripsi = $('#btnApproveID'+NoUrut).attr('data-Deskripsi');
		var TxtApprove = $('#btnApproveID'+NoUrut).attr('data-TxtApprove');

		if (!confirm('Anda yakin ingin '+TxtApprove+' data berikut ini?\n'+NoPD+' - '+Deskripsi)) {
			return false;
		}
		

		jQuery.ajax({
			url: "<?=site_url('Entry/PDPJ/EntryPDKeu_C/lakukanApprove')?>",
			type: 'POST',
			dataType: 'html',
			data: {NoPD: NoPD, TxtApprove : TxtApprove},
			beforeSend : function(){
				$('#btnApproveID'+NoUrut).attr('disabled',true);
			},
			complete: function(xhr, textStatus) {
				$('#btnApproveID'+NoUrut).attr('disabled',false);
			},
			success: function(data, textStatus, xhr) {
				if (data=='berhasil') {
					tampil_table_PD_Keuangan();

					toastr.success(TxtApprove+' berhasil');
				}else{
					tampil_table_PD_Keuangan();
					toastr.success(TxtApprove+' gagal');
				}
				// alert(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				alert('Something wrong');
			}
		});
	}

	function tampil_table_PD_Keuangan(){
		var JobNo = $('#formPDKeuangan #JobNo option:selected').val();
		var Alokasi = $('#formPDKeuangan #Alokasi option:selected').val();
		var CB_PD_MIX = ($('#formPDKeuangan #CB_PD_MIX').is(':checked')) ? 1 : 0;

		if (JobNo=='') {
			alert('Job belum di-pilih');
			return false;
		}

		if (Alokasi=='') {
			alert('Alokasi belum di-pilih');
			return false;
		}

		$.ajax({
			url: "<?=site_url('Entry/PDPJ/EntryPDKeu_C/tampil_table_PD_Keuangan')?>",
			type: 'POST',
			dataType: 'html',
			data: {JobNo: JobNo, Alokasi : Alokasi, CB_PD_MIX : CB_PD_MIX},
			beforeSend : function(){
				loading_halaman_show(1);
				$('#tampil_table_PD_Keuangan').html('');
			}
		})
		.done(function(data) {
			// console.log("success");
			$('#tampil_table_PD_Keuangan').html(data);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			loading_halaman_show(0);
		});
		
	}

	function loading_halaman_show(flag=1){
		if (flag==1) {
			$('#loading_halaman').show();
		}else{
			$('#loading_halaman').hide();
		}
		
	}

	
</script>