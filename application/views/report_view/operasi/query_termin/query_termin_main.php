<style>
	.mykonten {
		font-family: Arial, Helvetica, sans-serif !important;
		font-size: x-small !important;
	}

	.ukuran_select2 {
		font-size: 10px !important;
		font-family: Arial, Helvetica, sans-serif;

	}
	.select2-results__option { 
		font-family: Arial, Helvetica, sans-serif !important;
		font-size: x-small !important;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h5 style="font-weight:bold;"><?=$judul?></h5>

		<ol class="breadcrumb">
			<li><a href="<?= site_url('dashboard')  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?=$judul?></li>
		</ol>
	</section>

	<section class="content-header">

		<button class="btn btn-xs" style="background-color:#7f8c8d;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>


	</section>

	<section class="content">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

		<div class="box box-primary">

			<div class="box-header with-border">
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
				</div>
			</div>
			<div class="box-body">

				<div class="row">
					<div class="col-sm-6" style="font-size:12px;">

						<form id="formTermin">
							<table border="0" width="100%">
								<tr>
									<th style="padding-top:5px;padding-bottom:5px;" width="10%">Job. No</th>
									<td style="padding-top:5px;padding-bottom:5px;" width="50%">
										<select class="form-control input-sm" name="JobNo" id="JobNo" onchange="getTable_QueryTermin()"  required>
											<option value="">-- Pilih salah satu --</option>
											<?php foreach(dapat_list_job() as $data_job ) : ?>
												<option value="<?=$data_job['JobNo']?>" ><?=$data_job['JobNo'].' - '.$data_job['JobNm']?></option>
											<?php endforeach; ?>
										</select>
									</td>
									<td></td>
								</tr>
							</table>

							<table border="0" width="100%">
								<tr>
									<th style="padding-top:5px;padding-bottom:5px;" width="10%">Tahun</th>
									<td style="padding-top:5px;padding-bottom:5px;" width="10%">
										<input type="number" name="Tahun" id="Tahun" maxlength="4" minlength="1" class="form-control input-sm"value="<?=date('Y')?>" required>
									</td>
									<td></td>
								</tr>
							</table>

							<table border="0" width="100%">
								<tr>
									<th style="padding-top:5px;padding-bottom:5px;" width="10%"></th>
									<td style="padding-top:5px;padding-bottom:5px;" width="10%">
										<button type="submit" name="btn_tampil" id="btn_tampil" class="btn btn-sm bg-purple"><i class="fa fa-search"></i> Tampilkan</button>
									</td>
									<td></td>
								</tr>
							</table>
						</form>



					</div>

				</div>






			</div>
			<div class="overlay loadingku" style="display: none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>


		</div>

		<div class="tampil_table"></div>


	</section>


</div>


<script>

	$(document).ready(function() {

		$('#JobNo').select2({
			'theme': 'classic',
			'width': '100%',
		});



		$('#formTermin').submit(function(e){
			e.preventDefault();

			getTable_QueryTermin();

		})

	});


	function getTable_QueryTermin(){

		var JobNo = $('#JobNo :selected').val();
		var Tahun = $('#Tahun').val();

		
		jQuery.ajax({
			url: '<?=site_url('MyReport/Operasi/QueryTermin_Report/getTable_QueryTermin')?>',
			type: 'POST',
			dataType: 'HTML',
			async: true,
			cache : false,
			data: {
				'JobNo' : JobNo,
				'Tahun' : Tahun
			},
			beforeSend : function(){
				$('.loadingku').show();
				$('.tampil_table').html('');
			},
			complete: function(xhr, textStatus) {
				$('.loadingku').hide();
			},
			success: function(data, textStatus, xhr) {
				// alert(data);
				$('.tampil_table').html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				$.toast({
					text: 'Terjadi Kesalahan',
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