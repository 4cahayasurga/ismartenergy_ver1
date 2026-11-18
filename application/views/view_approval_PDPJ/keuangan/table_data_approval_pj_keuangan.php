<style type="text/css">
	#table_pj th {
		font-size: 12px !important;
		font-family: "Times New Roman", Times, serif;
		text-align: center;
	}

/*	#table_pj tbody td {
		font-size: 10px !important;
		font-family: Arial, Helvetica, sans-serif;
	}*/
</style>
<div class="row">
	<div class="col-sm-12">
		<div class="box">
			<div class="box-body">

				<div class="table-responsive">

					<table class="table table-striped table-hover table-condensed table-bordered" id="table_pj" data-JobNo="<?=$JobNo?>" data-Alokasi="<?=$Alokasi?>"  >
						<thead>
							<tr style="background-color:#95a5a6;">
								<th>No. PD</th>
								<th>Tgl PD</th>
								<th>Tipe Form</th>
								<th>Deskripsi</th>
								<th>Total PD (Rp)</th>
								<th>No. PJ</th>
								<th>Tgl PJ</th>
								<th>Total PJ (Rp)</th>
								<th>Approved By (Keuangan)</th>
								<th>Approved On</th>
								<th width="200px">#</th>

							</tr>
						</thead>
						<tbody></tbody>
						<tfoot >
							<tr style="background-color: #95a5a6;">
								<th colspan="4" style="text-decoration: italic;text-align: right !important;color:black;">Total (PD) :</th>
								<th style="color:black;text-align: right;"><?=UbahGabunganUang($TotalPD)?></th>
								<th></th>
								<th style="color:black;width: 70px !important;text-align: right;">Total (PJ) :</th>
								<th style="color:black;text-align: right;"><?=UbahGabunganUang($TotalPJ)?></th>
								<th></th>
								<th></th>
								<th></th>
								
							</tr>
						</tfoot>
					</table>

					

				</div>

			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){

		var JobNo = $('#table_pj').attr('data-JobNo');
		var Alokasi = $('#table_pj').attr('data-Alokasi');

		// alert(Alokasi);

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


		$("#table_pj").dataTable({
			initComplete: function() {
				var api = this.api();
				$('#table_pj_filter input')
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
				"url": "<?= site_url('Approval_PDPJ/Approval_PJ_Keuangan/dapatkan_data_json_approval_pj_keuangan') ?>",
				"type": "POST",
				"data": {
					'approval_pj_keuangan' : 'approval_pj_keuangan',
					'JobNo': JobNo,
					'Alokasi': Alokasi,

				}
			},
			columns: [


			{
				"data": "NoPD",
				"orderable": false,
				"searchable": true,
				"className": "text-left",

			},
			{
				"data": "TglPD",
				"orderable": false,
				"searchable": false,
				"className": "text-left",

			},

			{
				"data": "TipeForm",
				"orderable": false,
				"searchable": false,
				"className": "text-left",

			},

			{
				"data": "Deskripsi",
				"orderable": false,
				"searchable": false,
				"className": "text-left",

			},

			{
				"data": "TotalPD",
				"orderable": false,
				"searchable": false,
				"className": "text-right",

			},

			{
				"data": "NoPJ",
				"orderable": false,
				"searchable": false,
				"className": "text-left",

			},

			{
				"data": "TglPJ",
				"orderable": false,
				"searchable": false,
				"className": "text-left",

			},

			{
				"data": "TotalPJ",
				"orderable": false,
				"searchable": false,
				"className": "text-right",

			},

			{
				"data": "PjApprovedByKK",
				"orderable": false,
				"searchable": false,
				"className": "text-left",

			},


			{
				"data": "PjTimeApprovedKT",
				"orderable": false,
				"searchable": false,
				"className": "text-left",

			},

			{
				"data": "aksi_pj",
				"orderable": false,
				"searchable": false,
				"className": "text-center",
				"render" : function(data,type,row){

					var teks_approve = 'APPROVE';
					var btn_teks_approve = 'primary';
					var flag_status_approve = 0;


					if (row.PjApprovedByKK == '' || row.PjApprovedByKK == null) {


						teks_approve = 'APPROVE';
						btn_teks_approve = 'primary';
						flag_status_approve = 0;
					}else{
						teks_approve = 'UNAPPROVE';
						btn_teks_approve = 'danger';
						flag_status_approve = 1;
					}

					return data+' '+'<button type="button" onclick="aksi_approve_pj_keuangan('+row.LedgerNo+','+flag_status_approve+',\'' + row.NoPD + '\')" class="btn btn-'+btn_teks_approve+' btn-xs approve_btn_'+row.LedgerNo+'"  >'+teks_approve+'</button>';
				}

			},




			],
			order: [
			[0, 'DESC']
			],
		});

	})



	
</script>