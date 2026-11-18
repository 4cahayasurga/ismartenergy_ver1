<style type="text/css">
	.atur_td_detail{
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}
	.kanan{
		text-align: right;
	}

	.aturpading{
		padding-top: 5px;
		padding-bottom: 5px;
	}
	.paddingkiri{
		padding-left: 10px !important;
	}

	.border_bawah{
		border-bottom: 1px solid black;
	}
</style>



<div class="col-12">

	<div class="box">

		<div class="box-body">

			<div class="table-responsive">

				<table width="100%" border="0">
						<tr>
							<td style="padding-top:10px;padding-bottom:10px;">
								<a href="<?=site_url('MyReport/Procurement/RekapInvoice_Supplier/DataList_InvSupplier/'.$dari_tgl.'/'.$sampai_tgl.'/'.$JobNo.'/'.$VendorId.'/'.$SortBy)?>" class="btn btn-sm btn-danger" target="_blank">PDF Export &nbsp; <i class="fa fa-print"></i></a>
							</td>
						</tr>
					</table>


				<table class="pengaturan_tbl table table-borderless table-striped table-hover table-condensed" style="width: 100%;border-collapse: collapse;" border="0">
					
					<tr class="huruf_set border_bawah">
						<th style="width: 1%;text-align: center;" class="border_bawah">No</th>
						<th style="width: 12%;text-align: left;" class="border_bawah" >Supplier</th>
						<th style="width: 4%;text-align: left;" class="border_bawah" >Job No</th>
						<th style="width: 12%;text-align: left;" class="border_bawah" >Proyek</th>
						<th style="width: 5%;text-align: left;" class="border_bawah" >No KO</th>
						<th style="width: 12%;text-align: left;" class="border_bawah" >No Invoice</th>
						<th style="width: 7%;text-align: left;" class="border_bawah" >Tgl Invoice</th>
						<th style="width: 10%;text-align: right;" class="border_bawah" >Nilai Invoice (Rp)</th>
						<th style="width: 3%;text-align: left;" class="border_bawah" >Paraf</th>
						<th style="width: 10%;text-align: left;" class="border_bawah" >No. Faktur Pajak</th>
						<th style="width: 7%;text-align: left;" class="border_bawah" >Tgl Faktur</th>
						<th class="border_bawah">Keterangan</th>
					</tr>
					<?php
					$nomor_urut = 1;
					$sumTotal_Detail = 0;
					foreach ($dataInv as $inv): ?>
						<tr class="huruf_set">
							<td style="width: 1%;text-align: center;"><?=$nomor_urut++;?></td>
							<td><?=$inv['VendorNm']?></td>
							<td><?=$inv['JobNo']?></td>
							<td><?=$inv['JobNm']?></td>
							<td><?=$inv['NoKO']?></td>
							<td><?=$inv['InvNo']?></td>
							<td><?=TanggalSaya($inv['InvDate'])?></td>
							<td align="right"><?=UangBiasa($inv['Total'])?></td>
							<td></td>
							<td><?=$inv['FPNo']?></td>
							<td><?=TanggalSaya($inv['FPDate'])?></td>
							<td><?=$inv['Keterangan']?></td>
						</tr>
						<?php 
						$myquery = $this->db->query("

							SELECT A.NoKO, A.InvNo, A.NoPD,  
							ISNULL((SELECT SUM(PaymentAmount) FROM BLE, InvPD, PdHdr WHERE  
								(BLE.NoPD=PdHdr.NoPD OR BLE.NoPD=PdHdr.NoRef) AND PdHdr.NoKO=InvPD.NoKO AND InvPD.NoPD=PdHdr.NoPD AND  
								InvPD.NoKO=A.NoKO AND InvPD.InvNo=A.InvNo AND InvPD.NoPD=A.NoPD),0) AS BLEAmount,  
							(SELECT TOP 1 TglBayar FROM BLE, InvPD, PdHdr WHERE  (BLE.NoPD=PdHdr.NoPD OR BLE.NoPD=PdHdr.NoRef) AND PdHdr.NoKO=InvPD.NoKO AND InvPD.NoPD=PdHdr.NoPD AND  InvPD.NoKO=A.NoKO AND InvPD.InvNo=A.InvNo AND InvPD.NoPD=A.NoPD) AS TglBayar  
							FROM InvPD A WHERE NoKO='$inv[NoKO]' AND InvNo='$inv[InvNo]' "); 


							if ($myquery->num_rows() > 0) {
								// code...
							
							?>

							<tr>
								<td colspan="12" style="padding-left: 20px !important;">
									<br>
									<table style="border:1px solid black;" width="30%">
										<tr class="atur_td_detail">
											<td class="aturpading paddingkiri border_bawah" >No. PD</td>
											<td class="aturpading kanan border_bawah">Payment Amount (Rp)</td>
											<td class="aturpading border_bawah paddingkiri" >Tgl Bayar</td>
											<td width="5%"></td>
										</tr>
										<?php foreach($myquery->result() as $dtl) {
											$sumTotal_Detail += $dtl->BLEAmount;
											?>
											<tr class="atur_td_detail">
												<td class="aturpading paddingkiri" ><?=$dtl->NoPD?></td>
												<td class="aturpading kanan" ><?=UangBiasa($dtl->BLEAmount)?></td>
												<td class="aturpading paddingkiri"  ><?=TanggalSaya($dtl->TglBayar)?></td>
												<td></td>
											</tr>

										<?php } ?>
										<tr>
											<td></td>
											<td class="atur_td_detail text-bold text-right" style="border-top:1px solid black;padding-bottom:5px !important;padding-top:5px !important;"><?=UangBiasa($sumTotal_Detail)?></td>
											<td></td>
										</tr>
									</table>
									<br>
								</td>
							</tr>

						<?php } ?>

						<?php endforeach; ?>
					</table>


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