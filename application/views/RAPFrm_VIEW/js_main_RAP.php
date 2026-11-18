<script type="text/javascript">

	// var Versi = $('#MyForm_RAP').attr('data-Versi');

	$(document).ready(function(){
		// alert(Versi);
		$('.select2').select2();

		if ($('#MyForm_RAP #JobNo option:selected').val() !='') {
			// var Versi = $('#MyForm_RAP #Versi option:selected').val();

			getVersi();
		}

		if ($('#MyForm_RAP #Alokasi option:selected').val() !='') {
			getVersi();
		}

		$('#MyForm_RAP #JobNo').change(function(){
			getVersi();
		});

		$('#MyForm_RAP #Alokasi').change(function(){
			getVersi();
		});

		$('#MyForm_RAP #Versi').change(function(){
			ListRAP();
		});

		$('#MyForm_RAP').submit(function(event){
			event.preventDefault();
			if ($('#MyForm_RAP #JobNo option:selected').val()=='') {
				alert('Job masih kosong');
				return false;
			}

			if ($('#MyForm_RAP #Alokasi option:selected').val()=='') {
				alert('Alokasi masih kosong');
				return false;
			}

			if ($('#MyForm_RAP #Versi option:selected').val()=='') {
				alert('Versi masih kosong');
				return false;
			}


			ListRAP();
		});


		$('#FormRAP_IMP').submit(function(event){
			event.preventDefault();

			var formData = new FormData($(this)[0]);
			formData.append('BahanExcel',$("#BahanExcel").val());

			jQuery.ajax({
				type: "POST",
				url: "<?=site_url('Entry/RAPFrm/AksiImportExcel')?>",
				data: formData,
				processData: false,
				contentType: false,
				dataType : 'html',
				beforeSend : function(){
					$('#FormRAP_IMP #btnImport').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('#FormRAP_IMP #btnImport').attr('disabled',false);
					$('#myModal').modal('hide');
					ListRAP();
				},
				success: function(data, textStatus, xhr) {
					if (data=='berhasil') {
						toastr.success('Import Data Success');
						return false;
					}else{
						alert(data);
					}
					// alert(data);
					// console.log(data);
				},
				error: function(xhr, textStatus, errorThrown) {
					alert('something wrong');
				}
			});
			

			// alert('sss');	
		});


		$(document).on('click','.btnAddRAP',function(){
			if ($('#MyForm_RAP #JobNo option:selected').val()=='') {
				alert('Belum pilih Job');
				return false;
			}

			if ($('#MyForm_RAP #Alokasi option:selected').val()=='') {
				alert('Belum pilih Alokasi');
				return false;
			}

			if ($(this).attr('data-TipeRAP')!='RAP') {
				alert('Hanya bisa tambah data untuk RAP Versi '+$('#MyForm_RAP #Versi option:selected').val());
				return false;
			}

			window.location.replace($(this).attr('data-UrlSaya'));

		});

		$(document).on('click','.btnTambahVersi',function(event){
			event.preventDefault();
			
			var JobNo 			= $(this).attr('data-JobNo');
			var Alokasi 		= $(this).attr('data-Alokasi');
			var Versi 			= $(this).attr('data-Versi');
			var TipeRAP 		= $(this).attr('data-TipeRAP');
			var VersiTerkini 	= $(this).attr('data-VersiTerkini');

			if (JobNo=='') {
				alert('Belum pilih Job.');
				return false;
			}

			if (Alokasi=='') {
				alert('Belum pilih Alokasi.');
				return false;
			}

			if (TipeRAP!='RAP') {
				alert('Hanya bisa tambah versi untuk RAP Versi '+VersiTerkini+'.');
				return false;
			}

			$("#Form_TambahVersi input:radio").attr("checked", false);
			$("#Form_TambahVersi #VersiTerkini").val('0.0');
			$("#Form_TambahVersi #VersiTerkini").prop('readonly',true);
			$("#Form_TambahVersi #RubahVersiMenjadi").val('');

			$("#Form_TambahVersi #VersiTerkini").val(VersiTerkini);
			$("#Form_TambahVersi #JobNo_Versi").val(JobNo);
			$("#Form_TambahVersi #Alokasi_Versi").val(Alokasi);

			$('#Modal_TambahVersi').modal('show');
		});

		$(document).on('change','#Form_TambahVersi input[type=radio][name=PerubahanVersi]',function(){

			var data_ini = $("#Form_TambahVersi #VersiTerkini").val();

			if (this.value=='Major') {
				var RubahVersiMenjadi = data_ini.split('.')[0];
				RubahVersiMenjadi = parseInt(RubahVersiMenjadi) + 1;
				RubahVersiMenjadi = RubahVersiMenjadi+'.0';

				$("#Form_TambahVersi #RubahVersiMenjadi").val(RubahVersiMenjadi);
			}else if (this.value=='Minor') {
				var RubahVersiMenjadi = data_ini.split('.')[0];
				var AmbilBelakang = parseInt(data_ini.split('.')[1]) + 1;
				RubahVersiMenjadi = data_ini.split('.')[0]+'.';

				$("#Form_TambahVersi #RubahVersiMenjadi").val(RubahVersiMenjadi+''+AmbilBelakang);
			}

		});

		$(document).on('submit','#Form_TambahVersi',function(event){
			event.preventDefault();

			if ($('#Form_TambahVersi #RubahVersiMenjadi').val()=='') {
				alert('Belum pilih perubahan major atau minor.');
				return false;
			}


			jQuery.ajax({
				url 		: "<?=site_url('Entry/RAPFrm/AksiSimpan_Versi')?>",
				type 		: 'POST',
				dataType 	: 'html',
				data 		: $('#Form_TambahVersi').serialize(),
				beforeSend 	: function(){
					$('#Form_TambahVersi .btnSimpanVersi').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('#Form_TambahVersi .btnSimpanVersi').attr('disabled',false);
				},
				success: function(data, textStatus, xhr) {
					// console.log(data)
					var UrlFileIni = "<?=site_url('Entry/RAPFrm/index/')?>"+$("#Form_TambahVersi #JobNo_Versi").val()+'/'+$("#Form_TambahVersi #Alokasi_Versi").val();
					window.location.replace(UrlFileIni);
				},
				error: function(xhr, textStatus, errorThrown) {
					alert('something wrong');
				}
			});
			
		});

		$(document).on('click','.btnDelete', function(){


			var LedgerNo 	= $(this).attr('data-LedgerNo');
			var Versi 		= $(this).attr('data-Versi');
			var TipeRAP 	= $(this).attr('data-TipeRAP');
			
			var JobNo 		= $(this).attr('data-JobNo');
			var Alokasi 	= $(this).attr('data-Alokasi');
			var KdRAP 		= $(this).attr('data-KdRAP');
			var Tipe 		= $(this).attr('data-Tipe');
			var Uraian 		= $(this).attr('data-Uraian');

			// alert(LedgerNo+' - '+Versi+' - '+TipeRAP);

			if (TipeRAP!='RAP') {
				alert('hanya bisa delete versi '+Versi);
				return false;
			}

			jQuery.ajax({
				url 		: "<?=site_url('Entry/RAPFrm/CekData_RAP')?>",
				type 		: 'POST',
				dataType 	: 'html',
				data 		: {
					LedgerNo : LedgerNo, 
					Versi : Versi, 
					TipeRAP : TipeRAP,
					JobNo : JobNo,
					Alokasi : Alokasi,
					KdRAP : KdRAP,
					Tipe : Tipe,
				},
				beforeSend 	: function(){
					$('#btnDelete_ID_'+LedgerNo).attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('#btnDelete_ID_'+LedgerNo).attr('disabled',false);
				},
				success: function(data, textStatus, xhr) {
					// console.log(data);

					if (data!='lanjut') {
						alert(data);
						return false;
					}else{

						if (confirm("Anda yakin ingin menghapus data berikut?\nKode RAP: "+KdRAP+' - '+Uraian ) == true) {
							
							jQuery.ajax({
								url 		: "<?=site_url('Entry/RAPFrm/AksiHapus_RAP')?>",
								type 		: 'POST',
								dataType 	: 'html',
								data 		: {
									LedgerNo : LedgerNo, 
									Versi : Versi, 
									TipeRAP : TipeRAP,
									JobNo : JobNo,
									Alokasi : Alokasi,
									KdRAP : KdRAP,
									Tipe : Tipe,
								},
								beforeSend 	: function(){
									$('#btnDelete_ID_'+LedgerNo).attr('disabled',true);
								},
								complete: function(xhr, textStatus) {
									$('#btnDelete_ID_'+LedgerNo).attr('disabled',false);
								},
								success: function(data, textStatus, xhr) {

									ListRAP();
									if (data=='berhasil') {
										toastr.success('Deleted success');
									}else{
										alert(data);
									}

								},
								error: function(xhr, textStatus, errorThrown) {
									alert('something wrong');
								}
							});

						}

					}
				},
				error: function(xhr, textStatus, errorThrown) {
					alert('something wrong');
				}
			});



		})

		$(document).on('click','.btnUpKlik',function(){

			if ($('#MyForm_RAP #Versi option:selected').attr('data-TipeRAP')!='RAP') {
				return false;
			}

			var NoUrut_YangDiKlik 	= $(this).attr('data-NoUrut');
			var KdRAP_YangDiKlik	= $('#btnUpKlik_ID_'+NoUrut_YangDiKlik).attr('data-KdRAP');

			var NoUrut_atas 		= parseInt(NoUrut_YangDiKlik) - 1;
			var KdRAP_atas			= $('#btnUpKlik_ID_'+NoUrut_atas).attr('data-KdRAP');

			if (NoUrut_atas < 1) {
				return false;
			}

			UpdatePreference(NoUrut_atas,KdRAP_YangDiKlik);
			UpdatePreference(NoUrut_YangDiKlik,KdRAP_atas);

			ListRAP();

		});

		$(document).on('click','.btnDownKlik',function(){

			if ($('#MyForm_RAP #Versi option:selected').attr('data-TipeRAP')!='RAP') {
				return false;
			}

			var JumlahRow = parseInt($('#mytable tbody tr').length);

			var NoUrut_YangDiKlik 	= $(this).attr('data-NoUrut');
			var KdRAP_YangDiKlik	= $('#btnDownKlik_ID_'+NoUrut_YangDiKlik).attr('data-KdRAP');

			
			if (JumlahRow - parseInt(NoUrut_YangDiKlik) < 1) {
				return false;
			}

			var NoUrut_Bawah 		= parseInt(NoUrut_YangDiKlik) + 1;
			var KdRAP_Bawah			= $('#btnDownKlik_ID_'+NoUrut_Bawah).attr('data-KdRAP');

			UpdatePreference(NoUrut_Bawah,KdRAP_YangDiKlik);
			UpdatePreference(NoUrut_YangDiKlik,KdRAP_Bawah);

			ListRAP();
			return false;

		});

	});


function modal_import_excel(){
	$('#myModal').modal('show');

}

function UpdatePreference(NoUrut,KdRAP){
	var JobNo = $('#MyForm_RAP #JobNo option:selected').val();
	var Alokasi = $('#MyForm_RAP #Alokasi option:selected').val();
	$.post( "<?=site_url('Entry/RAPFrm/UpdatePreference')?>", { NoUrut: NoUrut, KdRAP : KdRAP, JobNo : JobNo, Alokasi : Alokasi })
	.done(function( data ) {
		// alert(data)
	});
}

function UpdatePreference_old(KdRAP_sekarang,NoUrut_atasnya,KdRAP_atasnya,NoUrut_sekarang,JobNo,FlagBtn){
	$.ajax({
		url: "<?=site_url('Entry/RAPFrm/UpdatePreference')?>",
		type: 'POST',
		dataType: 'html',
		data: {
			KdRAP_sekarang : KdRAP_sekarang,
			NoUrut_atasnya : NoUrut_atasnya,
			KdRAP_atasnya : KdRAP_atasnya,
			NoUrut_sekarang : NoUrut_sekarang,
			JobNo : JobNo,
			FlagBtn : FlagBtn,
		},
		beforeSend : function(){
			if (FlagBtn=='btnUp') {
				$('#btnUP_ID_'+NoUrut_sekarang).attr('disabled',true);
			}else{
				$('#btnDown_ID_'+NoUrut_sekarang).attr('disabled',true);
			}
		},
		complete: function(xhr, textStatus) {
			if (FlagBtn=='btnUp') {
				$('#btnUP_ID_'+NoUrut_sekarang).attr('disabled',false);
			}else{
				$('#btnDown_ID_'+NoUrut_sekarang).attr('disabled',false);
			}
		},
		success: function(data, textStatus, xhr) {
			ListRAP();
			if (data=='berhasil') {
				toastr.success('success');
				return false;
			}else{
				alert(data);
			}
		},
		error: function(xhr, textStatus, errorThrown) {
			alert('Terjadi Kesalahan');
		}
	});
}

function btnTambahVersi(){
	var JobNo = $('#MyForm_RAP #JobNo option:selected').val();
	var Alokasi = $('#MyForm_RAP #Alokasi option:selected').val();
	var Versi = $('#MyForm_RAP #Versi option:selected');

	alert(JobNo+' - '+Alokasi+' - '+Versi);
	$('#Modal_TambahVersi').modal('show');
}

function getVersi(Versi=''){
	if ($('#MyForm_RAP #JobNo').val()=='') {
		return false;
	}

	if ($('#MyForm_RAP #Alokasi').val()=='') {
		return false;
	}

	$.ajax({
		url: "<?=site_url('Entry/RAPFrm/getVersi')?>",
		type: 'POST',
		dataType: 'json',
		data: {'JobNo' : $('#MyForm_RAP #JobNo').val(), 'Alokasi' : $('#MyForm_RAP #Alokasi').val()},
		beforeSend : function(){
			$('#MyForm_RAP #JobNo').attr('disabled',true);
			$('#MyForm_RAP #Alokasi').attr('disabled',true);
		},
		complete: function(xhr, textStatus) {
			$('#MyForm_RAP #JobNo').attr('disabled',false);
			$('#MyForm_RAP #Alokasi').attr('disabled',false);
		},
		success: function(data, textStatus, xhr) {
			$('#MyForm_RAP #Versi').find('option').remove();
			var html='';
			$.each(data, function(index) {
				$('#MyForm_RAP #Versi').append('<option value="'+data[index].Versi+'" data-TipeRAP="'+data[index].TipeRAP+'">'+data[index].Versi+'</option>');
			});

			ListRAP();
		},
		error: function(xhr, textStatus, errorThrown) {
			alert('Terjadi Kesalahan');
		}
	});

}

function ListRAP(){
	if ($('#MyForm_RAP #JobNo').val()=='') {
		return false;
	}

	if ($('#MyForm_RAP #Alokasi').val()=='') {
		return false;
	}

	if ($('#MyForm_RAP #Versi option:selected').val()=='') {
		return false;
	}

	var JobNo = $('#MyForm_RAP #JobNo option:selected').val();
	var Alokasi = $('#MyForm_RAP #Alokasi option:selected').val();
	var Versi = $('#MyForm_RAP #Versi option:selected').val();
	var TipeRAP = $('#MyForm_RAP #Versi option:selected').attr('data-TipeRAP');

	$.ajax({
		url: "<?=site_url('Entry/RAPFrm/get_ListRAP')?>",
		type: 'POST',
		dataType: 'HTML',
		data: {
			'JobNo' : JobNo, 
			'Alokasi' : Alokasi,
			'Versi' : Versi,
			'TipeRAP' : TipeRAP
		},
		beforeSend : function(){
			$('#MyForm_RAP #JobNo').attr('disabled',true);
			$('#MyForm_RAP #Alokasi').attr('disabled',true);
			$('#MyForm_RAP #Versi').attr('disabled',true);

			$('#btnFilter').attr('disabled',true).text('Loading...');
			$('.loading_halaman').show();
			$('.tampil_table_RAP_disini').html('');
			$('.tampil_table_RAP_disini').html('<i class="fa fa-refresh fa-spin"></i>');
		},
		complete: function(xhr, textStatus) {
			$('#MyForm_RAP #JobNo').attr('disabled',false);
			$('#MyForm_RAP #Alokasi').attr('disabled',false);
			$('#MyForm_RAP #Versi').attr('disabled',false);

			$('#btnFilter').attr('disabled',false).text('FILTER');
			$('.loading_halaman').hide();
		},
		success: function(data, textStatus, xhr) {
			$('.tampil_table_RAP_disini').html('');
			$(".tampil_table_RAP_disini").html(data);

		},
		error: function(xhr, textStatus, errorThrown) {
			alert('Terjadi Kesalahan');
		}
	});
}


function btnFormImport(){
	$('#myModal').modal('show');
}
</script>