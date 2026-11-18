<style>
	table#mytable.dataTable tbody tr:hover {
		background-color: #bdc3c7;
		color: black;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size: 18px !important;

	}

	table#mytable.dataTable tbody tr:hover > .sorting_1 {
		background-color: #3498db
		color: black;
		font-size: 18px !important;
		font-weight: bold;
	}
	#mytable tbody tr td {
		font-size: 12px !important;
		/*font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;*/
		padding-top: 5px;
		padding-bottom: 5px;
		vertical-align: top;
	}

	#mytable thead tr th {
		font-size: 10px !important;
		background-color: #636e72;
		/*font-family: Arial, Helvetica, sans-serif;*/
		/*font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;*/
		padding-top: 10px;
		padding-bottom: 10px;
		vertical-align: middle;
		white-space: nowrap;
	}
</style>

<div class="col-12 ">
	<div class="box">
		<div class="box-header" style="display:none;">
			<a href="<?= $myurl?>" class="btn btn-sm btn-danger">Tambah Data &nbsp;<i class="fa fa-plus"></i></a>
			<button class="btn btn-sm btn-secondary" onclick="pencarian()">Refresh</button>
		</div>
		<div class="box-body">


			<div class="table-responsive">

				<table width="100%" border="0" style="border-collapse:collapse;margin-bottom:10px !important;"  >
					<tr>
						<td width="7%" >
							<button  class="btn btn-sm btn-danger" id="tambahPD" >Tambah PD &nbsp;<i class="fa fa-plus"></i></button>
						</td>
						<td width="9%">
							<button  class="btn btn-sm btn-success" id="tambahPDRKD" >Tambah PDRKD &nbsp;<i class="fa fa-plus"></i></button>
						</td>
						<td>
							<button  class="btn btn-sm btn-primary" id="tambahPDMIX" >Tambah PDMIX &nbsp;<i class="fa fa-plus"></i></button>
						</td>
						<td style="text-align:right;font-size: 14px;font-weight:bold;"><?='Sum = '.uang_koma_genap_decimal_kedua($sumTotalPD);?></td>
					</tr>
				</table>
				<hr>

				<table id="mytable" class="table table-striped table-hover table-condensed table-bordered" data-job="<?= $JobNo ?>" data-alokasi="<?= $Alokasi ?>" data-dari-tgl="<?= $dari_tgl ?>" data-sampai-tgl="<?= $sampai_tgl ?>" data-KSO="<?=$KSO?>" data-TipeManajerial="<?=$TipeManajerial?>" data-JobNm="<?=$JobNm?>" data-myurl="<?=$myurl?>" border="0">
					<thead>
						<tr style="font-size: 12px !important;" class="bg-gray-active">
							<th style="text-align:center;width: 20px !important;" data-orderable="false">No</th>
							<th style="text-align:center;width: 9%;"></th>
							<th style="text-align:center;">No.PD</th>
							<th style="text-align:left;">No.Ref <br>(Lapangan)</th>
							<th style="text-align:left;width: 5%;">Tgl.PD</th>
							<th style="text-align:left;">Alokasi</th>
							<th style="text-align:left;">Tipe Form</th>
							<th style="text-align:left;">No.KO</th>
							<th style="text-align:left;">Deskripsi</th>
							<th style="text-align:right;">Total PD (Rp)</th>
							<th style="text-align:left;">Approved By (Keuangan)</th>
							<th style="text-align:left;">Approved On</th>
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

		$('#tambahPD').click(function(){
			var JobNo = $('#mytable').attr('data-job');
			var JobNm = $('#mytable').attr('data-JobNm');

			var myurl = $('#mytable').attr('data-myurl');
			var TipeManajerial = $('#mytable').attr('data-TipeManajerial');
			var KSO = $('#mytable').attr('data-KSO');

			if (TipeManajerial == 'JO Integrated') {
				swal.fire('Info!', JobNo+' - '+JobNm+' merupakan JO Integrated  ','info');
				return false;
			}

			window.location.href=myurl;
		});

		$('#tambahPDRKD').click(function(){

			var JobNo = $('#mytable').attr('data-job');
			var JobNm = $('#mytable').attr('data-JobNm');

			var myurl = $('#mytable').attr('data-myurl');
			var TipeManajerial = $('#mytable').attr('data-TipeManajerial');
			var KSO = $('#mytable').attr('data-KSO');

			if (KSO !='1' || KSO !=1  ) {
				swal.fire('Info!', JobNo+' - '+JobNm+' bukan KSO  ','info');
				return false;
			}

			myurl = myurl+'/RKD';

			window.location.href=myurl;

		});

		$('#tambahPDMIX').click(function(){

			var JobNo = $('#mytable').attr('data-job');
			var JobNm = $('#mytable').attr('data-JobNm');

			var myurl = $('#mytable').attr('data-myurl');
			var TipeManajerial = $('#mytable').attr('data-TipeManajerial');
			var KSO = $('#mytable').attr('data-KSO');

			if (KSO !='1' || KSO !=1  ) {
				swal.fire('Info!', JobNo+' - '+JobNm+' bukan KSO  ','info');
				return false;
			}

			myurl = myurl+'/MIX';

			window.location.href=myurl;

		});

	});

	function cek_printPD(LedgerNo){

		if (LedgerNo=='') {
			return false;
		}

		var ApprovedByAK = $('#print_ID_'+LedgerNo).attr('data-ApprovedByAK');
		var NoPD = $('#print_ID_'+LedgerNo).attr('data-NoPD');
		var urlsaya = $('#print_ID_'+LedgerNo).attr('data-urlsaya');

		if (ApprovedByAK == '' || ApprovedByAK == null ) {
			swal.fire('Info!','Approve PD : '+NoPD+' terlebih dahulu','info');
			return false;
		}

		urlsaya = urlsaya+'/KEU';

		// alert(urlsaya);
		// return false;

		window.open(urlsaya,'_blank');

	}

	

	function data_json_pd_keuangan() {

		var JobNo = $('#mytable').attr('data-job');
		var Alokasi = $('#mytable').attr('data-alokasi');
		var dari_tgl = $('#mytable').attr('data-dari-tgl');
		var sampai_tgl = $('#mytable').attr('data-sampai-tgl');

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
			fixedColumns:   true,
			pagging : true,
			scrollY:        "500px",
			scrollX:        true,
			scrollCollapse: true,

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
			pageLength: 100,
			lengthMenu: [50,100,200,300,500,1000],
			processing: true,
			serverSide: true,

			ajax: {
				"url": "<?= site_url('Permintaan_dana_keuangan/dapat_list_pd_json') ?>",
				"type": "POST",
				"data": {
					'JobNo': JobNo,
					'Alokasi': Alokasi,
					'dari_tgl': dari_tgl,
					'sampai_tgl': sampai_tgl,
				}
			},
			columns: [


			{
				"data": "LedgerNo",
				"orderable": false,
				"searchable": false,
				"className": "text-center",

			},




			{
				"data": "ViewPDKeuangan",
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

						// var cek_KK = row.ApprovedByKK;
						var ApprovedByKK = (row.ApprovedByKK == null) ? 'kosong_KK' : row.ApprovedByKK;
						var ApprovedByKT = (row.ApprovedByKT == null) ? 'kosong_KT' : row.ApprovedByKT;


						var btn_approve = '<button class="btn btn-sm" '+warna+' onclick="btn_Approve('+row.LedgerNo+')" data-ApprovedByAK="'+ApprovedByAK+'" title="'+ApprovedByAK+'" id="id_btn_approve_'+row.LedgerNo+'" data-NoPD="'+row.NoPD+'" data-ApprovedByKK="'+ApprovedByKK+'" data-ApprovedByKT="'+ApprovedByKT+'" > '+ikon+' </button>';


						var button_print_terbaru = '<button class="btn btn-sm btn-danger" title="PRINT / PDF / EXPORT PDF "  onclick="button_print_terbaru('+row.LedgerNo+')" ><i class="fa fa-print"></i></button>';

						// return data+' '+btn_approve+' '+row.aksi_loading+' '+row.pdkeu_pdf_js;
						return data+' '+btn_approve+' '+row.aksi_loading+' '+row.print_PD_terbaru;

						// return data+' '+row.aksi_approve_or_unapprove+' '+row.pdkeu_pdf_js+' '+row.aksi_loading;
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
					"searchable": false,
					"className": "text-left",

				},

				{
					"data": "TglPD",
					"orderable": false,
					"searchable": false,
					"className": "text-left",

				},

				{
					"data": "Alokasi",
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
					"data": "NoKO",
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