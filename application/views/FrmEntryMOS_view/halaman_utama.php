<style type="text/css">
	#tblJob{
		font-family: sans-serif;
		font-size: 12px;
	}

	.font12{
		font-size: 12px;
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
		<h1>
			<?=$judul?>
		</h1>
		<br><small><a href="<?=site_url('Job/sub_job/'.$JobNo)?>"  class="btn btn-md btn-danger"><i class="fa fa-arrow-left"></i> BACK</a></small>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('Dashboard')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Material On Site</li>
		</ol>
	</section>

	<section class="content">
		<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
		<div class="row">

			<div class="col-md-12">
				<div class="box box-primary">
					
					<div class="box-body">

						<div class="table-responsive">
							<form id="formTampil">
								<table class="table" width="100%" id="tblJob">
									<tr>
										<td width="4%">Job No</td>
										<td class="text-center" width="1%">:</td>
										<td style="padding-left:5px;" width="30%">
											<select name="JobNo" id="JobNo" class="form-control input-sm font12" <?=($JobNo !='') ? 'disabled="disabled"' : '' ?>>
												<option value="">:: Pilih Salah Satu ::</option>
												<?php foreach ($ListJob as $row): ?>
													<option value="<?=$row->JobNo?>" <?=($row->JobNo == $JobNo) ? 'selected' : '' ?> ><?=$row->JobNo?> - <?=$row->JobNm?></option>
												<?php endforeach ?>
											</select>

										</td>
										<td style="padding-left:5px;" class="text-left">
											<button type="submit" class="btn btn-md btn-default btn_tampilkan font12" title="Klik untuk menampilkan table MOS" >Tampilkan <i class="fa fa-search"></i></button>
											<button type="button" class="btn btn-default btn-md btn_loading" style="display:none;" ><i class="fa fa-refresh fa-spin"></i></button>
										</td>
										<td></td>

									</tr>
									<tr>
										<td colspan="5">
											<!-- <a href="<?=site_url('MOS/FrmEntryMOS/addNewMOS/'.$JobNo)?>" type="button" title="Buat MOS Baru" class="btn btn-success btn-md">Add MOS <i class="fa fa-plus"></i></a> -->
										</td>
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
			TampilTableMOS();
		}

		

		
		$('#JobNo').select2({ dropdownCssClass: "font12" });

		$('select[name="JobNo"]').change(function(){
			TampilTableMOS();
		});

		$('#formTampil').submit(function(e){
			e.preventDefault();

			if ($('#JobNo').val() == '') {
				$.toast({
					text: 'Job masih kosong',
					position: 'top-right',
					icon : 'error'
				});
				return false;
			}

			TampilTableMOS();
		});

	});

	function TampilTableMOS(){
			// alert('aa');
		var JobNo = $('#JobNo').val();

		if (JobNo == '') {
			$.toast({
				text: 'Job masih kosong',
				position: 'top-right',
				icon : 'error'
			});
			return false;
		}

		$.ajax({
			url: '<?=site_url("MOS/FrmEntryMOS/TampilTableMOS")?>',
			type: 'post',
			data: {'JobNo' : JobNo },
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
					// alert(data);
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