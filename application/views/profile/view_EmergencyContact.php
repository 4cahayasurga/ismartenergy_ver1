<style type="text/css">
	#tbl_ec{
		font-size: 12px;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	#tbl_ec thead th{
		text-align: center;
	}
</style>

<div class="row">
	<div class="col-sm-12">
		
		<div class="table-responsive">

			<table width="100%" style="font-size: 12px;">
				<tr>
					<td style="padding-bottom:20px;text-align: right;">
						<button type="button" class="btn btn-sm btn-success btn_tambah_EmergencyContact" data-NIK="<?=$NIK?>">Tambah Data Emergency Contact <i class="fa fa-plus"></i></button>
					</td>
				</tr>
			</table>
			
			<table class="table table-bordered table-striped table-hover table-condensed"  width="100%" id="tbl_ec" >
				<thead>
					<tr>
						<th>No.</th>
						<th>Nama</th>
						<th>Hubungan Keluarga</th>
						<th>Alamat</th>
						<th>No. Telp / HP</th>
						<th>#</th>
					</tr>
				</thead>
				<tbody>

					<?php
					$mynomor = 1;
					foreach($dataEmergencyContact as $myemergencycontact) : ?>
						<tr>
							<td style="text-align:center;"><?=$mynomor++;?></td>
							<td><?=$myemergencycontact['EmergencyName']?></td>
							<td><?=$myemergencycontact['EmergencyRelationShip']?></td>
							<td><?=$myemergencycontact['EmergencyAddress']?></td>
							<td><?=$myemergencycontact['EmergencyPhone']?></td>
							<td class="text-center">
								<a href="javascript:void(0)" class="btn btn-xs btn-danger hapus_EC" data-NIK="<?=$myemergencycontact['NIK']?>" data-LedgerNo="<?=$myemergencycontact['LedgerNo']?>" >Hapus <i class="fa fa-trash"></i></a>

								<a href="javascript:void(0)" class="btn btn-xs btn-warning edit_EC" data-NIK="<?=$myemergencycontact['NIK']?>" data-LedgerNo="<?=$myemergencycontact['LedgerNo']?>" >Edit <i class="fa fa-pencil"></i></a>
							</td>
						</tr>
					<?php endforeach; ?>

				</tbody>
			</table>

		</div>

	</div>
</div>


<script type="text/javascript">
	$(function(){

		$('#tbl_ec').DataTable({
			'ordering' : false
		});

		$('.btn_tambah_EmergencyContact').click(function(){

			

			var NIK = $(this).attr('data-NIK');

			jQuery.ajax({
				url: "<?=site_url('Kepegawaian/tampil_FormEmergency')?>",
				type: 'POST',
				dataType: 'html',
				data: {'NIK': NIK},
				beforeSend : function(){
					$('#modal_keluarga_body').html('');
					$('.loadingku').show();
				},
				complete: function(xhr, textStatus) {
					$('.loadingku').hide();
				},
				success: function(data, textStatus, xhr) {
					// alert('sucsss');
					$('#modal_keluarga').modal('show');
					$('#modal_keluarga_body').html(data);
				},
				error: function(xhr, textStatus, errorThrown) {
					$.toast({
						text: 'Terjadi Kesalahan',
						position: 'bottom-right',
						stack: false,
						hideAfter: 1000,
						icon: 'error',

					});
					return false;
				}
			});


		});

		$('.hapus_EC').click(function(){
			
			var NIK = $(this).attr('data-NIK');
			var LedgerNo = $(this).attr('data-LedgerNo');


			Swal.fire({
				title: 'Anda Yakin?',
				text: "data tidak dapat dikembalikan!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, hapus!',
				cancelButtonText: 'Batal'
			}).then((result) => {

				if (result.value) {

					jQuery.ajax({
						url: "<?=site_url('Kepegawaian/AksiHapus_EC')?>",
						type: 'POST',
						dataType: 'html',
						data: {
							'NIK' : NIK,
							'LedgerNo' : LedgerNo
						},
						beforeSend : function(){
							$('.loadingku').show();
						},
						complete: function(xhr, textStatus) {
							$('.loadingku').hide();
							pilihan_menu();
						},
						success: function(data, textStatus, xhr) {


							if (data == 'berhasil') {
								$.toast({
									text: 'Berhasil menghapus data',
									position: 'top-right',
									stack: false,
									hideAfter: 2000,
									icon: 'success',

								});
								return false;
							}else{
								$.toast({
									text: 'gagal menghapus data',
									position: 'top-right',
									stack: false,
									hideAfter: 2000,
									icon: 'error',

								});
								return false;
							}

						},
						error: function(xhr, textStatus, errorThrown) {
							$.toast({
								text: 'Terjadi Kesalahan',
								position: 'bottom-right',
								stack: false,
								hideAfter: 1000,
								icon: 'error',

							});
							return false;
						}
					});
					
				}
			});


		});


		$('.edit_EC').click(function(){

			var NIK = $(this).attr('data-NIK');
			var LedgerNo = $(this).attr('data-LedgerNo');

			jQuery.ajax({
				url: "<?=site_url('Kepegawaian/tampilFormEdit_EC')?>",
				type: 'POST',
				dataType: 'html',
				data: {
					'NIK' : NIK,
					'LedgerNo' : LedgerNo
				},
				beforeSend : function(){
					$('.loadingku').show();
					$('#modal_keluarga_body').html('');
				},
				complete: function(xhr, textStatus) {
					$('.loadingku').hide();
				},
				success: function(data, textStatus, xhr) {

					$('#modal_keluarga').modal('show');
					$('#modal_keluarga_body').html(data);

				},
				error: function(xhr, textStatus, errorThrown) {
					$.toast({
						text: 'Terjadi Kesalahan',
						position: 'bottom-right',
						stack: false,
						hideAfter: 1000,
						icon: 'error',

					});
					return false;
				}
			});


			
		});


	});
</script>