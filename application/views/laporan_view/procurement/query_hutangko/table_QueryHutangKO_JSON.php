<style type="text/css">
	#table_QHutangKO {
		font-size: 12px;
		font-family: Arial, Helvetica, sans-serif;
	}

	#table_QHutangKO thead {
		background-color: #ccd4db;
		color: #000;
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

	#table_QHutangKO tbody tr td.dt-control .bg {
		background-color: #2b82bd;
		padding: 2px;
		border-radius: 2px;
		color: #fff;
	}

	#table_QHutangKO tbody tr.shown td.dt-control .bg {
		background-color: gray;
		padding: 2px;
		border-radius: 2px;
		color: #000;
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

	.modaltipeform {
		display: none;
		position: absolute;
		top: 50%;
		left: 50%;
		z-index: 99;
	}

	.modaltipeform .content {
		min-width: 200px;
		min-height: 100px;
		max-height: 300px;
		background-color: #fff;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		border-radius: 10px 10px 0 0;
		overflow: scroll;
	}

	.modaltipeform .content table {
		width: 100%;
		margin-bottom: 20px;
		min-height: 50px;
		max-height: 100px;
		overflow: scroll;
	}

	.modaltipeform .content table tr:hover {
		background-color: #ebebeb;
	}

	.modaltipeform .footer {
		background-color: #e8eaeb;
		border-radius: 0 0 10px 10px;
		display: flex;
		justify-content: center;
	}

	.modaltipeform .footer #btnFilter {
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

	.modaltipeform .footer #btnFilter:hover {
		background-color: #3ea6ed;
	}

	.modaltipeform .footer #closeModalTipeForm {
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

	@media (max-width:750px) {
		.modaltipeform {
			width: 80%;
		}
	}
</style>
<div class="col-12 ">

	<div class="box box-primary">

		<div class="box-header">

			<button class="btn btn-md btn-danger pdf_QHutangKO" data-JobNo="<?= $JobNo ?>" data-VendorId="<?= $VendorId ?>" data-Status="<?= $Status ?>" data-Url="<?= site_url('Pdf_master/Query_HutangKO_PDF/' . $JobNo . '/' . $VendorId . '/' . $Status . '/1') ?>">Print PDF <i class="fa fa-print"></i></button>

			<button class="btn btn-md btn-success excel_QHutangKO" data-JobNo="<?= $JobNo ?>" data-VendorId="<?= $VendorId ?>" data-Status="<?= $Status ?>" data-Url="<?= site_url('Laporan/Procurement/Query_HutangKO/export_excel/' . $JobNo . '/' . $VendorId . '/' . $Status . '/1') ?>">Export Excel <i class="fa fa-file-o"></i></button>

			<a href="<?= site_url('Laporan/Procurement/Query_HutangKO/export_excel_detail/' . $JobNo.'/'.$VendorId.'/'.$Status) ?>" target="_blank" class="btn btn-md btn-warning text-black pdf_QHutangKO_dengan_detail"  data-JobNo="<?= $JobNo ?>" data-VendorId="<?= $VendorId ?>" data-Status="<?= $Status ?>" >Export Excel Dengan Detail <i class="fa fa-file-o"></i></a>
			
			<a href="<?= site_url('Laporan/Procurement/Query_HutangKO/export_excel_tes/' . $JobNo) ?>" target="_blank" class="btn btn-md btn-primary pdf_QHutangKO_dengan_detail"  data-JobNo="<?= $JobNo ?>" data-VendorId="<?= $VendorId ?>" data-Status="<?= $Status ?>" >Export Excel 2 <i class="fa fa-file-o"></i></a>


		</div>

		<div class="box-body">

			<div class="table-responsive">

				<table id="table_QHutangKO" class="table table-striped table-hover table-condensed" border="0" width="100%">

					<thead>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th class="text-right">Sum = <?= number_format($TotalKO, 2, '.', ',') ?></th>
							<th class="text-right">Sum = <?= number_format($PaymentKO, 2, '.', ',') ?></th>
							<th class="text-right">Sum = <?= number_format($RemainingKO, 2, '.', ',') ?></th>
							<th class="text-right">Sum = <?= number_format($TotalInv, 2, '.', ',') ?></th>
							<th class="text-right">Sum = <?= number_format($PaymentInv, 2, '.', ',') ?></th>
							<th class="text-right">Sum = <?= number_format($RemainingInv, 2, '.', ',') ?></th>
						</tr>
						<tr>
							<th>#</th>
							<th>#</th>
							<th>Job. No</th>
							<th>Job Name</th>
							<th>No. KO
								<div class="cont-seacrh">
									<input type="text" id="searchNoKO" class="searchInput" placeholder="Cari Nomor KO..">
									<span class="filterHead" data-head="NoKO">
										<i class="fa fa-filter"></i>
									</span>
								</div>
							</th>
							<th>Tgl KO
								<div class="cont-seacrh">
									<input type="text" id="searchTglKO" class="searchInput" placeholder="Cari Tanggal KO..">
									<span class="filterHead" data-head="TglKO">
										<i class="fa fa-filter"></i>
									</span>
								</div>
							</th>
							<th>Tipe KO
								<div class="cont-seacrh">
									<input type="text" id="searchTipeKO" class="searchInput" placeholder="Cari Tipe KO..">
									<span class="filterHead" data-head="TipeKO">
										<i class="fa fa-filter"></i>
									</span>
								</div>
							</th>
							<th>
								Vendor
								<div class="cont-seacrh">
									<input type="text" id="searchVendor" class="searchInput" placeholder="Cari Vendor..">
									<span class="filterHead" data-head="Vendor">
										<i class="fa fa-filter"></i>
									</span>
								</div>
							</th>
							<th class="text-right">Amount KO (Rp)</th>
							<th class="text-right">Payment KO (Rp)</th>
							<th class="text-right">Remaining KO (Rp)</th>
							<th class="text-right">Amount Invoice (Rp)</th>
							<th class="text-right">Payment Invoice (Rp)</th>
							<th class="text-right">Remaining Invoice (Rp)</th>

						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>

		<div class="overlay dark loading_table" style="display: none;">
			<i class="fa fa-refresh fa-spin"></i>
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
		<div id="closeModalTipeForm">Close</div>
	</div>
</div>




<script>
	$(document).ready(function() {

		$('.pdf_QHutangKO').click(function() {

			var JobNo = $(this).attr('data-JobNo');
			var VendorId = $(this).attr('data-VendorId');
			var Status = $(this).attr('data-Status');
			var Url = $(this).attr('data-Url');

			window.open(Url, '_blank');

		});
		$('.excel_QHutangKO').click(function() {
			var Url = $(this).attr('data-Url');
			window.open(Url, '_blank');
		})
		getJSON_QqueryHutangKO();


		$('.searchInput').on('input', searchTable)

		var typeHead;
		$('.filterHead').click(function() {
			$("#table_QHutangKO tbody tr.shown").removeClass("shown");
			$("#table_QHutangKO tbody tr:gt(.shown)").hide();
			typeHead = $(this).data('head')
			var modal = $('.modaltipeform');
			var overlay = $('#overlay');
			var valuesMap = {};
			var valuesArray = [];
			$('#table_QHutangKO tbody tr').each(function() {
				if (typeHead == 'NoKO') {
					var valuesTd = $(this).find('td:eq(4)').text()
					if (!valuesMap[valuesTd]) {
						valuesMap[valuesTd] = true;
						valuesArray.push(valuesTd);
					}
				} else if (typeHead == 'TglKO') {
					var valuesTd = $(this).find('td:eq(5)').text()
					if (!valuesMap[valuesTd]) {
						valuesMap[valuesTd] = true;
						valuesArray.push(valuesTd);
					}
				} else if (typeHead == 'TipeKO') {
					var valuesTd = $(this).find('td:eq(6)').text()
					if (!valuesMap[valuesTd]) {
						valuesMap[valuesTd] = true;
						valuesArray.push(valuesTd);
					}
				} else if (typeHead == 'Vendor') {
					var valuesTd = $(this).find('td:eq(7)').text()
					if (!valuesMap[valuesTd]) {
						valuesMap[valuesTd] = true;
						valuesArray.push(valuesTd);
					}
				} else {
					valuesArray = []
				}
			})
			$('#tableTipeForm').empty();
			$.each(valuesArray, function(index, val) {
				$('#tableTipeForm').append(`
					<tr style="border-bottom:1px solid #ebe6e6; ">
						<td><input style="margin-right:8px; margin-bottom:10px;" type="checkbox" name="isi" class="checkboxtipeform" value="${val}" id="${index}"><label for="${index}">${val}</label></td>
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

		$('#closeModalTipeForm').click(function() {
			var modal = $('.modaltipeform');
			var overlay = $('#overlay');
			modal.hide();
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

		function filterTable(values) {
			$('#table_QHutangKO tbody tr').each(function() {
				if (typeHead == 'NoKO') {
					var txtValueTipe = $(this).find('td:eq(4)').text().toLowerCase()
				} else if (typeHead == 'TglKO') {
					var txtValueTipe = $(this).find('td:eq(5)').text().toLowerCase()
				} else if (typeHead == 'TipeKO') {
					var txtValueTipe = $(this).find('td:eq(6)').text().toLowerCase()
				} else if (typeHead == 'Vendor') {
					var txtValueTipe = $(this).find('td:eq(7)').text().toLowerCase()
				} else {
					return false;
				}
				var lowerCaseArray = values.map(function(value) {
					return value.toLowerCase();
				});
				if (lowerCaseArray.length === 0 || lowerCaseArray.includes(txtValueTipe)) {
					$(this).show()
				} else {
					$(this).hide()
				}
			})
		}
	});


	function searchTable() {
		var filterNoKO = $('#searchNoKO').val().toLowerCase()
		var filterTglKO = $('#searchTglKO').val().toLowerCase()
		var filterTipeKO = $('#searchTipeKO').val().toLowerCase()
		var filterVendor = $('#searchVendor').val().toLowerCase()
		$('#table_QHutangKO tbody tr').each(function() {
			var txtValueNoKO = $(this).find('td:eq(4)').text().toLowerCase()
			var txtValueTglKO = $(this).find('td:eq(5)').text().toLowerCase()
			var txtValueTipeKO = $(this).find('td:eq(6)').text().toLowerCase()
			var txtValueVendor = $(this).find('td:eq(7)').text().toLowerCase()
			if (txtValueNoKO.indexOf(filterNoKO) > -1 && txtValueTglKO.indexOf(filterTglKO) > -1 && txtValueTipeKO.indexOf(filterTipeKO) > -1 && txtValueVendor.indexOf(filterVendor) > -1) {
				$(this).show()
			} else {
				$(this).hide()
			}
		})
	}

	function LihatDetail(JobNo, NoKO) {
		// alert(JobNo+' - '+NoKO);
		$('#ModalBody_QueryHutangKO').html('');

		jQuery.ajax({
			url: '<?= site_url('Laporan/Procurement/Query_HutangKO/getDetail_QueryHutangKO2') ?>',
			type: 'POST',
			dataType: 'HTML',
			data: {
				'JobNo': JobNo,
				'NoKO': NoKO
			},
			beforeSend: function() {
				$('.loading_table').show();
			},
			complete: function(xhr, textStatus) {
				$('.loading_table').hide();
			},
			success: function(data, textStatus, xhr) {
				// alert(data);
				$('#ModalBody_QueryHutangKO').html(data);
				$('#Modal_QueryHutangKO').modal('show');
			},
			error: function(xhr, textStatus, errorThrown) {
				$.toast({
					text: 'Terjadi Kesalahan',
					position: 'bottom-right',
					stack: false,
					hideAfter: 1000,
					icon: 'error',

				});
				return false;
			}
		});
	}

	function formatRupiah(value) {
		const floatValue = parseFloat(value.replace(/[^\d.]/g, ''));
		const formattedValue = floatValue.toLocaleString('id-ID', {
			style: 'currency',
			currency: 'IDR',
			minimumFractionDigits: 0,
			maximumFractionDigits: 0
		});
		return formattedValue;
	}

	function format(d) {
		var data1 = d.data.data1
		var data2 = d.data.data2

		function generateRows() {
			var rows = '';
			data1.forEach(function(item, index) {
				rows += `
					<tr>
						<td style="border-top:1px solid #000;" width="10px">${index+1}</td>
						<td style="border-top:1px solid #000; text-align:left">${item.NoKO}</td>
						<td style="border-top:1px solid #000; text-align:left">${htmlspecialchars(item.InvNo)}</td>
						<td style="border-top:1px solid #000">${item.InvDate}</td>
						<td style="border-top:1px solid #000">${item.DueDate}</td>
						<td style="border-top:1px solid #000">${item.Total}</td>
						<td style="border-top:1px solid #000">${item.PPN}</td>
						<td style="border-top:1px solid #000">${item.FPNo}</td>
						<td style="border-top:1px solid #000">${item.FPDate}</td>
						<td style="border-top:1px solid #000">${formatRupiah(item.PaymentAmount)}</td>
					</tr>
				`;
				data2.forEach(function(item2, index2) {
					if (item.InvNo == item2.InvNo) {
						rows += `
							<tr>
								<td width="10px"></td>
								<td style="text-align:left;"><span style="font-weight:bold;">No Invoice :</span> ${htmlspecialchars(item2.InvNo)}</td>
								<td style="text-align:left;"><span style="font-weight:bold;">No PD :</span> ${item2.NoPD}</td>
								<td style="text-align:left;"><span style="font-weight:bold;">Payment Amount (Rp) :</span> ${formatRupiah(item2.BLEAmount)}</td>
							</tr>
						`;
					}
				})
			});
			return rows;
		}
		return (
			`
				<table class="table table-striped table-hover " border="0" style="width:40%;">
					<thead style="background-color:#dbdbdb">
						<th>No</th>
						<th style="text-align:left">NO KO</th>
						<th style="text-align:left">Invoice</th>
						<th>Tgl Invoice</th>
						<th>Due Date</th>
						<th>Total Invoice (Rp)</th>
						<th>PPN</th>
						<th>No Faktur Pajak</th>
						<th>Tgl Faktur Pajak</th>
						<th>Payment Amount (Rp)</th>
					</thead>
					<tbody>
						${generateRows()}
					</tbody>
				</table>
			`
		);
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


	function getJSON_QqueryHutangKO() {

		var JobNo = $('.pdf_QHutangKO').attr('data-JobNo');
		var VendorId = $('.pdf_QHutangKO').attr('data-VendorId');
		var Status = $('.pdf_QHutangKO').attr('data-Status');

		// alert(JobNo+' - '+VendorId+' - '+Status);
		// return false;

		$.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
			return {
				"iStart": oSettings._iDisplayStart,
				"iEnd": oSettings.fnDisplayEnd(),
				"iLength": oSettings._iDisplayLength,
				"iTotal": oSettings.fnRecordsTotal(),
				"iFilteredTotal": oSettings.fnRecordsDisplay(),
				"iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
				"iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
			};
		};


		var table = $("#table_QHutangKO").DataTable({
			initComplete: function() {
				var api = this.api();
				$('#table_QHutangKO_filter input')
					.off('.DT')
					.on('keyup.DT', function(e) {
						if (e.keyCode == 13) {
							api.search(this.value).draw();
						}
					});
			},
			processing: true,
			serverSide: true,
			ajax: {
				"url": "<?= site_url('Laporan/Procurement/Query_HutangKO/getJSON_QqueryHutangKO') ?>",
				"type": "POST",
				"data": {
					'JobNo': JobNo,
					'VendorId': VendorId,
					'Status': Status
				}
			},
			columns: [{
					"data": "LedgerNo",
					"orderable": false,
					"searchable": false,
					"className": "text-center hidden",

				},
				{
					"class": 'dt-control bg-white color-palette',
					"data": "dataDetail",
					"orderable": false,
					"render": function(data, type, row) {
						return `<td>
							<div class="bg">
		                    <i class="fa fa-eye"></i>
							</div>
		                </td>`;
					},
				},
				{
					"data": "JobNo",
					"orderable": false,
					"searchable": false,
					"className": "text-left",

				},

				{
					"data": "JobNm",
					"orderable": false,
					"searchable": true,
					"className": "text-left",

				},

				{
					"data": "NoKO",
					"orderable": false,
					"searchable": true,
					"className": "text-left",

				},

				{
					"data": "TglKO",
					"orderable": false,
					"searchable": true,
					"className": "text-left",

				},

				{
					"data": "KategoriId",
					"orderable": false,
					"searchable": false,
					"className": "text-left",

				},

				{
					"data": "VendorNm",
					"orderable": false,
					"searchable": true,
					"className": "text-left",

				},

				{
					"data": "TotalKO",
					"orderable": false,
					"searchable": false,
					"className": "text-right",

				},

				{
					"data": "PaymentKO",
					"orderable": false,
					"searchable": false,
					"className": "text-right",

				},

				{
					"data": "RemainingKO",
					"orderable": false,
					"searchable": false,
					"className": "text-right",

				},

				{
					"data": "TotalInv",
					"orderable": false,
					"searchable": false,
					"className": "text-right",

				},

				{
					"data": "PaymentInv",
					"orderable": false,
					"searchable": false,
					"className": "text-right",

				},

				{
					"data": "RemainingInv",
					"orderable": false,
					"searchable": false,
					"className": "text-right",

				},
			],
			// order: [
			// [3, 'DESC']
			// ],
			// rowCallback: function(row, data, iDisplayIndex) {
			// 	var info = this.fnPagingInfo();
			// 	var page = info.iPage;
			// 	var length = info.iLength;
			// 	var index = page * length + (iDisplayIndex + 1);
			// 	$('td:eq(0)', row).html(index);
			// }
		});

		table.on('click', 'td.dt-control', function(e) {
			var tr = $(this).parents('tr');
			var row = table.row(tr);
			const data = row.data()
			$.ajax({
				url: '<?= site_url('Laporan/Procurement/Query_HutangKO/getDetail_QueryHutangKO') ?>',
				type: 'POST',
				dataType: 'HTML',
				data: {
					'JobNo': JobNo,
					'NoKO': data.NoKO
				},
				success: function(result) {
					const res = JSON.parse(result)

					if (row.child.isShown()) {
						row.child.hide();
						tr.removeClass('shown');
					} else {
						row.child(format(res)).show();
						tr.addClass('shown');
					}
				}
			})
		});




	}
</script>