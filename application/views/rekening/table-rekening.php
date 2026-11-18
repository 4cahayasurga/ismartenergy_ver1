<div class="table-responsive">

	<table id="mytable" class="table table-bordered table-striped text-center" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th>No.</th>
				<th>Tanggal</th>
				<th>Keterangan</th>
				<th>Debet (Rp)</th>
				<th>Kredit (Rp)</th>
				<th>Saldo (Rp)</th>
			</tr>
		</thead>

		<tbody>
			<?php 
			$no=1;
			foreach($rek as $row){ ?>
				<tr>
					<td><?= $no++ ?></td>
					<td><?= $row->tglbayar ?></td>
					<td style="text-align: left;"><?= $row->keterangan ?></td>
					<td><?= UbahGabunganUang($row->debet); ?></td>
					<td><?= UbahGabunganUang($row->kredit); ?></td>
					<td><?= UbahGabunganUang($row->saldo); ?></td>
				</tr>
			<?php }?>
			
		</tbody>
		<!-- <tfoot>
			<tr>
				<th colspan="3" style="text-align: left;">TOTAL</th>
				<th><?= UbahGabunganUang($sum_debet) ?></th>
				<th><?= UbahGabunganUang($sum_kredit) ?></th>
			</tr>
		</tfoot> -->
	</table>
</div>
<script>
	$(function() {
		$('#mytable').DataTable();
	})
</script>
