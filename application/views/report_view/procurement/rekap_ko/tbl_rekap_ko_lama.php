<style type="text/css">
	.tbl_rap{
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

	.tbl_rap tbody td{
		font-size: 8px;
		padding-bottom: 8px !important;
		padding-top: 8px !important;
	}

</style>

<div class="row">
	<div class="col-sm-12">
		
		<div class="box">
			<div class="box-header">
				<a href="<?= site_url('MyReport/Procurement/Rekap_KO/pdf_rekap_ko/' . $JobNo . '/' . $dari_tgl . '/' . $sampai_tgl . '/' . $semua_periode) ?>" target="_blank" class="btn btn-sm btn-danger btn_print">PRINT PDF <i class="fa fa-print"></i></a>
			</div>

			<div class="box-body">
				
				<div class="table-responsive">
					
					<table class="table table-bordered table-striped table-hover table-condensed tbl_rap">
						
						<tr class="bg-gray" style="font-size:12px !important;">
							<th class="atur_th" style="width:2%;text-align: center;">No</th>
							<th class="atur_th" >No KO</th>
							<th class="atur_th" >Tanggal</th>
							<th class="atur_th">Vendor</th>
							<th class="atur_th" >Kategori</th>
							<th class="atur_th" style="text-align: right;">Nilai KO (Rp)</th>
							<th class="atur_th" style="text-align: right;">Telah Terbayar (Rp)</th>
							<th class="atur_th" style="text-align: right;">Sisa Pembayaran (Rp)*</th>
							<th class="atur_th" style="text-align: center;">Status Closed</th>
						</tr>
						

						
						<?php
						$nomor = 1;
						$NoKO = NULL;
						$jumlah_data_ko = 0;
						foreach($dataRekapKO as $dataRekapKO) { 

							$NoKO = $dataRekapKO['NoKO'];

							$myquery = $this->db->query(" SELECT * FROM BLE WHERE NoKO='$NoKO' AND TglBayar>='$dari_tgl' AND TglBayar<='$sampai_tgl' ORDER BY NoKO ");

							$jumlah_data_ko = $myquery->num_rows();
							
							// $warna_belakang = ($$jumlah_data_ko > 0) ? ''


							?>

							<tr class="<?=($jumlah_data_ko > 0) ? 'bg-navy disabled' : 'text-bold'  ?>" >
								<td><?=$nomor++;?></td>
								<td><?=$dataRekapKO['NoKO']?></td>
								<td><?=tgl_baru($dataRekapKO['TglKO'])?></td>
								<td><?=$dataRekapKO['VendorNm']?></td>
								<td><?=$dataRekapKO['KategoriId']?></td>
								<td style="text-align:right;"><?=UangBiasa($dataRekapKO['SubTotal'])?></td>
								<td style="text-align:right;">0</td>
								<td style="text-align:right;">0</td>
								<td style="text-align:center;"><?=($dataRekapKO['ClosedBy'] !='' OR $dataRekapKO['ClosedBy'] !=NULL ) ? 'Yes' : 'No' ?></td>
							</tr>


							<?php
							
							if ($jumlah_data_ko > 0) {
								// code...

								?>
								<tr>
									<td colspan="8"></td>
								</tr>
								<tr>
									<td style="border-right:1px solid black !important;"></td>
									<td style="border-bottom:1px solid;border:1px solid black !important;width: 8%;">No PD</td>
									<td style="border-bottom:1px solid;border:1px solid black !important;width: 8%;">Tgl Bayar</td>
									<td style="border-bottom:1px solid;border:1px solid black !important;text-align:right;width: 8%;">Nilai (Rp)</td>
									<td style="border-bottom:1px solid;border:1px solid black !important;" colspan="2" >Keterangan</td>
									<!-- <td style="text-align:right;"></td> -->
									<td style="text-align:right;"></td>
									<td style="text-align:right;"></td>
									<td style="text-align:center;"></td>
								</tr>
								<?php
								foreach($myquery->result_array() as $data_detail_ko) { ?>

									<tr>
										<td style="border-right:1px solid black !important;"></td>
										<td style="border:1px solid black !important;"><?=$data_detail_ko['NoPD']?></td>
										<td style="border:1px solid black !important;"><?=tgl_baru($data_detail_ko['TglBayar'])?></td>
										<td style="text-align:right;border:1px solid black !important;"><?=UangBiasa($data_detail_ko['Amount'])?></td>
										<td style="border:1px solid black !important;" colspan="2" ><?=$data_detail_ko['Keterangan']?></td>
										<!-- <td style="text-align:right;"></td> -->
										<td style="text-align:right;" ></td>
										<td style="text-align:right;"></td>
										<td style="text-align:center;"></td>
									</tr>




								<?php } ?>
								<tr>
									<td colspan="8"></td>
								</tr>
							<?php } ?>

						<?php } ?>



					</table>

				</div>

			</div>
		</div>

	</div>
</div>

<script type="text/javascript">
	$(function(){
		// $('.tbl_rap').DataTable({
		// 	'ordering' : false,
		// 	'pageLength': 100,
		// 	'lengthMenu': [5, 10, 20, 50, 100, 200, 500],
		// });
	});
</script>