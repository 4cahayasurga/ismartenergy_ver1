<style>
	table {
		border: 1px solid;
	}

	table tr th,
	table tr td {
		border: 1px solid;
	}

	table tr th {
		text-align: center;
	}

	table tr td {
		text-align: right;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<a href="<?= site_url('Report/MenuRptOperasi') ?>" class="btn btn-warning btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
		<!-- <a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a> -->
	</section>

	<!-- <Section class="content"> -->
	<section class="content-header">
		<div class="row" id="MOD-IN">
			<div class="col-md-12">
				<div class="box box-warning">
					<div class="box-header box-solid bg-blue-gradient">
						<h3 class="box-title">Bar Chart Proyek View</h3>
						<div class="pull-right box-tools">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
							</button>
						</div>
					</div>
					<div class="box-header with-border">
						<div class="box box-solid">

							<div class="box-body">
								<div class="form-group row">
									<form action="<?= site_url('Report/Barchart') ?>" method="POST">
										<label for="" class="col-sm-1 col-form-label">JobNo</label>
										<label for="" class="col-sm-1">:</label>
										<div class="col-sm-3">
											<select name="JobNoChart" class="form-control" id="JobNoChart" required>
												<option value="">-- Pilih Job No --</option>
												<?php
												foreach ($tampil_job_no->result() as $tampil_job) { ?>
													<option value="<?= $tampil_job->JobNo ?>" <?= ($tampil_job->JobNo == $JobNoChart) ? 'selected' : ''; ?>><?= $tampil_job->JobNo . " - " . $tampil_job->JobNm ?></option>
												<?php } ?>
											</select>
										</div>
										<button type="submit" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> &nbsp; PREVIEW</button>
									</form>
								</div>
							</div>
							<p>
								<?php
								if (isset($_POST['JobNoChart'])) { ?>
							<div class="col-sm-7">
								<div style="margin-bottom: 20px;">
									<button id="downloadPDF" class="btn btn-danger btn-xs" style="float: right;">Export PDF</button>
								</div>
								<div id="chartContainer" style="height: 370px; width: 100%; margin-top:50px;"></div>
								<!-- <canvas id="myChart" width="100%" height="100%"></canvas> -->
							</div>

							<br>
							<div class="col-sm-5">
								<h4><strong>
										<center>TERHADAP KONTRAK</center>
									</strong>
								</h4>
								<div class="table-responsive">
									<table width="100%" class="table ">
										<tr>
											<th colspan="2" style="background-color: #d8dee9;">Keterangan</th>
											<th style="background-color: #d8dee9;">Rp</th>
											<th style="background-color: #d8dee9;">%</th>
										</tr>
										<tr>
											<th colspan="2" style="background-color: #38d9a9;">Kontrak</th>
											<td><?= UbahMinus($bruto) ?></td>
											<td></td>
										</tr>
										<tr>
											<th rowspan="2">RAP</th>
											<th style="background-color: #6F9FD8;">B</th>
											<td><?= UbahMinus($RAP_B) ?></td>
											<td><?= UbahMinus(round($persen_rap_b)) . "%" ?></td>
										</tr>
										<tr>
											<th style="background-color:#fff85b;">C</th>
											<td><?= UbahMinus($RAP_C) ?></td>
											<td><?= UbahMinus(round($persen_rap_c)) . "%" ?></td>
										</tr>
										<tr>
											<th rowspan="2">Biaya (1)</th>
											<th style="background-color: #6F9FD8;">B</th>
											<td><?= UbahMinus($biaya_B) ?></td>
											<td></td>
										</tr>
										<tr>
											<th style="background-color:#fff85b;">C</th>
											<td><?= UbahMinus($biaya_C) ?></td>
											<td></td>
										</tr>
										<tr>
											<th colspan="2" style="background-color:#D1B894;">Sisa Hutang KO (2)</th>
											<td><?= UbahMinus($sisa_hutang_KO) ?></td>
											<td></td>
										</tr>
										<tr>
											<th rowspan="2">Sisa YAD (3)</th>
											<th style="background-color: #ffb90f;">B</th>
											<td><?= UbahMinus($sum_total_YAD_B) ?></td>
											<td></td>
										</tr>
										<tr>
											<th style="background-color:#da77f2;">C</th>
											<td><?= UbahMinus($sum_total_YAD_C) ?></td>
											<td></td>
										</tr>
										<tr>
											<th colspan="2">Total Biaya (1+2+3)</th>
											<td><?= UbahMinus($total_biaya_baru) ?></td>
											<td><?= UbahMinus(round($persen_biaya_baru)) . "%" ?></td>
										</tr>
										<tr>
											<th rowspan="2">Sisa Biaya</th>
											<th>Kontrak</th>
											<td><?= UbahMinus($sisa_biaya_terhadap_kontrak_baru) ?></td>
											<td><?= UbahMinus(round($persen_sisa_biaya_terhadap_kontrak_baru)) . "%" ?></td>
										</tr>
										<tr>
											<th>RAP</th>
											<td><?= UbahMinus($sisa_biaya_rap_terhadap_kontrak) ?></td>
											<td><?= UbahMinus(round($persen_sisa_biaya_rap_terhadap_kontrak)) . "%" ?></td>
										</tr>

									</table>

								</div>


							<?php } ?>



							</div>
						</div>
					</div>
				</div>
			</div>
	</section>
</div>
<script src="<?= base_url('assets/mycanvas.js') ?>"></script>


<?php

// $test[] = array(
// 	'label' => $bul, 'y' => $Pem
// );

$kontrak = array(
	array("label" => "KONTRAK", "y" => (int) $bruto),
	array("label" => "RAP", "y" => 0),
	array("label" => "Biaya", "y" => 0),
);

$rapB = array(
	array("label" => "KONTRAK", "y" => 0),
	array("label" => "RAP", "y" => (int) $RAP_B),
	array("label" => "Biaya", "y" => 0),
);
$rapC = array(
	array("label" => "KONTRAK", "y" => 0),
	array("label" => "RAP", "y" => $RAP_C),
	array("label" => "Biaya", "y" => 0),
);

$biayaB = array(
	array("label" => "KONTRAK", "y" => 0),
	array("label" => "RAP", "y" => 0),
	array("label" => "Biaya", "y" => $biaya_B),
);
$biayaC = array(
	array("label" => "KONTRAK", "y" => 0),
	array("label" => "RAP", "y" => 0),
	array("label" => "Biaya", "y" => $biaya_C),
);
$sisaHutangKO = array(
	array("label" => "KONTRAK", "y" => 0),
	array("label" => "RAP", "y" => 0),
	array("label" => "Biaya", "y" => $sisa_hutang_KO),
);
$sumTotalYadB = array(
	array("label" => "KONTRAK", "y" => 0),
	array("label" => "RAP", "y" => 0),
	array("label" => "Biaya", "y" => $sum_total_YAD_B),
);
$sumTotalYadC = array(
	array("label" => "KONTRAK", "y" => 0),
	array("label" => "RAP", "y" => 0),
	array("label" => "Biaya", "y" => $sum_total_YAD_C),
);
?>


<script>
	$(document).ready(function() {
		$('#JobNoChart').select2();

		var chart = new CanvasJS.Chart("chartContainer", {
			animationEnabled: true,
			exportEnabled: true,
			theme: "light1", // "light1", "light2", "dark1", "dark2"
			title: {
				text: "Bar Chart Proyek - <?php echo $nama_job ?>"
			},
			axisX: {
				reversed: true
			},
			axisY: {
				includeZero: true
			},
			toolTip: {
				shared: true
			},
			data: [{
					type: "stackedBar",
					name: "Kontrak",
					dataPoints: <?php echo json_encode($kontrak, JSON_NUMERIC_CHECK); ?>,
					color: '#12b886'
				}, {
					type: "stackedBar",
					name: "RAP B",
					dataPoints: <?php echo json_encode($rapB, JSON_NUMERIC_CHECK); ?>,
					color: "#6F9FD8"
				}, {
					type: "stackedBar",
					name: "RAP C",
					dataPoints: <?php echo json_encode($rapC, JSON_NUMERIC_CHECK); ?>,
					color: "#fff85b"
				}, {
					type: "stackedBar",
					name: "BIAYA B",
					dataPoints: <?php echo json_encode($biayaB, JSON_NUMERIC_CHECK); ?>,
					color: "#6F9FD8"
				}, {
					type: "stackedBar",
					name: "BIAYA C",
					dataPoints: <?php echo json_encode($biayaC, JSON_NUMERIC_CHECK); ?>,
					color: "#fff85b"
				},
				{
					type: "stackedBar",
					name: "Sisa Hutang KO",
					dataPoints: <?php echo json_encode($sisaHutangKO, JSON_NUMERIC_CHECK); ?>,
					color: "#D1B894"
				},
				{
					type: "stackedBar",
					name: "Sisa YAD B",
					dataPoints: <?php echo json_encode($sumTotalYadB, JSON_NUMERIC_CHECK); ?>,
					color: "#ffb90f"
				},
				{
					type: "stackedBar",
					name: "Sisa YAD C",
					dataPoints: <?php echo json_encode($sumTotalYadC, JSON_NUMERIC_CHECK); ?>,
					color: "#da77f2"
				},
			]
		});
		chart.render();


		$("#downloadPDF").click(function() {
			var canvas = $("#chartContainer .canvasjs-chart-canvas").get(0);
			var dataURL = canvas.toDataURL('image/png');
			var imageData = dataURL.replace(/^data:image\/(png|jpg);base64,/, '');
			$.ajax({
				type: "POST",
				url: "<?= site_url('Report/saveChart') ?>",
				data: imageData,
				contentType: "application/octet-stream",
				success: function(response) {
					console.log(response);
					var url = "<?= site_url('Report/exportPDFBarChartProyek/' . $JobNoChart) ?>"
					window.open(url, '_blank')
				},
				error: function(error) {
					console.error(error);
				}
			});
		});

	})
</script>


<script>
	$(document).idle({
		onIdle: function() {
			window.location = "/logout.php";
		},
		idle: 10000
	});
</script>