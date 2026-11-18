<style>
	#mytable th {
		font-size: 10px !important;
		font-family: Arial, Helvetica, sans-serif;
	}

	#mytable tbody td {
		font-size: 10px !important;
		font-family: Arial, Helvetica, sans-serif;
	}
</style>
<div class="col-12 ">
	<div class="box">
		<div class="box-header">
			<a href="<?=$atur_url?>" class="btn btn-sm btn-danger">Tambah Data &nbsp;<i class="fa fa-plus"></i></a>
			<button class="btn btn-sm btn-secondary" onclick="pencarian()">Refresh</button>
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table id="mytable" class="table table-striped table-hover table-condensed table-bordered display" data-job="<?= $JobNo ?>" data-alokasi="<?= $Alokasi ?>" data-dari-tgl="<?= $dari_tgl ?>" data-sampai-tgl="<?= $sampai_tgl ?>" data-UrlPengenal="<?=$UrlPengenal?>"cellspacing="0" width="100%"  >
					<thead>
						<tr>
							<th style="text-align:center;width:10px;">No</th>
							<th style="text-align:center;width:10% !important ; ">#</th>
							<th style="text-align:center;">No.PD</th>
							<th style="text-align:center;">No.REF Lapangan</th>
							<th style="text-align:center;">Tgl.PD</th>
							<th style="text-align:center;">Alokasi</th>
							<th style="text-align:center;">Tipe Form</th>
							<th style="text-align:center;">No.KO</th>
							<th style="text-align:center;">Deskripsi</th>
							<th style="text-align:center;">Total PD (Rp)</th>
							<th style="text-align:center;">Approved By (Lapangan)</th>
							<th style="text-align:center;">Approved On</th>
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


<script>
	$(document).ready(function() {



		var JobNo = $('#mytable').attr('data-job');
		var Alokasi = $('#mytable').attr('data-alokasi');
		var dari_tgl = $('#mytable').attr('data-dari-tgl');
		var sampai_tgl = $('#mytable').attr('data-sampai-tgl');

		var UrlPengenal = $('#mytable').attr('data-UrlPengenal');

		$('#mytable thead th').each(function () {
			var title = $(this).text();
			$(this).html(title+' <input type="text" class="col-search-input" placeholder="Search ' + title + '" />');
		});

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

		var table = $("#mytable").dataTable({
			pageLength: 50,
			lengthMenu: [20,50,100,200,500,1000 ],
			processing: true,
			serverSide: true,
			ajax: {
				"url": "<?= site_url('Permintaan_dana_lapangan/dapat_list_pd_json') ?>",
				"type": "POST",
				"data": {
					'JobNo': JobNo,
					'Alokasi': Alokasi,
					'dari_tgl': dari_tgl,
					'sampai_tgl': sampai_tgl,
					'UrlPengenal' : UrlPengenal
				}
			},
			columns: [


			{
				"data": "LedgerNo",
				"orderable": true,
				"searchable": false,
				"className": "text-center",

			},


			{
				"data": "ViewPDLapangan",
				"orderable": false,
				"searchable": false,
				"className": "text-center",

				"render": function(data, type, row) {

					var ApprovedByAK = "APPROVE";
					var warna = '';
					var ikon = '';


					if (row.ApprovedByAK == null || row.ApprovedByAK == ''){
						var ApprovedByAK = "APPROVE";
						var warna = 'style="background-color:#3498db;color:white;"';
						var ikon = '<i class="fa fa-check"></i> ';

					}else{
						var ApprovedByAK = "UNAPPROVE";
						var warna = 'style="background-color:#2c3e50;color:white;"';
						var ikon = '<i class="fa fa-close"></i> ';
					}

					var btn_approve = '<button class="btn btn-sm" '+warna+' onclick="btn_Approve('+row.LedgerNo+')"  title="'+ApprovedByAK+'"  > '+ikon+' </button>';
					return data+' '+btn_approve+' '+row.print_pd_new;

				}

			},

			{
				"data": "NoPD",
				"orderable": true,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "NoRef",
				"orderable": true,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "TglPD",
				"orderable": true,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "Alokasi",
				"orderable": true,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "TipeForm",
				"orderable": true,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "NoKO",
				"orderable": true,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "Deskripsi",
				"orderable": true,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "TotalPD",
				"orderable": true,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "ApprovedByAK",
				"orderable": false,
				"searchable": false,
				"className": "text-left",

			},

			{
				"data": "TimeApprovedAK",
				"orderable": false,
				"searchable": false,
				"className": "text-left",

			},



			],
			order: [
			[2, 'desc']
			],
			rowCallback: function(row, data, iDisplayIndex) {
				var info = this.fnPagingInfo();
				var page = info.iPage;
				var length = info.iLength;
				var index = page * length + (iDisplayIndex + 1);
				$('td:eq(0)', row).html(index);
			}
		});

		table.api().columns().every(function () {
			var table = this;
			$('input', this.header()).on('keyup change', function () {
				if (table.search() !== this.value) {
					table.search(this.value).draw();
				}
			});
		});

	});

	// function data_json_pd_keuangan() {

	// 	var JobNo = $('#mytable').attr('data-job');
	// 	var Alokasi = $('#mytable').attr('data-alokasi');
	// 	var dari_tgl = $('#mytable').attr('data-dari-tgl');
	// 	var sampai_tgl = $('#mytable').attr('data-sampai-tgl');

	// 	var UrlPengenal = $('#mytable').attr('data-UrlPengenal');

	// 	$.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
	// 		return {
	// 			"iStart": oSettings._iDisplayStart,
	// 			"iEnd": oSettings.fnDisplayEnd(),
	// 			"iLength": oSettings._iDisplayLength,
	// 			"iTotal": oSettings.fnRecordsTotal(),
	// 			"iFilteredTotal": oSettings.fnRecordsDisplay(),
	// 			"iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
	// 			"iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
	// 		};
	// 	};


	// 	$("#mytable").dataTable({
	// 		pageLength: 50,
	// 		lengthMenu: [20,50,100,200,500,1000 ],
	// 		processing: true,
	// 		serverSide: true,
	// 		ajax: {
	// 			"url": "<?= site_url('Permintaan_dana_lapangan/dapat_list_pd_json') ?>",
	// 			"type": "POST",
	// 			"data": {
	// 				'JobNo': JobNo,
	// 				'Alokasi': Alokasi,
	// 				'dari_tgl': dari_tgl,
	// 				'sampai_tgl': sampai_tgl,
	// 				'UrlPengenal' : UrlPengenal
	// 			}
	// 		},
	// 		columns: [


	// 		{
	// 			"data": "LedgerNo",
	// 			"orderable": true,
	// 			"searchable": false,
	// 			"className": "text-center",

	// 		},


	// 		{
	// 			"data": "ViewPDLapangan",
	// 			"orderable": false,
	// 			"searchable": false,
	// 			"className": "text-center",

	// 			"render": function(data, type, row) {

	// 				var ApprovedByAK = "APPROVE";
	// 				var warna = '';
	// 				var ikon = '';


	// 				if (row.ApprovedByAK == null || row.ApprovedByAK == ''){
	// 					var ApprovedByAK = "APPROVE";
	// 					var warna = 'style="background-color:#3498db;color:white;"';
	// 					var ikon = '<i class="fa fa-check"></i> ';

	// 				}else{
	// 					var ApprovedByAK = "UNAPPROVE";
	// 					var warna = 'style="background-color:#2c3e50;color:white;"';
	// 					var ikon = '<i class="fa fa-close"></i> ';
	// 				}

	// 				var btn_approve = '<button class="btn btn-sm" '+warna+' onclick="btn_Approve('+row.LedgerNo+')"  title="'+ApprovedByAK+'"  > '+ikon+' </button>';
	// 				return data+' '+btn_approve+' '+row.print_pd_new;

	// 			}

	// 		},

	// 		{
	// 			"data": "NoPD",
	// 			"orderable": true,
	// 			"searchable": true,
	// 			"className": "text-left",

	// 		},

	// 		{
	// 			"data": "NoRef",
	// 			"orderable": true,
	// 			"searchable": true,
	// 			"className": "text-left",

	// 		},

	// 		{
	// 			"data": "TglPD",
	// 			"orderable": true,
	// 			"searchable": true,
	// 			"className": "text-left",

	// 		},

	// 		{
	// 			"data": "Alokasi",
	// 			"orderable": true,
	// 			"searchable": true,
	// 			"className": "text-left",

	// 		},

	// 		{
	// 			"data": "TipeForm",
	// 			"orderable": true,
	// 			"searchable": true,
	// 			"className": "text-left",

	// 		},

	// 		{
	// 			"data": "NoKO",
	// 			"orderable": true,
	// 			"searchable": true,
	// 			"className": "text-left",

	// 		},

	// 		{
	// 			"data": "Deskripsi",
	// 			"orderable": true,
	// 			"searchable": true,
	// 			"className": "text-left",

	// 		},

	// 		{
	// 			"data": "TotalPD",
	// 			"orderable": true,
	// 			"searchable": true,
	// 			"className": "text-left",

	// 		},

	// 		{
	// 			"data": "ApprovedByAK",
	// 			"orderable": false,
	// 			"searchable": false,
	// 			"className": "text-left",

	// 		},

	// 		{
	// 			"data": "TimeApprovedAK",
	// 			"orderable": false,
	// 			"searchable": false,
	// 			"className": "text-left",

	// 		},



	// 		],
	// 		order: [
	// 		[2, 'desc']
	// 		],
	// 		rowCallback: function(row, data, iDisplayIndex) {
	// 			var info = this.fnPagingInfo();
	// 			var page = info.iPage;
	// 			var length = info.iLength;
	// 			var index = page * length + (iDisplayIndex + 1);
	// 			$('td:eq(0)', row).html(index);
	// 		}
	// 	});


	// }




</script>