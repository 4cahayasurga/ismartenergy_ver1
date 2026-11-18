<style type="text/css">
	#mytable thead tr th{
		color:White;
		background-color:#5D7B9D;
		font-weight:bold;
		text-align: center;
		font-size: 14px !important;
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
	}

	#mytable tbody tr td{
		font-size: 14px !important;
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
	}

	#mytable tfoot tr th{
		color:White;
		background-color:#5D7B9D;
		font-weight:bold;
		text-align: right;
		font-size: 14px !important;
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
	}

	

</style>

<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">

			<table border="0" width="100%" style="margin-bottom: 10px !important;">
				<tr>
					<td><a href="<?=site_url('Entry/Procurement/Invoice/AddNew/'.$JobNo)?>" class="btn btn-md btn-primary" >TAMBAH INVOICE <i class="fa fa-plus"></i></a></td>
				</tr>
			</table>


			<table width="100%" border="1" id="mytable" class="table table-bordered table-hover table-striped">
				<thead>
					<tr>
						<th>TimeEntry</th>
						
						<th>No KO</th>
						<th>Vendor</th>
						<th>No. Invoice</th>
						<th>Tgl Invoice</th>
						<th>Jatuh Tempo</th>
						<th>Total Invoice (Rp)</th>
						<th>PPN (Rp)</th>
						<th>PPH (Rp)</th>
						<th>No. Faktur Pajak</th>
						<th>Tgl Faktur Pajak</th>
						<th>Time Entry</th>
						<th width="100px"></th>	
					</tr>
				</thead>
				<tbody>
					<?php 

					$sumTotal = 0;
					$sumPPN = 0;
					$sumPPH = 0;
					$NoUrut = 0;
					foreach ($ListInvoice as $row): 

						$Total 	= CekNull_Angka_Dan_Bikin_Float($row->Total);
						$PPN 	= CekNull_Angka_Dan_Bikin_Float($row->PPN);
						$PPH 	= CekNull_Angka_Dan_Bikin_Float($row->PPH);

						$sumTotal = $sumTotal + $Total;
						$sumPPN = $sumTotal + $PPN;
						$sumPPH = $sumTotal + $PPH;

						$NoUrut++;


						?>
						<tr>
							<td><?=$row->TimeEntry?></td>
							
							<td><?=htmlspecialchars($row->NoKO)?></td>
							<td><?=htmlspecialchars($row->VendorNm)?></td>
							<td><?=htmlspecialchars($row->InvNo)?></td>
							<td><?=htmlspecialchars(tgl_baru($row->InvDate))?></td>
							<td><?=htmlspecialchars(tgl_baru($row->DueDate))?></td>
							<td class="text-right"><?=htmlspecialchars(number_format($Total))?></td>
							<td class="text-right"><?=htmlspecialchars(number_format($PPN))?></td>
							<td class="text-right"><?=htmlspecialchars(number_format($PPH))?></td>
							<td><?=htmlspecialchars($row->FPNo)?></td>
							<td><?=htmlspecialchars(tgl_baru($row->FPDate))?></td>
							<td><?=htmlspecialchars(tgl_baru_dengan_waktu($row->TimeEntry))?></td>
							<th class="text-center">

								<a href="<?=site_url('Entry/Procurement/Invoice/FormUpdateInvoice/'.$row->LedgerNo)?>" class="btn btn-link btn-lg" style="font-size: 20px !important;" title="Edit"><i class="fa fa-pencil"></i></a>

								<button type="button" class="btn btn-link btn-lg btnHapus" data-NoKO="<?=htmlspecialchars($row->NoKO)?>" data-InvNo="<?=htmlspecialchars($row->InvNo)?>" data-NoSJ="<?=htmlspecialchars($row->NoSJ)?>" data-LedgerNo="<?=htmlspecialchars($row->LedgerNo)?>" style="font-size: 20px !important;color:red;" title="Hapus"  ><i class="fa fa-trash"></i></button>
							</th>
						</tr>
					<?php endforeach ?>
				</tbody>
				<tfoot>
					<tr>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th><?='sum='.number_format($sumTotal)?></th>
						<th><?='sum='.number_format($sumPPN)?></th>
						<th><?='sum='.number_format($sumPPH)?></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#mytable').dataTable( {
			"order": [[ 0, 'desc' ]],
			"columnDefs": [{ "visible": false, "targets": 0 },{ "orderable": false, "targets": 1 }]
		} );
	});
</script>