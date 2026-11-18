<script type="text/javascript">
	$(document).ready(function(){

		$('.select2').select2();
		// loading_halaman_show(0);

		if ($('#formPDKeuangan #JobNo option:selected').val()!='' && $('#formPDKeuangan #Alokasi option:selected').val()!='' ) {
			tampil_table();
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

			tampil_table()
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

			tampil_table()
		});

		$('#formPDKeuangan').submit(function(event){
			event.preventDefault();
			tampil_table();
		})


	});

	function btnApprove(NoUrut){
		var NoPD = $('#btnApproveID'+NoUrut).attr('data-NoPD');
		var Deskripsi = $('#btnApproveID'+NoUrut).attr('data-Deskripsi');
		var TxtApprove = $('#btnApproveID'+NoUrut).attr('data-TxtApprove');

		// alert('sss');
		// return false;

		jQuery.ajax({
			url: "<?=site_url('Entry/PDPJ/FrmApprovalPD_KK/CekStatusApprovePD')?>",
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
				// alert(data);
				// return false;
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
			url: "<?=site_url('Entry/PDPJ/FrmApprovalPD_KK/lakukanApprove')?>",
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
					tampil_table();

					toastr.success(TxtApprove+' berhasil');
				}else{
					tampil_table();
					toastr.error(TxtApprove+' gagal');
				}
				// alert(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				alert('Something wrong');
			}
		});
	}

	function tampil_table(){
		var JobNo = $('#formPDKeuangan #JobNo option:selected').val();
		var Alokasi = $('#formPDKeuangan #Alokasi option:selected').val();

		if (JobNo=='') {
			alert('Job belum di-pilih');
			return false;
		}

		if (Alokasi=='') {
			alert('Alokasi belum di-pilih');
			return false;
		}

		$.ajax({
			url: "<?=site_url('Entry/PDPJ/FrmApprovalPD_KK/tampil_table')?>",
			type: 'POST',
			dataType: 'html',
			data: {JobNo: JobNo, Alokasi : Alokasi},
			beforeSend : function(){
				loading_halaman_show(1);
				$('#tampil_table').html('');
			}
		})
		.done(function(data) {
			// console.log("success");
			$('#tampil_table').html(data);
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