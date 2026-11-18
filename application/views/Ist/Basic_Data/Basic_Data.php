<style>
	.btn-gradient-primary {
		background: linear-gradient(135deg, #4e73df, #224abe);
		border: none;
		color: #fff;
		transition: all 0.3s ease;
	}

	.btn-gradient-primary:hover {
		background: linear-gradient(135deg, #224abe, #4e73df);
		transform: translateY(-2px);
		box-shadow: 0 8px 20px rgba(78, 115, 223, 0.4);
	}

	.btn-add-data i {
		transition: transform 0.3s ease;
	}

	.btn-add-data:hover i {
		transform: rotate(90deg);
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
				<!-- <p></p>
				<label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $invoice->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $invoice->JobNm ?></label>
				<br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $invoice->Lokasi ?></label> -->

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<!-- <section class="content-header">
		<a href="#" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
		<a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a>
	</section> -->

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
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-12">

										<a href="<?php echo site_url('Ist/Basic_Data/Basic_Data/Add_Basic_Data') ?>" type="button"
											class="btn btn-gradient-primary btn-lg shadow-sm btn-add-data">
											<i class="fa fa-plus mr-2"></i> Tambah Data
										</a>

											<br><br>
											<table class="table table-bordered table-striped">
												<thead>
													<tr>
														<th>AKSI</th>
														<th>No SPBB</th>
														<th>Lokasi</th>
														<th>NO SK PERTAMINA</th>
														<th>TGL SK </th>
														<th>TAHUN</th>
														<th>VOL QUOTA </th>
														<th>BOOKING</th>
														<th>TERPAKAI</th>
														<th>SISA QUOTA</th>
													</tr>
												</thead>
												<thead>
													<tr>
														<th></th>
														<th><input type="text" name="" id="" class="form-control"></th>
														<th><input type="text" name="" id="" class="form-control"></th>
														<th><input type="text" name="" id="" class="form-control"></th>
														<th><input type="text" name="" id="" class="form-control"></th>
														<th><input type="text" name="" id="" class="form-control"></th>
														<th><input type="text" name="" id="" class="form-control"></th>
														<th><input type="text" name="" id="" class="form-control"></th>
														<th><input type="text" name="" id="" class="form-control"></th>
														<th><input type="text" name="" id="" class="form-control"></th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($Quota as $Quota) : ?>
													<tr>
														<td>
															<a href="<?= site_url('Ist/Basic_Data/Basic_Data/Edit_Basic_Data/' . $Quota->LedgerNo) ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
															<a href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
														</td>
														<td><?= $Quota->No_SPBB; ?></td>
														<td><?= $Quota->Area; ?></td>
														<td><?= $Quota->No_SK; ?></td>
														<td><?= $Quota->Tgl_SK; ?></td>
														<td><?= $Quota->ThnAnggaran; ?></td>
														<td><?= number_format($Quota->Vol_Quota); ?></td>
														<td><?= number_format(isset($Quota->Vol_Booking) ? $Quota->Vol_Booking : 0); ?></td>
														<td><?= number_format(isset($Quota->Vol_Terpakai) ? $Quota->Vol_Terpakai : 0); ?></td>
														<td><?= number_format($Quota->Vol_Quota - (isset($Quota->Vol_Booking) ? $Quota->Vol_Booking : 0) - (isset($Quota->Vol_Terpakai) ? $Quota->Vol_Terpakai : 0)); ?></td>
													</tr>
													<?php endforeach; ?>
												</tbody>

											</table>


												


                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>	
</div>



