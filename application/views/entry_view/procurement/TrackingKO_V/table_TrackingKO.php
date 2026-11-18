<style type="text/css">
	#mytable thead tr th{
		font-size: 14px !important;
	}
	#mytable tbody tr td{
		font-size: 14px !important;
	}

</style>

<div class="box">
	<div class="box-header">
		<button class="btn btn-default" onclick="AddStatus('<?=$JobNo?>','<?=$NoKO?>')">ADD STATUS</button>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-6">
				<div class="table-responsive">
					
					<table width="100%"></table>
					<table id="mytable" class="table table-striped table-hover table-condensed table-bordered " style="margin-top: 20px;">
						<thead>
							<tr class="bg-gray disabled color-palette">
								<th class="text-center tengah" width="30px">No</th>
								<th class="text-center tengah">Keterangan</th>
								<th class="text-center tengah" width="80px">Tanggal</th>
								<th class="text-center tengah" width="80px">Status</th>
								<th class="text-center"></th>
								
							</tr>
						</thead>
						<tbody>
							<?php 
							$NoUrut=0;
							foreach ($ListTraceKO as $row): 
								$NoUrut++;
								?>
								<tr>
									<td class="text-center"><?=$NoUrut?></td>
									<td><?=$row->Keterangan?></td>
									<td class="text-center"><?=tgl_baru($row->Tanggal)?></td>
									<td class="text-center"><?=($row->Status == 0 || $row->Status == '0') ? 'In Process' : 'Delivered' ?></td>
									<td class="text-center">
										<button class="btn btn-link" 
										id="btn_EditStatus<?=$row->Trace?>" 
											data-Keterangan="<?=$row->Keterangan?>" 
											data-Tanggal="<?=$row->Tanggal?>" 
											data-Status="<?=$row->Status?>" 
											data-JobNo="<?=$JobNo?>" 
											data-NoKO="<?=$row->NoKO?>" 

											onclick="EditStatus(<?=$row->Trace?>)"><u>UPDATE</u></button>
									</td>
								</tr>
							<?php endforeach ?>
						</tbody>
						
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('#mytable').DataTable({
			order: [[0, 'asc']],
			columnDefs: [
				{ targets: 1, orderable : false},
				{ targets: 3, orderable : false},
				{ targets: 4, orderable : false},

				]
		});
	});
	
</script>


