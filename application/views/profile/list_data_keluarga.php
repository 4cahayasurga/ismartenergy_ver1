<style type="text/css">
	#tbl_list_keluarga{
		font-size: 12px;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	#tbl_list_keluarga thead th{
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
						<button type="button" class="btn btn-sm btn-success btn_tambah_keluarga" data-NIK="<?=$NIK?>">Tambah Data Keluarga <i class="fa fa-plus"></i></button>
					</td>
				</tr>
			</table>
			
			<table class="table table-bordered table-striped table-hover table-condensed"  width="100%" id="tbl_list_keluarga" >
				<thead>
					<tr>
						<th>No.</th>
						<th>Hubungan Keluarga</th>
						<th>Nama</th>
						<th>Jenis Kelamin</th>
						<th>Tanggal Lahir</th>
						<th>Pekerjaan</th>
						<th>Perusahaan</th>
						<th>#</th>
					</tr>
				</thead>
				<tbody>

					<?php
					$mynomor = 1;
					foreach($dataKeluarga as $mydataKeluarga) : ?>
						<tr>
							<!-- <td style="text-align:center;"><?=$mydataKeluarga['NoUrutKeluarga']?></td> -->
							<td style="text-align:center;"><?=$mynomor++;?></td>
							<td><?=($mydataKeluarga['Hubungan'] == NULL || $mydataKeluarga['Hubungan'] == '' )  ? '-' : $mydataKeluarga['Hubungan'] ?></td>
							<td><?=($mydataKeluarga['Nama'] == NULL || $mydataKeluarga['Nama'] == '' )  ? '-' : $mydataKeluarga['Nama'] ?></td>
							<td><?=($mydataKeluarga['Kelamin'] == NULL || $mydataKeluarga['Kelamin'] == '' )  ? '-' : ($mydataKeluarga['Kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan'  ?></td>
							<td><?=($mydataKeluarga['TglLahir'] == NULL || $mydataKeluarga['TglLahir'] == '' )  ? '-' : TanggalSaya($mydataKeluarga['TglLahir']) ?></td>
							<td><?=($mydataKeluarga['Pekerjaan'] == NULL || $mydataKeluarga['Pekerjaan'] == '' )  ? '-' : $mydataKeluarga['Pekerjaan'] ?></td>
							<td><?=($mydataKeluarga['Perusahaan'] == NULL || $mydataKeluarga['Perusahaan'] == '' )  ? '-' : $mydataKeluarga['Perusahaan'] ?></td>
							<td class="text-center">
								<a href="javascript:void(0)" class="btn btn-xs btn-danger hapus_kel" data-NIK="<?=$mydataKeluarga['NIK']?>" data-NoUrutKeluarga="<?=$mydataKeluarga['NoUrutKeluarga']?>" >Hapus <i class="fa fa-trash"></i></a>

								<a href="javascript:void(0)" class="btn btn-xs btn-warning edit_kel" data-NIK="<?=$mydataKeluarga['NIK']?>" data-NoUrutKeluarga="<?=$mydataKeluarga['NoUrutKeluarga']?>" >Edit <i class="fa fa-pencil"></i></a>
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

		$('#tbl_list_keluarga').DataTable({
			'ordering' : false
		});

		$('.btn_tambah_keluarga').click(function(){
			
			$('#modal_keluarga_body').html('');

			var NIK = $(this).attr('data-NIK');

			$.ajax({
				url: "<?=site_url('Kepegawaian/FormAddDataKeluarga')?>",
				type: "POST",
				dataType: "HTML",
				data: {'NIK' : NIK},
				beforeSend : function(){
					$('.loadingku').show();
				}
			})
			.done(function(data) {
				$('.loadingku').hide();
				// alert(data);
				$('#modal_keluarga').modal('show');
				$('#modal_keluarga_body').html(data);
			})
			.fail(function() {

				$('.loadingku').hide();
				$.toast({
					text: 'Terjadi Kesalahan',
					position: 'bottom-right',
					stack: false,
					hideAfter: 1000,
					icon: 'error',

				});
				return false;

			});
			

		});

		$('.hapus_kel').click(function(){
			
			var NIK = $(this).attr('data-NIK');
			var NoUrutKeluarga = $(this).attr('data-NoUrutKeluarga');


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

					$.ajax({
						url: "<?=site_url('Kepegawaian/Aksi_Hapus_Keluarga')?>",
						type: "POST",
						dataType: "HTML",
						data: {'NIK' : NIK,'NoUrutKeluarga' : NoUrutKeluarga},
						beforeSend : function(){
							$('.loadingku').show();
						}
					})
					.done(function(data) {
						$('.loadingku').hide();
						// alert(data);

						pilihan_menu();

						$.toast({
							text: 'Data berhasil di hapus',
							position: 'top-right',
							stack: false,
							hideAfter: 2000,
							icon: 'info',

						});
						return false;
					})
					.fail(function() {

						$('.loadingku').hide();
						$.toast({
							text: 'Terjadi Kesalahan',
							position: 'bottom-right',
							stack: false,
							hideAfter: 1000,
							icon: 'error',

						});
						return false;

					});

					

				}
			});


		});

		$('.edit_kel').click(function(){

			$('#modal_keluarga_body').html('');

			var NIK = $(this).attr('data-NIK');
			var NoUrutKeluarga = $(this).attr('data-NoUrutKeluarga');

			$.ajax({
				url: "<?=site_url('Kepegawaian/dapat_form_edit_DataKeluarga')?>",
				type: "POST",
				dataType: "HTML",
				data: {'NIK' : NIK,'NoUrutKeluarga' : NoUrutKeluarga},
				beforeSend : function(){
					$('.loadingku').show();
				}
			})
			.done(function(data) {
				$('.loadingku').hide();
				// alert(data);
				$('#modal_keluarga').modal('show');
				$('#modal_keluarga_body').html(data);
			})
			.fail(function() {

				$('.loadingku').hide();
				$.toast({
					text: 'Terjadi Kesalahan',
					position: 'bottom-right',
					stack: false,
					hideAfter: 1000,
					icon: 'error',

				});
				return false;

			});

		});



	});
</script>