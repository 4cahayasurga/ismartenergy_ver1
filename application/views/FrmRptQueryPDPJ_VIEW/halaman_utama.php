<style type="text/css">
	.font10 {
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size: 10px;
	}

	.font11 {
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size: 11px;
	}

	.font13 {
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	.font12 {
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size: 12px;
	}

	.font14 {
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size: 14px;
	}

	.font16 {
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size: 16px;
	}

	.jarak5 {
		padding-top: 5px;
		padding-bottom: 5px;
	}

	#tbl_search tr td {
		padding-top: 5px;
		padding-bottom: 5px;
	}

	.swal2-popup {
		font-size: 1.4rem !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	.swal-wide {
		width: 550px !important;
	}
</style>
<div class="content-wrapper">

	<section class="content">
		<div class="flashdata" data-flash="<?= $this->session->flashdata('message') ?>"></div>
		<table>
			<tr>
				<td class="font16 text-bold"><?= ($judul) ? strtoupper($judul) : 'Judul belum diatur'  ?></td>
			</tr>
		</table>

		<br>

		<div class="row">
			<div class="col-sm-12">
				<div class="box box-primary">
					<div class="box-body">

						<form id="MyForm">

							<div class="table-responsive">


								<table width="100%" border="0" style="border-collapse:collapse;">
									<tr>
										<td width="50px" class="myFont">Job No</td>
										<td width="10px" class="text-center myFont">:</td>
										<td width="500px" class="">
											<select name="JobNo" id="JobNo" class="form-control form-control-sm    select2" required>
												<option value="">-- Pilih salah satu --</option>
												<!-- <option value="All Job">All Job</option> -->
												<?php foreach ($dataJob as $row) : ?>
													<option value="<?= $row->JobNo ?>" data-JobNm="<?= $row->JobNm ?>"><?= $row->JobNo ?> - <?= $row->JobNm ?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td></td>
									</tr>
								</table>
								<p></p>

								<table class="jarak_atas" width="100%" border="0" style="border-collapse:collapse;">
									<tr>
										<td width="50px" class="myFont">Alokasi</td>
										<td width="10px" class="text-center myFont">:</td>
										<td width="500px" class="">
											<select name="Alokasi" id="Alokasi" class="form-control form-control-sm  select2" required>
												<!-- <option value="">-- Pilih salah satu --</option> -->
												<option value="All Alokasi">All Alokasi</option>
												<?php foreach ($dataAlokasi as $row) : ?>
													<option value="<?= $row->Alokasi ?>"><?= $row->Alokasi ?> - <?= $row->Keterangan ?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td></td>
									</tr>
								</table>
								<p></p>

								<table class="jarak_atas" width="100%" border="0" style="border-collapse:collapse;">
									<tr>
										<td width="50px"></td>
										<td width="10px" class="text-center"></td>
										<td width="500px">
											<button type="submit" class="btn btn-primary btn-md" id="btnTampilkan">Tampilkan</button>
										</td>
										<td></td>
									</tr>
								</table>


							</div>

						</form>

					</div>

				</div>
			</div>
		</div>

		<div id="table_QueryPDPJ">

		</div>

	</section>


</div>

<script src="<?= base_url('assets/select2_baru.js') ?>"></script>
<script type="text/javascript">
	$(function() {

		var flashdata = $('.flashdata').data('flash')
		if (flashdata != '') {
			Swal.fire(
				'Alert!',
				flashdata,
				'info'
			)
		}

		$('.select2').select2({
			dropdownCssClass: "myFont"
		});

		$('#MyForm').submit(function(event) {
			event.preventDefault();
			TampilkanTable_QueryPDPJ();
		});

	})

	function TampilkanTable_QueryPDPJ() {

		var JobNo = $('#JobNo').val();
		var JobNm = $('#JobNo :selected').attr('data-JobNm');
		var Alokasi = $('#Alokasi').val();

		if (JobNo == '') {
			Swal.fire(
				'Alert!',
				'Job belum dipilih',
				'info'
			)
			return false;
		}

		if (Alokasi == '') {
			Swal.fire(
				'Alert!',
				'Alokasi belum dipilih',
				'info'
			)
			return false;
		}


		var data = $("#MyForm").serializeArray();
		data.push({
			name: "JobNm",
			value: JobNm
		});

		jQuery.ajax({
			url: '<?= site_url('FrmRptQueryPDPJ/TampilkanTable_QueryPDPJ') ?>',
			type: 'POST',
			dataType: 'HTML',
			data: data,
			beforeSend: function() {
				$('#btnTampilkan').attr('disabled', true).text('Loading...');
				$('#table_QueryPDPJ').html('');
			},
			complete: function(xhr, textStatus) {
				$('#btnTampilkan').attr('disabled', false).text('Tampilkan');
			},
			success: function(data, textStatus, xhr) {
				$('#table_QueryPDPJ').html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				toastr.error('Error', '');
			}
		});

	}
</script>