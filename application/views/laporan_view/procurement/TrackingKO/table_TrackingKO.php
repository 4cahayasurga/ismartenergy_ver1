<style type="text/css">
	.tulisan{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
	.font_10{
		font-size: 12px;
	}
	.font_11{
		font-size: 13px;
	}
</style>
<div class="row">
	<div id="col-sm-12">
		<div class="box box-primary">
			<div class="box-header type">
				<a href="<?=site_url('Laporan/Procurement/FrmTrackingKO/ExportPDF_TrackKO/'.$JobNo.'/'.$Status)?>" class="btn btn-danger btn-sm" target="_blank">Export PDF <i class="fa fa-print"></i></a>
			</div>
			<div class="box-body">

				<div class="table-responsive">
					
					<table width="100%" border="0" id="mytable" class="table table-striped table-hover table-condensed tulisan font_10">

						<thead>
							<tr>
								<th >No.</th>
								<th  >No KO</th>
								<th  >Tanggal</th>
								<th  >Vendor</th>
								<th  >Nilai KO (Rp)</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($arrayTracking AS $data) { ?>
								<tr>
									<td ><?=$data->NoUrut?></td>
									<td  ><?=$data->NoKO?></td>
									<td  ><?=tgl_baru($data->TglKO)?></td>
									<td  ><?=$data->VendorNm?></td>
									<td  ><?=number_format($data->TotalKO)?></td>
								</tr>

								<?php if ($data->JumlahDetail > 0): ?>
									<tr>
										<td></td>
										<td colspan="4">
											<table width="70%" border="0" style="border-collapse:collapse;border:1px solid;">
												<tr>
													<th style="text-align:center;padding-left:3px;padding-right: 3px;width: 5%;padding-top:5px;padding-bottom:5px;border-bottom:1px solid;">No.</th>
													<th style="width: 10%;border-bottom:1px solid;" >No KO</th>
													<th style="border-bottom:1px solid;" >Keterangan</th>
													<th style="width: 10%;border-bottom:1px solid;">Tanggal</th>
													<th style="width: 10%;border-bottom:1px solid;">Status</th>
												</tr>
												<?php 
												$NoUrutDetail = 1;
												foreach($data->arrayDetail_Tracking as $detail) : ?>
													<tr>
														<td style="text-align:center;padding-top:5px;padding-bottom:5px;"><?=$NoUrutDetail++;?></td>
														<td><?=$detail['NoKO']?></td>
														<td><?=$detail['Keterangan']?></td>
														<td><?=tgl_baru($detail['Tanggal'])?></td>
														<td><?=$detail['Status']?></td>
													</tr>
												<?php endforeach; ?>
										</table>
									</td>
								</tr>
							<?php endif ?>

						<?php } ?>
					</tbody>


				</table>

			</div>


		</div>
	</div>
</div>
</div>