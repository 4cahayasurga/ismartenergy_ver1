<style type="text/css">
	#tablePendidikan{
		font-size: 12px;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		white-space: nowrap;
	}

	#tablePendidikan thead th{
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
						<button type="button" class="btn btn-sm btn-success btnAdd_DataPendidikan" data-NIK="<?=$NIK?>">Tambah Data Pendidikan <i class="fa fa-plus"></i></button>
					</td>
				</tr>
			</table>
			
			<table class="table table-bordered table-striped table-hover table-condensed"  width="100%" id="tablePendidikan" >
				<thead>
					<tr>
						<!-- <th>No.</th> -->
						<th>#</th>
						<th>Tingkat Pendidikan</th>
						<th>Periode Dimulai</th>
						<th>Periode Berakhir</th>
						<th>Nama Institusi</th>
						<th>Alamat Institusi</th>
						<th>Jurusan</th>
						<th>Kelulusan</th>
						<th>Nilai (IPK)</th>
						<th>No. Ijazah</th>
						
						
					</tr>
				</thead>
				<tbody>

					<?php
					$mynomor = 1;
					
					foreach($dataPendidikan as $my_dataPendidikan) : ?>
						<tr>
							<td class="text-center">
								<a href="javascript:void(0)" class="btn btn-xs btn-danger hapus_pendidikan" data-NIK="<?=$my_dataPendidikan['NIK']?>" data-NoUrutPendidikan="<?=$my_dataPendidikan['NoUrutPendidikan']?>" >Hapus <i class="fa fa-trash"></i></a>

								<a href="javascript:void(0)" class="btn btn-xs btn-warning edit_pendidikan" data-NIK="<?=$my_dataPendidikan['NIK']?>" data-NoUrutPendidikan="<?=$my_dataPendidikan['NoUrutPendidikan']?>" >Edit <i class="fa fa-pencil"></i></a>
							</td>
							<!-- <td style="text-align:center;"><?=$my_dataPendidikan['NoUrutPendidikan'];?></td> -->
							<td><?=$my_dataPendidikan['TgkPendidikan']?></td>
							<td><?=TanggalSaya($my_dataPendidikan['PrdAwal'])?></td>
							<td><?=TanggalSaya($my_dataPendidikan['PrdAkhir'])?></td>
							<td><?=$my_dataPendidikan['Institusi']?></td>
							<td><?=$my_dataPendidikan['Alamat']?></td>
							<td><?=$my_dataPendidikan['Jurusan']?></td>
							<td><?=$my_dataPendidikan['LlsTdkLls']?></td>
							<td><?=$my_dataPendidikan['IPK']?></td>
							<td><?=$my_dataPendidikan['NoIjazah']?></td>
							
						</tr>
					<?php endforeach; ?>

				</tbody>
			</table>

		</div>

	</div>
</div>


<script type="text/javascript">

	$(function(){

		$('#tablePendidikan').DataTable({
			"ordering" : false
		});

		$('.btnAdd_DataPendidikan').click(function(){

			var NIK = $(this).attr('data-NIK');

			jQuery.ajax({
				url: "<?=site_url('Kepegawaian/tampil_FormTambahPendidikan')?>",
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

		$('.hapus_pendidikan').click(function(){
			
			var NIK = $(this).attr('data-NIK');
			var NoUrutPendidikan = $(this).attr('data-NoUrutPendidikan');


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
						url: "<?=site_url('Kepegawaian/aksi_HapusPendidikan')?>",
						type: 'POST',
						dataType: 'html',
						data: {
							'NIK' : NIK,
							'NoUrutPendidikan' : NoUrutPendidikan
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


		$('.edit_pendidikan').click(function(){

			var NIK = $(this).attr('data-NIK');
			var NoUrutPendidikan = $(this).attr('data-NoUrutPendidikan');

			jQuery.ajax({
				url: "<?=site_url('Kepegawaian/tampilFormEdit_Pendidikan')?>",
				type: 'POST',
				dataType: 'html',
				data: {
					'NIK' : NIK,
					'NoUrutPendidikan' : NoUrutPendidikan
				},
				beforeSend : function(){
					$('.loadingku').show();
					$('#modal_keluarga_body').html('');
				},
				complete: function(xhr, textStatus) {
					$('.loadingku').hide();
				},
				success: function(data, textStatus, xhr) {

					// alert(data);

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