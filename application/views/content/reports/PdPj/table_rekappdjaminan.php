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
								<th colspan="8">No PD</th>
								<th>Tgl PD</th>
								<th>Tipe Form</th>
								<th>Deskripsi</th>
								<th>Total PD (Rp)</th>
								<th>Tgl Bayar</th>
								<th>Total Pembayaran (Rp)</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($query1 as $q1) {
								$NoPD = $q1->NoPD;
							?>
								<tr data-toggle="collapse" data-target="#demo1<?php echo $q1->NoPD ?>" class="accordion-toggle">
									<td><button class="btn btn-default btn-xs"><span class="fa fa-plus-circle"></span></button></td>
									<td><?= $q1->NoPD ?></td>
									<td><?= $q1->TglPD ?></td>
									<td><?= $q1->TipeForm ?></td>
									<td><?= $q1->Deskripsi ?></td>
									<td><?= $q1->TotalPD ?></td>
									<td><?= $q1->TglBayar ?></td>
									<td><?= $q1->BLEAmount ?></td>
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
																	<th>No PD</th>
																	<th>Tgl PD</th>
																	<th>Tipe Form</th>
																	<th>Deskripsi</th>
																	<th>Total PD (Rp)</th>
																	<th>Tgl Bayar</th>
																	<th>Total Pembayaran (Rp)</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($query1 as $q1) :
																			$NoPD = $q1->NoPD;
																		?>
																	<tr>
																		<td><?= $q1->NoPD ?></td>
																		<td><?= $q1->TglPD ?></td>
																		<td><?= $q1->TipeForm ?></td>
																		<td><?= $q1->Deskripsi ?></td>
																		<td><?= $q1->TotalPD ?></td>
																		<td><?= $q1->TglBayar ?></td>
																		<td><?= $q1->BLEAmount ?></td>
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
