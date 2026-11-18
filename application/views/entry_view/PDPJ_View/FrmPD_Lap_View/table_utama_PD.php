<!-- <p></p> -->
<?php
error_reporting(E_ALL);
?>
<hr>
<style type="text/css">
	.fontbuatan{
/*		font-family: Arial, Helvetica, sans-serif;*/
		font-size: 12px !important;
	}
	.font13{
		font-size: 13px !important;
	}

	.font14{
		font-size: 14px !important;
	}

</style>
<div class="row">
	<?=($this->session->userdata('message'))  ? $this->session->userdata('message') : '' ?> 
	<div class="col-sm-12 " style="border:0px solid;">
		<div class="table-responsive" >
			<table width="100%">
				<tr>
					<td>

						<button type="button" data-url="<?=site_url('Entry/PDPJ/FrmPDLap/FrmAddNewPD/'.$JobNo.'/'.$Alokasi.'/PD')?>" class="btn btn-md bg-gray color-pallete btn_tambahPD" title="FORM TAMBAH PERMINTAAN DANA LAPANGAN" 
							data-TipeManajerial="<?=$TipeManajerial?>" 
							data-JobNm="<?=$JobNo.' - '.$JobNm?>"
							data-StatusJob="<?=$StatusJob?>"
							data-KSO="<?=$KSO?>" 
							data-JobNo="<?=$JobNo?>"
							>TAMBAH PD <i class="fa fa-plus"></i></button>

							<button type="button" data-url="<?=site_url('Entry/PDPJ/FrmPDLap/FrmAddNewPD/'.$JobNo.'/'.$Alokasi.'/PDRKD')?>" class="btn btn-md bg-gray color-pallete btn_tambahPDRKD" title="FORM TAMBAH PERMINTAAN DANA LAPANGAN" 
								data-TipeManajerial="<?=$TipeManajerial?>" 
								data-JobNm="<?=$JobNo.' - '.$JobNm?>"
								data-StatusJob="<?=$StatusJob?>"
								data-KSO="<?=$KSO?>" 
								data-JobNo="<?=$JobNo?>"
								>TAMBAH PDRKD <i class="fa fa-plus"></i></button>

								<button type="button" data-url="<?=site_url('Entry/PDPJ/FrmPDLap/FrmAddNewPD/'.$JobNo.'/'.$Alokasi.'/PDMIX')?>" class="btn btn-md bg-gray color-pallete btn_tambahPDMIX" title="FORM TAMBAH PERMINTAAN DANA LAPANGAN" 
									data-TipeManajerial="<?=$TipeManajerial?>" 
									data-JobNm="<?=$JobNo.' - '.$JobNm?>"
									data-StatusJob="<?=$StatusJob?>"
									data-KSO="<?=$KSO?>" 
									data-JobNo="<?=$JobNo?>" style="display: none;" 
									
									>Tambah PDMIX <i class="fa fa-plus"></i></button>
								</td>
								<td class="text-bold text-right text-blue" style="font-size:16px;">SUM TOTAL = <?=number_format($sumTotalPD)?></td>
							</tr>
						</table>
						<hr>
						<table class="table table-striped table-bordered table-hover table-condensed" id="mytable" style="border:0px solid;">
							<thead >
								<tr class="font13" style="color:White;background-color:#5D7B9D;font-weight:bold;">
									<th  class="">No.</th>
									
									<th class="">No PD</th>
									<th class="">No. Ref Lapangan</th>
									<th class="">Tgl PD</th>
									<th class="">Alokasi</th>
									<th class="">Tipe Form</th>
									<th class="">No KO</th>
									<th class="">Deskripsi</th>
									<th class="text-right" >Total PD (Rp)</th>
									<th class="">Approved By (Keuangan)</th>
									<th class="">Approved On</th>
									<th class="text-center " >Action</th>
								</tr>
							</thead>
							<tbody  class="fontbuatan">
								<?php 
								$NoUrut = 1;
								foreach ($dataPD as $row): ?>
									<tr>
										<td  ><?=$row->LedgerNo?></td>
										
										<td  width="100px"><?=$row->NoPD?></td>
										<td  width="100px"><?=$row->NoRef?></td>
										<td width="80px" ><?=$row->TglPD?></td>
										<td  width="100px"><?=$row->Alokasi?></td>
										<td  width="100px"><?=$row->TipeForm?></td>
										<td  width="100px"><?=$row->NoKO?></td>
										<td ><?=$row->Deskripsi?></td>
										<td  class="text-right" width="100px"><?=$row->TotalPD?></td>
										<td  width="100px"><?=$row->ApprovedByAK?></td>
										<td  width="100px"><?=$row->TimeApprovedAK?></td>
										<td class="text-center" width="300px" >
											<a href="<?=site_url('Entry/PDPJ/FrmPDLap/FrmSelectPD/'.$JobNo.'/'.$Alokasi.'/'.$row->NoPD)?>" class="btn btn-md btn-link text-bold"><u>SELECT</u></a>

											<button class="btn btn-md btn-link text-bold" onclick="btn_approve(<?=$row->LedgerNo?>)" 
												id="btn_approveID<?=$row->LedgerNo?>" 
												type="button" style="text-decoration: underline;" 
												data-NoPD="<?=$row->NoPD?>" 
												data-TxtApprove="<?=($row->ApprovedByAK == NULL || $row->ApprovedByAK == '') ? 'APPROVE' : 'UNAPPROVE' ?>"  ><?=($row->ApprovedByAK == NULL || $row->ApprovedByAK == '') ? 'APPROVE' : 'UNAPPROVE' ?></button>

											<button type="button"  class="btn btn-md btn-link text-bold" style="font-size:13px;text-decoration: underline;" id="PrintID<?=$row->NoPD?>" data-url="<?=site_url('MY_PD/PD_PDF_ALL/ExpPDF_PD/'.$row->LedgerNo.'/'.$JobNo.'/'.$Alokasi.'/'.$row->NoPD.'/0/0/')?>" onclick="AksiPrint('<?=$row->NoPD?>')">PRINT</button>

										</td>
									</tr>
								<?php endforeach ?>
							</tbody>



						</table>
					</div>
				</div>
			</div>

			<script>
				$(document).ready(function() {

					var table = $('#mytable').DataTable( {
						// scrollY:        "350px",
						// scrollX:        true,
						// scrollCollapse: true,
						paging:         true,
						// fixedColumns:   {
						// 	left: 3,
						// },
						columnDefs: [
							{ targets: 0, visible: false },
							{ targets: 1, orderable : false},
							{ targets: 2, orderable : true},
							{ targets: 3, orderable : true},
							{ targets: 5, orderable : false},
							{ targets: 6, orderable : false},
							{ targets: 7, orderable : true},
							{ targets: 8, orderable : false},
							{ targets: 9, orderable : false},
							{ targets: 10, orderable : false},
							],
						order: [[0, 'desc']],
					} );

					$('.btn_tambahPD').click(function(){
						var url = $(this).attr('data-url');
						var TipeManajerial = $(this).attr('data-TipeManajerial');

						var JobNm = $(this).attr('data-JobNm');

						if(TipeManajerial == 'JO Integrated'){
							Swal.fire(
								'Alert!',
								JobNm+' merupakan JO Integrated ',
								'info'
								)
							return false;
						}
						window.location.href=url;
					})

					$('.btn_tambahPDRKD').click(function(){
						var url = $(this).attr('data-url');
						var StatusJob = $(this).attr('data-StatusJob');
						var KSO = $(this).attr('data-KSO');
						var JobNm = $(this).attr('data-JobNm');


						if(StatusJob == 'FHO'){
							Swal.fire(
								'Alert!',
								'Job : '+JobNm+' Status = '+StatusJob,
								'info'
								)
							return false;
						}

						if(KSO != '1'){
							Swal.fire(
								'Alert!',
								JobNm+' bukan KSO',
								'info'
								)
							return false;
						}

						window.location.href=url;
					})

					$('.btn_tambahPDMIX').click(function(){
						var url = $(this).attr('data-url');
						var StatusJob = $(this).attr('data-StatusJob');
						var KSO = $(this).attr('data-KSO');
						var JobNm = $(this).attr('data-JobNm');

						if(StatusJob == 'FHO'){
							Swal.fire(
								'Alert!',
								'Job : '+JobNm+' Status = '+StatusJob,
								'info'
								)
							return false;
						}

						if(KSO != '1'){
							Swal.fire(
								'Alert!',
								JobNm+' bukan KSO',
								'info'
								)
							return false;
						}

						window.location.href=url;
					})



				} );

				function btn_approve(LedgerNo=''){
						if (LedgerNo == '') {
							return false;
						}

						var NoPD = $('#btn_approveID'+LedgerNo).attr('data-NoPD');
						var TxtApprove = $('#btn_approveID'+LedgerNo).attr('data-TxtApprove');


						if( confirm("Anda yakin ingin "+TxtApprove+" "+NoPD+" ?",)) {
							jQuery.ajax({
								url: '<?= site_url('Entry/PDPJ/FrmPDKeu/AksiApprove') ?>',
								type: 'POST',
								dataType: 'HTML',
								data: {
									'NoPD': NoPD,
									'TxtApprove' : TxtApprove,
								},
								beforeSend : function(){
									$('#btn_approveID'+NoPD).text('Loading...').attr('disabled',true);
								},
								complete: function(xhr, textStatus) {
									$('#btn_approveID'+NoPD).text(TxtApprove).attr('disabled',false);
									refreshTablePD();
								},
								success: function(data, textStatus, xhr) {
										// alert(data);
									if(data == 'berhasil'){
										toastr.success('', TxtApprove);
									}else if(data == 'gagal'){
										toastr.info('terjadi kesalahan', 'FAILED!');
									}else{
										toastr.info(data, 'Info!');
									}


								},
								error: function(xhr, textStatus, errorThrown) {
									toastr.error('', 'error');
								}
							});
						}
					}

				function AksiPrint(NoPD){

					var TxtUrl = $('#PrintID'+NoPD).attr('data-url');


					jQuery.ajax({
						url: "<?=site_url('Entry/PDPJ/FrmPDLap/AksiPrint')?>",
						type: "POST",
						dataType: "HTML",
						data: {NoPD: NoPD},
						beforeSend : function(){
							$('#PrintID'+NoPD).attr('disabled',true);
						},
						complete: function(xhr, textStatus) {
							$('#PrintID'+NoPD).attr('disabled',false);

						},
						success: function(data, textStatus, xhr) {

							if (data == 'berhasil') {
					// window.open(TxtUrl);
								window.location.href=TxtUrl;
								return false;
							}

							swal("Info!", data, "info");
							return false;

						},
						error: function(xhr, textStatus, errorThrown) {
							swal("Info!", "Terjadi Kesalahan", "error");
						}
					});
				}
			</script>
