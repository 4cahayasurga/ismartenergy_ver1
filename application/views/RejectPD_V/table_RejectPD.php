<style type="text/css">	


	#mytable thead tr th{
		text-align: center;
		color:White;
		background-color:#5D7B9D;
		font-weight:bold;
		vertical-align: middle;
	}	

</style>
<div class="row">
	<div class="col-sm-12">
		<div class="table-responsive">
			<table width="100%" border="0" class="table table-striped table-hover table-bordered" id="mytable">
				<thead>
					<tr>
						<th >No. PD</th>
						<th >No.Ref<br>Lapangan</th>
						<th width="50px" >Tgl PD</th>
						<th >Alokasi</th>
						<th >Tipe Form</th>
						<th width="50px" >Periode <br>Dari</th>
						<th width="50px" >Periode <br>Sampai</th>
						<th>Deskripsi</th>
						<th >Total PD (Rp)</th>
						<th >PD Reject By</th>
						<th >PD Reject On</th>
						<th ></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$NoUrut = 1;
					foreach ($dataReject as $dataReject): 

						$NoUrut++;

						$RejectStatus = "";
						if ($dataReject->RejectBy!="" || $dataReject->RejectBy!=NULL) {
							$RejectStatus = $dataReject->NoPD." sudah di-reject oleh ".$dataReject->RejectBy;
						}

						$JobNo1 = "";
						$Alokasi1 = "";
						$NoPD = "";
						$TxtUrl = "";


						$JobNo1 = $dataReject->JobNo;
						$Alokasi1 = $dataReject->Alokasi;
						$NoPD = $dataReject->NoPD;

						if ($dataReject->NoRef=='' || $dataReject->NoRef==NULL) {
							$TxtUrl = site_url('Entry/PDPJ/FrmPDKeu/FrmSelectPD/'.$JobNo1.'/'.$Alokasi1.'/'.$NoPD);
						}else{
							$TxtUrl = site_url('Entry/PDPJ/FrmPDLap/FrmSelectPD/'.$JobNo1.'/'.$Alokasi1.'/'.$NoPD);
						}

						
					?>
						<tr>
							<td><?=$dataReject->NoPD?></td>
							<td><?=$dataReject->NoRef?></td>
							<td><?=tgl_baru($dataReject->TglPD)?></td>
							<td><?=$dataReject->Alokasi?></td>
							<td><?=$dataReject->TipeForm?></td>
							<td><?=tgl_baru($dataReject->PrdAwal)?></td>
							<td><?=tgl_baru($dataReject->PrdAkhir)?></td>
							<td><?=$dataReject->Deskripsi?></td>
							<td class="text-right"><?=number_format($dataReject->TotalPD)?></td>
							<td><?=$dataReject->RejectBy?></td>
							<td><?=tgl_baru_dengan_waktu($dataReject->TimeReject)?></td>
							<td  class="text-center">
								<a href="<?=$TxtUrl?>" class="btn btn-default btn-md text-blue" >SELECT</a>
							</td>
							<td  class="text-center">
								<button class="btn btn-default btn-md text-blue" id="btnRejectID<?=$NoUrut?>" onclick="btnReject(<?=$NoUrut?>)" data-NoPD="<?=$dataReject->NoPD?>" data-Deskripsi="<?=$dataReject->Deskripsi?>" data-RejectStatus="<?=$RejectStatus?>">REJECT</button>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="8">TOTAL</th>
						<th class="text-right"><?=number_format($SubTotal)?></th>
						<th colspan="4"></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#mytable').DataTable({
			ordering : false,
			pageLength : [50]
		});
	})
</script>