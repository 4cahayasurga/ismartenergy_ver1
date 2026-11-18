<style>
	.mykonten {
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;

		font-size: 12px !important;
	}

	.ukuran_select2 {
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}

	.atur_ukuran{
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}
	.swal2-popup {
		font-size: 1.2rem !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}


	#tbl_saya thead tr th{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size: 12px;
		padding-top: 10px !important;
		padding-bottom: 10px !important;
	}

	#tbl_saya tbody{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size: 12px;
		padding-top: 10px !important;
		padding-bottom: 10px !important;
	}

</style>
<div class="content-wrapper">
	<section class="content-header">
		<h3><?=(isset($judul)) ? $judul : '' ?></h3>
		<ol class="breadcrumb">
			<li><a href=<?=site_url('Welcome/index')?>><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">List Data Job</li>
		</ol>
	</section>


	<section class="content">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

		<div class="box box-primary">
			<div class="box-body" id="tampil_job">
				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover table-condensed" id="tbl_saya">
								<thead>
									<tr>
										<th>#</th>
										<th>Job No</th>
										<th>Job Name</th>
										<th>Deskripsi</th>
										<th>Kontraktor</th>
										<th>Status</th>
										<th>Kategori</th>
										
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div>


	</section>


</div>

<script type="text/javascript">
	$(document).ready(function(){

		tampilkan_list_job();

	});

	function tampilkan_list_job(){

		$("#tbl_saya").dataTable({
			initComplete: function() {
				var api = this.api();
				
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
				"url": "<?= site_url('JobList/get_job_json') ?>",
				"type": "POST",
				// "data": {'flag','dapat_job'},

			},
			columns: [

			{
				"data": "select_job",
				"orderable": false,
				"searchable": false,
				"className": "text-center",

			},
			{
				"data": "JobNo",
				"orderable": true,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "JobNm",
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
				"data": "CompanyId",
				"orderable": false,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "StatusJob",
				"orderable": false,
				"searchable": true,
				"className": "text-left",

			},

			{
				"data": "Kategori",
				"orderable": false,
				"searchable": true,
				"className": "text-left",

			},

			


			],
			order: [1, 'desc'],

		});
	}
</script>

