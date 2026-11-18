<style type="text/css">
	#tbl_posisi_pekerjaan{
		font-size: 12px;
		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
	}

	#tbl_posisi_pekerjaan tr th{
		padding-top: 12px;
		padding-bottom: 12px;
	}

	/*#tbl_posisi_pekerjaan thead th{*/
	/*	text-align: center;
		width: 10%;
	}*/

	/*#tbl_posisi_pekerjaan{
		width: 100%;
	}*/
</style>

<div class="row">
	<div class="col-sm-12">
		
		<div class="table-responsive">
			<form id="form_ubah_posisi_pekerjaan">
				<table id="tbl_posisi_pekerjaan" width="100%" style="border-collapse: collapse;" border="0">

					<tr>
						<th style="width:15%;">Terdaftar pada company</th>
						<td style="width:1%;text-align: center;" >:</td>
						<td style="width:15%;">
							<select name="Company" class="form-control input-sm" disabled="disabled">
								<option value="">-- Pilih Company -- </option>

								<?php
								foreach (list_company() as $data_company) : ?>

									<option value="<?=$data_company?>" <?=($data_company==$CompanyKaryawan) ? 'selected' : ''  ?> ><?=$data_company?></option>

								<?php endforeach; ?> 
							</select>
						</td>
						<td></td>
					</tr>

					<tr>
						<th style="width:15%;">Posisi Jabatan 1</th>
						<td style="width:1%;text-align: center;" >:</td>
						<td style="width:20%;">
							<select name="ID_JobPosition" class="form-control input-sm select2">
								<option value="">-- Pilih --</option>

								<?php foreach ($list_jabatan as $data_jabatan): ?>
									<option value="<?=$data_jabatan['ID_JobPosition']?>" <?=($data_jabatan['ID_JobPosition'] == $data_karyawan['ID_JobPosition']) ? 'selected' : '' ?>  ><?=$data_jabatan['PositionName']?></option>
								<?php endforeach ?>
							</select>
						</td>
						<td></td>
					</tr>

					<tr>
						<th style="width:15%;">Posisi Jabatan 2</th>
						<td style="width:1%;text-align: center;" >:</td>
						<td style="width:15%;">
							<select name="ID_JobPosition1" class="form-control input-sm select2">
								<option value="">-- Pilih --</option>

								<?php foreach ($list_jabatan as $data_jabatan): ?>
									<option value="<?=$data_jabatan['ID_JobPosition']?>" <?=($data_jabatan['ID_JobPosition'] == $data_karyawan['ID_JobPosition1']) ? 'selected' : '' ?>  ><?=$data_jabatan['PositionName']?></option>
								<?php endforeach ?>
							</select>
						</td>
						<td></td>
					</tr>

					<tr>
						<th style="width:15%;">Golongan</th>
						<td style="width:1%;text-align: center;" >:</td>
						<td style="width:15%;">
							<select name="Golongan" class="form-control input-sm">
								<option value="">-- Pilih --</option>
								<?php

								$list_Golongan = get_data_golongan();

								foreach($list_Golongan as $mygolongan) : ?>

									<option value="<?=$mygolongan['IdGolongan']?>" <?=($mygolongan['IdGolongan'] == $data_karyawan['Golongan']) ? 'selected' : ''  ?> ><?=$mygolongan['IdGolongan'].' - '.$mygolongan['NamaGolongan']?></option>
								<?php endforeach; ?>
							</select>
						</td>
						<td></td>
					</tr>

					<tr>
						<th style="width:15%;">Grade</th>
						<td style="width:1%;text-align: center;" >:</td>
						<td style="width:15%;">
							<select name="Grade" class="form-control input-sm">
								<option value="">-- Pilih --</option>
								<?php

								$list_grade = get_data_grade();

								foreach($list_grade as $mygrade) : ?>
									<option value="<?=$mygrade['IdGrade']?>" <?=($mygrade['IdGrade']  == $data_karyawan['Grade']) ? 'selected' : ''  ?>  ><?=$mygrade['IdGrade'].' - '.$mygrade['NamaGrade']?></option>
								<?php endforeach; ?>
							</select>
						</td>
						<td></td>
					</tr>

					<tr>
						<th style="width:15%;">Tanggal Mulai Menjabat</th>
						<td style="width:1%;text-align: center;" >:</td>
						<td style="width:15%;">
							<input type="date" name="PrdAwal"  class="form-control input-sm" value="<?=($data_karyawan['PrdAwal'] !='') ? $data_karyawan['PrdAwal'] : ''  ?>">
						</td>
						<td></td>
					</tr>

					<tr>
						<th style="width:15%;">Uraian Pekerjaan</th>
						<td style="width:1%;text-align: center;" >:</td>
						<td style="width:15%;">
							<textarea class="form-control input-sm" name="UraianPekerjaan" placeholder="Uraian Pekerjaan" ><?=($data_karyawan['UraianPekerjaan'] !='') ? $data_karyawan['UraianPekerjaan'] : ''  ?></textarea>
						</td>
						<td></td>
					</tr>

					<tr>
						<th style="width:15%;">Lokasi Kerja Saat Ini</th>
						<td style="width:1%;text-align: center;" >:</td>
						<td style="width:15%;">
							<select name="LokasiKerja" class="form-control input-sm">
								<option value="">-- Pilih --</option>
								<?php foreach(get_data_lokasikerja() as $data_lokasikerja ) : ?>
									<option value="<?=$data_lokasikerja['NamaLokasi']?>" <?=($data_lokasikerja['NamaLokasi'] == $data_karyawan['LokasiKerja'] ) ? 'selected' : ''  ?> ><?=$data_lokasikerja['NamaLokasi']?></option>
								<?php endforeach; ?>
							</select>
						</td>
						<td></td>
					</tr>

					<tr>
						<th style="width:15%;"></th>
						<td style="width:1%;text-align: center;" ></td>
						<td style="width:15%;">

							<input type="hidden" name="NIK" value="<?=$NIK?>" >

							<button type="submit" class="btn btn-sm btn-success btn_update_posisi_pekerjaan ">Update</button>
						</td>
						<td></td>
					</tr>

				</table>
			</form>

		</div>

	</div>
</div>

<script type="text/javascript">
	$(function(){

		// $('input[name="PrdAwal"]').val('');

		$('.select2').select2({
			width : '100%'
		})

		$('#form_ubah_posisi_pekerjaan').submit(function(e){

			e.preventDefault();

			jQuery.ajax({
				url: "<?=site_url('Kepegawaian/aksi_update_posisi_pekerjaan')?>",
				type: 'POST',
				dataType: 'HTML',
				data: $(this).serialize(),
				cache : false,
				beforeSend : function(){
					$('.btn_update_posisi_pekerjaan').text('Loading...').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('.btn_update_posisi_pekerjaan').text('Update').attr('disabled',false);
					pilihan_menu();
				},
				success: function(data, textStatus, xhr) {

					// alert(data);
					// console.log(data);
					// return false;
					
					if (data == 'berhasil') {
						$.toast({
							text: 'Berhasil mengubah data',
							position: 'top-right',
							stack: false,
							hideAfter: 2000,
							icon: 'success',

						});
						return false;
					}else{
						$.toast({
							text: 'gagal mengubah data',
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
	})


</script>

