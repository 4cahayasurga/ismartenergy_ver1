<style type="text/css">
	#tblstok thead tr th{
		padding-top: 10px;
		padding-bottom: 10px;
		font-family: sans-serif;
		font-size: 12px !important;
	}

	#tblstok tbody tr td{
		padding-top: 10px;
		padding-bottom: 10px;
		font-family: sans-serif;
		font-size: 12px !important;
	}

</style>
<div class="col-sm-12">
	<div class="table-responsive">
		<table class="table table-bordered table-striped table-hover table-condensed font12" id="tblstok" >
			<thead>
				<tr class="bg-gray">
					<th class="text-center">No.</th>
					<th>No KO</th>
					<th>Tgl Masuk</th>
					<th>Kode RAP</th>
					<th>Uraian</th>
					<th>Satuan</th>
					<th class="text-right">Vol</th>
					<th class="text-center">Status</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$Nomor = 1;
				foreach($dataBarangMasuk as $row) : ?>

					<tr>
						<td class="text-center"><?=$Nomor++;?></td>
						<td><?=$row->NoKO?></td>
						<td><?=$row->NoKO?></td>
						<td><?=$row->NoKO?></td>
						<td><?=$row->Uraian?></td>
						<td><?=$row->Uom?></td>
						<td class="text-right"><?=VolKetik($row->VolBrgMasuk)?></td>
						<td class="text-center">Barang Masuk</td>
					</tr>


				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>