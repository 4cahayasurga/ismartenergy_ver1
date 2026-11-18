<table width="100%" class="table table-bordered table-striped table-hover margin_atas_5" id="mytable_uraianPD">
	<thead>
		<tr style="color:White;background-color:#5D7B9D;font-weight:bold;text-align: center;" class="huruf13">
			<!-- <th width="50px" class="text-center" hidden><input type="checkbox" id="select_all"></th> -->
			<!-- <td></td>/ -->
			
			<th width="20px" class="text-center">No</th>
			<th width="120px" class="text-center">Kode RAP</th>
			<th class="text-center">Uraian</th>
			<th class="text-center" width="180px">Vol</th>
			<th class="text-center" width="100px">Sat</th>
			<th class="text-center" width="250px">Harga Satuan (Rp)</th>
			<th class="text-center" width="250px">Jumlah Harga (Rp)</th>
			<th class="text-center" width="180px" style="display: none;">New Record</th>
			<th class="text-center" width="100px"></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($ListPdDtl as $row): ?>
			<tr>
				<!-- <td class="text-center" hidden>
					<input type="checkbox" class="emp_checkbox" data-NoPD="<?=$row->NoPD?>" data-NoUrut="<?=$row->NoUrut?>">
				</td> -->
				<td class="text-center">
					<?=$row->NoUrut?>
					<input type="text" name="NoUrut_table[]" value="<?=$row->NoUrut?>" class="NoUrut_Class" style="display: none;">
				</td>
				<td>
					<?=$row->KdRAP?>
					<input type="text" name="KdRAP_table[]" value="<?=$row->KdRAP?>" style="display: none;">

				</td>
				<td>
					<?=$row->PjUraian?>
					<textarea name="PjUraian_table[]" class="PjUraian_Class" rows="4" style="display: none;"><?=$row->PjUraian?></textarea>
				</td>
				<td class="text-right">
					<?=UbahDecimal_Nol_Tiga($row->PjVol)?>
					<input type="text" name="PjVol_table[]" class="PjVol_tableClass" value="<?=UbahDecimal_Nol_Tiga($row->PjVol)?>" style="display: none;">
				</td>
				<td>
					<?=$row->Uom?>
					<input type="text" name="Uom_table[]" value="<?=$row->Uom?>" style="display: none;">
				</td>
				<td class="text-right">
					<?=number_format($row->PjHrgSatuan)?>
					<input type="text" name="PjHrgSatuan_table[]" class="PjHrgSatuan_tableClass" value="<?=number_format($row->PjHrgSatuan)?>" style="display: none;">

				</td>
				<td class="text-right">
					<?=number_format($row->JumlahHarga)?>
					<input type="text" name="JumlahHarga_table[]" value="<?=number_format($row->JumlahHarga)?>" style="display: none;">

				</td>
				<td class="text-right" style="display: none;">
					<?=$row->NewRecord?>
					<input type="text" name="NewRecord_table[]" value="<?=$row->NewRecord?>" style="display: none;">

				</td>
				<td class="text-center">
					<button type="button" class="btn btn-link btn-md" onclick="btnSelect(<?=$row->NoUrut?>)" 
						data-KdRAP="<?=$row->KdRAP?>" 
						data-Uraian="<?=$row->Uraian?>"
						data-Vol="<?=UbahDecimal_Nol_Tiga($row->Vol)?>"
						data-Uom="<?=$row->Uom?>"
						data-HrgSatuan="<?=number_format($row->HrgSatuan)?>"
						data-NoPJ="<?=$row->NoPJ?>"
						data-PjUraian="<?=$row->PjUraian?>"
						data-PjVol="<?=UbahDecimal_Nol_Tiga($row->PjVol)?>" 
						data-PjHrgSatuan="<?=number_format($row->PjHrgSatuan)?>"
						id="btnSelect_<?=$row->NoUrut?>"
						>SELECT</button>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
		<tfoot>
			<tr>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th class="text-center">Total Pertanggungjawaban</th>
				<th class="text-right TotalPertanggungJawaban_Class" style="text-align:right;"><?=$sumTotalPJ?></th>
				<th></th>
			</tr>
		</tfoot>
	</table>

	<script type="text/javascript">
		// $(document).ready(function(){
		// 	$('#mytable_uraianPD').DataTable({
		// 		// ordering : false,
		// 		"order": [[ 0, 'asc' ]]
		// 	});
		// });
	</script>
