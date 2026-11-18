<style type="text/css">

	#tbl_invoice{
		font-size: 11px;
		font-family: "Arial Rounded MT Bold", "Helvetica Rounded", Arial, sans-serif;
		white-space: nowrap;
	}

	#tbl_invoice tr th{
		padding-top: 10px;
		padding-bottom: 10px;
	}
	#tbl_invoice tr td{
		padding-top: 10px !important;
		padding-bottom: 10px !important; 
		padding-left: 5px !important;
	}

	#tbl_invoice tbody tr.even:hover {
		background-color: cadetblue;
		cursor: pointer;
	}

	#tbl_invoice tr.even:hover td.sorting_1 {
		background-color: cadetblue;
		cursor: pointer;
	}

</style>

<div class="row">
	<div class="col-sm-12">
		
		<div class="box">
			
			<div class="box-header" style="border-bottom:1px solid" >
				<a href="<?= site_url('MyReport/Procurement/Query_invoice_report/pdf_query_invoice/' . $JobNo . '/' . $VendorId. '/' . $PrdAwal. '/' . $PrdAkhir) ?>" target="_blank" class="btn btn-sm btn-danger btn_print">PRINT PDF <i class="fa fa-print"></i></a>

			</div>

			<div class="box-body">
				
				<div class="table-responsive">
					
					<table width="100%" border="0" id="tbl_invoice" class="table table-bordered table-striped table-hover table-condensed" >
						<thead>
							<tr class="bg-gray-active">
								<th align="center">No.</th>
								<th>Job No</th>
								<th>No KO</th>
								<th>Invoice</th>
								<th>Tgl Invoice</th>
								<th>Due Date</th>
								<th style="text-align: right;" >Total Invoice (Rp)</th>
								<th style="text-align: right;">PPN (Rp)</th>
								<th style="text-align: right;">PPH (Rp)</th>
								<th>No. Faktur Pajak</th>
								<th>Tgl Faktur Pajak</th>
								<th style="text-align: right;">Payment Amount (Rp)</th>
								<th style="text-align: right;">Sisa Invoice (Rp)</th>
							</tr>
						</thead>
						
						<tbody>
							<?php
							$nomor_urut = 1;
							$SisaInvoice = 0;
							foreach($data_query_inv as $mydata  ) :
								$SisaInvoice = $mydata->Total - $mydata->PaymentAmount;
								?>
								<tr>
									<td><?=$nomor_urut++?></td>
									<td><?=$mydata->JobNo1?></td>
									<td>
										<a href="javascript:void(0)" 
										data-LedgerNo="<?=$mydata->LedgerNo?>" 
										data-NoKO="<?=$mydata->NoKO?>" 
										data-InvNo="<?=$mydata->InvNo?>"  
										class="btn btn-link btn-sm btn_detail_invoice">
										<?=$mydata->NoKO?></a>
									</td>
									<td><?=$mydata->InvNo?></td>
									<td><?=tgl_baru($mydata->InvDate)?></td>
									<td><?=tgl_baru($mydata->DueDate)?></td>
									<td align="right"><?=UangBiasa($mydata->Total)?></td>
									<td align="right"><?=UangBiasa($mydata->PPN)?></td>
									<td align="right"><?=UangBiasa($mydata->PPH)?></td>
									<td><?=$mydata->FPNo?></td>
									<td><?=tgl_baru($mydata->FPDate)?></td>
									<td align="right"><?=UangBiasa($mydata->PaymentAmount)?></td>
									<td align="right"><?=UangBiasa($SisaInvoice)?></td>
								</tr>
							<?php endforeach; ?>

						</tbody>

					</table>

				</div>

			</div>

			<div class="overlay loading_saya" style="display:none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>
		</div>

	</div>

</div>


<div class="modal fade" id="modal_invoice">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h5 class="modal-title text-bold">DETAIL INVOICE</h5>
				</div>
				<div class="modal-body" id="modal_body_invoice">

				</div>
			</div>

		</div>
	</div>

	<script type="text/javascript">
		$(function(){
			// $('#tbl_invoice').DataTable({
			// 	'ordering' : false,
			// 	'pageLength': 50,
			// 	'lengthMenu': [5, 10, 20, 50, 100, 200, 500],
			// });

			$('.btn_detail_invoice').click(function(){

				var LedgerNo = $(this).attr('data-LedgerNo');
				var NoKO = $(this).attr('data-NoKO');
				var InvNo = $(this).attr('data-InvNo');

				jQuery.ajax({

					url: '<?= site_url("MyReport/Procurement/Query_invoice_report/get_detail_invoice") ?>',
					type: 'post',
					dataType: 'html',
					data: { 
						'NoKO': NoKO,
						'InvNo' : InvNo,

					},
					cache:false,
					beforeSend : function(){
						$('#modal_body_invoice').html('');
						$('.loading_saya').show();
					},
					complete: function(xhr, textStatus) {
						$('.loading_saya').hide();
					},
					success: function(data, textStatus, xhr) {

						$('#modal_invoice').modal('show');
						$('#modal_body_invoice').html(data);
					},
					error: function(xhr, textStatus, errorThrown) {
						$.toast({
							text: 'terjadi kesalahan',
							position: 'bottom-right',
							stack: false,
							hideAfter: 2000,
							icon: 'error',

						});
						return false;
					}
				});


			});

		});
	</script>