<div class="table-responsive">
	<table class="table table-bordered table-striped table-hover table-condensed" id="mytbl_ko">
		<thead>
			<tr>
				<th class="huruf_10">No KO</th>
				<th class="huruf_10">Tgl KO</th>
				<th class="huruf_10">Vendor</th>
				<th class="huruf_10">Total KO (Rp)</th>
			</tr>
		</thead>
		<tbody>
			
			<?php foreach ($list_KO as $list_KO): ?>
				<tr>
					<td><?=$list_KO->NoKO?></td>
					<td><?=$list_KO->TglKO?></td>
					<td><?=$list_KO->NoKO?></td>
					<td><?=$list_KO->NoKO?></td>
					<td><?=$list_KO->NoKO?></td>
				</tr>
			<?php endforeach ?>

		</tbody>
	</table>
</div>

<!-- <script type="text/javascript">
	$(document).ready(function(){
		$('#mytbl_ko').DataTable();
	})
</script> -->