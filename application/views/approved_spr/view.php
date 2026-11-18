<style type="text/css">
	select[readonly].select2-hidden-accessible + .select2-container {
		pointer-events: none;
		touch-action: none;
	}

	select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
		background: #eee;
		box-shadow: none;
	}

	select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow, select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
		display: none;
	}

</style>

<div class="content-wrapper">

	<section class="content-header">
		<h4 class="text-bold" style="text-decoration: underline;"><?= (isset($judul)) ? strtoupper($judul) : '' ?></h4>
		<a type="button" class="btn btn-danger btn-md" href="<?=site_url('Job/sub_job/'.$JobNo)?>"><i class="fa fa-arrow-left"></i> BACK</a>

		<ol class="breadcrumb">
			

		</ol>
	</section>

	<section class="content">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

		<div class="box box-primary">


			<div class="box-body">
				<form id="myform">

					<table style="width:100%;border-collapse:collapse;" border="0">
						<tr>
							<td width="7%">Job No</td>
							<td class="text-center" width="1%">:</td>
							<td width="30%">
								<select name="JobNo" class="form-control select2" <?=($JobNo !='') ? 'readonly' : ''?>  required>
									<option value="">-- Pilih Salah Satu --</option>
									<?php foreach ($ListJob as $row):?>
										<option value="<?=$row->JobNo?>"  <?=($row->JobNo==$JobNo) ? 'selected' : '' ?> ><?=$row->JobNo.' - '.$row->JobNm?></option>
									<?php endforeach ?>
								</select>
							</td>
							<td width="10%" style="padding-left:10px;">
								<button type="submit" class="btn btn-primary btn-sm btn_cari">SEARCH</button>
								<span class="loading_saya" style="display: none;">Loading.. <i class="fa fa-spinner fa-spin"></i></span>
							</td>
							<td></td>
						</tr>
					</table>


				</form>

			</div>
			<div class="box-footer tampil_tbl_utama">
				
			</div>



		</div>


	</section>

</div>

<div class="modal fade" id="ModalNotif" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<!-- <h4 class="modal-title" id="gridSystemModalLabel">Data Entry</h4> -->
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h4>Surat Permintaan Material/Alat tidak dapat di-unapprove karena, sudah digunakan oleh salah satu KO.</h4>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="MyModal">
	<div class="modal-dialog modal-lg ">
		<div class="modal-content">
			<div class="modal-header modal-info">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="MyModalTitle"></h4>
			</div>
			<div class="modal-body" id="MyModalBody">

			</div>


		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		$('.select2').select2({
			width : '100%'
		});

		// $('.btn_cari').hide();

		if($('#myform select[name="JobNo"] :selected').val() !=''){
			tbl_ApprovalSPR();
		}

		$('#myform').submit(function(event) {
			event.preventDefault();
			tbl_ApprovalSPR();
		});

		$('#myform select[name="JobNo"]').change(function(){
			tbl_ApprovalSPR()
		})
	})

	function AksiApprove(NoSPR,TxtApprove){

		var TxtApprove_ubah = (TxtApprove == 'APPROVE') ? 'Approve' : 'Unapprove';
		Swal.fire({
			title: 'yakin ingin '+TxtApprove+' data berikut? ',
			showDenyButton: true,
			showCancelButton: true,
			confirmButtonText: 'Ya',
		}).then((result) => {
			if (result.isConfirmed) {

				jQuery.ajax({
					url: '<?=site_url("Approved_spr/AksiApprove")?>',
					type: 'POST',
					dataType: 'html',
					data: {NoSPR : NoSPR, TxtApprove : TxtApprove},
					beforeSend : function(){

					},
					complete: function(xhr, textStatus) {

					},
					success: function(data, textStatus, xhr) {
						// alert(data);
						tbl_ApprovalSPR();
						$.toast({
							text: 'Success',
							position: 'top-right',
							icon : 'success'
						});

					},
					error: function(xhr, textStatus, errorThrown) {
						$.toast({
							text: 'terjadi kesalahan',
							position: 'top-right',
							icon : 'error'
						});
					}
				});
			}
		})

	}

	function tbl_ApprovalSPR(){

		var JobNo = $('#myform select[name="JobNo"] :selected').val();

		jQuery.ajax({
			url: '<?=site_url("Approved_spr/tbl_ApprovalSPR")?>',
			type: 'POST',
			dataType: 'html',
			data: {JobNo : JobNo},
			beforeSend : function(){
				$('.btn_cari').hide();
				$('.loading_saya').show();
				$('.tampil_tbl_utama').html('');
			},
			complete: function(xhr, textStatus) {
				$('.btn_cari').show();
				$('.loading_saya').hide();
			},
			success: function(data, textStatus, xhr) {
				// alert(data);
				$('.tampil_tbl_utama').html(data);

			},
			error: function(xhr, textStatus, errorThrown) {
				$.toast({
					text: 'terjadi kesalahan',
					position: 'top-right',
					icon : 'error'
				});
			}
		});
		
	}

	function getDetailSingkat(JobNo,NoSPR){
		if (JobNo == '' || NoSPR == '') {
			return false;
		}


		$('#MyModal').modal('show');
		$('#MyModalTitle').text('Detail SPR - '+JobNo+ ' / '+NoSPR);

		$.ajax({
			url: '<?=site_url('Approved_spr/getDetailSingkat')?>',
			type: 'POST',
			dataType: 'HTML',
			data:{
				'JobNo' : JobNo,
				'NoSPR' : NoSPR
			},
			beforeSend : function(){
				$('.my-loading').show();
			}
		})
		.done(function(data) {
			$('.my-loading').hide();
			$('#MyModalBody').html(data)
		})
		.fail(function() {
		})
		.always(function() {
		});

	}
</script>
