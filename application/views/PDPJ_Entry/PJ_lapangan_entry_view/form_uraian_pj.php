<style type="text/css">
	.atur_lebar{
		width: 100px;
		padding-top: 10px;
		padding-bottom: 10px;
	}
	.atur_titik_dua_uraian{
		text-align: center;
		width: 20px;
	}
	/*#tbl_detail_pd th td{
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}
	#tbl_detail_pd input[type="text"]{
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}
	#tbl_detail_pd input[type="date"]{
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}
	#tbl_detail_pd select{
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}
	#tbl_detail_pd textarea{
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}*/
	.detail_atur{
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
		
	}
</style>

<table width="100%" border="0">
	<tr>
		<th style="color:blue;text-transform: uppercase;">Data Entry</th>
	</tr>
</table>

<div class="row">


	<form id="form_uraian_pj">

		<div class="col-sm-6">
			<table width="100%" border="0">
				<tr>
					<th style="font-size:12px !important;text-transform: uppercase;">Permintaan Dana</th>
				</tr>
			</table>
			<br>

			<table width="100%" border="0" id="tbl_detail_pd" >
				<tr>
					<td class="atur_lebar detail_atur">No</td>
					<td class="atur_titik_dua_uraian detail_atur">:</td>
					<td>
						<input type="text"  class="form-control input-sm detail_atur" value="<?=$uraian_data['NoUrut']?>" disabled="disabled" >
					</td>
				</tr>

				<tr>
					<td class="atur_lebar detail_atur">Kode RAP</td>
					<td class="atur_titik_dua_uraian detail_atur">:</td>
					<td >
						<!-- <textarea class="form-control input-sm detail_atur" disabled="disabled"><?=$uraian_data['KdRAP'].' - '.$uraian_data['UraianRAP']?></textarea> -->
						<input type="text" class="form-control input-sm detail_atur" value="<?=$uraian_data['KdRAP'].' - '.$uraian_data['UraianRAP']?>" disabled="disabled">
					</td>
				</tr>

				<tr>
					<td class="atur_lebar detail_atur">Uraian</td>
					<td class="atur_titik_dua_uraian detail_atur">:</td>
					<td style="padding-top:10px;">
						<!-- <textarea class="form-control input-sm detail_atur" disabled="disabled"><?=$uraian_data['Uraian']?></textarea> -->
						<input type="text" class="form-control input-sm detail_atur" value="<?=$uraian_data['Uraian']?>" disabled="disabled">
					</td>
				</tr>

				<tr>
					<td class="atur_lebar detail_atur">Volume</td>
					<td class="atur_titik_dua_uraian detail_atur">:</td>
					<td>
						<input type="text"  class="form-control input-sm detail_atur" value="<?=new_decimal($uraian_data['Vol'])?>" disabled="disabled">
					</td>
				</tr>

				<tr>
					<td class="atur_lebar detail_atur">Satuan</td>
					<td class="atur_titik_dua_uraian detail_atur">:</td>
					<td>
						<input type="text"  class="form-control input-sm detail_atur" value="<?=$uraian_data['Uom']?>" disabled="disabled">
					</td>
				</tr>

				<tr>
					<td class="atur_lebar detail_atur">Harga Satuan</td>
					<td class="atur_titik_dua_uraian detail_atur">:</td>
					<td>
						<input type="text"  class="form-control input-sm detail_atur" value="<?=number_format($uraian_data['HrgSatuan'])?>" disabled="disabled">
					</td>
				</tr>

			</table>


		</div>

		<div class="col-sm-6">
			<table width="100%" border="0">
				<tr>
					<th style="font-size:12px !important;text-transform: uppercase;">Pertanggungjawaban Dana</th>
				</tr>
			</table>
			<br>
			<table width="100%" border="0">
				<tr>
					<td class="atur_lebar detail_atur">No</td>
					<td class="atur_titik_dua_uraian detail_atur">:</td>
					<td>
						<input type="text"  class="form-control input-sm detail_atur" value="<?=$uraian_data['NoUrut']?>" disabled="disabled" >
					</td>
				</tr>

				<tr>
					<td class="atur_lebar detail_atur">Kode RAP</td>
					<td class="atur_titik_dua_uraian detail_atur">:</td>
					<td>
						<!-- <textarea  class="form-control input-sm detail_atur" disabled="disabled"><?=$uraian_data['KdRAP'].' - '.$uraian_data['UraianRAP']?></textarea> -->

						<input type="text" class="form-control input-sm detail_atur" value="<?=$uraian_data['KdRAP'].' - '.$uraian_data['UraianRAP']?>" disabled="disabled">
					</td>
				</tr>

				<tr>
					<td class="atur_lebar detail_atur">Uraian</td>
					<td class="atur_titik_dua_uraian detail_atur">:</td>
					<td style="padding-top:10px;">
						<!-- <textarea  class="form-control input-sm detail_atur" name="PjUraian" id="PjUraian" placeholder="Uraian PJ" <?=$btn_dimatikan_uraian?> required><?=$uraian_data['PjUraian']?></textarea> -->
						<input type="text" name="PjUraian" id="PjUraian" placeholder="Uraian PJ" class="form-control input-sm detail_atur" value="<?=$uraian_data['PjUraian']?>" required <?=$btn_dimatikan_uraian?> >
					</td>
				</tr>

				<tr>
					<td class="atur_lebar detail_atur">Volume</td>
					<td class="atur_titik_dua_uraian detail_atur">:</td>
					<td>
						<input type="text" name="PjVol" id="PjVol" class="form-control input-sm detail_atur" value="<?=($uraian_data['PjVol'] == 0) ? 1 : new_decimal($uraian_data['PjVol']); ?>" onkeypress="return hanyaAngka(event)" <?=$btn_dimatikan_uraian?> required>
					</td>
				</tr>

				<tr>
					<td class="atur_lebar detail_atur">Satuan</td>
					<td class="atur_titik_dua_uraian detail_atur">:</td>
					<td>
						<input type="text" name="Uom" id="Uom"  class="form-control input-sm detail_atur" value="<?=$uraian_data['Uom']?>" disabled="disabled">
					</td>
				</tr>

				<tr>
					<td class="atur_lebar detail_atur">Harga Satuan</td>
					<td class="atur_titik_dua_uraian detail_atur">:</td>
					<td>
						<input type="text" name="PjHrgSatuan" id="PjHrgSatuan" class="form-control input-sm uang detail_atur" value="<?=number_format($uraian_data['PjHrgSatuan'])?>" <?=$btn_dimatikan_uraian?> required> 	

					</td>
				</tr>

			</table>
		</div>

		<div class="col-sm-12" <?=($btn_dimatikan_uraian !='')  ? 'hidden' : $btn_dimatikan_uraian ?>>
			<table width="100%" border="0">
				<tr>
					<td>
						<hr>
						<input type="hidden" name="NoUrut" value="<?=$uraian_data['NoUrut']?>">
						<input type="hidden" name="NoPD" value="<?=$uraian_data['NoPD']?>">
						<input type="hidden" name="status_detail_PdDtl" value="<?=$status_detail_PdDtl?>">
						<button type="submit" class="btn btn-md btn-primary pull-right" id="btn_simpan_uraian_pj" <?=$btn_dimatikan_uraian?> >Simpan</button>
					</td>

				</tr>



			</table>
		</div>

	</form>
	

</div>

<script type="text/javascript">
	$(function(){

		$('.uang').mask("#,##0", {
			reverse: true
		});


		$('#form_uraian_pj').submit(function(e){
			e.preventDefault();

			jQuery.ajax({
				url: '<?=site_url("PDPJ/PJ_lapangan_entry_controller/simpan_uraian_pj")?>',
				type: 'POST',
				dataType: 'HTML',
				data: $(this).serialize(),
				beforeSend : function(){
					$('#btn_simpan_uraian_pj').attr("disabled",true).text('Loading...');
				},
				complete: function(xhr, textStatus) {
					$('#btn_simpan_uraian_pj').attr("disabled",false).text('Simpan');
					$('#modal_uraian_pj').modal('hide');
					tampil_uraian_pj();
				},
				success: function(data, textStatus, xhr) {
					// alert(data);
					// return false;

					if (data == 'berhasil') {
						$.toast({
							text: 'data berhasil di ubah',
							position: 'bottom-right',
							stack: false,
							hideAfter: 3000,
							icon: 'success',

						});
						return false;
					}

					if (data == 'gagal') {
						$.toast({
							text: 'Gagal saat menyimpan data',
							position: 'bottom-right',
							stack: false,
							hideAfter: 3000,
							icon: 'error',

						});
						return false;
					}
				},
				error: function(xhr, textStatus, errorThrown) {

					$.toast({
						text: 'Terjadi Kesalahan',
						position: 'bottom-right',
						stack: false,
						hideAfter: 3000,
						icon: 'error',

					});
					return false;
				}
			});


		});

	})
</script>