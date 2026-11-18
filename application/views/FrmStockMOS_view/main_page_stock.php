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
		<a href="<?=site_url('Job/sub_job/'.$JobNo)?>" class="btn btn-md btn-danger">Back</a>
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

								<table class="table" width="50%" id="tblJob">
									<tr>
										<td style="vertical-align:middle;" width="5%">Job No : </td>
										<td style="vertical-align:middle;width: 20%;">
											<select name="JobNo" id="JobNo" class="form-control input-sm font12" <?=($JobNo !='') ? 'readonly' : '' ?> required>
												<option value="">-- Pilih Job --</option>
												<?php foreach ($ListJob as $row): ?>
													<option value="<?=$row->JobNo?>" <?=($row->JobNo == $JobNo) ? 'selected' : '' ?> ><?=$row->JobNo?> - <?=$row->JobNm?></option>
												<?php endforeach ?>
											</select>
										</td>
										<!-- <td style="vertical-align:middle;" width="5%">No MK : </td>
										<td style="vertical-align:middle;width: 20%;">
											<select name="NoMK" id="NoMK" class="form-control input-sm font12" >
												<option value="">-- Pilih --</option>
											</select>
										</td> -->
										<td></td>

										<!-- <td style="vertical-align:middle;" width="5%">
											<button class="btn btn-default btn-sm btn_tampilkan text-bold">Tampilkan <i class="fa fa-search"></i></button>
											<button type="button" class="btn btn-default btn-md btn_loading" style="display:none;" ><i class="fa fa-refresh fa-spin"></i></button>
										</td>
										<td></td> -->
									</tr>

									<tr>
										<td style="vertical-align:middle;" width="6%">Dari Tanggal : </td>
										<td style="vertical-align:middle;width: 20%;">
											<input type="date" name="DariTgl" class="form-control input-sm font12" value="<?=$DariTgl?>" required>
										</td>
										<td width="2%" class="text-center" style="vertical-align: middle;">s.d</td>
										<td style="vertical-align:middle;width: 20%;">
											<input type="date" name="SampaiTgl" class="form-control input-sm font12" value="<?=$SampaiTgl?>" required>
										</td>
										<td style="vertical-align:middle;" width="5%">
											<button class="btn btn-default btn-sm btn_tampilkan text-bold">Tampilkan <i class="fa fa-search"></i></button>
											<button type="button" class="btn btn-default btn-md btn_loading" style="display:none;" ><i class="fa fa-refresh fa-spin"></i></button>
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
		function tampilkan_table_stock(){

			if ($('#JobNo').val() == '') {
				$.toast({
					text: 'job masih kosong',
					position: 'mid-right',
					icon : 'info'
				});
				return false;
			}

			var JobNo = $('#JobNo').val();
			var DariTgl = $('#DariTgl').val();
			var SampaiTgl = $('#SampaiTgl').val();

			$.ajax({
				url: '<?=site_url("MOS/FrmStockMOS/table_stock")?>',
				type: 'post',
				// data: {'JobNo' : JobNo, 'DariTgl' : DariTgl, 'SampaiTgl' : SampaiTgl},
				data : $('#formTampil').serialize(),
				dataType : 'html',
				beforeSend : function(){
					$('.btn_tampilkan').hide();
					$('.btn_loading').show();
				}, complete : function(){
					$('.btn_tampilkan').show();
					$('.btn_loading').hide();
				},
				success: function (data) {
					// alert(data);
					$('.tampilDisini').html('');
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

		function CariNoMK(){

			if ($('#JobNo').val() == '') {
				return false;
			}

			$.ajax({
				url: '<?=site_url("MOS?FrmStockMOS/get_NoMK")?>',
				type: 'post',
				data: {'JobNo' : $('#JobNo').val()},
				dataType : 'html',
				beforeSend : function(){
					$('.btn_tampilkan').hide();
					$('.btn_loading').show();
				}, complete : function(){

				},
				success: function (data) {
					alert(data);
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

		$(function(){

			$('#JobNo,#NoMK').select2({ dropdownCssClass: "font14" });

			// $('#JobNo').change(function(){

			// });

			$('#formTampil').submit(function(e){
				e.preventDefault();

				tampilkan_table_stock();
			});


		});


	</script>