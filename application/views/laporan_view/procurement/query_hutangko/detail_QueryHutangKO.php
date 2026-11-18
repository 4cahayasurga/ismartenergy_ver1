<!-- <style type="text/css">
	.modal-dialog {
		width: 90%;
		height: auto;
		margin: 0;
		padding: 0;
	}

	.modal-content {
		height: auto;
		min-height: 90%;
		border-radius: 0;
	}
</style> -->
<div class="row">
	<div class="col-12">
		<div class="table-responsive" style="padding-left:20px;padding-right:20px;">
			<small>Detail Data <?= '<b> No.KO : ' . $NoKO . '</b>' ?></small>
			<table border="0" width="100%" style="font-size: 11px;border-collapse: collapse;">
				<thead>
					<tr style="border-bottom:1px solid black;">
						<th style="padding-top:10px;padding-bottom:10px;text-align: center;">No.</th>
						<th style="padding-top:10px;padding-bottom:10px;text-align: center;">No KO</th>
						<th style="padding-top:10px;padding-bottom:10px;text-align: center;">Invoice</th>
						<th style="padding-top:10px;padding-bottom:10px;text-align: center;">Tgl. Invoice</th>
						<th style="padding-top:10px;padding-bottom:10px;text-align: center;">Due Date</th>
						<th style="padding-top:10px;padding-bottom:10px;text-align: center;">Total Invoice (Rp)</th>
						<th style="padding-top:10px;padding-bottom:10px;text-align: center;">PPN (Rp)</th>
						<th style="padding-top:10px;padding-bottom:10px;text-align: center;">No. Faktur Pajak</th>
						<th style="padding-top:10px;padding-bottom:10px;text-align: center;">Tgl Faktur Pajak</th>
						<th style="padding-top:10px;padding-bottom:10px;text-align: center;">Payment Amount (Rp)</th>
					</tr>
				</thead>
				<tbody>

					<?php
					$nomor = 1;
					if (count($detail) == 0) {
						echo "<tr><td colspan='10'>Data tidak ditemukan</td></tr>";
					} else {
						$NoKO = NULL;
						$InvNo = NULL;
						foreach ($detail as $detail) :
							$NoKO = $detail['NoKO'];
							$InvNo = $detail['InvNo'];

					?>
							<tr style="border-bottom:1px solid black;">
								<td style="padding-top:10px;padding-bottom:10px;text-align: center;"><?= $nomor++; ?></td>
								<td style="padding-top:10px;padding-bottom:10px;text-align: center;"><?= $detail['NoKO'] ?></td>
								<td style="padding-top:10px;padding-bottom:10px;text-align: center;"><?= $detail['InvNo'] ?></td>
								<td style="padding-top:10px;padding-bottom:10px;text-align: center;"><?= TanggalSaya($detail['InvDate']) ?></td>
								<td style="padding-top:10px;padding-bottom:10px;text-align: center;"><?= TanggalSaya($detail['DueDate']) ?></td>
								<td style="padding-top:10px;padding-bottom:10px;text-align: center;"><?= new_decimal($detail['Total']) ?></td>
								<td style="padding-top:10px;padding-bottom:10px;text-align: center;"><?= new_decimal($detail['PPN']) ?></td>
								<td style="padding-top:10px;padding-bottom:10px;text-align: center;"><?= $detail['FPNo'] ?></td>
								<td style="padding-top:10px;padding-bottom:10px;text-align: center;"><?= TanggalSaya($detail['FPDate']) ?></td>
								<td style="padding-top:10px;padding-bottom:10px;text-align: center;"><?= new_decimal($detail['PaymentAmount']) ?></td>
							</tr>
							<tr>
								<td colspan="10" style="border-left:0px !important;padding-left:30px;">
									<table border="0" width="50%">
										<tr style="border-bottom:1px solid black;">
											<th style="padding-top:5px;padding-bottom:5px;text-align: left;">Invoice</th>
											<th style="padding-top:5px;padding-bottom:5px;text-align: left;">No. PD</th>
											<th style="padding-top:5px;padding-bottom:5px;text-align: left;">Payment Amount (Rp)</th>
										</tr>
										<?php
										$query_cek_ko = $this->db->query("
											SELECT A.InvNo, A.NoPD,
											ISNULL((SELECT SUM(PaymentAmount) 
												FROM BLE, InvPD, PdHdr 
												WHERE (BLE.NoPD=PdHdr.NoPD OR BLE.NoPD=PdHdr.NoRef) AND 
												PdHdr.NoKO=InvPD.NoKO AND InvPD.NoPD=PdHdr.NoPD AND 
												InvPD.NoKO=A.NoKO AND InvPD.InvNo=A.InvNo AND InvPD.NoPD=A.NoPD),0) AS BLEAmount
											FROM InvPD A 
											WHERE NoKO='$NoKO' AND InvNo='$InvNo'")->result_array();

										foreach ($query_cek_ko as $dataKO) : ?>
											<tr style="border-bottom:1px solid black;">
												<td style="padding-top:5px;padding-bottom:5px;text-align: left;"><?= $dataKO['InvNo'] ?></td>
												<td style="padding-top:5px;padding-bottom:5px;text-align: left;"><?= $dataKO['NoPD'] ?></td>
												<td style="padding-top:5px;padding-bottom:5px;text-align: left;"><?= new_decimal($dataKO['BLEAmount']) ?></td>
											</tr>
										<?php endforeach; ?>

									</table>

								</td>
							</tr>
						<?php endforeach; ?>

					<?php } ?>
				</tbody>
			</table>

			<table border="0" width="100%">
				<tr>
					<td class="text-right">
						<hr>
						<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Tutup</button>
					</td>

				</tr>
			</table>


		</div>
		<!-- <div class="box">
			<div class="box-body">
				<div class="table-responsive">

					<table border="1" width="100%"   style="font-size: 11px;border-collapse: collapse;">
						<thead>
							<tr>
								<th>No KO</th>
								<th>Invoice</th>
								<th>Tgl. Invoice</th>
								<th>Due Date</th>
								<th>Total Invoice (Rp)</th>
								<th>PPN (Rp)</th>
								<th>No. Faktur Pajak</th>
								<th>Tgl Faktur Pajak</th>
								<th>Payment Amount (Rp)</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($detail as $detail) : ?>
								<tr>
									<td><?= $detail['NoKO'] ?></td>
									<td><?= $detail['InvNo'] ?></td>
									<td><?= TanggalSaya($detail['InvDate']) ?></td>
									<td><?= TanggalSaya($detail['DueDate']) ?></td>
									<td><?= new_decimal($detail['Total']) ?></td>
									<td><?= new_decimal($detail['PPN']) ?></td>
									<td><?= $detail['FPNo'] ?></td>
									<td><?= TanggalSaya($detail['FPDate']) ?></td>
									<td><?= new_decimal($detail['PaymentAmount']) ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<table border="0" width="100%">
						<tr>
							<td class="text-right">
								<hr>
								<button type="button" class="btn btn-default btn-sm"  data-dismiss="modal">Tutup</button>
							</td>

						</tr>
					</table>


				</div>
			</div>
		</div>
	</div> -->
	</div>
	<script type="text/javascript">
		$(function() {
			// $('#tableDetail').DataTable();
		})
	</script>