<head>
	<!-- Tell the browser to be responsive to screen width -->
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
			Job Detail<p></p>
			<?php foreach ($job as $jb) ?>
			<label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $jb->JobNo ?></label> - <label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $jb->JobNm ?></label>
			<br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $jb->Lokasi ?></label>

				<?php T_ENDFOREACH ?>
		</h1>

	</section>

	<!-- <Section class="content"> -->



	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">

							<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>
							<a href="<?= site_url('job') ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>

						</div>


						<section class="content">
							<div class="row">

								<div class="col-sm-12">
									<a href="#" class="btn bg-black" style="width: 100%;"> Menu Entry</a>
									<p></p>
								</div>



								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->Akses_DataProyek == 0) ? 'hidden' : '' ?>>
									<div class="small-box bg-aqua">
										<div class="inner">
											<?php foreach ($job as $jb) ?>
											<a href="<?php echo site_url('job/dataproyek/' . $jb->JobNo) ?>">
												<h3 style="color: aliceblue;">Data Proyek</h3>
												<p style="color: aliceblue;">Job No, Nama Proyek, HPS, Wilayah Pekerjaan, dll</p>
											</a>
											<?php T_ENDFOREACH ?>
										</div>
										<div class="icon">
											<i class="fa fa-database"></i>
										</div>
										<a href="<?php echo site_url('job/dataproyek/' . $jb->JobNo) ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>


								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->Akses_DataKontrak == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-green">
										<div class="inner">
											<?php foreach ($job as $jb) ?>
											<a href="<?php echo site_url('job/datakontrak/' . $jb->JobNo) ?>">
												<h3 style="color: aliceblue;">Data Kontrak</h3>

												<p style="color: aliceblue;">Addendum, Rek Proyek, Jaminan, Ceklist Dokument</p>
											</a>
											<?php T_ENDFOREACH  ?>
										</div>
										<div class="icon">
											<i class="ion ion-stats-bars"></i>
										</div>
										<a href="Entry/dataproyek.php" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->Akses_Huba == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-aqua">
										<div class="inner">
											<?php foreach ($job as $jb) : ?>
												<a href="<?php echo site_url('Huba/ListHuba/' . $jb->JobNo)  ?>">
													<h3 style="color: aliceblue;">HUBA</h3>

													<p style="color: aliceblue;">Harga Upah Barang Dan Alat</p>
												</a>
											<?php endforeach; ?>
										</div>
										<div class="icon">
											<i class="fa fa-bookmark-o"></i>
										</div>
										<a href="<?php echo site_url('Huba/ListHuba/' . $jb->JobNo)  ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->Akses_Ahsp == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-aqua">
										<div class="inner">
											<?php foreach ($job as $jb) : ?>
												<a href="<?php echo site_url('AHSP/ahsp_awal/' . $jb->JobNo)  ?>">
													<h3 style="color: aliceblue;">AHSP</h3>

													<p style="color: aliceblue;">Analisa Harga Satuan Pekerjaan</p>
												</a>
											<?php endforeach; ?>
										</div>
										<div class="icon">
											<i class="fa fa-bookmark-o"></i>
										</div>
										<a href="<?php echo site_url('AHSP/ahsp_awal/' . $jb->JobNo)  ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->RAP == 0) ? 'hidden' : '' ?>>

									<div class="small-box bg-yellow">
										<div class="inner">
											<?php foreach ($job as $jb) : ?>
												<a href="<?php echo site_url('Entry_master/ENT_RAP/index/' . $jb->JobNo)  ?>">
													<h3 style="color: aliceblue;">RAP</h3>

													<p style="color: aliceblue;">Entry RAP, Monitoring RAP, Tambah Versi RAP</p>
												</a>
											<?php endforeach; ?>
										</div>
										<div class="icon">
											<i class="ion ion-stats-bars"></i>
										</div>
										<a href="<?php echo site_url('Entry_master/ENT_RAP/index/' . $jb->JobNo)  ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->Akses_TataKelola == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-blue">
										<div class="inner">
											<?php foreach ($job as $jb) ?>
											<!-- <a href="<?php echo site_url('job/tatakelola/' . $jb->JobNo)  ?>"> -->
											<a href="<?php echo site_url('Master/TataKelola_new/data_list_tatakelola/' . $jb->JobNo)  ?>">
												<h3 style="color: aliceblue;">Tata Kelola</h3>

												<p style="color: aliceblue;">Data KSO, Tipe Managerial, Porsi Kontrak</p>
											</a>

											<!-- <a href="<?php echo site_url('TataKelola/view/' . $jb->JobNo)  ?>">
												<h3 style="color: aliceblue;">Tata Kelola</h3>

												<p style="color: aliceblue;">Data KSO, Struktur Organisasi, Tipe Managerial, Porsi Kontrak</p>
											</a> -->
											<?php T_ENDFOREACH ?>
										</div>
										<div class="icon">
											<i class="fa fa-cog"></i>
										</div>
										<a href="<?php echo site_url('Master/TataKelola_new/data_list_tatakelola/' . $jb->JobNo)  ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->BAPPR == 0) ? 'hidden' : '' ?>>
									<div class="small-box bg-aqua">
										<div class="inner">
											<?php foreach ($job as $entry_master) ?>
											<a href="<?php echo site_url('Bapp/index/' . $jb->JobNo) ?>">
												<h3 style="color: aliceblue;">BAPP</h3>
												<p style="color: aliceblue;">Berita Acara Prestasi Pekerjaan </p>
											</a>
											<?php T_ENDFOREACH ?>
										</div>
										<div class="icon">
											<i class="fa fa-database"></i>
										</div>
										<a href="<?php echo site_url('DipaTermin/PenerimaanTermin/list_termin/' . $jb->JobNo) ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->RencanaTermin == 0) ? 'hidden' : '' ?>>

									<div class="small-box bg-orange">
										<div class="inner">
											<?php foreach ($job as $jb) ?>
											<a href="<?php echo site_url('job/dipa/' . $jb->JobNo) ?>">
												<h3 style="color: aliceblue;">Dipa </h3>

												<p style="color: aliceblue;">Dipa, Rencana Termin </p>
											</a>
											<?php T_ENDFOREACH ?>
										</div>
										<div class="icon">
											<i class="fa fa-dollar"></i>
										</div>
										<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<!-- <div class="col-sm-12 row"> -->
								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->Termin == 0) ? 'hidden' : '' ?>>
									<div class="small-box bg-aqua">
										<div class="inner">
											<?php foreach ($job as $entry_master) ?>
											<a href="<?php echo site_url('DipaTermin/PenerimaanTermin/list_termin/' . $jb->JobNo) ?>">
												<h3 style="color: aliceblue;">Penerimaan Termin</h3>
												<p style="color: aliceblue;">Penerimaan Termin </p>
											</a>
											<?php T_ENDFOREACH ?>
										</div>
										<div class="icon">
											<i class="fa fa-database"></i>
										</div>
										<a href="<?php echo site_url('DipaTermin/PenerimaanTermin/list_termin/' . $jb->JobNo) ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
								<hr>
								<!-- </div> -->


								<div class="col-lg-3 col-xs-6" id="YAD" <?= ($this->session->userdata('PecahToken')->EntryYAD == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-purple">
										<div class="inner">
											<?php foreach ($job as $jb) : ?>
												<a href="<?php echo site_url('job/YAD/' . $jb->JobNo)  ?>">
													<h3 style="color: aliceblue;">YAD</h3>

													<p style="color: aliceblue;">Entry Form YAD Project</p>
												</a>
											<?php endforeach; ?>
										</div>
										<div class="icon">
											<i class="ion ion-stats-bars"></i>
										</div>
										<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->QuerySPR == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-blue">
										<div class="inner">
											<?php foreach ($job as $jb) : ?>
												<a href="<?php echo site_url('job/spr/' . $jb->JobNo)  ?>">
													<h3 style="color: aliceblue;">SPR</h3>

													<p style="color: aliceblue;">Daftar Permintaan Material / Alat</p>
												</a>
											<?php endforeach; ?>
										</div>
										<div class="icon">
											<i class="fa fa-bookmark-o"></i>
										</div>
										<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->ApprovalSPR == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-orange">
										<div class="inner">
											<?php foreach ($job as $jb) : ?>
												<a href="<?= site_url('Approved_spr/view/' . $jb->JobNo) ?>">
													<h3 style="color: aliceblue;">Approval SPR</h3>

													<p style="color: aliceblue;">Approved Permintaan Material/Alat, Reivew PC</p>
												</a>
											<?php endforeach; ?>
										</div>
										<div class="icon">
											<i class="fa fa-bookmark-o"></i>
										</div>
										<a href="<?= site_url('Approved_spr/view/' . $jb->JobNo) ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->RPPM == 0) ? 'hidden' : '' ?>>
									<div class="small-box bg-aqua">
										<div class="inner">
											<?php foreach ($job as $jb) : ?>
												<!-- <a href="<?php echo site_url('job/rppm/' . $jb->JobNo)  ?>"> -->
												<a href="<?php echo site_url('BasicData/FrmRPPM_C/index/' . $jb->JobNo)  ?>">
													<h3 style="color: aliceblue;">RPPM</h3>

													<p style="color: aliceblue;">RPPM</p>
												</a>
											<?php endforeach; ?>
										</div>
										<div class="icon">
											<i class="ion ion-bag"></i>
										</div>
										<a href="<?php echo site_url('BasicData/FrmRPPM_C/index/' . $jb->JobNo)  ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->RPPM1 == 0) ? 'hidden' : '' ?>>
									<div class="small-box bg-green">
										<div class="inner">
											<?php foreach ($job as $jb) : ?>
												<a href="<?php echo site_url('Menu_entry/Progress_fisik/list_data_PF/' . $jb->JobNo)  ?>">
													<h3 style="color: aliceblue;">EMON / Progress Fisik</h3>

													<p style="color: aliceblue;">Data EMON / PROGRES FISIK</p>
												</a>
											<?php endforeach; ?>
										</div>
										<div class="icon">
											<i class="ion ion-bag"></i>
										</div>
										<a href="<?php echo site_url('Menu_entry/Progress_fisik/list_data_PF/' . $jb->JobNo)  ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>


								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->Akses_LeafletProyek == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-aqua">
										<div class="inner">
											<?php foreach ($job as $jb) : ?>
												<a href="<?php echo site_url('job/leaflet/' . $jb->JobNo)  ?>">
													<h3 style="color: aliceblue;">Leaflet Proyek</h3>

													<p style="color: aliceblue;">Data Profil Proyek</p>
												</a>
											<?php endforeach; ?>
										</div>
										<div class="icon">
											<i class="fa fa-bookmark-o"></i>
										</div>
										<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->EntryBAPP == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-aqua">
										<div class="inner">
											<?php foreach ($job as $jb) : ?>
												<a href="<?php echo site_url('FrmEntryBAPP/index/' . $jb->JobNo)  ?>">
													<h3 style="color: aliceblue;">Entry BAPP</h3>

													<p style="color: aliceblue;">Berita Acara Prestasi Pekerjaan</p>
												</a>
											<?php endforeach; ?>
										</div>
										<div class="icon">
											<i class="fa fa-bookmark-o"></i>
										</div>
										<a href="<?php echo site_url('FrmEntryBAPP/index/' . $jb->JobNo)  ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->ResumeK3 == 0) ? 'hidden' : '' ?>>
									<!-- small box -->
									<div class="small-box bg-aqua">
										<div class="inner">
											<?php foreach ($job as $jb) : ?>
												<a href="<?php echo site_url('FrmResumeK3/index/' . $jb->JobNo)  ?>">
													<h3 style="color: aliceblue;">Resume K3</h3>

													<p style="color: aliceblue;">FR, IR, SR</p>
												</a>
											<?php endforeach; ?>
										</div>
										<div class="icon">
											<i class="fa fa-bookmark-o"></i>
										</div>
										<a href="<?php echo site_url('FrmResumeK3/index/' . $jb->JobNo)  ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>



							</div>
							</p>

							<a href="#" class="btn bg-black" style="width: 100%;"><strong>MATERIAL ON SITE</strong> </a>
							<p></p>
							<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->Akses_MOS_In_Out == 0) ? 'hidden' : '' ?>>
								<!-- small box -->
								<div class="small-box bg-aqua">
									<div class="inner">
										<?php foreach ($job as $jb) : ?>
											<!-- <a href="<?php echo site_url('job/mos_Barang_Masuk/' . $jb->JobNo)  ?>"> -->
											<a href="<?php echo site_url('MOS/FrmEntryMOS/view/' . $jb->JobNo)  ?>">
												<h3 style="color: aliceblue;">Penerimaan Barang / <br>Material</h3>
												<!-- <p>
													<h3 style="color: aliceblue;"></h3>
												</p> -->

												<p style="color: aliceblue;">Penerimaan Barang / Material di Site</p>
											</a>
										<?php endforeach; ?>
									</div>
									<div class="icon">
										<i class="fa fa-exchange"></i>
									</div>
									<a href="<?php echo site_url('MOS/FrmEntryMOS/view/' . $jb->JobNo)  ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>

							<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->Akses_ApprovalMOS == 0) ? 'hidden' : '' ?>>
								<!-- small box -->
								<div class="small-box bg-orange">
									<div class="inner">
										<?php foreach ($job as $jb) : ?>
											<!-- <a href="<?php echo site_url('job/ApproveMos/' . $jb->JobNo)  ?>"> -->
											<a href="<?php echo site_url('MOS/FrmApproveMOS/view/' . $jb->JobNo)  ?>">
												<h3 style="color: aliceblue;">Approve Penerimaan <br>Barang/Material</h3>
												<!-- <p>
													<h3 style="color: aliceblue;"></h3>
												</p> -->

												<p style="color: aliceblue;">Penerimaan Barang / Material di Site</p>
											</a>
										<?php endforeach; ?>
									</div>
									<div class="icon">
										<i class="fa fa-exchange"></i>
									</div>
									<a href="<?php echo site_url('MOS/FrmApproveMOS/view/' . $jb->JobNo)  ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>

							<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->Akses_MOS_In_Out == 0) ? 'hidden' : '' ?>>
								<!-- small box -->
								<div class="small-box bg-blue">
									<div class="inner">
										<?php foreach ($job as $jb) : ?>
											<a href="<?php echo site_url('MOS/FrmReqPemakaianMOS/view/' . $jb->JobNo)  ?>">
												<!-- <a href="<?php echo site_url('job/StockKeluar/' . $jb->JobNo)  ?>"> -->
												<h3 style="color: aliceblue;">Pemakaian Barang /<br>Material</h3>
												<!-- <p>
													<h3 style="color: aliceblue;">Material</h3>
												</p> -->

												<p style="color: aliceblue;">Surat Permintaan Pemakaian barang / Material </p>
											</a>
										<?php endforeach; ?>
									</div>
									<div class="icon">
										<i class="fa fa-exchange"></i>
									</div>
									<a href="<?php echo site_url('MOS/FrmReqPemakaianMOS/view/' . $jb->JobNo)  ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>

							<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->Akses_MOS_In_Out == 0) ? 'hidden' : '' ?>>
								<!-- small box -->
								<div class="small-box bg-yellow">
									<div class="inner">
										<?php foreach ($job as $jb) : ?>
											<a href="<?php echo site_url('MOS/FrmStockMOS/view/' . $jb->JobNo)  ?>">
												<h3 style="color: aliceblue;">Inventory Material <br> On Site</h3>

												<p style="color: aliceblue;">Stock Material dan Barang yang Ada di Site </p>
											</a>
										<?php endforeach; ?>
									</div>
									<div class="icon">
										<i class="fa fa-exchange"></i>
									</div>
									<a href="<?php echo site_url('MOS/FrmStockMOS/view/' . $jb->JobNo)  ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>





							<a href="#" class="btn bg-black " style="width: 100%;"><strong>PERMINTAAN DANA & PERTANGGUNGJAWABAN DANA</strong></a>

							<p></p>

							<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->PD == 0) ? 'hidden' : '' ?>>


								<div class="small-box bg-purple">
									<div class="inner">
										<?php foreach ($job as $jb) : ?>
											<!-- <a href="<?php echo site_url('Permintaan_dana_lapangan/view/' . $jb->JobNo)  ?>"> -->
											<a href="<?php echo site_url('Entry/PDPJ/FrmPDLap/view/' . $jb->JobNo)  ?>">
												<h3 style="color: aliceblue;">PD LAPANGAN</h3>

												<p style="color: aliceblue;">PD Lapangan</p>
											</a>
										<?php endforeach;  ?>
									</div>
									<div class="icon">
										<i class="fa fa-exchange"></i>
									</div>
									<!-- <a href="<?php echo site_url('Permintaan_dana_lapangan/view/' . $jb->JobNo)  ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a> -->
									<a href="<?php echo site_url('Entry/PDPJ/FrmPDLap/view/' . $jb->JobNo)  ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
								</div>

							</div>

							<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->PDFinance == 0) ? 'hidden' : '' ?>>
								<div class="small-box" style="background-color: #2980b9;">
									<div class="inner">
										<?php foreach ($job as $jb) : ?>
											<!-- <a href="<?php echo site_url('Permintaan_dana_keuangan/view/' . $jb->JobNo)  ?>"> -->
											<a href="<?php echo site_url('Entry/PDPJ/FrmPDKeu/view/' . $jb->JobNo)  ?>">
												<h3 style="color: aliceblue;">PD KEUANGAN</h3>

												<p style="color: aliceblue;">PD KEUANGAN</p>
											</a>
										<?php endforeach;  ?>
									</div>
									<div class="icon">
										<i class="fa fa-exchange"></i>
									</div>
									<!-- <a href="<?php echo site_url('Permintaan_dana_keuangan/view/' . $jb->JobNo)  ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a> -->

									<a href="<?php echo site_url('Entry/PDPJ/FrmPDKeu/view/' . $jb->JobNo)  ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>

							<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->ApprovalPD_KT == 0) ? 'hidden' : '' ?>>
								<!-- small box -->
								<div class="small-box bg-orange">
									<div class="inner">
										<?php foreach ($job as $jb) : ?>
											<!-- <a href="<?= site_url('Approve_permintaan_dana_lapangan/view/' . $jb->JobNo) ?>"> -->
											<a href="<?= site_url('Entry/PDPJ/ApprovalPDCostControl_C/index/' . $jb->JobNo) ?>">
												<h3 style="color: aliceblue;">PD Cost Control</h3>
												<!-- <p style="color: aliceblue;font-size: 22px !important;">APPROVE PD Cost Control</p> -->
												<p style="color: aliceblue;">APPROVE PD Cost Control</p>
											</a>
										<?php endforeach; ?>
									</div>
									<div class="icon">
										<i class="fa fa-bookmark-o"></i>
									</div>
									<a href="<?= site_url('Entry/PDPJ/ApprovalPDCostControl_C/index/' . $jb->JobNo) ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
								</div>


							</div>

							<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->ApprovalPD_KK == 0) ? 'hidden' : '' ?>>
								<div class="small-box" style="background-color: #16a085;">
									<div class="inner">
										<?php foreach ($job as $jb) : ?>
											<!-- <a href="<?= site_url('Approve_permintaan_dana_keuangan/view/' . $jb->JobNo) ?>"> -->
											<a href="<?= site_url('Entry/PDPJ/ApprovalPDKeuangan_C/index/' . $jb->JobNo) ?>">
												<h3 style="color: aliceblue;">APPROVE PD KEU</h3>

												<p style="color: aliceblue;">Approve PD Keuangan</p>
											</a>
										<?php endforeach; ?>
									</div>
									<div class="icon">
										<i class="fa fa-bookmark-o"></i>
									</div>
									<a href="<?= site_url('Entry/PDPJ/ApprovalPDKeuangan_C/index/' . $jb->JobNo) ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>


							<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->ApprovalPJ_KT == 0) ? 'hidden' : '' ?> hidden>
								<!-- small box -->
								<div class="small-box bg-purple">
									<div class="inner">
										<?php foreach ($job as $jb) : ?>
											<a href="<?php echo site_url('job/pdpj/' . $jb->JobNo)  ?>">
												<h3 style="color: aliceblue;">Approve/ PJ</h3>

												<p style="color: aliceblue;">PJ Lapangan, PJ Keuangan</p>
											</a>
										<?php endforeach;  ?>
									</div>
									<div class="icon">
										<i class="fa fa-exchange"></i>
									</div>
									<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>

							<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->PJ == 0) ? 'hidden' : '' ?>>
								<!-- small box -->
								<div class="small-box bg-red">
									<div class="inner">
										<?php foreach ($job as $jb) : ?>
											<!-- <a href="<?php echo site_url('PDPJ/PJ_lapangan_entry_controller/pj_lapangan_list/' . $jb->JobNo)  ?>"> -->
											<a href="<?php echo site_url('FrmPJLap/view/' . $jb->JobNo) ?>">
												<h3 style="color: aliceblue;">ENTRY PJ LAPANGAN</h3>

												<p style="color: aliceblue;">Entry Pertanggungjawaban Dana (Lapangan)</p>
											</a>
										<?php endforeach; ?>
									</div>
									<div class="icon">
										<i class="fa fa-bookmark-o"></i>
									</div>
									<a href="<?php echo site_url('FrmPJLap/view/' . $jb->JobNo) ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>

							<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->PJFinance == 0) ? 'hidden' : '' ?>>
								<!-- small box -->
								<div class="small-box bg-blue">
									<div class="inner">
										<?php foreach ($job as $jb) : ?>
											<!-- <a href="<?php echo site_url('PDPJ/PJ_keuangan_entry_controller/pj_keuangan_list/' . $jb->JobNo)  ?>"> -->
											<a href="<?php echo site_url('FrmPJKeu/view/' . $jb->JobNo) ?>">
												<h3 style="color: aliceblue;">ENTRY PJ KEUANGAN</h3>

												<p style="color: aliceblue;">Entry Pertanggungjawaban Dana (Keuangan)</p>
											</a>
										<?php endforeach; ?>
									</div>
									<div class="icon">
										<i class="fa fa-bookmark-o"></i>
									</div>
									<a href="<?php echo site_url('FrmPJKeu/view/' . $jb->JobNo) ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
					</div>

					<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->ApprovalPJ_KT == 0) ? 'hidden' : '' ?>>
						<!-- small box -->
						<div class="small-box bg-red">
							<div class="inner">
								<?php foreach ($job as $jb) : ?>
									<a href="<?php echo site_url('Approval_PDPJ/Approval_PJ_CostControl/Pj_cost_control_data_list/' . $jb->JobNo)  ?>">
										<h3 style="color: aliceblue;">Approval PJ Cost Control</h3>

										<p style="color: aliceblue;">Approval Pertanggungjawaban Dana (Cost Control)</p>
									</a>
								<?php endforeach; ?>
							</div>
							<div class="icon">
								<i class="fa fa-bookmark-o"></i>
							</div>
							<a href="<?php echo site_url('Approval_PDPJ/Approval_PJ_CostControl/Pj_cost_control_data_list/' . $jb->JobNo)  ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>

					<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->ApprovalPJ_KK == 0) ? 'hidden' : '' ?>>
						<!-- small box -->
						<div class="small-box bg-blue">
							<div class="inner">
								<?php foreach ($job as $jb) : ?>
									<a href="<?php echo site_url('Approval_PDPJ/Approval_PJ_Keuangan/Pj_keuangan_data_list/' . $jb->JobNo)  ?>">
										<h3 style="color: aliceblue;">Approval PJ Keuangan</h3>

										<p style="color: aliceblue;">Approval Pertanggungjawaban Dana (Keuangan)</p>
									</a>
								<?php endforeach; ?>
							</div>
							<div class="icon">
								<i class="fa fa-bookmark-o"></i>
							</div>
							<a href="<?php echo site_url('Approval_PDPJ/Approval_PJ_Keuangan/Pj_keuangan_data_list/' . $jb->JobNo)  ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>

					<a href="#" class="btn bg-black" style="width: 100%;"> PROCUREMENT / PURCHASHING</a>

					</p>

					<div class="row">

						<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->KO == 0) ? 'hidden' : '' ?> style="display: none;">
							<div class="small-box bg-orange">
								<div class="inner">
									<?php foreach ($job as $jb) : ?>
										<a href="<?php echo site_url('job/FormKO/' . $jb->JobNo) ?>">
											<h3 style="color: aliceblue;">Daftar KO / PO</h3>

											<p style="color: aliceblue;">Tambah Kontrak, Tambah PO</p>
										</a>
									<?php endforeach; ?>
								</div>
								<div class="icon">
									<i class="fa fa-bookmark-o"></i>
								</div>
								<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>

						<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->KOAddendum == 0) ? 'hidden' : '' ?>>
							<div class="small-box bg-red">
								<div class="inner">
									<?php foreach ($job as $jb) : ?>
										<!-- <a href="<?php echo site_url('Ko_Addendum/view/' . $jb->JobNo) ?>"> -->
										<a href="<?= site_url('Entry/Procurement/KOAddendumRevisi_C/index/' . $jb->JobNo) ?>">
											<h3 style="color: aliceblue;">KO Add/Revisi</h3>

											<p style="color: aliceblue;">Create Addendum, History addendum </p>
										</a>
									<?php endforeach; ?>
								</div>
								<div class="icon">
									<i class="fa fa-bookmark-o"></i>
								</div>
								<a href="<?= site_url('Entry/Procurement/KOAddendumRevisi_C/index/' . $jb->JobNo) ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>

						<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->CancelPO == 0) ? 'hidden' : '' ?>>
							<div class="small-box bg-orange">
								<div class="inner">
									<?php foreach ($job as $jb) : ?>
										<!-- <a href="<?php echo site_url('Pembatalan_ko/view/' . $jb->JobNo) ?>"> -->
										<a href="<?php echo site_url('Entry/Procurement/PembatalanKO_C/index/' . $jb->JobNo) ?>">
											<h3 style="color: aliceblue;">Pembatalan KO</h3>

											<p style="color: aliceblue;">Pembatalan KO</p>
										</a>
									<?php endforeach; ?>
								</div>
								<div class="icon">
									<i class="fa fa-bookmark-o"></i>
								</div>
								<a href="<?php echo site_url('Entry/Procurement/PembatalanKO_C/index/' . $jb->JobNo) ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>

						<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->ClosingKO == 0) ? 'hidden' : '' ?>>
							<div class="small-box bg-orange">
								<div class="inner">
									<?php foreach ($job as $jb) : ?>
										<a href="<?php echo site_url('Entry/Procurement/FrmClosingKO_C/index/' . $jb->JobNo) ?>">
											<h3 style="color: aliceblue;">PENUTUPAN KO</h3>

											<p style="color: aliceblue;">Penutupan KO</p>
										</a>
									<?php endforeach; ?>
								</div>
								<div class="icon">
									<i class="fa fa-bookmark-o"></i>
								</div>
								<a href="<?php echo site_url('Entry/Procurement/FrmClosingKO_C/index/' . $jb->JobNo) ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>

						<!-- <div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->Akses_MOS_list == 0) ? 'hidden' : '' ?>>
									<div class="small-box bg-purple">
										<div class="inner">
											<?php foreach ($job as $jb) : ?>
												<a href="<?php echo site_url('job/mos/' . $jb->JobNo) ?>">
													<h3 style="color: aliceblue;">MOS</h3>

													<p style="color: aliceblue;">List / Data Material On Project </p>
												</a>
											<?php endforeach; ?>
										</div>
										<div class="icon">
											<i class="fa fa-bookmark-o"></i>
										</div>
										<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->Akses_ApprovalMOS == 0) ? 'hidden' : '' ?>>
									<div class="small-box bg-yellow">
										<div class="inner">
											<?php foreach ($job as $jb) : ?>
												<a href="<?php echo site_url('job/progressfisik/' . $jb->JobNo) ?>">
													<h3 style="color: aliceblue;">Approval MOS</h3>

													<p style="color: aliceblue;">Approval MOS Base On Mos Project</p>
												</a>
											<?php endforeach; ?>
										</div>
										<div class="icon">
											<i class="fa fa-bookmark-o"></i>
										</div>
										<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div> -->

						<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->Invoice == 0) ? 'hidden' : '' ?>>
							<div class="small-box bg-green">
								<div class="inner">
									<?php foreach ($job as $jb) : ?>
										<a href="<?php echo site_url('job/invoice/' . $jb->JobNo) ?>">
											<h3 style="color: aliceblue;">Invoice</h3>

											<p style="color: aliceblue;">Finance AP </p>
										</a>
									<?php endforeach; ?>
								</div>
								<div class="icon">
									<i class="fa fa-bookmark-o"></i>
								</div>
								<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>

						<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->Invoice == 0) ? 'hidden' : '' ?>>
							<div class="small-box bg-green">
								<div class="inner">
									<?php foreach ($job as $jb) : ?>
										<a href="#">
											<h3 style="color: aliceblue;">Verifikasi Invoice</h3>

											<p style="color: aliceblue;">Project Control </p>
										</a>
									<?php endforeach; ?>
								</div>
								<div class="icon">
									<i class="fa fa-bookmark-o"></i>
								</div>
								<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>

						<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->Invoice == 0) ? 'hidden' : '' ?>>
							<div class="small-box bg-green">
								<div class="inner">
									<?php foreach ($job as $jb) : ?>
										<a href="#">
											<h3 style="color: aliceblue;">Verifikasi Invoice</h3>

											<p style="color: aliceblue;">Procurement</p>
										</a>
									<?php endforeach; ?>
								</div>
								<div class="icon">
									<i class="fa fa-bookmark-o"></i>
								</div>
								<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>

						<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->Invoice == 0) ? 'hidden' : '' ?>>
							<div class="small-box bg-green">
								<div class="inner">
									<?php foreach ($job as $jb) : ?>
										<a href="#">
											<h3 style="color: aliceblue;">Verifikasi Invoice</h3>

											<p style="color: aliceblue;">Kopro </p>
										</a>
									<?php endforeach; ?>
								</div>
								<div class="icon">
									<i class="fa fa-bookmark-o"></i>
								</div>
								<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>

						<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->Invoice == 0) ? 'hidden' : '' ?>>
							<div class="small-box bg-green">
								<div class="inner">
									<?php foreach ($job as $jb) : ?>
										<a href="#">
											<h3 style="color: aliceblue;">Verifikasi Invoice</h3>

											<p style="color: aliceblue;">GM Operasi </p>
										</a>
									<?php endforeach; ?>
								</div>
								<div class="icon">
									<i class="fa fa-bookmark-o"></i>
								</div>
								<a href="#" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>




						<div class="col-lg-3 col-xs-6" <?= ($this->session->userdata('PecahToken')->TrackingKO == 0) ? 'hidden' : '' ?>>
							<div class="small-box bg-blue">
								<div class="inner">
									<?php foreach ($job as $jb) : ?>
										<a href="<?= site_url('Entry/Procurement/TrackingKO_C/') ?>">
											<h3 style="color: aliceblue;">Tracking KO</h3>

											<p style="color: aliceblue;">Tracking KO </p>
										</a>
									<?php endforeach; ?>
								</div>
								<div class="icon">
									<i class="fa fa-bookmark-o"></i>
								</div>
								<a href="<?= site_url('Entry/Procurement/TrackingKO_C/') ?>" class="small-box-footer">Klik here <i class="fa fa-arrow-circle-right"></i></a>
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
