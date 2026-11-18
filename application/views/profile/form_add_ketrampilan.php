<style type="text/css">
	#my_tbl_ketrampilan{
		width: 100%;
		/*border: 1px solid black;*/
		font-size: 10px;
		font-family: "Arial Rounded MT Bold", "Helvetica Rounded", Arial, sans-serif;
	}

	.atur_titik_dua{
		width: 2%;
		text-align: center;
	}

	.atur_lebar_tbl{
		width: 30%;
		padding-top: 12px;
		padding-bottom: 10px;
	}

</style>

<div class="table-responsive">
	
	<form id="form_simpan_ketrampilan">
		
		<table id="my_tbl_ketrampilan" border="0">

			<tr>
				<th class="atur_lebar_tbl">Nama Ketrampilan/Kursus/Pelatihan</th>
				<td class="atur_titik_dua">:</td>
				<td>
					<input type="text" name="Nama" class="form-control input-sm" placeholder="Masukkan Nama Ketrampilan/Kursus/Pelatihan">
				</td>
			</tr>

			<tr>
				<th class="atur_lebar_tbl">Tanggal Dimulai</th>
				<td class="atur_titik_dua">:</td>
				<td>
					<input type="date" name="PrdAwal" class="form-control input-sm">
				</td>
			</tr>

			<tr>
				<th class="atur_lebar_tbl">Tanggal Berakhir</th>
				<td class="atur_titik_dua">:</td>
				<td>
					<input type="date" name="PrdAkhir" class="form-control input-sm">
				</td>
			</tr>

			<tr>
				<th class="atur_lebar_tbl">Nama Sertifikat</th>
				<td class="atur_titik_dua">:</td>
				<td>
					<input type="text" name="Sertifikat" class="form-control input-sm" placeholder="Masukkan Nama Sertifikat" >
				</td>
			</tr>

			<tr>
				<th class="atur_lebar_tbl">Level / Grade</th>
				<td class="atur_titik_dua">:</td>
				<td>
					<input type="text" name="Grade" class="form-control input-sm" placeholder="Masukkan Level / Grade" >
				</td>
			</tr>

			<tr>
				<th class="atur_lebar_tbl">No. Sertifikat</th>
				<td class="atur_titik_dua">:</td>
				<td>
					<input type="text" name="NoSertifikat" class="form-control input-sm" placeholder="Masukkan No Sertifikat" >
				</td>
			</tr>

			<tr>
				<th class="atur_lebar_tbl">Institusi</th>
				<td class="atur_titik_dua">:</td>
				<td>
					<input type="text" name="Institusi" class="form-control input-sm" placeholder="Masukkan Institusi" >
				</td>
			</tr>

			<tr >
				<th class="atur_lebar_tbl"></th>
				<td class="atur_titik_dua"></td>
				<td style="text-align:right;">
					<input type="hidden" name="NIK" value="<?=$NIK?>">
					<button type="submit" class="btn btn-primary btn-sm btn_simpan_ketrampilan" style="display:;" >Simpan</button>
				</td>
			</tr>


		</table>

	</form>

</div>

<script type="text/javascript">
	$(function(){

		$('#form_simpan_ketrampilan').submit(function(e){

			e.preventDefault();

			jQuery.ajax({
				url: "<?=site_url('Kepegawaian/aksiSimpan_ketrampilan')?>",
				type: 'POST',
				dataType: 'HTML',
				data: $(this).serialize(),
				beforeSend : function(){
					$('.btn_simpan_ketrampilan').text('Loading...').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('.btn_simpan_ketrampilan').text('Simpan').attr('disabled',false);
					$('#modal_keluarga').modal('hide');
					pilihan_menu();
				},
				success: function(data, textStatus, xhr) {

					// alert(data);
					// console.log(data);
					// return false;
					
					if (data == 'berhasil') {
						$.toast({
							text: 'Berhasil menambahkan data',
							position: 'top-right',
							stack: false,
							hideAfter: 2000,
							icon: 'success',

						});
						return false;
					}else{
						$.toast({
							text: 'gagal menambahkan data',
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
						position: 'top-right',
						stack: false,
						hideAfter: 2000,
						icon: 'error',

					});
					return false;
				}
			});



		});

	});
</script>