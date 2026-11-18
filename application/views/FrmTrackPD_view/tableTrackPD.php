<style type="text/css">
	#tblPD thead tr th{
		text-align: center;
		font-size: 12px;
		padding-top:10px;
		padding-bottom: 10px;
	}

	#tblPD tbody tr td{
		font-size: 12px;
		padding-top:10px !important; 
		padding-bottom: 10px !important;
	}



</style>
<div class="row">
	
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover table-bordered table-condensed" id="tblPD"  >


						<thead>
							<tr class="bg-gray">
								<th rowspan="2">No PD</th>
								<th rowspan="2">Tgl PD</th>
								<th rowspan="2">Tipe Form</th>
								<th colspan="4">Permintaan Dana</th>
								<th colspan="3">Pertanggungjawaban Dana</th>
								<th rowspan="2">Reject By</th>
								<th rowspan="2">Reject On</th>
							</tr>
							<tr class="bg-gray">
								<th style="text-align: left;">Approved By<br>Lapangan/Pusat</th>
								<th style="text-align: left;" >Approved By<br>Cost Control</th>
								<th style="text-align: left;" >Approved By<br>Keuangan</th>
								<th style="text-align: left;">Payment Kasir<br>By</th>

								<th style="text-align: left;" >Approved By<br>Lapangan/Pusat</th>
								<th style="text-align: left;" >Approved By<br>Cost Control</th>
								<th style="text-align: left;" >Approved By<br>Keuangan</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($mydata as $data) { ?>
								<tr>
									<td><?=$data->NoPD?></td>
									<td><?=tgl_baru($data->TglPD)?></td>
									<td><?=$data->TipeForm?></td>
									<td><?=tgl_baru_tahun_pendek_dengan_waktu($data->TimeApprovedAK)?></td>
									<td><?=tgl_baru_tahun_pendek_dengan_waktu($data->TimeApprovedKT)?></td>
									<td><?=tgl_baru_tahun_pendek_dengan_waktu($data->TimeApprovedKK)?></td>
									<td><?=tgl_baru($data->TglBayar)?></td>
									<td><?=tgl_baru_tahun_pendek_dengan_waktu($data->PjTimeApprovedAK)?></td>
									<td><?=tgl_baru_tahun_pendek_dengan_waktu($data->PjTimeApprovedKT)?></td>
									<td><?=tgl_baru_tahun_pendek_dengan_waktu($data->PjTimeApprovedKK)?></td>
									<td><?=$data->RejectBy?></td>
									<td><?=tgl_baru_tahun_pendek_dengan_waktu($data->TimeReject)?></td>
								</tr>

							<?php } ?>
						</tbody>


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
			"pageLength": 50,
			// "fixedHeader": true,
		});



	});		


</script>