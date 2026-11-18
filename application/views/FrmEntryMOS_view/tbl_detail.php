<style type="text/css">
	#mytable{
		font-family: sans-serif;
		font-size: 11px;
		
	}
	#mytable tr th{
		padding-top: 10px;
		padding-bottom: 10px;
		border-top:1px solid;
		border-bottom:1px solid;
/*		background-color: #95a5a6;*/
/*		color : white;*/
	}
	#mytable tr td{
		padding-top: 5px;
		padding-bottom: 5px;
	}
	.border_red{
		border:2px solid red;
	}
</style>
<div class="table-responsive">
	<table class="table table-hover table-striped table-condensed" id="mytable">
		<thead>
			<tr class="bg-gray">
				<th class="text-center" width="3%">No</th>
				<th class="text-left" width="7%">Kode RAP</th>
				<th class="text-left">Uraian</th>
				<th class="text-center" width="3%">Sat</th>
				<th class="text-right" width="5%">Vol</th>
				<th class="text-right" width="10%">Vol Masuk</th>
				<th class="text-center" width="22%">Keterangan</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($dataKO as $row) : ?>
				<tr>
					<td class="text-center" ><?=$row->NoUrut?></td>
					<td><?=$row->KdRAP?></td>
					<td><?=$row->Uraian?></td>
					<td class="text-center"><?=$row->Uom?></td>
					<td class="text-right"><?=VolKetik($row->Vol)?></td>
					<td class="text-right">

						<input type="text" name="VolMasuk[]" class="form-control input-sm text-right VolMasuk KetikVol"autocomplete="off" style="width: 100px;">
						
					</td>
					<td>
						<textarea name="Keterangan[]" class="form-control input-sm" placeholder="Keterangan Lain" rows="2" style="width:100%;"></textarea>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

