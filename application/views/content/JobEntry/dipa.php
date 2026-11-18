

<div class="content-wrapper">
<!-- 	<section class="content-header">
		<h1 class="text-bold"><?=$judul?>
		<small style="font-size: 16px;" class="text-black">(<?=$dipa->JobNo.' - '.$dipa->JobNm.' - '.$dipa->Lokasi?>)</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">DIPA</li>
	</ol>
	<a href="<?=site_url('Job/sub_job/'.$dipa->JobNo)?>" class="btn btn-default btn-md" ><i class="fa fa-arrow-left"></i>  BACK</a>
</section> -->

<section class="content-header">
	<h1 class="text-bold">
		<?=$judul?>
		<?php if ($JobNo!=''): ?>
			<small style="font-size: 16px;" class="text-black">(<?=$dipa->JobNo.' - '.$dipa->JobNm.' - '.$dipa->Lokasi?>)</small>
		<?php endif ?>
	</h1>
	<a href="<?=site_url('Job/sub_job/'.$JobNo)?>" class="btn btn-default btn-md" ><i class="fa fa-arrow-left"></i>  BACK</a>
</section>

<section class="content">
	<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

	<div class="box box-primary">
		<div class="box-header">
			<div class="row">
				<div class="col-sm-12">

					<form id="myform" action="<?=site_url('Job/dipa/')?>" method="POST">
						<div class="table-responsive">

							<table width="100%" border="0">
								<tr style="font-size:14px;">
									<td width="100px"> Job No </td>
									<td width="10px" class="text-center">:</td>
									<td width="500px">
										<select name="JobNo" id="JobNo" class="form-control input-sm select2" style="font-size:14px;" required>
											<option value="">:: Pilih Salah Satu ::</option>
											<?php foreach ($ListJob as $row): ?>
												<option value="<?=$row->JobNo?>" <?=($JobNo == $row->JobNo) ? 'selected' : '' ?>  ><?=$row->JobNo.' - '.$row->JobNm?></option>
											<?php endforeach ?>
										</select>
									</td>
									<td></td>
								</tr>
							</table>


							<table width="100%" border="0" style="margin-top: 5px !important;">
								<tr>
									<td width="100px">  </td>
									<td width="10px" class="text-center"></td>
									<td width="500px">
										<button type="submit" class="btn btn-primary btn-md" id="btnTampilkan">TAMPILKAN</button>
										<button type="button" class="btn btn-primary btn-md" id="btnLoading" disabled="disabled" style="display:none;">LOADING...</button>
									</td>
									<td></td>
								</tr>
							</table>

						</div>
					</form>


				</div>
			</div>
		</div>
	</div>
	<script src="<?=base_url('assets/select2_baru.js')?>"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.select2').select2(); 
		})
	</script>


		
	
	<div class="box box-primary <?=($JobNo=='') ? 'hidden' : '' ?> " id="DIPA">
		<div class="box-header with-border">
			<h1 class="box-title" style="font-size: larger;">DIPA</h1>
			<p></p>
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-body">
								<a data-target="#modal-default" data-toggle="modal" class="btn btn-primary btn-md"><i class="fa fa-plus"></i> Tambah DIPA</a>
								<a href="<?=site_url('FrmDipa/ExportExcel_Dipa/'.$dipa->JobNo)?>" class="btn btn-default btn-md">EXPORT TO EXCEL <i class="fa fa-file-excel-o"></i> </a>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="modal fade" id="modal-default">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">Form Tambah Data DIPA</h4>
							</div>
							<?php echo form_open_multipart('DipaTermin/Dipa/aksi_tambah_dipa'); ?>
							<div class="modal-body">
								<input type="text" name="JobNo" hidden="" value="<?php echo $dipa->JobNo ?>">
								<p hidden=""><?= $this->session->userdata('MIS_LOGGED_NAME'); ?></p>
								<label>Tahun</label>

								<input type="text" name="Tahun" id="InfoPasar" class="form-control" onkeypress="return hanyaAngka(event)" placeholder="Masukkan Tahun Disini" minlength="4" maxlength="4" required>
								<label>DIPA (Rp)</label>

								<input type="text" name="Dipa" id="Dipa" class="form-control" onkeypress="return hanyaAngka(event)" placeholder="Masukkan DIPA (Rp) Disini" value="0" required>


								<label>DIPA Pagu Bruto</label>
								<input type="text" name="PaguBudget" id="PaguBudget" class="form-control" onkeypress="return hanyaAngka(event)" placeholder="Masukkan DIPA Pagu Bruto Disini" value="0" required>

								<input type="hidden" name="PaguBruto_dipa" value="<?=$PaguBruto_dipa?>">
								<input type="hidden" name="TotalPaguBudget_DIPA" value="<?=$TotalPaguBudget_DIPA?>">

							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-primary">Simpan</button>
							</div>
							<?php echo form_close(); ?>
						</div>
					</div>
				</div>


				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
				</div>
			</div>
			<div class="box-body">
				<!-- <div class="row"> -->
					<!-- <div class="col-md-6"> -->
						<div class="form-group">

							<div class="box-body table-responsive no-padding atur_ukuran ">
								<table class="table table-hover table-bordered table-striped" style="font-size: 14px !important;" cellspacing="2" width="100%">

									<tr style="background-color: coral;">
										<!-- <th></th> -->
										<th class="text-center">Tahun</th>
										<th class="text-center">DIPA (Rp)</th>
										<th class="text-center">DIPA Pagu Bruto (Rp)</th>
										<th class="text-center" width="200px">Action</th>
									</tr>
									<?php foreach ($tbldipa as $dipa) : ?>
										<tr class="atur_ukuran">
											<td class="text-center"><?= $dipa->Tahun ?></td>
											<td class="text-right"><?= number_format($dipa->Budget); ?></td>
											<td class="text-right"><?= number_format($dipa->PaguBudget); ?></td>
											<td class="text-center">
												<a onclick="return confirm('Anda yakin ingin menghapus data ini ?')" href="<?=site_url('DipaTermin/Dipa/aksi_hapus_dipa/'.$dipa->JobNo.'/'.$dipa->id_Dipa)?>" class="btn btn-danger btn-md" title="Hapus DIPA">HAPUS <i class="fa fa-trash"></i></a>
												<button type="button" data-toggle="modal" data-target="#modal-EditDipa<?php echo $dipa->id_Dipa; ?>" class="btn btn-success btn-md">EDIT <i class="fa fa-edit"></i></button>
											</td>
										</tr>
									<?php endforeach; ?>
									<tr style="background-color:lightgray;">
										<td colspan="1" style="font-size: 14px !important;font-weight: bold;">TOTAL</td>
										<td  style="font-size: 14px !important;font-weight: bold;" class="text-right" ><?php echo number_format($ttlDipa->Budget);?></td>
										<td  style="font-size: 14px !important;font-weight: bold;" class="text-right"><?php echo number_format($ttlPaguBudget->PaguBudget);?></td>
										<td></td>
									</tr>

								</table>
							</div>

						</div>
						<!-- </div> -->
						<!-- </div> -->
					</div>
				</div>

				<?php foreach ($tbldipa as $dipa) : ?>
					<div class="modal fade" id="modal-EditDipa<?php echo $dipa->id_Dipa ?>">
						<form action="<?php echo base_url('DipaTermin/Dipa/aksi_ubah_dipa') ?>" method="POST">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title">Edit Dipa</h4>
										</div>
										<div class="modal-body">

											<input type="hidden" name="id_Dipa"  value="<?php echo $dipa->id_Dipa ?>">
											<input type="hidden" name="JobNo" id="JobNo"  value="<?php echo $dipa->JobNo ?>">

											<input type="text" name="Tahun" id="InfoPasar" class="form-control" onkeypress="return hanyaAngka(event)" placeholder="Masukkan Tahun Disini" minlength="4" maxlength="4" value="<?=$dipa->Tahun?>" required>
											<label>DIPA (Rp)</label>

											<input type="text" name="Dipa" id="Dipa" class="form-control" onkeypress="return hanyaAngka(event)" placeholder="Masukkan DIPA (Rp) Disini" value="<?=new_decimal($dipa->Budget)?>" required>


											<label>DIPA Pagu Bruto</label>
											<input type="text" name="PaguBudget" id="PaguBudget" class="form-control" onkeypress="return hanyaAngka(event)" placeholder="Masukkan DIPA Pagu Bruto Disini" value="<?=new_decimal($dipa->PaguBudget)?>" required>

											<input type="hidden" name="PaguBruto_dipa" value="<?=$PaguBruto_dipa?>">
											<input type="hidden" name="TotalPaguBudget_DIPA" value="<?=$TotalPaguBudget_DIPA?>">

											<input type="hidden" name="TahunLama" value="<?=$dipa->Tahun?>">

										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
											<button type="Submit" class="btn btn-primary">Update</button>
										</div>
									</div>
									<!-- /.modal-content -->
								</div>
							</form>
							<!-- /.modal-dialog -->
						</div>
					<?php endforeach; ?>


					<div class="box box-primary <?=($JobNo=='') ? 'hidden' : '' ?>">
						<div class="box-header with-border">
							<h1 class="box-title" style="font-size: larger;">RENCANA TERMIN</h1>
							<p></p>
							<div class="container-fluid">
								<div class="row">
									<div class="col-12">
										<div class="card">
											<div class="card-body">
												<!-- <a href="<?= site_url('job/sub_job') ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a> -->
												<a data-target="#TambahDPRencanaTermin" data-toggle="modal" class="btn btn-flickr"><i class="fa fa-plus"></i> Input Uang Muka Rencana Termin</a>
												<a data-target="#TambahRencanaTermin" data-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Data Rencana Termin</a>

												<a href="<?=site_url('FrmDipa/ExportExcel_RencanaTermin/'.$dipa->JobNo)?>" class="btn btn-default btn-md">EXPORT TO EXCEL <i class="fa fa-file-excel-o"></i> </a>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="modal fade" id="TambahDPRencanaTermin">
								<div class="modal-dialog" style="width: 40%;">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title">DATA ENTRY TERMIN</h4>
												<p hidden><?= $this->session->userdata('MIS_LOGGED_NAME'); ?></p>
											</div>

											<form action="<?= site_url('Job/addDPtermin')  ?>" method="post">
												<div class="modal-body">
													<div class="form-group row">
														<label class="col-sm-4 col-form-label">Jenis Termin</label>
														<div class="col-sm-7">
															<select name="JenisTermin" class="form-control">
																<option value="">--Pilih Jenis Termin--</option>
																<option value="Uang Muka">Uang Muka</option>
																<option value="Termin">Termin</option>
																<option value="Retensi">Retensi</option>
															</select>
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-4 col-form-label">Rencana Tgl Jatuh Tempo</label>
														<div class="col-sm-7">
															<input type="date" name="tglRencanaTermin" class="form-control" id="TglRencanaTermin">
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-4 col-form-label">Uraian</label>
														<div class="col-sm-7">
															<input type="text" class="form-control" name="UraianTermin" placeholder="Uraian Termin" required>
															<input type="text" name="Budget" id="Budget" value="<?php echo $ttlDipa->Budget ?>" hidden>
															<?php foreach ($tbldipa as $dp) : ?>
																<input type="text" name="JobNo" id="JobNo" value="<?php echo $dp->JobNo ?>" hidden>
															<?php endforeach; ?>
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-4 col-form-label">Presentasi Rencana</label>
														<label style="align-content: center;">
															<h4 style="align-content: center;">%</h4>
														</label>
														<div class="col-sm-3">
															<input type="text" name="TxtPersentase" id="TxtPersentase" class="form-control" onkeyup="Rtermin()">
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-4 col-form-label">Nominal Rencana (A)</label>
														<div class="col-sm-7">
															<input type="text" class="form-control" name="TxtA" id="TxtA" onkeyup="toDecimal(this,Rtermin())" value="0">
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-4 col-form-label">Potongan Uang Muka (B = A x n%)</label>
														<label style="align-content: center;">
															<h4 style="align-content: center;">%</h4>
														</label>
														<div class="col-sm-3">
															<input type="text" class="form-control" name="TxtD" id="TxtD" value="0">
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-4 col-form-label"></label>
														<div class="col-sm-7">
															<input type="text" class="form-control" name="TxtUM" id="TxtUM" value="0" onkeyup="Rtermin()">
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-4 col-form-label">Retensi (C = A x 5%) &nbsp <input type="checkbox" name="cbRetensi[]" alt="Checkbox" id="cbRetensi" onkeyup="Rtermin()" value="1"></label>
														<div class="col-sm-7">
															<input type="text" class="form-control" name="TxtE" id="TxtE" value="0" onkeyup="Rtermin()">
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-4 col-form-label">Total Potongan (D = B + C)</label>
														<div class="col-sm-7">
															<input type="text" class="form-control" name="TxtF" id="TxtF" onkeyup="Rtermin()" value="0">
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-4 col-form-label">Pembayaran Fisik (E = A - D)*Inc PPN</label>
														<div class="col-sm-7">
															<input type="text" class="form-control" name="TxtG" id="TxtG" value="0" onkeyup="Rtermin()">
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-4 col-form-label">PPN (F = (E/1.11) x 11%) &nbsp <input type="checkbox" name="cbPPN[]" alt="Checkbox" id="cbPPN" onkeyup="Rtermin()" value="2"></label>
														<div class="col-sm-7">
															<input type="text" class="form-control" name="TxtH" id="TxtH" value="0" onkeyup="Rtermin()">
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-4 col-form-label">Total Nett Exc. PPN (G = E- F)</label>
														<div class="col-sm-7">
															<input type="text" class="form-control" name="TxtI" id="TxtI" value="0" onkeyup="Rtermin()">
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-4 col-form-label">PPH (H = (E/1.1) x 2.65%) &nbsp <input type="checkbox" name="cbPPH[]" alt="Checkbox" id="cbPPH" onkeyup="Rtermin()" value="3"></label>
														<div class="col-sm-7">
															<input type="text" class="form-control" name="TxtJ" id="TxtJ" value="0" onkeyup="Rtermin()">
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-4 col-form-label">Netto Exc. PPN & PPH (I = G-H)</label>
														<div class="col-sm-7">
															<input type="text" class="form-control" name="TxtK" id="TxtK" value="0" onkeyup="Rtermin()">
														</div>
													</div>

													<div class="modal-footer">
														<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
														<button type="submit" class="btn btn-primary">Save changes</button>
													</div>
												</div>
											</form>
										</div>
										<!-- /.modal-content -->
									</div>
									<!-- /.modal-dialog -->
								</div>


								<div class="modal fade" id="TambahRencanaTermin">
									<div class="modal-dialog" style="width: 40%;">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span></button>
													<h4 class="modal-title">DATA ENTRY TERMIN</h4>
												</div>

												<form action="<?= site_url('Job/TambahTermin') ?>" method="post">
													<div class="modal-body">
														<form class="form-horizontal">
															<div class="form-group row">
																<label class="col-sm-4 col-form-label">Jenis Termin</label>
																<div class="col-sm-7">
																	<select name="JenisTermin1" class="form-control">
																		<option value="">--Pilih Jenis Termin--</option>
																		<option value="Uang Muka">Uang Muka</option>
																		<option value="Termin">Termin</option>
																		<option value="Retensi">Retensi</option>
																	</select>
																</div>
															</div>
															<div class="form-group row">
																<label class="col-sm-4 col-form-label">Rencana Tgl Jatuh Tempo</label>
																<div class="col-sm-7">
																	<input type="date" name="tglRencanaTermin1" class="form-control" id="TglRencanaTermin1">
																</div>
															</div>
															<div class="form-group row">
																<label class="col-sm-4 col-form-label">Uraian</label>
																<div class="col-sm-7">
																	<input type="text" class="form-control" name="UraianTermin1" placeholder="Uraian Termin" onkeyup="this.value = this.value.toUpperCase()" required>
																	<input type="text" name="Budget1" id="Budget1" value="<?php echo $ttlDipa->Budget ?>" hidden>
																	<?php foreach ($tbldipa as $dp) : ?>
																		<input type="text" name="JobNo1" id="JobNo1" value="<?php echo $dp->JobNo ?>" hidden>
																	<?php endforeach; ?>
																</div>
															</div>
															<div class="form-group row">
																<label class="col-sm-4 col-form-label">Presentasi Rencana</label>
																<label style="align-content: center;">
																	<h4 style="align-content: center;">%</h4>
																</label>
																<div class="col-sm-3">
																	<input type="text" name="TxtPersentase1" id="TxtPersentase1" class="form-control" onkeyup="Rtermin1()">
																</div>
															</div>
															<div class="form-group row">
																<label class="col-sm-4 col-form-label">Nominal Rencana (A)</label>
																<div class="col-sm-7">
																	<input type="text" class="form-control" name="TxtA1" id="TxtA1" onkeyup="toDecimal(this,Rtermin1())" value="0">
																</div>
															</div>
															<div class="form-group row">
																<label class="col-sm-4 col-form-label">Potongan Uang Muka (B=Axn%)</label>
																<label style="align-content: center;">
																	<h4 style="align-content: center;">%</h4>
																</label>
																<div class="col-sm-3">
																	<?php foreach ($GetBruto as $gb) : ?>
																		<input type="text" class="form-control" name="TxtD1" id="TxtD1" value="<?php echo $gb->Persentase ?>">
																	<?php endforeach; ?>
																</div>
															</div>
															<div class="form-group row">
																<label class="col-sm-4 col-form-label"></label>
																<div class="col-sm-7">
																	<input type="text" class="form-control" name="TxtUM1" id="TxtUM1" value="0" onkeyup="Rtermin1()">
																</div>
															</div>
															<div class="form-group row">

																<label class="col-sm-4 col-form-label">Retensi (C=Ax5%) &nbsp <input type="checkbox" name="cbRetensi1[]" id="cbRetensi1" onkeyup="Rtermin1()" value="1"></label>

																<div class="col-sm-7">
																	<input type="text" class="form-control" name="TxtE1" id="TxtE1" value="0" onkeyup="Rtermin1()">
																</div>
															</div>
															<div class="form-group row">
																<label class="col-sm-4 col-form-label">Total Potongan (D=B+C)</label>
																<div class="col-sm-7">
																	<input type="text" class="form-control" name="TxtF1" id="TxtF1" onkeyup="Rtermin1()" value="0">
																</div>
															</div>
															<div class="form-group row">
																<label class="col-sm-4 col-form-label">Pembayaran Fisik (E=A-D)*Inc PPN</label>
																<div class="col-sm-7">
																	<input type="text" class="form-control" name="TxtG1" id="TxtG1" value="0" onkeyup="Rtermin1()">
																</div>
															</div>
															<div class="form-group row">
																<label class="col-sm-4 col-form-label">PPN (F=(E/1.11) x11%) &nbsp <input type="checkbox" name="cbPPN1[]" alt="Checkbox" id="cbPPN1" onkeyup="toDecimal(this, Rtermin1())" value="2"></label>
																<div class="col-sm-7">
																	<input type="text" class="form-control" name="TxtH1" id="TxtH1" value="0" onkeyup="toDecimal(this, Rtermin1())">
																</div>
															</div>
															<div class="form-group row">
																<label class="col-sm-4 col-form-label">Total Nett Exc. PPN (G = E- F)</label>
																<div class="col-sm-7">
																	<input type="text" class="form-control" name="TxtI1" id="TxtI1" value="0" onkeyup="Rtermin1()">
																</div>
															</div>
															<div class="form-group row">
																<label class="col-sm-4 col-form-label">PPH (H = (E/1.11)x2.65%)&nbsp<input type="checkbox" name="cbPPN1[]" alt="Checkbox" id="cbPPN1" onkeyup="Rtermin()" value="3"></label>
																<div class="col-sm-7">
																	<input type="text" class="form-control" name="TxtJ1" id="TxtJ1" value="0" onkeyup="Rtermin1()">
																</div>
															</div>
															<div class="form-group row">
																<label class="col-sm-4 col-form-label">Netto Exc. PPN & PPH (I = G-H) </label>
																<div class="col-sm-7">
																	<input type="text" class="form-control" name="TxtK1" id="TxtK1" value="0" onkeyup="Rtermin1()">
																</div>
															</div>

															<div class="modal-footer">
																<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
																<button type="Submit" class="btn btn-primary">Save changes</button>
															</div>
														</form>
													</div>
												</form>
											</div>
											<!-- /.modal-content -->
										</div>
										<!-- /.modal-dialog -->
									</div>

									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
										<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
									</div>
								</div>

								<div class="box-body">
									<!-- <div class="row"> -->
										<!-- <div class="col-md-6"> -->
											<div class="form-group">

												<div class="box-body table-responsive no-padding">
													<table class="table table-hover" border="1" cellspacing="2" style="font-size: 14px !important;" width="100%">

														<tr style="background-color: coral;" class="text-center">
															<th hidden></th>
															<th class="text-center">NO</th>
															<th class="text-center">Tgl Rencana Termin</th>
															<th class="text-center">Jenis</th>
															<th class="text-center">Uraian</th>
															<th class="text-center">Presentase (%)</th>
															<th class="text-center">Bruto (Rp)</th>
															<th class="text-center">Netto (Rp)</th>
															<th class="text-center"></th>
														</tr>

														<?php $no = 1;
														foreach ($tblRtermin as $j => $value) : ?>
															<tr>
																<td hidden><?= $value->LedgerNo; ?></td>
																<td><?php echo $no++ ?></td>
																<td><?= tgl_baru($value->TglRencana); ?></td>
																<td><?= $value->Jenis; ?></td>
																<td><?= $value->Uraian; ?></td>
																<td class="text-right"><?= $value->Persentase; ?></td>
																<td class="text-right"><?= number_format($value->Bruto); ?></td>
																<td class="text-right"><?= number_format($value->Netto); ?></td>
																<td class="text-center">
																	<button type="button" data-toggle="modal" data-target="#EditRencanaTermin<?php echo $value->LedgerNo; ?>" class="btn btn-success btn-md">SELECT</button>
																	<a href="<?= site_url('Job/DelRTermin/' . $value->LedgerNo); ?>" onclick="return confirm('Yaqin Hapus Data..?')" type="submit" class="btn btn-danger btn-md">DELETE</a>

																</td>
															</tr>
														<?php endforeach; ?>
														<tr style="background-color:lightgray;">
															<td colspan="5" rowspan="5" style="font-size:16px !important;font-weight: bold;">TOTAL</td>
															<td style="font-size:16px !important;font-weight: bold;text-align: right;"><?php echo number_format($SumRTBruto->Bruto); ?></td>
															<td style="font-size:16px !important;font-weight: bold;text-align: right;"><?php echo number_format($SumRTNetto->Netto); ?></td>
															<td></td>
														</tr>
													</table>
												</div>
											</div>
										</div>

										<?php foreach ($tblRtermin as $j => $value) : ?>
											<div class="modal fade" id="EditRencanaTermin<?php echo $value->LedgerNo; ?>">
												<div class="modal-dialog" style="width: 40%;">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span></button>
																<h4 class="modal-title">DATA ENTRY TERMIN</h4>
															</div>

															<div class="modal-body">
																<form class="form-horizontal">
																	<div class="form-group row">
																		<!-- <?php $JenisTermin = ['', 'UANG MUKA', 'TERMIN', 'RETENSI']; ?> -->
																		<label class="col-sm-4 col-form-label">Jenis Termin</label>
																		<div class="col-sm-7">
																			<input type="text" name="JenisTermin2" id="JenisTermin2" class="form-control" value="<?php echo $value->Jenis ?>">
																		</div>
																	</div>
																	<div class="form-group row">
																		<label class="col-sm-4 col-form-label">Rencana Tgl Jatuh Tempo</label>
																		<div class="col-sm-7">
																			<input type="date" name="tglRencanaTermin2" class="form-control" id="TglRencanaTermin2" value="<?php echo $value->TglRencana; ?>">
																		</div>
																	</div>
																	<div class="form-group row">
																		<label class="col-sm-4 col-form-label">Uraian</label>
																		<div class="col-sm-7">
																			<input type="text" class="form-control" name="UraianTermin2" placeholder="Uraian Termin" id="UraianTermin2" value="<?php echo $value->Uraian ?>" required>
																			<input type="text" name="Budget2" id="Budget2" value="<?php echo $ttlDipa->Budget ?>" hidden>
																			<?php foreach ($tbldipa as $dp) : ?>
																				<input type="text" name="JobNo2" id="JobNo2" value="<?php echo $dp->JobNo ?>" hidden>
																			<?php endforeach; ?>
																		</div>
																	</div>
																	<div class="form-group row">
																		<label class="col-sm-4 col-form-label">Presentasi Rencana</label>
																		<label style="align-content: center;">
																			<h4 style="align-content: center;">%</h4>
																		</label>
																		<div class="col-sm-3">
																			<input type="text" name="TxtPersentase2" id="TxtPersentase2" class="form-control" value="<?php echo $value->Persentase ?>" onkeyup="Rtermin2()">
																		</div>
																	</div>
																	<div class="form-group row">
																		<label class="col-sm-4 col-form-label">Nominal Rencana (A)</label>
																		<div class="col-sm-7">
																			<input type="text" class="form-control" name="TxtA2" id="TxtA2" onkeyup="toDecimal(this,Rtermin2())" value="<?php echo number_format($value->Bruto) ?>">
																		</div>
																	</div>
																	<div class="form-group row">
																		<label class="col-sm-4 col-form-label">Potongan Uang Muka (B = A x n%)</label>
																		<label style="align-content: center;">
																			<h4 style="align-content: center;">%</h4>
																		</label>
																		<div class="col-sm-3">
																			<?php foreach ($GetBruto as $gb) : ?>
																				<input type="text" class="form-control" name="TxtD2" id="TxtD2" value="<?php echo $value->PersentaseUM ?>">
																			<?php endforeach; ?>
																		</div>
																	</div>
																	<div class="form-group row">
																		<label class="col-sm-4 col-form-label"></label>
																		<div class="col-sm-7">
																			<input type="text" class="form-control" name="TxtUM2" id="TxtUM2" value="0" onkeyup="Rtermin2()">
																		</div>
																	</div>
																	<div class="form-group row">

																		<label class="col-sm-4 col-form-label">Retensi (C = A x 5%) &nbsp <input type="checkbox" name="cbRetensi2[]" id="cbRetensi2" onkeyup="Rtermin2()" value="1"></label>

																		<div class="col-sm-7">
																			<input type="text" class="form-control" name="TxtE2" id="TxtE2" value="0" onkeyup="Rtermin2()">
																		</div>
																	</div>
																	<div class="form-group row">
																		<label class="col-sm-4 col-form-label">Total Potongan (D = B + C)</label>
																		<div class="col-sm-7">
																			<input type="text" class="form-control" name="TxtF2" id="TxtF2" onkeyup="Rtermin2()" value="0">
																		</div>
																	</div>
																	<div class="form-group row">
																		<label class="col-sm-4 col-form-label">Pembayaran Fisik (E = A - D)*Inc PPN</label>
																		<div class="col-sm-7">
																			<input type="text" class="form-control" name="TxtG2" id="TxtG2" value="0" onkeyup="Rtermin2()">
																		</div>
																	</div>
																	<div class="form-group row">
																		<label class="col-sm-4 col-form-label">PPN (F = (E/1.1) x10%)&nbsp <input type="checkbox" name="cbPPN[]" id="cbPPN" onkeyup="Rtermin2()" value="2"></label>
																		<div class="col-sm-7">
																			<input type="text" class="form-control" name="TxtH2" id="TxtH2" value="0" onkeyup="Rtermin2()">
																		</div>
																	</div>
																	<div class="form-group row">
																		<label class="col-sm-4 col-form-label">Total Nett Exc. PPN (G = E- F)</label>
																		<div class="col-sm-7">
																			<input type="text" class="form-control" name="TxtI2" id="TxtI2" value="0" onkeyup="Rtermin2()">
																		</div>
																	</div>
																	<div class="form-group row">
																		<label class="col-sm-4 col-form-label">PPH (H = (E/1.1)x3%)&nbsp <input type="checkbox" name="cbPPH[]" id="cbPPH" onkeyup="Rtermin2()" value="3"></label>
																		<div class="col-sm-7">
																			<input type="text" class="form-control" name="TxtJ2" id="TxtJ2" value="0" onkeyup="Rtermin2()">
																		</div>
																	</div>
																	<div class="form-group row">
																		<label class="col-sm-4 col-form-label">Netto Exc. PPN & PPH (I = G-H)</label>
																		<div class="col-sm-7">
																			<input type="text" class="form-control" name="TxtK2" id="TxtK2" value="<?php echo number_format($value->Netto)  ?>" onkeyup="Rtermin2()">
																		</div>
																	</div>

																	<div class="modal-footer">
																		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
																		<button type="button" class="btn btn-primary">Save changes</button>
																	</div>
																</form>
															</div>

														</div>
														<!-- /.modal-content -->
													</div>
													<!-- /.modal-dialog -->
												</div>
											<?php endforeach; ?>
										</div>

										<div class="box box-primary" style="display:none;">
											<div class="box-header with-border">
												<h1 class="box-title" style="font-size: larger;">PENERIMAAN TERMIN</h1>
												<p></p>

												<div class="box-tools pull-right">
													<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
													<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
												</div>
											</div>
											<div class="box-body">
												<!-- <div class="row"> -->
													<!-- <div class="col-md-6"> -->
														<div class="form-group">

															<div class="box-body table-responsive no-padding">
																<div class="col-md-12">
																	<div class="card">
																		<div class="card-header p-2">
																			<ul class="nav nav-pills">
																				<li class="nav-item"><a class="nav-link active" href="#TerminInduk" data-toggle="tab">Termin Induk</a></li>
																				<li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Termin Member</a></li>
																			</ul>
																		</div><!-- /.card-header -->


																		<div class="card-body">
																			<div class="tab-content">
																				<div class="active tab-pane" id="TerminInduk">
																					<p></p>
																					<div class="container-fluid">
																						<div class="row">
																							<div class="col-12">
																								<div class="card">
																									<div class="card-body">
																										<!-- <a href="<?= site_url('job/sub_job') ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a> -->
																										<a data-target="#modal-default-TI" data-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah</a>
																									</div>
																								</div>
																							</div>
																						</div>
																					</div>
																					<!-- Post -->

																					<form action="<?= site_url('Job/SimpanTerminInduk') ?>" method="POST">
																						<div class="modal fade" id="modal-default-TI">
																							<div class="modal-dialog">
																								<div class="modal-content">
																									<div class="modal-header">
																										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																											<span aria-hidden="true">&times;</span></button>
																											<h4 class="modal-title">Input Termin Induk</h4>
																										</div>
																										<div class="modal-body">
																											<form class="form-horizontal">
																												<div class="form-group row">
																													<label for="inputName" class="col-sm-4 col-form-label">Jenin Termin</label>
																													<input type="text" name="JobNo" id="JobNo" value="<?php echo $dipa->JobNo; ?>" hidden>
																													<?php $JenisTermin = ['', 'Uang Muka', 'Termin']; ?>
																													<div class="col-sm-8">
																														<select name="JenisTermin" class="form-control">
																															<?php foreach ($JenisTermin as $jtm) : ?>
																																<option value="<?php echo $jtm ?>"><?php echo $jtm ?></option>
																															<?php endforeach; ?>
																														</select>
																													</div>
																												</div>
																												<div class="form-group row">
																													<label class="col-sm-4 col-form-label">Tgl Cair</label>
																													<div class="col-sm-8">
																														<input type="date" name="TglCair" class="form-control" id="TglCair">
																													</div>
																												</div>
																												<div class="form-group row">
																													<label class="col-sm-4 col-form-label">No BAP</label>
																													<div class="col-sm-8">
																														<input type="text" name="NoBap" class="form-control" id="NoBap" placeholder="No BAP">
																													</div>
																												</div>
																												<div class="form-group row">
																													<label class="col-sm-4 col-form-label">Uraian</label>
																													<div class="col-sm-8">
																														<input type="text" name="Uraian" class="form-control" name="UraianTermin" placeholder="Uraian"></input>
																													</div>
																												</div>
																												<div class="form-group row">
																													<label class="col-sm-4 col-form-label">Bruto BoQ (Rp)</label>
																													<div class="col-sm-8">
																														<input type="text" class="form-control" name="BrutoBoQ" id="BrutoBoQ" onkeyup="termininduk()">
																													</div>
																												</div>

																												<div class="form-group row">
																													<label class="col-sm-4 col-form-label">Potongan Uang Muka (Rp)</label>
																													<div class="col-sm-8">
																														<input type="text" class="form-control" name="PotUM" id="PotUM" onkeyup="termininduk()" value="0">
																													</div>
																												</div>

																												<div class="form-group row">
																													<label class="col-sm-4 col-form-label">Potongan Retensi (Rp)</label>
																													<div class="col-sm-8">
																														<input type="text" class="form-control" name="Potretensi" id="Potretensi" value="0" onkeyup="termininduk()">
																													</div>
																												</div>

																												<div class="form-group row">
																													<label class="col-sm-4 col-form-label">Netto BoQ (Rp)</label>
																													<div class="col-sm-8">
																														<input type="text" class="form-control" name="NettoBoQ" id="NettoBoQ" value="0">
																													</div>
																												</div>
																												<div class="form-group row">
																													<label class="col-sm-4 col-form-label">Penerimaan Fisik (Rp)</label>
																													<div class="col-sm-8">
																														<input type="text" class="form-control" name="Fisik" id="Fisik" value="0">
																													</div>
																												</div>
																												<div class="form-group row">
																													<label class="col-sm-4 col-form-label">PPN</label>
																													<div class="col-sm-3">
																														<select name="PPNTermin" id="ppntermin" class="form-control">
																															<option value="0">Tanpa PPN</option>
																															<option value="11">11%</option>
																															<option value="10">10%</option>
																														</select>
																													</div>
																													<div class="col-sm-5">
																														<input type="text" name="PPNValue" id="PPNValue" class="form-control">
																													</div>
																												</div>
																												<div class="form-group row">
																													<label class="col-sm-4 col-form-label">PPH Final</label>
																													<div class="col-sm-3">
																														<select name="PPHTermin" id="pphtermin" class="form-control">
																															<option value="0">Tanpa PPH</option>
																															<option value="3">3%</option>
																															<option value="2,65">2,65%</option>
																														</select>
																													</div>
																													<div class="col-sm-5">
																														<input type="text" class="form-control" name="PPh" id="PPh" value="0">
																													</div>
																												</div>
																												<div class="form-group row">
																													<label class="col-sm-4 col-form-label">Netto Penerimaan</label>
																													<div class="col-sm-8">
																														<input type="text" class="form-control" name="NettoTerima" id="Netto Terima" value="0">
																													</div>
																												</div>
																											</form>
																										</div>
																										<div class="modal-footer">
																											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
																											<button type="submit" class="btn btn-primary">Simpan</button>
																										</div>
																									</div>
																									<!-- /.modal-content -->
																								</div>
																								<!-- /.modal-dialog -->
																							</div>
																						</form>

																						<?php foreach ($getBrutoTermin as $j => $value) : ?>
																							<div class="form-group row">
																								<input type="text" class="text" name="TxtNetto" id="TxtNetto" style="float: right; width:125px; text-align: right;" value="<?php echo number_format($value->Netto) ?>" title="Netto" readonly>
																								<input type="text" class="text" name="TxtPPH" id="TxtPPH" style="float: right;width:125px; text-align: right;" value="<?php echo number_format($value->Bruto / 1.11 * 0.0265) ?>" title="PPH" readonly>
																								<input type="text" class="text" name="TxtPPN" id="TxtPPN" style="float: right;width:125px; text-align: right;" value="<?php echo number_format($value->Bruto / 1.11 * 0.11) ?>" onkeyup="NKTermin()" title="PPN" readonly>
																								<input type="text" class="text" name="TxtFisik" id="TxtFisik" style="float: right;width:125px; text-align: right;" value="<?php echo number_format($value->Bruto / 1.11) ?>" title="Fisik" readonly>
																								<input type="text" class="text" name="TxtBruto" id="TxtBruto" style="float: right; width:125px; text-align: right;" value="<?php echo number_format($value->Bruto) ?>" onchange="NKTermin()" title="Nilai Kontrak" readonly>
																								<input type="text" class="text" style="float: right; width:225px;" placeholder="NILAI KONTRAK INCL ADDENDUM" readonly>
																							</div>
																						<?php endforeach; ?>

																					</form>
																					<div class="box-body table-responsive no-padding">
																						<table class="table table-hover" border="1" cellspacing="2" width="100%" id="TI" methode="post">
																							<tr style="background-color: deepskyblue;">
																								<th hidden></th>
																								<th>No</th>
																								<th>Tgl Cair</th>
																								<th>No. BAP</th>
																								<th>Uraian</th>
																								<th>Bruto BOQ (Rp)</th>
																								<th>Potongan Uang Muka (Rp)</th>
																								<th>Potongan Retensi (Rp)</th>
																								<th>Netto BoQ (Rp)</th>
																								<th>Fisik (Rp)</th>
																								<th>PPN (Rp)</th>
																								<th>PPH Final (Rp)</th>
																								<th>Netto Penerimaan (Rp)</th>
																								<th>Action</th>
																							</tr>

																							<?php $no = 1;
																							foreach ($TblTerminInduk as $j) {

																								?>
																								<tr>
																									<td hidden><?php echo $j->LedgerNo ?></td>
																									<td><?php echo $no++ ?></td>
																									<td><?php echo $j->TglCair ?></td>
																									<td><?php echo $j->NoBAP ?></td>
																									<td><?php echo $j->Uraian ?></td>
																									<td><?php echo number_format($j->BrutoBOQ) ?></td>
																									<td><?php echo number_format($j->UM) ?></td>
																									<td><?php echo number_format($j->Retensi) ?></td>
																									<td id="bruto"><?php echo number_format($j->TerminInduk) ?></td>
																									<td><?php echo number_format($j->TerminInduk / 1.11) ?></td>
																									<td id="ppn"><?php echo number_format($j->TerminInduk / 1.11 * 0.11) ?></td>
																									<td id="pph"><?php echo number_format($j->TerminInduk / 1.11 * 0.0265) ?></td>
																									<td id="netto"><?php echo number_format($j->TerminInduk - $j->TerminInduk / 1.11 * 0.11 - $j->TerminInduk / 1.11 * 0.0265)  ?></td>
																									<td>
																										<button type="button" data-toggle="modal" data-target="#modal-edit-TI<?php echo $j->LedgerNo; ?>" class="btn btn-success badge btn-xs">Select</button>
																										<a href="<?= site_url('Job/DelTerminInduk/' . $j->LedgerNo); ?>" onclick="return confirm('Yaqin Hapus Data..?')" type="submit" class="btn btn-danger badge btn-xs">Delete</a>
																									</td>
																								</tr>
																								<?php
																							}
																							?>
																						</table>
																					</div>
																				</div>

																				<?php foreach ($TblTerminInduk as $j) : ?>
																					<form action="<?= site_url('Job/EditTerminInduk') ?>" method="POST">
																						<div class="modal fade" id="modal-edit-TI<?php echo $j->LedgerNo; ?>">
																							<div class="modal-dialog">
																								<div class="modal-content">
																									<div class="modal-header">
																										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																											<span aria-hidden="true">&times;</span></button>
																											<h4 class="modal-title">Edit Termin Induk</h4>
																											<p hidden><?= $this->session->userdata('MIS_LOGGED_NAME'); ?></p>
																										</div>
																										<div class="modal-body">
																											<form class="form-horizontal">
																												<div class="form-group row">
																													<label for="inputName" class="col-sm-4 col-form-label">Jenis Termin</label>
																													<input type="text" name="JobNo" id="JobNo" value="<?php echo $j->JobNo ?>" hidden>
																													<input type="text" name="LedgerNo" id="LedgerNo" value="<?php echo $j->LedgerNo ?>" hidden>
																													<?php $JenisTermin = ['', 'Uang Muka', 'Termin']; ?>
																													<div class="col-sm-8">
																														<select name="JenisTermin" class="form-control">

																															<option value="<?php echo $j->Jenis ?>"><?php echo $j->Jenis ?></option>

																														</select>
																													</div>
																												</div>
																												<div class="form-group row">
																													<label class="col-sm-4 col-form-label">Tgl Cair</label>
																													<div class="col-sm-8">
																														<input type="date" name="TglCair" class="form-control" id="TglCair" value="<?php echo $j->TglCair; ?>">
																													</div>
																												</div>
																												<div class="form-group row">
																													<label class="col-sm-4 col-form-label">No BAP</label>
																													<div class="col-sm-8">
																														<input type="text" name="NoBap" class="form-control" id="NoBap" placeholder="No BAP" value="<?php echo $j->NoBAP; ?>">
																													</div>
																												</div>
																												<div class="form-group row">
																													<label class="col-sm-4 col-form-label">Uraian</label>
																													<div class="col-sm-8">
																														<input type="text" name="Uraian" class="form-control" name="UraianTermin" placeholder="Uraian" value="<?php echo $j->Uraian; ?>"></input>
																													</div>
																												</div>
																												<div class="form-group row">
																													<label class="col-sm-4 col-form-label">Bruto BoQ (Rp)</label>
																													<div class="col-sm-8">
																														<input type="text" class="form-control" name="BrutoBoQ" id="BrutoBoQ" onkeyup="termininduk()" value="<?php echo number_format($j->BrutoBOQ); ?>">
																													</div>
																												</div>

																												<div class="form-group row">
																													<label class="col-sm-4 col-form-label">Potongan Uang Muka (Rp)</label>
																													<div class="col-sm-8">
																														<input type="text" class="form-control" name="PotUM" id="PotUM" onkeyup="termininduk()" value="<?php echo number_format($j->UM); ?>">
																													</div>
																												</div>

																												<div class="form-group row">
																													<label class="col-sm-4 col-form-label">Potongan Retensi (Rp)</label>
																													<div class="col-sm-8">
																														<input type="text" class="form-control" name="Potretensi" id="Potretensi" value="<?php echo  number_format($j->Retensi); ?>" onkeyup="termininduk()">
																													</div>
																												</div>

																												<div class="form-group row">
																													<label class="col-sm-4 col-form-label">Netto BoQ (Rp)</label>
																													<div class="col-sm-8">
																														<input type="text" class="form-control" name="NettoBoQ" id="NettoBoQ" value="<?php echo  number_format($j->TerminInduk); ?>">
																													</div>
																												</div>
																											</form>
																										</div>
																										<div class="modal-footer">
																											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
																											<button type="submit" class="btn btn-primary">Simpan</button>
																										</div>
																									</div>
																									<!-- /.modal-content -->
																								</div>
																								<!-- /.modal-dialog -->
																							</div>
																						</form>
																					<?php endforeach; ?>


																					<div class="tab-pane" id="timeline">
																						<p></p>
																						<div class="container-fluid">
																							<div class="row">
																								<div class="col-12">
																									<div class="card">
																										<div class="card-body">
																											<!-- <a href="<?= site_url('job/sub_job') ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a> -->
																											<a data-target="#modal-default-TM" data-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Termin Member</a>
																										</div>
																									</div>
																								</div>
																							</div>
																						</div>
																						<!-- The timeline -->

																						<!-- /.modal-content -->
																						<p> </p>
																						<table class="table table-hover" border="1" cellspacing="2" width="100%">
																							<tr style="background-color: deepskyblue;">
																								<th hidden></th>
																								<th style="width:2%;">No</th>
																								<th style="width:5%;">Tgl Cair</th>
																								<th style="width:5%;">Tgl Setor</th>
																								<th style="width:15%;">No. BAP</th>
																								<th style="width:20%;">Uraian</th>
																								<th style="width:10%;">Member 1 (Rp)</th>
																								<th style="width:10%;">Member 2 (Rp)</th>
																								<th style="width:10%;">Netto Cadangan KSO Member 1(Rp)</th>
																								<th style="width:10%;">Netto Cadangan KSO Member 2 (Rp)</th>
																								<th style="width:auto;">Action</th>
																							</tr>
																							<?php $no = 1;
																							foreach ($TblTerminMember as $j => $value) : ?>
																								<tr>
																									<td hidden><?= $value->LedgerNo; ?></td>
																									<td><?php echo $no++ ?></td>
																									<td><?= $value->TglCair; ?></td>
																									<td><?= $value->TglSetor; ?></td>
																									<td><?= $value->NoBAP; ?></td>
																									<td><?= $value->Uraian; ?></td>
																									<td><?= number_format($value->TerminMember1); ?></td>
																									<td><?= number_format($value->TerminMember2); ?></td>
																									<td><?= number_format($value->CadanganKSO); ?></td>
																									<td><?= number_format($value->CadanganKSOMember1); ?></td>
																									<td style="width: 5%">
																										<!-- <a title="Detail" href="<?= site_url('job/sub_job' . $value->JobNo) ?>" class="btn btn-success"><i class="fa fa-cubes"></i></a> -->
																										<a title="SELECT" data-toggle="modal" data-target="#modal-edit-TM<?php echo $value->LedgerNo; ?>" class="btn btn-success badge btn-xs"><i class="fa fa-pencil-square"></i></a>
																										<a title="DELETE" href="<?= site_url('') ?>" class="btn btn-danger badge btn-xs"><i class="fa fa-trash"></i></a>
																									</td>
																								</tr>
																								<?php
																							endforeach;
																							?>
																						</table>
																					</div>

																					<form action="<?= site_url('Job/SimpanTerminMember') ?>" method="POST">
																						<div class="modal fade" id="modal-default-TM">
																							<div class=" modal-dialog">
																								<div class="modal-content">
																									<div class="modal-header">
																										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																											<span aria-hidden="true">&times;</span></button>
																											<h4 class="modal-title">Input Termin Member</h4>
																											<p hidden><?= $this->session->userdata('MIS_LOGGED_NAME'); ?></p>
																										</div>
																										<div class="modal-body">
																											<form class="form-horizontal" style="width: 100%;">
																												<div class="form-group row">
																													<label for="inputName" class="col-sm-2 col-form-label">Jenis Termin </label>
																													<input type="text" name="JobNo" id="JobNo" value="<?php echo $dipa->JobNo; ?>" hidden>
																													<?php $JenisTermin = ['', 'Uang Muka', 'Termin']; ?>
																													<div class="col-sm-8">
																														<select name="JenisTermin" class="form-control" id="JenisTermin" onchange="changePesertaTender()" required="required">
																															<?php foreach ($JenisTermin as $jtm) : ?>
																																<option value="<?php echo $jtm ?>"><?php echo $jtm ?></option>
																															<?php endforeach; ?>
																														</select>
																													</div>
																												</div>
																												<div class="form-group row">
																													<label for="inputEmail" class="col-sm-2 col-form-label">Tgl Cair</label>
																													<div class="col-sm-10">
																														<input type="Date" class="form-control" name="TglCair" id="TglCair" placeholder="Tgl Cair Termin">
																													</div>
																												</div>
																												<div class="form-group row">
																													<label for="inputName2" class="col-sm-2 col-form-label">Tgl Setor Ke Rekening Induk</label>
																													<div class="col-sm-10">
																														<input type="Date" class="form-control" name="TglSetor" id="TglSetor" placeholder="Tgl Setor">
																													</div>
																												</div>
																												<div class="form-group row">
																													<label for="inputExperience" class="col-sm-2 col-form-label">No BAP</label>
																													<div class="col-sm-10">
																														<input type="text" class="form-control" name="NoBap" id="NoBap" placeholder="No BAP">
																													</div>
																												</div>
																												<div class="form-group row">
																													<label for="inputSkills" class="col-sm-2 col-form-label">Uraian</label>
																													<div class="col-sm-10">
																														<input type="text" class="form-control" id="Uraian" name="Uraian" placeholder="Uraian">
																													</div>
																												</div>
																												<label>Netto Share Termin (Rp) (Leader):</label>
																												<input type="text" name="NettoLeader" id="NettoLeader" class="form-control" placeholder="0">
																												<label>Netto Share Termin (Rp) (Member):</label>
																												<input type="text" name="NettoMember" id="NettoMember" class="form-control" placeholder="0">
																												<label>Cadangan KSO Member 1:*</label>
																												<input type="text" name="CadanganMember1" id="CadanganMember1" class="form-control" placeholder="0">
																												<label>Cadangan KSO Member 2:*</label>
																												<input type="text" name="CadanganMember2" id="CadanganMember2" class="form-control" placeholder="0">
																											</form>
																										</div>
																										<div class="modal-footer">
																											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
																											<button type="submit" class="btn btn-primary">Simpan</button>
																										</div>
																									</div>
																								</div>
																								<!-- /.modal-dialog -->
																							</div>
																						</form>

																						<!--modal edit Termin Member -->
																						<form action="<?= site_url('Job/EditTerminMember') ?>" method="POST">
																							<?php foreach ($TblTerminMember as $j) : ?>
																								<div class="modal fade" id="modal-edit-TM<?php echo $j->LedgerNo; ?>">
																									<div class=" modal-dialog">
																										<div class="modal-content">
																											<div class="modal-header">
																												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																													<span aria-hidden="true">&times;</span></button>
																													<h4 class="modal-title">Input Termin Member</h4>
																													<p hidden><?= $this->session->userdata('MIS_LOGGED_NAME'); ?></p>
																												</div>
																												<div class="modal-body">
																													<form class="form-horizontal" style="width: 100%;">
																														<div class="form-group row">
																															<label for="inputName" class="col-sm-2 col-form-label">Jenis Termin </label>
																															<input type="text" name="JobNo" id="JobNo" value="<?php echo $j->JobNo; ?>" hidden>
																															<input type="text" name="LedgerNo" id="LedgerNo" value="<?php echo $j->LedgerNo ?>" hidden>
																															<?php $JenisTermin = ['', 'Uang Muka', 'Termin']; ?>
																															<div class="col-sm-8">
																																<select name="JenisTermin" class="form-control" id="JenisTermin" onchange="changePesertaTender()" required="required">
																																	<!-- <?php foreach ($JenisTermin as $jtm) : ?> -->
																																	<option value="<?php echo $j->Jenis ?>"><?php echo $j->Jenis ?></option>
																																	<!-- <?php endforeach; ?> -->
																																</select>
																															</div>
																														</div>
																														<div class="form-group row">
																															<label for="inputEmail" class="col-sm-2 col-form-label">Tgl Cair</label>
																															<div class="col-sm-10">
																																<input type="Date" class="form-control" name="TglCair" id="TglCair" placeholder="Tgl Cair Termin" value="<?php echo $j->TglCair ?>">
																															</div>
																														</div>
																														<div class="form-group row">
																															<label for="inputName2" class="col-sm-2 col-form-label">Tgl Setor Ke Rekening Induk</label>
																															<div class="col-sm-10">
																																<input type="Date" class="form-control" name="TglSetor" id="TglSetor" placeholder="Tgl Setor" value="<?php echo $j->TglSetor ?>">
																															</div>
																														</div>
																														<div class="form-group row">
																															<label for="inputExperience" class="col-sm-2 col-form-label">No BAP</label>
																															<div class="col-sm-10">
																																<input type="text" class="form-control" name="NoBap" id="NoBap" placeholder="No BAP" value="<?php echo $j->NoBAP ?>">
																															</div>
																														</div>
																														<div class="form-group row">
																															<label for="inputSkills" class="col-sm-2 col-form-label">Uraian</label>
																															<div class="col-sm-10">
																																<input type="text" class="form-control" id="Uraian" name="Uraian" placeholder="Uraian" value="<?php echo $j->Uraian ?>">
																															</div>
																														</div>
																														<label>Netto Share Termin (Rp) (Leader):</label>
																														<input type="text" name="NettoLeader" id="NettoLeader" class="form-control" placeholder="0" value="<?php echo number_format($j->TerminMember1)  ?>">
																														<label>Netto Share Termin (Rp) (Member):</label>
																														<input type="text" name="NettoMember" id="NettoMember" class="form-control" placeholder="0" value="<?php echo number_format($j->TerminMember2)  ?>">
																														<label>Cadangan KSO Member 1:*</label>
																														<input type="text" name="CadanganMember1" id="CadanganMember1" class="form-control" placeholder="0" value="<?php echo number_format($j->CadanganKSO)  ?>">
																														<label>Cadangan KSO Member 2:*</label>
																														<input type="text" name="CadanganMember2" id="CadanganMember2" class="form-control" placeholder="0" value="<?php echo number_format($j->CadanganKSOMember1)  ?>">
																													</form>
																												</div>
																												<div class="modal-footer">
																													<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
																													<button type="submit" class="btn btn-primary">Simpan</button>
																												</div>
																											</div>
																										</div>
																										<!-- /.modal-dialog -->
																									</div>
																								<?php endforeach; ?>
																							</form>
																							<!-- /.modal-dialog -->
																						</div>



																					</div>
																					<!-- /.tab-pane -->
																					<!-- /.tab-pane -->
																				</div>
																				<!-- /.tab-content -->
																			</div><!-- /.card-body -->
																		</div>
																		<!-- /.card -->
																	</div>
																</div>
															</div>
															<!-- </div> -->
															<!-- </div> -->
														</div>
													</div>
												</section>



												<div class="modal fade" id="MyModalTerminInduk">

													<div class="modal-dialog modal-md">
														<div class="modal-content">

															<div class="modal-body" id="MyModalTerminInduk_Body">

																<div class="box" >

																	<div class="box-body" id="MyBoxBody">

																		<input type="text"  name="BrutoBOQ_termin_induk" id="BrutoBOQ_termin_induk" class="form-control input-sm" placeholder="0" onkeyup="MyTermin()" required>
																	</div>

																	<div id="myjs_termin_induk"></div>

																</div>
															</div>

														</div>

													</div>

												</div>

												<script type="text/javascript">
													$(function(){


														$('#mybtn_tambah_termin_induk').click(function(){

			// $('#MyModalTerminInduk').modal('show');
			// return false;
															var JobNo = $(this).attr('data-JobNo');

															jQuery.ajax({
																url: '<?=site_url('DipaTermin/PenerimaanTermin/dapat_form_tambah_termin_induk')?>',
																type: 'POST',
																dataType: 'HTML',
																data: {'JobNo' : JobNo},
																beforeSend : function(){

																	$(this).attr('disabled',true).text('Loading...');
																	$('#MyBoxBody').html('');
																},
																complete: function(xhr, textStatus) {
																	$(this).attr('disabled',false).text('Tambah Termin Induk');
																},
																success: function(data, textStatus, xhr) {

																	$('#MyModalTerminInduk').modal('show');
																	$('#MyBoxBody').html(data);
																},
																error: function(xhr, textStatus, errorThrown) {
																	$.toast({
																		text: 'terjadi kesalahan',
																		position: 'bottom-right',
																		stack: false,
																		hideAfter: 1000,
																		icon: 'error',

																	});
																	return false;
																}
															});

														});




													});
													function MyTermin(){
														alert('ssss');
													}
												</script>




												<!---Modal DIPA EDIT --->
												<div class="modal fade" id="modal-default">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span></button>
																	<h4 class="modal-title">Default Modal</h4>
																</div>
																<form action="<?= site_url('absensicon/updateDataAksi') ?>" method="post">
																	<div class="modal-body">
																		<input type="text" name="id_Dipa" hidden="" value="<?php echo $dipa->id_Dipa ?>">
																		<input type="text" name="JobNo" hidden="" value="<?php echo $dipa->JobNo ?>">
																		<p hidden=""><?= $this->session->userdata('MIS_LOGGED_NAME'); ?></p>
																		<label>Tahun</label>
																		<input type="text" name="Tahun" id="InfoPasar" class="form-control">
																		<label>DIPA (Rp)</label>
																		<input type="text" name="Dipa" id="Dipa" class="form-control" onkeyup="toDecimal(this)">
																		<label>DIPA Pagu Bruto</label>
																		<input type="text" name="PaguBudget" id="PaguBudget" class="form-control" onkeyup="toDecimal(this)">
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
																		<button type="submit" class="btn btn-primary">Simpan</button>
																	</div>
																</form>
															</div>
															<!-- /.modal-content -->
														</div>
														<!-- /.modal-dialog -->
													</div>
													<!---END DIPA EDIT -->
												</div>


												<script src="<?= base_url('assets/plugins/numeral/numeral.js') ?>"></script>
												<script src="http://code.jquery.com/jquery-latest.min.js"></script>
												<script src="http://code.jquery.com/jquery-2.1.4.js"></script>

												<script type="text/javascript">
													function NKTermin() {
														$(document).ready(function() {
															$("#TxtBruto").keyup(function() {
																var bttermin = parseInt($("#TxtBruto").val());
																var ppntermin = parseInt($("#TxtPPN").val());
																var pphtermin = parseInt($("#TxtPPH").val());
																var netto = bttermin - ppntermin - pphtermin;
																$("#TxtNetto").val(netto);
															});
														});
													}



													function termininduk() {
														var bruto = $('input[name="BrutoBoQ"]').val().replace(/,/g, '');
														var potum = $('input[name="PotUM"]').val().replace(/,/g, '');
														var potret = $('input[name="Potretensi"]').val();
														var netto = parseInt(bruto) - parseInt(potum);
														var netto1 = parseInt(netto) - parseInt(potret);
														if (!isNaN(netto1)) {
															$('input[name="NettoBoQ"]').val(numeral(netto1).format(0.0));
														} else {
															$('input[name="NettoBoQ"]').val(0);
														}
													}

													function Rtermin() {
														var bruto = $('#Budget').val();
														bruto = parseInt(bruto.replace(/,/g, ''));
														var persentase = parseInt($('#TxtPersentase').val());
														var nominal = bruto * (persentase / 100);

														$('#TxtA').val(numeral(nominal).format(0.0));

														var A = parseInt($('#TxtA').val().replace(/,/g, ''));
														var D = parseInt($('#TxtD').val().replace(/,/g, ''));
														var UM = A * (D / 100);
														$('#TxtUM').val(numeral(UM).format(0.0));

														$("input[value=1]").on("change", function(evt) {
															if ($(this).prop("checked")) {

																var bruto = $('#Budget').val();
																bruto = parseInt(bruto.replace(/,/g, ''));
																var persentase = parseInt($('#TxtPersentase').val());
																var nominal = bruto * (persentase / 100);

																$('#TxtA').val(numeral(nominal).format(0.0));

																var A = parseInt($('#TxtA').val().replace(/,/g, ''));
																var D = parseInt($('#TxtD').val().replace(/,/g, ''));
																var UM = A * (D / 100);
																$('#TxtUM').val(numeral(UM).format(0.0));

																var E = A * (5 / 100);
																$("#TxtE").val(numeral(E).format(0.0));

																var E = parseInt($('#TxtE').val().replace(/,/g, ''));
																var UM = parseInt($('#TxtUM').val().replace(/,/g, ''));
																var F = UM + E;
																$('#TxtF').val(numeral(F).format(0.0));

																var A = parseInt($('#TxtA').val().replace(/,/g, ''));
																var F = parseInt($('#TxtF').val().replace(/,/g, ''));
																var G = A - F;
																$('#TxtG').val(numeral(G).format(0.0));

																var G = parseInt($('#TxtG').val().replace(/,/g, ''));
																var H = (G / 1.11);
				// var H1 = H * (11 / 100);
																var H1 = 0;
																$('#TxtH').val(numeral(H1).format(0.0));

																var G = parseInt($('#TxtG').val().replace(/,/g, ''));
																var H = parseInt($('#TxtH').val().replace(/,/g, ''));
																var I = G - H;
																$('#TxtI').val(numeral(I).format(0.0));

																var G = parseInt($('#TxtG').val().replace(/,/g, ''));
																var J = (G / 1.1);
				// var J1 = J * (26.5 / 100);
																var J1 = 0;
																$('#TxtJ').val(numeral(J1).format(0.0));

																var G = parseInt($('#TxtG').val().replace(/,/g, ''));
																var I = parseInt($('#TxtH').val().replace(/,/g, ''));
																var J = parseInt($('#TxtJ').val().replace(/,/g, ''));
																var K = G - I - J;
																$('#TxtK').val(numeral(K).format(0.0));

															} else {

																var bruto = $('#Budget').val();
																bruto = parseInt(bruto.replace(/,/g, ''));
																var persentase = parseInt($('#TxtPersentase').val());
																var nominal = bruto * (persentase / 100);

																$('#TxtA').val(numeral(nominal).format(0.0));

																var A = parseInt($('#TxtA').val().replace(/,/g, ''));
																var D = parseInt($('#TxtD').val().replace(/,/g, ''));
																var UM = A * (D / 100);
																$('#TxtUM').val(numeral(UM).format(0.0));

																var E = 0;
																$("#TxtE").val(E);

																var E = parseInt($('#TxtE').val().replace(/,/g, ''));
																var UM = parseInt($('#TxtUM').val().replace(/,/g, ''));
																var F = UM + E;
																$('#TxtF').val(numeral(F).format(0.0));

																var A = parseInt($('#TxtA').val().replace(/,/g, ''));
																var F = parseInt($('#TxtF').val().replace(/,/g, ''));
																var G = A - F;
																$('#TxtG').val(numeral(G).format(0.0));

																var G = parseInt($('#TxtG').val().replace(/,/g, ''));
				// var H = (G / 1.11);
				// var H1 = H * (11 / 100);
																var H = 0;
																var H1 = H * (11 / 100);
																$('#TxtH').val(numeral(H).format(0.0).replace(/,/g, ''));

																var G = parseInt($('#TxtG').val().replace(/,/g, ''));
																var H = parseInt($('#TxtH').val().replace(/,/g, ''));
																var I = G - H;
																$('#TxtI').val(numeral(I).format(0.0));

																var G = parseInt($('#TxtG').val().replace(/,/g, ''));
				// var J = (G / 1.11);
				// var J1 = J * (26.5 / 100);
																var J = 0;
																var J1 = J * (2.65 / 100);
																$('#TxtJ').val(numeral(J).format(0.0));

																var G = parseInt($('#TxtG').val().replace(/,/g, ''));
																var I = parseInt($('#TxtH').val().replace(/,/g, ''));
																var J = parseInt($('#TxtJ').val().replace(/,/g, ''));
																var K = G - I - J;
																$('#TxtK').val(numeral(K).format(0.0));
															}
														});

$("input[value=2]").on("change", function(evt) {
	if ($(this).prop("checked")) {

		var bruto = $('#Budget').val();
		bruto = parseInt(bruto.replace(/,/g, ''));
		var persentase = parseInt($('#TxtPersentase').val());
		var nominal = bruto * (persentase / 100);

		$('#TxtA').val(numeral(nominal).format(0.0));

		var G = parseInt($('#TxtG').val().replace(/,/g, ''));
		var H = (G / 1.11);
		var H1 = H * (11 / 100);
				// var H1 = 0;
		$('#TxtH').val(numeral(H1).format(0.0));

		var G = parseInt($('#TxtG').val().replace(/,/g, ''));
		var H = parseInt($('#TxtH').val().replace(/,/g, ''));
		var I = G - H;
		$('#TxtI').val(numeral(I).format(0.0));

		var G = parseInt($('#TxtG').val().replace(/,/g, ''));
		var J = (G / 1.11);
				// var J1 = J * (2.65 / 100);
		var J1 = 0;
		$('#TxtJ').val(numeral(J1).format(0.0));

		var G = parseInt($('#TxtG').val().replace(/,/g, ''));
		var I = parseInt($('#TxtH').val().replace(/,/g, ''));
		var J = parseInt($('#TxtJ').val().replace(/,/g, ''));
		var K = G - I - J;
		$('#TxtK').val(numeral(K).format(0.0));

	} else {

		var bruto = $('#Budget').val();
		bruto = parseInt(bruto.replace(/,/g, ''));
		var persentase = parseInt($('#TxtPersentase').val());
		var nominal = bruto * (persentase / 100);

		$('#TxtA').val(numeral(nominal).format(0.0));

		var G = parseInt($('#TxtG').val().replace(/,/g, ''));
		var H = (G / 1.11);
				// var H1 = H * (11 / 100);
		var H1 = 0;
		$('#TxtH').val(numeral(H1).format(0.0));

		var G = parseInt($('#TxtG').val().replace(/,/g, ''));
		var H = parseInt($('#TxtH').val().replace(/,/g, ''));
		var I = G - H;
		$('#TxtI').val(numeral(I).format(0.0));

		var G = parseInt($('#TxtG').val().replace(/,/g, ''));
		var J = (G / 1.1);
				// var J1 = J * (26.5 / 100);
		var J1 = 0;
		$('#TxtJ').val(numeral(J1).format(0.0));

		var G = parseInt($('#TxtG').val().replace(/,/g, ''));
		var I = parseInt($('#TxtH').val().replace(/,/g, ''));
		var J = parseInt($('#TxtJ').val().replace(/,/g, ''));
		var K = G - I - J;
		$('#TxtK').val(numeral(K).format(0.0));
	}
});

$("input[value=3]").on("change", function(evt) {
	if ($(this).prop("checked")) {

		var bruto = $('#Budget').val();
		bruto = parseInt(bruto.replace(/,/g, ''));
		var persentase = parseInt($('#TxtPersentase').val());
		var nominal = bruto * (persentase / 100);

		$('#TxtA').val(numeral(nominal).format(0.0));

		var G = parseInt($('#TxtG').val().replace(/,/g, ''));
		var J = (G / 1.11);
		var J1 = J * (2.65 / 100);
				// var J1 = 0;
		$('#TxtJ').val(numeral(J1).format(0.0));

		var G = parseInt($('#TxtG').val().replace(/,/g, ''));
		var I = parseInt($('#TxtH').val().replace(/,/g, ''));
		var J = parseInt($('#TxtJ').val().replace(/,/g, ''));
		var K = G - I - J;
		$('#TxtK').val(numeral(K).format(0.0));

	} else {

		var bruto = $('#Budget').val();
		bruto = parseInt(bruto.replace(/,/g, ''));
		var persentase = parseInt($('#TxtPersentase').val());
		var nominal = bruto * (persentase / 100);

		$('#TxtA').val(numeral(nominal).format(0.0));

		var G = parseInt($('#TxtG').val().replace(/,/g, ''));
		var J = (G / 1.1);
				// var J1 = J * (26.5 / 100);
		var J1 = 0;
		$('#TxtJ').val(numeral(J1).format(0.0));

		var G = parseInt($('#TxtG').val().replace(/,/g, ''));
		var I = parseInt($('#TxtH').val().replace(/,/g, ''));
		var J = parseInt($('#TxtJ').val().replace(/,/g, ''));
		var K = G - I - J;
		$('#TxtK').val(numeral(K).format(0.0));
	}
});

var E = 0;
$("#TxtE").val(E);

var E = parseInt($('#TxtE').val().replace(/,/g, ''));
var UM = parseInt($('#TxtUM').val().replace(/,/g, ''));
var F = UM + E;
$('#TxtF').val(numeral(F).format(0.0));

var A = parseInt($('#TxtA').val().replace(/,/g, ''));
var F = parseInt($('#TxtF').val().replace(/,/g, ''));
var G = A - F;
$('#TxtG').val(numeral(G).format(0.0));

var G = parseInt($('#TxtG').val().replace(/,/g, ''));
		// var H = (G / 1.11);
		// var H1 = H * (11 / 100);
var H = 0;
var H1 = H * (11 / 100);
$('#TxtH').val(numeral(H).format(0.0).replace(/,/g, ''));

var G = parseInt($('#TxtG').val().replace(/,/g, ''));
var H = parseInt($('#TxtH').val().replace(/,/g, ''));
var I = G - H;
$('#TxtI').val(numeral(I).format(0.0));

var G = parseInt($('#TxtG').val().replace(/,/g, ''));
		// var J = (G / 1.11);
		// var J1 = J * (26.5 / 100);
var J = 0;
var J1 = J * (2.65 / 100);
$('#TxtJ').val(numeral(J).format(0.0));

var G = parseInt($('#TxtG').val().replace(/,/g, ''));
var I = parseInt($('#TxtI').val().replace(/,/g, ''));
var J = parseInt($('#TxtJ').val().replace(/,/g, ''));
var K = G - I - J;
$('#TxtK').val(numeral(K).format(0.0));

}

function Rtermin1() {

	var bruto = $('#Budget1').val();
	bruto = parseInt(bruto.replace(/,/g, ''));
	var persentase = parseInt($('#TxtPersentase1').val());
	var nominal = bruto * (persentase / 100);

	$('#TxtA1').val(numeral(nominal).format(0.0));

	var A = parseInt($('#TxtA1').val().replace(/,/g, ''));
	var D = parseInt($('#TxtD1').val().replace(/,/g, ''));
	var UM = A * (D / 100);
	$('#TxtUM1').val(numeral(UM).format(0.0));

		// var E = A * (5 / 100);
		// $('#TxtE').val(E)

	$("input[value=1]").on("change", function(evt) {

		if ($(this).prop("checked")) {

			var bruto = $('#Budget1').val();
			bruto = parseInt(bruto.replace(/,/g, ''));
			var persentase = parseInt($('#TxtPersentase1').val());
			var nominal = bruto * (persentase / 100);

			$('#TxtA1').val(numeral(nominal).format(0.0));

			var A = parseInt($('#TxtA1').val().replace(/,/g, ''));
			var D = parseInt($('#TxtD1').val().replace(/,/g, ''));
			var UM = A * (D / 100);
			$('#TxtUM1').val(numeral(UM).format(0.0));

			var E = A * (5 / 100);
			$("#TxtE1").val(numeral(E).format(0.0));

			var E = parseInt($('#TxtE1').val().replace(/,/g, ''));
			var UM = parseInt($('#TxtUM1').val().replace(/,/g, ''));
			var F = UM + E;
			$('#TxtF1').val(numeral(F).format(0.0));

			var A = parseInt($('#TxtA1').val().replace(/,/g, ''));
			var F = parseInt($('#TxtF1').val().replace(/,/g, ''));
			var G = A - F;
			$('#TxtG1').val(numeral(G).format(0.0));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
			var H = (G / 1.11);
				// var H1 = H * (11 / 100);
			var H1 = 0;
			$('#TxtH1').val(numeral(H1).format(0.0));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
			var H = parseInt($('#TxtH1').val().replace(/,/g, ''));
			var I = G - H;
			$('#TxtI1').val(numeral(I).format(0.0));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
			var J = (G / 1.1);
				// var J1 = J * (26.5 / 100);
			var J1 = 0;
			$('#TxtJ1').val(numeral(J1).format(0.0));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
			var I = parseInt($('#TxtH1').val().replace(/,/g, ''));
			var J = parseInt($('#TxtJ1').val().replace(/,/g, ''));
			var K = G - I - J;
			$('#TxtK1').val(numeral(K).format(0.0));

		} else {

			var bruto = $('#Budget1').val();
			bruto = parseInt(bruto.replace(/,/g, ''));
			var persentase = parseInt($('#TxtPersentase1').val());
			var nominal = bruto * (persentase / 100);

			$('#TxtA1').val(numeral(nominal).format(0.0));

			var A = parseInt($('#TxtA1').val().replace(/,/g, ''));
			var D = parseInt($('#TxtD1').val().replace(/,/g, ''));
			var UM = A * (D / 100);
			$('#TxtUM1').val(numeral(UM).format(0.0));

			var E = 0;
			$("#TxtE1").val(E);

			var E = parseInt($('#TxtE1').val().replace(/,/g, ''));
			var UM = parseInt($('#TxtUM1').val().replace(/,/g, ''));
			var F = UM + E;
			$('#TxtF1').val(numeral(F).format(0.0));

			var A = parseInt($('#TxtA1').val().replace(/,/g, ''));
			var F = parseInt($('#TxtF1').val().replace(/,/g, ''));
			var G = A - F;
			$('#TxtG1').val(numeral(G).format(0.0));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
				// var H = (G / 1.11);
				// var H1 = H * (11 / 100);
			var H = 0;
			var H1 = H * (11 / 100);
			$('#TxtH1').val(numeral(H).format(0.0).replace(/,/g, ''));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
			var H = parseInt($('#TxtH1').val().replace(/,/g, ''));
			var I = G - H;
			$('#TxtI1').val(numeral(I).format(0.0));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
				// var J = (G / 1.11);
				// var J1 = J * (26.5 / 100);
			var J = 0;
			var J1 = J * (2.65 / 100);
			$('#TxtJ1').val(numeral(J).format(0.0));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
			var I = parseInt($('#TxtH1').val().replace(/,/g, ''));
			var J = parseInt($('#TxtJ1').val().replace(/,/g, ''));
			var K = G - I - J;
			$('#TxtK1').val(numeral(K).format(0.0));
		}
	});

	$("input[value=2]").on("change", function(evt) {
		if ($(this).prop("checked")) {

			var bruto = $('#Budget1').val();
			bruto = parseInt(bruto.replace(/,/g, ''));
			var persentase = parseInt($('#TxtPersentase1').val());
			var nominal = bruto * (persentase / 100);

			$('#TxtA1').val(numeral(nominal).format(0.0));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
			var H = (G / 1.11);
			var H1 = H * (11 / 100);
				// var H1 = 0;
			$('#TxtH1').val(numeral(H1).format(0.0));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
			var H = parseInt($('#TxtH1').val().replace(/,/g, ''));
			var I = G - H;
			$('#TxtI1').val(numeral(I).format(0.0));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
			var J = (G / 1.11);
				// var J1 = J * (2.65 / 100);
			var J1 = 0;
			$('#TxtJ1').val(numeral(J1).format(0.0));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
			var I = parseInt($('#TxtH1').val().replace(/,/g, ''));
			var J = parseInt($('#TxtJ1').val().replace(/,/g, ''));
			var K = G - I - J;
			$('#TxtK1').val(numeral(K).format(0.0));

		} else {

			var bruto = $('#Budget1').val();
			bruto = parseInt(bruto.replace(/,/g, ''));
			var persentase = parseInt($('#TxtPersentase1').val());
			var nominal = bruto * (persentase / 100);

			$('#TxtA1').val(numeral(nominal).format(0.0));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
			var H = (G / 1.11);
				// var H1 = H * (11 / 100);
			var H1 = 0;
			$('#TxtH1').val(numeral(H1).format(0.0));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
			var H = parseInt($('#TxtH1').val().replace(/,/g, ''));
			var I = G - H;
			$('#TxtI1').val(numeral(I).format(0.0));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
			var J = (G / 1.1);
				// var J1 = J * (26.5 / 100);
			var J1 = 0;
			$('#TxtJ1').val(numeral(J1).format(0.0));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
			var I = parseInt($('#TxtH1').val().replace(/,/g, ''));
			var J = parseInt($('#TxtJ1').val().replace(/,/g, ''));
			var K = G - I - J;
			$('#TxtK1').val(numeral(K).format(0.0));
		}
	});

	$("input[value=3]").on("change", function(evt) {
		if ($(this).prop("checked")) {

			var bruto = $('#Budget1').val();
			bruto = parseInt(bruto.replace(/,/g, ''));
			var persentase = parseInt($('#TxtPersentase1').val());
			var nominal = bruto * (persentase / 100);

			$('#TxtA1').val(numeral(nominal).format(0.0));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
			var J = (G / 1.11);
			var J1 = J * (2.65 / 100);
				// var J1 = 0;
			$('#TxtJ1').val(numeral(J1).format(0.0));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
			var I = parseInt($('#TxtH1').val().replace(/,/g, ''));
			var J = parseInt($('#TxtJ1').val().replace(/,/g, ''));
			var K = G - I - J;
			$('#TxtK1').val(numeral(K).format(0.0));

		} else {

			var bruto = $('#Budget1').val();
			bruto = parseInt(bruto.replace(/,/g, ''));
			var persentase = parseInt($('#TxtPersentase1').val());
			var nominal = bruto * (persentase / 100);

			$('#TxtA1').val(numeral(nominal).format(0.0));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
			var J = (G / 1.1);
				// var J1 = J * (26.5 / 100);
			var J1 = 0;
			$('#TxtJ1').val(numeral(J1).format(0.0));

			var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
			var I = parseInt($('#TxtH1').val().replace(/,/g, ''));
			var J = parseInt($('#TxtJ1').val().replace(/,/g, ''));
			var K = G - I - J;
			$('#TxtK1').val(numeral(K).format(0.0));
		}
	});

	var E = 0;
	$("#TxtE1").val(E);

	var E = parseInt($('#TxtE1').val().replace(/,/g, ''));
	var UM = parseInt($('#TxtUM1').val().replace(/,/g, ''));
	var F = UM + E;
	$('#TxtF1').val(numeral(F).format(0.0));

	var A = parseInt($('#TxtA1').val().replace(/,/g, ''));
	var F = parseInt($('#TxtF1').val().replace(/,/g, ''));
	var G = A - F;
	$('#TxtG1').val(numeral(G).format(0.0));

	var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
		// var H = (G / 1.11);
		// var H1 = H * (11 / 100);
	var H = 0;
	var H1 = H * (11 / 100);
	$('#TxtH1').val(numeral(H).format(0.0).replace(/,/g, ''));

	var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
	var H = parseInt($('#TxtH1').val().replace(/,/g, ''));
	var I = G - H;
	$('#TxtI1').val(numeral(I).format(0.0));

	var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
		// var J = (G / 1.11);
		// var J1 = J * (26.5 / 100);
	var J = 0;
	var J1 = J * (2.65 / 100);
	$('#TxtJ1').val(numeral(J).format(0.0));

	var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
	var I = parseInt($('#TxtI1').val().replace(/,/g, ''));
	var J = parseInt($('#TxtJ1').val().replace(/,/g, ''));
	var K = G - I - J;
	$('#TxtK1').val(numeral(K).format(0.0));

}

function Rtermin2() {
	var bruto = $('#Budget2').val();
	bruto = parseInt(bruto.replace(/,/g, ''));
	var persentase = parseInt($('#TxtPersentase2').val());
	var nominal = bruto * (persentase / 100);

	$('#TxtA2').val(numeral(nominal).format(0.0));

	var A = parseInt($('#TxtA2').val().replace(/,/g, ''));
	var D = parseInt($('#TxtD2').val().replace(/,/g, ''));
	var UM = A * (D / 100);
	$('#TxtUM2').val(numeral(UM).format(0.0));

		// var E = A * (5 / 100);
		// $('#TxtE').val(E)

	$("input[value=1]").on("change", function(evt) {

		if ($(this).prop("checked")) {

			var bruto = $('#Budget2').val();
			bruto = parseInt(bruto.replace(/,/g, ''));
			var persentase = parseInt($('#TxtPersentase2').val());
			var nominal = bruto * (persentase / 100);

			$('#TxtA2').val(numeral(nominal).format(0.0));

			var A = parseInt($('#TxtA2').val().replace(/,/g, ''));
			var D = parseInt($('#TxtD2').val().replace(/,/g, ''));
			var UM = A * (D / 100);
			$('#TxtUM2').val(numeral(UM).format(0.0));

			var E = A * (5 / 100);
			$("#TxtE2").val(numeral(E).format(0.0));

			var E = parseInt($('#TxtE2').val().replace(/,/g, ''));
			var UM = parseInt($('#TxtUM2').val().replace(/,/g, ''));
			var F = UM + E;
			$('#TxtF2').val(numeral(F).format(0.0));

			var A = parseInt($('#TxtA2').val().replace(/,/g, ''));
			var F = parseInt($('#TxtF2').val().replace(/,/g, ''));
			var G = A - F;
			$('#TxtG2').val(numeral(G).format(0.0));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
			var H = (G / 1.11);
				// var H1 = H * (11 / 100);
			var H1 = 0;
			$('#TxtH2').val(numeral(H1).format(0.0));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
			var H = parseInt($('#TxtH2').val().replace(/,/g, ''));
			var I = G - H;
			$('#TxtI2').val(numeral(I).format(0.0));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
			var J = (G / 1.1);
				// var J1 = J * (26.5 / 100);
			var J1 = 0;
			$('#TxtJ2').val(numeral(J1).format(0.0));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
			var I = parseInt($('#TxtH2').val().replace(/,/g, ''));
			var J = parseInt($('#TxtJ2').val().replace(/,/g, ''));
			var K = G - I - J;
			$('#TxtK2').val(numeral(K).format(0.0));

		} else {

			var bruto = $('#Budget2').val();
			bruto = parseInt(bruto.replace(/,/g, ''));
			var persentase = parseInt($('#TxtPersentase2').val());
			var nominal = bruto * (persentase / 100);

			$('#TxtA2').val(numeral(nominal).format(0.0));

			var A = parseInt($('#TxtA2').val().replace(/,/g, ''));
			var D = parseInt($('#TxtD2').val().replace(/,/g, ''));
			var UM = A * (D / 100);
			$('#TxtUM2').val(numeral(UM).format(0.0));

			var E = 0;
			$("#TxtE2").val(E);

			var E = parseInt($('#TxtE2').val().replace(/,/g, ''));
			var UM = parseInt($('#TxtUM2').val().replace(/,/g, ''));
			var F = UM + E;
			$('#TxtF2').val(numeral(F).format(0.0));

			var A = parseInt($('#TxtA2').val().replace(/,/g, ''));
			var F = parseInt($('#TxtF2').val().replace(/,/g, ''));
			var G = A - F;
			$('#TxtG2').val(numeral(G).format(0.0));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
				// var H = (G / 1.11);
				// var H1 = H * (11 / 100);
			var H = 0;
			var H1 = H * (11 / 100);
			$('#TxtH2').val(numeral(H).format(0.0).replace(/,/g, ''));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
			var H = parseInt($('#TxtH2').val().replace(/,/g, ''));
			var I = G - H;
			$('#TxtI2').val(numeral(I).format(0.0));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
				// var J = (G / 1.11);
				// var J1 = J * (26.5 / 100);
			var J = 0;
			var J1 = J * (2.65 / 100);
			$('#TxtJ2').val(numeral(J).format(0.0));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
			var I = parseInt($('#TxtH2').val().replace(/,/g, ''));
			var J = parseInt($('#TxtJ2').val().replace(/,/g, ''));
			var K = G - I - J;
			$('#TxtK2').val(numeral(K).format(0.0));
		}
	});

	$("input[value=2]").on("change", function(evt) {
		if ($(this).prop("checked")) {

			var bruto = $('#Budget2').val();
			bruto = parseInt(bruto.replace(/,/g, ''));
			var persentase = parseInt($('#TxtPersentase2').val());
			var nominal = bruto * (persentase / 100);

			$('#TxtA2').val(numeral(nominal).format(0.0));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
			var H = (G / 1.11);
			var H1 = H * (11 / 100);
				// var H1 = 0;
			$('#TxtH2').val(numeral(H1).format(0.0));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
			var H = parseInt($('#TxtH2').val().replace(/,/g, ''));
			var I = G - H;
			$('#TxtI2').val(numeral(I).format(0.0));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
			var J = (G / 1.11);
				// var J1 = J * (2.65 / 100);
			var J1 = 0;
			$('#TxtJ2').val(numeral(J1).format(0.0));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
			var I = parseInt($('#TxtH2').val().replace(/,/g, ''));
			var J = parseInt($('#TxtJ2').val().replace(/,/g, ''));
			var K = G - I - J;
			$('#TxtK2').val(numeral(K).format(0.0));

		} else {

			var bruto = $('#Budget2').val();
			bruto = parseInt(bruto.replace(/,/g, ''));
			var persentase = parseInt($('#TxtPersentase2').val());
			var nominal = bruto * (persentase / 100);

			$('#TxtA2').val(numeral(nominal).format(0.0));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
			var H = (G / 1.11);
				// var H1 = H * (11 / 100);
			var H1 = 0;
			$('#TxtH2').val(numeral(H1).format(0.0));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
			var H = parseInt($('#TxtH2').val().replace(/,/g, ''));
			var I = G - H;
			$('#TxtI2').val(numeral(I).format(0.0));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
			var J = (G / 1.1);
				// var J1 = J * (26.5 / 100);
			var J1 = 0;
			$('#TxtJ2').val(numeral(J1).format(0.0));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
			var I = parseInt($('#TxtH2').val().replace(/,/g, ''));
			var J = parseInt($('#TxtJ2').val().replace(/,/g, ''));
			var K = G - I - J;
			$('#TxtK2').val(numeral(K).format(0.0));
		}
	});

	$("input[value=3]").on("change", function(evt) {
		if ($(this).prop("checked")) {

			var bruto = $('#Budget2').val();
			bruto = parseInt(bruto.replace(/,/g, ''));
			var persentase = parseInt($('#TxtPersentase2').val());
			var nominal = bruto * (persentase / 100);

			$('#TxtA2').val(numeral(nominal).format(0.0));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
			var J = (G / 1.11);
			var J1 = J * (2.65 / 100);
				// var J1 = 0;
			$('#TxtJ2').val(numeral(J1).format(0.0));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
			var I = parseInt($('#TxtH2').val().replace(/,/g, ''));
			var J = parseInt($('#TxtJ2').val().replace(/,/g, ''));
			var K = G - I - J;
			$('#TxtK2').val(numeral(K).format(0.0));

		} else {

			var bruto = $('#Budget2').val();
			bruto = parseInt(bruto.replace(/,/g, ''));
			var persentase = parseInt($('#TxtPersentase2').val());
			var nominal = bruto * (persentase / 100);

			$('#TxtA2').val(numeral(nominal).format(0.0));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
			var J = (G / 1.1);
				// var J1 = J * (26.5 / 100);
			var J1 = 0;
			$('#TxtJ2').val(numeral(J1).format(0.0));

			var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
			var I = parseInt($('#TxtH2').val().replace(/,/g, ''));
			var J = parseInt($('#TxtJ2').val().replace(/,/g, ''));
			var K = G - I - J;
			$('#TxtK2').val(numeral(K).format(0.0));
		}
	});

	var E = 0;
	$("#TxtE2").val(E);

	var E = parseInt($('#TxtE2').val().replace(/,/g, ''));
	var UM = parseInt($('#TxtUM2').val().replace(/,/g, ''));
	var F = UM + E;
	$('#TxtF2').val(numeral(F).format(0.0));

	var A = parseInt($('#TxtA2').val().replace(/,/g, ''));
	var F = parseInt($('#TxtF2').val().replace(/,/g, ''));
	var G = A - F;
	$('#TxtG2').val(numeral(G).format(0.0));

	var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
		// var H = (G / 1.11);
		// var H1 = H * (11 / 100);
	var H = 0;
	var H1 = H * (11 / 100);
	$('#TxtH2').val(numeral(H).format(0.0).replace(/,/g, ''));

	var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
	var H = parseInt($('#TxtH2').val().replace(/,/g, ''));
	var I = G - H;
	$('#TxtI2').val(numeral(I).format(0.0));

	var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
		// var J = (G / 1.11);
		// var J1 = J * (26.5 / 100);
	var J = 0;
	var J1 = J * (2.65 / 100);
	$('#TxtJ2').val(numeral(J).format(0.0));

	var G = parseInt($('#TxtG2').val().replace(/,/g, ''));
	var I = parseInt($('#TxtI2').val().replace(/,/g, ''));
	var J = parseInt($('#TxtJ2').val().replace(/,/g, ''));
	var K = G - I - J;
	$('#TxtK2').val(numeral(K).format(0.0));

}
</script>
