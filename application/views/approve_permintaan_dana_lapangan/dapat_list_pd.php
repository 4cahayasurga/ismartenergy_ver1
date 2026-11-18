<style>
	/*#mytable th {
		font-size: 10px !important;
		font-family: Arial, Helvetica, sans-serif;
	}*/

	/*#mytable tbody td {
		font-size: 10px !important;
		font-family: Arial, Helvetica, sans-serif;
	}*/
	#mytable thead tr th{
		padding-top: 10px;
		padding-bottom: 10px;
		vertical-align: middle;
		font-size: 10px;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}

	#mytable tbody tr td{
		padding-top: 7px;
		padding-bottom: 7px;
		vertical-align: middle;
		font-size: 10px;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}
</style>
<div class="col-12 ">
	<div class="box">
		<div class="box-header">

			<button class="btn btn-sm bg-gray" onclick="pencarian()">Refresh</button>
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table id="mytable" class="table table-striped table-hover table-condensed table-bordered" data-job="<?= $JobNo ?>" data-alokasi="<?= $Alokasi ?>" data-dari-tgl="<?= $dari_tgl ?>" data-sampai-tgl="<?= $sampai_tgl ?>" data-UrlPengenal="<?=$UrlPengenal?>">
					<thead>
						<tr>
							<th style="text-align:center;width:2%;">No</th>
							<th style="text-align:center;width:10% !important ; ">#</th>
							<th style="text-align:left;">No.PD</th>
							<th style="text-align:left;">No.REF Lapangan</th>
							<th style="text-align:left;width: 7%;">Tgl.PD</th>
							<th style="text-align:left;">Alokasi</th>
							<th style="text-align:left;width: 6%;">Tipe Form</th>
							<th style="text-align:left;">No.KO</th>
							<th style="text-align:left;">Deskripsi</th>
							<th style="text-align:right;width: 9%;">Total PD (Rp)</th>
							<th style="text-align:left;">Approved By (Lapangan)</th>
							<th style="text-align:left;width: 8%;">Approved On</th>
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
		data_json_pd_keuangan();



	})

	function data_json_pd_keuangan() {

		var JobNo = $('#mytable').attr('data-job');
		var Alokasi = $('#mytable').attr('data-alokasi');
		var dari_tgl = $('#mytable').attr('data-dari-tgl');
		var sampai_tgl = $('#mytable').attr('data-sampai-tgl');
		var UrlPengenal = $('#mytable').attr('data-UrlPengenal');

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


		$("#mytable").dataTable({
			initComplete: function() {
				var api = this.api();
				$('#mytable_filter input')
				.off('.DT')
				.on('keyup.DT', function(e) {
					if (e.keyCode == 13) {
						api.search(this.value).draw();
					}
				});
			},
			pageLength: 50,
			lengthMenu: [20,50,100,200,500,1000 ],
			processing: true,
			serverSide: true,
			ajax: {
				"url": "<?= site_url('Approve_permintaan_dana_lapangan/dapat_list_pd_json') ?>",
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

			// {
			// 		"data": "aksi_baru",
			// 		"orderable": false,
			// 		"searchable": false,
			// 		"className": "text-center",

			// 		"render": function(data, type, row) {
			// 			var status_pd = row['ApprovedByKT'];
			// 			var value_status_pd = 0;
			// 			if (status_pd == null) {
			// 				status_pd = 'Approve';
			// 				value_status_pd = 0;
			// 			} else {
			// 				status_pd = 'UnApprove';
			// 				value_status_pd = 1;
			// 			}


			// 			var isi = '<a href="javascript:void(0)" class="btn btn-xs btn-link" onclick="ubah_status(' + row.LedgerNo + ',' + value_status_pd + ',\'' +row.NoPD+ '\')" style="text-decoration:underline;font-size:10px !important;">' + status_pd + '</a>';

			// 			return data+' '+isi;
			// 			// return data;
			// 		}

			// 	},


			{
				"data": "ViewPDLapangan",
				"orderable": false,
				"searchable": false,
				"className": "text-center",

				"render": function(data, type, row) {

					var ApprovedByKT = "APPROVE";
					var warna = '';
					var ikon = '';

					var status_pd = row['ApprovedByKT'];
					var value_status_pd = 0;
					if (status_pd == null) {
						status_pd = 'Approve';
						value_status_pd = 0;
					} else {
						status_pd = 'UnApprove';
						value_status_pd = 1;
					}




					if (row.ApprovedByKT == null || row.ApprovedByKT == ''){
						var ApprovedByKT = "APPROVE";
						var warna = 'style="background-color:#3498db;color:white;"';
						var ikon = '<i class="fa fa-check"></i> ';

					}else{
						var ApprovedByKT = "UNAPPROVE";
						var warna = 'style="background-color:#2c3e50;color:white;"';
						var ikon = '<i class="fa fa-close"></i> ';
					}

					var ApprovedByKK = row.ApprovedByKK;

					var btn_approve = '<button class="btn btn-sm" '+warna+' onclick="ubah_status(' + row.LedgerNo + ',' + value_status_pd+',\'' +row.NoPD+ '\',\''+row.ApprovedByKK+'\')"  title="'+ApprovedByKT+'"  > '+ikon+' </button>';


					var btn_detail = (row.NoRef == '' || row.NoRef == null  ) ? row.ViewPDKeuangan : data;
					
					// if (row.NoRef == null || row.NoRef == '') {
					// 	return row.ViewPDKeuangan+' '+btn_approve+' '+row.print_pd_new;
					// }else{
					// 	return data+' '+btn_approve+' '+row.print_pd_new;
					// }
					// return data+' '+btn_approve+' '+row.print_pd_new;
					return btn_detail+' '+btn_approve;

					

					// return data+' '+btn_approve+' '+row.pdlap_pdf;
					
					// return '';
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
				"orderable": false,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "TglPD",
				"orderable": false,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "Alokasi",
				"orderable": false,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "TipeForm",
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
				"data": "Deskripsi",
				"orderable": false,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "TotalPD",
				"orderable": false,
				"searchable": true,
				"className": "text-right",

			},

			{
				"data": "ApprovedByKT",
				"orderable": false,
				"searchable": false,
				"className": "text-left",
				render : function(data,type,row){

					data = (data == null || data == '' ) ? '' : data;
					return data;
				}

			},

			{
				"data": "TimeApprovedKT",
				"orderable": false,
				"searchable": false,
				"className": "text-left",

			},



			],
			order: [
			[0, 'desc']
			],
			rowCallback: function(row, data, iDisplayIndex) {
				var info = this.fnPagingInfo();
				var page = info.iPage;
				var length = info.iLength;
				var index = page * length + (iDisplayIndex + 1);
				$('td:eq(0)', row).html(index);
			}
		});


}




</script>