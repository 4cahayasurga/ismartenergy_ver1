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
					<input type="text" name="Nama" class="form-control input-xs" placeholder="Nama" value="<?=$DataKeluarga['Nama']?>">
				</td>
			</tr>

			<tr>
				<td style="padding-top:10px;padding-bottom: 10px;width: 20%;">Hubungan Keluarga</td>
				<td style="text-align:center;">:</td>
				<td style="padding-left:10px;" >

					<label class="radio-inline"><input type="radio" name="Hubungan" value="Ayah" <?=($DataKeluarga['Hubungan'] == 'Ayah') ? 'checked="checked"' : ''  ?>   >Ayah</label>
					<label class="radio-inline"><input type="radio" name="Hubungan" value="Ibu" <?=($DataKeluarga['Hubungan'] == 'Ibu') ? 'checked="checked"' : ''  ?>  >Ibu</label>
					<label class="radio-inline"><input type="radio" name="Hubungan" value="Saudara Kandung" <?=($DataKeluarga['Hubungan'] == 'Saudara Kandung') ? 'checked="checked"' : ''  ?>  >Saudara Kandung</label>
					<label class="radio-inline"><input type="radio" name="Hubungan" value="Suami/Istri"  <?=($DataKeluarga['Hubungan'] == 'Suami/Istri') ? 'checked="checked"' : ''  ?> >Suami/Istri</label>
					<label class="radio-inline"><input type="radio" name="Hubungan" value="Anak" <?=($DataKeluarga['Hubungan'] == 'Anak') ? 'checked="checked"' : ''  ?> >Anak</label>

				</td>
			</tr>
			

			<tr>
				<td style="padding-top:10px;padding-bottom: 10px;width: 20%;">Jenis Kelamin</td>
				<td style="text-align:center;">:</td>
				<td style="padding-left:10px;" >

					<label class="radio-inline"><input type="radio" name="Kelamin" value="L" <?=($DataKeluarga['Kelamin'] == 'L') ? 'checked="checked"' : ''  ?> >Pria</label>
					<label class="radio-inline"><input type="radio" name="Kelamin" value="P"  <?=($DataKeluarga['Kelamin'] == 'P') ? 'checked="checked"' : ''  ?> >Wanita</label>
				</td>
			</tr>


			<tr>
				<td style="padding-top:10px;padding-bottom: 10px;width: 20%;">Tanggal Lahir</td>
				<td style="text-align:center;">:</td>
				<td style="padding-left:10px;" >
					<input type="date" name="TglLahir" class="form-control input-sm" value="<?=$DataKeluarga['TglLahir']?>">
				</td>
			</tr>

			<tr>
				<td style="padding-top:10px;padding-bottom: 10px;width: 20%;">Pekerjaan</td>
				<td style="text-align:center;">:</td>
				<td style="padding-left:10px;" >
					<input type="text" name="Pekerjaan" class="form-control input-sm" placeholder="Pekerjaan" value="<?=$DataKeluarga['Pekerjaan']?>">
				</td>
			</tr>

			<tr>
				<td style="padding-top:10px;padding-bottom: 10px;width: 20%;">Perusahaan</td>
				<td style="text-align:center;">:</td>
				<td style="padding-left:10px;" >
					<input type="text" name="Perusahaan" class="form-control input-sm" placeholder="Perusahaan" value="<?=$DataKeluarga['Perusahaan']?>" >
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
					<input type="hidden" name="NoUrutKeluarga" value="<?=$NoUrutKeluarga?>">
					<button type="submit" class="btn btn-sm btn-danger btn_frm_kel" style="display:;" >Update</button>
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
				url: "<?=site_url('Kepegawaian/UpdateDataKeluarga')?>",
				type: 'POST',
				dataType: 'html',
				data: $(this).serialize(),
				beforeSend : function(){
					$('.btn_frm_kel').text('Loading...').attr('disabled',true);
				}
			})
			.done(function(data) {

				// alert(data);
				

				$('.btn_frm_kel').text('Update').attr('disabled',false);
				$('#modal_keluarga').modal('hide');
				pilihan_menu();

				$.toast({
					text: 'Berhasil mengubah data',
					position: 'top-right',
					stack: false,
					hideAfter: 2000,
					icon: 'success',

				});
				return false;


			})
			.fail(function() {
				$('.btn_frm_kel').text('Update').attr('disabled',false);
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