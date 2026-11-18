<style type="text/css">
	#tblJob{
		font-family: sans-serif;
		font-size: 12px;
	}

	.font12{
		font-size: 12px;
	}
	.font14{
		font-size: 14px !important;
	}

	select[readonly].select2-hidden-accessible + .select2-container {
		pointer-events: none;
		touch-action: none;
	}

	select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
		background: #eee;
		box-shadow: none;
	}

	select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow, select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
		display: none;
	}

</style>

<div class="content-wrapper">

	<section class="content-header">
		<h4 style="font-weight: bold;"><?=$judul?></h4>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('Dashboard')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Add New Request Pemakaian MOS (Material On Site)</li>
		</ol>
		<a type="button" onclick="history.back()" class="btn btn-sm btn-danger">Back</a>
	</section>

	<section class="content">
		<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
		<div class="row">

			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border hidden">
						<a href="<?=site_url('Job/sub_job/'.$JobNo)?>" class="btn btn-md btn-danger">Back</a>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">

						<div class="table-responsive">
							<form id="formTampil">

								<table class="table" width="100%" id="tblJob">
									<tr>
										<td style="vertical-align:middle;" width="5%">Job No : </td>
										<td style="vertical-align:middle;width: 20%;">
											<select name="JobNo" id="JobNo" class="form-control input-sm font12" <?=($JobNo !='') ? 'disabled="disabled"' : '' ?>>
												<option value="">-- Pilih Job --</option>
												<?php foreach ($ListJob as $row): ?>
													<option value="<?=$row->JobNo?>" <?=($row->JobNo == $JobNo) ? 'selected' : '' ?> ><?=$row->JobNo?> - <?=$row->JobNm?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td style="vertical-align:middle;" width="5%">No MK : </td>
										<td style="vertical-align:middle;width: 20%;">
											<input type="text" id="NoMK" name="NoMK" class="form-control input-sm" value="<?=$NoMK?>" readonly  >
										</td>
										<td style="vertical-align:middle;" width="5%">No KO : </td>
										<td style="vertical-align:middle;width: 20%;">
											<select name="NoKO" id="NoKO" class="form-control input-sm font12" >
												<option value="">-- Pilih --</option>
											</select>
										</td>
										<td style="vertical-align:middle;" width="5%">
											<button class="btn btn-default btn-sm btn_tampilkan text-bold">Tampilkan <i class="fa fa-search"></i></button>
											<button type="button" class="btn btn-default btn-md btn_loading" style="display:none;" ><i class="fa fa-refresh fa-spin"></i></button>
										</td>
										<td></td>
									</tr>
									
								</table>



							</form>

						</div>

						<div class="tampilDisini">
							
						</div>

					</div>
				</div>
			</div>



		</div>


	</section>



</div>

<script src="<?=base_url('assets/select2_baru.js')?>"></script>

<script type="text/javascript">
	$(document).ready(function(){



		if ($('#JobNo').val() !='') {
			dapatkanListKO();
		}

		$('#JobNo, #NoKO').select2({ dropdownCssClass: "font14" });

		$('#JobNo').change(function(){
			dapatkanListKO();
		});

		$('#NoKO').change(function(){
			table_add_pemakaian_mos();
		});

		$('#formTampil').submit(function(e){
			e.preventDefault();
			table_add_pemakaian_mos();

		});

		


	});

	function dapatkanListKO(){
			var JobNo = $('select[name="JobNo"]').val();

			$.ajax({
				url: '<?=site_url("MOS/FrmReqPemakaianMOS/get_ListKO")?>',
				type: 'post',
				data: {'JobNo' : JobNo},
				beforeSend : function(){
					$('#NoKO').attr('disabled',true);
					$('.btn_tampilkan').attr('disabled',true);
				},
				complete : function(){
					$('#NoKO').attr('disabled',false);
					$('.btn_tampilkan').attr('disabled',false);
				},
				success: function (data) {
						// alert(data);
					$('#NoKO').html('');
					$('#NoKO').append(data);
				}, error : function(){

					$.toast({
						text: 'error',
						position: 'top-right',
						icon : 'error'
					});
					return false;
				}
			});
		}

		function table_add_pemakaian_mos(){

			var JobNo = $('#JobNo').val();
			var NoKO = $('#NoKO').val();
			var NoMK = $('#NoMK').val();


			if (JobNo == '') {
				$.toast({
					text: 'Job masih kosong',
					position: 'mid-center',
					icon : 'info'
				});
				return false;
			}

			if (NoKO == '') {
				$('.tampilDisini').html('');
				$.toast({
					text: 'No KO masih kosong',
					position: 'mid-center',
					icon : 'error'
				});
				return false;
			}

			$.ajax({
				url: '<?=site_url("MOS/FrmReqPemakaianMOS/table_request_pemakaian")?>',
				type: 'post',
				data: {'JobNo' : JobNo, 'NoKO' : NoKO, 'NoMK' : NoMK },
				dataType : 'html',
				beforeSend : function(){
					$('.btn_tampilkan').hide();
					$('.btn_loading').show();
					$('.tampilDisini').html('');
				},
				complete : function(){
					$('.btn_tampilkan').show();
					$('.btn_loading').hide();
				},
				success: function (data) {
					$('.tampilDisini').html(data);
				}, error : function(){
					$.toast({
						text: 'error',
						position: 'top-right',
						icon : 'error'
					});
					return false;
				}
			});
		}



	
</script>