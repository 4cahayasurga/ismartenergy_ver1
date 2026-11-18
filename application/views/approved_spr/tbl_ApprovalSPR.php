<style type="text/css">
	#tblutamaSPR{
		font-size: 12px;
		font-family: Arial, Helvetica, sans-serif;
	}

</style>
<div class="row" >
	<div class="col-sm-12" style="padding-bottom: 10px;display: none;">
		<!-- <a href="<?=site_url('Spr/add/'.$JobNo)?>" class="btn btn-default btn-md text-bold">TAMBAH DATA</a> -->
		<!-- <a href="<?=site_url('FrmSPR/FrmAdd_SPR/'.$JobNo)?>" class="btn btn-primary btn-md text-bold">TAMBAH DATA</a> -->
	</div>
	<!-- <hr> -->
</div>
<div class="row">

	<!-- <p></p> -->
	<!-- <p></p> -->
	<div class="col-sm-12">

		<div class="table-responsive">
			<table class="table table-bordered table-striped table-hover" id="tblutamaSPR">
				<thead class="bg-navy disabled color-palette">
					<tr>
						<th class="text-center" >No</th>
						<th class="text-center" >No SPR</th>
						<th class="text-center" >Tgl SPR</th>
						<th class="text-center">Untuk Pekerjaan</th>
						<th class="text-center">SPR Approved By</th>
						<th class="text-center">SPR Approved On</th>
						<th class="text-center"></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$NoUrut = 1;
					foreach ($dataPRHdr as $row): 
						$TxtApprove = '';
						if (ShowKodeAman($row->ApprovedBy) == NULL || ShowKodeAman($row->ApprovedBy) == '') {
							$TxtApprove = 'APPROVE';
						}else{
							$TxtApprove = 'UNAPPROVE';
						}
						?>
						<tr>
							<td><?=$NoUrut++?></td>
							<td><?=ShowKodeAman($row->NoSPR)?></td>
							<td><?=ShowKodeAman(tgl_baru($row->TglSPR))?></td>
							<td><?=ShowKodeAman($row->UtkPekerjaan)?></td>
							<td><?=ShowKodeAman($row->ApprovedBy)?></td>
							<td><?=tgl_baru_tahun_pendek_dengan_waktu(ShowKodeAman($row->TimeApproved))?></td>
							<td class="text-center">

								<button style="text-decoration: underline;" type="button" class="btn btn-link btn-md" onclick="getDetailSingkat('<?=$row->JobNo?>','<?=$row->NoSPR?>')" >SELECT</button>
								
								<button type="button" class="btn btn-link btn-md btn_approve" id="SPRID<?=$row->NoSPR?>" 
									data-NoSPR="<?=ShowKodeAman($row->NoSPR)?>" 
									data-TxtApprove="<?=$TxtApprove?>" style="text-decoration: underline;" ><?=$TxtApprove?></button>







									</td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<script type="text/javascript" src="<?=base_url('assets/swal2_terbaru.js')?>" ></script>

		<script type="text/javascript">
			$(function(){
				var table = $('#tblutamaSPR').DataTable();

				$('.btn_approve').click(function(){


					var NoSPR = $(this).attr('data-NoSPR');
					var TxtApprove = $(this).attr('data-TxtApprove');

					if (NoSPR =='') {
						$.toast({
							text: 'error',
							position: 'top-right',
							icon : 'error'
						});
						return false;
					}

					jQuery.ajax({
						url: '<?=site_url("Approved_spr/CekNoSPR")?>',
						type: 'POST',
						dataType: 'html',
						data: {NoSPR : NoSPR, TxtApprove : TxtApprove},
						beforeSend : function(){
							$('#SPRID'+NoSPR).text('Loading..').attr('disabled',true);
						},
						complete : function(){
							$('#SPRID'+NoSPR).text(TxtApprove).attr('disabled',false);
						},
						success: function(data, textStatus, xhr) {
							if (data == 'ada_ko') {
								$('#ModalNotif').modal('show');
								return false;
							}

							AksiApprove(NoSPR,TxtApprove)

						},
						error: function(xhr, textStatus, errorThrown) {
							$.toast({
								text: 'terjadi kesalahan',
								position: 'top-right',
								icon : 'error'
							});
						}
					});

				});




			})
		</script>