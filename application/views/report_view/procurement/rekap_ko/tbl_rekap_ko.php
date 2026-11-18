<style type="text/css">
	.tbl_rekap{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		/*white-space: nowrap;*/
	}

	.atur_th{
		font-size: 10px !important;
		font-weight: 700 !important;
		padding-top: 10px !important;
		padding-bottom: 10px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
/*	.tbl_rap thead th{
		
		font-size: 12px !important;
		font-weight: bold;
	}*/

	.tbl_rekap  td{
		font-size: 10px;
		padding-bottom: 5px !important;
		padding-top: 5px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}


	.atur_td{
		font-size: 10px;
		padding-bottom: 5px !important;
		padding-top: 5px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}


	.atur_tr{
		border-top: 1px solid gray;
		border-bottom: 1px solid gray;
		/*border-left: 1px solid gray;*/
		/*border-right: 1px solid gray;*/

	}

</style>

<div class="row">
	<div class="col-sm-12">
		
		<div class="box">
			<div class="box-header" >
				<a href="<?= site_url('MyReport/Procurement/Rekap_KO/pdf_rekap_ko/' . $JobNo . '/' . $dari_tgl . '/' . $sampai_tgl . '/' . $semua_periode) ?>" target="_blank" class="btn btn-sm btn-danger btn_print">PRINT PDF <i class="fa fa-print"></i></a>
			</div>

			<div class="box-body">
				
				<div class="table-responsive">
					
					<table class="table tbl_rekap" width="100%" border="0">
						


						<tr class="bg-gray">

							<th class="atur_th" style="width:2%;">No.</th>

							<th class="atur_th" style="width:7%;">No KO</th>

							<th class="atur_th" style="width:6% !important;">Tanggal</th>

							<th class="atur_th" style="width:6% !important;"	 >Vendor</th>

							<th class="atur_th" style="text-align: right;width:8% !important;">Kategori</th>

							<th class="atur_th" style="text-align: right;width:8% !important;">Nilai KO (Rp)</th>

							<th class="atur_th" style="text-align: right;width:8% !important;">Telah Terbayar (Rp)</th>

							<th class="atur_th" style="text-align: right;width:8% !important;">Sisa Pembayaran (Rp)</th>

							<th class="atur_th" style="text-align: center;width:8% !important;">Status Closed</th>

							
						</tr>
						

						<?php
						$no_urut = 1;
						$ClosedBy = NULL;
						$NoKO = NULL;
						$jumlah_data_ko = 0;

						foreach($dataRekapKO as $data_ko) : ?>
							
						<?php
							$ClosedBy = ($data_ko['ClosedBy'] !='' OR $data_ko['ClosedBy'] !=NULL ) ? 'Yes' : 'No';
							$NoKO = $data_ko['NoKO'];
							$myquery = $this->db->query(" SELECT * FROM BLE WHERE NoKO='$NoKO' AND TglBayar>='$dari_tgl' AND TglBayar<='$sampai_tgl' ORDER BY NoKO ");
							$jumlah_data_ko = $myquery->num_rows();



						?>
							

						

							<tr <?=($jumlah_data_ko > 0 ) ? 'style="color:green;" ' : '' ?> >
								<td ><?=$no_urut++;?></td>

								<td style="font-size: 10px !important;padding-bottom: 5px !important;padding-top: 5px !important;"  ><?=$data_ko['NoKO']?></td>

								<td><?=tgl_baru($data_ko['TglKO']);?></td>

								<td><?=$data_ko['VendorNm']?></td>

								<td><?=$data_ko['KategoriId']?></td>

								<td style="text-align:right;"><?=UangBiasa($data_ko['SubTotal'])?></td>

								<td style="text-align:right;">0</td>

								<td style="text-align:right;">0</td>

								<td style="text-align:center;" ><?=$ClosedBy;?></td>

							</tr>

							<?php if ($jumlah_data_ko > 0): ?>

								<tr><td colspan="9" style="padding-bottom:5px !important;"></td></tr>
								
								<tr style="font-weight: 690 !important;" >

									<td></td>

									<td class="atur_tr" style="padding-left:10px !important;"  >No PD</td>

									<td class="atur_tr" >Tgl Bayar</td>

									<td class="atur_tr" style="text-align: right;padding-right:10px !important;" >Nilai (Rp)</td>

									<td class="atur_tr" colspan="2" style="padding-left:10px !important;" >Keterangan</td>

									<td></td>

									<td ></td>
									<td ></td>
								</tr>

								<?php foreach($myquery->result() as $detail_ko_pd ) : ?>

									<tr >
										<td></td>

										<td class="atur_tr" style="padding-left:10px !important;" ><?=$detail_ko_pd->NoPD?></td>

										<td class="atur_tr"><?=tgl_baru($detail_ko_pd->TglBayar)?></td>

										<td class="atur_tr" style="text-align: right;padding-right:10px !important;" ><?=UangBiasa($detail_ko_pd->Amount)?></td>

										<td class="atur_tr" colspan="2" style="padding-left:10px !important;"  ><?=$detail_ko_pd->Keterangan?></td>

										<td></td>
										<td></td>
										<td></td>
										
									</tr>
									<!-- <tr><td colspan="9" style="padding-top:5px !important;"></td></tr> -->

								<?php endforeach; ?>
								<tr><td colspan="9" style="padding-bottom:5px !important;"></td></tr>
								<!-- <tr><td colspan="9" style="padding-bottom:5px !important;"></td></tr> -->
								<!-- <tr><td colspan="9" style="padding-bottom:5px !important;"></td></tr> -->


							<?php endif; ?>


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