<style type="text/css">
	#tbl_add_pekerjaan{
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
		width: 20%;
		padding-top: 12px;
		padding-bottom: 10px;
	}

</style>

<div class="table-responsive">
	
	<form id="form_simpan_ketrampilan">

		<table width="100%" border="0">
			<tr>
				<td style="border-bottom:1px solid silver;">Form Tambah Riwayat Pekerjaan</td>
			</tr>
		</table>
		<p></p>
		<table id="tbl_add_pekerjaan" border="0">

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
				<th class="atur_lebar_tbl">Nama Perusahaan</th>
				<td class="atur_titik_dua">:</td>
				<td>
					<input type="text" name="Perusahaan" class="form-control input-sm" placeholder="Masukkan Nama Perusahaan" >
				</td>
			</tr>

			<tr>
				<th class="atur_lebar_tbl">Alamat Perusahaan</th>
				<td class="atur_titik_dua">:</td>
				<td>
					<textarea name="Alamat" class="form-control input-sm" placeholder="Masukkan Alamat Perusahaan"></textarea>
				</td>
			</tr>

			<tr>
				<th class="atur_lebar_tbl">Bidang Industri</th>
				<td class="atur_titik_dua">:</td>
				<td>
					<input type="text" name="Industri" class="form-control input-sm" placeholder="Masukkan Bidang Industri" >
				</td>
			</tr>

			<tr>
				<th class="atur_lebar_tbl">Jabatan Terakhir</th>
				<td class="atur_titik_dua">:</td>
				<td>
					<!-- <input type="text" name="Jabatan" class="form-control input-sm" placeholder="Masukkan Jabatan Terakhir" > -->
					<select name="Jabatan" class="form-control input-sm select2">
						<option value="">-- Pilih --</option>
						<?php foreach(getAllJabatan() as $myjabatan) : ?>
							<option value="<?=$myjabatan->PositionName?>"><?=$myjabatan->PositionName?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>

			<tr>
				<th class="atur_lebar_tbl">Lokasi Kerja</th>
				<td class="atur_titik_dua">:</td>
				<td>
					<input type="text" name="LokasiKerja" class="form-control input-sm" placeholder="Masukkan Lokasi Kerja" >
				</td>
			</tr>

			<tr>
				<th class="atur_lebar_tbl">Gaji Pokok</th>
				<td class="atur_titik_dua">:</td>
				<td>
					<input type="text" name="GajiPokok" class="form-control input-sm uang" placeholder="Masukkan Gaji Pokok" >
				</td>
			</tr>

			<tr>
				<th class="atur_lebar_tbl">Tunjangan</th>
				<td class="atur_titik_dua">:</td>
				<td>
					<input type="text" name="Tunjangan"  class="form-control input-sm uang" placeholder="Masukkan Tunjangan" >
				</td>
			</tr>

			<tr>
				<th class="atur_lebar_tbl">Uraian Pekerjaan</th>
				<td class="atur_titik_dua">:</td>
				<td>
					<textarea name="UraianPekerjaan" class="form-control input-sm" placeholder="Masukkan Uraian Pekerjaan"></textarea>
				</td>
			</tr>



			<tr >
				<td class="atur_lebar_tbl"></td>
				<td class="atur_titik_dua"></td>
				<td style="text-align:right;">
					<input type="hidden" name="NIK" value="<?=$NIK?>">
					<p></p>
					<button type="submit" class="btn btn-primary btn-sm btn_simpan_riwayat_pekerjaan">Simpan</button>
				</td>
			</tr>


		</table>

	</form>

</div>

<script type="text/javascript" src="<?=base_url('assets/jquery.mask.min.js')?>" ></script>
<script type="text/javascript">
	$(function(){

		$('.select2').select2({
			width : '100%'
		});

		$('.uang').mask('000,000,000,000,000,000,000,000,000', {reverse: true});

		$('#form_simpan_ketrampilan').submit(function(e){

			e.preventDefault();

			jQuery.ajax({
				url: "<?=site_url('Kepegawaian/aksi_simpan_riwayat_pekerjaan')?>",
				type: 'POST',
				dataType: 'HTML',
				data: $(this).serialize(),
				beforeSend : function(){
					$('.btn_simpan_riwayat_pekerjaan').text('Loading...').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('.btn_simpan_riwayat_pekerjaan').text('Simpan').attr('disabled',false);
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