<style type="text/css">
	#table_ketrampilan{
		font-size: 12px;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		white-space: nowrap;
	}

	#table_ketrampilan thead th{
		text-align: center;
	}
	.swal2-popup {
        font-size: 1.2rem !important;
        font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
    }
</style>

<div class="row">
	<div class="col-sm-12">
		
		<div class="table-responsive">

			<table width="100%" style="font-size: 12px;">
				<tr>
					<td style="padding-bottom:20px;text-align: right;">
						<button type="button" class="btn btn-sm btn-success btn_add_ketrampilan" data-NIK="<?=$NIK?>">Tambah Data Ketrampilan <i class="fa fa-plus"></i></button>
					</td>
				</tr>
			</table>
			
			<table class="table table-bordered table-striped table-hover table-condensed"  width="100%" id="table_ketrampilan" >
				<thead>
					<tr>
						<!-- <th>No.</th> -->
						<th>#</th>
						<th>Nama <br>Keahlian/Ketrampilan/Kursus/Pelatihan</th>
						<th>Periode Dimulai</th>
						<th>Periode Berakhir</th>
						<th>Nama Sertifikat</th>
						<th>Level/Grade</th>
						<th>No. Sertifikat</th>
						<th>Nama Institusi</th>
						
						
					</tr>
				</thead>
				<tbody>

					<?php
					$mynomor = 1;
					foreach($data_ketrampilan as $my_data_ketrampilan) : ?>
						<tr>
							<td class="text-center">
								<a href="javascript:void(0)" class="btn btn-xs btn-danger hapus_ketrampilan" data-NIK="<?=$my_data_ketrampilan['NIK']?>" data-NoUrutKetrampilan="<?=$my_data_ketrampilan['NoUrutKetrampilan']?>" >Hapus <i class="fa fa-trash"></i></a>

								<a href="javascript:void(0)" class="btn btn-xs btn-warning edit_ketrampilan" data-NIK="<?=$my_data_ketrampilan['NIK']?>" data-NoUrutKetrampilan="<?=$my_data_ketrampilan['NoUrutKetrampilan']?>" >Edit <i class="fa fa-pencil"></i></a>
							</td>
							<!-- <td style="text-align:center;"><?=$my_data_ketrampilan['NoUrutKetrampilan'];?></td> -->
							<td><?=$my_data_ketrampilan['Nama']?></td>
							<td><?=TanggalSaya($my_data_ketrampilan['PrdAwal'])?></td>
							<td><?=TanggalSaya($my_data_ketrampilan['PrdAkhir'])?></td>
							<td><?=$my_data_ketrampilan['Sertifikat']?></td>
							<td><?=$my_data_ketrampilan['Grade']?></td>
							<td><?=$my_data_ketrampilan['NoSertifikat']?></td>
							<td><?=$my_data_ketrampilan['Institusi']?></td>
							
						</tr>
					<?php endforeach; ?>

				</tbody>
			</table>

		</div>

	</div>
</div>

<script type="text/javascript">
	$(function(){

		$('#table_ketrampilan').DataTable({
			'ordering' : false
		});

		$('.btn_add_ketrampilan').click(function(){

			var NIK = $(this).attr('data-NIK');

			jQuery.ajax({
				url: "<?=site_url('Kepegawaian/tampil_form_add_ketrampilan')?>",
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


		$('.hapus_ketrampilan').click(function(){
			
			var NIK = $(this).attr('data-NIK');
			var NoUrutKetrampilan = $(this).attr('data-NoUrutKetrampilan');


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
						url: "<?=site_url('Kepegawaian/aksi_hapus_ketrampilan')?>",
						type: 'POST',
						dataType: 'html',
						data: {
							'NIK' : NIK,
							'NoUrutKetrampilan' : NoUrutKetrampilan
						},
						beforeSend : function(){
							$('.loadingku').show();
						},
						complete: function(xhr, textStatus) {
							$('.loadingku').hide();
							pilihan_menu();
						},
						success: function(data, textStatus, xhr) {
							// alert(data);
							// return false;

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


		$('.edit_ketrampilan').click(function(){

			var NIK = $(this).attr('data-NIK');
			var NoUrutKetrampilan = $(this).attr('data-NoUrutKetrampilan');

			jQuery.ajax({
				url: "<?=site_url('Kepegawaian/tampil_form_edit_ketrampilan')?>",
				type: 'POST',
				dataType: 'html',
				data: {
					'NIK' : NIK,
					'NoUrutKetrampilan' : NoUrutKetrampilan
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

