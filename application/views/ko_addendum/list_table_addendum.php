<div class="col-sm-12">
	<div class="box">
		<div class="box-body">
			<div class=""></div>
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover" id="mytable" data-job="<?= $JobNo ?>">
					<thead>
						<tr>
							<th>No.</th>
							<th>#</th>
							<th>No. KO</th>
							<th>Tgl KO</th>
							<th>Penerima Kontrak</th>
							<th>Tipe KO</th>
							<th>Addendum/ <br> Revisi Ke</th>
							<th>Total KO (Rp)</th>
							<th>KO Approved By</th>
							<th>KO Approved ON</th>
							<th>Keterangan</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		var JobNo = $('#mytable').attr('data-job');

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
			"responsive": true,
			// "lengthChange": false,
			"autoWidth": false,
			scrollX: true,
			// scrollY: 200,
			scrollCollapse: true,
			scroller: true,
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
			processing: true,
			serverSide: true,
			ajax: {
				"url": "<?= site_url('Ko_addendum/dapat_list_ko_addendum') ?>",
				"type": "POST",
				"data": {
					'JobNo': JobNo
				}

			},
			columns: [{
				"data": "LedgerNo",
				"orderable": false,
				"searchable": false,
			},

			{
				"data": "aksi",
				"orderable": false,
				"searchable": false,
				"className": "text-center",
				"render" : function(data,type,row){
					return data+' '+row.detail_baru;
				}


			},

			{
				"data": "NoKO",
				"orderable": false,
				"searchable": true,
			},



			{
				"data": "TglKO",
				"orderable": false,
				"searchable": true,
			},

			{
				"data": "VendorNm",
				"orderable": false,
				"searchable": true,
			},


			{
				"data": "KategoriId",
				"orderable": false,
				"searchable": false,
			},

			{
				"data": "AddendumKe",
				"orderable": false,
				"searchable": false,
			},

			{
				"data": "SubTotal",
				"orderable": false,
				"searchable": false,
			},

			{
				"data": "ApprovedBy",
				"orderable": false,
				"searchable": false,

			},

			{
				"data": "TimeApproved",
				"orderable": false,
				"searchable": false,

			},

			{
				"data": "Keterangan",
				"orderable": false,
				"searchable": false,
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
	})
</script>