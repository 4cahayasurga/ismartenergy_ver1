<style type="text/css">
	#tbl_saya thead th{
		border-top:1px solid silver !important;
		border-bottom:1px solid silver !important;
		padding-top:10px !important;
		padding-bottom:10px !important;
		font-size: 10px;
	}

	#tbl_saya tbody td{
		border-bottom:1px solid silver !important;
		padding-top:9px !important;
		padding-bottom:9px !important;
		font-size: 9px !important;
	}
</style>
<div class="row">
	<div class="col-sm-12">
		<div class="box box-success">
			<div class="box-header with-border">
				<button type="button" class="btn btn-success btn-sm huruf_10" onclick="tambah_baru()">Tambah Data Baru <i class="fa fa-plus"></i></button>
			</div>
			<div class="box-body">
				
				<div class="table-responsive">
					<table class="table table-hover table-condensed table-striped table-borderless huruf_10"  id="tbl_saya" data-JobNo="<?=$JobNo?>" >
						<thead>
							<tr class="huruf_10 bg-gray-active" >
								<th></th>
								<th>No. KO</th>
								<th>Vendor</th>
								<th>No. Invoice</th>
								<th>Tgl Invoice</th>
								<th>Jatuh Tempo</th>
								<th>PPN (Rp)</th>
								<th>PPH (Rp)</th>
								<th>Total Invoice(Rp)</th>
								<th>No. Faktur Pajak</th>
								<th>Tgl Faktur Pajak</th>
								<th>Time Entry</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>

			</div>
			<div class="overlay" id="tbl_loading" style="display: none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		panggil_json_tbl();
	});

	function panggil_json_tbl(){

		var JobNo = $('#tbl_saya').attr('data-JobNo');

		$("#tbl_saya").dataTable({
			initComplete: function() {
				var api = this.api();
				$('#tbl_saya_filter input')
				.off('.DT')
				.on('keyup.DT', function(e) {
					if (e.keyCode == 13) {
						api.search(this.value).draw();
					}
				});
			},
			language: {
				'searchPlaceholder': "Ketik Dan Enter",
				"search":         "Pencarian : ",
				"processing": '<i class="fa fa-refresh fa-spin"></i>',
				'paginate': {
					'previous': '<i class="fa fa-chevron-left"></i>',
					'next': '<i class="fa fa-chevron-right"></i>',
				},
				"aria": {
					"sortAscending":  ": activate to sort column ascending",
					"sortDescending": ": activate to sort column descending"
				},
				"zeroRecords": "data pencarian tidak cocok",
				"emptyTable":  "data tidak ditemukan",
				"lengthMenu":  "Tampilkan _MENU_ Data",
				"info": "Menampilkan _START_ to _END_ of _TOTAL_ Data",

			},
			pageLength: 50,
			lengthMenu: [10,20,50,100,200,500,1000 ],
			processing: true,
			serverSide: true,
			ajax: {
				"url": "<?= site_url('Entry/Procurement/Invoice/panggil_json_tbl') ?>",
				"type": "POST",
				"data": {JobNo : JobNo},
			},
			columns: [

			{
				"data": "hapus_invoice",
				"orderable": false,
				"searchable": false,
				// "visible" : false,
				"className": "text-left",

			},

			{
				"data": "NoKO",
				"orderable": false,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "VendorNm",
				"orderable": false,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "InvNo",
				"orderable": false,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "InvDate",
				"orderable": true,
				"searchable": false,
				"className": "text-left",

			},

			{
				"data": "DueDate",
				"orderable": false,
				"searchable": false,
				"className": "text-left",

			},

			{
				"data": "PPN",
				"orderable": false,
				"searchable": false,
				"className": "text-left",

			},

			{
				"data": "PPH",
				"orderable": false,
				"searchable": false,
				"className": "text-left",

			},

			{
				"data": "Total",
				"orderable": false,
				"searchable": false,
				"className": "text-left",

			},

			{
				"data": "FPNo",
				"orderable": false,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "FPDate",
				"orderable": false,
				"searchable": false,
				"className": "text-left",

			},

			{
				"data": "TimeEntry",
				"orderable": false,
				"searchable": false,
				"className": "text-left",

			},


			],
			order: [4, 'desc'],

		});

	}

	
	
</script>