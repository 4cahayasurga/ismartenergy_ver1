<!-- Content Wrapper. Contains page content -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcxre39pTkzGvIjkqAxg73iN609rg2MDA&callback=initMap" async defer></script>
<script type="text/javascript"></script>

<?php error_reporting(0) ?>

<script>
	function initMap() {
		var map = new google.maps.Map(document.getElementById('show_maps'), {
			center: {
				lat: -1.035721,
				lng: 118.436931
			},
			zoom: 5
		});
	}
</script>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Dashboard
			<small>Control panel</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Dashboard</li>
		</ol>
		<?php echo date('l, d-m-Y'); ?>
	</section>

	<section class="content-header">
		<a href="<?= site_url('Report/MenuRptOperasi') ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
	</section>



	<section class="content">

		<div class="row">
			<div class="col-lg-12 connectedSortable">
				<div class="box">
					<div class="box-header box-solid bg-yellow-gradient">
						<h3 class="box-title">INFORMASI PROGRESS PROYEK</h3>

						<div class="pull-right box-tools">
							<!-- button with a dropdown -->
							<div class="btn-group">
							</div>
							<button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
							</button>
						</div>
					</div>
					<div class="box-body">
						<form action="<?= site_url('Report/KurvaProgress') ?>" method="POST">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Job No.</label>
									<select name="JobNoChart" class="form-control" id="JobNoChart" required>
										<option value="">-- Pilih Job No --</option>
										<?php
										foreach ($tampil_job_no->result() as $tampil_job) { ?>
											<option value="<?= $tampil_job->JobNo ?>" <?= ($tampil_job->JobNo == $JobNoChart) ? 'selected' : ''; ?>><?= $tampil_job->JobNo . " - " . $tampil_job->JobNm ?></option>
										<?php } ?>
									</select>
									<br>

								</div>
								<button type="submit" class="btn btn-default btn-sm">Tampil</button>

							</div>
						</form>


					</div>
				</div>


			</div>
		</div>

		<div class="row">
			<div class="col-lg-12 connectedSortable">
				<div class="box">
					<div class="box-header box-solid bg-blue-gradient">
						<h3 class="box-title">GRAFIK PERFOMANCE (%)</h3>

						<div class="pull-right box-tools">
							<!-- button with a dropdown -->
							<div class="btn-group">
							</div>
							<button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
							</button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<h5>(Note : Untuk komparasi data, Silahkan nonaktifkan data yang ingin di hide dengan <strong>klik kotak warna</strong> data)</h5>
								<br>

								<?php
								if (isset($_POST['JobNoChart'])) { ?>
									<canvas id="myChart" width="100%" height="30%"></canvas>


							</div>
						</div>
					<?php } ?>

					</div>
				</div>


			</div>
		</div>

		<!-- <div class="row">
			<!-- Left col --
			<section class="col-lg-12 connectedSortable">
				<!-- Custom tabs (Charts with tabs)--
				<div class="box box-solid bg-blue-gradient">
					<div class="box-header">
						<i class="fa fa-bar-chart"></i>

						<h3 class="box-title">HISTORY HUTANG KO</h3>
						<!-- tools box --
						<div class="pull-right box-tools">

							<button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
							</button>
						</div>
						<!-- /. tools --
					</div>
					<!-- /.box-header --
					<!-- <div class="box-body no-padding">
            
            <div id="Absen1" style="width: 100%"></div>
        </div> -->
		<!-- /.box-body --
					<div class="box-footer text-black">
						<div class="row">
							<div class="col-md-12">
								<div class="box">
									<div class="box-body">

										<div class="col-lg-12">
											<!-- <div class="tabel-responsive">
												<table class="table table-bordered">
													<tr>
														<th>Payment KO</th>
														<th>Remaining KO</th>
														<th>Total KO </th>
														<th>Total Inv</th>
														<th>Payment Inv</th>
														<th>Remaining Inv</th>

													</tr>
													<?php foreach ($historyko as $kh => $value) : ?>
														<tr>
															<td><?= number_format($value->PaymentKO) ?></td>
															<td><?= number_format($value->RemainingKO) ?></td>
															<td><?= number_format($value->AmountTotal) ?></td>
															<td><?= number_format($value->TotalInv) ?></td>
															<td><?= number_format($value->PaymentInv) ?></td>
															<td><?= number_format($value->RemainingInv) ?></td>

														</tr>
													<?php endforeach; ?>
												</table>
											</div> --

											<div class="col-sm-12">

												<div class="col-sm-6">
													<div id="catair" style="height: 370px; width: 100%;"></div>
												</div>

												<div class="col-sm-6">
													<div id="catbasah" style="height: 370px; width: 100%;"></div>
												</div>

											</div>
										</div>
									</div>
								</div>
								<!-- /.col --
							</div>
							<!-- /.row --
						</div>
					</div>


			</section>

		</div> -->







		<?php
		$query_new_termin = $this->db->query("
							

								SELECT 
								SUM(CASE
								WHEN MONTH(TglCair)='01' THEN TerminInduk

								ELSE 0
								END) as Januari,
								SUM(CASE
								WHEN MONTH(TglCair)='02' THEN TerminInduk
								ELSE 0
								END)  as Februari,
								SUM(CASE
								WHEN MONTH(TglCair)='03' THEN TerminInduk
								ELSE 0
								END)  as Maret,
								SUM(CASE
								WHEN MONTH(TglCair)='04' THEN TerminInduk
								ELSE 0
								END)  as April,
								SUM(CASE
								WHEN MONTH(TglCair)='05' THEN TerminInduk
								ELSE 0
								END)  as Mei,
								SUM(CASE
								WHEN MONTH(TglCair)='06' THEN TerminInduk
								ELSE 0
								END) as Juni,

								SUM(CASE
								WHEN MONTH(TglCair)='07' THEN TerminInduk
								ELSE 0
								END) as Juli,

								SUM(CASE
								WHEN MONTH(TglCair)='08' THEN TerminInduk
								ELSE 0
								END) as Agustus,

								SUM(CASE
								WHEN MONTH(TglCair)='09' THEN TerminInduk
								ELSE 0
								END) as September,

								SUM(CASE
								WHEN MONTH(TglCair)='10' THEN TerminInduk
								ELSE 0
								END) as Oktober,

								SUM(CASE
								WHEN MONTH(TglCair)='11' THEN TerminInduk
								ELSE 0
								END) as November,

								SUM(CASE
								WHEN MONTH(TglCair)='12' THEN TerminInduk

								ELSE 0
								END)AS Desember
								FROM TerminInduk WHERE JobNo='$JobNo' AND Jenis='Termin' AND YEAR(TglCair)='2021' ");
		?>

	</section>
	<!-- right col -->
</div>

<?php
//Inisialisasi nilai variabel awal
$bulan = "";
$rencana_progress = null;
$realisasi_progress = null;
$rencana_termin = null;
$realisasi_termin = null;
$realisasi_biaya = null;
$rencana_biaya = null;

foreach ($result as $item) {
	$bul = $item->month;
	$bulan .= "'$bul'" . ", ";

	$renprog = $item->balance_rencana_progress;
	$rencana_progress .= "$renprog" . ", ";

	$realprog = $item->balance_realisasi_progress;
	$realisasi_progress .= "$realprog" . ", ";

	$retermin = $item->balance_rencana_termin;
	$rencana_termin .= "$retermin" . ", ";

	$realTermin = $item->balance_realisasi_termin;
	$realisasi_termin .= "$realTermin" . ", ";

	$monTermin = $item->balance_monitoring_termin;
	$monitoring_termin .= "$monTermin" . ", ";

	$realBiaya = $item->balance_realisasi_biaya_rap;
	$realisasi_biaya .= "$realBiaya" . ", ";

	$rencanaBiaya = $item->balance_rencana_biaya_rap;
	$rencana_biaya .= "$rencanaBiaya" . ", ";
}
?>


<script>
	$(document).ready(function() {
		$('#JobNoChart').select2();
	})
	// $data['DataCurva'] = $this->db->query("Report_GraphCurva N'{$JobNo}'")->result();

	const ctx = document.getElementById('myChart').getContext('2d');
	const myChart = new Chart(ctx, {
		type: 'line',
		data: {
			// labels: ['KONTRAK', 'RAP B', 'RAP C', 'BIAYA B', 'BIAYA C', 'SISA HUTANG KO', 'YAD B', 'YAD C', 'TOTAL BIAYA (1+2+3)', 'SISA KONTRAK', 'SISA RAP'],
			labels: [<?php echo $bulan ?>],
			datasets: [{
					label: 'Rencana Progress',
					data: [<?= $rencana_progress ?>],
					backgroundColor: [
						'rgba(231, 76, 60,1.0)',
					],
					borderColor: [
						'rgba(231, 76, 60,1.0)',
					],
					borderWidth: 1
				},

				{
					label: 'realisasi Progress',
					data: [<?= $realisasi_progress ?>],
					backgroundColor: [
						'rgba(52, 152, 219,1.0)',
					],
					borderColor: [
						'rgba(52, 152, 219,1.0)',
					],
					borderWidth: 1
				},

				{
					label: 'Rencana Termin',
					data: [<?= $rencana_termin ?>],
					backgroundColor: [
						'rgba(241, 196, 15,1.0)',
					],
					borderColor: [
						'rgba(241, 196, 15,1.0)',
					],
					borderWidth: 1
				},

				{
					label: 'Realisasi Termin (W/O UM)',
					data: [<?= $realisasi_termin ?>],
					backgroundColor: [
						'rgba(39, 174, 96,1.0)',
					],
					borderColor: [
						'rgba(39, 174, 96,1.0)',
					],
					borderWidth: 1
				},

				{
					label: 'Monitoring Termin (With UM)',
					data: [<?= $monitoring_termin ?>],
					backgroundColor: [
						'rgba(232, 67, 147,1.0)',
					],
					borderColor: [
						'rgba(232, 67, 147,1.0)',
					],
					borderWidth: 1
				},

				{
					label: 'Rencana Biaya',
					data: [<?= $rencana_biaya ?>],
					backgroundColor: [
						'rgba(230, 126, 34,1.0)',
					],
					borderColor: [
						'rgba(230, 126, 34,1.0)',
					],
					borderWidth: 1
				},

				{
					label: 'Realisasi Biaya',
					data: [<?= $realisasi_biaya ?>],
					backgroundColor: [
						'rgba(142, 68, 173,1.0)',
					],
					borderColor: [
						'rgba(142, 68, 173,1.0)',
					],
					borderWidth: 1
				}
			]
		},
		options: {
			scales: {
				y: {
					beginAtZero: true
				}
			}
		}
	});

	const ctxKeuanganFisik = document.getElementById('ChartKeuanganFisik').getContext('2d');
	const ChartKeuanganFisik = new Chart(ctxKeuanganFisik, {
		type: 'line',

		data: {
			labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
			datasets: [{
					label: "Progress Keuangan",
					fill: false,
					lineTension: 0.1,
					backgroundColor: "#29B0D0",
					borderColor: "#29B0D0",
					pointHoverBackgroundColor: "#29B0D0",
					pointHoverBorderColor: "#29B0D0",
					data: [<?php foreach ($query_new_termin->result_array() as $p) {
									echo '"' . $p['Januari'] . '","' . $p['Februari'] . '","' . $p['Maret'] . '","' . $p['April'] . '","' . $p['Mei'] . '","' . $p['Juni'] . '","' . $p['Juli'] . '","' . $p['Agustus'] . '","' . $p['September'] . '","' . $p['Oktober'] . '","' . $p['November'] . '","' . $p['Desember'] . '",';
								} ?>]
				},

			]

		},
		options: {
			scales: {
				y: {
					beginAtZero: true
				}
			}
		}
	});
</script>

<?php
foreach ($historyko as $hk) {
	$PaymentKO = $value->PaymentKO;
	$RemainingKO = $value->RemainingKO;
	$TotalKO = $value->AmountTotal;

	$TotalInv = $value->TotalInv;
	$PaymentInv = $value->PaymentInv;
	$RemainingInv = $value->RemainingInv;

	$label1 = "History Hutang KO";

	$PaymentKO = array(
		array("label" => $label1, "y" => $PaymentKO),
	);

	$RemainingKO = array(
		array("label" => $label1, "y" => $RemainingKO),
	);

	$TotalKO = array(
		array("label" => $label1, "y" => $TotalKO),
	);

	$TotalInv = array(
		array("label" => $label1, "y" => $TotalInv)
	);

	$PaymentInv = array(
		array("label" => $label1, "y" => $PaymentInv)
	);
	$RemainingInv = array(
		array("label" => $label1, "y" => $RemainingInv)
	);
}
?>
<script>
	var chart = new CanvasJS.Chart("catair", {
		title: {
			text: "HUTANG KO BERDASARKAN KONTRAK"
		},
		theme: "light2",
		animationEnabled: true,
		toolTip: {
			shared: true,
			reversed: true
		},
		axisY: {
			title: "Cumulative Value",
			// suffix: " MW"
		},
		legend: {
			cursor: "pointer",
			itemclick: toggleDataSeries
		},
		data: [{
			type: "column",
			name: "Payment KO",
			showInLegend: true,
			color: "Orange",
			// yValueFormatString: "#,##0",
			dataPoints: <?php echo json_encode($PaymentKO, JSON_NUMERIC_CHECK); ?>
		}, {
			type: "column",
			name: "Remaining KO",
			showInLegend: true,
			color: "green",
			// yValueFormatString: "#,##0",
			dataPoints: <?php echo json_encode($RemainingKO, JSON_NUMERIC_CHECK); ?>
		}, {
			type: "column",
			name: "Total KO",
			showInLegend: true,
			color: "yellow",
			// yValueFormatString: "#,##0",
			dataPoints: <?php echo json_encode($TotalKO, JSON_NUMERIC_CHECK); ?>
		}]
	});

	chart.render();

	function toggleDataSeries(e) {
		if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
			e.dataSeries.visible = false;
		} else {
			e.dataSeries.visible = true;
		}
		e.chart.render();
	}

	var chart = new CanvasJS.Chart("catbasah", {
		title: {
			text: "HUTANG KO BERDASARKAN INVOICE"
		},
		theme: "light2",
		animationEnabled: true,
		toolTip: {
			shared: true,
			reversed: true
		},
		axisY: {
			title: "Cumulative Value",
			// suffix: " MW"
		},
		legend: {
			cursor: "pointer",
			itemclick: toggleDataSeries
		},
		data: [{
			type: "column",
			name: "Payment Invoice",
			showInLegend: true,
			color: "blue",
			// yValueFormatString: "#,##0",
			dataPoints: <?php echo json_encode($PaymentInv, JSON_NUMERIC_CHECK); ?>
		}, {
			type: "column",
			name: "Remaining Invoice",
			showInLegend: true,
			color: "red",
			// yValueFormatString: "#,##0",
			dataPoints: <?php echo json_encode($RemainingInv, JSON_NUMERIC_CHECK); ?>
		}, {
			type: "column",
			name: "Total Invoice",
			showInLegend: true,
			color: "purple",
			// yValueFormatString: "#,##0",
			dataPoints: <?php echo json_encode($TotalInv, JSON_NUMERIC_CHECK); ?>
		}]
	});

	chart.render();

	function toggleDataSeries(e) {
		if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
			e.dataSeries.visible = false;
		} else {
			e.dataSeries.visible = true;
		}
		e.chart.render();
	}
	// var chart = new CanvasJS.Chart("catair", {
	// 	title: {
	// 		text: "HUTANG BERDASARKAN KO/PO   "
	// 	},
	// 	theme: "light2",
	// 	animationEnabled: true,
	// 	toolTip: {
	// 		shared: true,
	// 		reversed: true
	// 	},
	// 	axisY: {
	// 		title: "Cumulative Value",
	// 		suffix: " IDR"
	// 	},
	// 	legend: {
	// 		cursor: "pointer",
	// 		itemclick: toggleDataSeries
	// 	},
	// 	data: [{
	// 		type: "column",
	// 		name: "Payment KO",
	// 		showInLegend: true,
	// 		yValueFormatString: "#,##0 IDR",
	// 		dataPoints: <?php echo json_encode($PaymentKO, JSON_NUMERIC_CHECK); ?>
	// 	}, {
	// 		type: "column",
	// 		name: "Remaining KO",
	// 		showInLegend: true,
	// 		yValueFormatString: "#,##0 IDR",
	// 		dataPoints: <?php echo json_encode($RemainingKO, JSON_NUMERIC_CHECK); ?>
	// 	}, {
	// 		type: "column",
	// 		name: "Total KO",
	// 		showInLegend: true,
	// 		yValueFormatString: "#,##0 IDR",
	// 		dataPoints: <?php echo json_encode($TotalKO, JSON_NUMERIC_CHECK); ?>
	// 	}],
	// 	// data: [{
	// 	// 	type: "stackedColumn",
	// 	// 	name: "Total Invoice",
	// 	// 	showInLegend: true,
	// 	// 	yValueFormatString: "#,##0 IDR",
	// 	// 	dataPoints: <?php echo json_encode($TotalInv, JSON_NUMERIC_CHECK); ?>
	// 	// }, {
	// 	// 	type: "stackedColumn",
	// 	// 	name: "Payment Invoice",
	// 	// 	showInLegend: true,
	// 	// 	yValueFormatString: "#,##0 IDR",
	// 	// 	dataPoints: <?php echo json_encode($PaymentInv, JSON_NUMERIC_CHECK); ?>
	// 	// }, {
	// 	// 	type: "stackedColumn",
	// 	// 	name: "Remaining Invoice",
	// 	// 	showInLegend: true,
	// 	// 	yValueFormatString: "#,##0 IDR",
	// 	// 	dataPoints: <?php echo json_encode($RemainingInv, JSON_NUMERIC_CHECK); ?>
	// 	// }],
	// });

	// chart.render();

	// function toggleDataSeries(e) {
	// 	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
	// 		e.dataSeries.visible = false;
	// 	} else {
	// 		e.dataSeries.visible = true;
	// 	}
	// 	e.chart.render();
	// }
</script>

</body>

</html>
