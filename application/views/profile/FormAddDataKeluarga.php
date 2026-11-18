<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->

<style type="text/css">
	#tbl_kel input, select{
		font-size:10px;
		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
	}
	.swal2-popup {
        font-size: 1.2rem !important;
        font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
    }
</style>

<div class="table-responsive">

	<form id="frm_kel">

		<table width="100%" border="0" id="tbl_kel" style="font-size:10px;font-family: Tahoma, Verdana, Segoe, sans-serif;">

			<tr>
				<td style="padding-top:10px;padding-bottom: 10px;width: 20%;">Nama</td>
				<td style="text-align:center;">:</td>
				<td style="padding-left:10px;" >
					<input type="text" name="Nama" class="form-control input-xs" placeholder="Nama">
				</td>
			</tr>

			<tr>
				<td style="padding-top:10px;padding-bottom: 10px;width: 20%;">Hubungan Keluarga</td>
				<td style="text-align:center;">:</td>
				<td style="padding-left:10px;" >

					<label class="radio-inline"><input type="radio" name="Hubungan" value="Ayah" checked="checked" >Ayah</label>
					<label class="radio-inline"><input type="radio" name="Hubungan" value="Ibu">Ibu</label>
					<label class="radio-inline"><input type="radio" name="Hubungan" value="Saudara Kandung">Saudara Kandung</label>
					<label class="radio-inline"><input type="radio" name="Hubungan" value="Suami/Istri">Suami/Istri</label>
					<label class="radio-inline"><input type="radio" name="Hubungan" value="Anak">Anak</label>

				</td>
			</tr>

			

			<tr>
				<td style="padding-top:10px;padding-bottom: 10px;width: 20%;">Jenis Kelamin</td>
				<td style="text-align:center;">:</td>
				<td style="padding-left:10px;" >

					<label class="radio-inline"><input type="radio" name="Kelamin" value="L" checked="checked" >Pria</label>
					<label class="radio-inline"><input type="radio" name="Kelamin" value="P">Wanita</label>
				</td>
			</tr>

			<tr>
				<td style="padding-top:10px;padding-bottom: 10px;width: 20%;">Tanggal Lahir</td>
				<td style="text-align:center;">:</td>
				<td style="padding-left:10px;" >
					<input type="date" name="TglLahir" class="form-control input-sm">
				</td>
			</tr>

			<tr>
				<td style="padding-top:10px;padding-bottom: 10px;width: 20%;">Pekerjaan</td>
				<td style="text-align:center;">:</td>
				<td style="padding-left:10px;" >
					<input type="text" name="Pekerjaan" class="form-control input-sm" placeholder="Pekerjaan">
				</td>
			</tr>

			<tr>
				<td style="padding-top:10px;padding-bottom: 10px;width: 20%;">Perusahaan</td>
				<td style="text-align:center;">:</td>
				<td style="padding-left:10px;" >
					<input type="text" name="Perusahaan" class="form-control input-sm" placeholder="Perusahaan">
				</td>
			</tr>

			

		</table>

		<table width="100%">
			<tr style="border-top:0px solid black;">
				<td style="padding-top:30px;padding-bottom: 10px;width: 22%;"></td>
				<td style="text-align:center;"></td>
				<td style="padding-left:10px;">
					<button class="btn btn-sm btn-default" data-dismiss="modal" >Batal</button>
					
				</td>
				<td style="text-align:right;">
					<input type="hidden" name="NIK" value="<?=$NIK?>">
					<button type="submit" class="btn btn-sm btn-danger btn_frm_kel">Simpan</button>
				</td>
			</tr>
		</table>

	</form>
</div>


<script type="text/javascript">
	$(function(){

		$('#frm_kel').submit(function(e){

			e.preventDefault();
			$.ajax({
				url: "<?=site_url('Kepegawaian/simpanDataKeluarga')?>",
				type: 'POST',
				dataType: 'html',
				data: $(this).serialize(),
				beforeSend : function(){
					$('.btn_frm_kel').text('Loading...').attr('disabled',true);
				}
			})
			.done(function(data) {
				

				$('.btn_frm_kel').text('Simpan').attr('disabled',false);
				$('#modal_keluarga').modal('hide');
				pilihan_menu();

				$.toast({
					text: 'Berhasil menambahkan data',
					position: 'top-right',
					stack: false,
					hideAfter: 2000,
					icon: 'success',

				});
				return false;


			})
			.fail(function() {
				$('.btn_frm_kel').text('Simpan').attr('disabled',false);
				$('#modal_keluarga').modal('hide');
				$.toast({
					text: 'Terjadi Kesalahan',
					position: 'top-right',
					stack: false,
					hideAfter: 2000,
					icon: 'error',

				});
				return false;
			});


		});

	});
</script>