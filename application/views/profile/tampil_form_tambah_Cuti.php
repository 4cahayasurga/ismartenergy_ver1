<style type="text/css">
	#tbl_tambah_dokumen_pendukung {
		width: 100%;
		/*border: 1px solid black;*/
		font-size: 10px;
		font-family: TimesNewRoman, "Times New Roman", Times, Baskerville, Georgia, serif;
	}

	.atur_titik_dua {
		width: 2%;
		text-align: center;
	}

	.atur_lebar_tbl {
		width: 20%;
		padding-top: 12px;
		padding-bottom: 10px;
	}
</style>

<div class="table-responsive">

	<form id="form_simpan_dokumen">

		<table width="100%" border="0">
			<tr>
				<th style="border-bottom:1px solid silver;bold">Form Pengajuan Cuti</th>
			</tr>
		</table>
		<p></p>

		<table id="tbl_tambah_dokumen_pendukung" border="0" style="padding-top:10px;">


			<tr>
				<th style="vertical-align: top;" class="atur_lebar_tbl">Tipe Request</th>
				<td class="atur_titik_dua" style="vertical-align: top;">:</td>
				<td colspan="3">
					<select name="statusReq" id="statusReq" class="select2 form-control">
						<option value="Masuk">Masuk</option>
						<option value="Cuti">Cuti</option>
						<option value="Dinas Proyek">Dinas Proyek</option>
						<option value="Sakit">Sakit</option>
						<option value="Cuti Melahirkan">Cuti Melahirkan</option>
						<option value="Cuti Menikah">Cuti Menikah</option>
					</select>
				</td>
			</tr>

			<tr>
				<th class="atur_lebar_tbl" style="vertical-align: top;">Tanggal</th>
				<td class="atur_titik_dua" style="vertical-align: top;">:</td>
				<td style="vertical-align: top; width: 40%;">
					<input type="date" name="DrTgl" id="DrTgl" class="form-control">
				</td>
				<td width="5px">s/d</td>
				<td style="vertical-align: top;">
					<input type="date" name="SpTgl" id="SpTgl" class="form-control">
				</td>

			</tr>

			<tr>
				<th class="atur_lebar_tbl" style="vertical-align: top;">Keterangan</th>
				<td class="atur_titik_dua" style="vertical-align: top;">:</td>
				<td colspan="3">
					<Textarea class="form-control" rows="3"></Textarea>
				</td>
			</tr>

			<tr>
				<th class="atur_lebar_tbl" style="vertical-align: top;">Karyawan Pengganti</th>
				<td class="atur_titik_dua" style="vertical-align: top;">:</td>
				<td colspan="3">
					<select name="Karpeng" id="Karpeng" class="form-control">
						<?php foreach ($Karyawan as $kr) : ?>
							<option value="<?= $kr->Nama; ?>"><?= $kr->Nama; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>



			<tr>
				<td class="atur_lebar_tbl"></td>
				<td class="atur_titik_dua"></td>
				<td style="text-align:right;">
					<input type="hidden" name="NIK" value="<?= $NIK ?>">
					<p></p>
					<button type="submit" class="btn btn-primary btn-sm btn_simpan_dokumen">Simpan</button>
				</td>
			</tr>


		</table>

	</form>

</div>

<script type="text/javascript">
	$(function() {

		$('#NamaFile').bind('change', function() {
			var a = (this.files[0].size);

			if (a > 2000000) {
				$('#NamaFile').val('');
				$.toast({
					text: 'Ukuran File Maximal 2MB',
					position: 'top-right',
					stack: false,
					hideAfter: 3000,
					icon: 'error',

				});
				return false;
			};
		});

		$('#form_simpan_dokumen').submit(function(e) {



			e.preventDefault();

			var formData = new FormData(this);

			jQuery.ajax({
				url: "<?= site_url('Kepegawaian/form_simpan_dokumen') ?>",
				type: 'POST',
				dataType: 'HTML',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$('.btn_simpan_dokumen').text('Loading...').attr('disabled', true);
				},
				complete: function(xhr, textStatus) {
					$('.btn_simpan_dokumen').text('Simpan').attr('disabled', false);
					$('#modal_keluarga').modal('hide');
					pilihan_menu();
				},
				success: function(data, textStatus, xhr) {

					// alert(data);/
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
					} else {
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
