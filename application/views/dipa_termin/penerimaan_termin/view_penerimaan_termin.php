<style>

	.huruf14{
		font-size: 14px !important;
	}
</style>

<div class="content-wrapper">


	<section class="content-header">
		<h1 class="text-bold">
			<?=$judul?>
			<?php if ($JobNo!=''): ?>
				<small style="font-size: 16px;" class="text-black">(<?=$JobNo.' - '.$JobNm.' - '.$Lokasi?>)</small>
			<?php endif ?>
		</h1>
		<a href="<?=site_url('Job/sub_job/'.$JobNo)?>" class="btn btn-default btn-md" ><i class="fa fa-arrow-left"></i>  BACK</a>
	</section>

	<section class="content">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

		<div class="box box-primary">
			<div class="box-header">
				<div class="row">
					<div class="col-sm-12">

						<form id="myform" action="<?=site_url('DipaTermin/PenerimaanTermin/list_termin/')?>" data-TxtUrl="<?=site_url('DipaTermin/PenerimaanTermin/list_termin/')?>" method="POST">
							<div class="table-responsive">

								<table width="100%" border="0">
									<tr style="font-size:14px;">
										<td width="100px"> Job No </td>
										<td width="10px" class="text-center">:</td>
										<td width="500px">
											<select name="JobNo" id="JobNo" class="form-control input-sm select2" style="font-size:14px;" required>
												<option value="">:: Pilih Salah Satu ::</option>
												<?php foreach ($ListJob as $row): ?>
													<option value="<?=$row->JobNo?>" <?=($JobNo == $row->JobNo) ? 'selected' : '' ?>  ><?=$row->JobNo.' - '.$row->JobNm?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td></td>
									</tr>
								</table>


								<table width="100%" border="0" style="margin-top: 5px !important;">
									<tr>
										<td width="100px">  </td>
										<td width="10px" class="text-center"></td>
										<td width="500px">
											<button type="submit" class="btn btn-primary btn-md" id="btnTampilkan">TAMPILKAN</button>
											<button type="button" class="btn btn-primary btn-md" id="btnLoading" disabled="disabled" style="display:none;">LOADING...</button>
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
		<div class="box box-primary" <?=($JobNo=='') ? 'style="display:none;"' : '' ?>>
			<div class="box-body">
				<table border="0" width="100%">
					<tr>
						<td>
							<label class="radio-inline"> <input type="radio" name="PilihTermin" data-JobNo="<?=$JobNo?>" class="PilihTermin" value="TerminInduk" checked> Termin Induk </label>
							<label class="radio-inline"> <input type="radio" name="PilihTermin" data-JobNo="<?=$JobNo?>" class="PilihTermin"  value="TerminMember" > Termin Member </label>
						</td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="overlay loading_member" style="display: none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>
		</div>

		<div id="tampil_pilihan_termin"></div>


	</section>

</div>


<div class="modal fade" id="MyModalTerminInduk">

	<div class="modal-dialog modal-md">
		<div class="modal-content">

			<div class="modal-body" id="MyModalTerminInduk_Body">

			</div>

		</div>
	</div>
</div>

<div class="clearfix"></div>
<script src="<?=base_url('assets/select2_baru.js')?>"></script>

<script type="text/javascript">

	$(document).ready(function(){
		$('.select2').select2();

		if ($('#myform #JobNo option:selected').val()!='') {
			dapat_pilihan_termin();
		}
		
		$('#myform #JobNo').change(function(){
			 var TxtUrl = $('#myform').attr('data-TxtUrl');
			 TxtUrl = TxtUrl+this.value;
			 // alert(TxtUrl);
			window.location.href=TxtUrl;
			// dapat_pilihan_termin()
		});

		$('.PilihTermin').change(function(){
			dapat_pilihan_termin();
		});
	});


	function dapat_pilihan_termin(){
		var PilihTermin = $('.PilihTermin:checked').val();
		var JobNo = $('#myform #JobNo option:selected').val();
		// var JobNo = $('.PilihTermin:checked').attr('data-JobNo');
		// alert(JobNo)

		if (JobNo =='') {
			alert('Job masih kosong');
			return false;
		}

		jQuery.ajax({
			url: '<?=site_url('DipaTermin/PenerimaanTermin/Pilihan_termin')?>',
			type: 'POST',
			dataType: 'HTML',
			async: true,
			cache : false,
			data: {'PilihTermin' : PilihTermin, JobNo : JobNo},
			beforeSend : function(){
				$('.loading_member').show();
			},
			complete: function(xhr, textStatus) {
				$('.loading_member').hide();
			},
			success: function(data, textStatus, xhr) {
				// alert(data);
				$('#tampil_pilihan_termin').html(data);
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
