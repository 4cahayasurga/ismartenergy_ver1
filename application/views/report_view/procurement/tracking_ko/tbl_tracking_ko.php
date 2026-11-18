<style type="text/css">
	

	/*.atur_th{
		font-size: 10px !important;
		font-weight: 700 !important;
		padding-top: 10px !important;
		padding-bottom: 10px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}*/
	/*.atur_td_saya{
		font-size: 10px !important;
		font-weight: 700 !important;
		padding-top: 10px !important;
		padding-bottom: 10px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	.atur_td{
		font-size: 10px;
		padding-bottom: 30px !important;
		padding-top: 5px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}


	.atur_tr{
		border-top: 1px solid gray;
		border-bottom: 1px solid gray;

	}*/

	/*#sub_tracking{
		font-size: 10px;
		font-family: "Franklin Gothic Medium", "Franklin Gothic", "ITC Franklin Gothic", Arial, sans-serif;
		border-bottom: 1px solid silver;
		text-align: center;
	}
*/

	#tbl_tracking{
		font-size: 12px;
		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
		border-bottom: 1px solid;
	}

	#atur_th{
		padding-top: 10px !important;
		padding-bottom: 10px !important;
	}
	#atur_td{
		padding-top: 10px !important;
		padding-bottom: 10px !important;
	}
</style>

<div class="row">
	<div class="col-sm-12">
		
		<div class="box">
			<div class="box-header" >
				<a href="<?= site_url('MyReport/Procurement/Tracking_KO/pdf_tracking_ko/' . $JobNo . '/' . $Status) ?>" target="_blank" class="btn btn-sm btn-danger btn_print">PRINT PDF <i class="fa fa-print"></i></a>
			</div>

			<div class="box-body">
				
				<div class="table-responsive">
					
					<table id="tbl_tracking" width="100%" border="0">

						<tr style="border-bottom:1px solid;">
							<th id="atur_th" style="width: 30px !important;text-align: center;" >No</th>
							<th style="width:7%;">No KO</th>
							<th style="width:7%;">Tanggal</th>
							<th>Vendor</th>
							<th style="width:7%;text-align: right;">Nilai KO (Rp)</th>
						</tr>




						<?php
						$nomor_urut = 1;
						

						$NoKO = NULL;
						$TglKO = NULL;
						$VendorNm = NULL;
						$TotalKO = 0;
						$myarray = array();

						foreach($dataTrackingKO as $mydata) : 

							$NoKO = $mydata['NoKO'];
							$TglKO = $mydata['TglKO'];
							$VendorNm = $mydata['VendorNm'];
							$TotalKO = $mydata['SubTotal'] - $mydata['DiscAmount'] + $mydata['PPN'];

							?>	
							<tr >

								<td id="atur_td" style="width:30px;text-align: center;"><?=$nomor_urut++;?></td>
								<td ><?=$NoKO?></td>
								<td ><?=tgl_baru($TglKO);?></td>
								<td ><?=$VendorNm?></td>
								<td style="width:7%;text-align: right;" ><?=UangBiasa($TotalKO)?></td>

							</tr>

							<?php
							if($Status <> 'All_Status' ){

								$myquery = $this->db->query("SELECT TOP 1 * FROM TraceKO WHERE NoKO='$NoKO' ORDER BY Tanggal DESC");

								if ($myquery->num_rows() > 0) {

									$row_myquery = $myquery->row();
									$nomor_urut_detail = 1;

									// if ($Status == $row_myquery->Status) {


									$myquery_detail = $this->db->query("SELECT * FROM TraceKO WHERE NoKO='$NoKO' ");
									if ($Status == $myquery_detail->row()->Status) {
										?>

										<tr>
											<td></td>
											<td colspan="4" >
												<table border="0" width="80%" style="border:1px solid silver;">
													<tr style="border-bottom:1px solid silver;">
														<td style="text-align:center;padding-top: 5px;padding-bottom: 5px;width: 3%;">No</td>
														<td>Keterangan</td>
														<td style="width:7%;">Tanggal</td>
														<td style="width:7%;">Status</td>
													</tr>
													<?php
													foreach($myquery_detail->result_array() as $mydetail) :
														
														?>
														<tr>
															<td style="text-align:center;padding-top: 5px;padding-bottom: 5px;"><?=$nomor_urut_detail++?></td>
															<td><?=$mydetail['Keterangan']?></td>
															<td><?=tgl_baru($mydetail['Tanggal'])?></td>
															<td><?=($mydetail['Status'] == 0) ? 'In Process' : 'Delivered' ?></td>
														</tr>

													<?php endforeach;?>
												</table>
												
											</td>
										</tr>

										<?php
									}

								}

							}else{
								$myquery = $this->db->query("SELECT * FROM TraceKO WHERE NoKO='$NoKO' ");
								// print_rr($myquery->result());

								if ($myquery->num_rows() > 0) :
									$nomor_urut_detail = 1;
								?>

									<tr>
										<td></td>
										<td colspan="4" >
											<table border="0" width="90%" style="border:1px solid silver;">
												<tr style="border-bottom:1px solid silver;">
													<td style="text-align:center;padding-top: 5px;padding-bottom: 5px;width: 3%;">No</td>
													<td>Keterangan</td>
													<td style="width:7%;">Tanggal</td>
													<td style="width:7%;">Status</td>
												</tr>
												<?php
												foreach($myquery->result_array() as $mydetail) :

													?>
													<tr>
														<td style="text-align:center;padding-top: 5px;padding-bottom: 5px;"><?=$nomor_urut_detail++?></td>
														<td><?=$mydetail['Keterangan']?></td>
														<td><?=tgl_baru($mydetail['Tanggal'])?></td>
														<td><?=($mydetail['Status'] == 0) ? 'In Process' : 'Delivered' ?></td>
													</tr>

												<?php endforeach;?>
											</table>

										</td>
									</tr>

								<?php endif; ?>

							<?php } ?>



					<?php endforeach; ?>

				</table>

			</div>

		</div>
	</div>

</div>
</div>

<script type="text/javascript">
	$(function(){
		// $('#tbl_tracking').DataTable({
		// 	'ordering' : false,
		// 	'pageLength': 100,
		// 	'lengthMenu': [5, 10, 20, 50, 100, 200, 500],
		// });
	});
</script>