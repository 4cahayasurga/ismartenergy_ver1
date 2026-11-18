<style type="text/css">
	#table_QHutangKO {
		font-size: 12px;
		font-family: Arial, Helvetica, sans-serif;
	}

	#table_QHutangKO thead tr th {
		padding-top: 10px;
		padding-bottom: 10px;
		text-align: center;
		white-space: nowrap;
		border-bottom: 1px solid black;

	}

	#table_QHutangKO tbody tr td {
		padding-top: 10px;
		padding-bottom: 10px;
		text-align: center;
		white-space: nowrap;

	}

	#table_QHutangKO tfoot tr th {
		padding-top: 10px;
		padding-bottom: 10px;
		padding-left: 5px;
		border-top: 1px solid black;
		white-space: nowrap;

	}
</style>
<div class="col-12 ">

	<div class="box box-primary">

		<div class="box-header">

			<button class="btn btn-md btn-danger pdf_QHutangKO" data-JobNo="<?= $JobNo ?>" data-VendorId="<?= $VendorId ?>" data-Status="<?= $Status ?>" data-Url="<?= site_url('Pdf_master/Query_HutangKO_PDF/' . $JobNo . '/' . $VendorId . '/' . $Status) ?>">Print PDF <i class="fa fa-print"></i></button>

		</div>

		<div class="box-body">

			<div class="table-responsive">

				<table id="table_QHutangKO" class="table table-striped table-hover table-condensed" border="0" width="100%">

					<thead>
						<tr>
							<th>#</th>
							<th>Job. No</th>
							<th>Job Name</th>
							<th>No. KO</th>
							<th>Tgl KO</th>
							<th>Tipe KO</th>
							<th>Vendor</th>
							<th>Amount KO (Rp)</th>
							<th>Payment KO (Rp)</th>
							<th>Remaining KO (Rp)</th>
							<th>Amount Invoice (Rp)</th>
							<th>Payment Invoice (Rp)</th>
							<th>Remaining Invoice (Rp)</th>

						</tr>
						<!-- <tr><td colspan="13" style="border:1px solid black;">ss</td></tr> -->
					</thead>

					<tbody>
						<?php
						$TotalAmountKO = 0;
						$TotalPaymentKO = 0;
						$TotalRemainingKO = 0;


						$TotalAmountInv = 0;
						$TotalPaymentInv = 0;
						$TotalRemainingInv = 0;

						foreach ($dataKO as $dataKO) :
							$TotalAmountKO += $dataKO['TotalKO'];
							$TotalPaymentKO += $dataKO['PaymentKO'];
							$TotalRemainingKO += $dataKO['RemainingKO'];

							$TotalAmountInv += $dataKO['TotalInv'];
							$TotalPaymentInv += $dataKO['PaymentInv'];
							$TotalRemainingInv += $dataKO['RemainingInv'];

						?>


							<tr data-toggle="collapse" data-target="#demo1<?= $dataKO['LedgerNo'] ?>" class="accordion-toggle">
								<td>
									<button class="btn btn-default btn-xs"><span class="fa fa-plus-circle"></span></button>
								</td>
								<td style="text-align:left;"><?= $dataKO['JobNo'] ?></td>
								<td style="text-align:left;"><?= $dataKO['JobNm'] ?></td>
								<td style="text-align:left;"><?= $dataKO['NoKO'] ?></td>
								<td style="text-align:left;"><?= TanggalSaya($dataKO['TglKO']) ?></td>
								<td style="text-align:left;"><?= $dataKO['KategoriId'] ?></td>
								<td style="text-align:left;"><?= $dataKO['VendorNm'] ?></td>
								<td style="text-align:right;"><?= new_decimal($dataKO['TotalKO']) ?></td>
								<td style="text-align:right;"><?= new_decimal($dataKO['PaymentKO']) ?></td>
								<td style="text-align:right;"><?= new_decimal($dataKO['RemainingKO']) ?></td>
								<td style="text-align:right;"><?= new_decimal($dataKO['TotalInv']) ?></td>
								<td style="text-align:right;"><?= new_decimal($dataKO['PaymentInv']) ?></td>
								<td style="text-align:right;"><?= new_decimal($dataKO['RemainingInv']) ?></td>
							</tr>

							<!-- <tr>
								<td colspan="13" style="border:1px solid black;">
									
									<table border="1" width="100%">
										<tr>
											<td>kkkkkkkkkkkkkkkkkkkk</td>
										</tr>
									</table>

								</td>
							</tr> -->

							<!-- <tr>
								<td colspan="12" class="hiddenRow">
									<div class="accordian-body collapse" id="demo1<?= $dataKO['LedgerNo'] ?>">
										<div class="nav-tabs-custom">
											<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
												<li class="nav-item">
													<a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#UraianPD<?= $dataKO['LedgerNo'] ?>" role="tab" aria-controls="UraianPD" aria-selected="true">Uraian PD</a>
												</li>
											</ul>
											<div class="tab-content" id="pills-tabContent">
												<div class="tab-pane fade show active" id="UraianPD<?= $dataKO['LedgerNo'] ?>" role="tabpanel" aria-labelledby="pills-home-tab">

													<table id="TblQueryUraianPD" class="table table-bordered table-striped table-hover table-condensed">
														<
													</table>
												</div>

												

												
											</div>
										</div>
									</div>
								</td>
							</tr> -->

						<?php endforeach; ?>

					</tbody>

					<!-- <tfoot>

						<tr>
							<th colspan="7"></th>
							<th style="text-align:right;"><?= "Total = " . new_decimal($TotalAmountKO) ?></th>
							<th style="text-align:right;"><?= "Total = " . new_decimal($TotalPaymentKO) ?></th>
							<th style="text-align:right;"><?= "Total = " . new_decimal($TotalRemainingKO) ?></th>

							<th style="text-align:right;"><?= "Total = " . new_decimal($TotalAmountInv) ?></th>
							<th style="text-align:right;"><?= "Total = " . new_decimal($TotalPaymentInv) ?></th>
							<th style="text-align:right;"><?= "Total = " . new_decimal($TotalRemainingInv) ?></th>
						</tr>
						
					</tfoot> -->

				</table>

			</div>


		</div>

		<div class="overlay dark loading_table" style="display: none;">
			<i class="fa fa-refresh fa-spin"></i>
		</div>

	</div>

</div>

<script>
	$(document).ready(function() {

		$('#table_QHutangKO').DataTable({
			'ordering': false
		});

		$('.pdf_QHutangKO').click(function() {

			var JobNo = $(this).attr('data-JobNo');
			var VendorId = $(this).attr('data-VendorId');
			var Status = $(this).attr('data-Status');
			var Url = $(this).attr('data-Url');

			window.open(Url, '_blank');

		});





	})
</script>