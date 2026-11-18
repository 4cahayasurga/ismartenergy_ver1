<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
			<label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $GetYAD->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $GetYAD->JobNm ?></label>
			<br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $GetYAD->Lokasi ?></label>

			</h1>
			<ol class="breadcrumb">
				<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
				<li class="active"><?php echo $judul ?></li>
			</ol>
		</section>

		<section class="content-header">
			<a href="<?= site_url('job/sub_job/' . $GetYAD->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
			<!-- <a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a> -->
		</section>

		<!-- <Section class="content"> -->
			<section class="content-header">
				<div class="row">
					<div class="col-md-12">
						<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
						<div class="box box-warning">
							<div class="box-header with-border">
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
									<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
								</div>
								<div class="box-body">

									<div class="col-md-2">
										<div class="form-group">
											<label for="">BUDGET RAP</label>
											<?php foreach ($GetRap as $GR) : ?>
												<strong><input type="text" name="BudgetRAP" id="BudgetRAP" class="form-control" style="text-size: 24;" value="<?= number_format($GR['TTLRAP']) ?>" readonly></strong>
											<?php endforeach; ?>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="">TOTAL KO</label>
											<?php foreach ($TotalKO as $TP) : ?>
												<strong><input type="text" name="totalko" id="totalko" class="form-control" value="<?= number_format($TP['TotalKO']) ?>" readonly></strong>
											<?php endforeach; ?>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="">TOTAL INVOICE</label>
											<?php foreach ($TotalInv as $TP) : ?>
												<strong><input type="text" name="totalinv" id="totalinv" class="form-control" value="<?= number_format($TP['TotalINV']) ?>" readonly></strong>
											<?php endforeach; ?>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="">WIP </label>
											<strong><input type="text" name="wip" id="wip" class="form-control" readonly></strong>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="">YAD</label>
											<?php foreach ($getYAD as $GTY) : ?>
												<strong><input type="text" name="yad" id="yad" class="form-control" readonly value="<?= number_format($GTY['getYAD']) ?>"></strong>
											<?php endforeach; ?>
										</div>
									</div>
							<!-- <div class="col-md-2">
								<div class="form-group">
									<label for="">ESTIMASI MARGIN</label>
									<strong><input type="text" name="estimasi" id="estimasi" class="form-control" readonly></strong>
								</div>
							</div> -->


							<p></p>
							<div class="col-md-12">
								<div id="chartContainer" style="height: 370px; width: 100%;"></div>
								<!-- <div class="form-group row">
								<label class="col-sm-1 col-form-label">Job No</label>
								<select class="form-control" name="alokasi" id="alokasi" style="width: 30%;">
									<?php foreach ($AksesJob as $data) : ?>
										<option value="<?php echo $data->JobNo ?>"><?php echo $data->JobNo ?> - <?php echo $data->JobNm ?></option>
									<?php endforeach; ?>
								</select>
							</div> -->
							<!-- <a href="<?= site_url('job/sub_job') ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a> -->
							<a data-target="#modal-YAD" data-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> TAMBAH DATA YAD</a>

							<form action="<?= site_url('Job/EntryYAD') ?>" method="POST">

								<div class="modal fade" id="modal-YAD">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span></button>
													<h4 class="modal-title">ENTRY YAD</h4>
												</div>
												<div class="modal-body">
													<input type="text" name="JobNo" hidden="" value=">">
													<p hidden=""><?= $this->session->userdata('MIS_LOGGED_NAME'); ?></p>
													<input type="text" name="JobNo" value="<?php echo $GetYAD->JobNo ?>" hidden>

													<label for="">Tanggal</label>
													<input type="date" name="TglYAD" id="TglYAD" class="form-control" style="width: 40%;" required>

													<label for="">KdRAP</label>
													<select name="KdRAP" id="KdRAP" class="form-control select2" onchange="autofill(this.value)">
														<option value="">-- Pilih Kode RAP -- </option>
														<?php foreach ($KdRAP as $KdRAP) : ?>
															<option value="<?php echo $KdRAP->KdRAP ?>"><?php echo $KdRAP->KdRAP ?> - <?php echo $KdRAP->Uraian ?></option>
														<?php endforeach; ?>
													</select>

													<label for="">Uraian</label>
													<input type="text" name="Uraian" id="Uraian" class="form-control">

													<label for="">Alokasi</label>
													<input type="text" name="Alokasi" id="Alokasi" class="form-control" style="width: 40%;" readonly>

													<label for="">Satuan</label>
													<input type="text" name="Satuan" id="Satuan" class="form-control">

													<label for="">Vol</label>
													<input type="text" name="Vol" id="Vol" class="form-control" onkeyup="HitungYAD()">

													<label for="">Harga Satuan</label>
													<input type="text" name="HrgSatuanYAD" id="HrgSatuanYAD" class="form-control" onkeyup="HitungYAD()">

													<input type="hidden" name="Amount" id="Amount" class="form-control">

													<label for="">PPN Satuan</label>
													<input type="text" name="PPN" id="PPN" class="form-control" onkeyup="HitungYAD()" >

													<label for="">Total PPN</label>
													<input type="text" name="TtlPPN" id="TtlPPN" class="form-control" readonly>

													<label for="">Keterangan</label>
													<textarea type="text" name="Remark" id="Remark" class="form-control"></textarea>

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

								<div class="box-body">
									<!-- <div class="row"> -->
										<!-- <div class="col-md-6"> -->
											<div class="form-group">
												<div class="box-body table-responsive no-padding">
													<table class="table table-hover table-striped  " id="tableKami" cellspacing="2" width="100%">
														<thead>
															<tr style="background-color:orange;font-size:16px !important;">
																<th hidden></th>
																<th>No</th>
																<th>Kode RAP</th>
																<th>Uraian</th>
																<th>Satuan</th>
																<th>Volume</th>
																<th>Hrg Satuan</th>
																<th>Jumlah Harga</th>
																<th>Harga Satuan PPN (Rp)</th>
																<th>Jumlah Harga PPN (Rp)</th>
																<th>#</th>
															</tr>
														</thead>
														<tbody>
															<?php $no = 1;
															foreach ($TblYAD as $value) : ?>
																<tr style="font-size:16px !important;">
																	<td hidden><?= $value->LedgerNo; ?></td>
																	<td><?= $no++ ?></td>
																	<td><?= $value->KdRAP; ?></td>
																	<td><?= $value->uraian; ?></td>
																	<td><?= $value->Uom; ?></td>
																	<td><?= UbahDecimal_Nol_tiga($value->Vol); ?></td>
																	<td><?= UbahDecimal_Nol_Dua($value->HrgSatuan); ?></td>
																	<td><?= UbahDecimal_Nol_Dua($value->Amount); ?></td>
																	<td><?= UbahDecimal_Nol_Dua($value->PPN); ?></td>
																	<td><?= UbahDecimal_Nol_Dua($value->TtlPPN); ?></td>
																	<td>
																		<!-- <?php echo anchor('Job/HapusYAD/' . $value->LedgerNo, '<div class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></div>') ?>
																		<button type="button" data-toggle="modal" data-target="#modal-EditDipa<?php echo $value->LedgerNo; ?>" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></button> -->

																		<a href="<?=site_url('Job/HapusYAD/'.$value->LedgerNo.'/'.$value->JobNo)?>" class="btn btn-danger btn-md" title="HAPUS DATA INI" onclick="return confirm('Anda yakin ingin menghapus data ini?')" ><i class="fa fa-trash"></i></a>

																		<button type="button" class="btn btn-md btn-success" title="Edit Data Ini" data-toggle="modal" data-target="#modal-EditDipa<?php echo $value->LedgerNo; ?>"><i class="fa fa-edit"></i></button>

																	</td>
																</tr>
															<?php endforeach; ?>
														</tbody>
														<tfoot>
															<tr style="background-color:lightgray;">
																<td colspan="5" rowspan="5">
																	<h4 style="float: right;">TOTAL</h4>
																</td>
																<td>
																	<h4><?= UbahDecimal_Nol_Dua($TtlSatuan->TtlSatuan); ?></h4>
																</td>
																<td>
																	<h4><?= UbahDecimal_Nol_Dua($TtlHrg->TtlHrg); ?></h4>
																</td>
																<td>
																	<h4><?= UbahDecimal_Nol_Dua($TtlPPNSat->TtlPPNSat); ?></h4>
																</td>
																<td>
																	<h4><?= UbahDecimal_Nol_Dua($TtlPPN->TtlPPN); ?></h4>
																</td>
																<td></td>
															</tr>
														</tfoot>
													</table>
												</div>
											</div>

											
											<?php foreach ($TblYAD as $yad) : ?>
												<form action="<?= site_url('Job/EditYAD/'.$yad->LedgerNo) ?>" method="post">
													<div class="modal fade" id="modal-EditDipa<?php echo $yad->LedgerNo; ?>">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																		<span aria-hidden="true">&times;</span></button>
																		<h4 class="modal-title">ENTRY YAD</h4>
																	</div>
																	<div class="modal-body">
																		<input type="text" name="JobNo" hidden="" value=">">
																		<p hidden=""><?= $this->session->userdata('MIS_LOGGED_NAME'); ?></p>
																		<input type="text" name="JobNo" value="<?php echo $yad->JobNo ?>" hidden>

																		<label for="">Tanggal</label>
																		<input type="date" name="TglYAD" id="TglYAD" class="form-control" style="width: 40%;" value="<?php echo $yad->Tgl ?>" required>

																		<label for="">KdRAP</label>
																		<input type="text" name="KdRAP" id="KdRAP" class="form-control" value="<?php echo $yad->KdRAP ?>" readonly>

																		<label for="">Uraian</label>
																		<input type="text" name="Uraian" id="Uraian" class="form-control" value="<?php echo $yad->uraian ?>" readonly>

																		<label for="">Alokasi</label>
																		<input type="text" name="Alokasi" id="Alokasi" class="form-control" style="width: 40%;" value="<?php echo $yad->Alokasi ?>" readonly>

																		<label for="">Satuan</label>
																		<input type="text" name="Satuan" id="Satuan" class="form-control" style="width: 40%;" value="<?php echo $yad->Uom ?>">

																		<label for="">Vol</label>
																		<!-- <input type="text" name="Vol" id="Vol_edit_ID_<?=$yad->LedgerNo?>" class="form-control Vol_edit_CLASS " onkeyup="HitungYAD_edit(<?=$yad->LedgerNo?>)" value="<?php echo UbahDecimal_Nol_Tiga($yad->Vol) ?>" data-LedgerNo="<?=$yad->LedgerNo?>" > -->

																		<input type="text" name="Vol" id="Vol_edit_ID_<?=$yad->LedgerNo?>" class="form-control Vol_edit_CLASS "  value="<?php echo UbahDecimal_Nol_Tiga($yad->Vol) ?>" data-LedgerNo="<?=$yad->LedgerNo?>" >

																		<label for="">Harga Satuan</label>
																		<!-- <input type="text" name="HrgSatuanYAD" id="HrgSatuanYAD_edit" class="form-control" onkeyup="HitungYAD_edit()" value="<?php echo UbahDecimal_Nol_Dua($yad->HrgSatuan) ?>"> -->

																		<input type="text" name="HrgSatuanYAD" id="HrgSatuan_edit_ID_<?=$yad->LedgerNo?>" class="form-control HrgSatuan_edit_CLASS "  value="<?php echo number_format($yad->HrgSatuan) ?>" data-LedgerNo="<?=$yad->LedgerNo?>" >

																		<input type="hidden" name="Amount" id="Amount_edit_ID_<?=$yad->LedgerNo?>" class="form-control " value="<?php echo UbahDecimal_Nol_Dua($yad->Amount) ?>" readonly>

																		<label for="">PPN Satuan</label>
																		<!-- <input type="text" name="PPN" id="PPN_edit" class="form-control" onkeyup="HitungYAD_edit()" value="<?php echo UbahDecimal_Nol_Dua($yad->PPN) ?>"> -->

																		<input type="text" name="PPN" id="PPN_edit_ID_<?=$yad->LedgerNo?>" class="form-control PPN_edit_CLASS "  value="<?php echo UbahDecimal_Nol_Dua($yad->PPN) ?>" data-LedgerNo="<?=$yad->LedgerNo?>" >

																		<label for="">Total PPN</label>
																		<input type="text" name="TtlPPN" id="TtlPPN_edit_ID_<?=$yad->LedgerNo?>" class="form-control" value="<?php echo UbahDecimal_Nol_Dua($yad->TtlPPN) ?>" readonly>

																		<label for="">Keterangan</label>
																		<textarea type="text" name="Remark" id="Remark" class="form-control"><?php echo $yad->Remark ?></textarea>

																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-default pull-left btn-md" data-dismiss="modal">Close</button>
																		<button type="submit" class="btn btn-primary btn-md">UPDATE</button>
																	</div>
																</div>
																<!-- /.modal-content -->
															</div>
															<!-- /.modal-dialog -->
														</div>

														<script type="text/javascript">
															
														</script>
													</form>
												<?php endforeach; ?>
												

									<!-- <div class="form-group row">
									<label class="col-lg-2 col-form-label">TOTAL YAD B </label>
									<div class="col-sm-3">
										<input type="text" name="TtlYadB" id="TtlYadB" class="form-control" value="<?= $TtlTADB ?>">
									</div>
								</div> -->
									<!-- <div class="form-group row">
									<label class="col-lg-2 col-form-label">TOTAL YAD C</label>
									<div class="col-sm-3">
										
										<input type="text" name="TtlYADC" id="TtlYADC" class="formcontrol" value="<?php echo $TotalYADB ?>">
									</div>
								</div> -->
								<!-- </div> -->
								<!-- </div> -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>




		<?php
		$dataPoints = array(
			array("y" => $q_yad['getYAD'], "label" => "YAD"),
			array("y" => $q_yad['WIP'], "label" => "WIP"),
			array("y" => $q_yad['TOTALINV'], "label" => "TOTAL INV"),
			array("y" => $q_yad['TOTALKO'], "label" => "TOTAL KO"),
			array("y" => $q_yad['TTLRAP'], "label" => "BUDGET RAP")
		);

		?>

		<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
		<script>
			window.onload = function() {

				var chart = new CanvasJS.Chart("chartContainer", {
					animationEnabled: true,
					title: {
						text: "GRAFIK MONITORING RAP VS YAD"
					},
					axisY: {
						title: "Revenue (in IDR)",
						includeZero: true,
						prefix: "Rp.",
					// suffix: "k"
					},
					data: [{
						type: "bar",
						yValueFormatString: "Rp#,##0",
						indexLabel: "{y}",
						indexLabelPlacement: "inside",
						indexLabelFontWeight: "bolder",
						indexLabelFontColor: "white",
						dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
					}]
				});
				chart.render();

			}
		</script>








		<script src="<?= base_url('assets/plugins/numeral/numeral.js') ?>"></script>
		<script src="http://code.jquery.com/jquery-latest.min.js"></script>
		<script src="http://code.jquery.com/jquery-2.1.4.js"></script>
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
		<script type="text/javascript" src="<?=base_url('assets/select2_baru.js')?>"></script>

		<script type="text/javascript">
			function HitungYAD() {

				var A = parseFloat($('#Vol').val().replace(/,/g, ''));
				var B = parseFloat($('#HrgSatuanYAD').val().replace(/,/g, ''));
				var C = parseFloat($('#PPN').val().replace(/,/g, ''));

				var Amnt = A * B;
				var TP = A * C;

				$('#Amount').val(numeral(Amnt).format(0.0));
				$('#TtlPPN').val(numeral(TP).format(0.0));

			}

			// function HitungYAD_edit(LedgerNo='') {

			// 	// alert(LedgerNo)

			// 	var A = ($('#Vol_edit_ID_'+LedgerNo).val()).split(',').join('').split('$').join('');

			// 	console.log(A)
			// 	// var A = parseFloat($('#Vol_edit_ID_'+LedgerNo).val().replace(/,/g, ''));
			// 	// var B = parseFloat($('#HrgSatuanYAD_edit').val().replace(/,/g, ''));
			// 	// var C = parseFloat($('#PPN_edit').val().replace(/,/g, ''));

			// 	// var Amnt = A * B;
			// 	// var TP = A * C;

			// 	// $('#Amount_edit').val(numeral(Amnt).format(0.0));
			// 	// $('#TtlPPN_edit').val(numeral(TP).format(0.0));


			// }
			

			function HitungJumlahHarga_Edit(LedgerNo){
				var Vol 		= ($('#Vol_edit_ID_'+LedgerNo).val()).split(',').join('').split('$').join('');
				var HrgSatuan 	= ($('#HrgSatuan_edit_ID_'+LedgerNo).val()).split(',').join('').split('$').join('');
				var PPN 		= ($('#PPN_edit_ID_'+LedgerNo).val()).split(',').join('').split('$').join('');

				Vol 			= parseFloat(Vol);
				HrgSatuan 		= parseFloat(HrgSatuan);
				PPN 			= parseFloat(PPN);

				var Amnt 		= 0;
				var TP 		= 0;

				Amnt = Vol * HrgSatuan;
				TP = Vol * PPN;

				// alert(numeral(Amnt).format(0.0));

				// $('#Amount').val(numeral(Amnt).format(0.0));
				$('#Amount_edit_ID_'+LedgerNo).val(numeral(Amnt).format(0.0));

				// $('#Amount_edit_ID_'+LedgerNo).prop('readonly', true).hide();
				$('#TtlPPN_edit_ID_'+LedgerNo).val(numeral(TP).format(0.0));

				// alert(PPN);.
			}

			function FormatVol_Buatan(num)
			{
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
			}

			function FormatVol_Buatan_HrgSatuan(num)
			{
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
				return("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
			}

			function FormatUang_TotalBayar_Class(num)
			{
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
				return("" + formatted);
			}

			function addCommas(nStr) {
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

		</script>



		<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
		<script type="text/javascript">
			$(document).ready(function() {
				$('.select2').select2({
					'width': '100%',
				});

				$(".Vol_edit_CLASS").on('input', function(e) 
				{
					var inputValue = $(this).val();
					var filteredValue = inputValue.replace(/[^0-9.-]/g, ''); 

					if (filteredValue.indexOf('-') > 0) { 
						filteredValue = filteredValue.replace(/-/g, '');
						filteredValue = '-' + filteredValue;
					}

					if (filteredValue.indexOf('.') == 0) { 
						filteredValue = '0' + filteredValue;
					}

					var parts = filteredValue.split('.');
					if (parts[1] && parts[1].length > 3) {
						parts[1] = parts[1].substr(0, 3);
						filteredValue = parts.join('.');
					}

					$(this).val(filteredValue);
				});

				$(".Vol_edit_CLASS").focusin(function()
				{
					if (this.value=='0.000' || this.value=='0') {
						$(this).val('');
					}
				});

				$(".Vol_edit_CLASS").focusout(function()
				{
					if (this.value=='') {
						$(this).val('0.000');
					}else{
						var Vol_edit_CLASS = parseFloat(this.value);
						Vol_edit_CLASS = Number(Vol_edit_CLASS).toFixed(3);
						Vol_edit_CLASS = FormatVol_Buatan(Vol_edit_CLASS);
						$(this).val(Vol_edit_CLASS);
					}

					HitungJumlahHarga_Edit($(this).attr('data-LedgerNo'));
				});


				$(".HrgSatuan_edit_CLASS").on('input', function(e) 
				{
					var inputValue = $(this).val();
					var filteredValue = inputValue.replace(/[^0-9.-]/g, ''); 

					if (filteredValue.indexOf('-') > 0) { 
						filteredValue = filteredValue.replace(/-/g, '');
						filteredValue = '-' + filteredValue;
					}

					if (filteredValue.indexOf('.') == 0) { 
						filteredValue = '0' + filteredValue;
					}

					var parts = filteredValue.split('.');
					if (parts[1] && parts[1].length > 3) {
						parts[1] = parts[1].substr(0, 3);
						filteredValue = parts.join('.');
					}

					$(this).val(filteredValue);
				});

				$(".HrgSatuan_edit_CLASS").focusin(function()
				{
					if (this.value=='0.000' || this.value=='0') {
						$(this).val('');
					}
				});

				$(".HrgSatuan_edit_CLASS").focusout(function()
				{
					// var LedgerNo = $(this).attr('data-LedgerNo');
					// alert(LedgerNo);
					if (this.value=='') {
						$(this).val('0');
					}else{
						// var HrgSatuan_edit_CLASS = parseFloat(this.value);
						var HrgSatuan_edit_CLASS = (this.value).split(',').join('').split('$').join('');
						HrgSatuan_edit_CLASS = parseFloat(HrgSatuan_edit_CLASS);
						HrgSatuan_edit_CLASS = Number(HrgSatuan_edit_CLASS);
						HrgSatuan_edit_CLASS = FormatVol_Buatan_HrgSatuan(HrgSatuan_edit_CLASS);

						$(this).val(HrgSatuan_edit_CLASS);
						// alert(HrgSatuan_edit_CLASS)
					}

					HitungJumlahHarga_Edit($(this).attr('data-LedgerNo'));


				});

				$(".PPN_edit_CLASS").on('input', function(e) 
				{
					var inputValue = $(this).val();
					var filteredValue = inputValue.replace(/[^0-9.-]/g, ''); 

					if (filteredValue.indexOf('-') > 0) { 
						filteredValue = filteredValue.replace(/-/g, '');
						filteredValue = '-' + filteredValue;
					}

					if (filteredValue.indexOf('.') == 0) { 
						filteredValue = '0' + filteredValue;
					}

					var parts = filteredValue.split('.');
					if (parts[1] && parts[1].length > 3) {
						parts[1] = parts[1].substr(0, 3);
						filteredValue = parts.join('.');
					}

					$(this).val(filteredValue);
				});

				$(".PPN_edit_CLASS").focusin(function()
				{
					if (this.value=='0.000' || this.value=='0') {
						$(this).val('');
					}
				});

				$(".PPN_edit_CLASS").focusout(function()
				{
					if (this.value=='') {
						$(this).val('0');
					}else{
						// var PPN_edit_CLASS = parseFloat(this.value);
						// PPN_edit_CLASS = Number(PPN_edit_CLASS);
						// PPN_edit_CLASS = FormatVol_Buatan_HrgSatuan(PPN_edit_CLASS);
						// $(this).val(PPN_edit_CLASS);

						var PPN_edit_CLASS = (this.value).split(',').join('').split('$').join('');
						PPN_edit_CLASS = parseFloat(PPN_edit_CLASS);
						PPN_edit_CLASS = Number(PPN_edit_CLASS);
						PPN_edit_CLASS = FormatVol_Buatan_HrgSatuan(PPN_edit_CLASS);
						
						$(this).val(PPN_edit_CLASS);
					}

					HitungJumlahHarga_Edit($(this).attr('data-LedgerNo'));


				});



				$("#BudgetRAP, #Terpakai").ready(function() {
					var budget = $("#BudgetRAP").val().replace(/,/g, '');
					var totalko = $("#totalko").val().replace(/,/g, '');
					var totalinv = $("#totalinv").val().replace(/,/g, '');

					var total = parseInt(totalko) - parseInt(totalinv);
					$("#wip").val(numeral(total).format(0.0));

					var estimasi = parseInt(total) - parseInt(yad);
					$('#estimasi').val(numeral(estimasi).format(0.0));

				});
			});

function autofill(KdRAP = '') {

	if (KdRAP == '') {
		var KdRAP = $('#KdRAP').val();
	}


	$.ajax({
		url: '<?= site_url('Job/ajax') ?>',
		type: 'post',
		dataType: 'JSON',
		data: {
			'KdRAP': KdRAP
		},
		beforeSend: function() {
			$('.loadingsaya').show();
		},
		success: function(data) {
			$('.loadingsaya').hide();
					// alert(data.TtlPPN);
			$('#Uraian').val('');
			$('#Satuan').val('');
			$('#Alokasi').val('');
			$('#Vol').val(0);
			$('#HrgSatuanYAD').val(0);
			$('#PPN').val(0);
			$('#TtlPPN').val(0);

			$('#Uraian').val(data.Uraian);
			$('#Satuan').val(data.Uom);
			$('#Alokasi').val(data.Alokasi);
			$('#Vol').val(data.Vol);
			$('#HrgSatuanYAD').val(data.HrgSatuan);
			$('#PPN').val(data.PPN);
			$('#TtlPPN').val(data.TtlPPN);

					// HitungYAD();
		},
		error: function() {
			$('.loadingsaya').hide();
			$.toast({
				text: 'Terjadi Kesalahan',
				position: 'bottom-right',
				stack: false,
				hideAfter: 1000,
				icon: 'error',

			});
		}
	})
}
</script>

<script>
		// function loadTipeForm() {
	$("#Alokasi").change(function() {
		var getAlokasi = this.value;
		$.ajax({
			type: "POST",
			dataType: "html",
			url: "<?= base_url(); ?>Job/getTipeForm",
			data: {
				Alokasi: getAlokasi
			},
			success: function(data) {
				$("#TipeForm").html(data);
			}
		});
	});
		// }
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
