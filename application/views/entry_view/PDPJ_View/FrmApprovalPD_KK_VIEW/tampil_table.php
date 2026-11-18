
<div class="box box-primary">
	<div class="box-header with-border">

	</div>
	<div class="box-body">
		<table width="100%" class="table table-bordered table-striped table-hover" id="mytable">
			<thead>
				<tr style="color:White;background-color:#5D7B9D;font-weight:bold;">
					<!-- <th>NoPD</th> -->
					<th>No PD</th>
					<th>No. Ref Lapangan</th>
					<th width="80px">Tgl PD</th>
					<th>Alokasi</th>
					<th>Tipe Form</th>
					<th>No KO</th>
					<th>Deskripsi</th>
					<th class="text-right">Total PD (Rp)</th>
					<th>Approved By (Cost Control)</th>
					<th>Approved On</th>
					<th width="45px"></th>
					<th width="45px"></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$NoUrut = 0;
				foreach ($ListPD as $row): 
					$NoUrut++;
					$ApprovedByKK = ($row->ApprovedByKK=='' || $row->ApprovedByKK==NULL) ? 'APPROVE' : 'UNAPPROVE';
					$flagAksi = ($row->ApprovedByKK=='' || $row->ApprovedByKK==NULL) ? 'UPD' : 'SEE_PD';
					$TipePD = NULL;
					if (substr($row->NoPD,0,5)=='PDKSO' || substr($row->NoPD,0,5)=='PDRKD' ) {
						$TipePD = 'RKD';
					}else if(substr($row->NoPD,0,5)=='PDMIX'){
						$TipePD = 'MIX';
					}else{
						$TipePD = '';
					}

					$UrlPD = "";
					if ($row->NoRef=='' || $row->NoRef==NULL ) {
						$UrlPD = 'EntryPDKeu_C';
					}else{
						$UrlPD = 'EntryPDLap_C';
					}

					?>
					<tr>
						<!-- <td><?=$row->NoPD?></td> -->
						<td><?=$row->NoPD?></td>
						<td><?=$row->NoRef?></td>
						<td><?=tgl_baru($row->TglPD)?></td>
						<td><?=$row->Alokasi?></td>
						<td><?=$row->TipeForm?></td>
						<td><?=$row->NoKO?></td>
						<td><?=$row->Deskripsi?></td>
						<td class="text-right"><?=number_format($row->TotalPD)?></td>
						<td><?=$row->ApprovedByKK?></td>
						<td><?=tgl_baru_dengan_waktu($row->TimeApprovedKK)?></td>
						<td class="text-center">
							<a href="<?=site_url('Entry/PDPJ/'.$UrlPD.'/UpdateFormPD/'.$flagAksi.'/'.$JobNo.'/'.$Alokasi.'/'.$row->NoPD.'/'.$TipePD)?>" class="btn btn-md  btnUpdate text-bold text-blue" >SELECT</a>	
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-md btnApprove text-bold text-blue" onclick="btnApprove(<?=$NoUrut?>)" id="btnApproveID<?=$NoUrut?>" data-NoPD="<?=$row->NoPD?>" data-Deskripsi="<?=$row->Deskripsi?>" data-TxtApprove="<?=ucfirst($ApprovedByKK)?>" ><?=$ApprovedByKK?></button>
						</td class="text-center">

					</tr>
				<?php endforeach ?>
			</tbody>
			<tfoot>
				<tr style="color:White;background-color:#5D7B9D;font-weight:bold;">
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="text-right text-bold" style="font-size:14px;width: 200px;">sum = <?=$Ttl?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>



<script type="text/javascript">
	$('#mytable').DataTable({
		order: [[0, 'desc']]
	});

	
</script>