
<style type="text/css">
	.gaya_huruf{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	.swal2-popup {
		font-size: 1.4rem !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
	.select2-selection { overflow: hidden; }
	.select2-selection__rendered { white-space: normal; word-break: break-all; }

	.padding_lima{
		padding-top: 5px !important;
	}

	.padding_sepuluh{
		padding-top: 10px !important;
	}

	.myFont {
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size:11px;
	}

</style>
<div class="content-wrapper">



	<section class="content">
		<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
		<table style="width:100%;border-collapse: collapse;" class="gaya_huruf">
			<tr>
				<th style="text-decoration:underline;vertical-align: top;font-size:22px !important;"><?=($judul) ? strtoupper($judul) : 'Judul belum diatur'  ?></th>
			</tr>
		</table>

		<div class="box box-widget">

			<div class="box-header bg-aqua-active">

			</div>

			<div class="box-body">
				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive">

							<form id="FrmRptQueryPDPJ" style="font-size:13px !important;">

								<table style="width: 100%;border-collapse: collapse;" >
									<tr style="margin-top: 10px !important;">
										<th width="50px">Job No</th>
										<td width="20px" class="text-center">:</td>
										<td width="500px">
											<select name="JobNo" id="JobNo" class="form-control select2" style="font-size:13px !important;" required>
												<!-- <option value="All Job">All Job</option> -->
												<!-- <?php foreach ($ListJobNo as $key ): ?>
													<option value="<?=$ListJobNo[0]?>"><?=$ListJobNo[0]['JobNo']?> - <?=$ListJobNo[0]?></option>
													<?php endforeach ?> -->
													<?php foreach($ListJobNo as $key){ ?>
														<option value="<?=$key['JobNo']?>"><?=$key['JobNo']?> - <?=$key['JobNm']?></option>
													<?php } ?>
												</select>
											</td>
											<td></td>

										</tr>
									</table>

									<p></p>
									<table style="width: 100%;border-collapse: collapse;">
										<tr>
											<th width="50px">Alokasi</th>
											<td width="20px" class="text-center">:</td>
											<td width="500px">
												<select name="Alokasi" id="Alokasi" class="form-control select2" style="font-size:13px !important;" required>
													<option value="All Alokasi">All Alokasi</option>
													<?php foreach($ListAlokasi as $key){ ?>
														<option value="<?=$key['Alokasi']?>"><?=$key['Alokasi']?> - <?=$key['Keterangan']?></option>
													<?php } ?>
												</select>
											</td>
											<td></td>

										</tr>
									</table>

									<p></p>
									<table style="width: 100%;border-collapse: collapse;">

										<tr >
											<td width="50px"></td>
											<td width="20px" class="text-center"></td>
											<td width="200px">
												<button type="submit" class="btn btn-md btn-default btn_tampilkan text-bold"><i class="fa fa-search"></i> Tampilkan</button>
												<button class="btn btn-md bg-default btn_proses disabled" style="display:none;"><i class="fa fa-spinner fa-spin"></i> &nbsp;Loading...</button>
											</td>
											<td></td>
										</tr>

									</table>

								</form>

							</div>

							<div></div>

						</div>
					</div>

					<div id="tampilkan_table_RptQueryPDPJ"></div>
					<div id="tampilkan_table_QueryPDPJ_tanpa_server_side"></div>

				</div>

			</div>

			<!-- <div class="tampil_table"></div> -->



		</section>


	</div>



	<script src="<?=base_url('assets/select2_baru.js')?>"></script>

	<script type="text/javascript">




		$(document).ready(function(){
			$('.select2').select2({
				width: '100%',
				theme : 'classic',
				dropdownCssClass: "myFont"
			});


			$('#FrmRptQueryPDPJ').submit(function(event){
				event.preventDefault();
				// tampilkan_table_QueryPDPJ();
				tampilkan_table_QueryPDPJ_tanpa_server_side();
			});


		});

		function tampilkan_table_QueryPDPJ_tanpa_server_side(){


			var JobNo = $('#JobNo').find(":selected").val();
			var Alokasi = $('#Alokasi').find(":selected").val();

			
			if (JobNo == '' || JobNo == null) {
				Swal.fire('Info?!','Job masih kosong','info');
				return false;
			}

			if (Alokasi == '' || Alokasi == null) {
				Swal.fire('Info?!','Alokasi masih kosong','info');
				return false;
			}

			jQuery.ajax({
				url: '<?=site_url('RptQuery_PDPJ/tampilkan_table_QueryPDPJ_tanpa_server_side')?>',
				type: 'POST',
				dataType: 'html',
				data: {JobNo : JobNo, Alokasi : Alokasi},
				cache : false,
				beforeSend : function(){
					$('#tampilkan_table_RptQueryPDPJ').html('');
					$('.btn_tampilkan').hide();
					$('.btn_proses').show();
				},
				complete: function(xhr, textStatus) {
					$('.btn_tampilkan').show();
					$('.btn_proses').hide();
				},
				success: function(data, textStatus, xhr) {
					$('#tampilkan_table_RptQueryPDPJ').html(data);
					// data_json_QueryPDPJ();
				},
				error: function(xhr, textStatus, errorThrown) {
					Swal.fire('Error!','','error');
					return false;
				}
			});

		}

		function tampilkan_table_QueryPDPJ(){

			var JobNo = $('#JobNo').find(":selected").val();
			var Alokasi = $('#Alokasi').find(":selected").val();

			
			if (JobNo == '' || JobNo == null) {
				Swal.fire('Info?!','Job masih kosong','info');
				return false;
			}

			if (Alokasi == '' || Alokasi == null) {
				Swal.fire('Info?!','Alokasi masih kosong','info');
				return false;
			}

			jQuery.ajax({
				url: '<?=site_url('RptQuery_PDPJ/tampilkan_table_QueryPDPJ')?>',
				type: 'POST',
				dataType: 'html',
				data: {JobNo : JobNo, Alokasi : Alokasi},
				cache : false,
				beforeSend : function(){
					$('#tampilkan_table_RptQueryPDPJ').html('');
					$('.btn_tampilkan').hide();
					$('.btn_proses').show();
				},
				complete: function(xhr, textStatus) {
					$('.btn_tampilkan').show();
					$('.btn_proses').hide();
				},
				success: function(data, textStatus, xhr) {
					$('#tampilkan_table_RptQueryPDPJ').html(data);
					data_json_QueryPDPJ();
				},
				error: function(xhr, textStatus, errorThrown) {
					Swal.fire('Error!','','error');
					return false;
				}
			});



		}   

		function data_json_QueryPDPJ() {

			var JobNo = $('#tblQueryPDPJ').attr('data-JobNo');
			var Alokasi = $('#tblQueryPDPJ').attr('data-Alokasi');

			// alert(JobNo+' - '+Alokasi);
			// return false;

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


			$("#tblQueryPDPJ").dataTable({
				
				pageLength: 50,
				lengthMenu: [20,50,100,200,300,500,1000],
				processing: true,
				serverSide: true,
				orderable : false,
				paging: false,
				scrollY:    500,
				scrollX:        true,
				scrollCollapse: true,
				fixedColumns:   true,
				ajax: {
					"url": "<?= site_url('RptQuery_PDPJ/data_json_QueryPDPJ') ?>",
					"type": "POST",
					"data": {
						'JobNo': JobNo,
						'Alokasi': Alokasi,
					}
				},
				columns: [


				{
					"data": "JobNo",
					"orderable": false,
					"searchable": false,
					"className": "text-center",

				},



				{
					"data": "NoPD",
					"orderable": true,
					"searchable": true,
					"className": "text-left",

				},


				{
					"data": "Alokasi",
					"orderable": true,
					"searchable": true,
					"className": "text-left",

				},

				// {
				// 	"data": "NoRef",
				// 	"orderable": false,
				// 	"searchable": false,
				// 	"className": "text-left",

				// },

				// {
				// 	"data": "TglPD",
				// 	"orderable": false,
				// 	"searchable": false,
				// 	"className": "text-left",

				// },

				// {
				// 	"data": "Alokasi",
				// 	"orderable": false,
				// 	"searchable": false,
				// 	"className": "text-left",

				// },

				// {
				// 	"data": "TipeForm",
				// 	"orderable": false,
				// 	"searchable": false,
				// 	"className": "text-left",

				// },

				// {
				// 	"data": "NoKO",
				// 	"orderable": false,
				// 	"searchable": false,
				// 	"className": "text-left",

				// },

				// {
				// 	"data": "Deskripsi",
				// 	"orderable": false,
				// 	"searchable": false,
				// 	"className": "text-left",

				// },

				// {
				// 	"data": "TotalPD",
				// 	"orderable": false,
				// 	"searchable": false,
				// 	"className": "text-left",

				// },

				// {
				// 	"data": "ApprovedByAK",
				// 	"orderable": false,
				// 	"searchable": false,
				// 	"className": "text-left",

				// },

				// {
				// 	"data": "TimeApprovedAK",
				// 	"orderable": false,
				// 	"searchable": false,
				// 	"className": "text-left",

				// },



				],
				// order: [
				// [0, 'desc']
				// ],
				// rowCallback: function(row, data, iDisplayIndex) {
				// 	var info = this.fnPagingInfo();
				// 	var page = info.iPage;
				// 	var length = info.iLength;
				// 	var index = page * length + (iDisplayIndex + 1);
				// 	$('td:eq(0)', row).html(index);
				// }
			});


		}



	</script>

