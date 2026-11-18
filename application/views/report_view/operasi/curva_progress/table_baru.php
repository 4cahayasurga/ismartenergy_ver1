

<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcxre39pTkzGvIjkqAxg73iN609rg2MDA&callback=initMap" async defer></script> -->
<?php
// error_reporting(0);
?>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<div class="box no-border">


	<div class="box-body">
		
		<table width="100%" border="0">
			<tr>
				<td style="font-size:14px;background-color: #1abc9c;color:white;padding-top:5px;padding-bottom:5px;">GRAFIK PERFORMANCE (%)</td>
			</tr>
		</table>

		<table width="100%" border="0">
			<tr>
				<td style="padding-top:5px;padding-bottom:5px;">(Note : Untuk komparasi data, Silahkan nonaktifkan data yang ingin di hide dengan klik kotak warna data)</td>
			</tr>
		</table>


		<div class="col-12" >
			<canvas id="myChart" width="100%" height="30%"></canvas>
		</div>


	</div>

</div>


<script>
	const ctx = document.getElementById('myChart').getContext('2d');
	const myChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: [<?=$Bulan?>],
			datasets: [
			{
				label: 'Rencana Progress',
				data: [<?=$Balance_Rencana_Progress?>],
				backgroundColor: [
				'rgba(231, 76, 60,1.0)',
				],
				borderColor: [
				'rgba(231, 76, 60,1.0)',
				],
				borderWidth: 1
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


