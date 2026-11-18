<style type="text/css">
	#table_saya{
		font-size: 10px !important;
		font-family: Arial, Helvetica, sans-serif !important;
	}
	#table_saya thead tr th{
		text-align: center !important;
		padding-bottom: 9px;
		vertical-align: top;

	}

	#table_saya tbody tr td{
		padding-top: 9px !important;
		padding-bottom: 9px !important;
		font-size: 10px !important;
		font-family: Arial, Helvetica, sans-serif !important;
		cursor: pointer !important;
		vertical-align: top !important;
	}

	.table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
		background-color: #bdc3c7;
		color: black;
	}

	.ukur{
		font-size: 10px !important;
		font-family: Arial, Helvetica, sans-serif !important;
	}

</style>

<table width="100%" border="0" style="border-collapse:collapse;margin-bottom: 10px !important;">
	<tr>
		<td width="8%">
			<button class="btn btn-sm btn-success tambah_PD" id="tambah_PD" ><i class="fa fa-plus"></i> TAMBAH PD</button>
		</td>
		<td width="10%">
			<button class="btn btn-sm btn-warning tambah_PDRKD" id="tambah_PDRKD"><i class="fa fa-plus"></i> TAMBAH PDRKD</button>
		</td>
		<td></td>
	</tr>
</table>

<div class="box box-success">
	<div class="box-body">
		<div class="table-responsive">

			<table class="table table-hover table-striped table-condensed" width="100%" id="table_saya" data-LedgerNo="<?=$LedgerNo?>" data-JobNo="<?=$JobNo?>" data-Alokasi="<?=$Alokasi?>" data-dari_tgl="<?=$dari_tgl?>" data-sampai_tgl="<?=$sampai_tgl?>" >
				<thead>
					<tr class="bg-light-blue-active color-palette">
						<th></th>
						<th>#</th>
						<th>No. PD</th>
						<th>No. Ref (Lapangan)</th>
						<th>Tgl PD</th>
						<th>Alokasi</th>
						<th>Tipe Form</th>
						<th>No KO</th>
						<th>Total PD (Rp)</th>
						<th>Approved By (Lapangan)</th>
						<th>Approved On</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>

		</div>
	</div>
</div>


<script type="text/javascript">

	function cekJob(from=''){

		var JobNo = $('#table_saya').attr('data-JobNo');
		var LedgerNo = $('#table_saya').attr('data-LedgerNo');
		var Alokasi = $('#table_saya').attr('data-Alokasi');

		var dari_tgl = ($('#table_saya').attr('data-dari_tgl') == '' || $('#table_saya').attr('data-dari_tgl') == null  ) ? 0 : $('#table_saya').attr('data-dari_tgl') ;
		var sampai_tgl = ($('#table_saya').attr('data-sampai_tgl') == '' || $('#table_saya').attr('data-sampai_tgl') == null  ) ? 0 : $('#table_saya').attr('data-sampai_tgl') ;

		// alert(from+' - '+JobNo+' - '+LedgerNo+' - '+Alokasi+' - '+dari_tgl+' - '+sampai_tgl);
		// return false;




		jQuery.ajax({
			url: '<?=site_url("MY_PD/PD_LAPANGAN/cekJob")?>',
			type: 'POST',
			dataType: 'json',
			data: {
				'JobNo' : JobNo,
				'from' : from
			},
			cache : false,
			beforeSend : function(){
			},
			complete: function(xhr, textStatus) {

			},
			success: function(data, textStatus, xhr) {

				if (data.status !='sukses') {
					swal.fire('Info!',data.pesan,'info');
					return false;
				}

				var url_saya = "<?=site_url('MY_PD/PD_Lapangan/PD_ADD')?>"+'/'+from+'/'+LedgerNo+'/'+JobNo+'/'+Alokasi+'/'+dari_tgl+'/'+sampai_tgl;
				// window.open(url_saya);
				window.location.href=url_saya;

				// alert(url_saya);

				// $JenisPD='', $LedgerNo='', $NoPD='', $JobNo='', $Alokasi='', $dari_tgl='',$sampai_tgl=''

				// alert(data.status);
			},
			error: function(xhr, textStatus, errorThrown) {
				swal.fire('Error!','Terjadi Kesalahan','error');
				return false;
			}
		});

	}


	$(document).ready(function(){

		$('#tambah_PD').click(function(){
			cekJob('tambah_PD');
			// alert('aaa');
		});
		$('#tambah_PDRKD').click(function(){
			cekJob('tambah_PDRKD');
			// alert('aaa');
		});

		

		var JobNo = $('#table_saya').attr('data-JobNo');
		var Alokasi = $('#table_saya').attr('data-Alokasi');
		var dari_tgl = $('#table_saya').attr('data-dari_tgl');
		var sampai_tgl = $('#table_saya').attr('data-sampai_tgl');

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

		var table = $("#table_saya").dataTable({
			initComplete: function() {
				var api = this.api();
				$('#table_saya_filter input')
				.off('.DT')
				.on('input.DT', function() {
					api.search(this.value).draw();
				});
			},
			oLanguage: {
				sProcessing: "<i class='fa fa-refresh fa-spin'></i>"
			},
			pageLength: 50,
			lengthMenu: [20,50,100,200,500,1000 ],
			processing: true,
			serverSide: true,
			ajax: {
				"url": "<?=base_url().'MY_PD/PD_LAPANGAN/get_data_PD'?>", 
				"type": "POST",
				"cache" : false,
				"data" : {
					'JobNo': JobNo,
					'Alokasi': Alokasi,
					'dari_tgl': dari_tgl,
					'sampai_tgl': sampai_tgl,
				},

			},
			columns: [
			{"data": "LedgerNo", "orderable": false, "searchable": false, "className": "text-left",'visible' : false},
			{
				"data": "btn_approve", 
				"orderable": false, 
				"searchable": false, 
				"className": "text-center",
				"render" : function(data,type,row){

					var btn_pdf = '';
					var teks_approve = (row.ApprovedByAK == null || row.ApprovedByAK == '') ? 'APPROVE' : 'UNAPPROVE';
					var icon_approve = (row.ApprovedByAK == null || row.ApprovedByAK == '') ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>';

					var warna_app = (row.ApprovedByAK == null || row.ApprovedByAK == '') ? 'text-green text-bold' : 'text-blue text-bold';

					var btn_approve = '<button class="btn btn-xs btn-link ukur aksi_approve '+warna_app+' " title="'+teks_approve+'" onClick="aksi_approve('+row.LedgerNo+')" id="aksi_approve_'+row.LedgerNo+'" data-JobNo="'+row.JobNo+'" data-Alokasi="'+row.Alokasi+'" data-ApprovedByKT="'+row.cek_KT+'" data-ApprovedByKK="'+row.cek_KK+'" data-type_app="'+teks_approve+'" data-NoPD="'+row.NoPD+'" ><u>'+teks_approve+'</u></button> <button class="btn btn-sm btn-danger  disabled" id="loading_row_'+row.LedgerNo+'"  style="display: none;"><i class="fa fa-refresh fa-spin"></i> </button>';

					btn_pdf = '<button class="btn btn-link btn-xs ukur btn_pdf text-red" onClick="btn_pdf('+row.LedgerNo+')" id="btn_pdf_'+row.LedgerNo+'" data-JobNo="'+row.JobNo+'" data-Alokasi="'+row.Alokasi+'"  data-NoPD="'+row.NoPD+'" data-ApprovedByAK="'+teks_approve+'" data-dari_tgl="'+dari_tgl+'" data-sampai_tgl="'+sampai_tgl+'"  ><u>PRINT <i class="fa fa-print"></i></u></button>';

					return btn_approve+' '+btn_pdf;
				}
			},
			{"data": "NoPD", "orderable": true, "searchable": true, "className": "text-left"},
			{"data": "NoRef", "orderable": false, "searchable": false, "className": "text-left"},
			{"data": "TglPD", "orderable": false, "searchable": false, "className": "text-left"},
			{"data": "Alokasi", "orderable": false, "searchable": false, "className": "text-left"},
			{"data": "TipeForm", "orderable": false, "searchable": false, "className": "text-left"},
			{"data": "NoKO", "orderable": false, "searchable": false, "className": "text-left"},
			{"data": "TotalPD", render: $.fn.dataTable.render.number(',', '.', ''), "orderable": false, "searchable": false, "className": "text-right text-bold"},
			{"data": "ApprovedByAK", "orderable": false, "searchable": false, "className": "text-center"},
			{"data": "TimeApprovedAK", "orderable": false, "searchable": false, "className": "text-center"},
			],
			order: [[0, 'desc']],

		});

	});




</script>