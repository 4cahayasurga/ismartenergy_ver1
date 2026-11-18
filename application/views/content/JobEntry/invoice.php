<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
			<label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $invoice->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $invoice->JobNm ?></label>
			<br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $invoice->Lokasi ?></label>

			</h1>
			<ol class="breadcrumb">
				<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
				<li class="active"><?php echo $judul ?></li>
			</ol>
		</section>

		<section class="content-header">
			<a href="<?= site_url('job/sub_job/' . $invoice->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
			<!-- <a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a> -->
		</section>

		<!-- <Section class="content"> -->
			<section class="content-header">
				<div class="row" id="MOD-IN">
					<div class="col-md-12">
						<div class="box box-warning">
							<div class="box-header with-border">
								<div class="box box-solid">
									<div class="box-header with-border">
										<div class="box-header bg-blue-gradient">
											<h3 class="box-title"></h3>
											<div class="box-tools pull-right">
												<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
												<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
											</div>
										</div>
									</div>

									<div class="box-body">
										<div class="table-responsive">
											<a data-toggle="modal" data-target="#Addinvoice" class="btn bg-primary btn-flat btn-sm "><i class="fa fa-plus"></i> NEW</a><br><br>
											<table class="table table-bordered table-striped table-hover table-condensed " id="TblInv">
												<thead>
													<tr>
														<th style="width:6%" class="text-center">#</th>

														
														<th >No. KO</th>
														<th>Vendor</th>
														<th>No. Invoice</th>
														<th>Tgl. Invoice</th>
														<th>Jatuh Tempo</th>
														<th>Total Invoice (Rp)</th>
														<th>PPN (Rp)</th>
														<th>PPH (Rp)</th>
														<th>No. Faktur Pajak</th>
														<th>Tgl Faktur Pajak</th>
														<th>Time Entry</th>
													</tr>
												</thead>
												<tbody>

													<?php
													$nomor = 1;
													foreach ($tblinvoice as $tbi) : ?>
														<tr>
															<td class="text-center">

																<a data-target="#editinvoice<?php echo $tbi->NoKO ?>" data-toggle="modal" class="badge btn-primary badge btn-xs"><i class="fa fa-pencil"></i></a>
																<a href="" class="badge btn-danger badge btn-xs"><i class="fa fa-trash"></i></a>
															</td>

															<td><?php echo $tbi->NoKO ?></td>
															<td ><?=$tbi->VendorNm ?></td>
															<td><?php echo $tbi->InvNo ?></td>
															<td><?php echo tgl_baru_tahun_pendek($tbi->InvDate) ?></td>
															<td><?php echo tgl_baru_tahun_pendek($tbi->Duedate) ?></td>
															<td><?php echo number_format($tbi->Total) ?></td>
															<td><?php echo number_Format($tbi->PPN) ?></td>
															<td><?php echo number_format($tbi->PPH) ?></td>
															<td><?php echo $tbi->FPNo ?></td>
															<td><?php echo tgl_baru_tahun_pendek($tbi->FPDate) ?></td>
															<td><?php echo tgl_baru_tahun_pendek($tbi->TimeEntry) ?></td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>

									<?php foreach ($tblinvoice as $tbi) : ?>
										<div class="modal fade" id="editinvoice<?php echo $tbi->NoKO ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel">Entry Invoice</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body">
														<div class="form-group row">
															<label for="inputEmail" class="col-sm-2 col-form-label">No KO</label>
															<div class="col-sm-5">
																<select class="form-control select2" name="NoKO" id="NoKO" style="width: 100%;">
																	<option value="<?php echo $tbi->NoKO ?>"><?php echo $tbi->NoKO ?></option>
																</select>
															</div>
														</div>
														<div class="form-group row">
															<label for="" class="col-sm-2 col-form-label">No. MOS</label>
															<div class="col-sm-7">
																<input type="text" name="NoMOS" id="NoMOS" class="form-control" value="">
															</div>
														</div>
														<div class="form-group row">
															<label for="" class="col-sm-2 col-form-label">No.Invoice</label>
															<div class="col-sm-7">
																<input type="text" name="NoMOS" id="NoMOS" class="form-control" value="<?php echo $tbi->InvNo ?>">
															</div>
														</div>
														<div class="form-group row">
															<label for="" class="col-sm-2 col-form-label">Tgl. Invoice</label>
															<div class="col-sm-7">
																<input type="date" name="InvDate" id="InvDate" class="form-control" value="<?php echo $tbi->InvDate ?>">
															</div>
														</div>
														<div class="form-group row">
															<label for="" class="col-sm-2 col-form-label">Jatuh Tempo</label>
															<div class="col-sm-7">
																<input type="date" name="Duedate" id="Duedate" class="form-control" value="<?php echo $tbi->Duedate ?>">
															</div>
														</div>
														<div class="form-group row">
															<label for="" class="col-sm-2 col-form-label">No. Faktur Pajak</label>
															<div class="col-sm-7">
																<input type="text" name="FPNo" id="FPNO" class="form-control" value="<?php echo $tbi->FPNo ?>">
															</div>
														</div>
														<div class="form-group row">
															<label for="" class="col-sm-2 col-form-label">Tgl. Faktur Pajak</label>
															<div class="col-sm-7">
																<input type="text" name="FPDate" id="FPDate" class="form-control" value="<?php echo $tbi->FPDate ?>">
															</div>
														</div>
														<div class="form-group row">
															<label for="" class="col-sm-2 col-form-label">Total</label>
															<div class="col-sm-7">
																<input type="text" name="Total" id="Total" class="form-control" value="<?php echo number_format($tbi->Total) ?>">
															</div>
														</div>
														<div class="form-group row">
															<label for="" class="col-sm-2 col-form-label">PPN</label>
															<div class="col-sm-7">
																<input type="text" name="PPN" id="PPN" class="form-control" value="<?php echo number_format($tbi->PPN) ?>">
															</div>
														</div>
														<div class="form-group row">
															<label for="" class="col-sm-2 col-form-label">PPH</label>
															<div class="col-sm-7">
																<input type="text" name="PPH" id="PPH" class="form-control" value="<?php echo number_format($tbi->PPH) ?>">
															</div>
														</div>
														<div class="form-group row">
															<label for="" class="col-sm-2 col-form-label">Keterangan</label>
															<div class="col-sm-7">
																<textarea type="text" name="Keterangan" id="Keterangan" class="form-control"><?php echo $tbi->Keterangan ?></textarea>
															</div>
														</div>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
														<button type="submit" class="btn btn-primary">Save changes</button>
													</div>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
									<div class="modal fade" id="editinvoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLabel">Entry Invoice</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<div class="form-group row">
														<label for="inputEmail" class="col-sm-2 col-form-label">No KO</label>
														<div class="col-sm-5">
															<select class="form-control select2" name="NoKO" id="NoKO" style="width: 100%;">
																<option value="">-- Pilih NoKO --</option>
																<?php foreach ($NoKO as $data) : ?>
																	<option value="<?php echo $data->NoKO ?>"><?php echo $data->NoKO ?></option>
																<?php endforeach; ?>
															</select>
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">No. MOS</label>
														<div class="col-sm-7">
															<input type="text" name="NoMOS" id="NoMOS" class="form-control">
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">No.Invoice</label>
														<div class="col-sm-7">
															<input type="text" name="NoMOS" id="NoMOS" class="form-control">
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">Tgl. Invoice</label>
														<div class="col-sm-7">
															<input type="date" name="InvDate" id="InvDate" class="form-control">
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">Jatuh Tempo</label>
														<div class="col-sm-7">
															<input type="date" name="Duedate" id="Duedate" class="form-control">
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">No. Faktur Pajak</label>
														<div class="col-sm-7">
															<input type="text" name="FPNo" id="FPNO" class="form-control">
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">Tgl. Faktur Pajak</label>
														<div class="col-sm-7">
															<input type="text" name="FPDate" id="FPDate" class="form-control">
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">Total</label>
														<div class="col-sm-7">
															<input type="text" name="Total" id="Total" class="form-control">
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">PPN</label>
														<div class="col-sm-7">
															<input type="text" name="PPN" id="PPN" class="form-control">
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">PPH</label>
														<div class="col-sm-7">
															<input type="text" name="PPH" id="PPH" class="form-control">
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">Keterangan</label>
														<div class="col-sm-7">
															<textarea type="text" name="Keterangan" id="Keterangan" class="form-control"></textarea>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
													<button type="button" class="btn btn-primary">Save changes</button>
												</div>
											</div>
										</div>
									</div>



									<div class="modal fade" id="Addinvoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLabel">Entry Invoice</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<div class="form-group row">
														<label for="inputEmail" class="col-sm-2 col-form-label">No KO</label>
														<div class="col-sm-5">
															<select class="form-control select2" name="NoKO" id="NoKO" style="width: 100%;">
																<option value="">-- Pilih NoKO --</option>
																<?php foreach ($NoKO as $data) : ?>
																	<option value="<?php echo $data->NoKO ?>"><?php echo $data->NoKO ?></option>
																<?php endforeach; ?>
															</select>
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">No. MOS</label>
														<div class="col-sm-7">
															<input type="text" name="NoMOS" id="NoMOS" class="form-control">
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">No.Invoice</label>
														<div class="col-sm-7">
															<input type="text" name="NoMOS" id="NoMOS" class="form-control">
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">Tgl. Invoice</label>
														<div class="col-sm-7">
															<input type="date" name="InvDate" id="InvDate" class="form-control">
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">Jatuh Tempo</label>
														<div class="col-sm-7">
															<input type="date" name="Duedate" id="Duedate" class="form-control">
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">No. Faktur Pajak</label>
														<div class="col-sm-7">
															<input type="text" name="FPNo" id="FPNO" class="form-control">
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">Tgl. Faktur Pajak</label>
														<div class="col-sm-7">
															<input type="text" name="FPDate" id="FPDate" class="form-control">
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">Total</label>
														<div class="col-sm-7">
															<input type="text" name="Total" id="Total" class="form-control">
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">PPN</label>
														<div class="col-sm-7">
															<input type="text" name="PPN" id="PPN" class="form-control">
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">PPH</label>
														<div class="col-sm-7">
															<input type="text" name="PPH" id="PPH" class="form-control">
														</div>
													</div>
													<div class="form-group row">
														<label for="" class="col-sm-2 col-form-label">Keterangan</label>
														<div class="col-sm-7">
															<textarea type="text" name="Keterangan" id="Keterangan" class="form-control"></textarea>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
													<button type="button" class="btn btn-primary">Save changes</button>
												</div>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</section>
				<script src="<?=base_url('assets/select2_baru.js')?>"></script>
				<script>
					$(document).ready(function() {
						$('#TblInv').DataTable();

						$('.select2').select2({
							'width': '100%',
						});

					});
				</script>




			</div>


			<!-- /.container-fluid -->
		</section>

	</section>
	


	<!-- </Section> -->
