<style type="text/css">
	.font10{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size : 10px;
	}
	.font11{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size : 11px;
	}
	.font13{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
	.font12{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size : 12px;
	}
	.font14{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size : 14px;
	}
	.font16{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size : 16px;
	}
	.jarak5{
		padding-top: 5px;
		padding-bottom: 5px;
	}
	#tbl_search tr td{
		padding-top: 5px;
		padding-bottom: 5px;
	}
	.swal2-popup {
		font-size: 1.4rem !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
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

	<section class="content">
		<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
		<table>
			<tr>
				<td class="font16 text-bold"><?=($judul) ? strtoupper($judul) : 'Judul belum diatur'  ?></td>
			</tr>
		</table>

		<br>

		<div class="row">
			<div class="col-sm-12">
				<div class="box box-primary">
					<div class="box-body">
						<form id="MyForm">
							<table width="100%" border="0" class="font12">
								<tr>
									<td width="5%">Job No.</td>
									<td class="text-center" width="1%">:</td>
									<td width="30%">
										<select name="JobNo" id="JobNo" class="form-control input-sm font12 select2" <?=$TxtJob?>>
											<option value="">-- Pilih Job --</option>
											<?php foreach ($ListJob as $key): ?>
												<option value="<?=$key->JobNo?>" <?=($key->JobNo == $JobNo) ? 'selected' : ''   ?>  ><?=$key->JobNo.' - '.$key->JobNm?></option>
											<?php endforeach ?>
										</select>
									</td>
									<td></td>
								</tr>
							</table>

							<table class="selectNoKO" width="100%" border="0" class="font12" style="margin-top:5px !important;margin-bottom:5px !important;" >
								<tr>
									<td width="5%">No SJ.</td>
									<td class="text-center" width="1%">:</td>
									<td width="30%">
										<select name="NoSJ" id="NoSJ" class="form-control input-sm font12 select2" style="display:;">
											<option value="">-- Pilih --</option>

										</select>
										<i class="fa fa-refresh fa-spin loading_saya" style="display:none;"></i>
									</td>
									<td></td>
								</tr>
							</table>

							<table  width="100%" border="0" class="font12" style="margin-bottom:5px !important;"  >
								<tr>
									<td width="5%"></td>
									<td class="text-center" width="1%"></td>
									<td width="30%">
										<button class="btn btn-default btn_cari" type="submit">SEARCH <i class="fa fa-search"></i></button>
									</td>
									<td></td>
								</tr>
							</table>

						</form>


						<div class="tampil_table_PemakaianSJ"></div>

					</div>
				</div>
			</div>
		</div>

	</section>

</div>

<script src="<?=base_url('assets/select2_baru.js')?>"></script>
<script type="text/javascript">
	$(function(){
		$('.select2').select2({ dropdownCssClass: "font12" });

		$('#MyForm').submit(function(e){
			e.preventDefault();
			if ($('select[name="NoSJ"]').val() == '') {
				$.toast({
					text: 'No SJ masih kosong',
					position: 'top-right',
					icon : 'error'
				});
				return false;
			}
			tampil_table_PemakaianSJ();
		});


		if ($('#JobNo').val() !='' ) {
			GetSelect_NoSJ();

		}

		$('#JobNo').change(function(){
			GetSelect_NoSJ();
		});

		$('select[name="NoSJ"]').change(function(){

			if ($('#JobNo').val() == '') {
				Swal.fire(
					'Alert!',
					'belum pilih job',
					'error'
					)
				return false;
			}

			tampil_table_PemakaianSJ();
		});


		function GetSelect_NoSJ(){
			var JobNo = $('#JobNo').val();

			$.ajax({
				url: '<?=site_url('MOS/FrmUsageMOS/GetSelect_NoSJ')?>',
				type: 'post',
				data: {JobNo : JobNo},
				dataType : 'html',
				cache : false,
				beforeSend : function(){
				},
				complete : function(){
				},
				success: function (data) {

					if (data == 'error') {
						$.toast({
							text: 'error',
							position: 'top-right',
							icon : 'error'
						})
					}

					$('select[name="NoSJ"]').html(data);

				},error : function(){
					$.toast({
						text: 'error',
						position: 'top-right',
						icon : 'error'
					});

				}
			});
			return false;

		}

		function tampil_table_PemakaianSJ(){
			var JobNo = $('#JobNo').val();
			var NoSJ = $('#NoSJ').val();

			if (NoSJ == '') {
				$.toast({
					text: 'No SJ masih kosong',
					position: 'top-right',
					icon : 'error'
				});
				return false;
			}

			$.ajax({
				url: '<?=site_url('MOS/FrmUsageMOS/tampil_table_PemakaianSJ')?>',
				type: 'post',
				data: {JobNo : JobNo, NoSJ : NoSJ},
				dataType : 'html',
				beforeSend : function(){
					$('.btn_cari').attr('disabled',true);
					$('.loading_saya').show();
					$('select[name="NoSJ"]').next(".select2-container").hide();
					$('.tampil_table_PemakaianSJ').html('');
				},
				complete : function(){
					$('.btn_cari').attr('disabled',false);
					$('.loading_saya').hide();
					$('select[name="NoSJ"]').next(".select2-container").show();
				},
				success: function (data) {
					$('.tampil_table_PemakaianSJ').html(data);
				},error : function(){
					$.toast({
						text: 'error',
						position: 'top-right',
						icon : 'error'
					})
				}
			});
		}
		
	});

	


</script>
