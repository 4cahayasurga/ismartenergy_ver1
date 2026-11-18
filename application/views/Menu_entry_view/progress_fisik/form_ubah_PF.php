<style type="text/css">
	table .atur_lebar{
		width: 230px !important;
		padding-top: 10px !important;
		padding-bottom: 10px !important;

	}
	#table_saya{
		font-size: 12px;
	}
	#table_saya input{
		font-size: 12px;
	}
</style>




<!-- <form id="FormPF_Edit"> -->
	<form method="POST" action="<?=site_url('Menu_entry/Progress_fisik/aksi_ubah_PF')?>">
		
		<div class="table-responsive">

			<table border="0" width="100%" id="table_saya" >

				<tr>
					<td class="atur_lebar">Tahun</td>
					<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
					<td>
						<select class="form-control input-sm select2" name="Tahun" id="Tahun" required>
							<option value="">-- Pilih Tahun --</option>
							<?php
							$tahun = date('Y');
							for ($i = $atur_tahun; $i < $tahun + 10; $i++) { ?>
								<option value="<?php echo $i ?>" <?=($i==$dataPF['Tahun']) ? 'selected' : '' ?> ><?php echo $i ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>

				<tr>
					<td class="atur_lebar">Bulan</td>
					<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
					<td>
						<select class="form-control input-sm select2" name="Bulan" id="Bulan" required>
							<option value="">-- Pilih Bulan--</option>
							<option value="01" <?=($dataPF['Bulan'] == '01') ? 'selected' : '' ?> >Januari</option>
							<option value="02" <?=($dataPF['Bulan'] == '02') ? 'selected' : '' ?> >Februari</option>
							<option value="03" <?=($dataPF['Bulan'] == '03') ? 'selected' : '' ?>  >Maret</option>
							<option value="04" <?=($dataPF['Bulan'] == '04') ? 'selected' : '' ?> >April</option>
							<option value="05" <?=($dataPF['Bulan'] == '05') ? 'selected' : '' ?> >Mei</option>
							<option value="06" <?=($dataPF['Bulan'] == '06') ? 'selected' : '' ?> >Juni</option>
							<option value="07" <?=($dataPF['Bulan'] == '07') ? 'selected' : '' ?> >Juli</option>
							<option value="08" <?=($dataPF['Bulan'] == '08') ? 'selected' : '' ?> >Agustus</option>
							<option value="09" <?=($dataPF['Bulan'] == '09') ? 'selected' : '' ?> >September</option>
							<option value="10" <?=($dataPF['Bulan'] == '10') ? 'selected' : '' ?> >Oktober</option>
							<option value="11" <?=($dataPF['Bulan'] == '11') ? 'selected' : '' ?> >November</option>
							<option value="12" <?=($dataPF['Bulan'] == '12') ? 'selected' : '' ?> >Desember</option>
						</select>
					</td>
				</tr>


				<tr>
					<td class="atur_lebar">Rencana Fisik Kumulatif (%)</td>
					<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
					<td>
						<input type="text"  name="RencanaK" id="RencanaK"  class="form-control input-sm" autocomplete="off" onkeypress="return hanyaAngka(event)" placeholder="0" value="<?=new_decimal($dataPF['RencanaK'])?>" required>
					</td>
				</tr>

				<tr>
					<td class="atur_lebar">Realisasi Fisik Kumulatif (%)</td>
					<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
					<td>
						<input type="text"  name="RealisasiK" id="RealisasiK"  class="form-control input-sm" autocomplete="off" onkeypress="return hanyaAngka(event)" placeholder="0" value="<?=new_decimal($dataPF['RealisasiK'])?>" required>
					</td>
				</tr>

				<tr>
					<td class="atur_lebar">Realisasi Keuangan Kumulatif (%)</td>
					<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
					<td>
						<input type="text"  name="RealisasiKeuK" id="RealisasiKeuK"class="form-control input-sm" autocomplete="off" onkeypress="return hanyaAngka(event)" placeholder="0" value="<?=new_decimal($dataPF['RealisasiKeuK'])?>" required>
					</td>
				</tr>


				<tr>
					<td class="atur_lebar">Rencana Fisik Tahun Berjalan (%)</td>
					<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
					<td>
						<input type="text"  name="RencanaTB" id="RencanaTB" class="form-control input-sm" autocomplete="off" onkeypress="return hanyaAngka(event)" placeholder="0" value="<?=new_decimal($dataPF['RencanaTB'])?>" required>
					</td>
				</tr>

				<tr>
					<td class="atur_lebar">Realisasi Fisik Tahun Berjalan (%)</td>
					<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
					<td>
						<input type="text"  name="RealisasiTB" id="RealisasiTB"  class="form-control input-sm" autocomplete="off" onkeypress="return hanyaAngka(event)" placeholder="0" value="<?=new_decimal($dataPF['RealisasiTB'])?>" required>
					</td>
				</tr>

				<tr>
					<td class="atur_lebar">Realisasi Keuangan Fisik Tahun Berjalan (%)</td>
					<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
					<td>
						<input type="text"  name="RealisasiKeuTB" id="RealisasiKeuTB"  class="form-control input-sm" autocomplete="off" onkeypress="return hanyaAngka(event)" placeholder="0" value="<?=new_decimal($dataPF['RealisasiKeuTB'])?>" required>
					</td>
				</tr>

			</table>

			<table border="0" width="100%">
				<tr>
					<td>
						<hr>
						<input type="hidden" name="JobNo" value="<?=$JobNo?>">
						<input type="hidden" name="LedgerNo" value="<?=$dataPF['LedgerNo']?>">
						<button data-dismiss="modal" aria-label="close" class="btn btn-default btn-md">Batal</button>
						<button type="submit" class="btn btn-md btn-primary pull-right btn_simpan">Update </button>
					</td>
				</tr>
			</table>

		</div>

	</form>

	<script type="text/javascript">
		$(function(){


			$('#Tahun').select2({	
				'width' : '100%'
			});

			$('#FormPF_Edit').submit(function(e){
				e.preventDefault();

				jQuery.ajax({
					url: '<?=site_url('Menu_entry/Progress_fisik/aksi_ubah_PF')?>',
					type: 'POST',
					dataType: 'HTML',
					async: true,
					cache : false,
					data: $(this).serialize(),
					beforeSend : function(){
						$('#btn_simpan').attr('disabled',true).text('Loading...');
					},
					complete: function(xhr, textStatus) {
						$('#btn_simpan').attr('disabled',false).text('Update');
						$('#Modal_PF').modal('hide');
						location.reload();
					},
					success: function(data, textStatus, xhr) {
					// alert(data);
						
					},
					error: function(xhr, textStatus, errorThrown) {
						$.toast({
							text: 'terjadi kesalahan',
							position: 'bottom-right',
							stack: false,
							hideAfter: 1000,
							icon: 'error',

						});
						return false;
					}
				});
				
			});




		});





	</script>