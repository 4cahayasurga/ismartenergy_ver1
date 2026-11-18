<style>
	.mykonten {
		font-family: Arial, Helvetica, sans-serif !important;
		font-size: x-small !important;
	}

	.ukuran_select2 {
		font-size: 10px !important;
		font-family: Arial, Helvetica, sans-serif;

	}
	.select2-results__option { 
		font-family: Arial, Helvetica, sans-serif !important;
		font-size: x-small !important;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h5 style="font-weight:bold;"><?=$judul?></h5>

		<ol class="breadcrumb">
			<li><a href="<?= site_url('dashboard')  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?=$judul?></li>
		</ol>
	</section>

	<section class="content-header">

		<button class="btn btn-xs" style="background-color:#7f8c8d;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>


	</section>

	<section class="content">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

		<div class="box box-warning">

			<div class="box-header with-border">
				<a href="<?=site_url('MyReport/Operasi/PiutangProgressFisik_report')?>" class="btn btn-info btn-sm text-white">Refresh Halaman <i class="fa fa-refresh"></i></a>
				
				<a href="<?=site_url('MyReport/Operasi/PiutangProgressFisik_report/myPDF_Piutang')?>" class="btn btn-danger btn-sm text-white" target="_blank">Print PDF <i class="fa fa-print"></i></a>


				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
				</div>
			</div>
			<div class="box-body">

				<div class="row">
					<div class="col-sm-12" style="font-size:12px;">


						<div class="table-responsive">

							<table width="100%" border="0" style="border-collapse:collapse;" >
								
								<thead>
									<tr style="border-bottom:0px solid silver;">
										<th colspan="2" ></th>
										<th colspan="3" style="text-align: center;padding-top: 5px;padding-bottom: 5px;border-bottom:1px solid silver;">RPPM</th>
										<th colspan="2" style="text-align: center;padding-top: 5px;padding-bottom: 5px;border-bottom:1px solid silver;">Termin</th>
										<th colspan="2" style="text-align: center;padding-top: 5px;padding-bottom: 5px;border-bottom:1px solid silver;">Sisa Yg Harus DiTagihkan</th>
									</tr>

									<tr style="border-bottom:1px solid silver;">
										<th rowspan="2" style="padding-top: 5px;padding-bottom: 5px;vertical-align: middle;padding-left: 5px;">JobNo</th>
										<th rowspan="2" style="padding-top: 5px;padding-bottom: 5px;vertical-align: middle;padding-left: 5px;">Job Name</th>
										<th style="padding-top: 5px;padding-bottom: 5px;vertical-align: middle;padding-left: 5px;border-left:0px solid black;">Tgl RPPM</th>
										<th style="padding-top: 5px;padding-bottom: 5px;vertical-align: middle;padding-left: 5px;">%</th>
										<th style="padding-top: 5px;padding-bottom: 5px;vertical-align: middle;padding-left: 5px;">Rp</th>
										<th style="padding-top: 5px;padding-bottom: 5px;vertical-align: middle;padding-left: 5px;border-left:0px solid black;">%</th>
										<th style="padding-top: 5px;padding-bottom: 5px;vertical-align: middle;padding-left: 5px;">Rp</th>
										<th style="padding-top: 5px;padding-bottom: 5px;vertical-align: middle;padding-left: 5px;border-left:0px solid black;">%</th>
										<th style="padding-top: 5px;padding-bottom: 5px;vertical-align: middle;padding-left: 5px;">Rp</th>
									</tr>
								</thead>

								<tbody>
									<?php
									$SUM_RpRPPM = 0;
									$SUM_RpTermin = 0;
									$SUM_RpSisa = 0;

									?>

									<?php if ($dataPrimary == 1): ?>
										
										<tr style="border-bottom:1px solid black;background-color: #95a5a6;color:white;">
											<td style="border:0px solid black;padding-top:5px;padding-bottom:5px;" colspan="9">
												Kategori : Primary
											</td>
										</tr>

										<?php foreach($queryPrimary as $myPrimary) : ?> 

											<?php
											$SUM_RpRPPM += $myPrimary['RpRPPM'];
											$SUM_RpTermin += $myPrimary['RpTermin'];
											$SUM_RpSisa += $myPrimary['RpSisa'];

											?>

											<tr >
												<td style="padding-top:5px;padding-bottom:5px;padding-left:30px;"><?=$myPrimary['JobNo']?></td>
												<td><?=$myPrimary['JobNm']?></td>
												<td style="border-left:0px solid black;"><?=($myPrimary['dateRPPM'] == NULL) ? '' : tgl_baru_tahun_pendek($myPrimary['dateRPPM'])?></td>

												<td style="border-left:0px solid black;text-align: right;"><?=($myPrimary['PersenRPPM'] == NULL) ? '' :new_decimal($myPrimary['PersenRPPM'])?></td>

												<td style="text-align: right;"><?=($myPrimary['RpRPPM'] == NULL) ? '' :uang_koma_genap_decimal($myPrimary['RpRPPM'])?></td>

												<td style="text-align: right;"><?=($myPrimary['PersenTermin'] == NULL) ? '' :new_decimal($myPrimary['PersenTermin'])?></td>

												<td style="text-align: right;"><?=($myPrimary['RpTermin'] == NULL) ? '' :uang_koma_genap_decimal($myPrimary['RpTermin'])?></td>

												<td style="text-align: right;"><?=($myPrimary['SisaPersen'] == NULL) ? '' :new_decimal($myPrimary['SisaPersen'])?></td>

												<td style="text-align: right;"><?=($myPrimary['RpSisa'] == NULL) ? '' :uang_koma_genap_decimal($myPrimary['RpSisa'])?></td>
											
										</tr>
									<?php endforeach; ?>

									<tr style="border-top:1px solid silver;">

										<td colspan="4" style="padding-top:10px;padding-bottom:10px;"></td>
										<td class="text-right" style="padding-top:10px;padding-bottom:10px;"><?='SUM='.uang_koma_genap_decimal($SUM_RpRPPM)?></td>
										<td class="text-right" colspan="2"  style="padding-top:10px;padding-bottom:10px;"><?='SUM='.uang_koma_genap_decimal($SUM_RpTermin)?></td>
										<td class="text-right" colspan="2" style="padding-top:10px;padding-bottom:10px;"><?='SUM='.uang_koma_genap_decimal($SUM_RpSisa)?></td>

									</tr>
									<?php 
									$SUM_RpRPPM = 0;
									$SUM_RpTermin = 0;
									$SUM_RpSisa = 0;
									?>

								<?php endif ?>	

								<!-- //batas -->


								<?php if ($dataSecondary == 1): ?>
									


									<tr style="border-bottom:1px solid black;background-color: #95a5a6;color:white;">
										<td style="border:0px solid black;padding-top:5px;padding-bottom:5px;" colspan="9">
											Kategori : Primary
										</td>
									</tr>

									<?php foreach($querySecondary as $mySecondary) : ?> 

										<?php
										$SUM_RpRPPM += $mySecondary['RpRPPM'];
										$SUM_RpTermin += $mySecondary['RpTermin'];
										$SUM_RpSisa += $mySecondary['RpSisa'];

										?>

										<tr >
											<td style="padding-top:5px;padding-bottom:5px;padding-left:30px;"><?=$mySecondary['JobNo']?></td>
											<td><?=$mySecondary['JobNm']?></td>
											<td style="border-left:0px solid black;"><?=($mySecondary['dateRPPM'] == NULL) ? '' : tgl_baru_tahun_pendek($mySecondary['dateRPPM'])?></td>

											<td style="border-left:0px solid black;text-align: right;"><?=($mySecondary['PersenRPPM'] == NULL) ? '' :new_decimal($mySecondary['PersenRPPM'])?></td>

											<td style="text-align: right;"><?=($mySecondary['RpRPPM'] == NULL) ? '' :uang_koma_genap_decimal($mySecondary['RpRPPM'])?></td>

											<td style="text-align: right;"><?=($mySecondary['PersenTermin'] == NULL) ? '' :new_decimal($mySecondary['PersenTermin'])?></td>

											<td style="text-align: right;"><?=($mySecondary['RpTermin'] == NULL) ? '' :uang_koma_genap_decimal($mySecondary['RpTermin'])?></td>

											<td style="text-align: right;"><?=($mySecondary['SisaPersen'] == NULL) ? '' :new_decimal($mySecondary['SisaPersen'])?></td>

											<td style="text-align: right;"><?=($mySecondary['RpSisa'] == NULL) ? '' :uang_koma_genap_decimal($mySecondary['RpSisa'])?></td>
										</td>
									</tr>
								<?php endforeach; ?>

								<tr style="border-top:1px solid silver;">

									<td colspan="4" style="padding-top:10px;padding-bottom:10px;"></td>
									<td class="text-right"  style="padding-top:10px;padding-bottom:10px;"><?='SUM='.uang_koma_genap_decimal($SUM_RpRPPM)?></td>
									<td class="text-right" colspan="2" style="padding-top:10px;padding-bottom:10px;"><?='SUM='.uang_koma_genap_decimal($SUM_RpTermin)?></td>
									<td class="text-right" colspan="2" style="padding-top:10px;padding-bottom:10px;"><?='SUM='.uang_koma_genap_decimal($SUM_RpSisa)?></td>

								</tr>
								<?php 
								$SUM_RpRPPM = 0;
								$SUM_RpTermin = 0;
								$SUM_RpSisa = 0;
								?>

							<?php endif; ?>


							<!-- //batas -->

							<?php if ($dataSYC == 1): ?>



								<tr style="border-bottom:1px solid black;background-color: #95a5a6;color:white;">
									<td style="border:0px solid black;padding-top:5px;padding-bottom:5px;" colspan="9">
										Kategori : SYC
									</td>
								</tr>

								<?php foreach($querySYC as $mySYC) : ?> 

									<?php
									$SUM_RpRPPM += $mySYC['RpRPPM'];
									$SUM_RpTermin += $mySYC['RpTermin'];
									$SUM_RpSisa += $mySYC['RpSisa'];

									?>

									<tr >
										<td style="padding-top:5px;padding-bottom:5px;padding-left:30px;"><?=$mySYC['JobNo']?></td>
										<td><?=$mySYC['JobNm']?></td>
										<td style="border-left:0px solid black;"><?=($mySYC['dateRPPM'] == NULL) ? '' : tgl_baru_tahun_pendek($mySYC['dateRPPM'])?></td>

										<td style="border-left:0px solid black;text-align: right;"><?=($mySYC['PersenRPPM'] == NULL) ? '' :new_decimal($mySYC['PersenRPPM'])?></td>

										<td style="text-align: right;"><?=($mySYC['RpRPPM'] == NULL) ? '' :uang_koma_genap_decimal($mySYC['RpRPPM'])?></td>

										<td style="text-align: right;"><?=($mySYC['PersenTermin'] == NULL) ? '' :new_decimal($mySYC['PersenTermin'])?></td>

										<td style="text-align: right;"><?=($mySYC['RpTermin'] == NULL) ? '' :uang_koma_genap_decimal($mySYC['RpTermin'])?></td>

										<td style="text-align: right;"><?=($mySYC['SisaPersen'] == NULL) ? '' :new_decimal($mySYC['SisaPersen'])?></td>

										<td style="text-align: right;"><?=($mySYC['RpSisa'] == NULL) ? '' :uang_koma_genap_decimal($mySYC['RpSisa'])?></td>
									</td>
								</tr>
							<?php endforeach; ?>

							<tr style="border-top:1px solid silver;">

								<td colspan="4" style="padding-top:10px;padding-bottom:10px;"></td>
								<td class="text-right" style="padding-top:10px;padding-bottom:10px;"><?='SUM='.uang_koma_genap_decimal($SUM_RpRPPM)?></td>
								<td class="text-right" colspan="2" style="padding-top:10px;padding-bottom:10px;"><?='SUM='.uang_koma_genap_decimal($SUM_RpTermin)?></td>
								<td class="text-right" colspan="2" style="padding-top:10px;padding-bottom:10px;"><?='SUM='.uang_koma_genap_decimal($SUM_RpSisa)?></td>

							</tr>
							<?php 
							$SUM_RpRPPM = 0;
							$SUM_RpTermin = 0;
							$SUM_RpSisa = 0;
							?>

						<?php endif; ?>




						<?php if ($dataKategoriNull == 1): ?>
							<tr style="border-top:1px solid black;background-color: #95a5a6;color:white;">
								<td style="border:0px solid black;padding-top:5px;padding-bottom:5px;" colspan="9">
									Kategori : 
								</td>
							</tr>

							<?php foreach($queryKategoriNull as $myKategoriNull) : ?> 
								<?php
								$SUM_RpRPPM += $myKategoriNull['RpRPPM'];
								$SUM_RpTermin += $myKategoriNull['RpTermin'];
								$SUM_RpSisa += $myKategoriNull['RpSisa'];

								?>
								<tr>
									<td style="padding-top:5px;padding-bottom:5px;"><?=$myKategoriNull['JobNo']?></td>
									<td><?=$myKategoriNull['JobNm']?></td>
									<td style="border-left:0px solid black;"><?=($myKategoriNull['dateRPPM'] == NULL) ? '' : tgl_baru_tahun_pendek($myKategoriNull['dateRPPM'])?></td>

									<td style="border-left:0px solid black;text-align: right;"><?=($myKategoriNull['PersenRPPM'] == NULL) ? '' :new_decimal($myKategoriNull['PersenRPPM'])?></td>

									<td style="text-align: right;"><?=($myKategoriNull['RpRPPM'] == NULL) ? '' :uang_koma_genap_decimal($myKategoriNull['RpRPPM'])?></td>

									<td style="text-align: right;"><?=($myKategoriNull['PersenTermin'] == NULL) ? '' :new_decimal($myKategoriNull['PersenTermin'])?></td>

									<td style="text-align: right;"><?=($myKategoriNull['RpTermin'] == NULL) ? '' :uang_koma_genap_decimal($myKategoriNull['RpTermin'])?></td>

									<td style="text-align: right;"><?=($myKategoriNull['SisaPersen'] == NULL) ? '' :new_decimal($myKategoriNull['SisaPersen'])?></td>

									<td style="text-align: right;"><?=($myKategoriNull['RpSisa'] == NULL) ? '' :uang_koma_genap_decimal($myKategoriNull['RpSisa'])?></td>
								</td>
							</tr>
						<?php endforeach; ?>
						<tr style="border-top:1px solid silver;">

							<td colspan="4" style="padding-top:10px;padding-bottom:10px;"></td>
							<td class="text-right" style="padding-top:10px;padding-bottom:10px;"><?='SUM= '.uang_koma_genap_decimal($SUM_RpRPPM)?></td>
							<td class="text-right" colspan="2" style="padding-top:10px;padding-bottom:10px;" ><?='SUM= '.uang_koma_genap_decimal($SUM_RpTermin)?></td>
							<td class="text-right" colspan="2" style="padding-top:10px;padding-bottom:10px;" ><?='SUM= '.uang_koma_genap_decimal($SUM_RpSisa)?></td>

						</tr>
					<?php endif ?>

				</tbody>

				<tfoot>
					<tr style="border-top:1px solid silver;font-weight: bold;">

						<td colspan="4" style="padding-top:10px;padding-bottom:10px;">SUBTOTAL</td>
						<td class="text-right" style="padding-top:10px;padding-bottom:10px;"><?='SUM = '.uang_koma_genap_decimal($SUBTOTAL_RpRPPM)?></td>
						<td class="text-right" colspan="2" style="padding-top:10px;padding-bottom:10px;" ><?='SUM = '.uang_koma_genap_decimal($SUBTOTAL_RpTermin)?></td>
						<td class="text-right" colspan="2" style="padding-top:10px;padding-bottom:10px;" ><?='SUM = '.uang_koma_genap_decimal($SUBTOTAL_RpSisa)?></td>

					</tr>
				</tfoot>







			</table>
		</div>


	</div>

</div>






</div>
<div class="overlay loadingku" style="display: none;">
	<i class="fa fa-refresh fa-spin"></i>
</div>


</div>

<div class="tampil_table"></div>


</section>


</div>


<script>

	$(document).ready(function() {

		$('#JobNo').select2({
			'theme': 'classic',
			'width': '100%',
		});



		

	});


</script>