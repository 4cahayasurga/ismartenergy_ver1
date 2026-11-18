<style type="text/css">
	#tbl_riwayat_pekerjaan{
		font-size: 12px;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		/*white-space: nowrap;*/
	}

	#tbl_riwayat_pekerjaan thead th{
		text-align: center;
	}
</style>

<div class="row">
	<div class="col-sm-12">
		
		<div class="table-responsive">

			<table width="100%" style="font-size: 12px;">
				<tr>
					<td style="padding-bottom:20px;text-align: right;">
						<button type="button" class="btn btn-sm btn-success btn_add_riwayat_pekerjaan" data-NIK="<?=$NIK?>">Tambah Data Riwayat Pekerjaan <i class="fa fa-plus"></i></button>
					</td>
				</tr>
			</table>
			
			<table class="table table-bordered table-striped table-hover table-condensed"  width="100%" id="tbl_riwayat_pekerjaan" >
				<thead>
					<tr>
						<th>No.</th>
						<th>Periode Dimulai</th>
						<th>Periode Berakhir</th>
						<th>Nama Perusahaan</th>
						<th>Alamat Perusahaan</th>
						<th>Bidang Industri</th>
						<th>Jabatan Terakhir</th>
						<th>Lokasi Kerja</th>
						<th>Gaji Pokok</th>
						<th>Fasilitas Tunjangan</th>
						<th>Uraian Pekerjaan</th>
						<th>#</th>
					</tr>
				</thead>
				<tbody>

					<?php
					$mynomor = 1;
					foreach($data_riwayat_pekerjaan as $mydata_riwayat_pekerjaan) : ?>
						<tr>
							<!-- <td style="text-align:center;"><?=$mydata_riwayat_pekerjaan['NoUrutRwytPekerjaan'];?></td> -->
							<td style="text-align:center;"><?=$mynomor++?></td>
							<td><?=TanggalSaya($mydata_riwayat_pekerjaan['PrdAwal'])?></td>
							<td><?=TanggalSaya($mydata_riwayat_pekerjaan['PrdAkhir'])?></td>
							<td><?=$mydata_riwayat_pekerjaan['Perusahaan']?></td>
							<td><?=$mydata_riwayat_pekerjaan['Alamat']?></td>
							<td><?=$mydata_riwayat_pekerjaan['Industri']?></td>
							<td><?=$mydata_riwayat_pekerjaan['Jabatan']?></td>
							<td><?=$mydata_riwayat_pekerjaan['LokasiKerja']?></td>
							<td><?=UbahGabunganUang_new($mydata_riwayat_pekerjaan['GajiPokok'])?></td>
							<td><?=UbahGabunganUang_new($mydata_riwayat_pekerjaan['Tunjangan'])?></td>
							<td><?=$mydata_riwayat_pekerjaan['UraianPekerjaan']?></td>

							<td class="text-center">
								<a href="javascript:void(0)" class="btn btn-xs btn-danger hapus_riwayat_pekerjaan" data-NIK="<?=$mydata_riwayat_pekerjaan['NIK']?>" data-NoUrutRwytPekerjaan="<?=$mydata_riwayat_pekerjaan['NoUrutRwytPekerjaan']?>" >Hapus <i class="fa fa-trash"></i></a>

								<a href="javascript:void(0)" class="btn btn-xs btn-warning edit_riwayat_pekerjaan" data-NIK="<?=$mydata_riwayat_pekerjaan['NIK']?>" data-NoUrutRwytPekerjaan="<?=$mydata_riwayat_pekerjaan['NoUrutRwytPekerjaan']?>" >Edit <i class="fa fa-pencil"></i></a>
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

		$('#tbl_riwayat_pekerjaan').DataTable({
			'ordering' : false
		});

		$('.btn_add_riwayat_pekerjaan').click(function(){

			var NIK = $(this).attr('data-NIK');

			jQuery.ajax({
				url: "<?=site_url('Kepegawaian/tampil_form_add_riwayat_pekerjaan')?>",
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


		$('.hapus_riwayat_pekerjaan').click(function(){
			
			var NIK = $(this).attr('data-NIK');
			var NoUrutRwytPekerjaan = $(this).attr('data-NoUrutRwytPekerjaan');


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
						url: "<?=site_url('Kepegawaian/aksi_hapus_riwayat_pekerjaan')?>",
						type: 'POST',
						dataType: 'html',
						data: {
							'NIK' : NIK,
							'NoUrutRwytPekerjaan' : NoUrutRwytPekerjaan
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


		$('.edit_riwayat_pekerjaan').click(function(){

			var NIK = $(this).attr('data-NIK');
			var NoUrutRwytPekerjaan = $(this).attr('data-NoUrutRwytPekerjaan');

			jQuery.ajax({
				url: "<?=site_url('Kepegawaian/tampil_form_edit_riwayat_pekerjaan')?>",
				type: 'POST',
				dataType: 'html',
				data: {
					'NIK' : NIK,
					'NoUrutRwytPekerjaan' : NoUrutRwytPekerjaan
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

