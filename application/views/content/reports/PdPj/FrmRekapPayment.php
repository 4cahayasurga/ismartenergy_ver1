<!-- <?php
		// error_reporting(0);
		// print_rr($query1);
		// exit;


		?> -->
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1><?php echo $judul ?></h1>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>
	<section class="content-header">
		<a onclick="history.back()" class="btn btn-warning btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
	</section>

	<!-- Main content -->
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
								<form id="myform">
									<div class="form-group row">
										<div class="col-sm-2">
											<label for="inputName" class="col-sm-5 col-form-label">Tanggal Pembayaran</label>
										</div>
										<div class="col-sm-2">
											<label style="font-size:11px !important;">Dari Tgl</label>
											<input type="date" style="font-size:10px !important;" name="DariTgl" id="DariTgl" value="" class="form-control datepicker">
										</div>
										<div class="col-sm-2">
											<label style="font-size:11px !important;">Sampai Tgl</label>
											<input style="font-size:11px !important;" type="date" name="SampaiTgl" id="SampaiTgl" value="" class="form-control">
										</div>
									</div>

									<div class="form-group row">
										<div class="col-sm-2">
											<label for="inputName" class="col-sm-6 col-form-label">JobNo</label>
										</div>
										<div class="col-sm-3">
											<select class="form-control" name="JobNo" id="JobNo" required>
												<option value="">-- Pilih Job.No --</option>
												<?php
												foreach ($job as $job) { ?>
													<option value="<?= $job['JobNo'] ?>"><?= $job['JobNo'] . ' - ' . $job['NamaPaket'] ?></option>
												<?php } ?>
											</select>
										</div>
									</div>

									<div class="form-group row">
										<label for="inputName" class="col-sm-2 col-form-label"></label>
										<div class="col-sm-3">
											<button type="submit" id="print" class="btn btn-dropbox"><i class="fa fa-search"></i>&nbsp; Preview</button>
											<button type="submit" id="print" class="btn btn-info"><i class="fa fa-print"></i>&nbsp; PDF & Print</button>
											<button type="submit" id="print" class="btn btn-warning"><i class="fa fa-file"></i>&nbsp; Excel</button>
										</div>
									</div>
								</form>
							</div>

							<div class="col-md-12">
								<table id="TblQuery1" class="table table-bordered table-striped table-hover table-condensed">
									<thead>
										<tr>
											<th>No</th>
											<th>Tanggal</th>
											<th>Form</th>
											<th>No PD</th>
											<th>Job Name</th>
											<th>Nama Penerima</th>
											<th>Rekening Penerima</th>
											<th>Bank Penerima</th>
											<th>Nilai Transfer</th>
											<th>Bank Pengirim</th>
											<th>NO Cek/BG</th>
											<th>Rekening Pengirim</th>
											<th>No KOntrak</th>
											<th>Keterangan</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
