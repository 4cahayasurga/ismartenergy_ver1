<!-- <a href="javascript:void(0)" id="tambah-data" data-JobNo="<?= $JobNo ?>" data-alokasi="<?= $alokasi ?>" data-versi="<?= $Versi_rap ?>" class="btn bg-orange btn-flat btn-sm tambah-data"><i class="fa fa-plus"></i> TAMBAH DATA</a>

<a href="javascript:void(0)" id="tambah-rap" data-JobNo="<?= $JobNo ?>" data-alokasi="<?= $alokasi ?>" data-versi="<?= $Versi_rap ?>" class="btn bg-purple btn-flat btn-sm tambah-rap"><i class="fa fa-plus"></i> TAMBAH DATA</a> -->

<!-- <div class="table-responsive" style="padding-top:10px;">
	<table class="table table-bordered table-striped table-hover table-condensed" id="mytable">
		<thead>
			<tr>
				<th style="background-color: #1abc9c; color : white;vertical-align:middle;">
					<center> Kode RAP</center>
				</th>
				<th style="background-color: #1abc9c; color : white;vertical-align:middle;">
					<center> Uraian</center>
				</th>
				<th style="background-color: #1abc9c; color : white;vertical-align:middle;">
					<center> Sat</center>
				</th>
				<th style="background-color: #1abc9c; color : white;vertical-align:middle;">
					<center> Vol</center>
				</th>
				<th style="background-color: #2980b9; color : white;vertical-align:middle;">
					<center> RAP <p> Harga Satuan (Rp)</center>
				</th>
				<th style="background-color: #2980b9; color : white;vertical-align:middle;">
					<center> RAP <p> Jumlah Harga (Rp)</center>
				</th>
				<th style="background-color: #2980b9; color : white;vertical-align:middle;">
					<center> RAP <p> Harga Satuan (Rp)</center>
				</th>
				<th style="background-color: #2ecc71; color : white;vertical-align:middle;">
					<Center> RAB <p> Harga Satuan (Rp)</Center>
				</th>
				<th style="background-color: #2ecc71; color : white;vertical-align:middle;">
					<center> RAB <p> Jumlah Harga (Rp)</center>
				</th>
				<th style="background-color: #2ecc71; color : white;vertical-align:middle;">
					<center> RAB <p> Bobot %</center>
				</th>
				<th style="background-color: #1abc9c; color : white;vertical-align:middle;"></th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div> -->

<a href="javascript:void(1)" id="view-rap" data-JobNo-rap="<?= $JobNo ?>" data-alokasi-rap="<?= $alokasi_rap ?>" data-versi-rap="<?= $versi_rap ?>" class="btn bg-purple btn-flat btn-sm view-rap">VIEW</a>

<div class="table-responsive" style="padding-top:10px;">
	<table class="table table-bordered table-striped table-hover table-condensed" id="mytable">
		<thead>
			<tr>
				<th class="text-center" style="background-color: #708090; color : white;vertical-align:middle;" rowspan="2">Kode RAP</th>
				<th class="text-center" style="background-color: #708090; color : white;vertical-align:middle;" rowspan="2">Uraian</th>
				<th class="text-center" style="background-color: #708090; color : white;vertical-align:middle;" rowspan="2">Sat</th>
				<th class="text-center" style="background-color: #708090; color : white;vertical-align:middle;" rowspan="2">Vol</th>
				<th class="text-center" style="background-color: #00BFFF; color : black;vertical-align:middle;" colspan="3">RAP</th>
				<th class="text-center" style="background-color: #7CFC00; color : black;vertical-align:middle;" colspan="3">RAB</th>
				<th class="text-center" style="background-color: #708090; color : white;vertical-align:middle;" rowspan="2">RAP/RAB (%)</th>
			</tr>
			<tr>
				<th class="text-center" style="background-color: #00BFFF; color : black;vertical-align:middle;">Harga Satuan (Rp)</th>
				<th class="text-center" style="background-color: #00BFFF; color : black;vertical-align:middle;">PPN (Rp)</th>
				<th class="text-center" style="background-color: #00BFFF; color : black;vertical-align:middle;">Jumlah Harga (Rp)</th>
				<th class="text-center" style="background-color: #00BFFF; color : black;vertical-align:middle;">Bobot (%)</th>
				<th class="text-center" style="background-color: #7CFC00; color : black;vertical-align:middle;">Harga Satuan (Rp)</th>
				<th class="text-center" style="background-color: #7CFC00; color : black;vertical-align:middle;">Jumlah Harga (Rp)</th>
				<th class="text-center" style="background-color: #7CFC00; color : black;vertical-align:middle;">Bobot (%)</th>
			</tr>
		</thead>
		<tbody>
			<?php $no = 1;
			foreach ($result as $key => $index) : ?>
				<tr>
					<?php if ($index->no_item == "TOTAL") { ?>
						<td class="text-center" style="background-color: #708090; color : white;vertical-align:top;" colspan="4"><strong><?php echo $index->no_item ?></strong></td>
					<?php } else { ?>
						<?php if ($index->Tipe == "Top Header") { ?>
							<td class="text-left" style="background-color: #DCDCDC; color : black;vertical-align:top;"><strong><?php echo $index->no_item ?></strong></td>
						<?php } elseif ($index->Tipe == "Header") { ?>
							<td class="text-right" style="background-color: #DCDCDC; color : black;vertical-align:top;"><strong><?php echo $index->no_item ?></strong></td>
						<?php } elseif ($index->Tipe == "Total") { ?>
							<td class="text-right" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo $index->no_item ?></strong></td>
						<?php } else { ?>
							<td style="background-color: #DCDCDC; color : black;vertical-align:top;"></td>
						<?php } ?>
					<?php } ?>

					<?php if ($index->no_item != "TOTAL") { ?>
						<?php if ($index->Tipe == "Top Header" || $index->Tipe == "Header") { ?>
							<td class="text-left" style="background-color: #DCDCDC; color : black;vertical-align:top;"><strong><?php echo $index->item_kegiatan ?></strong></td>
						<?php } elseif ($index->Tipe == "Sumda") { ?>
							<td class="text-left" style="background-color: #DCDCDC; color : black;vertical-align:top;"><?php echo $index->no_item . ' - ' . $index->item_kegiatan ?></td>
						<?php } elseif ($index->Tipe == "Total") { ?>
							<td class="text-left" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo $index->item_kegiatan ?></strong></td>
						<?php } else { ?>
							<td style="padding-left: 30px; background-color: #DCDCDC; color : black;vertical-align:top;"><?php echo $index->no_item . ' - ' . $index->item_kegiatan ?></td>
						<?php } ?>
					<?php } ?>

					<?php if ($index->no_item != "TOTAL") { ?>
						<?php if ($index->Tipe == "Top Header" || $index->Tipe == "Header") { ?>
							<td style="background-color: #DCDCDC; color : black;vertical-align:top;"><strong><?php echo $index->uom; ?></strong></td>
						<?php } elseif ($index->Tipe == "Total") { ?>
							<td style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo $index->uom ?></strong></td>
						<?php } else { ?>
							<td style="background-color: #DCDCDC; color : black;vertical-align:top;"><?php echo $index->uom; ?></td>
						<?php } ?>
					<?php } ?>

					<?php if ($index->no_item != "TOTAL") { ?>
						<?php if ($index->Tipe == "Top Header" || $index->Tipe == "Header") { ?>
							<td class="text-right" style="background-color: #DCDCDC; color : black;vertical-align:top;"><strong><?php echo number_format($index->vol); ?></strong></td>
						<?php } elseif ($index->Tipe == "Total") { ?>
							<td class="text-right" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo number_format($index->vol); ?></strong></td>
						<?php } else { ?>
							<td class="text-right" style="background-color: #DCDCDC; color : black;vertical-align:top;"><?php echo number_format($index->vol); ?></td>
						<?php } ?>
					<?php } ?>

					<?php if ($index->no_item == "TOTAL") { ?>
						<td class="text-right" style="background-color: #708090; color : white;vertical-align:top;"><strong><?php echo number_format($index->HrgSatuan_rap); ?></strong></td>
					<?php } else { ?>
						<?php if ($index->Tipe == "Top Header" || $index->Tipe == "Header") { ?>
							<td class="text-right" style="background-color: #B0E0E6; color : black;vertical-align:top;"><strong><?php echo number_format($index->HrgSatuan_rap); ?></strong></td>
						<?php } elseif ($index->Tipe == "Total") { ?>
							<td class="text-right" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo number_format($index->HrgSatuan_rap); ?></strong></td>
						<?php } else { ?>
							<td class="text-right" style="background-color: #B0E0E6; color : black;vertical-align:top;"><?php echo number_format($index->HrgSatuan_rap); ?></td>
						<?php } ?>
					<?php } ?>

					<!-- ppn -->
					<?php if ($index->no_item == "TOTAL") { ?>
						<td class="text-right" style="background-color: #708090; color : white;vertical-align:top;"><strong><?php echo number_format($index->HrgSatuan_rap); ?></strong></td>
					<?php } else { ?>
						<?php if ($index->Tipe == "Top Header" || $index->Tipe == "Header") { ?>
							<td class="text-right" style="background-color: #B0E0E6; color : black;vertical-align:top;"><strong><?php echo number_format($index->HrgSatuan_rap); ?></strong></td>
						<?php } elseif ($index->Tipe == "Total") { ?>
							<td class="text-right" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo number_format($index->HrgSatuan_rap); ?></strong></td>
						<?php } else { ?>
							<td class="text-right" style="background-color: #B0E0E6; color : black;vertical-align:top;"><?php echo number_format($index->HrgSatuan_rap); ?></td>
						<?php } ?>
					<?php } ?>
					<!-- ppn -->

					<?php if ($index->no_item == "TOTAL") { ?>
						<td class="text-right" style="background-color: #708090; color : white;vertical-align:top;"><strong><?php echo number_format($index->jumlah_rap); ?></strong></td>
					<?php } else { ?>
						<?php if ($index->Tipe == "Top Header" || $index->Tipe == "Header") { ?>
							<td class="text-right" style="background-color: #B0E0E6; color : black;vertical-align:top;"><strong><?php echo number_format($index->jumlah_rap); ?></strong></td>
						<?php } elseif ($index->Tipe == "Total") { ?>
							<td class="text-right" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo number_format($index->jumlah_rap); ?></strong></td>
						<?php } else { ?>
							<td class="text-right" style="background-color: #B0E0E6; color : black;vertical-align:top;"><?php echo number_format($index->jumlah_rap); ?></td>
						<?php } ?>
					<?php } ?>

					<?php if ($index->no_item == "TOTAL") { ?>
						<td class="text-right" style="background-color: #708090; color : white;vertical-align:top;"><strong><?php echo number_format($index->bobot_rap); ?></strong></td>
					<?php } else { ?>
						<?php if ($index->Tipe == "Top Header" || $index->Tipe == "Header") { ?>
							<td class="text-right" style="background-color: #B0E0E6; color : black;vertical-align:top;"><strong><?php echo number_format($index->bobot_rap); ?></strong></td>
						<?php } elseif ($index->Tipe == "Total") { ?>
							<td class="text-right" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo number_format($index->bobot_rap); ?></strong></td>
						<?php } else { ?>
							<td class="text-right" style="background-color: #B0E0E6; color : black;vertical-align:top;"><?php echo number_format($index->bobot_rap); ?></td>
						<?php } ?>
					<?php } ?>

					<?php if ($index->no_item == "TOTAL") { ?>
						<td class="text-right" style="background-color: #708090; color : white;vertical-align:top;"><strong><?php echo number_format($index->HrgRAB_rab); ?></strong></td>
					<?php } else { ?>
						<?php if ($index->Tipe == "Top Header" || $index->Tipe == "Header") { ?>
							<td class="text-right" style="background-color: #98FB98; color : black;vertical-align:top;"><strong><?php echo number_format($index->HrgRAB_rab); ?></strong></td>
						<?php } elseif ($index->Tipe == "Total") { ?>
							<td class="text-right" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo number_format($index->HrgRAB_rab); ?></strong></td>
						<?php } else { ?>
							<td class="text-right" style="background-color: #98FB98; color : black;vertical-align:top;"><?php echo number_format($index->HrgRAB_rab); ?></td>
						<?php } ?>
					<?php } ?>

					<?php if ($index->no_item == "TOTAL") { ?>
						<td class="text-right" style="background-color: #708090; color : white;vertical-align:top;"><strong><?php echo number_format($index->jumlah_rab); ?></strong></td>
					<?php } else { ?>
						<?php if ($index->Tipe == "Top Header" || $index->Tipe == "Header") { ?>
							<td class="text-right" style="background-color: #98FB98; color : black;vertical-align:top;"><strong><?php echo number_format($index->jumlah_rab); ?></strong></td>
						<?php } elseif ($index->Tipe == "Total") { ?>
							<td class="text-right" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo number_format($index->jumlah_rab); ?></strong></td>
						<?php } else { ?>
							<td class="text-right" style="background-color: #98FB98; color : black;vertical-align:top;"><?php echo number_format($index->jumlah_rab); ?></td>
						<?php } ?>
					<?php } ?>

					<?php if ($index->no_item == "TOTAL") { ?>
						<td class="text-right" style="background-color: #708090; color : white;vertical-align:top;"><strong><?php echo number_format($index->bobot_rab); ?></strong></td>
					<?php } else { ?>
						<?php if ($index->Tipe == "Top Header" || $index->Tipe == "Header") { ?>
							<td class="text-right" style="background-color: #98FB98; color : black;vertical-align:top;"><strong><?php echo number_format($index->bobot_rab); ?></strong></td>
						<?php } elseif ($index->Tipe == "Total") { ?>
							<td class="text-right" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo number_format($index->bobot_rab); ?></strong></td>
						<?php } else { ?>
							<td class="text-right" style="background-color: #98FB98; color : black;vertical-align:top;"><?php echo number_format($index->bobot_rab); ?></td>
						<?php } ?>
					<?php } ?>

					<?php if ($index->no_item == "TOTAL") { ?>
						<td class="text-right" style="background-color: #708090; color : white;vertical-align:top;"><strong><?php echo number_format($index->rab_rap); ?></strong></td>
					<?php } else { ?>
						<?php if ($index->Tipe == "Top Header" || $index->Tipe == "Header") { ?>
							<td class="text-right" style="background-color: #DCDCDC; color : black;vertical-align:top;"><strong><?php echo number_format($index->rab_rap); ?></strong></td>
						<?php } elseif ($index->Tipe == "Total") { ?>
							<td class="text-right" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo number_format($index->rab_rap); ?></strong></td>
						<?php } else { ?>
							<td class="text-right" style="background-color: #DCDCDC; color : black;vertical-align:top;"><?php echo number_format($index->rab_rap); ?></td>
						<?php } ?>
					<?php } ?>

				<?php
			endforeach;
				?>

		</tbody>
	</table>
</div>

<script>
	$(document).ready(function() {
		$('#tambah-rap').removeAttr('disabled').text('Tambah Data');
		$('#view-rap').removeAttr('disabled').text('VIEW');

		$('#tambah-rap').click(function() {
			var JobNo = $(this).attr('data-JobNo');
			var alokasi = $(this).attr('data-alokasi');
			var Versi_rap = $(this).attr('data-versi');


			$('#my-modal-title').text('Form Tambah RAP');
			$('#my-modal').modal('show');
			$.ajax({
					url: '<?= site_url('Rap/getForm') ?>',
					type: 'POST',
					dataType: 'HTML',
					data: {
						"type": "tambah",
						"JobNo": JobNo,
						"alokasi": alokasi,
						"Versi_rap": Versi_rap
					},
					beforeSend: function() {
						$(this).attr('disabled', 'disabled').text('Loading...');
					}
				})
				.done(function(response) {
					$(this).removeAttr('disabled').text('Tambah Data');
					$('#my-modal-body').html(response);
				})
				.fail(function() {
					// console.log("error");
				})
				.always(function() {
					// console.log("complete");
				});

		})

		$('#tambah-data').click(function() {
			var JobNo = $(this).attr('data-JobNo');
			var alokasi = $(this).attr('data-alokasi');
			var Versi_rap = $(this).attr('data-versi');

			$('#my-modal-title').text('Form Tambah RAP');
			$('#my-modal').modal('show');
			$.ajax({
					url: '<?= site_url('Rap/getForm') ?>',
					type: 'POST',
					dataType: 'HTML',
					data: {
						"type": "tambah",
						"JobNo": JobNo,
						"alokasi": alokasi,
						"Versi_rap": Versi_rap
					},
					beforeSend: function() {
						$('.my-loading').show();
					}
				})
				.done(function(response) {
					$('.my-loading').hide();
					$('#my-modal-body').html(response);
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});


		})

		$('#view-rap').click(function() {
			var JobNo = $(this).attr('data-JobNo-rap');
			var alokasi_rap = $(this).attr('data-alokasi-rap');
			var versi_rap = $(this).attr('data-versi-rap');

			getTableRap(JobNo, alokasi_rap, versi_rap)
		})

		function getTableRap(JobNo, alokasi_rap, versi_rap) {
			if (alokasi_rap == '') {
				return false;
			}

			$.ajax({
					url: '<?= site_url('Rap/getTableRap') ?>',
					type: 'POST',
					dataType: 'HTML',
					data: {
						"JobNo": JobNo,
						"alokasi_rap": alokasi_rap,
						"versi_rap": versi_rap
					},
					beforeSend: function() {
						$('#my-loading-rap').show();
					}
				})
				.done(function(response) {
					$('#my-loading-rap').hide();
					$('#tampil-rap').html(response);

				})
				.fail(function() {
					// console.log("error");
				})
				.always(function() {
					// console.log("complete");
				});
		}


	})
</script>
