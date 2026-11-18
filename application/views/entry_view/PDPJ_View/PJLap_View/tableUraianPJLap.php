<div class="row">
	<div class="col-sm-10" style="margin-bottom:20px;">
		<table border="0" width="100%" class="huruf15" >
			<tr>
				<td width="60%"></td>
				<td width="20%" class="text-center text-bold ">Dana yang tersedia dilapangan</td>
				<td width="300px">
					<input type="text" class="form-control input-md text-right" value="<?=number_format($sumTotalPD)?>" disabled >
				</td>
				<td></td>
			</tr>
		</table>

		<table border="0" width="100%" class="huruf15" style="margin-top:5px;">
			<tr>
				<td width="60%"></td>
				<td width="20%" class="text-center text-bold" style="font-size: 16px !important;">Saldo</td>
				<td width="300px">
					<input type="text" class="form-control input-md text-right" value="<?=number_format($Saldo)?>" disabled >
				</td>
				<td></td>
			</tr>
		</table>
	</div>
</div>

<div class="row">
	<div class="col-sm-10">
		<div class="table-responsive">
			
			<table class="table table-bordered table-striped table-hover" id="mytable_uraian">
				<thead class="bg-gray" >
					<tr>
						<th class="text-center" width="30px">No.</th>
						<th class="text-center" width="80px">Kode RAP</th>
						<th class="text-center">Uraian</th>
						<th class="text-center" width="80px">Vol</th>
						<th class="text-center" width="10px">Sat</th>
						<th class="text-center" width="170px">Harga Satuan (Rp)</th>
						<th class="text-center" width="150px">Jumlah Harga (Rp)</th>
						<th class="text-center" width="100px"></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$sumTotalPJ=0;

					foreach ($ListPdDtl as $row):
						$NoUrut = ShowKodeAman($row['NoUrut']);
						$sumTotalPJ+=($row['PjVol'] * $row['PjHrgSatuan']);
						?>
						<tr>
							<td class="text-center TxtNoUrut"><?=$row['NoUrut']?></td>
							<td><?=$row['KdRAP']?></td>
							<td class="TxtUraian"><?=$row['PjUraian']?></td>
							<td class="text-right"><?=UbahDecimal_Nol_Tiga($row['PjVol'])?></td>
							<td class="text-center"><?=$row['Uom']?></td>
							<td class="text-right"><?=UbahDecimal_Nol_Dua($row['PjHrgSatuan'])?></td>
							<td class="text-right"><?=number_format($row['PjVol'] * $row['PjHrgSatuan'])?></td>
							<td class="text-center" >
								<button type="button" class="btn btn-warning" 
								onclick="btnSelectUraian(<?=$NoUrut?>)" id="btnSelectUraianID<?=$NoUrut?>" 
								data-NoPD="<?=$row['NoPD']?>"  data-KdRAP_Lengkap="<?=$row['KdRAP_Lengkap']?>" 
								data-Uraian="<?=$row['Uraian']?>" data-Vol="<?=UbahDecimal_Nol_Tiga($row['Vol'])?>"
								data-Uom="<?=$row['Uom']?>" data-HrgSatuan="<?=number_format($row['HrgSatuan'])?>"

								data-PjUraian="<?=($row['PjUraian']==NULL || $row['PjUraian']=='') ? $row['Uraian'] : $row['PjUraian'] ?>"
								data-PjVol="<?=($row['PjVol']==NULL || $row['PjVol']=='') ? UbahDecimal_Nol_Dua($row['Vol']) : UbahDecimal_Nol_Dua($row['PjVol']) ?>"
								data-PjHrgSatuan="<?=($row['PjHrgSatuan']==NULL || $row['PjHrgSatuan']=='') ? number_format($row['HrgSatuan']) : number_format($row['PjHrgSatuan']) ?>"
								>SELECT</button>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
				<tfoot>
					<tr style="background-color:#d1d8e0;">
						<td colspan="5"></td>
						<th class="text-center huruf15">Total Pertanggungjawaban</th>
						<th class="text-right huruf15">
							sum = <?=number_format($sumTotalPJ)?>
							<input type="hidden" name="sumTotalPJ" id="sumTotalPJ" value="<?=number_format($sumTotalPJ)?>">
						</th>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-10">
		<table border="0" width="100%" class="huruf15" style="margin-top:20px;">
			<tr>
				<td width="60%"></td>
				<td width="20%" class="text-center text-bold">Dana yang tersedia dilapangan</td>
				<td width="300px">
					<input type="text" class="form-control text-right" value="<?=number_format($sumTotalPD)?>" disabled >
				</td>
				<td></td>
			</tr>
		</table>

		<table border="0" width="100%" class="huruf15" style="margin-top:5px;">
			<tr>
				<td width="60%"></td>
				<td width="20%" class="text-center text-bold">Saldo</td>
				<td width="300px">
					<input type="text" id="Saldo" class="form-control text-right" value="<?=number_format($Saldo)?>" disabled >

				</td>
				<td></td>
			</tr>
		</table>
	</div>
</div>