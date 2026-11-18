
<style type="text/css">
	#tbl_atur{
		/*border: 1px solid black;*/
		width: 100%;
		font-size: 10px;
		font-family: "Avant Garde", Avantgarde, "Century Gothic", CenturyGothic, "AppleGothic", sans-serif;
	}
	#tbl_atur tr td{
		padding-top: 3px;
		padding-bottom: 3px;
	}

	#atur_titik_dua{
		width: 1%;
		text-align: center;
	}
</style>

<form id="form_data_pribadi" method="POST" action="<?=site_url('Kepegawaian/aksi_ubah_data_pribadi')?>" enctype="multipart/form-data" >

	<div class="table-responsive" > 

		<table id="tbl_atur" border="0">
			<tr>
				<td style="width:10%">NIK</td>
				<td id="atur_titik_dua">:</td>
				<td style="padding-left:10px">
					<input type="text"   class="form-control input-sm" value="<?=$mydata['NIK']?>" placeholder="NIK" style="width: 10%;" disabled >
				</td>
			</tr>

			<tr>
				<td>Nama</td>
				<td id="atur_titik_dua">:</td>
				<td style="padding-left:10px">
					<input type="text" name="Nama" class="form-control input-sm" value="<?=$mydata['Nama']?>" placeholder="Nama" style="width: 30%;">
				</td>
			</tr>

			<tr>
				<td>Jenis Kelamin</td>
				<td id="atur_titik_dua">:</td>
				<td style="padding-left:10px">
					<label class="radio-inline">
						<input type="radio" name="Kelamin" id="inlineRadio1" value="L" <?=($mydata['Kelamin'] == 'L') ? 'checked="checked"' : '' ?>  > Pria
					</label>  
					<label class="radio-inline">
						<input type="radio" name="Kelamin" id="inlineRadio2" value="P" <?=($mydata['Kelamin'] == 'P') ? 'checked="checked"' : '' ?> > Perempuan
					</label>
				</td>
			</tr>

		</table>

		<table id="tbl_atur" border="0">
			<tr>
				<td style="width:10%">Tempat, Tanggal Lahir</td>
				<td id="atur_titik_dua">:</td>
				<td style="padding-left:10px;width:20%;">
					<input type="text" name="TmpLahir"  class="form-control input-sm" value="<?=$mydata['TmpLahir']?>" placeholder="Tempat Lahir" >
				</td>
				<td style="padding-left:10px;width:20%;">
					<input type="date" name="TglLahir"  class="form-control input-sm" value="<?=$mydata['TglLahir']?>" >
				</td>
				<td style="width:100%;"></td>
			</tr>
		</table>

		<table id="tbl_atur" border="0">

			<tr>
				<td style="width:10%">Kewarganegaraan</td>
				<td id="atur_titik_dua">:</td>
				<td style="padding-left:10px">
					<input type="text" name="WN"  class="form-control input-sm" value="<?=$mydata['WN']?>" placeholder="Kewarganegaraan" style="width: 30%;" >
				</td>
			</tr>

			<tr>
				<td style="width:10%">Status Pernikahan</td>
				<td id="atur_titik_dua">:</td>
				<td style="padding-left:10px">
					<input type="text" name="StsNikah"  class="form-control input-sm" value="<?=$mydata['StsNikah']?>" placeholder="Kewarganegaraan"  style="width: 30%;">
				</td>
			</tr>


			<tr>
				<td>Status Pernikahan</td>
				<td id="atur_titik_dua">:</td>
				<td style="padding-left:10px;">
					<div style="border:1px solid silver;width: 30%;padding-top: 5px;padding-bottom: 5px;">
						<label class="radio-inline" >&nbsp;
							<input type="radio" name="StsNikah" id="inlineRadio1" value="Lajang" <?=($mydata['StsNikah'] == 'Lajang') ? 'checked="checked"' : '' ?>> Lajang
						</label>

						<label class="radio-inline">
							<input type="radio" name="StsNikah" id="inlineRadio2" value="Janda/Duda" <?=($mydata['StsNikah'] == 'Janda/Duda') ? 'checked="checked"' : '' ?> > Janda/Duda
						</label>

						<label class="radio-inline">
							<input type="radio" name="StsNikah" id="inlineRadio3" value="Menikah" <?=($mydata['StsNikah'] == 'Menikah') ? 'checked="checked"' : '' ?> > Menikah
						</label>

					</div>
					

				</td>
			</tr>

			<tr>
				<td>Agama</td>
				<td id="atur_titik_dua">:</td>
				<td style="padding-left:10px">

					<div style="border:1px solid silver;width: 35%;padding-top: 5px;padding-bottom: 5px;">

						<label class="radio-inline">&nbsp;
							<input type="radio" name="Agama" id="inlineRadio1" value="Islam" <?=($mydata['Agama'] == 'Islam') ? 'checked="checked"' : '' ?> > Islam
						</label>

						<label class="radio-inline">
							<input type="radio" name="Agama" id="inlineRadio2" value="Kristen" <?=($mydata['Agama'] == 'Kristen') ? 'checked="checked"' : '' ?> > Kristen
						</label>

						<label class="radio-inline">
							<input type="radio" name="Agama" id="inlineRadio3" value="Katolik" <?=($mydata['Agama'] == 'Katolik') ? 'checked="checked"' : '' ?> > Katolik
						</label>


						<label class="radio-inline">
							<input type="radio" name="Agama" id="inlineRadio4" value="Hindu" <?=($mydata['Agama'] == 'Hindu') ? 'checked="checked"' : '' ?> > Hindu
						</label>

						<label class="radio-inline">
							<input type="radio" name="Agama" id="inlineRadio5" value="Buddha" <?=($mydata['Agama'] == 'Buddha') ? 'checked="checked"' : '' ?> > Buddha
						</label>

					</div>
					
				</td>
			</tr>

			<tr>
				<td style="width:10%">Alamat Sesuai Kartu Identitas</td>
				<td id="atur_titik_dua">:</td>
				<td style="padding-left:10px">
					<textarea name="Alamat" class="form-control input-sm" placeholder="Alamat" style="width:50%"><?=$mydata['Alamat']?></textarea>
				</td>
			</tr>

			<tr>
				<td>Provinsi</td>
				<td id="atur_titik_dua">:</td>
				<td style="padding-left:10px">
					<input type="text" name="Provinsi" class="form-control input-sm" value="<?=$mydata['Provinsi']?>" placeholder="Provinsi" style="width:30%">
				</td>
			</tr>

			<tr>
				<td>Kota</td>
				<td id="atur_titik_dua">:</td>
				<td style="padding-left:10px">
					<input type="text" name="Kota" class="form-control input-sm" value="<?=$mydata['Kota']?>" placeholder="Kota" style="width:30%">
				</td>
			</tr>


			<tr>
				<td style="width:10%">Alamat Tinggal Sekarang</td>
				<td id="atur_titik_dua">:</td>
				<td style="padding-left:10px">
					<textarea name="AlamatSurat" class="form-control input-sm" placeholder="Alamat Tinggal Sekarang" style="width:50%"><?=$mydata['AlamatSurat']?></textarea>
				</td>
			</tr>

			<tr>
				<td>Alamat Email</td>
				<td id="atur_titik_dua">:</td>
				<td style="padding-left:10px">
					<input type="email" name="Email" class="form-control input-sm" value="<?=$mydata['Email']?>" placeholder="Email" style="width:30%">
				</td>
			</tr>

			<tr>
				<td>No Telp/HP</td>
				<td id="atur_titik_dua">:</td>
				<td style="padding-left:10px">
					<input type="text" name="NoTelp" class="form-control input-sm" value="<?=$mydata['NoTelp']?>" placeholder="No Telp/HP" style="width:30%">
				</td>
			</tr>

			<tr>
				<td style="vertical-align:top;">Foto</td>
				<td id="atur_titik_dua" style="vertical-align:top;">:</td>
				<td style="padding-left:10px;">
					<span style="color:red;">Nama file tidak boleh mengandung karakter % ; / ? : @ & = + "," $ </span>
					<input type="file" name="FotoKaryawan" class="form-control input-sm"  accept="image/*" id="imgInp" style="width:30%">
					<p></p>
					
					<img id="blah" src="<?=file_get_contents(alamat_server_dan_local_dan_folder('server').'Akses_get_file/dapat_foto/'.$mydata['NIK'].'/1') ?>" style="width:200px;height: 200px;"  />

				</td>
			</tr>
			
		</table>

		
		<table width="100%">
			<tr>
				<th style="padding-top:10px;border-top:1px solid black;text-align: right;">

					
					
					<input type="hidden" name="NIK" value="<?=$mydata['NIK']?>">

					<button type="submit" class="btn btn-md btn-primary" style="display:;" >Simpan Data Pribadi</button>
				</th>
			</tr>
		</table>


	</div>

</form>

<script type="text/javascript">

	$(function(){


		$('#imgInp').bind('change', function() {
			var a=(this.files[0].size);

			if(a > 2000000) {
				$('#imgInp').val('');
				$.toast({
					text: 'Ukuran foto maximal 2MB',
					position: 'top-right',
					stack: false,
					hideAfter: 3000,
					icon: 'error',

				});
				return false;
			};
		});


		imgInp.onchange = evt => {
			const [file] = imgInp.files
			if (file) {
				blah.src = URL.createObjectURL(file)
			}
		}

	})

	
</script>
