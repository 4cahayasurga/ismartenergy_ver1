<style type="text/css">
	#mytable thead tr th{
		font-size: 14px !important;
	}
	#mytable tbody tr td{
		font-size: 14px !important;
	}

</style>
<div class="row">
	<div class="col-sm-12">
		<div class="box">
			<div class="box-body">
				<div class="table-responsive">
					<table width="100%">
						<tr>
							<td width="100px">
								<a href="<?=site_url('Entry/Procurement/FrmClosingKO_C/ExportExcel_dataPenutupanKO/'.$JobNo)?>" target="_blank" class="btn btn-default btn-md" >Export To Excel</a></td>
							
							<td></td>
						</tr>
					</table>
					<p></p>
					<table id="mytable" class="table table-striped table-hover table-condensed table-bordered " style="margin-top: 20px;">
						<thead>
							<tr>

								<th class="text-center tengah"></th>
								<th class="text-center tengah">No KO</th>
								<th class="text-center tengah" width="80px">Tgl KO</th>
								<th class="text-center tengah">Vendor</th>
								<th class="text-center tengah">Tipe KO</th>
								<th class="text-right right" width="150px">Total KO (Rp)</th>
								<th class="text-right right" width="150px">Telah Terbayar (Rp)</th>
								<th class="text-left tengah">Alasan Penutupan</th>
								<th class="text-left tengah">KO Ditutup Oleh </th>
								<th class="text-left tengah">KO Ditutup Pada</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$NoUrut = 1;
							$sumTotalKO = 0;
							$sumPaymentAmount = 0;
							foreach ($ListTable as $row) :
								$NoKO = ShowKodeAman($row->NoKO);
								$VendorNm = ShowKodeAman($row->VendorNm);

								$url_Validasi = '';
								if ($row->KategoriId=='KONTRAK') {
									$url_Validasi = site_url('Kontrak_po/detail_ko/'.$row->LedgerNo.'/KONTRAK/'.$row->JobNo);
								}else{
									$url_Validasi = site_url('Kontrak_po/detail_ko/'.$row->LedgerNo.'/PO/'.$row->JobNo);
								}


								$sumTotalKO += $row->TotalKO;
								$sumPaymentAmount += $row->PaymentAmount;
								?>
								<tr>
									<td class="text-center" width="200px">
										<a href="<?=$url_Validasi?>" class="btn btn-link btn-md" style="font-size: 16px !important;" ><u>SELECT</u></a>

										<button class="btn btn-link btn-md" id="btnCloseID<?=$NoKO?>" style="font-size: 16px !important;" onclick="btnClose('<?=$NoKO?>')"  data-VendorNm="<?=$VendorNm?>" data-JobNo="<?=$JobNo?>" ><u>CLOSE</u></button>

									</td>
									<td><?=ShowKodeAman($row->NoKO)?></td>
									<td><?=ShowKodeAman(tgl_baru($row->TglKO))?></td>
									<td><?=ShowKodeAman($row->VendorNm)?></td>
									<td><?=ShowKodeAman($row->KategoriId)?></td>
									<td class="text-right"><?=ShowKodeAman(number_format($row->TotalKO))?></td>
									<td class="text-right"><?=ShowKodeAman(number_format($row->PaymentAmount))?></td>
									<td><?=ShowKodeAman($row->ClosedReason)?></td>
									<td><?=ShowKodeAman($row->ClosedBy)?></td>
									<td><?=ShowKodeAman(tgl_baru($row->TimeClosed))?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="6" class="text-right text-bold huruf15">Sum=<?=number_format($sumTotalKO)?></td>
								<td class="text-right text-bold huruf15">Sum=<?=number_format($sumTotalKO)?></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$('#mytable').DataTable();
})
</script>


