<style type="text/css">
	
	.atur_th{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size: 11px !important;
		background-color: #b2bec3;
		color: black;
	}

	.atur_td{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size: 10px !important;
	}

	table#mytable_belum_bayar{
		white-space: nowrap;
	}

</style>


<div class="col-sm-12 row">

	<div class="table-responsive">

	<table width="100%" class="table table-bordered table-striped table-hover" border="1" id="mytable_belum_bayar">
		<thead>
			<!-- <tr class="atur_th">
				<th class="text-center">#</th>
				<th style="width:50px;border:0px solid;">Job No</th>
				<th style="width:100px;border:0px solid;" >Job Name</th>
				<th style="width:50px;border:0px solid;">No. PD</th>
				<th style="width:50px;border:0px solid;">Tgl PD</th>
				<th style="width:50px;border:0px solid;">Alokasi</th>
				<th style="width:70px;border:0px solid;">Tipe Form</th>
				<th style="width:50px;border:0px solid;">No KO</th>
				<th style="width:200px;border:0px solid;">Deskripsi</th>
				<th style="width:50px;border:0px solid;text-align: center;">Total PD</th>
				<th style="width:150px;border:0px solid;">Approved By Keuangan</th>
				<th style="width:90px;border:0px solid;">Approved On</th>
			</tr> -->
			<tr class="atur_th">
				<th class="text-center">#</th>
				<th >Job No</th>
				<th>Job Name</th>
				<th >No. PD</th>
				<th>Tgl PD</th>
				<th >Alokasi</th>
				<th>Tipe Form</th>
				<th>No KO</th>
				<th >Deskripsi</th>
				<th >Total PD</th>
				<th>Approved By Keuangan</th>
				<th>Approved On</th>
			</tr>
		</thead>
		<tbody class="atur_td">

			<?php 
			$SumTotalPD = 0;
			foreach($list_data_payment_belum_dibayar as $mydata) : 

				$SumTotalPD += $mydata->TotalPD;

			?>

				<tr>
					<td class="text-center">
						<!-- <input type="text" name="NoPD" value="<?=$mydata->NoPD?>"> -->
						<!-- <button class="btn btn-md bg-blue-active" title="Pembayaran PD" id="data_PD<?=$mydata->NoPD?>" onclick="FrmPembayaran('<?=$mydata->NoPD?>')"><i class="fa fa-credit-card"></i></button> -->
						<!-- <button class="btn btn-md bg-gray-active" title="Pembayaran PD" id="loading_pd<?=$mydata->NoPD?>" style="display: none;" ><i class="fa fa-spinner fa-spin"></i></button> -->

						<a href="<?=site_url('FrmPayment_PD/FrmDetailPD/'.$mydata->LedgerNo.'/'.$mydata->NoPD.'/'.$TipePembayaran)?>" class="btn btn-md bg-blue-active" title="Detail PD"><i class="fa fa-credit-card"></i></a>
						
					</td>
					<td><?=$mydata->JobNo?></td>
					<td><?=$mydata->JobNm?></td>
					<td><?=$mydata->NoPD?></td>
					<td><?=tgl_baru($mydata->TglPD)?></td>
					<td><?=$mydata->Alokasi?></td>
					<td><?=$mydata->TipeForm?></td>
					<td><?=$mydata->NoKO?></td>
					<td><?=$mydata->Deskripsi?></td>
					<td style="text-align: center;"><?=number_format($mydata->TotalPD)?></td>
					<td><?=$mydata->ApprovedByKK?></td>
					<td><?=($mydata->TimeApprovedKK == '' || $mydata->TimeApprovedKK == NULL ) ? '' : date('d-M-Y H:i',strtotime($mydata->TimeApprovedKK))?></td>
				</tr>

			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="9" class="text-right">Sum = <?=number_format($SumTotalPD)?></th>
				<th colspan="2"></th>
			</tr>
		</tfoot>
	</table>

	</div>
</div>




<script type="text/javascript" src="<?=base_url()?>assets/dataTables.fixedColumns.min.js"></script>
<script type="text/javascript">
	$(function(){

		var table = $('#mytable_belum_bayar').DataTable( {
			scrollY:        "5400px",
			scrollX:        true,
			scrollCollapse: true,
			paging:         false,
			fixedColumns:   true
		} );


	});

		// 	var data = [];      
		// 	$('#mytable_belum_bayar tbody tr').each(function(){
		// 		var row = {};
		// 		$(this).find('input').each(function(){
		// 			row[$(this).attr('name')] = $(this).val();
		// 		});
		// 	alert(row);
		// 	data.push(row);
		// 	data.push(row);
		// });
			// console.log(data);
			// alert(data[i].NoPD);
			// $.each(data, function(i, item) {
				
			// 	$('#NoPD').append('<option value="'+(data[i].NoPD+'" >'+(data[i].NoPD+'</option>');
			// });

		// alert(data);
		// alert(data[0]);
	</script>