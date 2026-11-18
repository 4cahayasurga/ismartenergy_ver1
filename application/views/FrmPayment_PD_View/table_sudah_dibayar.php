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

	#table_sudah_bayar{
		white-space: nowrap;
	}

	table thead tr th{
		padding-top: 10px !important;
		padding-bottom: 10px !important;
	}

	table tbody tr td{
		padding-top: 10px !important;
		padding-bottom: 10px !important;
	}

	table tfoot tr th{
		padding-top: 10px !important;
		padding-bottom: 10px !important;
	}

	table#table_sudah_bayar.dataTable tbody tr:hover {
		background-color: #bdc3c7;
		/*font-size: 12px !important;*/
	}

	table#table_sudah_bayar.dataTable tbody tr:hover > .sorting_1 {
		/*background-color: #ffa;*/
		background-color: #bdc3c7;
		/*font-size: 12px !important;*/
	}

</style>


<div class="col-sm-12 row">
	<form action="<?=site_url('FrmPayment_PD/PaymentExportExcel')?>" method="POST">

		<input type="date" style="display:none;" name="DariTgl" value="<?=$DariTgl?>">
		<input type="date" style="display:none;" name="SampaiTgl" value="<?=$SampaiTgl?>">

		<button type="submit" class="btn btn-success btn-md btn_export_excel" data-DariTgl="<?=$DariTgl?>" data-SampaiTgl="<?=$SampaiTgl?>" disabled >Export Excel <i class="fa fa-upload"></i></button>

		<button type="button" class="btn btn-default btn-md loading_btn_excel" style="display:none;" disabled >Loading... <i class="fa fa-refresh fa-spin"></i></button>
		<p></p>

		<div class="table-responsive">

			<table width="100%" class="table table-bordered table-striped table-hover table-condensed" id="table_sudah_bayar">
				<thead>
					<tr class="atur_th">
						<th style="width: 100px !important;" class="text-center">
							<input type="checkbox" id="check-all" class="text-center" style="width: 100px !important;">
						</th>
						<th class="text-center">#</th>
						<th>Tgl Bayar</th>
						<th >Tipe Form</th>
						<th >Alokasi</th>
						<th>No. PD</th>
						<th >Job Name</th>
						<th>Job No</th>
						<th>Nama Penerima</th>


						<th>Rekening Penerima</th>
						<th >Bank Penerima</th>
						<th style="text-align: right;">Nilai (Rp)</th>
						<th >Media</th>
						<th>Via</th>
						<th>No. Cek/BG</th>
						<th>Rekening Pengirim</th>
						<th>No. KO</th>
						<th>Keterangan</th>

					</tr>
				</thead>
				<tbody class="atur_td">
					<?php 
					$SumAmount = 0;
					foreach ($list_SudahBayar_PD as $DonePayPD): 

						$SumAmount += $DonePayPD->Amount;
						?>


						<tr>
							<td width="100px" class="text-center">

								<input type="checkbox" name="IdPayment_array[]" class="check-item text-center" value="<?=$DonePayPD->LedgerNo?>">

							</td>
							<td class="text-center" style="padding-left:10px;padding-right:10px;">
								<a href="javascript:void(0)" class="btn btn-default btn-md text-black text-bold" title="Hapus Data" onclick="deletePayment('<?=$DonePayPD->LedgerNo?>','<?=$DonePayPD->NoPD?>')" id="btn_delete<?=$DonePayPD->LedgerNo?>"><i class="fa fa-trash"></i></a>
								<!-- <button type="button" class="btn btn-link btn-lg text-black text-bold" onclick="deletePayment('<?=$DonePayPD->LedgerNo?>','<?=$DonePayPD->NoPD?>')" id="btn_delete<?=$DonePayPD->LedgerNo?>"><i class="fa fa-trash"></i></button> -->
								<button type="button" class="btn btn-link btn-md text-blue text-bold" id="btn_loading<?=$DonePayPD->LedgerNo?>" style="display: none;"><i class="fa fa-refresh fa-spin"></i></button>
								<a data-UrlSaya="<?=site_url('FrmPayment_PD/PdfExport_Payment/'.$DonePayPD->LedgerNo)?>" href="javascript:void(0)" onclick="BtnPrint('<?=$DonePayPD->LedgerNo?>','<?=$DonePayPD->Keterangan?>')" class="btn btn-danger btn-md text-bold" title="Print Data" id="btn_print<?=$DonePayPD->LedgerNo?>"><i class="fa fa-print"></i></a>

							</td>
							<td><?=tgl_baru($DonePayPD->TglBayar)?></td>
							<td ><?=$DonePayPD->TipeForm?></td>
							<td ><?=$DonePayPD->Alokasi?></td>
							<td><?=$DonePayPD->NoPD?></td>
							<td ><?=$DonePayPD->JobNm?></td>
							<td><?=$DonePayPD->JobNo?></td>
							<td><?=$DonePayPD->AtasNama?></td>


							<td><?=$DonePayPD->NoRek?></td>
							<td ><?=$DonePayPD->Bank?></td>
							<td style="text-align: right;"><?=number_format($DonePayPD->Amount)?></td>
							<td ><?=$DonePayPD->CaraBayar?></td>
							<td><?=$DonePayPD->JenisTrf?></td>
							<td><?=$DonePayPD->NoCG?></td>
							<td><?=$DonePayPD->RekId?></td>
							<td><?=$DonePayPD->NoKO?>O</td>
							<td><?=$DonePayPD->Keterangan?></td>
						</tr>

					<?php endforeach ?>

				</tbody>
				<tfoot>
					<tr>
						<th colspan="11" style="text-align:right;">Sum = <?=number_format($SumAmount)?></th>
						<th colspan="6" ></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</form>
</div>



<script type="text/javascript" src="<?=base_url()?>assets/dataTables.fixedColumns.min.js"></script>
<script type="text/javascript">
	function BtnPrint(LedgerNo='',Keterangan=''){
		if(LedgerNo == ''){
			Swal.fire('Error!','Error','error');
			return false;
		}

		if (Keterangan != 'PINDAH DANA') {
			Swal.fire('Info!','Print hanya berlaku untuk PINDAH DANA.','info');
			return false;
		}

		var UrlSaya = $('#btn_print'+LedgerNo).attr('data-UrlSaya');
		window.open(UrlSaya,"_blank");

	}

	function CekJumlahCentang(){
		// var numberNotChecked = $('input:checkbox:(":checked")').length;
		var numberNotChecked = $('.check-item').filter(':checked').length;
		if (numberNotChecked > 0) {
			$('.btn_export_excel').attr('disabled',false);
		}else{
			$('.btn_export_excel').attr('disabled',true);
		}
		// alert(numberNotChecked);
	}

	$(function(){

		var table = $('#table_sudah_bayar').DataTable( {
			scrollY:        "400px",
			scrollX:        true,
			scrollCollapse: true,
			paging:         false,
			fixedColumns:   {
				left: 1,
				right: 1
			},
			ordering : false,
			"columnDefs": [ {
				"targets": 0,
				"orderable": false
			} ]
		} );


		// $('.btn_export_excel').click(function(){
		// 	var DariTgl = $(this).attr('data-DariTgl');
		// 	var SampaiTgl = $(this).attr('data-SampaiTgl');
		// 	var LedgerNo_centang = $('.check-item').filter(':checked').val();


		// 	var IdPayment_array=[];
		// 	$.each($("input[name='IdPayment']:checked"), function(){            
		// 		IdPayment_array.push($(this).val());
		// 	});


		// 	jQuery.ajax({
		// 		url: '<?=site_url('FrmPayment_PD/PaymentExportExcel')?>',
		// 		type: 'POST',
		// 		dataType: 'html',
		// 		data: {IdPayment_array : IdPayment_array, DariTgl : DariTgl, SampaiTgl : SampaiTgl},
		// 		beforeSend : function(){
		// 			$(this).hide();
		// 			$('#loading_btn_excel').show();
		// 		},
		// 		complete: function(xhr, textStatus) {
		// 			$(this).show();
		// 			$('#loading_btn_excel').hide();
		// 		},
		// 		success: function(data, textStatus, xhr) {
		// 			console.log(data);
		// 		},
		// 		error: function(xhr, textStatus, errorThrown) {
		// 			alert('error');
		// 		}
		// 	});
		// 	return false;
		// });

	});

	$('#check-all').click(function(){
		if($(this).is(":checked")){
			$(".check-item").prop("checked", true);
		}else{
			$(".check-item").prop("checked", false);
		}
		CekJumlahCentang();
	});

	$('.check-item').change(function(){
		CekJumlahCentang();
	});

</script>