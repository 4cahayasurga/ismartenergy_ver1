
<div class="box box-primary">
	<div class="box-header with-border">

		<?php
		$AlamatUrl = $this->uri->segment(3);

		?>

		<a href="<?=site_url('Entry/PDPJ/EntryPDKeu_C/CekData/'.$JobNo.'/'.$Alokasi.'/')?>" class="btn btn-md btn-default text-bold" >TAMBAH PD <i class="fa fa-plus"></i></a>

		<a href="<?=site_url('Entry/PDPJ/EntryPDKeu_C/CekData/'.$JobNo.'/'.$Alokasi.'/AddKSO')?>" type="button" class="btn btn-md btn-default text-bold">TAMBAH PDRKD <i class="fa fa-plus"></i></a>

		<a href="<?=site_url('Entry/PDPJ/EntryPDKeu_C/CekData/'.$JobNo.'/'.$Alokasi.'/AddMIX')?>" type="button" class="btn btn-md btn-default text-bold">TAMBAH PDMIX <i class="fa fa-plus"></i></a>

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
					<th>Approved By (Keuangan)</th>
					<th>Approved On</th>
					<th width="45px"></th>
					<th width="45px"></th>
					<th width="45px"></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$NoUrut = 0;
				foreach ($ListPD as $row): 
					$NoUrut++;
					$ApprovedByAK = ($row->ApprovedByAK=='' || $row->ApprovedByAK==NULL) ? 'APPROVE' : 'UNAPPROVE';
					$flagAksi = ($row->ApprovedByAK=='' || $row->ApprovedByAK==NULL) ? 'UPD' : 'SEE_PD';
					$TipePD = NULL;
					if (substr($row->NoPD,0,5)=='PDKSO' || substr($row->NoPD,0,5)=='PDRKD' ) {
						$TipePD = 'RKD';
					}else if(substr($row->NoPD,0,5)=='PDMIX'){
						$TipePD = 'MIX';
					}else{
						$TipePD = '';
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
						<td><?=$row->ApprovedByAK?></td>
						<td><?=tgl_baru_dengan_waktu($row->TimeApprovedAK)?></td>
						<td class="text-center">
							<a href="<?=site_url('Entry/PDPJ/EntryPDKeu_C/UpdateFormPD/'.$flagAksi.'/'.$JobNo.'/'.$Alokasi.'/'.$row->NoPD.'/'.$TipePD)?>" class="btn btn-md  btnUpdate text-bold text-blue" >SELECT</a>	
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-md  btnApprove text-bold text-blue" onclick="btnApprove(<?=$NoUrut?>)" id="btnApproveID<?=$NoUrut?>" data-NoPD="<?=$row->NoPD?>" data-Deskripsi="<?=$row->Deskripsi?>" data-TxtApprove="<?=ucfirst($ApprovedByAK)?>" ><?=$ApprovedByAK?></button>
						</td class="text-center">

						<td>
							<a target="_blank" href="<?=site_url('MY_PD/PD_PDF_ALL/ExpPDF_PD/'.$row->LedgerNo.'/'.$row->JobNo.'/'.$row->Alokasi.'/'.$row->NoPD.'/0/0/KEU')?>" class="btn btn-md  btnPrint text-bold text-blue" >PRINT</a></td>

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
					<td></td>
				</tr>
			</tfoot>
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
