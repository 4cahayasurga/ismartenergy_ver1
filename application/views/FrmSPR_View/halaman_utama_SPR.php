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
		<h4 class="text-bold" style="text-decoration: underline;"><?= (isset($judul)) ? strtoupper($judul) : '' ?></h4><br>
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

<script type="text/javascript">
	$(function(){
		$('.select2').select2({
			width : '100%'
		});

		// $('.btn_cari').hide();

		if($('#myform select[name="JobNo"] :selected').val() !=''){
			tbl_utama_SPR();
		}

		$('#myform').submit(function(event) {
			event.preventDefault();
			tbl_utama_SPR();
		});

		$('#myform select[name="JobNo"]').change(function(){
			tbl_utama_SPR()
		})
	})

	function tbl_utama_SPR(){

		var JobNo = $('#myform select[name="JobNo"] :selected').val();
			

	
		jQuery.ajax({
			url: '<?=site_url("FrmSPR/tbl_utama_SPR")?>',
			type: 'POST',
			dataType: 'html',
			data: {JobNo : JobNo},
			beforeSend : function(){
				// $('#myform select[name="JobNo"] :selected').hide();
				$('.btn_cari').hide();
				$('.loading_saya').show();
				$('.tampil_tbl_utama').html('');
			},
			complete: function(xhr, textStatus) {
				// $('#myform select[name="JobNo"] :selected').show();
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
</script>
