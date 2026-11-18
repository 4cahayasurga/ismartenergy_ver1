
<style type="text/css">
	#tbl_EC input, textarea{
		font-size:12px;
		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
	}
	.tbl_pen{
		font-size:12px;
		font-family: TimesNewRoman, "Times New Roman", Times, Baskerville, Georgia, serif;
	}
	.tbl_pen input{
		font-size:12px;
		font-family: TimesNewRoman, "Times New Roman", Times, Baskerville, Georgia, serif;
	}

	.atur_lebar{
		width: 100px;
	}

	.tbl_pen_dua{
		font-size:12px;
		font-family: TimesNewRoman, "Times New Roman", Times, Baskerville, Georgia, serif;
	}
	.tbl_pen_dua input {
		font-size:12px !important;
		font-family: TimesNewRoman, "Times New Roman", Times, Baskerville, Georgia, serif;
	}

	textarea{
		font-size:12px !important;
		font-family: TimesNewRoman, "Times New Roman", Times, Baskerville, Georgia, serif;
	}

	.tbl_pen_dua td{
		padding-top: 10px;
	}
</style>

<div class="table-responsive">

	<form id="FormEdit_pendidikan">

		<table class="tbl_pen" width="100%" border="0"  style="border-collapse:collapse;">
			<tr>
				<td style="width:100px;">Tingkat Pendidikan </td>
				<td>:</td>
				<td>
					<!-- <input type="text" name="TgkPendidikan" id="TgkPendidikan" class="form-control input-xs" placeholder="Tingkat Pendidikan" value="<?=$dataPendidikan->TgkPendidikan?>"> -->
					<select name="TgkPendidikan" id="TgkPendidikan"  class="form-control input-sm select2" required>
						<option value="">-- Pilih -- </option>
						<option value="S2 - Magister" <?=($dataPendidikan->TgkPendidikan == 'S2 - Magister') ? 'selected' : ''?>>S2 - Magister</option>
						<option value="S1 - Sarjana" <?=($dataPendidikan->TgkPendidikan == 'S1 - Sarjana') ? 'selected' : ''?>>S1 - Sarjana</option>
						<option value="D3 - Diploma" <?=($dataPendidikan->TgkPendidikan == 'D3 - Diploma') ? 'selected' : ''?>>D3 - Diploma</option>
						<option value="SMA/SMK" <?=($dataPendidikan->TgkPendidikan == 'SMA/SMK') ? 'selected' : ''?>>SMA/SMK</option>
						<option value="SMP" <?=($dataPendidikan->TgkPendidikan == 'SMP') ? 'selected' : ''?>>SMP</option>
						<option value="SD" <?=($dataPendidikan->TgkPendidikan == 'SD	') ? 'selected' : ''?>>SD</option>
					</select>
				</td>
			</tr>
		</table>
		<p></p>

		<table class="tbl_pen" width="100%" border="0"  style="border-collapse:collapse;">
			<tr>
				<td style="width:100px;">Periode</td>
				<td>:</td>
				<td style="padding-left:6px;">
					<input type="date" name="PrdAwal" id="PrdAwal" class="form-control input-xs" value="<?=$dataPendidikan->PrdAwal?>" >
				</td>
				<td style="width:2%;text-align: center;padding-left:10px;padding-right: 10px;">s.d</td>
				<td>
					<input type="date" name="PrdAkhir" id="PrdAkhir" class="form-control input-xs" value="<?=$dataPendidikan->PrdAkhir?>" >
				</td>
				<td></td>
			</tr>
		</table>

		<table class="tbl_pen_dua" width="100%" border="0"  style="border-collapse:collapse;">
			<tr>
				<td style="width:100px;padding-top:10px !important;padding-bottom:10px;">Nama Institusi</td>
				<td>:</td>
				<td style="padding-left:4px;">
					<input type="text" name="Institusi" id="Institusi" class="form-control input-xs" placeholder="Nama Institusi" value="<?=$dataPendidikan->Institusi?>" >
				</td>
			</tr>

			<tr>
				<td style="width:100px;">Alamat Institusi</td>
				<td>:</td>
				<td style="padding-left:4px;">
					<textarea name="Alamat" id="Alamat" class="form-control input-xs" placeholder="Alamat Institusi"><?=$dataPendidikan->Alamat?></textarea>
				</td>
			</tr>

			<tr>
				<td>Jurusan</td>
				<td>:</td>
				<td style="padding-left:4px;">
					<!-- <input type="text" name="Jurusan" id="Jurusan" class="form-control input-xs" placeholder="Jurusan" value="<?=$dataPendidikan->Jurusan?>" > -->
					<select class="form-control input-sm select2" name="Jurusan" id="Jurusan" >
					<option value="">--- Pilih Jurusan ---</option>
					<option value="IPA" <?=($dataPendidikan->Jurusan == 'IPA') ? 'selected' : '' ?> >SMA - IPA</option>
					<option value="IPS" <?=($dataPendidikan->Jurusan == 'IPS ') ? 'selected' : '' ?>>SMA - IPS</option>
					<option value="BAHASA" <?=($dataPendidikan->Jurusan == 'BAHASA') ? 'selected' : '' ?>>SMA - BAHASA</option>
					<option value="ARSITEKTUR" <?=($dataPendidikan->Jurusan == 'ARSITEKTUR') ? 'selected' : '' ?>>ARSITEKTUR </option>
					<option value="TEKNIK SIPIL" <?=($dataPendidikan->Jurusan == 'TEKNIK SIPIL') ? 'selected' : '' ?>>TEKNIK SIPIL</option>
					<option value="TEKNIK MESIN" <?=($dataPendidikan->Jurusan == 'TEKNIK MESIN') ? 'selected' : '' ?>>TEKNIK MESIN</option>
					<option value="TEKNIK INDUSTRI" <?=($dataPendidikan->Jurusan == 'TEKNIK INDUSTRI') ? 'selected' : '' ?>>TEKNIK INDUSTRI</option>
					<option value="TEKNIK PERTAMBANGAN" <?=($dataPendidikan->Jurusan == 'TEKNIK PERTAMBANGAN') ? 'selected' : '' ?>>TEKNIK PERTAMBANGAN</option>
					<option value="TEKNIK GEOFISIKA" <?=($dataPendidikan->Jurusan == 'TEKNIK GEOFISIKA') ? 'selected' : '' ?>>TEKNIK GEOFISIKA</option>
					<option value="TEKNIK KOMPUTER" <?=($dataPendidikan->Jurusan == 'TEKNIK KOMPUTER') ? 'selected' : '' ?>>TEKNIK KOMPUTER </option>
					<option value="TEKNIK INFORMATIKA" <?=($dataPendidikan->Jurusan == 'TEKNIK INFORMATIKA') ? 'selected' : '' ?>>TEKNIK INFORMATIKA </option>
					<option value="SISTEM INFORMATIKA" <?=($dataPendidikan->Jurusan == 'SISTEM INFORMATIKA') ? 'selected' : '' ?>>SISTEM INFORMATIKA </option>
					<option value="MATEMATIKA" <?=($dataPendidikan->Jurusan == 'MATEMATIKA') ? 'selected' : '' ?>>MATEMATIKA</option>
					<option value="EKONOMI INTERNASIONAL" <?=($dataPendidikan->Jurusan == 'EKONOMI INTERNASIONAL') ? 'selected' : '' ?>>EKONOMI INTERNASIONAL</option>
					<option value="EKONOMI PUBLIK" <?=($dataPendidikan->Jurusan == 'EKONOMI PUBLIK') ? 'selected' : '' ?>>EKONOMI PUBLIK</option>
					<option value="EKONOMI REGIONAL" <?=($dataPendidikan->Jurusan == 'EKONOMI REGIONAL') ? 'selected' : '' ?>>EKONOMI REGIONAL</option>
					<option value="EKONOMI MONETER" <?=($dataPendidikan->Jurusan == 'EKONOMI MONETER') ? 'selected' : '' ?>>EKONOMI MONETER</option>
					<option value="EKONOMI PEMBANGUNAN" <?=($dataPendidikan->Jurusan == 'EKONOMI PEMBANGUNAN') ? 'selected' : '' ?>>EKONOMI PEMBANGUNAN</option>
					<option value="EKONOMI INDUSTRI" <?=($dataPendidikan->Jurusan == 'EKONOMI INDUSTRI') ? 'selected' : '' ?>>EKONOMI INDUSTRI</option>
					<option value="EKONOMI SUMBER DAYA ALAM DAN LINGKUNGAN" <?=($dataPendidikan->Jurusan == 'EKONOMI SUMBER DAYA ALAM DAN LINGKUNGAN') ? 'selected' : '' ?>>EKONOMI SUMBER DAYA ALAM DAN LINGKUNGAN</option>
					<option value="EKONOMI SUMBER DAYA MANUSIA" <?=($dataPendidikan->Jurusan == 'EKONOMI SUMBER DAYA MANUSIA') ? 'selected' : '' ?>>EKONOMI SUMBER DAYA MANUSIA</option>
					<option value="MANAJEMEN PEMASARAN" <?=($dataPendidikan->Jurusan == 'MANAJEMEN PEMASARAN') ? 'selected' : '' ?>>MANAJEMEN PEMASARAN</option>
					<option value="MANAJEMEN KEUANGAN" <?=($dataPendidikan->Jurusan == 'MANAJEMEN KEUANGAN') ? 'selected' : '' ?>>MANAJEMEN KEUANGAN</option>
					<option value="MANAJEMEN AKUNTANSI" <?=($dataPendidikan->Jurusan == 'MANAJEMEN AKUNTANSI') ? 'selected' : '' ?>>MANAJEMEN AKUNTANSI</option>
					<option value="MANAJEMEN SUMBER DAYA MANUSIA" <?=($dataPendidikan->Jurusan == 'MANAJEMEN SUMBER DAYA MANUSIA') ? 'selected' : '' ?>>MANAJEMEN SUMBER DAYA MANUSIA</option>
					<option value="MANAJEMEN OPERASIONAL" <?=($dataPendidikan->Jurusan == 'MANAJEMEN OPERASIONAL') ? 'selected' : '' ?>>MANAJEMENS OPERASIONAL</option>
					<option value="AKUNTANSI KEUANGAN" <?=($dataPendidikan->Jurusan == 'AKUNTANSI KEUANGAN') ? 'selected' : '' ?>>AKUNTANSI KEUANGAN</option>
					<option value="AKUNTANSI PERPAJAKANL" <?=($dataPendidikan->Jurusan == 'AKUNTANSI PERPAJAKANL') ? 'selected' : '' ?>>AKUNTANSI PERPAJAKAN</option>
					<option value="AUDIT" <?=($dataPendidikan->Jurusan == 'AUDIT') ? 'selected' : '' ?>>AUDIT</option>
				</select>
				</td>
			</tr>

			<tr>
				<td>Status Kelulusan</td>
				<td>:</td>
				<td style="padding-left:4px;">
					<input type="text" name="LlsTdkLls" id="LlsTdkLls" class="form-control input-xs" placeholder="Status Kelulusan" value="<?=$dataPendidikan->LlsTdkLls?>" >
				</td>
			</tr>

			<tr>
				<td>Nilai (IPK)</td>
				<td>:</td>
				<td style="padding-left:4px;">
					<input type="text" name="IPK" id="IPK" class="form-control input-xs" placeholder="Nilai (IPK)" value="<?=$dataPendidikan->IPK?>" >
				</td>
			</tr>

			<tr>
				<td>No. Ijazah</td>
				<td>:</td>
				<td style="padding-left:4px;">
					<input type="text" name="NoIjazah" id="NoIjazah" class="form-control input-xs" placeholder="No. Ijazah" value="<?=$dataPendidikan->NoIjazah?>" >
				</td>
			</tr>



		</table>


		<p></p>
		<table width="100%" border="0">
			<tr style="border-top:0px solid black;">

				<td>
					<!-- <button class="btn btn-sm btn-default" data-dismiss="modal" >Batal</button> -->
					
				</td>
				<td style="text-align:right;">
					<input type="hidden" name="NIK" value="<?=$NIK?>">
					<input type="hidden" name="NoUrutPendidikan" value="<?=$NoUrutPendidikan?>">
					<button type="submit" class="btn btn-sm btn-danger btn_update_pendidikan">Update</button>
				</td>
			</tr>
		</table>

	</form>
</div>


<script type="text/javascript">
	$(function(){
		$('.select2').select2({
			width : '100%'
		});

		$('#FormEdit_pendidikan').submit(function(e){

			e.preventDefault();

			jQuery.ajax({
				url: "<?=site_url('Kepegawaian/aksiUpdate_pendidikan')?>",
				type: 'POST',
				dataType: 'HTML',
				data: $(this).serialize(),
				beforeSend : function(){
					$('.btn_update_pendidikan').text('Loading...').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('.btn_update_pendidikan').text('Update').attr('disabled',false);
					$('#modal_keluarga').modal('hide');
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

	});
</script>