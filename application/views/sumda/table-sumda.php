<a href="javascript:void(0)" id="tambah-sumda" data-JobNo-sumda="<?= $JobNo ?>" data-alokasi-sumda="<?= $alokasi_sumda ?>" data-versi-sumda="<?= $versi_sumda ?>" class="btn bg-purple btn-flat btn-sm tambah-rap">CREATE</a>
<a href="javascript:void(1)" id="view-sumda" data-JobNo-sumda="<?= $JobNo ?>" data-alokasi-sumda="<?= $alokasi_sumda ?>" data-versi-sumda="<?= $versi_sumda ?>" class="btn bg-purple btn-flat btn-sm view-rap">VIEW</a>

<!-- <td><?php echo $no++ ?></td> -->

<div class="table-responsive" style="padding-top:10px;">
    <table class="table table-bordered table-striped table-hover table-condensed" id="mytablesumda">
        <thead>
			<tr>
				<th class="text-center" style="background-color: #708090; color : white;vertical-align:middle;" rowspan="2">Kode RAP</th>
				<th class="text-center" style="background-color: #708090; color : white;vertical-align:middle;" rowspan="2">Uraian</th>
				<th class="text-center" style="background-color: #708090; color : white;vertical-align:middle;" rowspan="2">Sat</th>
				<th class="text-center" style="background-color: #708090; color : white;vertical-align:middle;" rowspan="2">Vol</th>
				<th class="text-center" style="background-color: #00BFFF; color : black;vertical-align:middle;" colspan="3">RAP</th>
				<th class="text-center" style="background-color: #7CFC00; color : black;vertical-align:middle;" colspan="3">RAB</th>
				<th class="text-center" style="background-color: #708090; color : white;vertical-align:middle;"rowspan="2">RAP/RAB (%)</th>
            </tr>	
            <tr>
				<th class="text-center" style="background-color: #00BFFF; color : black;vertical-align:middle;">Harga Satuan (Rp)</th>
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
						<?php if ($index->no_item == "TOTAL"){ ?>
							<td class="text-center" style="background-color: #708090; color : white;vertical-align:top;" colspan="4"><strong><?php echo $index->no_item ?></strong></td>  
						<?php }else{ ?>
							<?php if ($index->Tipe == "Header"){ ?>
								<td class="text-left" style="background-color: #DCDCDC; color : black;vertical-align:top;"><strong><?php echo $index->no_item ?></strong></td>  
							<?php }elseif ($index->Tipe == "Detail"){ ?>
								<td class="text-right" style="background-color: #DCDCDC; color : black;vertical-align:top;"><strong><?php echo $index->no_item ?></strong></td>  
							<?php }elseif ($index->Tipe == "Total"){ ?>
								<td class="text-right" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo $index->no_item ?></strong></td>  
							<?php }else{ ?>
								<td style="background-color: #DCDCDC; color : black;vertical-align:top;"></td>  
							<?php } ?>
						<?php } ?>

						<?php if ($index->no_item != "TOTAL"){ ?>
							<?php if ($index->Tipe == "Header" || $index->Tipe == "Detail"){ ?>
								<td class="text-left" style="background-color: #DCDCDC; color : black;vertical-align:top;"><strong><?php echo $index->item_kegiatan ?></strong></td>  
							<?php }elseif ($index->Tipe == "Sumda"){ ?>
								<td class="text-left" style="background-color: #DCDCDC; color : black;vertical-align:top;"><?php echo $index->no_item.' - '.$index->item_kegiatan ?></td>
							<?php }elseif ($index->Tipe == "Total"){ ?>
								<td class="text-left" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo $index->item_kegiatan ?></strong></td>							  
							<?php }else{ ?>
								<td style="padding-left: 30px; background-color: #DCDCDC; color : black;vertical-align:top;"><?php echo $index->no_item.' - '.$index->item_kegiatan ?></td>  
							<?php } ?>
						<?php } ?>

						<?php if ($index->no_item != "TOTAL"){ ?>
							<?php if ($index->Tipe == "Header" || $index->Tipe == "Detail"){ ?>
								<td style="background-color: #DCDCDC; color : black;vertical-align:top;"><strong><?php echo $index->uom; ?></strong></td>
							<?php }elseif ($index->Tipe == "Total"){ ?>
								<td style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo $index->uom ?></strong></td>							  
							<?php }else{ ?>
								<td style="background-color: #DCDCDC; color : black;vertical-align:top;"><?php echo $index->uom; ?></td>
							<?php } ?>
						<?php } ?>

						<?php if ($index->no_item != "TOTAL"){ ?>
							<?php if ($index->Tipe == "Header" || $index->Tipe == "Detail"){ ?>
								<td class="text-right" style="background-color: #DCDCDC; color : black;vertical-align:top;"><strong><?php echo number_format($index->vol); ?></strong></td>
							<?php }elseif ($index->Tipe == "Total"){ ?>
								<td class="text-right" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo number_format($index->vol); ?></strong></td>		  
							<?php }else{ ?>
								<td class="text-right" style="background-color: #DCDCDC; color : black;vertical-align:top;"><?php echo number_format($index->vol); ?></td>
							<?php } ?>
						<?php } ?>

						<?php if ($index->no_item == "TOTAL"){ ?>
							<td class="text-right" style="background-color: #708090; color : white;vertical-align:top;"><strong><?php echo number_format($index->HrgSatuan_rap); ?></strong></td>  
						<?php }else{ ?>
							<?php if ($index->Tipe == "Header" || $index->Tipe == "Detail"){ ?>
								<td class="text-right" style="background-color: #B0E0E6; color : black;vertical-align:top;"><strong><?php echo number_format($index->HrgSatuan_rap); ?></strong></td>
							<?php }elseif ($index->Tipe == "Total"){ ?>
								<td class="text-right" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo number_format($index->HrgSatuan_rap); ?></strong></td>		  
							<?php }else{ ?>
								<td class="text-right" style="background-color: #B0E0E6; color : black;vertical-align:top;"><?php echo number_format($index->HrgSatuan_rap); ?></td>
							<?php } ?>
						<?php } ?>

						<?php if ($index->no_item == "TOTAL"){ ?>
							<td class="text-right" style="background-color: #708090; color : white;vertical-align:top;"><strong><?php echo number_format($index->jumlah_rap); ?></strong></td>  
						<?php }else{ ?>
							<?php if ($index->Tipe == "Header" || $index->Tipe == "Detail"){ ?>
								<td class="text-right" style="background-color: #B0E0E6; color : black;vertical-align:top;"><strong><?php echo number_format($index->jumlah_rap); ?></strong></td>
							<?php }elseif ($index->Tipe == "Total"){ ?>
								<td class="text-right" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo number_format($index->jumlah_rap); ?></strong></td>
							<?php }else{ ?>
								<td class="text-right" style="background-color: #B0E0E6; color : black;vertical-align:top;"><?php echo number_format($index->jumlah_rap); ?></td>
							<?php } ?>
						<?php } ?>

						<?php if ($index->no_item == "TOTAL"){ ?>
							<td class="text-right" style="background-color: #708090; color : white;vertical-align:top;"><strong><?php echo number_format($index->bobot_rap); ?></strong></td>  
						<?php }else{ ?>
							<?php if ($index->Tipe == "Header" || $index->Tipe == "Detail"){ ?>
								<td class="text-right" style="background-color: #B0E0E6; color : black;vertical-align:top;"><strong><?php echo number_format($index->bobot_rap); ?></strong></td>
							<?php }elseif ($index->Tipe == "Total"){ ?>
								<td class="text-right" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo number_format($index->bobot_rap); ?></strong></td>
							<?php }else{ ?>
								<td class="text-right" style="background-color: #B0E0E6; color : black;vertical-align:top;"><?php echo number_format($index->bobot_rap); ?></td>
							<?php } ?>
						<?php } ?>

						<?php if ($index->no_item == "TOTAL"){ ?>
							<td class="text-right" style="background-color: #708090; color : white;vertical-align:top;"><strong><?php echo number_format($index->HrgRAB_rab); ?></strong></td>  
						<?php }else{ ?>
							<?php if ($index->Tipe == "Header" || $index->Tipe == "Detail"){ ?>
								<td class="text-right" style="background-color: #98FB98; color : black;vertical-align:top;"><strong><?php echo number_format($index->HrgRAB_rab); ?></strong></td>
							<?php }elseif ($index->Tipe == "Total"){ ?>
								<td class="text-right" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo number_format($index->HrgRAB_rab); ?></strong></td>
							<?php }else{ ?>
								<td class="text-right" style="background-color: #98FB98; color : black;vertical-align:top;"><?php echo number_format($index->HrgRAB_rab); ?></td>
							<?php } ?>
						<?php } ?>

						<?php if ($index->no_item == "TOTAL"){ ?>
							<td class="text-right" style="background-color: #708090; color : white;vertical-align:top;"><strong><?php echo number_format($index->jumlah_rab); ?></strong></td>  
						<?php }else{ ?>
							<?php if ($index->Tipe == "Header" || $index->Tipe == "Detail"){ ?>
								<td class="text-right" style="background-color: #98FB98; color : black;vertical-align:top;"><strong><?php echo number_format($index->jumlah_rab); ?></strong></td>
							<?php }elseif ($index->Tipe == "Total"){ ?>
								<td class="text-right" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo number_format($index->jumlah_rab); ?></strong></td>
							<?php }else{ ?>
								<td class="text-right" style="background-color: #98FB98; color : black;vertical-align:top;"><?php echo number_format($index->jumlah_rab); ?></td>
							<?php } ?>
						<?php } ?>

						<?php if ($index->no_item == "TOTAL"){ ?>
							<td class="text-right" style="background-color: #708090; color : white;vertical-align:top;"><strong><?php echo number_format($index->bobot_rab); ?></strong></td>  
						<?php }else{ ?>
							<?php if ($index->Tipe == "Header" || $index->Tipe == "Detail"){ ?>
								<td class="text-right" style="background-color: #98FB98; color : black;vertical-align:top;"><strong><?php echo number_format($index->bobot_rab); ?></strong></td>
							<?php }elseif ($index->Tipe == "Total"){ ?>
								<td class="text-right" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo number_format($index->bobot_rab); ?></strong></td>
							<?php }else{ ?>
								<td class="text-right" style="background-color: #98FB98; color : black;vertical-align:top;"><?php echo number_format($index->bobot_rab); ?></td>
							<?php } ?>
						<?php } ?>

						<?php if ($index->no_item == "TOTAL"){ ?>
							<td class="text-right" style="background-color: #708090; color : white;vertical-align:top;"><strong><?php echo number_format($index->rab_rap); ?></strong></td>  
						<?php }else{ ?>
							<?php if ($index->Tipe == "Header" || $index->Tipe == "Detail"){ ?>
								<td class="text-right" style="background-color: #DCDCDC; color : black;vertical-align:top;"><strong><?php echo number_format($index->rab_rap); ?></strong></td>
							<?php }elseif ($index->Tipe == "Total"){ ?>
								<td class="text-right" style="background-color: #708090; color : black;vertical-align:top;"><strong><?php echo number_format($index->rab_rap); ?></strong></td>
							<?php }else{ ?>
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
        $('#tambah-sumda').removeAttr('disabled').text('CREATE');
        $('#view-sumda').removeAttr('disabled').text('VIEW');

        $('#tambah-sumda').click(function() {
            var JobNo = $(this).attr('data-JobNo-sumda');
            var alokasi_sumda = $(this).attr('data-alokasi-sumda');
            var versi_sumda = $(this).attr('data-versi-sumda');

            $('#my-modal-title-sumda').text('Form Create SUMDA');
            $('#my-modal-sumda').modal('show');
            $.ajax({
                    url: '<?= site_url('Sumda/getFormSumda') ?>',
                    type: 'POST',
                    dataType: 'HTML',
                    data: {
                        "type": "tambah",
                        "JobNo": JobNo,
                        "alokasi_sumda": alokasi_sumda,
                        "versi_sumda": versi_sumda
                    },
                    beforeSend: function() {
                        $(this).attr('disabled', 'disabled').text('Loading...');
                    }
                })
                .done(function(response) {
                    $(this).removeAttr('disabled').text('Tambah Data');
                    $('#my-modal-body-sumda').html(response);
                })
                .fail(function() {
                    // console.log("error");
                })
                .always(function() {
                    // console.log("complete");
                });

        })

        $('#view-sumda').click(function() {
            var JobNo = $(this).attr('data-JobNo-sumda');
            var alokasi_sumda = $(this).attr('data-alokasi-sumda');
            var versi_sumda = $(this).attr('data-versi-sumda');

			getTableSumda(JobNo, alokasi_sumda, versi_sumda)			
        })

		function getTableSumda(JobNo, alokasi_sumda, versi_sumda) {
			if (alokasi_sumda == '') {
				return false;
			}

			$.ajax({
					url: '<?= site_url('Sumda/getTableSumda') ?>',
					type: 'POST',
					dataType: 'HTML',
					data: {
						"JobNo": JobNo,
						"alokasi_sumda": alokasi_sumda,
						"versi_sumda": versi_sumda
					},
					beforeSend: function() {
						$('#my-loading-sumda').show();
					}
				})
				.done(function(response) {
					$('#my-loading-sumda').hide();
					$('#tampil-sumda').html(response);

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
