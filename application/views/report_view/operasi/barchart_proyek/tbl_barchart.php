<div class="box no-border">


	<div class="box-body">
		
		<table width="100%" border="0">
			<tr>
				<td style="font-size:14px;background-color: #1abc9c;color:white;padding-top:5px;padding-bottom:5px;">&nbsp;&nbsp;&nbsp;CHART PROYEK</td>
			</tr>
		</table>

		<table width="100%" border="0">
			<tr>
				<td style="padding-top:5px;padding-bottom:5px;">&nbsp;&nbsp;&nbsp;(Note : Untuk komparasi data, Silahkan nonaktifkan data yang ingin di hide dengan klik kotak warna data)</td>
				<td style="font-size:14px;font-weight: bold;text-align: right;"><?=$JobNo.' - '.$JobNm?>&nbsp;&nbsp;</td>
			</tr>
			
		</table>


		<div class="col-12" style="padding-top: 20px;">
			<canvas id="chart_saya" width="100%" height="30%"></canvas>
		</div>


	</div>

</div>

<script src="<?=base_url('assets/mycanvas.js')?>"></script>
<?php

$kontrak = array(
	array("label" => "KONTRAK", "y" => (int) $bruto),
	array("label" => "RAP", "y" => 0),
	array("label" => "Biaya", "y" => 0),
);

$rap = array(
	array("label" => "KONTRAK", "y" => 0),
	array("label" => "RAP", "y" => $RAP_B),
	array("label" => "Biaya", "y" => 0),
);

$biaya = array(
	array("label" => "KONTRAK", "y" => 0),
	array("label" => "RAP", "y" => $RAP_C),
	array("label" => "Biaya", "y" => 0),
);
?>
<script>
	window.onload = function() {

		var chart = new CanvasJS.Chart("chart_saya", {
			animationEnabled: true,
			exportEnabled: true,
			theme: "light1",
			title: {
				text: "Bar Chart Proyek - <?php echo $tampil_job->JobNm ?>"
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
				dataPoints: <?php echo json_encode($kontrak, JSON_NUMERIC_CHECK); ?>
			}, {
				type: "stackedBar",
				name: "RAP B",
				dataPoints: <?php echo json_encode($rap, JSON_NUMERIC_CHECK); ?>
			}, {
				type: "stackedBar",
				name: "RAP C",
				dataPoints: <?php echo json_encode($biaya, JSON_NUMERIC_CHECK); ?>
			}
			, {
				type: "stackedBar",
				name: "BIAYA B",
				dataPoints: <?php echo json_encode($biaya, JSON_NUMERIC_CHECK); ?>
			}, {
				type: "stackedBar",
				name: "BIAYA C",
				dataPoints: <?php echo json_encode($biaya, JSON_NUMERIC_CHECK); ?>
			}
		]
		});
		chart.render();



	}
</script>



