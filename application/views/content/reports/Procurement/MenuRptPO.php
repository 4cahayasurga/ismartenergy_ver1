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
							<a href="#" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
						</div>

						<section class="content">
							<div class="row">

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->RekapKO == 0) ? 'hidden' : '' ?>>
									<div class="small-box bg-aqua">
										<div class="inner">
											<!-- <a href="<?= site_url('RekapKO') ?>"> -->
											<a href="<?= site_url('Laporan/Procurement/FrmRekapKO') ?>">
												<h4 style="color: aliceblue;">Rekap KO</h4>
												<p style="color: aliceblue;"></p>
											</a>
										</div>
										<div class="icon">
											<i class="fa fa-database"></i>
										</div>
										<!-- <a href="<?= site_url('RekapKO') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a> -->
										<a href="<?= site_url('Laporan/Procurement/FrmRekapKO') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>


								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->RptTrackingKO == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-green">
										<div class="inner">
											<!-- <a href="<?= site_url('TrackingKO') ?>"> -->
											<a href="<?= site_url('Laporan/Procurement/FrmTrackingKO') ?>">
												<h4 style="color: aliceblue;">Tracking KO</h4>

												<p style="color: aliceblue;"></p>
											</a>
										</div>
										<div class="icon">
											<i class="ion ion-stats-bars"></i>
										</div>
										<a href="<?= site_url('Laporan/Procurement/FrmTrackingKO') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<!-- <div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->InvoiceReceipt == 0) ? 'hidden' : '' ?>> -->
								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->InvoiceReceipt == 0) ? 'hidden' : '' ?>>
									<div class="small-box bg-orange">
										<div class="inner">
											<a href="<?= site_url('MyReport/Procurement/RekapInvoice_Supplier/view') ?>">
												<h4 style="color: aliceblue;">Rekap Inv Supplier</h4>
												<p style="color: aliceblue;"></p>
											</a>
										</div>
										<div class="icon">
											<i class="fa fa-dollar"></i>
										</div>
										<a href="<?= site_url('MyReport/Procurement/RekapInvoice_Supplier/view') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->ItemPrice == 0) ? 'hidden' : '' ?>>
									<div class="small-box bg-blue">
										<div class="inner">
											<!-- <a href="<?= site_url('DaftarHarga') ?>"> -->
											<a href="<?= site_url('MyReport/Procurement/DaftarHarga/view') ?>">
												<h4 style="color: aliceblue;">Daftar Harga</h4>

												<p style="color: aliceblue;"></p>
											</a>
										</div>
										<div class="icon">
											<i class="fa fa-cog"></i>
										</div>
										<a href="<?= site_url('MyReport/Procurement/DaftarHarga/view') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->QueryAP == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-yellow">
										<div class="inner">
											<!--?php foreach ($job as $jb) : ?-->
											<a href="<?= site_url('Laporan/Procurement/Query_HutangKO') ?>">
												<h4 style="color: aliceblue;">Query Hutang KO</h4>

												<p style="color: aliceblue;"></p>
											</a>
											<!--?php endforeach; ?-->
										</div>
										<div class="icon">
											<i class="ion ion-stats-bars"></i>
										</div>
										<a href="<?= site_url('Laporan/Procurement/Query_HutangKO') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->QueryAP == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-purple">
										<div class="inner">
											<!--?php foreach ($job as $jb) : ?-->
											<a href="<?= site_url('MyReport/Procurement/Query_invoice_report') ?>">
												<h4 style="color: aliceblue;">Query Invoice</h4>

												<p style="color: aliceblue;"></p>
											</a>
											<!--?php endforeach; ?-->
										</div>
										<div class="icon">
											<i class="ion ion-stats-bars"></i>
										</div>
										<a href="<?= site_url('MyReport/Procurement/Query_invoice_report') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->RekapKO == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-orange">
										<div class="inner">
											<!--?php foreach ($job as $jb) : ?-->
											<a href="<?= site_url('Laporan/Procurement/Rekap_Hutang_KO') ?>">
												<h4 style="color: aliceblue;">Rekap Hutang <br>KO</h4>

												<p style="color: aliceblue;"></p>
											</a>
											<!--?php endforeach; ?-->
										</div>
										<div class="icon">
											<i class="fa fa-bookmark-o"></i>
										</div>
										<a href="<?= site_url('Laporan/Procurement/Rekap_Hutang_KO') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->RekapKO == 0) ? 'hidden' : '' ?>>
									<div class="small-box" style="background-color: #2980b9;">
										<div class="inner">
											<!--?php foreach ($job as $jb) : ?-->
											<a href="#">
												<h4 style="color: aliceblue;">Rekap Hutang <br>KO (PHO)</h4>

												<p style="color: aliceblue;"></p>
											</a>
											<!--?php endforeach;  ?-->
										</div>
										<div class="icon">
											<i class="fa fa-exchange"></i>
										</div>
										<a href="" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>



								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->QuerySPR == 0) ? 'hidden' : '' ?>>
									<div class="small-box" style="background-color: #16a085;">
										<div class="inner">
											<!--?php foreach ($job as $jb) : ?-->
											<a href="#">
												<h4 style="color: aliceblue;">Query SPR</h4>

												<p style="color: aliceblue;">Query Permintaan Material/alat</p>
											</a>
											<!--?php endforeach; ?-->
										</div>
										<div class="icon">
											<i class="fa fa-bookmark-o"></i>
										</div>
										<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->RekapKO == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-purple">
										<div class="inner">
											<!--?php foreach ($job as $jb) : ?-->
											<a href="#">
												<h4 style="color: aliceblue;">Daftar Pengiriman</h4>

												<p style="color: aliceblue;">Daftar Pengiriman KO Jatuh Tempo</p>
											</a>
											<!--?php endforeach;  ?-->
										</div>
										<div class="icon">
											<i class="fa fa-exchange"></i>
										</div>
										<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>


								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->MosVol == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-red">
										<div class="inner">
											<!--?php foreach ($job as $jb) : ?-->
											<a href="#">
												<h4 style="color: aliceblue;">Volume MOS</h4>

												<p style="color: aliceblue;">Material On Site</p>
											</a>
											<!--?php endforeach; ?-->
										</div>
										<div class="icon">
											<i class="ion ion-bag"></i>
										</div>
										<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->SerapRAPKO == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-blue">
										<div class="inner">
											<!--?php foreach ($job as $jb) : ?-->
											<a href="#">
												<h4 style="color: aliceblue;">Penyerapan RAP</h4>

												<p style="color: aliceblue;">RAP Fisik Dgn KO </p>
											</a>
											<!--?php endforeach; ?-->
										</div>
										<div class="icon">
											<i class="fa fa-bookmark-o"></i>
										</div>
										<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->AnalisaDelta == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-green">
										<div class="inner">
											<!--?php foreach ($job as $jb) : ?-->
											<!-- <a href="<?= site_url('Report/PKET') ?>"> -->
											<a href="<?= site_url('MyReport/Procurement/AnalisaDelta') ?>">
												<h4 style="color: aliceblue;">Analisa Delta <br>RAP vs Realisasi RAP</h4>

												<p style="color: aliceblue;"></p>
											</a>
											<!--?php endforeach; ?-->
										</div>
										<div class="icon">
											<i class="fa fa-bookmark-o"></i>
										</div>
										<a href="<?= site_url('MyReport/Procurement/AnalisaDelta') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->DaftarMaterial == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-red">
										<div class="inner">
											<a href="<?= site_url('MyReport/Procurement/DaftarMaterial') ?>">
												<h4 style="color: aliceblue;">Daftar Barang / <br>Material</h4>

												<p style="color: aliceblue;"></p>
											</a>
											<!--?php endforeach; ?-->
										</div>
										<div class="icon">
											<i class="ion ion-stats-bars"></i>
										</div>
										<a href="<?= site_url('MyReport/Procurement/DaftarMaterial') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->RptDaftarMaterial == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-orange">
										<div class="inner">
											<!--?php foreach ($job as $jb) : ?-->
											<a href="<?= site_url('MyReport/Procurement/PembelianMaterial') ?>">
												<h4 style="color: aliceblue;">Daftar Pembelian Material</h4>
											</a>
											<!--?php endforeach;  ?-->
										</div>
										<div class="icon">
											<i class="fa fa-exchange"></i>
										</div>
										<a href="<?= site_url('MyReport/Procurement/PembelianMaterial') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
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
