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
								<!-- Main content -->
								<section class="content">
									<div class="container-fluid">
										<div class="row">
											<div class="col-12">
												<div class="card">
													<div class="card-body">
														<!-- <a href="#add" data-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah</a>
						<br /><br /> -->
														<div class="box-body">
															<!-- <div class="row"> -->
															<form id="myform">
																<div class="form-group row">
																	<div class="col-sm-2">
																		<label for="inputName"  style="font-size:11px !important;">Tanggal PD</label>
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
																	<label for="inputName" class="col-sm-2 col-form-label" style="font-size:11px !important;">By</label>
																	<div class="col-sm-4">
																		<select class="form-control" name="JobNo" id="JobNo" required>
																			<option value="Project Control">Project Control</option>
																			<option value="Keuangan">Keuangan</option>
																		</select>
																	</div>
																</div>

																<div class="form-group row">
																	<label for="inputName" class="col-sm-2 col-form-label" style="font-size:11px !important;">Type</label>
																	<div class="col-sm-4">
																		<select class="form-control" name="JobNo" id="JobNo" required>
																			<option value="Outstanding">OutStanding</option>
																			<option value="Approved">Approved</option>
																		</select>
																	</div>
																</div>

																<div class="form-group row">
																	<label for="inputName" class="col-sm-2 col-form-label"></label>
																	<div class="col-sm-3">
																		<button type="submit" id="print" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp; Preview</button>
																		<button type="submit" id="print" class="btn btn-info"><i class="fa fa-print"></i>&nbsp; Print</button>
																	</div>

																</div>

															</form>
														</div>
													</div>
													<!-- <div class="container"> -->
													<div class="col-md-12">

														<table id="TblQuery1" class="table table-bordered table-striped table-hover table-condensed">
															<thead>
																<tr>
																	<th class="active"><input type="checkbox" class="select-all checkbox" name="select-all" /></th>
																	<th>Job No</th>
																	<th>Job Name</th>
																	<th>No PD</th>
																	<th>Alokasi</th>
																	<th>Tipe Form</th>
																	<th>Atas Nama</th>
																	<th>Rekening Penerima</th>
																	<th>No. KO</th>
																	<th>Total PD (Rp)</th>
																	<th>Tgl PD</th>
																	<th>Jth Tempo</th>
																	<th>Deskripsi</th>
																	<th>Approved By</th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td class="active">
																		<input type="checkbox" class="select-item checkbox" name="select-item" value="1" />
																	</td>
																	<td class="success"></td>
																	<td class="warning"></td>
																	<td class="danger"></td>
																	<td class="info"></td>
																	<td class="success"></td>
																	<td class="warning"></td>
																	<td class="danger"></td>
																	<td class="info"></td>
																	<td class="success"></td>
																	<td class="warning"></td>
																	<td class="danger"></td>
																	<td class="info"></td>
																	<td class="danger"></td>
																	<td class="info"></td>
																</tr>
															</tbody>
														</table>
														<!-- <button id="select-all" class="btn button-default">SelectAll/Cancel</button>
						<button id="select-invert" class="btn button-default">Invert</button>
						<button id="selected" class="btn button-default">GetSelected</button> -->
														</body>
														<script src="//cdn.bootcss.com/jquery/2.2.1/jquery.min.js"></script>
														<script src="//cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
														<script>
															$(function() {

																//button select all or cancel
																$("#select-all").click(function() {
																	var all = $("input.select-all")[0];
																	all.checked = !all.checked
																	var checked = all.checked;
																	$("input.select-item").each(function(index, item) {
																		item.checked = checked;
																	});
																});

																//button select invert
																$("#select-invert").click(function() {
																	$("input.select-item").each(function(index, item) {
																		item.checked = !item.checked;
																	});
																	checkSelected();
																});

																//button get selected info
																$("#selected").click(function() {
																	var items = [];
																	$("input.select-item:checked:checked").each(function(index, item) {
																		items[index] = item.value;
																	});
																	if (items.length < 1) {
																		alert("no selected items!!!");
																	} else {
																		var values = items.join(',');
																		console.log(values);
																		var html = $("<div></div>");
																		html.html("selected:" + values);
																		html.appendTo("body");
																	}
																});

																//column checkbox select all or cancel
																$("input.select-all").click(function() {
																	var checked = this.checked;
																	$("input.select-item").each(function(index, item) {
																		item.checked = checked;
																	});
																});

																//check selected items
																$("input.select-item").click(function() {
																	var checked = this.checked;
																	console.log(checked);
																	checkSelected();
																});

																//check is all selected
																function checkSelected() {
																	var all = $("input.select-all")[0];
																	var total = $("input.select-item").length;
																	var len = $("input.select-item:checked:checked").length;
																	console.log("total:" + total);
																	console.log("len:" + len);
																	all.checked = len === total;
																}
															});
														</script>
													</div>
								</section>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
</div>
</section>
</div>
