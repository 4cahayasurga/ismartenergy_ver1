<style type="text/css">
	select[readonly].select2-hidden-accessible + .select2-container {
  pointer-events: none;
  touch-action: none;
}
</style>

<div class="content-wrapper">

	<section class="content-header">
		<h4>
			<label class="col-lg col-form-label"><?=($judul) ? $judul : '' ?></label>
			<p></p>
		</h4>
		<label for="inputName" class="col-sm col-form-label"><?=$dataJob['JobNo']?></label> - <label for="inputName" class="col-sm col-form-label"><?=$dataJob['JobNm']?></label><br>
		<small><label for="inputName" class="col-sm col-form-label"><?=$dataJob['Lokasi']?></label></small>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?=($judul) ? $judul : '' ?></li>
		</ol>
	</section>


	<section class="content-header">

		<button class="btn btn-xs" style="background-color:#7f8c8d;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>

	</section>

	<section class="content">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

		<div class="box box-danger">

			<div class="box-body">
				

				<div class="table-responsive">
					<table width="100%" border="1">
						<tr>
							<td>Job. No</td>
							<td>:</td>
							<td>
								<select name="JobNo" id="JobNo" class="form-control input-sm select2" readonly required>
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

			</div>

			<div class="overlay loading_pd_keuangan" style="display: none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>

		</div>




	</section>

</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('.select2').select2();
	})
</script>