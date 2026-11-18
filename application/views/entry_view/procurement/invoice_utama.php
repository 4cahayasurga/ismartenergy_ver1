<style type="text/css">
	body{
		font-size: 12px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}

	.huruf_10{
		font-size: 10px !important;
	}
	.huruf_11{
		font-size: 11px !important;
	}
	.huruf_12{
		font-size: 12px !important;
	}
	.huruf_13{
		font-size: 13px !important;
	}
	.huruf_14{
		font-size: 14px !important;
	}
	.huruf_15{
		font-size: 15px !important;
	}
	.huruf_16{
		font-size: 16px !important;
	}
	.swal2-popup {
		font-size: 1.6rem !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}
</style>

<div class="content-wrapper">

	<section class="content-header">

		<div class="row">
			<div class="col-sm-12">
				<h5 style="font-size:14px;font-weight: bold;" class="gaya_huruf"><?=(isset($judul)) ? $judul : '' ?></h5>
			</div>
		</div>

		<div class="row">
			<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>
			<div class="col-sm-12">
				<div class="box box-primary">
					<div class="box-body">
						
						<div class="table-responsive huruf_10">
							<table width="100%"  border="0" style="border-collapse:collapse;">
								<tr>
									<th width="5%">Job No</th>
									<th width="1%" style="padding-left:3px;padding-right:3px;text-align: center;">:</th>
									<td width="30%">
										<select name="JobNo" id="JobNo" class="form-control input-sm" required>
											<option value="">-- Pilih Job --</option>
											<?php foreach ($list_job_akses as $job_param): ?>
												<option value="<?=$job_param['JobNo']?>" <?=($JobNo == $job_param['JobNo']) ? 'selected' : '' ?> ><?=$job_param['JobNo'].' - '.$job_param['JobNm']?></option>
											<?php endforeach ?>
										</select>
									</td>
									<td></td>
								</tr>
								<tr>
									<th width="5%"></th>
									<th width="1%" style="padding-left:3px;padding-right:3px;text-align: center;"></th>
									<td width="30%">
										<p></p>
										<button type="submit" class="btn btn-sm bg-gray color-pallete huruf_10" id="btn_search" ><i class="fa fa-search"></i>&nbsp;SEARCH</button>
									</td>
									<td></td>
								</tr>
							</table>
						</div>

					</div>
					<div class="overlay" id="myloading" style="display: none;">
						<i class="fa fa-refresh fa-spin"></i>
					</div>
				</div>
			</div>
		</div>

		<div id="tampil" ></div>

	</section>

	<div class="modal fade" id="modalTambah">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-body" id="modalbodyTambah">

				</div>
			</div>

		</div>

	</div>
</div>

</div>



<script src="<?=base_url('assets/select2_baru.js')?>"></script>
<script type="text/javascript">
	$(document).ready(function(){


		if ($('#JobNo :selected').val() != null || $('#JobNo :selected').val() !=''  ) {
			table_invoice();
		}

		$('#JobNo').select2({
			'width': '100%',
			'dropdownCssClass': "huruf_10"
		});

		$('#btn_search').click(function(){
			table_invoice();
		});

		$('#JobNo').change(function(){
			table_invoice();
		});



	});

	function tambah_baru(){

		var JobNo = $('#JobNo :selected').val();
		if (JobNo == '') {
			swal("Warning!","Job Masih Kosong!","warning");
			return false;
		}

		$('#modalbodyTambah').html('');
		
		jQuery.ajax({
			url: '<?=site_url("Entry/Procurement/Invoice/tambah_invoice_baru")?>',
			type: 'POST',
			dataType: 'html',
			data: {JobNo : JobNo},
			cache : false,
			beforeSend : function(){
				$('#tbl_loading').show();
			},
			complete : function(){
				$('#tbl_loading').hide();
			},
			success: function(data, textStatus, xhr) {
				
				$('#modalbodyTambah').html(data);
				$('#modalTambah').modal('show');

			},
			error: function(xhr, textStatus, errorThrown) {
				swal("Oops!","Something wrong!","error");
				return false;
			}
		});


	}

	function table_invoice(){

		$('#tampil').html('');
		var JobNo = $('#JobNo :selected').val();

		if (JobNo == '') {
			swal("Warning!","Job Masih Kosong!","warning");
			return false;
		}

		jQuery.ajax({
			url: '<?=site_url("Entry/Procurement/Invoice/table_invoice")?>',
			type: 'POST',
			dataType: 'html',
			data: {JobNo : JobNo},
			cache : false,
			beforeSend : function(){
				$('#myloading').show();
			},
			complete: function(xhr, textStatus) {
				$('#myloading').hide();
			},
			success: function(data, textStatus, xhr) {
				$('#tampil').html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				swal("Oops!","Something wrong!","error");
				return false;
			}
		});


	}


	function tutup_modal(){
		$('#modalTambah').modal('hide');
	}

	function btn_hapus_invoice(LedgerNo=''){
		if (LedgerNo == '') {
			return false;
		}


		// return false;
		Swal.fire({
			title: 'Delete??',
			text: "Yakin ingin menghapus data ini?!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Hapus!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.value) {

				jQuery.ajax({
					url: '<?=site_url("Entry/Procurement/Invoice/aksi_hapus_invoice")?>',
					type: 'POST',
					dataType: 'JSON',
					data: {LedgerNo : LedgerNo},
					cache : false,
					beforeSend : function(){
						$('#btn_hapus'+LedgerNo).attr('disabled',true);
					},
					complete : function(){
						$('#btn_hapus'+LedgerNo).attr('disabled',false);
					},
					success: function(data, textStatus, xhr) {
						// alert(data);
						swal(data.teks_label,data.teks_pesan,data.teks_icon);
						table_invoice();
						return false;
					},
					error: function(xhr, textStatus, errorThrown) {
						swal("Oops!","Something wrong!","error");
						return false;
					}
				});


			}
		});


	}

	function btn_edit_invoice(LedgerNo=''){
		if (LedgerNo == '') {
			return false;
		}

		var JobNo = $('#JobNo :selected').val();
		if (JobNo == '') {
			swal("Warning!","Job Masih Kosong!","warning");
			return false;
		}

		$('#modalbodyTambah').html('');
		$('#modalTambah').modal('show');

		jQuery.ajax({
			url: '<?=site_url("Entry/Procurement/Invoice/dapat_form_edit_invoice")?>',
			type: 'POST',
			dataType: 'html',
			data: {
				LedgerNo : LedgerNo, 
				JobNo : JobNo
			},
			cache : false,
			beforeSend : function(){
				$('#btn_edit'+LedgerNo).attr('disabled',true);
			},complete : function(){
				$('#btn_edit'+LedgerNo).attr('disabled',false);
			},
			success: function(data, textStatus, xhr) {
				$('#modalbodyTambah').html(data);
				return false;
			},
			error: function(xhr, textStatus, errorThrown) {
				swal("Oops!","Something wrong!","error");
				return false;
			}
		});

	}

	


</script>