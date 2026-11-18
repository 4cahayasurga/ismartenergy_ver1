
<div class="box box-primary">
	<div class="box-header with-border">

		<!-- 
		<a href="<?=site_url('Entry/PDPJ/EntryPDKeu_C/CekData/'.$JobNo.'/'.$Alokasi.'/')?>" class="btn btn-md btn-default text-bold" >TAMBAH PD <i class="fa fa-plus"></i></a>

		<a href="<?=site_url('Entry/PDPJ/EntryPDKeu_C/CekData/'.$JobNo.'/'.$Alokasi.'/AddKSO')?>" type="button" class="btn btn-md btn-default text-bold">TAMBAH PDRKD <i class="fa fa-plus"></i></a>

		<a href="<?=site_url('Entry/PDPJ/EntryPDKeu_C/CekData/'.$JobNo.'/'.$Alokasi.'/AddMIX')?>" type="button" class="btn btn-md btn-default text-bold">TAMBAH PDMIX <i class="fa fa-plus"></i></a> -->

	</div>
	<div class="box-body">
		<table width="100%" class="table table-bordered table-striped table-hover huruf13" id="mytable">
			<thead>
				
				<tr style="color:White;background-color:#5D7B9D;font-weight:bold;">
					<th>No.PD</th>
					<th width="80px">Tgl PD</th>
					<th width="80px">Tipe Form</th>
					<th>Deskripsi</th>
					<th width="120px">Total PD (Rp)</th>
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
						
						<th class="text-center">
							<a href="<?=site_url('Entry/PDPJ/EntryPjKeu/CekPD/'.$row->JobNo.'/'.$row->Alokasi.'/'.$row->NoPD)?>" class="btn btn-default text-bold">SELECT</a>
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
							<a href="javascript:void(0)" class="btn btn-warning font13 btnPrint_<?=$row->LedgerNo?>" onclick="PrintPD('<?=$row->LedgerNo?>')" data-link="<?=site_url('MY_PD/PD_PDF_ALL/ExpPDF_PD/'.$row->LedgerNo.'/'.$row->JobNo.'/'.$row->Alokasi.'/'.$row->NoPD.'/0/0/0/0/Keu')?>" data-NoPD="<?=$row->NoPD?>" >PRINT </a>
						</th>
					</tr>
				<?php endforeach; ?>
			</tbody>
			<!-- <tfoot>
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
					<td></td>
				</tr>
			</tfoot> -->
		</table>
	</div>
</div>



<script type="text/javascript">
	$('#mytable').DataTable({
		order: [[0, 'desc']]
		// columnDefs: [
		// 	{ targets: [0, 1], visible: true},
		// 	{ targets: '_all', visible: false }
		// 	]
	});

	// function btnAdd(){
	// 	var JobNo = $('#formPDKeuangan #JobNo option:selected').val();
	// 	var Alokasi = $('#formPDKeuangan #Alokasi option:selected').val();

	// 	if (JobNo=='' || Alokasi=='') {
	// 		alert('Belum pilih Job/Alokasi.');
	// 		return false;
	// 	}

	// 	$.ajax({
	// 		url: "<?=site_url('Entry/PDPJ/EntryPDKeu_C/')?>",
	// 		type: 'default GET (Other values: POST)',
	// 		dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
	// 		data: {param1: 'value1'},
	// 	})
	// 	.done(function() {
	// 		console.log("success");
	// 	})
	// 	.fail(function() {
	// 		console.log("error");
	// 	})
	// 	.always(function() {
	// 		console.log("complete");
	// 	});


	// 	if (Alokasi=='') {
	// 		return false;
	// 	}

	// 	alert(JobNo+' - '+Alokasi);
	// }

</script>