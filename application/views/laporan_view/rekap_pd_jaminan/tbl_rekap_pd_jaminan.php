<style type="text/css">
	.tbl_rekap{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		/*white-space: nowrap;*/
	}

	.atur_th{
		font-size: 10px !important;
		font-weight: bold;
		padding-top: 10px !important;
		padding-bottom: 10px !important;
	}
/*	.tbl_rap thead th{
		
		font-size: 12px !important;
		font-weight: bold;
	}*/

	.tbl_rekap tbody td{
		font-size: 10px;
		padding-bottom: 5px !important;
		padding-top: 5px !important;
	}

</style>

<div class="row">
	<div class="col-sm-12">
		
		<div class="box">
			<div class="box-header" >
				<a href="<?= site_url('Laporan/Report_PD_Jaminan/pdf_rekap_pd_jaminan/'.$JobNo)?>" target="_blank" class="btn btn-sm btn-danger btn_print">PRINT PDF <i class="fa fa-print"></i></a>
			</div>

			<div class="box-body">
				
				<div class="table-responsive">
					
					<table class="table table-striped table-hover table-condensed tbl_rekap" width="100%" >
						
						<tr class="bg-gray">
							<th class="atur_th" style="width:2%;">No.</th>
							<th class="atur_th" style="width:7%;">No PD</th>
							<th class="atur_th" style="width:6% !important;">Tgl PD</th>
							<th class="atur_th" style="width:6% !important;"	 >Tipe Form</th>
							<th class="atur_th" style="text-align: left;">Deskripsi</th>
							<th class="atur_th" style="text-align: right;width:8% !important;">Total PD (Rp)</th>
							<th class="atur_th" style="text-align: right;width:8% !important;">Tgl Bayar</th>
							<th class="atur_th" style="text-align: right;width:8% !important;">Total Pembayaran</th>
							
						</tr>
						

						

						<?php
						$nomor = 1;
						$JobNo = '';
						$total_pd = 0;
						foreach ($data_job as $data_job) :
							$JobNo = $data_job['JobNo'];

							?>
							<tr>
								<td style="text-align:left;padding-top:10px !important;padding-bottom:10px !important;"><?=$nomor++;?></td>
								<td style="text-align:left;padding-top:10px !important;padding-bottom:10px !important;font-weight: bold;" colspan="7" class=""  ><?=$data_job['JobNo'].' - '.$data_job['JobNm']?>
								<?php
								$Company = $this->config->item('Company');
								$dapat_data_pd = $this->db->query("
									SELECT A.*, B.JobNo + ' - ' + B.JobNm AS 'JobNm1',
									(SELECT TOP 1 TglBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'TglBayar',
									(SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'BLEAmount'
									FROM PdHdr A LEFT JOIN Job B ON A.JobNo=B.JobNo WHERE A.Jaminan='1' AND A.ApprovedByKK IS NOT NULL AND 
									B.Company='$Company' AND A.JobNo='$JobNo'  ORDER BY A.NoPD DESC ")->result_array();

									foreach($dapat_data_pd as $data_pd ) {
										$total_pd += $data_pd['BLEAmount'];
									?>
										

										<tr style="padding-left:20px !important;">
											<td></td>
											<td style="padding-left:20px !important;padding-top:10px !important;padding-bottom:10px !important;border:1px solid silver;"><?=$data_pd['NoPD']?></td>
											<td style="border:1px solid silver;"><?=tgl_baru($data_pd['TglPD'])?></td>
											<td style="border:1px solid silver;"><?=$data_pd['TipeForm']?></td>
											<td style="border:1px solid silver;"><?=$data_pd['Deskripsi']?></td>
											<td style="text-align: right;border:1px solid silver;" ><?=UangBiasa($data_pd['TotalPD'])?></td>
											<td style="text-align: right;border:1px solid silver;" ><?=tgl_baru($data_pd['TglBayar'])?></td>
											<td style="text-align: right;border:1px solid silver;" ><?=UangBiasa($data_pd['BLEAmount'])?></td>


										</tr>

									<?php } ?>

								</td>
								<td></td>
								<td colspan="7" style="border:0	px solid;text-align:right;font-size: 10px;font-weight: bold;"><?='SUM = '.UangBiasa($total_pd)?></td>

							</tr>
						<?php endforeach; ?>
						
					</table>

				</div>

			</div>
		</div>

	</div>
</div>

<script type="text/javascript">
	$(function(){
		$('.tbl_rap').DataTable({
			'ordering' : false,
			'pageLength': 100,
			'lengthMenu': [5, 10, 20, 50, 100, 200, 500],
		});
	});
</script>