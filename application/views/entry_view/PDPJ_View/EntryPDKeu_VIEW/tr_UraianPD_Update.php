<table width="100%" class="table table-bordered table-striped table-hover margin_atas_5" id="mytable_uraianPD">
	<thead>
		<tr style="color:White;background-color:#5D7B9D;font-weight:bold;text-align: center;" class="huruf13">
			<th width="50px" class="text-center"><input type="checkbox" id="select_all" <?=($tombol_dimatikan)?> ></th>
			<th width="20px">No</th>
			<th width="120px">Kode RAP</th>
			<th>Uraian</th>
			<th width="180px">Vol</th>
			<th width="100px">Sat</th>
			<th width="180px">Harga Satuan (Rp)</th>
			<th width="180px">Jumlah Harga (Rp)</th>
			<th width="100px"></th>
			<!-- <th width="100px"></th> -->
		</tr>
	</thead>
	<tbody>
		<?php 
		$NoUrut_Buatan = 1;
		foreach ($List_PdDtl as $row_PdDtl){ ?>
			<tr>
				<td class="text-center">
					<input type="checkbox" class="emp_checkbox" data-NoPD="<?=$row_PdDtl->NoPD?>" data-NoUrut="<?=$row_PdDtl->NoUrut?>" <?=($tombol_dimatikan)?>>
				</td>
				<td><?=$NoUrut_Buatan++?></td>

				<td><?=$row_PdDtl->KdRAP?></td>
				<td><?=$row_PdDtl->Uraian?></td>
				<td class="text-right VolClass"><?=VolDecimal_Nol_Tiga($row_PdDtl->Vol)?></td>
				<td class="text-center"><?=$row_PdDtl->Uom?></td>
				<td class="text-right HrgSatuanClass"><?=UbahDecimal_Nol_Dua($row_PdDtl->HrgSatuan)?></td>
				<td class="text-right"><?=number_format($row_PdDtl->JumlahHarga)?></td>
				<td class="text-center">
					<button type="button" class="btn btn-link btn-md text-bold" 
					id="btnUpdate_ID_<?=$row_PdDtl->NoUrut?>" 
					onClick="btn_EditUraian(<?=$row_PdDtl->NoUrut?>)"
					data-KdRAP="<?=$row_PdDtl->KdRAP?>" 
					data-Tipe="<?=$row_PdDtl->Tipe?>" 
					data-Uraian="<?=$row_PdDtl->Uraian?>" 
					data-Vol="<?=VolDecimal_Nol_Tiga($row_PdDtl->Vol)?>"
					data-Uom="<?=$row_PdDtl->Uom?>" 
					data-HrgSatuan="<?=UbahDecimal_Nol_Dua($row_PdDtl->HrgSatuan)?>"
					>SELECT</button>
				</td>

			</tr>
		<?php } ?>
	</tbody>
</table>


