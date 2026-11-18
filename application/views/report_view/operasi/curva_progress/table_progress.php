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


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcxre39pTkzGvIjkqAxg73iN609rg2MDA&callback=initMap" async defer></script>
<script type="text/javascript">
	const ctx = document.getElementById('myChart').getContext('2d');
	const myChart = new Chart(ctx, {
		type: 'line',
		data: {
			// labels: ['KONTRAK', 'RAP B', 'RAP C', 'BIAYA B', 'BIAYA C', 'SISA HUTANG KO', 'YAD B', 'YAD C', 'TOTAL BIAYA (1+2+3)', 'SISA KONTRAK', 'SISA RAP'],
			labels: [<?php echo $bulan ?>],
			datasets: [{
					label: 'Rencana Progress',
					data: ['12'],
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
					data: ['345'],
					backgroundColor: [
						'rgba(52, 152, 219,1.0)',
					],
					borderColor: [
						'rgba(52, 152, 219,1.0)',
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

