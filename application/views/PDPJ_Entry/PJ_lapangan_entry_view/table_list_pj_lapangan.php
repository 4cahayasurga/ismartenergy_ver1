<style>

	#mytable thead tr th{
		padding-top: 10px;
		padding-bottom: 10px;
		vertical-align: middle;
		font-size: 10px;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
		background-color: #2c3e50;
		color: white;
	}

	#mytable tbody tr td{
		padding-top: 7px;
		padding-bottom: 7px;
		vertical-align: middle;
		font-size: 10px;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}

	.table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
		background-color: #7f8c8d;
		color: white;
	}

</style>
<div class="col-12 ">
	<div class="box">
		<div class="box-header">
			<!-- <button class="btn btn-sm bg-gray" onclick="pencarian()">Refresh</button> -->

			<table width="100%" border="0" style="border-collapse:collapse; border-bottom: 1px solid silver; ">
				<tr>
					<th></th>
					<th class="text-right text-blue" style="width:15%;padding-bottom: 10px;">
						<?='SUBTOTAL PD = '.$SubTotal_TotalPD?>
					</th>
					<!-- <th style="text-align:center;width: 3%;padding-bottom: 10px;">||</th> -->
					<th class="text-right text-green " style="width:15%;padding-bottom: 10px;">
						<?='SUBTOTAL PJ = '.$SubTotal_TotalPJ?>
					</th>
				</tr>
			</table>
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table id="mytable" class="table table-striped table-hover table-condensed table-bordered" data-job="<?= $JobNo ?>" data-alokasi="<?= $Alokasi ?>" data-dari-tgl="<?= $dari_tgl ?>" data-sampai-tgl="<?= $sampai_tgl ?>" data-dari-tgl_pj="<?= $dari_tgl_pj ?>" data-sampai-tgl_pj="<?= $sampai_tgl_pj ?>" style="white-space: nowrap;">
					<thead>
						<tr>
							<th style="text-align:center;width:2%;">No</th>
							<th style="text-align:center;width:10% !important ; ">#</th>
							<th style="text-align:center;">No.PD</th>
							<th style="text-align:center;">Tgl.PD</th>
							<th style="text-align:center;">Tipe Form</th>
							<th style="text-align:center;">Deskripsi</th>
							<th style="text-align:center;">Total PD (Rp)</th>
							<th style="text-align:center;">No. PJ</th>
							<th style="text-align:center;">Tgl. PJ</th>
							<th style="text-align:center;">Total PJ (Rp)</th>
							<th style="text-align:center;">Approved By<br>(Lapangan)</th>
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
		data_json_pd_entry_pj_lapangan();



	})

	function data_json_pd_entry_pj_lapangan() {

		var JobNo = $('#mytable').attr('data-job');
		var Alokasi = $('#mytable').attr('data-alokasi');
		var dari_tgl = $('#mytable').attr('data-dari-tgl');
		var sampai_tgl = $('#mytable').attr('data-sampai-tgl');

		var dari_tgl_pj = $('#mytable').attr('data-dari-tgl_pj');
		var sampai_tgl_pj = $('#mytable').attr('data-sampai-tgl_pj');

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
				"url": "<?= site_url('PDPJ/PJ_lapangan_entry_controller/data_json_pj_lapangan') ?>",
				"type": "POST",
				"data": {
					'JobNo': JobNo,
					'Alokasi': Alokasi,
					'dari_tgl': dari_tgl,
					'sampai_tgl': sampai_tgl,

					'dari_tgl_pj': dari_tgl_pj,
					'sampai_tgl_pj': sampai_tgl_pj,
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
				"data": "aksi_pj",
				"orderable": false,
				"searchable": false,
				"className": "text-center",

				"render": function(data, type, row) {

					var teks_approve = 'APPROVE';
					var btn_teks_approve = 'primary';
					var flag_status_approve = 0;


					var warna = '';
					var ikon = '';


					if (row.PjApprovedByAK == '' || row.PjApprovedByAK == null) {

						teks_approve = 'APPROVE';
						btn_teks_approve = 'primary';
						flag_status_approve = 0;

						warna = 'style="background-color:#3498db;color:white;"';
						ikon = '<i class="fa fa-check"></i> ';

					}else{
						teks_approve = 'UNAPPROVE';
						btn_teks_approve = 'danger';
						flag_status_approve = 1;

						warna = 'style="background-color:#2c3e50;color:white;"';
						ikon = '<i class="fa fa-close"></i> ';

					}

					// return data+' '+'<button class="btn btn-sm approve_btn_'+row.LedgerNo+'" '+warna+' onclick="aksi_approve_pj('+row.LedgerNo+','+flag_status_approve+',\'' + row.NoPD + '\')"  title="'+teks_approve+'"  > '+ikon+' </button>'+' '+row.aksi_pj_js+' '+row.aksi_loading_pj;

					// return row.aksi_pj_js+' '+row.aksi_loading_pj+' '+'<button class="btn btn-sm approve_btn_'+row.LedgerNo+'" '+warna+' onclick="aksi_approve_pj('+row.LedgerNo+','+flag_status_approve+',\'' + row.NoPD + '\')"  title="'+teks_approve+'"  > '+ikon+' </button>'+' '+row.print_pd_new;
					return row.aksi_pj_js+' '+row.aksi_loading_pj+' '+'<button class="btn btn-sm approve_btn_'+row.LedgerNo+'" '+warna+' onclick="aksi_approve_pj('+row.LedgerNo+','+flag_status_approve+',\'' + row.NoPD + '\')"  title="'+teks_approve+'"  > '+ikon+' </button>'+' '+row.print_PD_terbaru;
					 // var btn_loading_pj = '<button class="btn btn-sm btn-danger huruf_ukuran disabled" id="loading_pj_'+row.LedgerNo+'" style="display: none;"><i class="fa fa-spinner fa-spin"></i></button>';



					 // return data+' '+'<button class="btn btn-sm approve_btn_'+row.LedgerNo+'" '+warna+' onclick="aksi_approve_pj('+row.LedgerNo+','+flag_status_approve+',\'' + row.NoPD + '\')"  title="'+teks_approve+'"  > '+ikon+' </button>'+' '+row.aksi_pj_js+'  '+btn_loading_pj;

					// return data+' '+'<button type="button" onclick="aksi_approve_pj('+row.LedgerNo+','+flag_status_approve+',\'' + row.NoPD + '\')" class="btn btn-'+btn_teks_approve+' btn-xs approve_btn_'+row.LedgerNo+'"  >'+teks_approve+'</button>';
				}

			},

			{
				"data": "NoPD",
				"orderable": true,
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
				"data": "TipeForm",
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
				"data": "NoPJ",
				"orderable": true,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "TglPJ",
				"orderable": false,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "TotalPJ",
				"orderable": false,
				"searchable": true,
				"className": "text-right",

			},

			{
				"data": "PjApprovedByAK",
				"orderable": false,
				"searchable": false,
				"className": "text-left",
				

			},

			{
				"data": "PjTimeApprovedAK",
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