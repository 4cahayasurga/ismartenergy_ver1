<div class="content-wrapper">
	<section class="content-header">
		<h1>
			RAP & SUMDA
			<p>
			</p>

			<label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $rap->JobNo ?></label> - <label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $rap->JobNm ?></label>
			<br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $rap->Lokasi ?></label>
				<!--small>Control panel</small-->
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index')  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?= site_url('job')  ?>">Job List</a></li>
			<li><a href="<?= site_url('job/sub_job/' . $rap->JobNo)  ?>">Job Detail</a></li>
			<li><a href="<?= site_url('job/rap/'.$rap->JobNo)  ?>">RAP & SUMDA</a></li>
		</ol>
	</section>

	<!-- <section class="content-header">

		<a href="<?= site_url('job/sub_job/' . $rap->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
		<a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a>

	</section> -->

	<!-- <Section class="content"> -->
	<section class="content">
		<div class="box box-success">
			<div class="box-header with-border">
				<h1 class="box-title">RAP</h1>

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
				</div>
			</div>
			<div class="box-body">

				<div class="form-group">
					<form class="form-horizontal" id="form-rap">
						<div class="form-group row">

							<!-- update code 2-->

							<label for="inputName" class="col-sm-2 col-form-label">Alokasi</label>
							<div class="col-sm-10">
								<select class="form-control select2" name="alokasi_rap" id="alokasi_rap" style="width: 50%;">
									<option value="">-- Pilih Alokasi --</option>
									<?php foreach ($AksesAlokasi as $data) : ?>
										<option value="<?php echo $data->Alokasi ?>"><?php echo $data->Alokasi ?> - <?php echo $data->Keterangan ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="inputEmail" class="col-sm-2 col-form-label">Versi</label>
							<div class="col-sm-10">
								<select class="form-control" name="versi_rap" id="versi_rap" style="width: 30%;">
									<option value="0.0">0.0</option>
									<option value="0.1" selected>0.1</option>
								</select>
							</div>
						</div>
						<!-- <div class="form-group row">
							<label for="FileRap" class="col-lg-2 col-form-label">Upload File RAP</label>
							<div class="col-sm-10">
								<input type="file" name="filerap" id="filerap" class="form-control" style="width: 30%;">
							</div>
						</div> -->
					</form>

				</div>

				<span class="my-loading-rap" id="my-loading-rap" style="display:none;">Loading...</span>
				<div class="tampil-rap" id="tampil-rap"></div>

			</div>
		</div>

		<div class="modal fade" tabindex="-1" role="dialog" id="my-modal">
			<div class="modal-dialog modal-md" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="my-modal-title"></h4>
					</div>
					<div class="modal-body" id="my-modal-body">

					</div>
				</div>
			</div>
		</div>

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title" style="font-size: larger;">SUMDA</h3>

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
				</div>
			</div>
			<div class="box-body">

				<div class="form-group">
					<form class="form-horizontal" id="form-sumda">
						<div class="form-group row">

							<!-- update code 2-->

							<label for="inputName" class="col-sm-2 col-form-label">Alokasi</label>
							<div class="col-sm-10">
								<select class="form-control select2" name="alokasi_sumda" id="alokasi_sumda" style="width: 50%;">
									<option value="">-- Pilih Alokasi --</option>
									<?php foreach ($AksesAlokasi as $data) : ?>
										<option value="<?php echo $data->Alokasi ?>"><?php echo $data->Alokasi ?> - <?php echo $data->Keterangan ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="inputEmail" class="col-sm-2 col-form-label">Versi</label>
							<div class="col-sm-10">
								<select class="form-control" name="versi_sumda" id="versi_sumda" style="width: 30%;">
									<option value="0.0">0.0</option>
									<option value="0.1" selected>0.1</option>
								</select>
							</div>
						</div>

					</form>

				</div>

				<span class="my-loading-sumda" id="my-loading-sumda" style="display:none;">Loading...</span>
				<div class="tampil-sumda" id="tampil-sumda"></div>
			</div>
		</div>

		<div class="modal fade" tabindex="-1" role="dialog" id="my-modal-sumda">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="my-modal-title-sumda"></h4>
					</div>
					<div class="modal-body" id="my-modal-body-sumda">

					</div>

				</div>
			</div>
		</div>

</div>
</section>
<script type="text/javascript">
	var JobNo = '<?= $rap->JobNo ?>';
	$(document).ready(function() {

		$('.select2').select2();
		
		// $('#alokasi').change(function() {
		// 	var alokasi = this.value;
		// 	var Versi_rap = $('#Versi_rap').val();
		// 	// alert(this.value);
		// 	getTable(JobNo, alokasi, Versi_rap)
		// })

		//rap
		$('#alokasi_rap').change(function() {
			var alokasi_rap = this.value;
			var versi_rap = $('#versi_rap').val();
			// alert(this.value);
			getTableRap(JobNo, alokasi_rap, versi_rap)
		})

		$('#versi_rap').change(function() {
			var alokasi_rap = $('#alokasi_rap').val();
			var versi_rap = this.value;
			// alert(this.value);
			getTableRap(JobNo, alokasi_rap, versi_rap)
		})

		//sumda
		$('#alokasi_sumda').change(function() {
			var alokasi_sumda = this.value;
			var versi_sumda = $('#versi_sumda').val();
			// alert(this.value);
			getTableSumda(JobNo, alokasi_sumda, versi_sumda)
		})

		$('#versi_sumda').change(function() {
			var alokasi_sumda = $('#alokasi_sumda').val();
			var versi_sumda = this.value;
			// alert(this.value);
			getTableSumda(JobNo, alokasi_sumda, versi_sumda)
		})


	})



	// function getTable(JobNo, alokasi, Versi_rap) {
	// 	if (alokasi == '') {
	// 		return false;
	// 	}


	// 	$.ajax({
	// 			url: '<?= site_url('Rap/getTable') ?>',
	// 			type: 'POST',
	// 			dataType: 'HTML',
	// 			data: {
	// 				"JobNo": JobNo,
	// 				"alokasi": alokasi,
	// 				"Versi_rap": Versi_rap
	// 			},
	// 			beforeSend: function() {
	// 				$('.my-loading').show();
	// 			}
	// 		})
	// 		.done(function(response) {
	// 			$('.my-loading').hide();
	// 			$('.tampil-rap').html(response);

	// 		})
	// 		.fail(function() {
	// 			// console.log("error");
	// 		})
	// 		.always(function() {
	// 			// console.log("complete");
	// 		});
	// }

	function getTableSumda(JobNo, alokasi_sumda, versi_sumda) {
		if (alokasi_sumda == '') {
			return false;
		}

		$.ajax({
				url: '<?= site_url('Sumda/getTableSumda') ?>',
				type: 'POST',
				dataType: 'HTML',
				data: {
					"JobNo": JobNo,
					"alokasi_sumda": alokasi_sumda,
					"versi_sumda": versi_sumda
				},
				beforeSend: function() {
					$('.my-loading-sumda').show();
				}
			})
			.done(function(response) {
				$('.my-loading-sumda').hide();
				$('.tampil-sumda').html(response);

			})
			.fail(function() {
				// console.log("error");
			})
			.always(function() {
				// console.log("complete");
			});
	}

	function getTableRap(JobNo, alokasi_rap, versi_rap) {
		if (alokasi_rap == '') {
			return false;
		}

		$.ajax({
				url: '<?= site_url('Rap/getTableRap') ?>',
				type: 'POST',
				dataType: 'HTML',
				data: {
					"JobNo": JobNo,
					"alokasi_rap": alokasi_rap,
					"versi_rap": versi_rap
				},
				beforeSend: function() {
					$('.my-loading-rap').show();
				}
			})
			.done(function(response) {
				$('.my-loading-rap').hide();
				$('.tampil-rap').html(response);

			})
			.fail(function() {
				// console.log("error");
			})
			.always(function() {
				// console.log("complete");
			});
	}


</script>
