<style type="text/css">
	#tblPD thead tr th{
		text-align: center;
		font-size: 11px !important;
		padding-top:10px;
		padding-bottom: 10px;
	}

	#tblPD tbody tr td{
		font-size: 10px !important;
		padding-top:10px !important; 
		padding-bottom: 10px !important;
	}

	table#tblPD.dataTable tbody tr:hover {
		background-color: #ffa;
		font-size:12px;
	}

	table#tblPD.dataTable tbody tr:hover > .sorting_1 {
		background-color: #ffa;
		font-size:12px;
	}



</style>
<div class="row">
	
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover table-bordered table-condense font11" id="tblPD"  >

						<thead>
							<tr class="bg-gray">
								
								<th class="font11">No PD</th>
								<th class="font11" width="50px">Tgl PD</th>
								<th class="font11">Tipe Form</th>
								<th class="font11" width="270px">Deskripsi</th>
								<th class="text-right font11">Total PD (Rp)</th>
								<th class="font11">No. PJ</th>
								<th class="font11" width="50px">Tgl PJ</th>
								<th class="text-right font11">Total PJ (Rp)</th>
								<th class="font11" width="100px">Approved By (Keuangan)</th>
								<th class="font11"width="70px">Approved On</th>
								<th class="text-center" width="250px"></th>
							</tr>
						</thead>

						<tbody>
							<?php
							$no = 1;
							$ApproveTxt = '';
							$WarnaTxt = '';
							$SubTotal_PJ = 0;
							foreach ($mydata as $row) { ?>

								<?php 

								$LedgerNo = $row->LedgerNo;
								$NoPD = $row->NoPD;
								$JobNo = $row->JobNo;
								$Alokasi = $row->Alokasi;


								if ($row->PjApprovedByAKText == 'kosong') {
									$ApproveTxt = 'Approve';
									$WarnaTxt = 'btn-primary';
								}else{
									$ApproveTxt = 'UnApprove';
									$WarnaTxt = 'btn-danger';
								}

								$SubTotal_PJ += $row->TotalPJ;

								?>

								<tr>
									<td class="font12"><?=$NoPD?></td>
									<td class="font12"><?=tgl_baru($row->TglPD)?></td>
									<td class="font12"><?=$row->TipeForm?></td>
									<td class="font12"><?=$row->Deskripsi?></td>
									<td class="text-right font12"><?=UangTerbaru($row->TotalPD)?></td>
									<td class="font12"><?=$row->NoPJ?></td>
									<td class="font12"><?=tgl_baru($row->TglPJ)?></td>
									<td class="text-right font12"><?=UangTerbaru($row->TotalPJ)?></td>
									<td class="font12"> <?=$row->PjApprovedByAK?></td>
									<td class="font12"><?=tgl_baru_tahun_pendek_dengan_waktu($row->PjTimeApprovedAK)?></td>
									<td class="text-center font12" >
										<button href="javascript:void(0)" class="btn btn-default btn-sm font10 " id="SelectBtn_<?=$row->LedgerNo?>" data-NoPD="<?=$row->NoPD?>" onclick="PilihPD(<?=$row->LedgerNo?>)" data-RejectBy="<?=$row->RejectBy?>"  >SELECT</button>

										<button href="javascript:void(0)" class="btn btn-sm font10 <?=$WarnaTxt?>" id="ApproveBtn_<?=$row->LedgerNo?>" data-NoPD="<?=$row->NoPD?>" data-ApproveTxt="<?=$ApproveTxt?>" onclick="ApprovePD(<?=$row->LedgerNo?>)" data-RejectBy="<?=$row->RejectBy?>"  ><?=strtoupper($ApproveTxt)?></button>

										<a href="javascript:void(0)" class="btn btn-warning btn-sm font10 btnPrint_<?=$LedgerNo?>"
											onclick="PrintPD('<?=$LedgerNo?>')"
											data-link="<?=site_url('MY_PD/PD_PDF_ALL/ExpPDF_PD/'.$LedgerNo.'/'.$JobNo.'/'.$Alokasi.'/'.$NoPD.'/0/0/KEU')?>"
											>PRINT <i class="fa fa-print"></i></a>
										</td>
									</tr>


								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="7" class="text-right font12">Total :</th>
									<th class="text-right font12"><?=UangTerbaru($SubTotal_PJ)?></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
							</tfoot>

						</table>
					</div>
				</div>
			</div>
		</div>

	</div>

	<!-- <script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script> -->



	<script type="text/javascript">
		$(function(){
			$('#tblPD').DataTable({
				pageLength: 1000,
				fixedColumns:   true,
				pagging : false,
				scrollY:        "500px",
				scrollX:        true,
				scrollCollapse: true,

			});



		});		


	</script>