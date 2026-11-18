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

					<table width="100%" border="0">
						<tr>
							<td style="width:100px;">Job. No</td>
							<td style="width:20px;text-align: center;">:</td>
							<td style="width:300px;">
								<select name="JobNo" id="JobNo" class="form-control input-sm select2" readonly required>
									<option value="">-- Pilih salah satu --</option>
									<?php
									foreach (dapat_list_job($JobNo) as $data_job) :
										?>
										<option value="<?= $data_job['JobNo'] ?>" <?= ($JobNo == $data_job['JobNo']) ? 'selected' : ''  ?>><?= $data_job['JobNo'] . ' - ' . $data_job['JobNm'] ?></option>

									<?php endforeach; ?>
								</select>
							</td>
							<td></td>
						</tr>
					</table>
					<p></p>

					<table width="100%" border="0" style="padding-top: 10px;">
						<tr>
							<td style="width:100px;">Alokasi</td>
							<td style="width:20px;text-align: center;">:</td>
							<td style="width:300px;">
								<select name="Alokasi" id="Alokasi" class="form-control input-sm"  required>
									<option value="">-- Pilih Salah Satu</option>
									<?php foreach ($data_alokasi as $alokasi) : ?>
										<option value="<?= $alokasi->Alokasi ?>" <?=($alokasi->Alokasi ==  $my_alokasi ) ? 'selected' : '' ?> ><?= $alokasi->Alokasi ?> - <?= $alokasi->Keterangan ?></option>
									<?php endforeach; ?>
								</select>
							</td>
							<td></td>
						</tr>
					</table>
					<p></p>

					<table width="100%" border="0" style="padding-top: 10px;">
						<tr>
							<td style="width:100px;"></td>
							<td style="width:20px;text-align: center;"></td>
							<td style="width:300px;">
								<button type="button" class="btn bg-maroon btn-sm" id="btn_tampilkan">Tampilkan</button>
							</td>
							<td></td>
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

		$('#Alokasi').change(function(){
			var JobNo = this.value;
			var Alokasi = $('#Alokasi :selected').val();
			// alert(JobNo+' - '+Alokasi);
			if (JobNo == '' ) {
				$.toast({
					text: 'belum pilih job',
					position: 'bottom-right',
					stack: false,
					hideAfter: 1000,
					icon: 'info',

				});
				return false;
			}

			if (Alokasi == '' ) {
				$.toast({
					text: 'belum pilih alokasi',
					position: 'bottom-right',
					stack: false,
					hideAfter: 1000,
					icon: 'info',

				});
				return false;
			}

			dapat_table_pd_keuangan();
		})

		$('#btn_tampilkan').click(function(){
			var JobNo = $('#JobNo :selected').val();
			var Alokasi = $('#Alokasi :selected').val();
			// alert(JobNo+' - '+Alokasi);
			if (JobNo == '' ) {
				$.toast({
					text: 'belum pilih job',
					position: 'bottom-right',
					stack: false,
					hideAfter: 1000,
					icon: 'info',

				});
				return false;
			}

			if (Alokasi == '' ) {
				$.toast({
					text: 'belum pilih alokasi',
					position: 'bottom-right',
					stack: false,
					hideAfter: 1000,
					icon: 'info',

				});
				return false;
			}

			dapat_table_pd_keuangan();

		})
	});


	function dapat_table_pd_keuangan(){
		var JobNo = $('#JobNo').val();
		var Alokasi = $('#Alokasi :selected').val();
		// alert(JobNo+' - '+Alokasi);

		jQuery.ajax({
			url: '<?=site_url('Menu_entry/PD_keuangan/dapat_table_pd_keuangan')?>',
			type: 'POST',
			dataType: 'HTML',
			async: true,
			cache : false,
			data: {

				'JobNo' : JobNo,
				'Alokasi' : 'Alokasi'

			},
			beforeSend : function(){
				$('#btn_simpan').attr('disabled',true).text('Loading...');
			},
			complete: function(xhr, textStatus) {
				$('#btn_simpan').attr('disabled',false).text('Simpan');
				$('#Modal_PF').modal('hide');
				location.reload();
			},
			success: function(data, textStatus, xhr) {
					// alert(data);
					
				},
				error: function(xhr, textStatus, errorThrown) {
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


</script>