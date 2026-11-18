<style>
	.mykonten {
		font-family: Arial, Helvetica, sans-serif !important;
		font-family: TimesNewRoman, "Times New Roman", Times, Baskerville, Georgia, serif;
		font-size: 10px !important;
	}

	.mykonten input[type="date"] {
		font-family: TimesNewRoman, "Times New Roman", Times, Baskerville, Georgia, serif;
		font-size: 10px !important;
	}

	.mykonten button {
		font-family: TimesNewRoman, "Times New Roman", Times, Baskerville, Georgia, serif;
		font-size: 10px !important;
	}


	.atur_td {
		font-family: TimesNewRoman, "Times New Roman", Times, Baskerville, Georgia, serif;
		width: 100px;
	}

	.myFont {
		font-family: TimesNewRoman, "Times New Roman", Times, Baskerville, Georgia, serif;
		font-size: 10px !important;
	}

	.select2 {
		font-family: TimesNewRoman, "Times New Roman", Times, Baskerville, Georgia, serif;
		font-size: 9px;
	}
</style>

<div class="content-wrapper">

	<section class="content-header">
		<h6 style="font-weight: bold;"><?= (isset($judul)) ? strtoupper($judul) : '' ?></h6>

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

					<table width="100%" border="0" class="mykonten" style="margin-bottom:10px !important;" >
						<tr>
							<td style="width:8%;">Job No</td>
							<td style="width:1%;text-align: center;">:</td>
							<td style="width:29%;">
								<select name="JobNo" id="JobNo" class="form-control input-sm select2">
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

					<table width="100%" border="0" class="mykonten" style="margin-bottom:10px !important;" >
						<tr>
							<td style="width:8%;">Alokasi</td>
							<td style="width:1%;text-align: center;">:</td>
							<td style="width:29%;">
								<select name="Alokasi" id="Alokasi" class="form-control input-sm select2">
									<option value="">-- Pilih Alokasi --</option>
									<?php
									foreach (list_job_alokasi() as $data_alokasi) :
										?>
										<option value="<?= $data_alokasi['Alokasi'] ?>"><?= $data_alokasi['Alokasi'] . ' - ' . $data_alokasi['Keterangan'] ?></option>
									<?php endforeach; ?>
								</select>
							</td>
							<td></td>
						</tr>
					</table>

					<table width="100%" border="0" class="mykonten" style="margin-bottom:10px !important;" >
						<tr>
							<td style="width:8%;">Periode</td>
							<td style="width:1%;text-align: center;">:</td>
							<td style="width:14%;">
								<input type="date" name="dari_tgl" id="dari_tgl" value="<?= (isset($tgl_pertama)) ? $tgl_pertama : '
								' ?>" class="form-control input-sm">
							</td>
							<td style="width:2%;text-align: center;">s.d</td>
							<td style="width:13%;">
								<input type="date" name="sampai_tgl" id="sampai_tgl" value="<?= (isset($tgl_terakhir)) ? $tgl_terakhir : '
								' ?>" class="form-control input-sm">
							</td>
							<td style="padding-left:10px;">

								<input type="checkbox" name="all_periode" id="all_periode" value="1">
								<label for="all_periode">All Periode</label>
							</td>
							<td></td>
						</tr>
					</table>



					<table width="100%" border="0" class="mykonten" >
						<tr>
							<td style="width:8%;"></td>
							<td style="width:1%;text-align: center;"></td>
							<td style="width:29%;">
								<button id="btn_tampil_dppd" class="btn btn-sm btn-success">Tampilkan <i class="fa fa-search"></i> </button>
								<button id="btn_loading" class="btn btn-sm btn-danger" disabled="disabled" style="display:none;">Loading... <i class="fa fa-spinner"></i></button>
							</td>	
							<td></td>
						</tr>
					</table>


					

				</div>


			</div>

		</div>

		<div id="tampil_table"></div>

	</section>


</div>
<script type="text/javascript">

	

	$(function() {


		$('.select2').select2({
			'width': '100%',
		});

		$('#btn_tampil_dppd').click(function() {

			var JobNo = $('#JobNo').val();
			var Alokasi = $('#Alokasi').val();
			var dari_tgl = ($('#dari_tgl').val()) ? $('#dari_tgl').val() : '';
			var sampai_tgl = ($('#sampai_tgl').val()) ? $('#sampai_tgl').val() : '';

			var all_periode = 0;


			if (JobNo == '' || Alokasi == '') {
				$.toast({
					text: 'Pilih Job No / Alokasi terlebih dahulu',
					position: 'bottom-right',
					stack: false,
					hideAfter: 1000,
					icon: 'info',

				});
				return false;
			}

			if ($("#all_periode").is(':checked')) {
				all_periode = 1;

			} else {
				all_periode = 0;
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

			}


			jQuery.ajax({

				url: '<?= site_url("Laporan/Lap_DPPD/view_table_dppd") ?>',
				type: 'post',
				dataType: 'html',
				data: { 
					'JobNo': JobNo,
					'Alokasi': Alokasi,
					'dari_tgl': dari_tgl,
					'sampai_tgl': sampai_tgl,
					'all_periode': all_periode,

				},
				beforeSend : function(){
					$('#btn_tampil_dppd').hide();
					$('#btn_loading').show();
					$('#tampil_table').html('');
				},
				complete: function(xhr, textStatus) {
					$('#btn_tampil_dppd').show();
					$('#btn_loading').hide();
				},
				success: function(data, textStatus, xhr) {
					$('#tampil_table').html(data);
					// alert(data);
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



		});

		$('#all_periode').change(function(){
			atur_all_periode();
			// $('#btn_tampil_dppd').trigger('click');
		});

		




	});

	function atur_all_periode(){
		if (($('#all_periode').is(':checked')) ) {

			$('#sampai_tgl').attr('readonly',true);
			$('#dari_tgl').attr('readonly',true);

		}else{

			$('#sampai_tgl').attr('readonly',false);
			$('#dari_tgl').attr('readonly',false);

		}
	}
</script>