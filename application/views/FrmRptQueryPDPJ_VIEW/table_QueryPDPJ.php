<style type="text/css">
	.ukuran {
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
		/*white-space: nowrap;*/
	}

	.buat_nowrap {
		white-space: nowrap;
	}

	.jarak_atas_bawah {
		padding-top: 7px !important;
		padding-bottom: 7px !important;
	}

	.jarak_atas_bawah_td {
		padding-top: 5px !important;
		padding-bottom: 5px !important;
	}


	.font10 {
		font-size: 13px !important;
		/*		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;*/
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	.font12 {
		font-size: 12px !important;
		/*		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;*/
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	.kanan {
		text-align: right;
	}

	.kiri {
		text-align: left;
	}

	.tengah {
		text-align: center;
	}


	tr input {
		background-color: #fff;
		border-radius: 5px;
		outline: none;
		padding: 5px;
		border: none;
		font-size: 12px;
		font-weight: 500;
	}

	.cont-seacrh {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 10px;
	}

	.cont-seacrh span {
		width: 20px;
		height: 20px;
		border-radius: 10%;
		background-color: #454444;
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 5px;
	}

	.cont-seacrh span:hover {
		cursor: pointer;
	}

	.cont-seacrh span i {
		color: #fff;
		font-size: 12px;
	}

	.modal {
		display: none;
		position: absolute;
		border: 1px solid #ccc;
		padding: 10px;
		background-color: #fff;
		z-index: 1002;
	}

	#overlay {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0, 0, 0, 0.5);
		z-index: 999;
	}

	.modaltipeform,
	.modaltipedate {
		display: none;
		position: absolute;
		top: 50%;
		left: 50%;
		z-index: 99;
	}

	.modaltipeform .content,
	.modaltipedate .content {
		padding: 20px;
		min-width: 200px;
		min-height: 100px;
		max-height: 300px;
		background-color: #fff;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		border-radius: 10px 10px 0 0;
		overflow: scroll;
	}

	.modaltipeform .content table,
	.modaltipedate .content table {
		width: 100%;
		margin-bottom: 20px;
		min-height: 50px;
		max-height: 100px;
		overflow: scroll;
	}

	.modaltipeform .footer,
	.modaltipedate .footer {
		background-color: #e8eaeb;
		border-radius: 0 0 10px 10px;
		display: flex;
		justify-content: center;
	}

	.modaltipeform .footer #btnFilter,
	.modaltipedate .footer #btnFilter {
		background-color: #2b82bd;
		color: #fff;
		width: 100%;
		padding: 5px 0;
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 0 0 0 10px;
		cursor: pointer;
	}

	.modaltipeform .footer #btnFilter:hover,
	.modaltipedate .footer #btnFilter:hover {
		background-color: #3ea6ed;
	}

	.modaltipeform .footer .closeModalTipeForm,
	.modaltipedate .footer .closeModalTipeForm {
		background-color: #c2c2c2;
		color: #fff;
		width: 100%;
		padding: 5px 0;
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 0 0 10px 0;
		cursor: pointer;
	}

	#myTable tbody tr td:nth-child(9) {
		width: 20px;
	}

	@media (max-width:750px) {

		.modaltipeform,
		.modaltipedate {
			width: 80%;
		}
	}
</style>

<div class="row">
	<div class="col-sm-12">
		<div class="box">
			<div class="box-body">
				<form id="formExport" action="<?= base_url('FrmRptQueryPDPJ/ExpToExcel') ?>" method="POST">

					<div class="">
						<table width="100%" style="border-top:1px solid silver;margin-top:5px;">
							<tr>
								<td style="padding-top:10px !important;">
									<?php foreach ($dataSum as $row) : ?>
										<p><?= $row['sumTotalPD'] ?></p>
									<?php endforeach; ?>
									<button type="submit" class="btn btn-danger btn-sm btn-exportExcel">Export To Excel</button>
								</td>
							</tr>
						</table>
						<p></p>
						<table id="myTable" class="table table-bordered table-hover table-condensed" class="font10">
							<thead class="font-10">
								<tr>
									<th colspan="10"></th>
									<th id="sumTotalKO">Sum</th>
									<th colspan="3"></th>
									<th id="sumTotalPD">Sum</th>
									<th colspan="2"></th>
									<th id="sumTotalBayar">Sum</th>
									<th colspan="3"></th>
									<th id="sumTotalPJ">Sum</th>
									<th id="sumSaldo">Sum</th>
								</tr>
								<tr>
									<th class="bg-gray" style="display: flex; width:100%; height: 100%; justify-content: right;">
										<input type="checkbox" name="checkAll" class="checkAll text-center bg-red">
									</th>
									<th class="bg-gray color-palette"></th>
									<th class="bg-gray color-palette">Job No <br><input type="text" id="searchJobNo" class="searchInput" placeholder="Cari JobNo.."></th>
									<th class="bg-gray color-palette">No PD <br><input type="text" id="searchNoPD" class="searchInput" placeholder="Cari NoPD.."></th>
									<th class="bg-gray color-palette">Tgl PD
										<div class="cont-seacrh">
											<input type="text" id="searchRef" class="searchInput" placeholder="Cari Periode..">
											<span class="filterHead2" data-head="TglPD">
												<i class="fa fa-filter"></i>
											</span>
										</div>
									</th>
									<th class="bg-gray color-palette">No. Ref Lapangan
										<div class="cont-seacrh">
											<input type="text" id="searchRef" class="searchInput" placeholder="Cari Nomor Ref..">
											<span class="filterHead" data-head="Ref">
												<i class="fa fa-filter"></i>
											</span>
										</div>
									</th>
									<th class="bg-gray color-palette" style="width: 20px;">Alokasi
										<div class="cont-seacrh">
											<input type="text" id="searchAlokasi" class="searchInput" placeholder="Cari Tipe Alokasi..">
											<span class="filterHead" data-head="Alokasi">
												<i class="fa fa-filter"></i>
											</span>
										</div>
									</th>
									<th class="bg-gray color-palette" style="width: 100px;">Tipe Form
										<div class="cont-seacrh">
											<input type="text" id="searchTipeForm" class="searchInput" placeholder="Cari Tipe Form..">
											<span class="filterHead" data-head="TipeForm">
												<i class="fa fa-filter"></i>
											</span>
										</div>
									</th>
									<th class="bg-gray color-palette" style="width: 200px;">Deskripsi</th>
									<th class="bg-gray color-palette" style="width: 80px;">No KO <br><input type="text" id="searchNoKo" class="searchInput" placeholder="Cari No Ko.."></th>
									<th class="bg-gray color-palette" class="text-center">Total KO (Rp)</th>

									<th class="bg-gray color-palette">No. Tagihan</th>
									<th class="bg-gray color-palette">Rekening Penerima</th>
									<th class="bg-gray color-palette">Atas Nama</th>
									<th class="bg-gray color-palette">Total PD (Rp)</th>
									<th class="bg-gray color-palette">Tgl Bayar
										<div class="cont-seacrh">
											<input type="text" id="searchRef" class="searchInput" placeholder="Cari Tgl Bayar..">
											<span class="filterHead2" data-head="TglBayar">
												<i class="fa fa-filter"></i>
											</span>
										</div>
									</th>

									<th class="bg-gray color-palette">Via</th>
									<th class="bg-gray color-palette">Total Pembayaran (Rp)</th>
									<th class="bg-gray color-palette">Rekening Pengirim</th>
									<th class="bg-gray color-palette">No PJ</th>
									<th class="bg-gray color-palette">Tgl PJ</th>
									<th class="bg-gray color-palette ">Total PJ (Rp)</th>
									<th class="bg-gray color-palette ">Saldo (Rp)</th>
								</tr>
							</thead>
						</table>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="modaltipeform" hidden>
		<div class="content">
			<p>Filter Value:</p>
			<hr>
			<table id="tableTipeForm">

			</table>
		</div>
		<div class="footer">
			<div id="btnFilter">Filter</div>
			<div class="closeModalTipeForm">Close</div>
		</div>
	</div>
	<div class="modaltipedate" hidden>
		<div class="content">
			<p>Pilih Periode:</p>
			<hr>
			<table id="tableTipeForm">
				<div class="input-group-addon">
					<i class="fa fa-calendar" style="margin-right: 5px;"></i>Date
				</div>
				<input type="text" class="form-control pull-right" id="datePeriode">
				<button class="btn btn-success btn-sm btn-reset" style="width: 100%; margin-top:10px;">Hapus Filter</button>
			</table>
		</div>
		<div class="footer">
			<div class="closeModalTipeForm">Close</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	$('.modaltipeform').hide()
	$(function() {
		$('.checkAll').click(function() {
			var isChecked = $(this).prop('checked');
			$('.checkboxes').prop('checked', isChecked)
		});

		$(".checkboxes").click(function() {
			var numberOfCheckboxes = $(".checkboxes").length;
			var numberOfCheckboxesChecked = $('.checkboxes:checked').length;
			if (numberOfCheckboxes == numberOfCheckboxesChecked) {
				$(".checkAll").prop("checked", true);
			} else {
				$(".checkAll").prop("checked", false);
			}
		});
	});

	function format(d) {
		var NoPd = d.NoPD
		var Pddtl = d.res.Pddtl
		var SumSubTotalPD = d.res.SumSubTotalPD
		var SumSubTotalPJ = d.res.SumSubTotalPJ
		var Invoice = d.res.Invoice
		var SumTotalInv = d.res.SumTotalInv
		var Pembayaran = d.res.Pembayaran
		var SumNilaiBayar = d.res.SumNilaiBayar

		function generatePdttlRows() {
			var rows = '';
			Pddtl.forEach(function(item) {
				rows += `
		        <tr>
		            <td>${item.KdRAP}</td>
		            <td>${item.Uraian}</td>
		            <td>${item.Vol}</td>
		            <td>${item.Uom}</td>
		            <td>${formatRupiah(item.HrgSatuan.toString())}</td>
		            <td>${formatRupiah(item.SubTotal.toString())}</td>
		            <td>${item.PjUraian}</td>
		            <td>${item.PjVol}</td>
		            <td>${item.Uom}</td>
		            <td>${formatRupiah(item.PjHrgSatuan.toString())}</td>
		            <td>${formatRupiah(item.PjSubtotal.toString())}</td>
		        </tr>
		    `;
			});
			return rows;
		}

		function generateInvoiceRows() {
			var rows = '';
			Invoice.forEach(function(item) {
				rows += `
		        <tr>
					<td>${item.NoKO}</td>
		            <td>${htmlspecialchars(item.InvNo)}</td>
					<td>${item.InvDate}</td>
		            <td>${item.DueDate}</td>
		            <td>${item.TotalInv}</td>
		            <td>${item.PPN}</td>
		            <td>${item.FPNo}</td>
		            <td>${item.FPDate}</td>
		            <td>${item.PaymentAmount}</td>
		        </tr>
		    `;
			});
			return rows;
		}

		function generatePembayaranRows() {
			var rows = '';
			Pembayaran.forEach(function(item) {
				rows += `
		        <tr>
		            <td>${item.TglBayar}</td>
		            <td>${item.AtasNama}</td>
		            <td>${item.NoRek}</td>
		            <td>${item.Bank}</td>
		            <td>${formatRupiah(item.Amount.toString())}</td>
		            <td>${item.CaraBayar}</td>
		            <td>${item.JenisTrf}</td>
		            <td>${item.NoCG}</td>
		            <td>${item.RekId}</td>
		            <td>${item.NoKO}</td>
		            <td>${item.Keterangan}</td>
		        </tr>
		    `;
			});
			return rows;
		}

		return `
				<div class="panel panel-default">
					<div class="panel-heading panel-heading-nav">
						<ul class="nav nav-tabs">
							<li role="presentation" class="TabA active">
								<a href="#one${NoPd}" aria-controls="one" role="tab" data-toggle="tab">Uraian PD</a>
							</li>
							<li role="presentation" class="TabB" >
								<a href="#two${NoPd}" aria-controls="two" role="tab" data-toggle="tab">Invoice</a>
							</li>
							<li role="presentation" class="TabC" >
								<a href="#three${NoPd}" aria-controls="three" role="tab" data-toggle="tab">Pembayaran</a>
							</li>
						</ul>
					</div>
					<div class="panel-body">
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane fade in active" id="one${NoPd}">
								<div class="row tampilan_table_RPPM">
									<div class="col-sm-12">
										<div class="table-responsive">
											<table id="myTable" class="table myTable table-bordered table-hover table-condensed" style="width:40%;" class="font10">
												<thead class="font-10">
													<tr>
														<th class="bg-gray color-palette" width="5%">Kode RAP</th>
														<th class="bg-gray color-palette" width="15%">Uraian PD</th>
														<th class="bg-gray color-palette" width="10%">Volume PD</th>
														<th class="bg-gray color-palette" width="5%">UOM</th>
														<th class="bg-gray color-palette" width="10%">Harga Satuan PD (Rp)</th>
														<th class="bg-gray color-palette" width="15%">Sub Total PD (Rp)</th>

														<th class="bg-gray color-palette" width="10%">Uraian PJ</th>
														<th class="bg-gray color-palette" width="5%">Volume PJ</th>
														<th class="bg-gray color-palette" width="5%">UOM</th>
														<th class="bg-gray color-palette" width="10%" class="text-right">Harga Satuan PJ (Rp)</th>
														<th class="bg-gray color-palette" width="10%">Sub Total PJ</th>
													</tr>
												</thead>
												<tbody>
													 ${generatePdttlRows()}
													 <tr>
													 	<td></td><td></td><td></td><td></td><td></td>
														<td>
															Sum = Rp. ${SumSubTotalPD}
														</td>
														<td></td><td></td><td></td><td>
														<td>
															Sum = Rp. ${SumSubTotalPJ}
														</td>
													 </tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="two${NoPd}" >
								<div class="row">
									<div class="col-sm-12">
										<div class="table-responsive">
											<table id="myTable" class="table table-bordered table-hover table-condensed" style="width:40%;" class="font10">
												<thead class="font-10">
													<tr>
														<th class="bg-gray color-palette">No KO</th>
														<th class="bg-gray color-palette">Invoice</th>
														<th class="bg-gray color-palette">Tgl Invoice</th>
														<th class="bg-gray color-palette">Due Date</th>
														<th class="bg-gray color-palette" class="text-right">Total Invoice (Rp)</th>
														<th class="bg-gray color-palette">PPN</th>
														<th class="bg-gray color-palette">No Faktur Pajak</th>
														<th class="bg-gray color-palette">Tgl Faktur Pajak</th>
														<th class="bg-gray color-palette">Payment Amount (Rp)</th>
													</tr>
												</thead>
												<tbody>
													${generateInvoiceRows()}
													<tr>
														<td></td><td></td><td></td><td></td>
														<td>Sum = Rp. ${SumTotalInv}</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="three${NoPd}" >
								<div class="row">
									<div class="col-sm-12">
										<div class="table-responsive">
											<table id="myTable" class="table table-bordered table-hover table-condensed" style="width:40%;" class="font10">
												<thead class="font-10">
													<tr>
														<th class="bg-gray color-palette" width="10%">Tgl Bayar</th>
														<th class="bg-gray color-palette" width="10%">Nama Penerima</th>
														<th class="bg-gray color-palette" width="10%">Rekening Penerima</th>
														<th class="bg-gray color-palette" width="10%">Bank Penerima</th>
														<th class="bg-gray color-palette" width="15%">Nilai Bayar (Rp)</th>

														<th class="bg-gray color-palette" width="10%">Media</th>
														<th class="bg-gray color-palette" width="10%">Via</th>
														<th class="bg-gray color-palette" width="5%">No Cek/BG</th>
														<th class="bg-gray color-palette" width="10%" class="text-right">Rekening Pengirim</th>
														<th class="bg-gray color-palette" width="10%">No. KO</th>
														<th class="bg-gray color-palette" width="10%">Keterangan</th>
													</tr>
												</thead>
												<tbody>
												${generatePembayaranRows()}
												<tr>
													<td></td><td></td><td></td><td></td>
													<td>Sum = Rp. ${SumNilaiBayar}</d>
												</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			`;
	}

	function formatRupiah(value) {
		const cleanedValue = value.replace(/[^\d.-]/g, '');
		const floatValue = parseFloat(cleanedValue);
		const isNegative = floatValue < 0;
		const absoluteValue = Math.abs(floatValue);
		let formattedValue = absoluteValue.toLocaleString('id-ID', {
			currency: 'IDR',
			minimumFractionDigits: 0,
			maximumFractionDigits: 0
		});
		formattedValue = isNegative ? `Rp -${formattedValue}` : `Rp ${formattedValue}`;
		return formattedValue;
	}


	function htmlspecialchars(str) {
		var map = {
			'&': '&amp;',
			'<': '&lt;',
			'>': '&gt;',
			'"': '&quot;',
			"'": '&#039;'
		};
		return str.replace(/[&<>"']/g, function(m) {
			return map[m];
		});
	}

	function sum_string_to_int(nilai) {
		nilai = nilai.replace(/[^\d.-]/g, '');
		const integerValue = parseInt(nilai.replace(/\./g, ''), 10);
		if (isNaN(integerValue)) {
			console.error('Invalid input value:', nilai);
			return 0;
		}
		return integerValue;
	}

	function filterTableByDate(startDate, endDate, typeHead) {
		var nilaiTotalKO = 0;
		var nilaiTotalPD = 0;
		var nilaiTotalPembayaran = 0;
		var nilaiTotalPJ = 0;
		var nilaiTotalSaldo = 0;
		$('#myTable tbody tr').each(function() {
			if (typeHead == 'TglPD') {
				var rowTglPD = $(this).find('td:eq(4)').text().toLowerCase()
			} else if (typeHead == 'TglBayar') {
				var rowTglPD = $(this).find('td:eq(15)').text().toLowerCase()
			}
			if (rowTglPD >= startDate && rowTglPD <= endDate) {
				$(this).show();
				nilaiTotalKO += reomoveRpAndDot($(this).find('td:eq(10)').text())
				nilaiTotalPD += reomoveRpAndDot($(this).find('td:eq(14)').text())
				nilaiTotalPembayaran += reomoveRpAndDot($(this).find('td:eq(17)').text())
				nilaiTotalPJ += reomoveRpAndDot($(this).find('td:eq(21)').text())
				nilaiTotalSaldo += reomoveRpAndDot($(this).find('td:eq(22)').text())
			} else {
				$(this).hide();
			}
			$('#sumTotalKO').html(formatRupiah(nilaiTotalKO.toString()))
			$('#sumTotalPD').html(formatRupiah(nilaiTotalPD.toString()))
			$('#sumTotalBayar').html(formatRupiah(nilaiTotalPembayaran.toString()))
			$('#sumTotalPJ').html(formatRupiah(nilaiTotalPJ.toString()))
			$('#sumSaldo').html(formatRupiah(nilaiTotalSaldo.toString()))
		})

	}

	function reomoveRpAndDot(value) {
		var removeRPandDot = value.replace(/Rp|\./g, '')
		return parseInt(removeRPandDot)
	}

	$(document).ready(function() {
		var table = $('#myTable').DataTable({
			ajax: {
				'url': "<?= base_url('FrmRptQueryPDPJ/TampilkanTable_QueryPDPJ2') ?>",
				'dataSrc': function(result) {
					$('#sumTotalPD').html(formatRupiah(result.dataSum.sumTotalPD.toString()))
					$('#sumTotalPJ').html(formatRupiah(result.dataSum.sumTotalPJ.toString()))
					$('#sumSaldo').html(formatRupiah(result.dataSum.sumSaldo.toString()))
					$('#sumTotalBayar').html(formatRupiah(result.dataSum.sumTotalBayar.toString()))
					$('#sumTotalKO').html(formatRupiah(result.dataSum.sumTotalKO.toString()))
					return result.data
				}
			},
			"scrollY": false,
			"scrollCollapse": true,
			"pageLength": 50,
			"lengthMenu": [50, 100, 200, 500, 1000, -1],
			"scrollX": true,
			"columns": [{
					"data": "NomorUrut",
					"class": "text-center",
					"render": function(data, type, row) {
						return `<td>
		                    <input type="checkbox" name="checklistExport[]" class="checkboxes chck" value="${data}">
		                </td>`;
					},
					"orderable": false,
				},
				{
					"class": 'details-control bg-white color-palette',
					"data": "NomorUrut",
					"render": function(data, type, row) {
						return `<div style="display:flex; justify-content:center; align-items: center; padding: 5px; width: 20px; height:20px; text-align: center; border-radius:50%; background: #000; color: #fff;">
						<i class="fa fa-plus"></i>
						<input type="checkbox" style="display: none;" name="showDetail[]" value="${data}" /></div>`;
					},
					"orderable": false,
				},
				{
					"class": "text-center",
					"data": "JobNo1",
					"orderable": false,
				},
				{
					"data": "NoPD",
					"orderable": false,
				},
				{
					"data": "TglPD",
					"orderable": false,
					"render": function(data, type, row) {
						return data;
					}
				},
				{
					"data": "NoRef",
					"orderable": false,
				},
				{
					"class": "text-center",
					"data": "Alokasi",
					"orderable": false,
				},
				{
					"class": "text-center",
					"data": "TipeForm",
					"orderable": false,
				},
				{
					"class": "deskripsi",
					"data": "Deskripsi"
				},
				{
					"class": "",
					"data": "NoKO",
					"orderable": false,
				},
				{
					"class": "text-right",
					"data": "TotalKO",
					"render": function(data, type, row) {
						if (data !== '.0000') {
							return formatRupiah(data.toString());
						} else {
							return 0;
						}
					},
				},
				{
					"data": "NoTagihan"
				},
				{
					"data": "NoRek"
				},
				{
					"data": "AtasNama"
				},
				{
					"class": "text-right",
					"data": "TotalPD",
					"render": function(data, type, row) {
						if (data !== '.0000') {
							return formatRupiah(data.toString());
						} else {
							return 0;
						}
					},
				},
				{
					"data": "TglBayar",
					"orderable": false
				},
				{
					"data": "JenisTrf"
				},
				{
					"class": "text-right",
					"data": "BLEAmount",
					"render": function(data, type, row) {
						if (data !== '.0000') {
							return formatRupiah(data.toString());
						} else {
							return 0;
						}
					},
				},
				{
					"data": "RekId"
				},
				{
					"data": "NoPJ"
				},
				{
					"data": "TglPJ"
				},
				{
					"data": "TotalPJ",
					"render": function(data, type, row) {
						if (data !== '.0000') {
							return formatRupiah(data.toString());
						} else {
							return 0;
						}
					},
				},
				{
					"data": "Saldo1",
				},

			],
			"order": [
				[1, 'asc']
			],
		});


		var arrDetailKlik = [];
		$('#myTable tbody').on('click', 'td.details-control', function() {
			var tr = $(this).parents('tr');
			var row = table.row(tr);
			const data = row.data()
			var checkDetail = $(this).find('input');

			$.ajax({
				url: "<?= base_url('FrmRptQueryPDPJ/getDataForChildRowTablePDPJ') ?>",
				method: 'POST',
				data: {
					NoPD: data.NoPD
				},
				success: function(result) {
					const res = JSON.parse(result);
					dataResult = {
						'NoPD': data.NoPD,
						'res': res,
					}
					var hasilquery = dataResult;

					if (row.child.isShown()) {
						row.child.hide();
						tr.removeClass('shown');
						checkDetail.prop('checked', false)
					} else {
						row.child(format(hasilquery)).show();
						tr.addClass('shown');
						checkDetail.prop('checked', true)
					}
				}
			})

		});

		function searchTable() {
			var filterJobNo = $('#searchJobNo').val().toLowerCase()
			var filterNoPD = $('#searchNoPD').val().toLowerCase()
			var filterRef = $('#searchRef').val().toLowerCase()
			var filterAlokasi = $('#searchAlokasi').val().toLowerCase()
			var filterTipeForm = $('#searchTipeForm').val().toLowerCase()
			var filterNoKo = $('#searchNoKo').val().toLowerCase()
			$('#myTable tbody tr').each(function() {
				var txtValueJobNo = $(this).find('td:eq(2)').text().toLowerCase()
				var txtValueNoPD = $(this).find('td:eq(3)').text().toLowerCase()
				var txtValueRef = $(this).find('td:eq(5)').text().toLowerCase()
				var txtValueAlokasi = $(this).find('td:eq(6)').text().toLowerCase()
				var txtValueTipeForm = $(this).find('td:eq(7)').text().toLowerCase()
				var txtValueNoKo = $(this).find('td:eq(9)').text().toLowerCase()
				if (txtValueJobNo.indexOf(filterJobNo) > -1 && txtValueNoPD.indexOf(filterNoPD) > -1 && txtValueRef.indexOf(filterRef) > -1 && txtValueAlokasi.indexOf(filterAlokasi) > -1 && txtValueTipeForm.indexOf(filterTipeForm) > -1 && txtValueNoKo.indexOf(filterNoKo) > -1) {
					$(this).show()
				} else {
					$(this).hide()
				}
			})
		}

		function filterTable(values) {
			var TotalPD = 0;
			var TotalBayar = 0;
			var TotalPJ = 0;
			var TotalSaldo = 0;
			var TotalKO = 0;
			$('#myTable tbody tr').each(function() {
				if (typeHead == 'Alokasi') {
					var txtValueTipe = $(this).find('td:eq(6)').text().toLowerCase()
				} else if (typeHead == 'TipeForm') {
					var txtValueTipe = $(this).find('td:eq(7)').text().toLowerCase()
				} else if (typeHead == 'Ref') {
					var txtValueTipe = $(this).find('td:eq(5)').text().toLowerCase()
				}
				var lowerCaseArray = values.map(function(value) {
					return value.toLowerCase();
				});
				if (lowerCaseArray.length === 0 || lowerCaseArray.includes(txtValueTipe)) {
					$(this).show()
					var valueTotalPD = $(this).find('td:eq(14)').text().toString() == '' ? 'Rp 0' : $(this).find('td:eq(14)').text().toString()
					var valueTotalBayar = $(this).find('td:eq(17)').text().toString() == '' ? 'Rp 0' : $(this).find('td:eq(17)').text().toString()
					var valueTotalPJ = $(this).find('td:eq(21)').text().toString() == '' ? 'Rp 0' : $(this).find('td:eq(21)').text().toString()
					var valueTotalSaldo = $(this).find('td:eq(22)').text().toString() == '' ? 'Rp 0' : $(this).find('td:eq(22)').text().toString()
					var valueTotalKO = $(this).find('td:eq(10)').text().toString() == '' ? 'Rp 0' : $(this).find('td:eq(10)').text().toString()
					var convPD = sum_string_to_int(valueTotalPD)
					var convBayar = sum_string_to_int(valueTotalBayar)
					var convPJ = sum_string_to_int(valueTotalPJ)
					var convSaldo = sum_string_to_int(valueTotalSaldo)
					var convKO = sum_string_to_int(valueTotalKO)
					if (!isNaN(convPD)) {
						TotalPD += convPD;
					}
					if (!isNaN(convBayar)) {
						TotalBayar += convBayar;
					}
					if (!isNaN(convPJ)) {
						TotalPJ += convPJ;
					}
					if (!isNaN(convSaldo)) {
						TotalSaldo += convSaldo;
					}
					if (!isNaN(convKO)) {
						TotalKO += convKO;
					}
				} else {
					$(this).hide()
				}

			})
			$('#sumTotalPD').html(formatRupiah(TotalPD.toString()))
			$('#sumTotalBayar').html(formatRupiah(TotalBayar.toString()))
			$('#sumTotalPJ').html(formatRupiah(TotalPJ.toString()))
			$('#sumSaldo').html(formatRupiah(TotalSaldo.toString()))
			$('#sumTotalKO').html(formatRupiah(TotalKO.toString()))
		}


		$('.searchInput').on('input', searchTable)

		var typeHead;
		$('.filterHead').click(function() {
			typeHead = $(this).data('head')
			var modal = $('.modaltipeform');
			var overlay = $('#overlay');
			var valuesMap = {};
			var valuesArray = [];
			$('#myTable tbody tr').each(function() {
				if (typeHead == 'TipeForm') {
					var valuesTd = $(this).find('td:eq(7)').text() == 'null' ? 'kosong' : $(this).find('td:eq(7)').text()
					if (!valuesMap[valuesTd]) {
						valuesMap[valuesTd] = true;
						valuesArray.push(valuesTd);
					}
				} else if (typeHead == 'Alokasi') {
					var valuesTd = $(this).find('td:eq(6)').text()
					if (!valuesMap[valuesTd]) {
						valuesMap[valuesTd] = true;
						valuesArray.push(valuesTd);
					}
				} else if (typeHead == 'Ref') {
					var valuesTd = $(this).find('td:eq(5)').text() == '' ? 'null' : $(this).find('td:eq(5)').text()
					if (!valuesMap[valuesTd]) {
						valuesMap[valuesTd] = true;
						valuesArray.push(valuesTd);
					}
				} else {
					valuesArray = []
				}
			})
			$('#tableTipeForm').empty();
			$.each(valuesArray, function(index, label) {
				if (label == 'null') {
					var value = ''
				} else {
					var value = label
				}
				$('#tableTipeForm').append(`
					<tr style="border-bottom:1px solid #ebe6e6; ">
						<td><input style="margin-right:8px; margin-bottom:10px;" type="checkbox" name="isi" class="checkboxtipeform" value="${value}" id="${index}"><label for="${index}">${label}</label></td>
					</tr>
				`)
			})
			var buttonPosition = $(this).offset();
			var modalWidth = modal.outerWidth();
			var modalHeight = modal.outerHeight();
			var screenWidth = $(window).width();
			var screenHeight = $(window).height();
			var left = buttonPosition.left;
			if (buttonPosition.left + modalWidth > screenWidth) {
				left = screenWidth - modalWidth;
			}
			var top = buttonPosition.top + $(this).outerHeight();
			if (buttonPosition.top + $(this).outerHeight() + modalHeight > screenHeight) {
				top = screenHeight - modalHeight - $(this).outerHeight();
			}
			modal.css({
				top: top,
				left: left
			});
			modal.show();
		});

		$('.filterHead2').click(function() {
			typeHead = $(this).data('head')
			var modal = $('.modaltipedate');
			var overlay = $('#overlay');
			var valuesMap = {};
			var valuesArray = [];
			var buttonPosition = $(this).offset();
			var modalWidth = modal.outerWidth();
			var modalHeight = modal.outerHeight();
			var screenWidth = $(window).width();
			var screenHeight = $(window).height();
			var left = buttonPosition.left;
			if (buttonPosition.left + modalWidth > screenWidth) {
				left = screenWidth - modalWidth;
			}
			var top = buttonPosition.top + $(this).outerHeight();
			if (buttonPosition.top + $(this).outerHeight() + modalHeight > screenHeight) {
				top = screenHeight - modalHeight - $(this).outerHeight();
			}
			modal.css({
				top: top,
				left: left
			});
			modal.show();
		})


		$('.closeModalTipeForm').click(function() {
			var modal = $('.modaltipeform');
			var modal2 = $('.modaltipedate');
			var overlay = $('#overlay');
			modal.hide();
			modal2.hide();
			overlay.hide();
		})
		$('#btnFilter').click(function() {
			var checkValues = [];
			$('.checkboxtipeform:checked').each(function() {
				checkValues.push($(this).val())
			})
			filterTable(checkValues)
			var modal = $('.modaltipeform');
			var overlay = $('#overlay');
			modal.hide();
			overlay.hide();
		})

		$('#formExport').submit(function(e) {
			e.preventDefault()
			var numberOfCheckboxesChecked = $('.checkboxes:checked').length;
			numberOfCheckboxesChecked >= 1 ? $(this).unbind('submit').submit() : Swal.fire('Alert!', 'Silahkan pilih data terlebih dahulu', 'info')
		})

		$('#datePeriode').daterangepicker();

		$('#datePeriode').on('apply.daterangepicker', function(ev, picker) {
			var startDate = picker.startDate.format('YYYY-MM-DD');
			var endDate = picker.endDate.format('YYYY-MM-DD');
			filterTableByDate(startDate, endDate, typeHead)
		});

		$('.btn-reset').click(function() {
			var nilaiTotalKO = 0;
			var nilaiTotalPD = 0;
			var nilaiTotalPembayaran = 0;
			var nilaiTotalPJ = 0;
			var nilaiTotalSaldo = 0;
			$('#myTable tbody tr').each(function() {
				nilaiTotalKO += reomoveRpAndDot($(this).find('td:eq(10)').text())
				nilaiTotalPD += reomoveRpAndDot($(this).find('td:eq(14)').text())
				nilaiTotalPembayaran += reomoveRpAndDot($(this).find('td:eq(17)').text())
				nilaiTotalPJ += reomoveRpAndDot($(this).find('td:eq(21)').text())
				nilaiTotalSaldo += reomoveRpAndDot($(this).find('td:eq(22)').text())
				$('#sumTotalKO').html(formatRupiah(nilaiTotalKO.toString()))
				$('#sumTotalPD').html(formatRupiah(nilaiTotalPD.toString()))
				$('#sumTotalBayar').html(formatRupiah(nilaiTotalPembayaran.toString()))
				$('#sumTotalPJ').html(formatRupiah(nilaiTotalPJ.toString()))
				$('#sumSaldo').html(formatRupiah(nilaiTotalSaldo.toString()))
			})
			$("#myTable tbody tr").show();
		})


	});
</script>