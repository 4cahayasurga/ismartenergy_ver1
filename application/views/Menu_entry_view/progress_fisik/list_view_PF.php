<style type="text/css">
	#mytable{
		font-size: 14px !important;
	}

	#mytable thead tr th{
		padding-top: 10px;
		padding-bottom: 10px;
		height: 20px;
		text-align: center;
	}

	#mytable tbody tr td{
		font-size: 14px !important;
	}
</style>

<div class="content-wrapper">


	<section class="content-header">
		<h1 class="text-bold">
			<?=$judul?>
			<?php if ($JobNo!=''): ?>
				<small style="font-size: 16px;" class="text-black">(<?=$JobNo.' - '.$JobNm.' - '.$Lokasi?>)</small>
			<?php endif ?>
		</h1>
		<a href="<?=site_url('Job/sub_job/'.$JobNo)?>" class="btn btn-default btn-md" ><i class="fa fa-arrow-left"></i>  BACK</a>
	</section>

	<section class="content">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

		<div class="box box-primary">
			<div class="box-header">
				<div class="row">
					<div class="col-sm-12">

						<form id="myform" action="<?=site_url('Menu_entry/Progress_fisik/list_data_PF/')?>" method="POST">
							<div class="table-responsive">

								<table width="100%" border="0">
									<tr style="font-size:14px;">
										<td width="100px"> Job No </td>
										<td width="10px" class="text-center">:</td>
										<td width="500px">
											<select name="JobNo" id="JobNo" class="form-control input-sm select2" style="font-size:14px;" required>
												<option value="">:: Pilih Salah Satu ::</option>
												<?php foreach ($ListJob as $row): ?>
													<option value="<?=$row->JobNo?>" <?=($JobNo == $row->JobNo) ? 'selected' : '' ?>  ><?=$row->JobNo.' - '.$row->JobNm?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td></td>
									</tr>
								</table>


								<table width="100%" border="0" style="margin-top: 5px !important;">
									<tr>
										<td width="100px">  </td>
										<td width="10px" class="text-center"></td>
										<td width="500px">
											<button type="submit" class="btn btn-primary btn-md" id="btnTampilkan">TAMPILKAN</button>
											<button type="button" class="btn btn-primary btn-md" id="btnLoading" disabled="disabled" style="display:none;">LOADING...</button>
										</td>
										<td></td>
									</tr>
								</table>

							</div>
						</form>


					</div>
				</div>
			</div>
		</div>

		<div class="box box-danger" <?=($JobNo=='') ? 'style="display:none;"' : '' ?>>
			<div class="box-header">
				<button type="button" class="btn btn-md btn-default" title="Tambah Data Progress Fisik" id="btn_tambah_PF" data-JobNo="<?=$JobNo?>">TAMBAH DATA <i class="fa fa-plus"></i></button>
				<a href="<?=site_url('Menu_entry/Progress_fisik/ExportExcel_PF/'.$JobNo)?>" class="btn btn-md btn-default">Export To Excel <i class="fa fa-file-excel-o"></i></a>
			</div>
			<div class="box-body">
				
				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-condensed table-hover" id="mytable" data-JobNo="<?=$JobNo?>">
								<thead>
									<tr>
										<!-- <th>No</th> -->
										<th>#</th>
										<th>Tahun</th>
										<th>Bulan</th>
										<th class="bg-info">Rencana Fisik Kumulatif (%)</th>
										<th class="bg-info">Realisasi Fisik Kumulatif (%)</th>
										<th class="bg-info">Realisasi Keuangan Kumulatif (%)</th>
										<th class="bg-success">Rencana Fisik Tahun Berjalan (%)</th>
										<th class="bg-success">Realisasi Fisik Tahun Berjalan (%)</th>
										<th class="bg-success">Realisasi Keuangan Tahun Berjalan (%)</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$sumRencanaK = 0;
									$sumRealisasiK = 0;
									$sumRealisasiKeuK = 0;
									$sumRencanaTB = 0;
									$sumRealisasiTB = 0;
									$sumRealisasiKeuTB = 0;
									$NoUrut=1;
									foreach ($dataPF as $row): 

										$sumRencanaK += (float) $row->RencanaK;
										$sumRealisasiK += (float) $row->RealisasiK;
										$sumRealisasiKeuK += (float) $row->RealisasiKeuK;
										$sumRencanaTB += (float) $row->RencanaTB;
										$sumRealisasiTB += (float) $row->RealisasiTB;
										$sumRealisasiKeuTB += (float) $row->RealisasiKeuTB;

										$NoUrut++;
										?>
										<tr>
											<td class="text-center">
												<a href="javascript:void(0)" class="btn btn-md btn-default text-blue " id="btnSelectID<?=$row->LedgerNo?>" onclick="btnSelect('<?=$row->LedgerNo?>')" data-JobNo="<?=$row->JobNo?>" data-LedgerNo="<?=$row->LedgerNo?>" title="Lihat / Edit" style="text-decoration: underline;" >SELECT</a>
											</td>
											<td class="text-center"><?=$row->Tahun?></td>
											<td class="text-center" ><?=getBulan($row->Bulan)?></td>
											<td class="text-right" ><?=new_decimal($row->RencanaK)?></td>
											<td class="text-right" ><?=new_decimal($row->RealisasiK)?></td>
											<td class="text-right" ><?=new_decimal($row->RealisasiKeuK)?></td>
											<td class="text-right"><?=new_decimal($row->RencanaTB)?></td>
											<td class="text-right" ><?=new_decimal($row->RealisasiTB)?></td>
											<td class="text-right" ><?=new_decimal($row->RealisasiKeuTB)?></td>
										</tr>
									<?php endforeach ?>
								</tbody>
								<tfoot>
									<tr>
										<th colspan="3">TOTAL</th>
										<th class="text-right bg-info"><!--?=new_decimal($sumRencanaK)?--></th>
										<th class="text-right bg-info"><!--?=new_decimal($sumRealisasiK)?--></th>
										<th class="text-right bg-info"><!--?=new_decimal($sumRealisasiKeuK)?--></th>
										<th class="text-right bg-success"><?=new_decimal($sumRencanaTB)?></th>
										<th class="text-right bg-success"><?=new_decimal($sumRealisasiTB)?></th>
										<th class="text-right bg-success"><?=new_decimal($sumRealisasiKeuTB)?></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
				

			</div>

			<div class="overlay loading_PF" style="display: none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>

		</div>




	</section>


	<div class="modal fade" id="Modal_PF">

		<div class="modal-dialog modal-md">
			<div class="modal-content">

				<div class="modal-body" id="ModalBody_PF">

				</div>

			</div>
		</div>
	</div>


</div>


<div class="clearfix"></div>

<script src="<?=base_url('assets/select2_baru.js')?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.select2').select2();
		$('#mytable').DataTable({
			language: {
				// search: "_INPUT_",
				searchPlaceholder: "Ketik disini..."
			}
		});

		


		$('#btn_tambah_PF').click(function(){
			var JobNo = $(this).attr('data-JobNo');

			jQuery.ajax({
				url: '<?=site_url('Menu_entry/Progress_fisik/dapat_form_tambah_data_PF')?>',
				type: 'POST',
				dataType: 'HTML',
				cache : false,
				data: {'JobNo' : JobNo},
				beforeSend : function(){
					$('#btn_tambah_PF').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('#btn_tambah_PF').attr('disabled',false);
				},
				success: function(data, textStatus, xhr) {
					$('#ModalBody_PF').html('');
					$('#ModalBody_PF').html(data);
					$('#Modal_PF').modal('show');
				},
				error: function(xhr, textStatus, errorThrown) {
					alert('Error,Terjadi Kesalahan');
					return false;
				}
			});



		});
		return false;


	});


	function list_data_json_PF() {

		var JobNo = $('#mytable').attr('data-JobNo');

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
			processing: true,
			serverSide: true,
			ajax: {
				"url": "<?= site_url('Menu_entry/Progress_fisik/get_list_data_json_PF') ?>",
				"type": "POST",
				"async": true,
				"cache" : false,
				"data": {'JobNo': JobNo}
			},
			columns: [


				// {
				// 	"data": "LedgerNo",
				// 	"orderable": true,
				// 	"searchable": false,
				// 	"className": "text-center",

				// },


			{
				"data": "aksi",
				"orderable": false,
				"searchable": false,
				"className": "text-center",
			},

			{
				"data": "Tahun",
				"orderable": true,
				"searchable": true,
				"className": "text-center",

			},

			{
				"data": "Bulan",
				"orderable": true,
				"searchable": true,
				"className": "text-center",				


			},

			{
				"data": "RencanaK",
				"orderable": false,
				"searchable": false,
				"className": "bg-info text-center",

			},

			{
				"data": "RealisasiK",
				"orderable": false,
				"searchable": false,
				"className": "bg-info text-center",

			},

			{
				"data": "RealisasiKeuK",
				"orderable": false,
				"searchable": false,
				"className": "bg-info text-center",

			},

			{
				"data": "RencanaTB",
				"orderable": false,
				"searchable": false,
				"className": "bg-success text-center",

			},

			{
				"data": "RealisasiTB",
				"orderable": false,
				"searchable": false,
				"className": "bg-success text-center",

			},

			{
				"data": "RealisasiKeuTB",
				"orderable": false,
				"searchable": false,
				"className": "bg-success text-center",

			},




			],
			order: [
				[1, 'DESC'],[2, 'DESC']
				]
		});


	}

	// === BATAS ===

	function btnSelect(LedgerNo=''){
		if (LedgerNo=='') {
			alert('Ledger kosong');
			return false;
		}

		var JobNo = $('#btnSelectID'+LedgerNo).attr('data-JobNo');
		// alert(LedgerNo+' - '+JobNo);

		$('#ModalBody_PF').html('');

		$.ajax({
			url: "<?=site_url('Menu_entry/Progress_fisik/dapat_form_ubah_data_PF')?>",
			type: "POST",
			dataType: "html",
			cache : false,
			data: {
				'JobNo' : JobNo,
				'LedgerNo' : LedgerNo
			},
			beforeSend : function(){
				$('#btnSelectID'+LedgerNo).attr('disabled',true).text('Loading...');
			}, complete : function(){
				$('#btnSelectID'+LedgerNo).attr('disabled',false).text('SELECT');
			}

		})
		.done(function(data) {
			$('#ModalBody_PF').html(data);
			$('#Modal_PF').modal('show');
			// alert(data);
			// console.log("success");
		})
		.fail(function() {
			alert('Error, Terjadi Kesalahan');
			// console.log("error");
		})
		.always(function() {
			// console.log("complete");
		});
		

	}

	function dapat_form_ubah_PF(JobNo,LedgerNo){
		// alert(JobNo+ ' - '+LedgerNo);

		jQuery.ajax({
			url: '<?=site_url('Menu_entry/Progress_fisik/dapat_form_ubah_data_PF')?>',
			type: 'POST',
			dataType: 'HTML',
			async: true,
			cache : false,
			data: {
				'JobNo' : JobNo,
				'LedgerNo' : LedgerNo
			},
			beforeSend : function(){
				$('.loading_PF').show();
				$('#ModalBody_PF').html('');
			},
			complete: function(xhr, textStatus) {
				$('.loading_PF').hide();
				$('#Modal_PF').modal('show');
			},
			success: function(data, textStatus, xhr) {
				$('#ModalBody_PF').html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				$.toast({
					text: 'terjadi kesalahan',
					position: 'bottom-right',
					stack: false,
					hideAfter: 1000,
					icon: 'error',

				});
				return false;
			}
		});

	}



</script>
