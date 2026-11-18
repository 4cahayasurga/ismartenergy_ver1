<style>
	.mykonten {
		font-family: Arial, Helvetica, sans-serif !important;
		font-size: x-small !important;
	}

	.ukuran_select2 {
		font-size: 10px !important;
		font-family: Arial, Helvetica, sans-serif;

	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h4 style="font-weight:bold;"><?= $judul ?></h4>

		<ol class="breadcrumb">
			<li><a href="<?= site_url('dashboard')  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?= $judul ?></li>
		</ol>
	</section>

	<section class="content-header">

		<button class="btn btn-xs" style="background-color:#7f8c8d;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>


	</section>

	<section class="content">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

		<div class="box box-danger">

			<div class="box-header with-border">
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
				</div>
			</div>
			<div class="box-body">

				<div class="row">
					<div class="col-sm-6">

						<table border="0" width="100%">
							<tr>
								<th style="padding-top:5px;padding-bottom:5px;" width="100px">Job. No</th>
								<td style="padding-top:5px;padding-bottom:5px;">
									<select class="form-control input-sm" name="JobNo" id="JobNo" required>
										<option value="">-- Pilih salah satu --</option>
										<?php foreach ($DetailJob as $data_job) : ?>
											<option value="<?= $data_job['JobNo'] ?>"><?= $data_job['JobNo'] . ' - ' . $data_job['JobNm'] ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
						</table>

						<table border="0" width="100%">
							<tr>
								<th style="padding-top:5px;padding-bottom:5px;" width="100px">Vendor</th>
								<td style="padding-top:5px;padding-bottom:5px;">
									<select class="form-control input-sm" name="VendorId" id="VendorId">
										<option value="ALL">ALL</option>
										<?php foreach ($DetailVendor as $data_vendor) : ?>
											<option value="<?= $data_vendor['VendorId'] ?>"><?= $data_vendor['VendorId'] . ' - ' . $data_vendor['VendorNm'] ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
						</table>

						<table border="0" width="100%">
							<tr>
								<th style="padding-top:5px;padding-bottom:5px;" width="100px">Status</th>
								<td style="padding-top:5px;padding-bottom:5px;">
									<select class="form-control input-sm" name="Status" id="Status">
										<option value="ALL">ALL</option>
										<option value="Outstanding_KO">Outstanding KO</option>
										<option value="Outstanding_Invoice">Outstanding Invoice</option>
										<option value="Settle_KO">Settle KO</option>
										<option value="Settle_Invoice">Settle Invoice</option>
									</select>
								</td>
							</tr>
						</table>


						<table border="0" width="100%">
							<tr>
								<th style="padding-top:5px;padding-bottom:5px;" width="100px"></th>
								<td style="padding-top:5px;padding-bottom:5px;">
									<button type="submit" name="btn_tampil" id="btn_tampil" class="btn btn-sm bg-purple"><i class="fa fa-search"></i> Tampilkan</button>
								</td>
							</tr>
						</table>



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

		$('#JobNo, #VendorId, #Status').select2({
			'theme': 'classic',
			'width': '100%',
		});

		$('#btn_tampil').click(function() {
			getTable_QueryHutangKO();
		})

	});


	function getTable_QueryHutangKO() {
		var JobNo = $('#JobNo').val();
		var VendorId = $('#VendorId :selected').val();
		var Status = $('#Status :selected').val();

		if (JobNo == '') {

			$.toast({

				text: 'pilih job terlebih dahulu',
				position: 'bottom-right',
				stack: false,
				hideAfter: 1000,
				icon: 'info',

			})
			return false;
		}

		jQuery.ajax({
			url: '<?= site_url('Laporan/Procurement/Query_HutangKO/getTable_QueryHutangKO') ?>',
			type: 'POST',
			dataType: 'HTML',
			async: true,
			cache: false,
			data: {
				'JobNo': JobNo,
				'VendorId': VendorId,
				'Status': Status,
			},
			beforeSend: function() {
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