
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

	<form id="FormTambah_pendidikan">

		<table class="tbl_pen" width="100%" border="0"  style="border-collapse:collapse;">
			<tr>
				<td style="width:100px;">Tingkat Pendidikan </td>
				<td>:</td>
				<td>
					<!-- <input type="text" name="TgkPendidikan" id="TgkPendidikan" class="form-control input-xs" placeholder="Tingkat Pendidikan"> -->
					<select name="TgkPendidikan" id="TgkPendidikan"  class="form-control input-sm select2" required>
						<option value="">-- Pilih -- </option>
						<option value="S2 - Magister">S2 - Magister</option>
						<option value="S1 - Sarjana">S1 - Sarjana</option>
						<option value="D3 - Diploma">D3 - Diploma</option>
						<option value="SMA/SMK">SMA/SMK</option>
						<option value="SMP">SMP</option>
						<option value="SD">SD</option>
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
					<input type="date" name="PrdAwal" id="PrdAwal" class="form-control input-xs">
				</td>
				<td style="width:2%;text-align: center;padding-left:10px;padding-right: 10px;">s.d</td>
				<td>
					<input type="date" name="PrdAkhir" id="PrdAkhir" class="form-control input-xs">
				</td>
				<td></td>
			</tr>
		</table>

		<table class="tbl_pen_dua" width="100%" border="0"  style="border-collapse:collapse;">
			<tr>
				<td style="width:100px;padding-top:10px !important;padding-bottom:10px;">Nama Institusi</td>
				<td>:</td>
				<td style="padding-left:4px;">
					<input type="text" name="Institusi" id="Institusi" class="form-control input-xs" placeholder="Nama Institusi">
				</td>
			</tr>

			<tr>
				<td style="width:100px;">Alamat Institusi</td>
				<td>:</td>
				<td style="padding-left:4px;">
					<textarea name="Alamat" id="Alamat" class="form-control input-xs" placeholder="Alamat Institusi"></textarea>
				</td>
			</tr>

			<tr>
				<td>Jurusan</td>
				<td>:</td>
				<td style="padding-left:4px;">
				<select class="form-control input-sm select2" name="Jurusan" id="Jurusan" >
					<option value="">--- Pilih Jurusan ---</option>
					<option value="IPA">SMA - IPA</option>
					<option value="IPS">SMA - IPS</option>
					<option value="BAHASA">SMA - BAHASA</option>
					<option value="ARSITEKTUR">ARSITEKTUR </option>
					<option value="TEKNIK SIPIL">TEKNIK SIPIL</option>
					<option value="TEKNIK MESIN">TEKNIK MESIN</option>
					<option value="TEKNIK INDUSTRI">TEKNIK INDUSTRI</option>
					<option value="TEKNIK PERTAMBANGAN">TEKNIK PERTAMBANGAN</option>
					<option value="TEKNIK GEOFISIKA">TEKNIK GEOFISIKA</option>
					<option value="TEKNIK KOMPUTER">TEKNIK KOMPUTER </option>
					<option value="TEKNIK INFORMATIKA">TEKNIK INFORMATIKA </option>
					<option value="SISTEM INFORMATIKA">SISTEM INFORMATIKA </option>
					<option value="MATEMATIKA">MATEMATIKA</option>
					<option value="EKONOMI INTERNASIONAL">EKONOMI INTERNASIONAL</option>
					<option value="EKONOMI PUBLIK">EKONOMI PUBLIK</option>
					<option value="EKONOMI REGIONAL">EKONOMI REGIONAL</option>
					<option value="EKONOMI MONETER">EKONOMI MONETER</option>
					<option value="EKONOMI PEMBANGUNAN">EKONOMI PEMBANGUNAN</option>
					<option value="EKONOMI INDUSTRI">EKONOMI INDUSTRI</option>
					<option value="EKONOMI SUMBER DAYA ALAM DAN LINGKUNGAN">EKONOMI SUMBER DAYA ALAM DAN LINGKUNGAN</option>
					<option value="EKONOMI SUMBER DAYA MANUSIA">EKONOMI SUMBER DAYA MANUSIA</option>
					<option value="MANAJEMEN PEMASARAN">MANAJEMEN PEMASARAN</option>
					<option value="MANAJEMEN KEUANGAN">MANAJEMEN KEUANGAN</option>
					<option value="MANAJEMEN AKUNTANSI">MANAJEMEN AKUNTANSI</option>
					<option value="MANAJEMEN SUMBER DAYA MANUSIA">MANAJEMEN SUMBER DAYA MANUSIA</option>
					<option value="MANAJEMEN OPERASIONAL">MANAJEMENS OPERASIONAL</option>
					<option value="AKUNTANSI KEUANGAN">AKUNTANSI KEUANGAN</option>
					<option value="AKUNTANSI PERPAJAKANL">AKUNTANSI PERPAJAKAN</option>
					<option value="AUDIT">AUDIT</option>
				</select>
					<!-- <input type="text" name="Jurusan" id="Jurusan" class="form-control input-xs" placeholder="Jurusan"> -->
				</td>
			</tr>

			<tr>
				<td>Status Kelulusan</td>
				<td>:</td>
				<td style="padding-left:4px;">
					<input type="text" name="LlsTdkLls" id="LlsTdkLls" class="form-control input-xs" placeholder="Status Kelulusan">
				</td>
			</tr>

			<tr>
				<td>Nilai (IPK)</td>
				<td>:</td>
				<td style="padding-left:4px;">
					<input type="text" name="IPK" id="IPK" class="form-control input-xs" placeholder="Nilai (IPK)">
				</td>
			</tr>

			<tr>
				<td>No. Ijazah</td>
				<td>:</td>
				<td style="padding-left:4px;">
					<input type="text" name="NoIjazah" id="NoIjazah" class="form-control input-xs" placeholder="No. Ijazah">
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
					<button type="submit" class="btn btn-sm btn-danger btn_simpan_pendidikan">Simpan</button>
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

		$('#FormTambah_pendidikan').submit(function(e){

			e.preventDefault();

			jQuery.ajax({
				url: "<?=site_url('Kepegawaian/aksiSimpan_pendidikan')?>",
				type: 'POST',
				dataType: 'HTML',
				data: $(this).serialize(),
				beforeSend : function(){
					$('.btn_simpan_pendidikan').text('Loading...').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('.btn_simpan_pendidikan').text('Simpan').attr('disabled',false);
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