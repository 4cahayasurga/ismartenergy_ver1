<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?php echo $judul ?><p></p>
		</h1>

	</section>

	<section class="content">
		<div class="container-fluid">


			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<button class="btn btn-sm" style="background-color:#7f8c8d;color:white;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Kembali</button>
						</div>


						<section class="content">

							<div class="row">


								<div class="col-lg-3 col-xs-6" id="DataProyek" <?= ($this->session->userdata('PecahToken')->QueryTermin == 0) ? 'hidden' : '' ?>>

									<div class="small-box bg-aqua">
										<div class="inner">
											<a href="<?= site_url('MyReport/Operasi/QueryTermin_Report') ?>">
												<h4 style="color: aliceblue;">Query Termin</h4>
												<p style="color: aliceblue;"></p>
											</a>
										</div>
										<div class="icon">
											<i class="fa fa-database"></i>
										</div>
										<a href="<?= site_url('MyReport/Operasi/QueryTermin_Report') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>

								</div>


								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->Termin == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-green">
										<div class="inner">
											<a href="<?= site_url('Report/MonitoringTermin') ?>">
												<h4 style="color: aliceblue;">Monitoring Termin</h4>

												<p style="color: aliceblue;"></p>
											</a>
										</div>
										<div class="icon">
											<i class="ion ion-stats-bars"></i>
										</div>
										<a href="<?= site_url('Report/MonitoringTermin') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->PiutangProgress == 0) ? 'hidden' : '' ?>>
									<div class="small-box bg-orange">
										<div class="inner">
											<a href="<?= site_url('MyReport/Operasi/PiutangProgressFisik_report') ?>">
												<h4 style="color: aliceblue;">Piutang Progress Fisik</h4>
												<p style="color: aliceblue;"></p>
											</a>
										</div>
										<div class="icon">
											<i class="fa fa-dollar"></i>
										</div>
										<a href="<?= site_url('MyReport/Operasi/PiutangProgressFisik_report') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" hidden>
									<div class="small-box bg-blue">
										<div class="inner">
											<a href="<?= site_url('Report/AttributeKontrak') ?>">
												<h4 style="color: aliceblue;">Attribute Kontrak</h4>

												<p style="color: aliceblue;"></p>
											</a>
										</div>
										<div class="icon">
											<i class="fa fa-cog"></i>
										</div>
										<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->RptRencanaTermin == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-yellow">
										<div class="inner">
											<!--?php foreach ($job as $jb) : ?-->
											<a href="<?= site_url('Report/MonitoringRencanaTermin')  ?>">
												<h4 style="color: aliceblue;">Monitoring Rencana Termin</h4>

												<p style="color: aliceblue;"></p>
											</a>
											<!--?php endforeach; ?-->
										</div>
										<div class="icon">
											<i class="ion ion-stats-bars"></i>
										</div>
										<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" id="YAD" <?= ($this->session->userdata('PecahToken')->RptRPPM == 0) ? 'hidden' : '' ?>>
									<div class="small-box bg-purple">
										<div class="inner">
											<!--?php foreach ($job as $jb) : ?-->
											<a href="<?= site_url('Report/RptRPPM') ?>">
												<h4 style="color: aliceblue;">Monitoring RPPM</h4>

												<p style="color: aliceblue;"></p>
											</a>
											<!--?php endforeach; ?-->
										</div>
										<div class="icon">
											<i class="ion ion-stats-bars"></i>
										</div>
										<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->MonCadKSO == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-yellow">
										<div class="inner">
											<!--?php foreach ($job as $jb) : ?-->
											<a href="#">
												<h4 style="color: aliceblue;">Monitoring Cadangan KSO</h4>

												<p style="color: aliceblue;"></p>
											</a>
											<!--?php endforeach; ?-->
										</div>
										<div class="icon">
											<i class="fa fa-bookmark-o"></i>
										</div>
										<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->RptBarChart == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-red">
										<div class="inner">
											<!--?php foreach ($job as $jb) : ?-->
											<!-- <a href="<?= site_url('Report/Barchart') ?>"> -->
											<a href="<?= site_url('Report/Barchart') ?>">
												<h4 style="color: aliceblue;">Bar Chart Proyek</h4>

												<p style="color: aliceblue;"></p>
											</a>
											<!--?php endforeach; ?-->
										</div>
										<div class="icon">
											<i class="fa fa-bookmark-o"></i>
										</div>
										<a href="<?= site_url('Report/Barchart') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->RptBarChart == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-orange">
										<div class="inner">
											<a href="<?= site_url('MyReport/Operasi/CurvaProgressReport') ?>">
												<h4 style="color: aliceblue;">Kurva Progress</h4>

												<p style="color: aliceblue;"></p>
											</a>
											<!--?php endforeach; ?-->
										</div>
										<div class="icon">
											<i class="fa fa-bookmark-o"></i>
										</div>
										<a href="<?= site_url('MyReport/Operasi/CurvaProgressReport') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->MonProyek == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-green">
										<div class="inner">
											<!--?php foreach ($job as $jb) : ?-->
											<a href="#">
												<h4 style="color: aliceblue;">Monitoring Proyek</h4>

												<p style="color: aliceblue;"></p>
											</a>
											<!--?php endforeach; ?-->
										</div>
										<div class="icon">
											<i class="fa fa-bookmark-o"></i>
										</div>
										<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->RekapProyek == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-blue">
										<div class="inner">
											<!--?php foreach ($job as $jb) : ?-->
											<!-- <a href="<?= site_url('Report/PKET') ?>"> -->
											<a href="<?= site_url('MyReport/Operasi/RekapProyek') ?>">
												<h4 style="color: aliceblue;">Rekap Proyek <br>Kontrak, Expense & Termin</h4>

												<p style="color: aliceblue;"></p>
											</a>
											<!--?php endforeach; ?-->
										</div>
										<div class="icon">
											<i class="fa fa-bookmark-o"></i>
										</div>
										<a href="<?= site_url('MyReport/Operasi/RekapProyek') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								

							</div>


						</section>

					</div>
				</div>
			</div>
		</div>
	</section>



	<!-- </Section> -->

</div>
