<style type="text/css">
	
	.atur{
		background-color:#5D7B9D;
		text-align: center;
		font-size: 14px !important;
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
		color:white;
	}

	#tableSJ thead tr th{
		background-color:#5D7B9D;
		text-align: center;
		font-size: 14px !important;
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
		color:white;
	}

	#tableSJ tbody tr td{
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
		font-size: 13px !important;
	}

</style>



<div class="row" style="margin-top:10px !important;">
	<div class="col-md-10">
		<div class="table-responsive">

			<table width="100%" border="0" style="margin-bottom:10px !important;">
				<tr>
					<td width="160px">Total Barang Masuk (Rp)</td>
					<td width="10px" class="text-center">:</td>
					<td width="200px">
						
						<input type="text" class="TotalBarang form-control" name="TotalBarang" value="<?=number_format($sumTotal)?>" style="text-align:right;" readonly>
					</td>
					<td width="20px"></td>
					<td width="120px">Total Centang (Rp)</td>
					<td width="10px" class="text-center">:</td>
					<td width="200px">
						<input type="text" class="TotalCentang form-control" name="TotalCentang" value="<?=number_format($sumTotal_Centang)?>" style="text-align:right;" readonly>
					</td>
					<td></td>
				</tr>
			</table>

			<table width="100%" border="1" id="tableSJ"  class="table table-bordered table-striped table-hover ">
				<thead>
					<tr>
						<th width="70px" class="text-center"><input type="checkbox" id="select_all"></th>
						<th width="100px">Kode RAP</th>
						<th>Uraian</th>
						<th width="100px">Uom</th>
						<th width="150px">Vol Masuk</th>
						<th width="150px">Hrg Satuan (Rp)</th>
						<th width="150px">Vol Invoice</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($List_SJ as $row): ?>
						<tr>
							<td class="text-center"><input type="checkbox" class="emp_checkbox" name="emp_checkbox[<?=$row->LedgerNo?>]" value="<?=$row->LedgerNo?>" <?=($row->IsChecked==1) ? 'checked' : ''?> data-LedgerNo="<?=$row->LedgerNo?>"  <?=($Aksi=='UPD' && $status_invoice=='disabled') ? 'disabled="disabled"' : '' ?> >
							</td>
							<td><?=ShowKodeAman(($row->KdRAP))?></td>
							<td><?=ShowKodeAman($row->Uraian)?></td>
							<td class="text-center"><?=htmlspecialchars($row->Uom)?></td>
							<td class="text-right VolBrgMasukClass" id="VolBrgMasuk_Text_ID_<?=$row->LedgerNo?>"><?=UbahDecimal_Nol_Tiga($row->VolBrgMasuk)?></td>
							<td class="text-right HrgSatuanClass" id="HrgSatuan_Text_ID_<?=$row->LedgerNo?>"><?=number_format($row->HrgSatuan)?></td>
							<td style="display:none;">

								<input type="text" name="NoUrut_Multiple[<?=$row->LedgerNo?>]" value="<?=$row->NoUrut?>">
								<input type="text" name="KdRAP_Multiple[<?=$row->LedgerNo?>]" value="<?=ShowKodeAman($row->KdRAP)?>">
								<input type="text" name="Uraian_Multiple[<?=$row->LedgerNo?>]" value="<?=ShowKodeAman($row->Uraian)?>">
								<input type="text" name="Uom_Multiple[<?=$row->LedgerNo?>]" value="<?=$row->Uom?>">
								<input type="text" name="VolBrgMasuk_Multiple[<?=$row->LedgerNo?>]" id="VolBrgMasuk_ID_<?=$row->LedgerNo?>" value="<?=$row->VolBrgMasuk?>">
								<input type="text" name="HrgSatuan_Multiple[<?=$row->LedgerNo?>]"  value="<?=$row->HrgSatuan?>">

							</td>
							<td >
								<div class="form-group" id="peringatan_ID_<?=$row->LedgerNo ?>">
									
									<input type="text" name="VolInput[<?=$row->LedgerNo?>]" class="VolInputClass form-control" id="VolInput_ID_<?=$row->LedgerNo?>" style="width: 100%;text-align: right;" value="<?=UbahDecimal_Nol_Tiga($row->VolBrgMasuk)?>" readonly >
									
								</div>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>