
<style type="text/css">
	#table_PdDtl.dataTable tbody tr:hover {
		background-color: #bdc3c7;
		color: black;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size: 18px !important;
		font-weight: bold;

	}

	#table_PdDtl.dataTable tbody tr:hover > .sorting_1 {
		background-color: #3498db
		color: black;
		/*font-size: 18px !important;*/
		/*font-weight: bold;*/
	}
	.atur_ukuran_dtl {
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}
</style>
<div class="table-responsive">


	<table class="table table-bordered table-striped table-hover table-condesed atur_ukuran_dtl" id="table_PdDtl" >
		<thead>
			<tr class="atur_ukuran_dtl bg-gray">
				<th style="text-align:center"><input type="checkbox" class="atur_ukuran_dtl" id="check-all" <?=$khusus_inputan?>></th>
				<th style="text-align:center;width: 100px;" class="atur_ukuran_dtl">#</th>
				<th style="text-align:center;" class="atur_ukuran_dtl">No.</th>
				<th style="text-align:center;width: 110px;" class="atur_ukuran_dtl">Kode RAP</th>
				<th style="text-align:center;width: 50%;" class="atur_ukuran_dtl" >Uraian</th>
				<th style="text-align:center;" class="atur_ukuran_dtl">Vol</th>
				<th style="text-align:center;" class="atur_ukuran_dtl" >Sat</th>
				<th style="text-align:center;width:11%;" class="atur_ukuran_dtl" >Harga Satuan (Rp)</th>
				<th style="text-align:center;width:11%;" class="atur_ukuran_dtl" >Jumlah Harga (Rp)</th>
			</tr>
		</thead>
		<tbody>

			<?php
			if ($query_dapat_pddtl->num_rows() == 0) {
				echo '<tr><td colspan="8" class="atur_ukuran_dtl">Data tidak ditemukan</td></tr>';
				exit;
			}

			?>

			<?php 
			$no =1;
			$SubTotal=0;
			$NoUrut=0;


			foreach ($query_dapat_pddtl->result_array() as $data_pddtl) :
				$SubTotal += $data_pddtl['Total'];
				$NoUrut = $data_pddtl['NoUrut'];
				?>
				<tr >
					<td style="text-align:center;" class="atur_ukuran_dtl">

						<input type="checkbox" class="check-item atur_ukuran_dtl" data-NoUrut="<?=$data_pddtl['NoUrut']?>" <?=$khusus_inputan?>  >

					</td>
					<td style="text-align:center;" class="atur_ukuran_dtl">

						<a href="javascript:void(0)" class="btn btn-sm <?=($khusus_inputan == '') ? 'btn-warning' : 'btn-primary' ?> atur_ukuran_dtl" title="Edit Uraian" onclick="edit_uraian(<?=$NoUrut?>)"><i  class="fa <?=($khusus_inputan == '') ? 'fa-edit' : 'fa-eye' ?>"></i></button>
						</td>
						<td style="text-align:center;" class="atur_ukuran_dtl"><?=$NoUrut?></td>
						<td style="text-align:center;" class="atur_ukuran_dtl" ><?=$data_pddtl['KdRAP']?></td>
						<td class="atur_ukuran_dtl" ><?=$data_pddtl['Uraian']?></td>
						<td style="text-align:right;" class="atur_ukuran_dtl" ><?=uang_koma_genap_decimal($data_pddtl['Vol'])?></td>
						<td class="atur_ukuran_dtl" ><?=$data_pddtl['Uom']?></td>
						<td style="text-align:right;" class="atur_ukuran_dtl" ><?=uang_koma_genap_decimal($data_pddtl['HrgSatuan'])?></td>
						<td style="text-align:right;" class="atur_ukuran_dtl" ><?=uang_koma_genap_decimal($data_pddtl['Total'])?></td>
					</tr>

				<?php endforeach; ?>

			</tbody>

		</table>
		<br><br>

		<table width="100%" border="0">
			<tr>
				<th style="text-align:right;font-size: 10px !important;">Sub Total </th>
				<th style="text-align:right;width: 2%;"></th>
				<th style="text-align:right;width: 20%;">
					<!-- <input type="text" name="SubTotal" id="SubTotal" value="<?=new_decimal($SubTotal)?>" class="form-control atur_ukuran_dtl" style="text-align:right;font-size: 14px !important;" readonly   > -->

					<!-- <input type="text" name="SubTotal" id="SubTotal" value="<?=$SubTotal?>" class="form-control atur_ukuran_dtl" style="text-align:right;font-size: 10px !important;" readonly   > -->

					<input type="text"id="SubTotal_hanya_utk_view" value="<?=number_format($SubTotal, 2, '.', ',')?>" class="form-control input-sm ukuran_pada_table_kedua" style="text-align:right;" readonly   >

					<input type="hidden" id="SubTotal" value="<?=$SubTotal?>" class="form-control input-sm ukuran_pada_table_kedua" style="text-align:right;" readonly   >

				</th>
			</tr>
		</table>


		<table width="100%" border="0" style="margin-top:10px !important;" >
			<tr>
				<th style="text-align:right;font-size: 10px !important;">Saldo Sebelumnya dari No </th>
				<th style="text-align:right;width: 20%;padding-left:10px;padding-right:10px;">
					<select id="pilih_NoPJSebelumnya" class="form-control input-sm" style="font-size:10px !important;" <?=$khusus_inputan?>>
						<option value="">-- Pilih --</option>

						<?php
						if ($NoPJSebelumnya !='' OR $NoPJSebelumnya != NULL) { ?>
							<option value="<?=$NoPJSebelumnya?>" selected><?=$NoPJSebelumnya?></option>
						<?php } ?>
						<?php
						foreach($dapat_NoPJSebelumnya as $data_NoPJSebelumnya) : ?>
							<option value="<?=$data_NoPJSebelumnya['NoPJ']?>" <?=($data_NoPJSebelumnya['NoPJ'] == $NoPJSebelumnya) ? 'selected' : ''   ?> ><?=$data_NoPJSebelumnya['NoPJ']?></option>
						<?php endforeach; ?>
					</select>
				</th>
				<th style="text-align:right;width: 20%;">
					<!-- <input type="text" id="Saldo" name="Saldo" class="form-control" style="text-align:right;font-size: 10px !important;" value="<?=$dataUraian['Saldo']?>" readonly   > -->

					<input type="text" id="Saldo_view_saja" class="form-control input-sm ukuran_pada_table_kedua" style="text-align:right;" value="<?=number_format($dataUraian['Saldo'], 2, '.', ',')?>" disabled   >
					<input type="hidden" id="Saldo" name="Saldo" class="form-control input-sm ukuran_pada_table_kedua" style="text-align:right;" value="<?=$dataUraian['Saldo']?>" readonly   >

					<button id="loading_pj_sebelumnya" class="btn btn-default btn-md disabled" style="display:none;"   ><i class="fa fa-spinner"></i></button>
				</th>
			</tr>
		</table>

		<?php

		$TotalPermintaan = $SubTotal - $dataUraian['Saldo'];
		?>

		<table width="100%" border="0" style="margin-top:10px !important;" >
			<tr>
				<th style="text-align:right;font-size: 10px !important;">Total Permintaan </th>
				<th style="text-align:right;width: 2%;"></th>
				<th style="text-align:right;width: 20%;">
					<!-- <input type="text" id="TotalPermintaan" name="TotalPermintaan"  class="form-control" style="text-align:right;font-size: 10px !important;" value="<?=$TotalPermintaan?>" readonly> -->

					<input type="text" id="TotalPermintaan" name="TotalPermintaan"  class="form-control input-sm ukuran_pada_table_kedua" style="text-align:right;" value="<?=number_format($TotalPermintaan, 2, '.', ',')?>" readonly>

				</th>
			</tr>
		</table>

		<br><br>
	</div>


	<script>
		$(document).ready(function(){
			$('#table_PdDtl').DataTable({
				pageLength: 50,
				lengthMenu: [50,100,200,300,500,1000],
				fixedColumns:   true,
				pagging : true,
				scrollY:        "300px",
				scrollX:        true,
				scrollCollapse: true,
			});
		});
		
		$(function(){

			// cek_saldo_NoPJSebelumnya();
			dapatkan_Saldo_NoPJSebelumnya();
			$('#check-all').click(function(){
				if($(this).is(":checked")){
					$(".check-item").prop("checked", true);
				}else{
					$(".check-item").prop("checked", false);
				}
			});


			$('#pilih_NoPJSebelumnya').change(function(){
				// cek_saldo_NoPJSebelumnya();
				dapatkan_Saldo_NoPJSebelumnya();
			});

		});

		function dapatkan_Saldo_NoPJSebelumnya(){
			var NoPJSebelumnya = $('#pilih_NoPJSebelumnya').val();
			var SubTotal = $('#SubTotal').val();
			var TotalPermintaan = $('#TotalPermintaan').val();

			var JobNo = $('#JobNo').val();
			var TipeForm = $('#TipeForm').val();

			// alert(NoPJSebelumnya+' - '+SubTotal+' - '+TotalPermintaan);

			jQuery.ajax({
				url : '<?=site_url("Permintaan_dana_keuangan/dapatkan_Saldo_NoPJSebelumnya")?>',
				type: 'POST',
				dataType: 'json',
				data: {NoPJSebelumnya: NoPJSebelumnya, SubTotal : SubTotal, TotalPermintaan : TotalPermintaan, JobNo : JobNo, TipeForm : TipeForm},
				beforeSend : function(){
					$('#loading_pj_sebelumnya').show();
					$('#Saldo_view_saja').hide();
					$('#Saldo').hide();
				},
				complete: function(xhr, textStatus) {
					$('#loading_pj_sebelumnya').hide();
					$('#Saldo_view_saja').show();
					$('#Saldo').show();
				},
				success: function(data, textStatus, xhr) {
					$('#Saldo').val(data.Saldo);
					$('#Saldo_view_saja').val(data.Saldo_view_saja);
					$('#TotalPermintaan').val(data.TotalPermintaan);
					$('#SubTotal').val(data.SubTotal);
				},
				error: function(xhr, textStatus, errorThrown) {
					alert('error');
				}
			});

		}

		function cek_saldo_NoPJSebelumnya(){
			var NoPJSebelumnya = $('#pilih_NoPJSebelumnya').val();
			var SubTotal = $('#SubTotal').val();
			jQuery.ajax({
				url : '<?=site_url("Permintaan_dana_keuangan/dapat_saldo_NoPJSebelumnya_terbaru")?>',
				type: 'POST',
				dataType: 'JSON',
				data: {'NoPJSebelumnya' : NoPJSebelumnya,'SubTotal' : SubTotal},
				cache : false,
				success: function(data, textStatus, xhr) {
					$('#Saldo').val(data.Saldo);
					$('#TotalPermintaan').val(data.TotalPermintaan);
					$('#SubTotal').val(data.SubTotal);
				}
			});
		}

			// function cek_saldo_NoPJSebelumnya(){
			// 	var NoPJSebelumnya = $('#pilih_NoPJSebelumnya').val();
			// 	jQuery.post('<?=site_url("Permintaan_dana_keuangan/dapat_saldo_NoPJSebelumnya")?>', {'NoPJSebelumnya' : NoPJSebelumnya}, function(data, textStatus, xhr) {
			// 		$('#Saldo').val(data);
			// 		Hitung_TotalPermintaan();
			// 	});
			// }

			function Hitung_TotalPermintaan(){
				var SubTotal = $('#SubTotal').val();
				var Saldo = $('#Saldo').val();
				var Hitung = 0;

				SubTotal = HapusKoma(SubTotal);
				Saldo = HapusKoma(Saldo);

				var Hitung = parseFloat(SubTotal) - parseFloat(Saldo);
				Hitung = addCommas(Hitung.toFixed(2));
				$('#TotalPermintaan').val(Hitung);

			}




		</script>

