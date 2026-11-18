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
		<div class="row">
			<div class="col-sm-6" style="font-size: 18px !important;font-weight: bold;"><?=$judul?><br>
				<button class="btn btn-md btn-default" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>
			</div>
			<div class="col-sm-6" style="text-align:right;font-size: 12px;">

				<ol class="breadcrumb">
					<li><a href="<?= site_url('dashboard')  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active"><?=$judul?></li>
				</ol>
			</div>

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

					<div class="row">

						<div class="col-sm-6">

							<form id="FrmHutangKO">
								<table border="0" width="100%">

									<tr>
										<th style="padding-top:5px;padding-bottom:15px;" width="10%">Job No</th>
										<td style="padding-left:5px;padding-right:5px;" width="2%">:</td>
										<td style="padding-top:5px;padding-bottom:5px;padding-left:5px;padding-right:5px;" >
											<select name="JobNo" id="JobNo" class="form-control input-sm select2" required>
												<option value="">-- Pilih Salah Satu</option>
												<!-- <option value="All Job">All Job</option> -->
												<?php
												foreach ($data_job as $data_job) :
													?>
													<option value="<?= $data_job['JobNo'] ?>" ><?= $data_job['JobNo'] . ' - ' . $data_job['JobNm'] ?></option>

												<?php endforeach; ?>
											</select>
										</td>
									</tr>

								</table>

								<table border="0" width="100%">

									<tr>
										<th style="padding-top:5px;padding-bottom:5px;" width="10%">Periode</th>
										<td style="padding-left:5px;padding-right:5px;" width="2%">:</td>
										<td style="padding-top:5px;padding-bottom:5px;padding-left:5px;padding-right:5px;">
											<input type="date" class="form-control input-sm" name="DariTgl" id="DariTgl" value="<?=$DariTgl?>" required  >
										</td>
										<th style="padding-top:5px;padding-bottom:5px;padding-left:5px;padding-right:5px;" width="10%">s.d.</th>
										<td style="padding-top:5px;padding-bottom:5px;padding-left:5px;padding-right:5px;">
											<input type="date" class="form-control input-sm" name="SampaiTgl" id="SampaiTgl" value="<?=$SampaiTgl?>" required  >
										</td>
									</tr>

								</table>

								<table border="0" width="100%">

									<tr>
										<th style="padding-top:5px;padding-bottom:15px;" width="10%"></th>
										<td style="padding-left:5px;padding-right:5px;" width="2%"></td>
										<td style="padding-top:5px;padding-bottom:5px;padding-left:5px;padding-right:5px;" >
											<button type="submit"  id="btn_hutangKO" class="btn btn-md btn-default"><i class="fa fa-search"></i> Tampilkan</button>
											<button type="button" id="btn_hutangKO_loading" class="btn btn-md btn-default hidden disabled"><i class="fa fa-spinner fa-spin"></i> Loading... </button>
										</td>
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

	<script src="<?=base_url('assets/select2_baru.js')?>"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.select2').select2({
				'width': '100%',
			});

			$('#FrmHutangKO').submit(function(e){
				e.preventDefault();
				dapatkan_Table_HutangKO();
			});

		});



		function dapatkan_Table_HutangKO(){

			var JobNo = $('#JobNo').val();
			var DariTgl = $('#DariTgl').val();
			var SampaiTgl = $('#SampaiTgl').val();

			if (JobNo == '' || JobNo == null) {
				Swal.fire('Alert!','Job belum dipilih','error');
				return false;
			}

			if (DariTgl == '' ||  SampaiTgl == '') {
				Swal.fire('Alert!','Periode belum dipilih','error');
				return false;
			}

			if (SampaiTgl < DariTgl) {
				Swal.fire('Alert!','Periode tidak sesuai','error');
				return false;
			}

			// console.log(JobNo+' - '+DariTgl+' - '+SampaiTgl);
			// return false;

			jQuery.ajax({
				url: '<?=site_url('Laporan/Procurement/Rekap_Hutang_KO/dapatkan_Table_HutangKO')?>',
				type: 'POST',
				dataType: 'HTML',
				async: true,
				cache : false,
				data: $('#FrmHutangKO').serialize(),
				beforeSend : function(){
					$('#btn_hutangKO').hide();
					$('#btn_hutangKO_loading').show();
					$('.tampil_table').html('');
				},
				complete: function(xhr, textStatus) {
					$('#btn_hutangKO').show();
					$('#btn_hutangKO_loading').hide();
				},
				success: function(data, textStatus, xhr) {
					$('.tampil_table').html(data);
				},
				error: function(xhr, textStatus, errorThrown) {
					alert('error');
				}
			});



		}
	</script>
