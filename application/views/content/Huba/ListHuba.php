<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
			<label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $dipa->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $dipa->JobNm ?></label>
			<br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?= $dipa->Lokasi ?></label>

			</h1>
			<ol class="breadcrumb">
				<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
				<li class="active"><?php echo $judul ?></li>
			</ol>
		</section>

		<section class="content-header">
			<a href="<?= site_url('job/sub_job/' . $dipa->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
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

										<!-- <a href="<?= site_url('job/sub_job/' . $dipa->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a> -->
										<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus-circle"></i> Tambah Data</button>
										<!-- <a href="#modaledit" data-toggle="modal" class="btn btn-warning"><i class="fa fa-file-excel"></i> Import From Excel</a> -->
										<a href="<?= site_url('Basic/print/' . $datapaket->JobNo) ?>" data-toggle="modal" class="btn btn-danger"><i class="fa fa-file-pdf"></i> Export To PDF</a>

										<!-- <a href="<?= site_url('Basic/dapatkan_TemplateExcel') ?>" type="button" class="btn btn-default"></a> -->

										<br /><br />


										<div class="table-responsive">
											<table id="example" class="table table-striped table-bordered" style="width:100%">
												<thead>
													<tr>
														<!-- <th></th> -->
														<th>Kd HUBA</th>
														<th>URAIAN PEKERJAAN</th>
														<th>SATUAN</th>
														<th>HARGA (Rp)</th>
														<th>PPN (Rp)</th>
														<th>Total (Rp)</th>
														<th>KETERANGAN</th>
														<th>AKSI</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($datahuba as $row => $dh) :
														$bold_tulisan = ($dh->Tipe == 'Header') ? 'font-weight:bold;' : '';
														?>
														<tr>
															<!-- <td><?= $dh->LedgerNo ?></td> -->
															<td style="<?= $bold_tulisan; ?>"><?= $dh->KdHuba ?></td>
															<td style="<?= $bold_tulisan; ?>"><?= $dh->Uraian ?></td>
															<td style="<?= $bold_tulisan; ?>"><?= $dh->Uom ?></td>
															<td style="<?= $bold_tulisan; ?>"><?= number_format($dh->Harga) ?></td>
															<td style="<?= $bold_tulisan; ?>"><?= number_format($dh->PPN_Nominal) ?></td>
															<td style="<?= $bold_tulisan; ?>"><?= number_format($dh->Total) ?></td>
															<td style="<?= $bold_tulisan; ?>"><?= $dh->Keterangan ?></td>
															
															<td>
																<a href="#modaledit<?= $dh->LedgerNo ?>" data-toggle="modal" class="btn btn-primary"><i class="fa fa-edit"></i></a>
															</td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>

											<!-- Modal EDIT -->
											<?php foreach ($datahuba as $row => $v) : ?>
												<div class="modal fade" id="modaledit<?= $v->LedgerNo ?>">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h4 class="modal-title"><i class="fa fa-edit"></i> Ubah Data</h4>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																	<span class="sr-only">Close</span>
																</button>
															</div>
															<form action="<?= site_url('Huba/edithuba') ?>" method="post">

																<div class="modal-body">
																	<table class="col-sm-12">
																		<td><input type="text" name="JobNoEd" id="JobNoEd" value="<?= $datapaket->JobNo ?>" hidden></td>
																		<td><input type="text" name="LedgerNoEd" id="LedgerNoEd" value="<?= $v->LedgerNo ?>" hidden></td>
																		<tr>
																			<td>Type Data</td>
																			<td>:</td>
																			<td><select name="typeed" id="typeed" class="form-control" required>
																				<?php foreach (['Header', 'Detail'] as $row => $value) : ?>
																					<option <?= ($v->Tipe == $value ? 'selected' : '') ?> value="<?= $value ?>"><?= $value ?></option>
																				<?php endforeach ?>

																			<!-- <option value="<?= $row->Tipe ?>"><?= $row->Tipe ?></option>
																<option value="Header">Header</option>
																<option value="Detail">Detail</option> -->
															</select>
														</td>
													</tr>

													<tr id="header">
														<td>Header</td>
														<td>:</td>
														<td>
															<select name="Headered" id="Headered" class="form-control" onchange="header()">
																<option value="0">Top Header</option>
																<?php foreach ($Header as $dth => $value) : ?>
																	<option <?= ($v->AlokasiHeader == $value->KdHuba) ? 'selected' : '' ?> value="<?= $value->KdHuba ?>"><?= $value->KdHuba ?> - <?= $value->Uraian ?></option>
																<?php endforeach; ?>
															</select>
														</td>
													</tr>
													<tr>
														<td>Kd Alokasi Header</td>
														<td>:</td>
														<td><input type="text" name="KdAlokasiHeadered" id="KdAlokasiHeadered" class="form-control" value="<?= $v->AlokasiHeader ?>"></td>
													</tr>
													<tr>
														<td>KdHUBA</td>
														<td>:</td>
														<td><input type="text" name="Kdhubaed" id="Kdhubaed" class="form-control" value="<?= $v->KdHuba ?>"></td>
													</tr>
													<tr>
														<td>Uraian Pekerjaan</td>
														<td>:</td>
														<td><input type="text" name="Uraianed" id="Uraianed" class="form-control" value="<?= $v->Uraian ?>"></td>
													</tr>
													<tr>
														<td>Satuan</td>
														<td>:</td>
														<td><input type="text" name="Uomed" id="Uomed" class="form-control" value="<?= $v->Uom ?>"></td>
													</tr>
													<tr>
														<td>Harga</td>
														<td>:</td>
														<td><input type="text" name="hargaed" id="hargaed" class="form-control" value="<?= number_format($v->Harga) ?>"></td>
													</tr>
													<!-- <tr>
														<td>PPN</td>
														<td>:</td>
														<td><input type="checkbox" name="cbPPN_ed" id="cbPPN_ed" alt="Checkbox" value="1"></td>
													</tr>
													<tr>
														<td>PPN (Rp)</td>
														<td>:</td>
														<td>
															<input type="text" id="GetPPN_Nominal_ed" value="" class="form-control"  disabled>
															<input type="hidden" name="GetPPN_Nominal_db_ed" id="GetPPN_Nominal_db_ed" value="0" class="form-control"  readonly>
														</td>
													</tr>
													<tr>
														<td>Total</td>
														<td>:</td>
														<td>
															<input type="text" id="GetTotal_ed" value="0" class="form-control" disabled>
															<input type="hidden" name="GetTotal_db_ed" id="GetTotal_db_ed" value="0" class="form-control" readonly>
														</td>
													</tr> -->
													<tr>
														<td>Keterangan</td>
														<td>:</td>
														<td><input type="text" name="Keteranganed" id="Keteranganed" class="form-control" value="<?= $v->Keterangan ?>"></td>
													</tr>
												</table>

											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
												<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
											</div>
										</form>

									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>

				</div>

			</div>
		</div>
	</div>
</div>
</section>

<form action="<?= site_url('Huba/hubasimpan') ?>" method="post">
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Tambah Data HUBA</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<table class="col-sm-12">
						<td><input type="text" name="JobNo" id="JobNo" value="<?= $datapaket->JobNo ?>" hidden></td>
						<tr>
							<td>Type Data</td>
							<td>:</td>
							<td><select name="type" id="type" class="form-control" required>
								<option value="">--- pilih type data ---</option>
								<option value="Header">Header</option>
								<option value="Detail">Detail</option>
							</select>
						</td>
					</tr>

					<tr id="header">
						<td>Header</td>
						<td>:</td>
						<td><select name="Header" id="Header" class="form-control" onchange="header()">
							<option value="0">Top Header</option>
							<?php foreach ($Header as $dth) : ?>
								<option value="<?= $dth->KdHuba ?>"><?= $dth->KdHuba ?> - <?= $dth->Uraian ?></option>
							<?php endforeach; ?>
						</select></td>
					</tr>
					<tr>
						<td>Kd Alokasi Header</td>
						<td>:</td>
						<td><input type="text" name="KdAlokasiHeader" id="KdAlokasiHeader" class="form-control" onkeyup="kdheader()"></td>
					</tr>
					<tr>
						<td>KdHUBA</td>
						<td>:</td>
						<td><input type="text" name="Kdhuba" id="Kdhuba" class="form-control"></td>
					</tr>
					<tr>
						<td>Uraian Pekerjaan</td>
						<td>:</td>
						<td><input type="text" name="Uraian" id="Uraian" class="form-control"></td>
					</tr>
					<tr>
						<td>Satuan</td>
						<td>:</td>
						<td><input type="text" name="Uom" id="Uom" class="form-control"></td>
					</tr>
					<tr>
						<td>Harga</td>
						<td>:</td>
						<td><input type="text" name="harga" id="harga" class="form-control" value="0"></td>
					</tr>
					<tr>
						<td>PPN</td>
						<td>:</td>
						<td><input type="checkbox" name="cbPPN" id="cbPPN" alt="Checkbox" value="1"></td>
					</tr>
					<tr>
						<td>PPN (Rp)</td>
						<td>:</td>
						<td>
							<input type="text" id="GetPPN_Nominal" value="0" class="form-control"  disabled>
							<input type="hidden" name="GetPPN_Nominal_db" id="GetPPN_Nominal_db" value="0" class="form-control"  readonly>
						</td>
					</tr>
					<tr>
						<td>Total</td>
						<td>:</td>
						<td>
							<input type="text" id="GetTotal" value="0" class="form-control" disabled>
							<input type="hidden" name="GetTotal_db" id="GetTotal_db" value="0" class="form-control" readonly>
						</td>
					</tr>
					<tr>
						<td>Keterangan</td>
						<td>:</td>
						<td><input type="text" name="Keterangan" id="keterangan" class="form-control"></td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> -->

<script type="text/javascript">
	$(function() {
		$("#type").change(function() {
			if ($(this).val() == 'Header') {
				$("#KdAlokasiHeader").removeAttr("readonly");
				$("#KdAlokasiHeader").focus();
			} else {
				$("#KdAlokasiHeader").attr("readonly", "readonly");

			}
		});

		


	});
</script>

<script>

	function CekDecimalAtauBukan(startVal){

		return (startVal - Math.floor(startVal)) != 0;
	}
	$(document).ready(function() {
		$('#example').DataTable();

		// // var startVal = 123.000;
		// alert( (startVal - Math.floor(startVal)) != 0 )



		var formatUang_Nol_Tiga = function(num){
			var str = num.toString().replace("", ""), parts = false, output = [], i = 1, formatted = null;
			if(str.indexOf(".") > 0) {
				parts = str.split(".");
				str = parts[0];
			}
			str = str.split("").reverse();
			for(var j = 0, len = str.length; j < len; j++) {
				if(str[j] != ",") {
					output.push(str[j]);
					if(i%3 == 0 && j < (len - 1)) {
						output.push(",");
					}
					i++;
				}
			}
			formatted = output.reverse().join("");
			return("" + formatted + ((parts) ? "." + parts[1].substr(0, 3) : ""));
		};

		// HitungTotal();

		$("#cbPPN").click(function(){
			HitungTotal();
		});

		

		$("#harga").keyup(function(e){
			$(this).val(formatUang_Nol_Tiga($(this).val()));

			HitungTotal();

		});

		$('#harga').focus(function(){
			if (this.value == 0) {
				$(this).val('');
			}



		});


		// $('#harga').blur(function(){
		// 	if (this.value == '') {
		// 		$(this).val(0);
		// 	}
		// 	HitungTotal();
		// });


	});

	function addCommas(nStr)
	{
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}

	function HitungTotal(){
		var Harga = $('#harga').val();
		var cbPPN = 0;

		var NominalPPN = parseFloat(0);
		var Total = parseFloat(0);

		var NominalPPN_db = parseFloat(0);
		var Total_db = parseFloat(0);

		if ($('#cbPPN').is(':checked')) {
			cbPPN = 1;
		}

		Harga = parseFloat(Harga.replace(/,/g, ''));

		if ($.isNumeric(Harga) == false) {
			Harga = parseFloat(0);
		}

		if (cbPPN == 1) {
			NominalPPN = Harga * (11/100);
		}

		Harga = (Harga < 1) ? 0 : Harga;
		NominalPPN = (NominalPPN < 1) ? 0 : NominalPPN;

		Total = Harga + NominalPPN;
		$('#GetPPN_Nominal_db').val(NominalPPN);
		$('#GetTotal_db').val(Total);

		// NominalPPN = NominalPPN.toFixed(3);
		// Total = Total.toFixed(3);
		NominalPPN = addCommas(NominalPPN);
		Total = addCommas(Total);

		$('#GetPPN_Nominal').val(NominalPPN);
		$('#GetTotal').val(Total);

	}

	function header() {
		var tes = document.getElementById("Header").value;
		document.getElementById("KdAlokasiHeader").value = tes;
		document.getElementById("Kdhuba").value = tes;
	}

	function kdheader() {
		var klik = document.getElementById("KdAlokasiHeader").value;
		document.getElementById("Kdhuba").value = klik;
	}

	function ppn() {
		$("input[value=1]".on("change", function(evt){
			if ($(this).prop("checked")) {

			}
		}))

			// $("input[value=2]").on("change", function(evt) {
			// 	if ($(this).prop("checked")) {

			// 		var G = parseInt($('#TxtG1').val().replace(/,/g, ''));
			// 		var H = (G / 1.11);
			// 		var H1 = H * (11 / 100);
			// 		// var H1 = 0;
			// 		$('#TxtH1').val(numeral(H1).format(0.0));
			// 	}
			// 	else(

			// 	)
			// })
	}
</script>



<script>
	$(document).idle({
		onIdle: function() {
			window.location = "/logout.php";
		},
		idle: 10000
	});
</script>
</div>


<!-- /.container-fluid -->
</section>

</section>



<!-- </Section> -->
