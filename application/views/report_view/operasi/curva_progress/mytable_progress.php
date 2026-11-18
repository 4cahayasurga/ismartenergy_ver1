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
				<td style="font-size:14px;font-weight: bold;text-align: right;"><?= $JobNo . ' - ' . $JobNm ?></td>
			</tr>

		</table>


		<div class="col-12" style="padding-top: 20px;">
			<canvas id="myChart" width="100%" height="30%"></canvas>
		</div>


	</div>

</div>

<?php
$bulan = "";
$rencana_progress = null;
$realisasi_progress = null;
$rencana_termin = null;
$realisasi_termin = null;
$realisasi_biaya = null;
$rencana_biaya = null;

foreach ($result as $item) {
	$bul = date('M-Y', strtotime($item->Tanggal_Gabungan));
	$bulan .= "'$bul'" . ", ";

	$renprog = ($item->balance_rencana_progress == 0) ? NULL : $item->balance_rencana_progress;
	$rencana_progress .= "$renprog" . ", ";

	$realprog = ($item->balance_realisasi_progress == 0) ? NULL : $item->balance_realisasi_progress;
	$realisasi_progress .= "$realprog" . ", ";

	$retermin = ($item->Ballance_R_Termin1 == 0) ? NULL : $item->Ballance_R_Termin1;
	$rencana_termin .= "$retermin" . ", ";

	$realTermin = ($item->balance_realisasi_termin == 0) ? NULL : $item->balance_realisasi_termin;
	$realisasi_termin .= "$realTermin" . ", ";


	$realBiaya = ($item->balance_realisasi_biaya_rap == 0) ? NULL : $item->balance_realisasi_biaya_rap;
	$realisasi_biaya .= "$realBiaya" . ", ";

	$rencanaBiaya = ($item->balance_rencana_biaya_rap == 0) ? NULL : $item->balance_rencana_biaya_rap;
	$rencana_biaya .= "$rencanaBiaya" . ", ";
}
?>

<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcxre39pTkzGvIjkqAxg73iN609rg2MDA&callback=initMap" async defer></script> -->
<script src="<?= base_url('assets/mycanvas.js') ?>"></script>
<script type="text/javascript">
	$(function() {

		const ctx = document.getElementById('myChart').getContext('2d');
		const myChart = new Chart(ctx, {
			type: 'line',
			data: {

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
						label: 'Realisasi Progress',
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


	})
</script>