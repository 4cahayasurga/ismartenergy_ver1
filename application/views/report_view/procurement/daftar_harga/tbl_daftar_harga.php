<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-body">
				
				<div class="table-responsive">

					<table width="100%" border="0" style="margin-bottom:12px;">
						<tr>
							<!-- <td class="text-right">
								<button type="button" class="btn btn-sm bg-gray">SUBTOTAL : <?=($this->session->flashdata('SubTotal')) ? UangBiasa($this->session->flashdata('SubTotal')) : UangBiasa(0)  ?></button>
							</td> -->
							<td class="text-left">
								<a  href="<?=site_url('MyReport/Procurement/DaftarHarga/PDF_dfthrg')?>" onclick="return false;" class='btn btn-sm btn-danger' target="_blank"  disabled>Export PDF <i class="fa fa-print"></i></a>

								<a href="<?=site_url('MyReport/Procurement/DaftarHarga/ExpExcel_DaftarHarga/1')?>" class='btn btn-sm btn-success' target="_blank" >Export Excel&nbsp;&nbsp; <i class="fa fa-upload"></i></a>


							</td>
						</tr>
					</table>
					<table class="table table-bordered table-striped table-hover table-condense pengaturan_tbl" id="mytable" style="font-size:10px;">
						<thead>
							<tr>
								<th class="text-center">No</th>
								<th style="width:30%">Uraian</th>
								<th>U.O.M</th>
								<th class="text-right">Vol</th>
								<th class="text-right">Harga Satuan (Rp)</th>
								<th class="text-right">Total Harga (Rp)</th>
								<th>Vendor</th>
								<th>No KO</th>
								<th>Tgl KO</th>
								<th>Job Name</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>

				</div>

			</div>
		</div>

	</div>
</div>

<script type="text/javascript">


	function list_harga_json() {

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
			"pageLength": 100,
			"lengthMenu": [100,200,300,500],
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
				"url": "<?= site_url('MyReport/Procurement/DaftarHarga/list_harga_json') ?>",
				"type": "POST",
				"data": {
					'flag' : 'json_harga'

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
				"data": "Uraian",
				"orderable": false,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "Uom",
				"orderable": false,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "Vol",
				"orderable": false,
				"searchable": false,
				"className": "text-right",

			},

			{
				"data": "HrgSatuan",
				"orderable": false,
				"searchable": false,
				"className": "text-right",

			},

			{
				"data": "TotalHarga",
				"orderable": false,
				"searchable": false,
				"className": "text-right",

			},

			{
				"data": "VendorNm",
				"orderable": false,
				"searchable": false,
				"className": "text-left",

			},

			{
				"data": "NoKO",
				"orderable": false,
				"searchable": false,
				"className": "text-left",

			},

			{
				"data": "TglKO",
				"orderable": false,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "JobNm",
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