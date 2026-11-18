<?php
error_reporting(E_ALL);
?>


<style type="text/css">
	body{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
	.huruf8{
		font-size: 9px !important;
		font-family:  sans-serif;
	}
	.huruf10{
		font-size: 10px !important;
		font-family:  sans-serif;
	}

	.huruf11{
		font-size: 11px !important;
		font-family:  sans-serif;
	}

	.huruf12{
		font-size: 12px !important;
	}

	.huruf13{
		font-size: 13px !important;
	}

	.huruf14{
		font-size: 14px !important;
	}
	.huruf15{
		font-size: 15px !important;
	}
	.jarak{
		padding-top: 5px !important;
		padding-bottom: 5px !important;
	}

	.jarak_baru{
		padding-top: 10px !important;
		/* padding-bottom: 5px !important; */
	}

	.select2-container--default .select2-results>.select2-results__options{
		min-height: 300px !important;
		max-height: 400px !important;
	}
	.tengah{
		vertical-align:middle;
	}

</style>

<div class="content-wrapper">

	<section class="content-header">
		<h1><?=$judul?></h1>
		<label><button class="btn btn-md btn-default" onClick="history.back()"><i class="fa fa-arrow-left"></i> BACK</button></label>
	</section>

	<section class="content">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-sm-4 "><?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?></div>
				</div>
				<div class="row">
					<div class="col-sm-7" style="border: 0px solid;">
						<form  id="Form_PJ_Lapangan" class="form-horizontal">
							<table width="50%" style="border-collapse: collapse;">
								<tr>
									<td width="50px">Job No</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<select name="JobNo" id="JobNo" class="form-control select2" required>
											<option value="">-- Pilih --</option>
											<?php foreach ($ListJob as $row): ?>
												<option value="<?=$row->JobNo?>" <?=($JobNo !='' && ($JobNo == $row->JobNo)) ? 'selected' : '' ?>  ><?=$row->JobNo.' - '.$row->JobNm?></option>
											<?php endforeach ?>
										</select>
									</td>
								</tr>
							</table>


							<table width="50%" style="border-collapse: collapse;margin-top: 5px;">
								<tr>
									<td width="50px"></td>
									<td width="10px" class="text-center"></td>
									<td>
										<button type="submit" class="btn btn-default btn-md btn_cari" id="btnTampilkan" >TAMPILKAN</button>
									</td>
								</tr>
							</table>
						</form>
					</div> 
				</div>
			</div>
			<div class="box-footer">

				
				<div id="table_PJ_Lapangan"></div>
			</div>
		</div>



		<div id="Modal_Entry_Invoice" class="modal fade" role="dialog">
			<div class="modal-dialog">

				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title text-bold">Entry Invoice </h4>
					</div>
					<div class="modal-body">
						
						<div class="row">

							<div class="col-sm-12">
								
								<form class="form-horizontal" id="Form_SimpanInvoice">
									<div class="table-responsive">
										<table width="100%" border="1" style="border-collapse:collapse;">
											<tr>
												<td>No. KO</td>
												<td width="10px">:</td>
												<td>
													<input type="" name="">
												</td>
											</tr>
										</table>
									</div>
								</form>
								
							</div>

							
						</div>

					</div>
					
				</div>

			</div>
		</div>

	</section>

</div>

<script src="<?=base_url('assets/select2_baru.js')?>"></script>
<script type="text/javascript">
	$(document).ready(function(){

		$('#JobNo').select2();

		toastr.options = {
			"debug": false,
			"positionClass": "toast-bottom-full-width",
			"onclick": null,
			"fadeIn": 300,
			"fadeOut": 1000,
			"timeOut": 5000,
			"extendedTimeOut": 1000
		}

		if ($('#JobNo option:selected').val() !='') {
			table_PJ_Lapangan();
		}

		$('#Form_PJ_Lapangan').submit(function(event){
			event.preventDefault();
			table_PJ_Lapangan();
		})

		$('#Form_PJ_Lapangan #JobNo').on('change',function(){
			table_PJ_Lapangan();
		});

		

	});

	function table_PJ_Lapangan(){
		$('#table_PJ_Lapangan').html('');
		var JobNo = $('#JobNo option:selected').val();

		if (JobNo=='') {
			toastr.info('Info', 'Job masih kosong');
			return false;
		}

		jQuery.ajax({
			url: "<?=site_url('Entry/PDPJ/FrmPJ_Lap/table_PJ_Lapangan')?>",
			type: 'POST',
			dataType: 'html',
			data: {JobNo : JobNo},
			beforeSend : function(){
				$('#btnTampilkan').attr('disabled',true).text('LOADING...');
			},
			complete: function(xhr, textStatus) {
				$('#btnTampilkan').attr('disabled',false).text('TAMPILKAN');
			},
			success: function(data, textStatus, xhr) {
				$('#table_PJ_Lapangan').html(data);
				
			},
			error: function(xhr, textStatus, errorThrown) {
				toastr.error('error', 'Error');
				return false;
			}
		});


	}

</script>


