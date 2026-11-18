<style type="text/css">
	#tblPD thead tr th{
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
		text-align: center;
		font-size: 13px;
		padding-top:10px;
		padding-bottom: 10px;
	}

	#tblPD tbody tr td{
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
		font-size: 13px !important;
		padding-top:10px !important; 
		padding-bottom: 10px !important;
	}



</style>
<div class="row">
	
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover table-bordered table-condensed" id="tblPD" style="" >
						<thead >
							<tr style="color:White;background-color:#5D7B9D;font-weight:bold;">
								<th>No</th>
								<th>No.PD</th>
								<th>Tgl PD</th>
								<th>Tipe Form</th>
								<th>Deskripsi</th>
								<th>Total PD (Rp)</th>
								<th>No.PJ</th>
								<th>Tgl PJ</th>
								<th>Total PJ (Rp)</th>
								<th>Approved By <br>(Lapangan)</th>
								<th>Approved On</th>
								<th width="70px"></th>
								<th width="70px"></th>
								<th width="70px"></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$NoUrut = 0;
							foreach ($mydata as $row): 
								$NoUrut++;

								$PjApprovedByAK = ($row->PjApprovedByAK=='' || $row->PjApprovedByAK==NULL) ? 'APPROVE' : 'UNAPPROVE';

								?>
								<tr>
									<td><?=$NoUrut?></td>
									<td><?=$row->NoPD?></td>
									<td><?=tgl_baru(($row->TglPD=='' || $row->TglPD==NULL || $row->TglPD=='1900-01-01') ? NULL : $row->TglPD  )?></td>
									<td><?=$row->TipeForm?></td>
									<td><?=$row->Deskripsi?></td>
									<td class="text-right"><?=number_format($row->TotalPD)?></td>
									<td><?=$row->NoPJ?></td>
									<td><?=tgl_baru(($row->TglPJ=='' || $row->TglPJ==NULL || $row->TglPJ=='1900-01-01') ? NULL : $row->TglPJ  )?></td>
									<td class="text-right"><?=number_format($row->TotalPJ)?></td>
									<td><?=$row->PjApprovedByAK?></td>
									<td><?=tgl_baru_dengan_waktu(($row->PjTimeApprovedAK=='' || $row->PjTimeApprovedAK==NULL || $row->PjTimeApprovedAK=='1900-01-01 00:00:00') ? NULL : $row->PjTimeApprovedAK  )?></td>
									<!-- <td><?=$row->PjTimeApprovedAK?></td> -->
									<th class="text-center">
										<a href="<?=site_url('FrmPJLap/CekPD/'.$row->JobNo.'/'.$row->Alokasi.'/'.$row->NoPD)?>" class="btn btn-default text-bold">SELECT</a>
									</th>
									<th class="text-center">
										
										<button type="button" class="btn btn-md btnApprove text-bold text-blue btn-block" 
										onclick="btnApprove(<?=$NoUrut?>)" 
										id="btnApproveID<?=$NoUrut?>" 
										data-NoPD="<?=$row->NoPD?>" 
										data-NoPJ="<?=$row->NoPJ?>" 
										data-Deskripsi="<?=$row->Deskripsi?>" 
										data-TxtApprove="<?=ucfirst($PjApprovedByAK)?>" ><?=$PjApprovedByAK?></button>

									</th>
									<th class="text-center">
										<a href="javascript:void(0)" class="btn btn-warning font13 btnPrint_<?=$row->LedgerNo?>" onclick="PrintPD('<?=$row->LedgerNo?>')" data-link="<?=site_url('MY_PD/PD_PDF_ALL/ExpPDF_PD/'.$row->LedgerNo.'/'.$row->JobNo.'/'.$row->Alokasi.'/'.$row->NoPD.'/0/0/0/0')?>">PRINT </a>
									</th>
								</tr>
							<?php endforeach; ?>
						</tbody>


					</table>
				</div>
			</div>
		</div>
	</div>

</div>




<script type="text/javascript">
	$(function(){
		$('#tblPD').DataTable({
			"pageLength": 50,
			// "fixedHeader": true,
		});



	});		


</script>