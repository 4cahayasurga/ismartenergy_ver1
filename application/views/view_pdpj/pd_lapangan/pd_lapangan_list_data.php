<style type="text/css">
	.atur_huruf{
		font-size: 12px !important;
		font-family: sans-serif;
	}
</style>

<div class="content-wrapper">
	
	<section class="content-header">
		<h5>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
		</h5>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>


	<section class="content-header">
		<button onclick="history.back()" class="btn btn-sm btn-link" style="background-color:#34495e;color:white;"><i class="fa fa-arrow-left"></i> Back</button>
	</section>

	<section class="content-header">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

		<div class="row">

			<div class="col-sm-12">

				<div class="box box-success">

					<div class="box-header">

						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>



					</div>

					<div class="box-body atur_huruf">

						<table border="0" >
							<tr>
								<th width="100px">JobNo</th>
								<td style="text-align:center;">:</td>
								<td style="width:350px;padding-left:5px;">
									<select class="form-control select2" id="JobNo" name="JobNo"  required >
										<option value="">-- Pilih salah satu --</option>
										<?php
										foreach (dapat_list_job() as $data_job) :
											?>
											<option value="<?= $data_job['JobNo'] ?>" <?= ($JobNo == $data_job['JobNo']) ? 'selected' : ''  ?>><?= $data_job['JobNo'] . ' - ' . $data_job['JobNm'] ?></option>

										<?php endforeach; ?>
									</select>
								</td>
							</tr>
						</table>


					</div>

					<div class="box-footer">




					</div>

					<div class="overlay loadingsaya" style="display: none;">
						<i class="fa fa-refresh fa-spin"></i>
					</div>


				</div>


			</div>



		</section>

	</div>

	<script type="text/javascript">
		$(function(){
			$('.select2').select2();
		});
	</script>