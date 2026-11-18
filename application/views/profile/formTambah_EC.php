
<style type="text/css">
	#tbl_EC input, textarea{
		font-size:12px;
		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
	}
</style>

<div class="table-responsive">

	<form id="MyFormTambah_EC">

		<table width="100%" border="0" id="tbl_EC" style="font-size:12px;font-family: Tahoma, Verdana, Segoe, sans-serif;">

			<tr>
				<td style="padding-top:20px;padding-bottom: 10px;width: 20%;">Nama</td>
				<td style="text-align:center;">:</td>
				<td style="padding-left:10px;" >
					<input type="text" name="EmergencyName" id="EmergencyName" class="form-control input-xs" placeholder="Nama">
				</td>
			</tr>

			<tr>
				<td style="padding-top:20px;padding-bottom: 10px;width: 20%;">Hubungan Keluarga</td>
				<td style="text-align:center;">:</td>
				<td style="padding-left:10px;" >
					<input type="text" name="EmergencyRelationShip" id="EmergencyRelationShip" class="form-control input-xs" placeholder="Hubungan Keluarga">
				</td>
			</tr>

			<tr>
				<td style="padding-top:20px;padding-bottom: 10px;width: 20%;">Alamat</td>
				<td style="text-align:center;">:</td>
				<td style="padding-left:10px;" >
					<textarea name="EmergencyAddress" id="EmergencyAddress" class="form-control input-xs" placeholder="Alamat"></textarea>
				</td>
			</tr>

			<tr>
				<td style="padding-top:20px;padding-bottom: 10px;width: 20%;">No. Telp/HP</td>
				<td style="text-align:center;">:</td>
				<td style="padding-left:10px;" >
					<input type="text" name="EmergencyPhone" id="EmergencyPhone" class="form-control input-xs" placeholder="No. Telp/HP">
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
					<button type="submit" class="btn btn-sm btn-danger btn_simpan_EC">Simpan</button>
				</td>
			</tr>
		</table>

	</form>
</div>


<script type="text/javascript">
	$(function(){

		$('#MyFormTambah_EC').submit(function(e){

			e.preventDefault();

			jQuery.ajax({
				url: "<?=site_url('Kepegawaian/aksiSimpan_EC')?>",
				type: 'POST',
				dataType: 'HTML',
				data: $(this).serialize(),
				beforeSend : function(){
					$('.btn_simpan_EC').text('Loading...').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('.btn_simpan_EC').text('Simpan').attr('disabled',false);
					$('#modal_keluarga').modal('hide');
					pilihan_menu();
				},
				success: function(data, textStatus, xhr) {
					
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