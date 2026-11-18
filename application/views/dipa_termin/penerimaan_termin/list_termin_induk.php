

<div class="box box-danger">
	<div class="box-header">
		<button class="btn btn-md bg-purple" type="button" id="mybtn_tambah_termin_induk" data-JobNo="<?=$JobNo?>">TAMBAH TERMIN INDUK</button>
		<a href="<?=site_url('DipaTermin/PenerimaanTermin/ExportExcel_TerminInduk/'.$JobNo)?>" class="btn btn-md btn-default">EXPORT TO EXCEL <i class="fa  fa-file-excel-o"></i></a>
	</div>

	<div class="box-body">
		<div class="table-responsive">
			<table id="mytable_penerimaan_termin" class="table table-bordered table-striped table-hover table-condesed huruf14" >
				<thead>
					<tr style="color:White;background-color:#5D7B9D;font-weight:bold;">
						<th>No</th>
						<th style="width:80px;text-align: center;">Tgl Cair</th>
						<th>No. BAP</th>
						<th>Uraian</th>
						<th>Bruto BOQ (Rp)</th>
						<th>Potongan Uang Muka (Rp)</th>
						<th>Potongan Retensi (Rp)</th>
						<th>PPN (%)</th>
						<th>PPN (Rp)</th>
						<th>PPH (%)</th>
						<th>PPH (Rp)</th>
						<th>Netto Penerimaan (Rp)</th>
						<th style="text-align: center;">Action</th>
					</tr>
				</thead>
				<tbody>


					<?php
					$no = 1;
					$sumBrutoBOQ=0;
					$sumUM=0;
					$sumRetensi=0;
					$sumPPN_RP=0;
					$sumPPH_RP=0;
					$sumNettoPenerimaan=0;

					foreach ($list_termin_induk->result_array() as $data_termin_induk) : 

						$LedgerNo = $data_termin_induk['LedgerNo'];

						$PPN_persen = ($data_termin_induk['PPN_persen'] != NULL) ? (float) $data_termin_induk['PPN_persen'] : 0;
						$PPH_persen = ($data_termin_induk['PPH_persen'] != NULL) ? (float) $data_termin_induk['PPH_persen'] : 0;

						$sumBrutoBOQ += (float) $data_termin_induk['BrutoBOQ'];
						$sumUM += (float) $data_termin_induk['UM'];
						$sumRetensi += (float) $data_termin_induk['Retensi'];
						$sumPPN_RP += (float) $data_termin_induk['PPN_nominal'];
						$sumPPH_RP += (float) $data_termin_induk['PPH_nominal'];
						$sumNettoPenerimaan += (float) $data_termin_induk['TerminInduk'];

						?>



						<tr>
							<td><?=$no++?></td>
							<td style="width:50px;text-align: center;"><?=tgl_baru_tahun_pendek($data_termin_induk['TglCair'])?></td>
							<td><?=$data_termin_induk['NoBAP']?></td>
							<td><?=$data_termin_induk['Uraian']?></td>

							<td class="text-right"><?=new_decimal($data_termin_induk['BrutoBOQ'])?></td>
							<td class="text-right"><?=new_decimal($data_termin_induk['UM'])?></td>
							<td class="text-right"><?=new_decimal($data_termin_induk['Retensi'])?></td>

							<td class="text-right"><?=$PPN_persen.'%'?></td>
							<td class="text-right"><?=new_decimal($data_termin_induk['PPN_nominal'])?></td>
							<td class="text-right"><?=$PPH_persen.'%'?></td>

							<td class="text-right"><?=new_decimal($data_termin_induk['PPH_nominal'])?></td>
							<td class="text-right"><?=new_decimal($data_termin_induk['TerminInduk'])?></td>
							<td style="text-align: center;">
								<button id="btn_edit_termin_induk<?=$LedgerNo?>"  data-JobNo="<?=$JobNo?>" data-LedgerNo="<?=$data_termin_induk['LedgerNo']?>" class="btn btn-sm btn-default text-blue" style="text-decoration: underline;" title="button Lihat / Edit" onClick="EditTerminInduk(<?=$LedgerNo?>)"   >LIHAT <i class="fa fa-pencil"></i></button>
								<?php if($data_termin_induk['PDFFile'] != NULL) : ?>
									<button class="btn btn-sm btn-danger btn-showpdf" data-urlfile="<?= $data_termin_induk['PDFFile'] ?>">PDF<i class="fa fa-file-o"></i></button>
								<?php else : ?>
									<button class="btn btn-sm btn-danger" disabled>PDF <i class="fa fa-file-o"></i></button>
								<?php endif; ?>

							</td>

						</tr>
					<?php endforeach ?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="4">TOTAL</th>
						<th class="text-right"><?=number_format($sumBrutoBOQ)?></th>
						<th class="text-right"><?=number_format($sumUM)?></th>
						<th class="text-right"><?=number_format($sumRetensi)?></th>
						<th class="text-right"></th>
						<th class="text-right"><?=number_format($sumPPN_RP)?></th>
						<th class="text-right"></th>
						<th class="text-right"><?=number_format($sumPPH_RP)?></th>
						<th class="text-right"><?=number_format($sumNettoPenerimaan)?></th>
						<th></th>
					</tr>
				</tfoot>
			</table>
		</div>


	</div>

	<div class="overlay loading_ent_rap" style="display: none;">
		<i class="fa fa-refresh fa-spin"></i>
	</div>

	<div class="modal fade" id="modalShowPDF">
		<div class="modal-dialog modal-lg" style="height: 100vh;">
			<div class="modal-content">
				<div class="modal-body">
					<embed id="embedPDF" type="application/pdf" src="" width="100%" height="900"></embed>
				</div>
			</div>
		</div>
	</div>

</div>

<script type="text/javascript">

	$(document).ready(function(){
		$('#mytable_penerimaan_termin').DataTable({
			"ordering": false
		});

		$('#mybtn_tambah_termin_induk').click(function(){
			var JobNo = $(this).attr('data-JobNo');
			$('#MyModalTerminInduk_Body').html('');

			jQuery.ajax({
				url: '<?=site_url('DipaTermin/PenerimaanTermin/dapat_form_tambah_termin_induk')?>',
				type: 'POST',
				dataType: 'html',
				async: true,
				cache : false,
				data: {'JobNo' : JobNo},

				beforeSend : function(){
					// $('.loading_ent_rap').show();
					$('#mybtn_tambah_termin_induk').attr('disabled',true).text('LOADING...');
					
				},
				complete: function(xhr, textStatus) {
					$('#mybtn_tambah_termin_induk').attr('disabled',false).text('TAMBAH TERMIN INDUK');
				},
				success: function(data, textStatus, xhr) {
					
					$('#MyModalTerminInduk').modal('show');
					$('#MyModalTerminInduk_Body').html(data);
				},
				error: function(xhr, textStatus, errorThrown) {
					$.toast({
						text: 'terjadi kesalahan',
						position: 'bottom-right',
						stack: false,
						hideAfter: 1000,
						icon: 'error',

					});
					return false;
				}
			});

			
		});

		// $('.btn_edit_termin_induk').click(function(){
		// 	var JobNo = $(this).attr('data-JobNo');
		// 	var LedgerNo = $(this).attr('data-LedgerNo');
		// 	// alert(LedgerNo);

		// 	jQuery.ajax({
		// 		url: '<?=site_url('DipaTermin/PenerimaanTermin/dapat_form_edit_termin_induk')?>',
		// 		type: 'POST',
		// 		dataType: 'HTML',
		// 		async: true,
		// 		cache : false,
		// 		data: {
		// 			'JobNo' : JobNo,
		// 			'LedgerNo' : LedgerNo
		// 		},
				
		// 		beforeSend : function(){
		// 			$('.loading_ent_rap').show();
		// 		},
		// 		complete: function(xhr, textStatus) {
		// 			$('.loading_ent_rap').hide();
		// 		},
		// 		success: function(data, textStatus, xhr) {
		// 			// console.log(data);
		// 			$('#MyModalTerminInduk').modal('show');
		// 			$('#MyModalTerminInduk_Body').html(data);
		// 		},
		// 		error: function(xhr, textStatus, errorThrown) {
		// 			$.toast({
		// 				text: 'terjadi kesalahan',
		// 				position: 'bottom-right',
		// 				stack: false,
		// 				hideAfter: 1000,
		// 				icon: 'error',

		// 			});
		// 			return false;
		// 		}
		// 	});

		// });

		$('.btn-showpdf').click(function(){
			var urlfile = $(this).data('urlfile');
			var url = "<?=site_url('images/termin_induk/')?>"+urlfile;
			$('#embedPDF').attr('src',url);
			$('#modalShowPDF').modal('show');
		})

	});

	function EditTerminInduk(LedgerNo){
		var JobNo = $('#btn_edit_termin_induk'+LedgerNo).attr('data-JobNo');
		var LedgerNo = $('#btn_edit_termin_induk'+LedgerNo).attr('data-LedgerNo');
			// alert(LedgerNo);

		jQuery.ajax({
			url: '<?=site_url('DipaTermin/PenerimaanTermin/dapat_form_edit_termin_induk')?>',
			type: 'POST',
			dataType: 'HTML',
			async: true,
			cache : false,
			data: {
				'JobNo' : JobNo,
				'LedgerNo' : LedgerNo
			},

			beforeSend : function(){
				$('.loading_ent_rap').show();
			},
			complete: function(xhr, textStatus) {
				$('.loading_ent_rap').hide();
			},
			success: function(data, textStatus, xhr) {
					// console.log(data);
				$('#MyModalTerminInduk').modal('show');
				$('#MyModalTerminInduk_Body').html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				$.toast({
					text: 'terjadi kesalahan',
					position: 'bottom-right',
					stack: false,
					hideAfter: 1000,
					icon: 'error',

				});
				return false;
			}
		});

		
	}



	
</script>
