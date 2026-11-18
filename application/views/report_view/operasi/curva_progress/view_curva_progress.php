<style type="text/css">
	#kontenProgress{
		font-size: 10px;
		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
	}
</style>
<div class="content-wrapper">

	<section class="content-header">
		<h5 style="font-weight:bold;"><?=$judul?></h5>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('Dashboard')  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?=$judul?></li>
		</ol>
	</section>

	<section class="content-header">
		<button class="btn btn-xs" style="background-color:#7f8c8d;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>
	</section>

	<section class="content" id="kontenProgress">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

		<div class="box box-warning">

			<div class="box-header">
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
				</div>
			</div>

			<div class="box-body">
				
				<div class="row">

					<div class="col-sm-12" >
						<table width="100%" border="0">
							<!-- <form id="myformCurva"> -->
								<tr>
									<td width="5%" class="text-left">Job No.</td>
									<td width="1%" class="text-center">:</td>
									<td width="22%">
										<select name="JobNo" id="JobNo" class="form-control input-sm select2" onchange="getData()" required>
											<option value="">-- Pilih Salah Satu --</option>
											<?php
											foreach(dapat_list_job() AS $myJob ) : ?>
												<option value="<?=$myJob['JobNo']?>"><?=$myJob['JobNo'].' - '.$myJob['JobNm']?></option>
											<?php endforeach; ?>
										</select>
									</td>
									<td></td>
								</tr>

								<tr>
									<td width="5%" class="text-left"></td>
									<td width="1%" class="text-center"></td>
									<td width="22%">
										<p></p>
										<button type="button" class="btn btn-sm btn_tampil" style="background-color:#2980b9;color:white;" >Tampilkan</button>
									</td>
									<td></td>
								</tr>

								<!-- </form> -->
							</table>
						</div>

					</div>


				</div>

				<div class="overlay loadingku" style="display: none;">
					<i class="fa fa-refresh fa-spin"></i>
				</div>


			</div>


			<div class="table_progress">

			</div>

		</section>


	</div>

	<script type="text/javascript">
		$(function() {

			$('#JobNo').select2();

			// $('.select2').select2({
			// 	'width': '100%',
			// 	'theme': 'classic',
			// });

			$('.btn_tampil').click(function(){
				getData();
			})



		});

		function getData(){

			$('.table_progress').html('');

			var JobNo = $('#JobNo').val();

			jQuery.ajax({
				url: '<?=site_url('MyReport/Operasi/CurvaProgressReport/getDataProgress')?>',
				type: 'POST',
				dataType: 'html',
				data: {JobNo: JobNo},
				beforeSend : function(){
					$('.loadingku').show();
				},
				success: function(data, textStatus, xhr) {
					$('.loadingku').hide();
					$('.table_progress').html(data);
				},
				error: function(xhr, textStatus, errorThrown) {
					$('.loadingku').hide();
					$.toast({
						text: 'Terjadi Kesalahan',
						position: 'bottom-right',
						stack: false,
						hideAfter: 2000,
						icon: 'error',
					});
				}

			});


		}
	</script>