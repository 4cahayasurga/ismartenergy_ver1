<head>

	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/Ionicons/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/dist/css/AdminLTE.min.css">

	<link rel="stylesheet" href="<?php echo base_url() ?>assets/dist/css/skins/_all-skins.min.css">

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>


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
								<div class="box">
									<div class="box-body">

										<div class="col-lg-6" <?=($this->session->userdata('PecahToken')->DPPD == 0) ? 'hidden' : '' ?> >
											<div class="small-box bg-aqua">
												<div class="inner">
													<a href="<?= site_url('Laporan/Lap_DPPD') ?>">
														<h4 style="color: aliceblue;">Daftar Permintaan Dana & Pertanggungjawaban Dana</h4>
														<p style="color: aliceblue;"></p>
													</a>
												</div>
												<div class="icon">
													<i class="ion ion-stats-bars"></i>
												</div>
												<a href="<?= site_url('Laporan/Lap_DPPD') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
											</div>
										</div>

										<div class="col-lg-6" <?=($this->session->userdata('PecahToken')->RekapPayment == 0) ? 'hidden' : '' ?> >
											<div class="small-box bg-purple">
												<div class="inner">
													<a href="<?= site_url('Laporan/Rkp_pembayaran') ?>">
														<h4 style="color: aliceblue;">Rekap Pembayaran</h4>
														<p style="color: aliceblue;"></p>
													</a>
												</div>
												<div class="icon">
													<i class="ion ion-stats-bars"></i>
												</div>
												<a href="<?= site_url('Laporan/Rkp_pembayaran') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
											</div>
										</div>

										<div class="col-lg-6" <?=($this->session->userdata('PecahToken')->SerapRAP == 0) ? 'hidden' : '' ?> >
											<div class="small-box bg-orange">
												<div class="inner">
													<a href="<?= site_url('Laporan/RptPenyerapanRAP') ?>">
														<h4 style="color: white;">Report Penyerapan RAP</h4>
														<p style="color: white;"></p>
													</a>
												</div>
												<div class="icon">
													<i class="ion ion-stats-bars"></i>
												</div>
												<a href="<?= site_url('Laporan/RptPenyerapanRAP') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
											</div>
										</div>

										<!-- <div class="col-lg-6" <?=($this->session->userdata('PecahToken')->RptGiroMundur == 0) ? 'hidden' : '' ?> > -->
											<div class="col-lg-6" <?=($this->session->userdata('PecahToken')->QueryPD == 0) ? 'hidden' : '' ?> >
												<div class="small-box bg-red">
													<div class="inner">
														<!-- <a href="<?= site_url('Laporan/Report_query_pd_pj') ?>"> -->
															<a href="<?= site_url('RptQuery_PDPJ') ?>">
																<h4 style="color: white;">Report Query PD / PJ</h4>
																<p style="color: white;"></p>
															</a>
														</div>
														<div class="icon">
															<i class="ion ion-stats-bars"></i>
														</div>
														<a href="<?= site_url('RptQuery_PDPJ') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
													</div>
												</div>

												<div class="col-lg-6" <?=($this->session->userdata('PecahToken')->RekapPDJaminan == 0) ? 'hidden' : '' ?> >
													<div class="small-box bg-red">
														<div class="inner">
															<!-- <a href="<?= site_url('Laporan/Report_outstanding_approvedpd') ?>"> -->
																<a href="<?= site_url("RptOutstandingPC") ?>">
																	<h4 style="color: white;">Report OutStandings / Approved PD</h4>
																	<p style="color: white;"></p>
																</a>
															</div>
															<div class="icon">
																<i class="ion ion-stats-bars"></i>
															</div>
															<a href="<?= site_url("RptOutstandingPC") ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
														</div>
													</div>

													<div class="col-lg-6" <?=($this->session->userdata('PecahToken')->RptGiroMundur == 0) ? 'hidden' : '' ?> >
														<div class="small-box bg-purple">
															<div class="inner">
																<a href="<?= site_url('MonitoringCGM') ?>">
																	<h4 style="color: white;">Monitoring Cek / Giro Mundur</h4>
																	<p style="color: white;"></p>
																</a>
															</div>
															<div class="icon">
																<i class="ion ion-stats-bars"></i>
															</div>
															<a href="<?= site_url('MonitoringCGM') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
														</div>
													</div>

													<div class="col-lg-6" <?=($this->session->userdata('PecahToken')->RekapPDJaminan == 0) ? 'hidden' : '' ?> >
														<div class="small-box bg-blue">
															<div class="inner">
																<a href="<?= site_url('RekapPDJaminan') ?>">
																	<h4 style="color: white;">Rekap PD Jaminan</h4>
																	<p style="color: white;"></p>
																</a>
															</div>
															<div class="icon">
																<i class="ion ion-stats-bars"></i>
															</div>
															<a href="<?= site_url('RekapPDJaminan') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
														</div>
													</div>

												</div>



											</div>
										</div>

										<div class="row">

											<div class="col-lg-3 col-xs-6" hidden>
												<div class="small-box bg-aqua">
													<div class="inner">
														<a href="<?= site_url('Dppd') ?>">
															<h4 style="color: aliceblue;">Daftar Permintaan Dana <br> Pertanggungjawaban Dana</h4>
															<p style="color: aliceblue;"></p>
														</a>
													</div>
													<div class="icon">
														<i class="fa fa-database"></i>
													</div>
													<a href="<?= site_url('Dppd') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
												</div>

											</div>


											<div class="col-lg-3 col-xs-6" hidden>
												<div class="small-box bg-green">
													<div class="inner">
														<a href="<?= site_url('RekapPayment') ?>">
															<h4 style="color: aliceblue;">Rekap Pembayaran <br> Permintaan Dana</h4>

															<p style="color: aliceblue;"></p>
														</a>
													</div>
													<div class="icon">
														<i class="ion ion-stats-bars"></i>
													</div>
													<a href="<?= site_url('RekapPayment') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
												</div>
											</div>

											<div class="col-lg-3 col-xs-6" hidden>
												<!-- small box -->
												<div class="small-box bg-orange">
													<div class="inner">
														<a href="<?= site_url('PenyerapanRAP') ?>">
															<h4 style="color: aliceblue;">Penyerapan Terhadap <br>RAP</h4>
															<p style="color: aliceblue;"></p>
														</a>
													</div>
													<div class="icon">
														<i class="fa fa-dollar"></i>
													</div>
													<a href="<?= site_url('PenyerapanRAP') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
												</div>
											</div>

											<div class="col-lg-3 col-xs-6" hidden>
												<div class="small-box bg-blue">
													<div class="inner">
														<a href="<?= site_url('QueryPd') ?>">
															<h4 style="color: aliceblue;">Query Permintaan Dana <br> Pertanggungjawaban Dana</h4>

															<p style="color: aliceblue;"></p>
														</a>
													</div>
													<div class="icon">
														<i class="fa fa-cog"></i>
													</div>
													<a href="<?= site_url('QueryPd') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
												</div>
											</div>

											<div class="col-lg-3 col-xs-6" hidden>
												<!-- small box -->
												<div class="small-box bg-yellow">
													<div class="inner">
														<a href="<?= site_url('RekapOS') ?>">
															<h4 style="color: aliceblue;">Rekap Outstanding & Approved PD</h4>

															<p style="color: aliceblue;"></p>
														</a>
													</div>
													<div class="icon">
														<i class="ion ion-stats-bars"></i>
													</div>
													<a href="<?= site_url('RekapOS') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
												</div>
											</div>

											<div class="col-lg-3 col-xs-6" hidden>
												<div class="small-box bg-purple">
													<div class="inner">
														<a href="<?= site_url('MonitoringCGM') ?>">
															<h4 style="color: aliceblue;">Monitoring Cek / Giro Mundur</h4>

															<p style="color: aliceblue;"></p>
														</a>
													</div>
													<div class="icon">
														<i class="ion ion-stats-bars"></i>
													</div>
													<a href="<?= site_url('MonitoringCGM') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
												</div>
											</div>

											<div class="col-lg-3 col-xs-6" hidden>
												<div class="small-box bg-blue">
													<div class="inner">
														<a href="<?= site_url('RekapPDJaminan') ?>">
															<h4 style="color: aliceblue;">Rekap PD Jaminan</h4>

															<p style="color: aliceblue;"> </p>
														</a>
													</div>
													<div class="icon">
														<i class="fa fa-bookmark-o"></i>
													</div>
													<a href="<?= site_url('RekapPDJaminan') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
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