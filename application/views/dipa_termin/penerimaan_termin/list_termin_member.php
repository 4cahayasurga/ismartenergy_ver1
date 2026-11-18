<div class="box">
	<div class="box-header">
		<button class="btn btn-md" style="background-color:#27ae60;color:white;" type="button" id="mybtn_tambah_termin_member" data-JobNo="<?=$JobNo?>">TAMBAH TERMIN MEMBER</button>
		<a href="<?=site_url('DipaTermin/PenerimaanTermin/ExportExcel_TerminMember/'.$JobNo)?>" class="btn btn-md btn-default">EXPORT TO EXCEL <i class="fa  fa-file-excel-o"></i></a>
	</div>
	<div class="box-body">

		<div class="table-responsive">
			<table id="table_termin_member" class="table table-bordered table-striped table-hover table-condesed huruf14" >
				<thead>
					<tr style="color:White;background-color:#5D7B9D;font-weight:bold;">
						<th style="text-align:center;">No</th>
						<th style="text-align:center;">#</th>
						<th style="width:30px;text-align: center;">Job</th>
						<th style="width:40px;text-align: center;">Tgl Cair</th>
						<th style="width:50px;text-align:center;">Tgl Setor</th>
						<th style="text-align:center;">No. BAP</th>
						<th style="text-align:center;">Uraian</th>

						<th style="text-align:center;">
							Netto Share Termin
							<?=$dapat_member_dari_job['Member1']?> (Rp) (Leader)
						</th>

						<th style="text-align:center;">Netto Share Termin <?=$dapat_member_dari_job['Member2']?> (Rp) (Member)</th>

						<th style="text-align:center;">Netto Cadangan KSO <?=$dapat_member_dari_job['Member1']?> (Rp)</th>

						<th style="text-align:center;">Netto Cadangan KSO <?=$dapat_member_dari_job['Member2']?> (Rp)</th>

						<!-- <th>Action</th> -->
					</tr>
				</thead>
				<tbody>
					<?php
					$nomor = 1;
					$sum_NettoShare_Leader=0;
					$sum_NettoShare_Member=0;
					$sum_NettoCadanganKSO_1=0;
					$sum_NettoCadanganKSO_2=0;

					foreach ($dataTerminMember as $dataMember) { 

						$LedgerNo = $dataMember['LedgerNo'];

						$sum_NettoShare_Leader += (float) $dataMember['TerminMember1'];
						$sum_NettoShare_Member += (float) $dataMember['TerminMember2'];
						$sum_NettoCadanganKSO_1 += (float) $dataMember['CadanganKSO'];
						$sum_NettoCadanganKSO_2 += (float) $dataMember['CadanganKSOMember1'];

						?>
						<tr>
							<td style="text-align:center;"><?=$nomor++?></td>
							<td style="text-align:center;">
			

								<button id="btn_edit_termin_member<?=$LedgerNo?>"  data-JobNo="<?=$JobNo?>" data-LedgerNo="<?=$dataMember['LedgerNo']?>" class="btn btn-sm btn-default text-blue " style="text-decoration: underline;" title="button Lihat / Edit" onClick="EditTerminMember(<?=$LedgerNo?>)" >LIHAT <i class="fa fa-pencil"></i></button>
								<?php if($dataMember['PDFFile'] != NULL) : ?>
									<button class="btn btn-sm btn-danger btn-showpdf" data-urlfile="<?= $dataMember['PDFFile'] ?>">PDF<i class="fa fa-file-o"></i></button>
								<?php else : ?>
									<button class="btn btn-sm btn-danger" disabled>PDF <i class="fa fa-file-o"></i></button>
								<?php endif; ?>


							</td>
							<td style="text-align:left;"><?=$dataMember['JobNo'].' - '.$dapat_member_dari_job['JobNm']?></td>
							<td style="text-align:center;"><?=tgl_baru_tahun_pendek($dataMember['TglCair'])?></td>
							<td style="text-align:center;"><?=tgl_baru_tahun_pendek($dataMember['TglSetor'])?></td>
							<td style="text-align:left;"><?=$dataMember['NoBAP']?></td>
							<td style="text-align:left;"><?=$dataMember['Uraian']?></td>
							<td style="text-align:right;"><?=new_decimal($dataMember['TerminMember1'])?></td>
							<td style="text-align:right;"><?=new_decimal($dataMember['TerminMember2'])?></td>

							<td style="text-align:right;"><?=new_decimal($dataMember['CadanganKSO'])?></td>
							<td style="text-align:right;"><?=new_decimal($dataMember['CadanganKSOMember1'])?></td>

						</tr>
					<?php } ?>
					
				</tbody>
				<tfoot>
					<tr>
						<th class="text-left" colspan="7">TOTAL</th>
						<th class="text-right"><?=number_format($sum_NettoShare_Leader)?></th>
						<th class="text-right"><?=number_format($sum_NettoShare_Member)?></th>
						<th class="text-right"><?=number_format($sum_NettoCadanganKSO_1)?></th>
						<th class="text-right"><?=number_format($sum_NettoCadanganKSO_2)?></th>
					</tr>
				</tfoot>

			</table>


		</div>
	</div>

	<div class="overlay loading_termin_member" style="display: none;">
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
	$(function(){
		
		$('#table_termin_member').DataTable({
			"ordering": false
		});

		$('#mybtn_tambah_termin_member').click(function(){
			var JobNo = $(this).attr('data-JobNo');
			$('#MyModalTerminInduk_Body').html('');


			jQuery.ajax({
				url: '<?=site_url('DipaTermin/PenerimaanTermin/dapat_form_tambah_termin_member')?>',
				type: 'POST',
				dataType: 'html',
				async: true,
				cache : false,
				data: {'JobNo' : JobNo},

				beforeSend : function(){
					// $('.loading_termin_member').show();
					$('#mybtn_tambah_termin_member').attr('disabled',true).text('LOADING....');
				},
				complete: function(xhr, textStatus) {
					$('#mybtn_tambah_termin_member').attr('disabled',false).text('TAMBAH TERMIN MEMBER');
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

		// 	jQuery.ajax({
		// 		url: '<?=site_url('DipaTermin/PenerimaanTermin/form_edit_termin_member')?>',
		// 		type: 'POST',
		// 		dataType: 'HTML',
		// 		async: true,
		// 		cache : false,
		// 		data: {
		// 			'JobNo' : JobNo,
		// 			'LedgerNo' : LedgerNo
		// 		},
				
		// 		beforeSend : function(){
		// 			$('.loading_termin_member').show();
		// 		},
		// 		complete: function(xhr, textStatus) {
		// 			$('.loading_termin_member').hide();
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
			var url = "<?=site_url('images/termin_member/')?>"+urlfile;
			console.log(url)
			$('#embedPDF').attr('src',url);
			$('#modalShowPDF').modal('show');
		})

	});

	function EditTerminMember(LedgerNo){
		var JobNo = $('#btn_edit_termin_member'+LedgerNo).attr('data-JobNo');
		var LedgerNo = $('#btn_edit_termin_member'+LedgerNo).attr('data-LedgerNo');

		jQuery.ajax({
			url: '<?=site_url('DipaTermin/PenerimaanTermin/form_edit_termin_member')?>',
			type: 'POST',
			dataType: 'HTML',
			async: true,
			cache : false,
			data: {
				'JobNo' : JobNo,
				'LedgerNo' : LedgerNo
			},

			beforeSend : function(){
				$('.loading_termin_member').show();
			},
			complete: function(xhr, textStatus) {
				$('.loading_termin_member').hide();
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