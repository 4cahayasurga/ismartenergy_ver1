<div class="row">
	<div class="col-sm-12">
		<?php
		// echo CekKecepatan();

		?>
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-condensed table-hover" id="my_table_querypdpj">
						<thead>
							<tr>
								<th></th>
								<th>Job No</th>
								<th>No PD</th>
								<th>Tgl PD</th>
								<th>No Ref.Lapangan</th>
								<th>Alokasi</th>
								<th>Tipe Form</th>
								<th>Deskripsi</th>
								<th>No. KO</th>
								<th>Total KO</th>
								<th>No. Tagihan</th>
								<th>Rekening Penerima</th>
								<th>Atas Nama</th>
								<th>Total PD</th>
								<th>Tgl Bayar</th>
								<th>Via</th>
								<th>Total Pembayaran</th>
								<th>Rekening Pengirim</th>
								<th>No PJ</th>
								<th>Tgl PJ</th>
								<th>Total PJ</th>
								<th>Saldo (Rp)</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($query1 as $q1) {
								$NoPD = $q1->NoPD;
							?>
								<tr data-toggle="collapse" data-target="#demo1<?php echo $q1->NoPD ?>" class="accordion-toggle">
									<td><button class="btn btn-default btn-xs"><span class="fa fa-plus-circle"></span></button></td>
									<td><?= $q1->JobNo1 ?></td>
									<td><?= $q1->NoPD ?></td>
									<td><?= TglIndoGarisMiring_tanpa_waktu($q1->TglPD) ?></td>
									<td><?= $q1->NoRef ?></td>
									<td><?= $q1->Alokasi ?></td>
									<td><?= $q1->TipeForm ?></td>
									<td><?= $q1->Deskripsi ?></td>
									<td><?= $q1->NoKO ?></td>
									<td><?= number_format($q1->SubTotal - ($q1->DiscAmount + $q1->PPN)) ?></td>
									<td><?= $q1->NoTagihan ?></td>
									<td><?= $q1->NoRek ?></td>
									<td><?= $q1->AtasNama ?></td>
									<td><?= UbahGabunganUang($q1->TotalPD) ?></td>
									<td><?= TglIndoGarisMiring_tanpa_waktu($q1->TglBayar) ?></td>
									<td><?= $q1->JenisTrf ?></td>
									<td><?= UbahGabunganUang($q1->BLEAmount) ?></td>
									<td><?= $q1->RekId ?></td>
									<td><?= $q1->NoPJ ?></td>
									<td><?= TglIndoGarisMiring_tanpa_waktu($q1->TglPJ) ?></td>
									<td><?= UbahGabunganUang($q1->TotalPJ) ?></td>
									<td><?= UbahGabunganUang($q1->TotalPD - $q1->TotalPJ) ?></td>
								</tr>

								<tr>
									<td colspan="12" class="hiddenRow">
										<div class="accordian-body collapse" id="demo1<?php echo $q1->NoPD ?>">
											<div class="nav-tabs-custom">
												<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
													<li class="nav-item">
														<a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#UraianPD<?php echo $q1->NoPD ?>" role="tab" aria-controls="UraianPD" aria-selected="true">Uraian PD</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#Invoice<?php echo $q1->NoPD ?>" role="tab" aria-controls="Invoice" aria-selected="false">Invoice</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#Pembayaran<?php echo $q1->NoPD ?>" role="tab" aria-controls="Pembayaran" aria-selected="false">Pembayaran</a>
													</li>
												</ul>
												<div class="tab-content" id="pills-tabContent">
													<div class="tab-pane fade show active" id="UraianPD<?php echo $q1->NoPD ?>" role="tabpanel" aria-labelledby="pills-home-tab">
														<table id="TblQueryUraianPD" class="table table-bordered table-striped table-hover table-condensed">
															<thead>
																<tr>
																	<th>No. Urut</th>
																	<th>Kode RAP</th>
																	<th>Uraian PD</th>
																	<th>Volume PD</th>
																	<th>Uom</th>
																	<th>Hrg Satuan</th>
																	<th>Sub Total PD (Rp)</th>
																	<th>Uraian PJ</th>
																	<th>Volume PJ</th>
																	<th>Uom</th>
																	<th>Hrg Satuan (Rp)</th>
																	<th>Sub Total PJ (Rp)</th>
																</tr>
															</thead>
															<tbody>
																<?php
																$UraianPD = $this->M_QueryPd->uraianPD_new($NoPD);
																?>
																<?php foreach ($UraianPD as $upd) : ?>
																	<tr>
																		<td><?php echo $upd['NoUrut'] ?></td>
																		<td><?php echo $upd['KdRAP'] ?></td>
																		<td><?php echo $upd['Uraian'] ?></td>
																		<td><?php echo KhususDecimal($upd['Vol']) ?></td>
																		<td><?php echo $upd['Uom'] ?></td>
																		<td><?php echo UbahGabunganUang($upd['HrgSatuan']) ?></td>
																		<td><?php echo UbahGabunganUang($upd['total_pd']) ?></td>
																		<td><?php echo $upd['PjUraian'] ?></td>
																		<td><?php echo KhususDecimal($upd['PjVol']) ?></td>
																		<td><?php echo $upd['Uom'] ?></td>
																		<td><?php echo UbahGabunganUang($upd['PjHrgSatuan']) ?></td>
																		<td><?php echo UbahGabunganUang($upd['total_pj']) ?></td>
																	</tr>
																<?php endforeach; ?>
															</tbody>
														</table>
													</div>

													<div class="tab-pane fade" id="Invoice<?php echo $q1->NoPD ?>" role="tabpanel" aria-labelledby="pills-profile-tab">
														<table id="TblQueryInvoice" class="table table-bordered table-striped table-hover table-condensed">
															<thead>
																<tr>
																	<th>No. KO</th>
																	<th>Invoice</th>
																	<th>Tgl Invoice</th>
																	<th>Due Date</th>
																	<th>Total Invoice (Rp)</th>
																	<th>PPN (Rp)</th>
																	<th>No. Faktur Pajak</th>
																	<th>Tgl Faktur Pajak</th>
																	<th>Payment Amount (Rp)</th>
																</tr>
															</thead>
															<tbody>
																<?php
																$InvoicePD = $this->M_QueryPd->InvoicePD_new($NoPD);
																?>
																<?php foreach ($InvoicePD as $inv) : ?>
																	<tr>
																		<td><?= $inv['NoKO'] ?></td>
																		<td><?= $inv['InvNo'] ?></td>
																		<td><?= TglIndoGarisMiring_tanpa_waktu($inv['InvDate']) ?></td>
																		<td><?= TglIndoGarisMiring_tanpa_waktu($inv['DueDate']) ?></td>
																		<td><?= UbahGabunganUang($inv['Total']) ?></td>
																		<td><?= UbahGabunganUang($inv['PPN']) ?></td>
																		<td><?= $inv['FPNo'] ?></td>
																		<td><?= TglIndoGarisMiring_tanpa_waktu($inv['FPDate']) ?></td>
																		<td><?= UbahGabunganUang($inv['PaymentAmount']) ?></td>
																	</tr>

																<?php endforeach; ?>
															</tbody>
														</table>
													</div>

													<div class="tab-pane fade" id="Pembayaran<?php echo $q1->NoPD ?>" role="tabpanel" aria-labelledby="pills-contact-tab">
														<table id="TblQueryPembayaran" class="table table-bordered table-striped table-hover table-condensed">
															<thead>
																<tr>
																	<th>Tgl Bayar</th>
																	<th>Nama Penerima</th>
																	<th>Rekening Penerima</th>
																	<th>Nilai Bayar(Rp)</th>
																	<th>Media</th>
																	<th>Via</th>
																	<th>No Cek/BG</th>
																	<th>Rekening Pengirim</th>
																	<th>No. KO</th>
																	<th>Keterangan</th>
																</tr>
															</thead>
															<tbody>
																<?php
																$pembayaran = $this->M_QueryPd->PembayaranPD_new($NoPD);
																?>
																<?php foreach ($pembayaran as $pem) : ?>
																	<tr>
																		<td><?= TglIndoGarisMiring_tanpa_waktu($pem['TglBayar']) ?></td>
																		<td><?= $pem['AtasNama'] ?></td>
																		<td><?= $pem['NoRek'] ?></td>
																		<td><?= UbahGabunganUang($pem['Amount']) ?></td>
																		<td><?= $pem['CaraBayar'] ?></td>
																		<td><?= $pem['JenisTrf'] ?></td>
																		<td><?= $pem['NoCG'] ?></td>
																		<td><?= $pem['RekId'] ?></td>
																		<td><?= $pem['NoKO'] ?></td>
																		<td><?= $pem['Keterangan'] ?></td>
																	</tr>

																<?php endforeach; ?>
															</tbody>
														</table>
													</div>

												</div>
											</div>
										</div>
									</td>
								</tr>

							<?php } ?>
						</tbody>

					</table>
				</div>
			</div>
		</div>

	</div>
</div>
<script>
	$(function() {
		$('#my_table_querypdpj').DataTable();
	})
</script>
