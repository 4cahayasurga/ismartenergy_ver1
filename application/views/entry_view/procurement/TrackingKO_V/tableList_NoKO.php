<div class="row">

	<div class="col-sm-12">

		<div class="table-responsive">
			<table width="100%" class="table table-bordered table-striped table-hover" id="tableKO">
				<thead>
					<tr>
						<!-- <th></th> -->
						<th>No KO</th>
						<th>Tgl KO</th>
						<th>Vendor</th>
						<th>Total KO (Rp)</th>
						<th width="10%"></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$Txt_NoKO = "";
					$Txt_TglKO = "";
					$Txt_AlamatKirim = "";
					$Txt_NamaKirim = "";
					$Txt_TeleponKirim = "";
					$NoUrut = 0;
					foreach ($List_NoKO as $data): 
						$NoKO = ShowKodeAman($data->NoKO);
						$TglKO = ShowKodeAman($data->TglKO);
						$VendorNm = ShowKodeAman($data->VendorNm);
						$TotalKO =  KodeAmanKhususNumeric($data->TotalKO, ENT_XML1, 'UTF-8');
						$NoUrut++;

						?>
						<tr>
							<!-- <td><?=$NoKO?></td> -->
							<td><?=$NoKO?></td>
							<td><?=tgl_baru($TglKO)?></td>
							<td><?=$VendorNm?></td>
							<td><?=$TotalKO?></td>
							<td class="text-center">
								<button type="button" class="btn btn-primary" onClick="NoKO_Berganti('<?=$NoKO?>')" data-VendorNm="<?=$VendorNm?>" id="btnNoKO_Berganti<?=$NoKO?>" >PILIH</button>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>


	</div>
</div>

<script type="text/javascript">
	$(function(){
		$('#tableKO').DataTable({
			order: [[0, 'desc']]
		});
	})
</script>