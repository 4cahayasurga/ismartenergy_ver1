
<style type="text/css">
	#tbl_EC input, textarea{
		font-size:12px;
		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
	}
</style>

<div class="table-responsive">

	<form id="FormEditEC">

		<table width="100%" border="0" id="tbl_EC" style="font-size:12px;font-family: Tahoma, Verdana, Segoe, sans-serif;">

			<tr>
				<td style="padding-top:20px;padding-bottom: 10px;width: 20%;">Nama</td>
				<td style="text-align:center;">:</td>
				<td style="padding-left:10px;" >
					<input type="text" name="EmergencyName" id="EmergencyName" class="form-control input-xs" placeholder="Nama" value="<?=$dataEC->EmergencyName?>">
				</td>
			</tr>

			<tr>
				<td style="padding-top:20px;padding-bottom: 10px;width: 20%;">Hubungan Keluarga</td>
				<td style="text-align:center;">:</td>
				<td style="padding-left:10px;" >
					<input type="text" name="EmergencyRelationShip" id="EmergencyRelationShip" class="form-control input-xs" placeholder="Hubungan Keluarga" value="<?=$dataEC->EmergencyRelationShip?>" >
				</td>
			</tr>

			<tr>
				<td style="padding-top:20px;padding-bottom: 10px;width: 20%;">Alamat</td>
				<td style="text-align:center;">:</td>
				<td style="padding-left:10px;" >
					<textarea name="EmergencyAddress" id="EmergencyAddress" class="form-control input-xs" placeholder="Alamat"><?=$dataEC->EmergencyAddress?></textarea>
				</td>
			</tr>

			<tr>
				<td style="padding-top:20px;padding-bottom: 10px;width: 20%;">No. Telp/HP</td>
				<td style="text-align:center;">:</td>
				<td style="padding-left:10px;" >
					<input type="text" name="EmergencyPhone" id="EmergencyPhone" class="form-control input-xs" placeholder="No. Telp/HP" value="<?=$dataEC->EmergencyPhone?>">
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
					<input type="hidden" name="NIK" value="<?=$dataEC->NIK?>">
					<input type="hidden" name="LedgerNo" value="<?=$dataEC->LedgerNo?>">
					<button type="submit" class="btn btn-sm btn-danger btn_update_EC">Update</button>
				</td>
			</tr>
		</table>

	</form>
</div>


<script type="text/javascript">
	$(function(){

		$('#FormEditEC').submit(function(e){

			e.preventDefault();

			jQuery.ajax({
				url: "<?=site_url('Kepegawaian/aksiUpdate_EC')?>",
				type: 'POST',
				dataType: 'HTML',
				data: $(this).serialize(),
				beforeSend : function(){
					$('.btn_update_EC').text('Loading...').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('.btn_update_EC').text('Update').attr('disabled',false);
					$('#modal_keluarga').modal('hide');
					pilihan_menu();
				},
				success: function(data, textStatus, xhr) {
					// alert(data);
					// return false;
					
					if (data == 'berhasil') {

						$.toast({
							text: 'data telah diubah',
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