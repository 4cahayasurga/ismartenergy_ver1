<style type="text/css">
	.atur_ukuran_font{
		font-size: 12px !important;
		font-family: "Times New Roman", Times, serif;
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
		<button onclick="history.back()" class="btn btn-sm btn-default" ><i class="fa fa-arrow-left"></i> Back</button>
	</section>

	<section class="content-header">
		<?= ($this->session->flashdata('message')) ? $this->session->flashdata('message') : ''  ?>
		<!-- <?=$this->session->flashdata('message')?> -->

		<div class="row" >

			<div class="col-sm-12">

				<div class="box box-success">
					<div class="box-header">
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>

					<div class="box-body">

						<table border="0" id="table_saya">
							<tr>
								<td class="atur_ukuran_font" width="80px">Job.No</td>
								<td class="atur_ukuran_font" width="20px" align="center">:</td>
								<td class="atur_ukuran_font" width="300px">

									<input type="hidden" name="JobNo" id="JobNo" class="form-control" value="<?=$JobNo?>" required>

									<select  class="form-control select2" disabled required>
										<?php
										foreach (dapat_list_job() as $data_job) :
											?>
											<option value="<?= $data_job['JobNo'] ?>" <?= ($JobNo == $data_job['JobNo']) ? 'selected' : ''  ?>><?= $data_job['JobNo'] . ' - ' . $data_job['JobNm'] ?></option>
										<?php endforeach; ?>

									</select>
								</td>
							</tr>
						</table>

						<table border="0" style="margin-top:5px !important;" >
							<tr>
								<td class="atur_ukuran_font" width="80px">Alokasi</td>
								<td class="atur_ukuran_font" width="20px" align="center">:</td>
								<td class="atur_ukuran_font" width="300px">
									<select name="Alokasi" id="Alokasi" class="form-control select2" required>
										<option value="">-- Pilih salah satu --</option>
										<?php
										foreach (list_job_alokasi() as $data_alokasi) :
											?>
											<option value="<?= $data_alokasi['Alokasi'] ?>"><?= $data_alokasi['Alokasi'] . ' - ' . $data_alokasi['Keterangan'] ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
						</table>

						<table border="0" style="margin-top:5px !important;">
							<tr>
								<td class="atur_ukuran_font" width="80px"></td>
								<td class="atur_ukuran_font" width="20px" align="center"></td>
								<td class="atur_ukuran_font" width="300px">
									<button type="button" class="btn btn-md bg-maroon btn-flat " id="btn_cari_pj">Cari</button>
								</td>
							</tr>
						</table>
						

					</div>
					<div class="overlay loadingsaya" style="display: none;">
						<i class="fa fa-refresh fa-spin"></i>
					</div>


				</div>


			</div>

		</div>

		<div id="tampil_table_pj"></div>

	</section>

</div>

<section></section>

<script type="text/javascript">
	$(document).ready(function(){
		$('.select2').select2();

		$('#Alokasi').change(function(){
			dapat_list_pj_keuangan();
		});

		$('#btn_cari_pj').click(function(){
			dapat_list_pj_keuangan();
		})



	})

	function dapat_list_pj_keuangan(){

		var JobNo = $('#JobNo').val();
		var Alokasi = $('#Alokasi').val();

		if (Alokasi == '') {
			$.toast({
				text: 'Pilih Alokasi terlebih dahulu',
				position: 'top-right',
				stack: false,
				hideAfter: 3000,
				icon: 'info',

			});
			return false;
		}

		$.ajax({
			url: "<?=site_url('PDPJ/PJ_keuangan_entry_controller/dapat_list_pj_keuangan')?>",
			type: 'post',
			dataType : 'html',
			data: {
				'id' : 1, 
				'JobNo' : JobNo,
				'Alokasi' : Alokasi
			}, beforeSend : function(){
				$('.loadingsaya').show();
				$('#tampil_table_pj').html('');
			},success: function (data) {
				$('.loadingsaya').hide();
				// alert(data);
				$('#tampil_table_pj').html(data);

			},error : function(){
				$('.loadingsaya').hide();
				$.toast({
					text: 'Terjadi Kesalahan',
					position: 'top-right',
					stack: false,
					hideAfter: 3000,
					icon: 'error',

				});
				return false;
			}
		});

	}

	function aksi_approve_pj(LedgerNo,flag_status_approve,NoPD){

		Swal.fire({
			title: 'Anda Yakin?',
			// text: "perubahan data tidak dapat dikembalikan!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Lakukan!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.value) {

				var status_nama = (flag_status_approve == 0) ? 'APPROVE' : 'UNAPPROVE';

				$.ajax({
					url: "<?=site_url('PDPJ/PJ_keuangan_entry_controller/aksi_approve_pj_keuangan')?>",
					type: 'post',
					dataType : 'JSON',
					data: {
						'LedgerNo' : LedgerNo,
						'flag_status_approve' : flag_status_approve,
						'NoPD' : NoPD
					}, beforeSend : function(){
						$('.loadingsaya').show();

					},complete : function(){
						dapat_list_pj_keuangan();
					},success: function (data) {
						$('.loadingsaya').hide();

						console.log(data);

						if (data.status == 'data_tidak_ada') {
							$.toast({
								text: data.pesan,
								position: 'bottom-right',
								stack: false,
								hideAfter: 3000,
								icon: 'error',

							});
							return false;
						}

						if (data.status == 'belum_bayar') {
							$.toast({
								text: data.pesan,
								position: 'bottom-right',
								stack: false,
								hideAfter: 3000,
								icon: 'error',

							});
							return false;
						}

						if (data.status == 'pj_masih_kosong') {
							$.toast({
								text: data.pesan,
								position: 'bottom-right',
								stack: false,
								hideAfter: 3000,
								icon: 'error',

							});
							return false;
						}

						if (data.status == 'sudah_approvedKT') {
							$.toast({
								text: data.pesan,
								position: 'bottom-right',
								stack: false,
								hideAfter: 3000,
								icon: 'error',

							});
							return false;
						}

						if (data.status == 'sudah_approvedKK') {
							$.toast({
								text: data.pesan,
								position: 'bottom-right',
								stack: false,
								hideAfter: 3000,
								icon: 'error',

							});
							return false;
						}

						if (data.status == 'berhasil') {
							$.toast({
								text: data.pesan,
								position: 'bottom-right',
								stack: false,
								hideAfter: 3000,
								icon: 'success',

							});
							return false;
						}

						if (data.status == 'gagal') {
							$.toast({
								text: data.pesan,
								position: 'bottom-right',
								stack: false,
								hideAfter: 3000,
								icon: 'success',

							});
							return false;
						}

						


						return false;


					},error : function(){
						$('.loadingsaya').hide();
						$.toast({
							text: 'Terjadi Kesalahan',
							position: 'top-right',
							stack: false,
							hideAfter: 3000,
							icon: 'error',

						});
						return false;
					}
				});

			}

		});



		

	}


</script>