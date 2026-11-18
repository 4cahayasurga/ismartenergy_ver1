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
							<td style="width:28%;">
								<select name="JobNo" id="JobNo" class="form-control input-sm select2" required>
									<option value="">-- Pilih Job --</option>
									<?php
									foreach (dapat_list_job() as $data_job) :
										?>
										<option value="<?= $data_job['JobNo'] ?>" <?= ($JobNo == $data_job['JobNo']) ? 'selected' : ''  ?>><?= $data_job['JobNo'] . ' - ' . $data_job['JobNm'] ?></option>

									<?php endforeach; ?>
								</select>
							</td>
							<td></td>
						</tr>
					</table>

					<table width="100%%" border="0" class="mykonten">
						<tr>
							<td style="width:8%;padding-bottom:20px !important;">Periode</td>
							<td style="width:1%;text-align: center;">:</td>
							<td style="width:13%;">
								<input type="date" name="dari_tgl" id="dari_tgl" value="<?= (isset($tgl_pertama)) ? $tgl_pertama : '
								' ?>" class="form-control input-sm mykonten">
							</td>
							<td style="width:2%;text-align: center;">s.d</td>
							<td style="width:13%;">
								<input type="date" name="sampai_tgl" id="sampai_tgl" value="<?= (isset($tgl_terakhir)) ? $tgl_terakhir : '
								' ?>" class="form-control input-sm mykonten">
							</td>
							<td>
								<label for="semua_periode" style="padding-left:10px;">
									<input type="checkbox" name="semua_periode" id="semua_periode" class="semua_periode" onchange="semua_periode_validasi()" value="1" >
									Semua Periode
								</label>
								
							</td>

						</tr>
					</table>


					<table width="100%" border="0" class="mykonten" >
						<tr>
							<td style="width:8%;"></td>
							<td style="width:1%;text-align: center;"></td>
							<td style="width:205;">
								<button id="btn_tampil_rekap_ko" class="btn btn-sm btn-success">Tampilkan <i class="fa fa-search"></i> </button>
								<button id="btn_loading" class="btn btn-sm btn-danger" disabled="disabled" style="display:none;">Loading... <i class="fa fa-spinner"></i></button>

							</td>	
							<td></td>
						</tr>

					</table>

					

					

				</div>


			</div>

			
		</div>

		<div id="tampil_tbl_rekap_ko"></div>

	</section>


</div>
<script type="text/javascript">
	$(function() {


		$('.select2').select2({
			'width': '100%',
			'theme': 'classic',
		});

		$('#btn_tampil_rekap_ko').click(function(){
			tbl_rekap_ko();
		});

	});

	function semua_periode_validasi(){

		if ($('.semua_periode').is(':checked')) {
			$('#dari_tgl').attr('readonly',true);
			$('#sampai_tgl').attr('readonly',true);
		}else{
			$('#dari_tgl').attr('readonly',false);
			$('#sampai_tgl').attr('readonly',false);
		}

		
	}

	function tbl_rekap_ko(){

		var JobNo = $('#JobNo').val();
		var dari_tgl = ($('#dari_tgl').val()) ? $('#dari_tgl').val() : '';
		var sampai_tgl = ($('#sampai_tgl').val()) ? $('#sampai_tgl').val() : '';
		var semua_periode =  $('.semua_periode').is(':checked') ? 1 : 0; 

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

		if (dari_tgl == '' || sampai_tgl == '') {
			$.toast({
				text: 'pilih Tanggal terlebih dahulu',
				position: 'bottom-right',
				stack: false,
				hideAfter: 1000,
				icon: 'info',

			});
			return false;
		}

		jQuery.ajax({

			url: '<?= site_url("MyReport/Procurement/Rekap_KO/tbl_rekap_ko") ?>',
			type: 'post',
			dataType: 'html',
			data: { 
				'JobNo': JobNo,
				'dari_tgl' : dari_tgl,
				'sampai_tgl' : sampai_tgl,
				'semua_periode' : semua_periode
			},
			beforeSend : function(){
				$('#btn_tampil_rekap_ko').hide();
				$('#btn_loading').show();
				$('#tampil_tbl_rekap_ko').html('');
				semua_periode_validasi();
			},
			complete: function(xhr, textStatus) {
				$('#btn_tampil_rekap_ko').show();
				$('#btn_loading').hide();
			},
			success: function(data, textStatus, xhr) {
				$('#tampil_tbl_rekap_ko').html(data);
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