<style>
	.mykonten {
		font-family: Arial, Helvetica, sans-serif !important;
		font-size: 10px !important;
	}

	.atur_td {
		width: 100px;
	}

	.myFont {
		font-size: 10px !important;
	}

	.select2 {
		font-size: 9px;
	}
</style>

<div class="content-wrapper">

	<section class="content-header">
		<h4 style="font-weight: bold;"><?= (isset($judul)) ? strtoupper($judul) : '' ?></h4>

		<ol class="breadcrumb">
			<li><a href="<?= site_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?= (isset($judul)) ? $judul : '' ?></li>
		</ol>
	</section>

	<section class="content-header">

		<button class="btn btn-xs" style="background-color:#7f8c8d;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>

	</section>

	<section class="content">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

		<div class="box box-danger">

			<div class="box-body">
				


				<?php
				if (isset($_SESSION['failed'])) {

					echo '
					<div class="callout callout-danger">
						<p>'.$_SESSION['failed'].'</p>
					</div>';
					unset($_SESSION['failed']);

				}

				?>
				<div class="table-responsive">


					<table width="30%" border="0" class="mykonten">
						<tr>
							<th style="padding-top:5px;padding-bottom: 5px;" width="80px">Job. No</th>
							<td style="text-align: center;padding-top:5px;padding-bottom: 5px">:</td>
							<td style="padding-top:5px;padding-bottom: 5px;">
								<!-- <input type="hidden" name="JobNo" value="<?=$JobNo?>" id="JobNo"> -->
								<select name="JobNo" id="JobNo" class="form-control input-sm select2"  required>
									<option value="">-- Pilih Salah Satu --</option>
									<?php
									foreach (dapat_list_job() as $data_job) :
										?>
										<option value="<?= $data_job['JobNo'] ?>" <?= ($JobNo == $data_job['JobNo']) ? 'selected' : ''  ?>><?= $data_job['JobNo'] . ' - ' . $data_job['JobNm'] ?></option>

									<?php endforeach; ?>
								</select>
							</td>
						</tr>
					</table>


					<table width="30%" border="0" class="mykonten">
						<tr>
							<th style="padding-top:5px;padding-bottom: 5px;" width="80px">Alokasi</th>
							<td style="text-align: center;padding-top:5px;padding-bottom: 5px">:</td>
							<td style="padding-top:5px;padding-bottom: 5px;">
								<select name="Alokasi" id="Alokasi" class="form-control input-sm select2" required>
									<option value="">-- Pilih Salah Satu --</option>
									<?php
									foreach (list_job_alokasi() as $data_alokasi) :
										?>
										<option value="<?= $data_alokasi['Alokasi'] ?>"><?= $data_alokasi['Alokasi'] . ' - ' . $data_alokasi['Keterangan'] ?></option>

									<?php endforeach; ?>
								</select>
							</td>
						</tr>
					</table>

					<table width="30%" border="0" class="mykonten">
						<tr>
							<th style="padding-top:5px;padding-bottom: 5px;" width="80px">Versi</th>
							<td style="text-align: center;padding-top:5px;padding-bottom: 5px">:</td>
							<td style="padding-top:5px;padding-bottom: 5px;">
								<select name="Versi" id="Versi" class="form-control input-sm select2" required>
									<option value="0.0">0.0</option>

								</select>
							</td>
						</tr>
					</table>

					<table width="30%" border="0" class="mykonten">
						<tr>
							<th style="padding-top:5px;padding-bottom: 5px;" width="80px">Filter By</th>
							<td style="text-align: center;padding-top:5px;padding-bottom: 5px">:</td>
							<td style="padding-top:5px;padding-bottom: 5px;">
								<input type="text" name="FilterBy" id="FilterBy" class="form-control input-sm" placeholder="Cari berdasarkan uraian">
							</td>
						</tr>
					</table>


				</div>


			</div>

			<div class="overlay loading_ent_rap" style="display: none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>

		</div>

		<div id="tampil_table_rap"></div>





	</section>

</div>
<script type="text/javascript">
	$(function() {

		$('.select2').select2({
			'width': '100%',
			'theme': 'classic',
		});

		$('#JobNo').change(function() {
			var JobNo = $('select[name=JobNo] option').filter(':selected').val();
			var Alokasi = $('select[name=Alokasi] option').filter(':selected').val();
			dapat_versi(JobNo, Alokasi);
		})

		$('#Alokasi').change(function() {
			var JobNo = $('select[name=JobNo] option').filter(':selected').val();
			var Alokasi = $('select[name=Alokasi] option').filter(':selected').val();
			dapat_versi(JobNo, Alokasi);
		})

		$('.btn_segarkan').click(function() {
			var JobNo = $('select[name=JobNo] option').filter(':selected').val();
			var Alokasi = $('select[name=Alokasi] option').filter(':selected').val();
			dapat_versi(JobNo, Alokasi);
		})

		$('#Versi').change(function() {
			list_data_rap();
		})

	});

	function dapat_versi(JobNo = '', Alokasi = '') {


		jQuery.ajax({
			url: '<?= site_url('Entry_master/ENT_RAP/dapat_versi') ?>',
			type: 'POST',
			dataType: 'html',
			data: {
				'JobNo': JobNo,
				'Alokasi': Alokasi
			},
			beforeSend: function() {
				$('.loading_ent_rap').show();
			},
			complete: function(xhr, textStatus) {
				$('.loading_ent_rap').hide();
				// list_data_rap();
			},
			success: function(data, textStatus, xhr) {
				$('.loading_ent_rap').hide();
				if (data == 'no') {
					return false;
				}
				$('#Versi').html(data);
				list_data_rap();
				return false;
			},
			error: function(xhr, textStatus, errorThrown) {
				$('.loading_ent_rap').hide();
				$.toast({
					text: 'terjadi kesalahan',
					position: 'bottom-right',
					stack: false,
					hideAfter: 1000,
					icon: 'error',

				});
				return false;
			}
		});



	}

	function list_data_rap() {

		var JobNo = $('select[name=JobNo] option').filter(':selected').val();
		var Alokasi = $('select[name=Alokasi] option').filter(':selected').val();

		var Versi = $('select[name=Versi] option').filter(':selected').val();
		var Versi_tipe = $('#Versi option:selected').attr('data-versi_tipe');

		if (JobNo == '' || Alokasi == '') {
			return false;
		}


		jQuery.ajax({
			url: '<?= site_url('Entry_master/ENT_RAP/list_data_rap') ?>',
			type: 'POST',
			dataType: 'html',
			data: {
				'JobNo': JobNo,
				'Alokasi': Alokasi,
				'Versi': Versi,
				'Versi_tipe': Versi_tipe
			},
			beforeSend: function() {
				$('.loading_ent_rap').show();
			},
			complete: function(xhr, textStatus) {
				$('.loading_ent_rap').hide();

			},
			success: function(data, textStatus, xhr) {
				$('.loading_ent_rap').hide();
				// alert(data);
				// return false;
				if (data == '') {
					return false;
				}

				$('#tampil_table_rap').html(data);

			},
			error: function(xhr, textStatus, errorThrown) {
				$('.loading_ent_rap').hide();
				$.toast({
					text: 'terjadi kesalahan',
					position: 'bottom-right',
					stack: false,
					hideAfter: 1000,
					icon: 'error',

				});
				return false;
			}
		});

	}

	function segarkan_data(){
		list_data_rap();
	}
</script>
