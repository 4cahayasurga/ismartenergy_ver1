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
		<h5 style="font-weight: bold;"><?= (isset($judul)) ? strtoupper($judul) : '' ?></h5>

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

		<div class="box box-success">

			<div class="box-header with-border">

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
				</div>
			</div>
			<div class="box-body">

				<div class="table-responsive">

					<table width="100%" border="0" class="mykonten" >
						<tr>
							<td style="width:8%;padding-bottom:20px !important;">Job No</td>
							<td style="width:1%;text-align: center;">:</td>
							<td style="width:25%;">
								<select name="JobNo" id="JobNo" class="form-control input-sm select2" required>
									<option value="all_job">Semua Job</option>
									<?php
									foreach (dapat_list_job() as $data_job) :
										?>
										<option value="<?= $data_job['JobNo'] ?>" <?= ($JobNo == $data_job['JobNo']) ? 'selected' : ''  ?>><?= $data_job['JobNo'] . ' - ' . $data_job['JobNm'] ?></option>

									<?php endforeach; ?>
								</select>
							</td>
							<td></td>
						</tr>

						<tr>
							<td style="width:8%;"></td>
							<td style="width:1%;text-align: center;"></td>
							<td style="width:25%;">
								<button id="btn_tampil_rekap" class="btn btn-sm btn-success">Tampilkan <i class="fa fa-search"></i> </button>
								<button id="btn_loading" class="btn btn-sm btn-danger" disabled="disabled" style="display:none;">Loading... <i class="fa fa-spinner"></i></button>
							</td>
							<td></td>
						</tr>

					</table>

					

				</div>


			</div>

			
		</div>

		<div id="tampil_tbl_rekap"></div>

	</section>


</div>
<script type="text/javascript">
	$(function() {


		$('.select2').select2({
			'width': '100%',
			'theme': 'classic',
		});

		$('#btn_tampil_rekap').click(function(){
			tbl_rekap();
		});

	});

	function tbl_rekap(){

		var JobNo = $('#JobNo').val();

		if (JobNo == '') {
			$.toast({
				text: 'Pilih Job terlebih dahulu',
				position: 'bottom-right',
				stack: false,
				hideAfter: 1000,
				icon: 'info',

			});
			return false;
		}

		jQuery.ajax({

			url: '<?= site_url("Laporan/Report_PD_Jaminan/tbl_rekap") ?>',
			type: 'post',
			dataType: 'html',
			data: { 'JobNo': JobNo },
			beforeSend : function(){
				$('#btn_tampil_rekap').hide();
				$('#btn_loading').show();
				$('#tampil_tbl_rekap').html('');
			},
			complete: function(xhr, textStatus) {
				$('#btn_tampil_rekap').show();
				$('#btn_loading').hide();
			},
			success: function(data, textStatus, xhr) {
				$('#tampil_tbl_rekap').html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				$.toast({
					text: 'terjadi kesalahan',
					position: 'bottom-right',
					stack: false,
					hideAfter: 2000,
					icon: 'error',

				});
				return false;
			}
		});
	}

</script>