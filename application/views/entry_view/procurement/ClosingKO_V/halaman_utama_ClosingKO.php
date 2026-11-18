<?php
error_reporting(E_ALL);
?>
<style type="text/css">
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
		<h1>
			<?=$judul?>
		</h1>

	</section>

	<section class="content">


		<div class="box">

			<div class="box-body">
				<div class="row">
					<div class="col-sm-4 "><?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?></div>
				</div>
				<div class="row">

					<div class="col-sm-12" style="border: 0px solid;">

						<form  id="Form_ClosingKO">
							<table width="50%" style="border-collapse: collapse;">
								<tr>
									<td width="100px">Job No</td>
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

							

							<table width="50%" style="border-collapse: collapse;margin-top: 10px;">
								<tr>
									<td width="100px"></td>
									<td width="10px" class="text-center"></td>
									<td>
										<button type="submit" class="btn btn-primary btn-md btn_cari huruf15" id="btnTampilkan" >TAMPILKAN <i class="fa fa-search"></<i></i></button>
									</td>
								</tr>
							</table>


						</form>

						<br>
						


					</div> 



				</div>



			</div>
		</div>

		<div id="table_CLosingKO"></div> 

		<div id="myModal" class="modal fade" role="dialog">
			<div class="modal-dialog">

				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title text-yellow">APPROVAL CONFIRMATION </h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-12 text-center" >
								<h4 >Anda Yakin ingin tutup KO berikut ?</h4>
								<h4  class="detail"></h4>
							</div>
							<div class="col-sm-12">

								<form class="form-horizontal" id="formModal" action="<?=site_url('Entry/Procurement/FrmClosingKO_C/aksi_penutupan_KO')?>" method="POST">
									<div class="form-group" hidden >
										<label class="control-label col-sm-3" for="NoKO">No KONTRAK/PO :</label>
										<div class="col-sm-9">
											<input type="text" class="form-control text-bold" id="NoKO" name="NoKO" readonly required>
											<input type="text" class="form-control" id="JobNo" name="JobNo"  readonly required>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-3" for="CancelReason">Alasan Penutupan:</label>
										<div class="col-sm-9">
											<textarea name="ClosedReason" id="ClosedReason" class="form-control" placeholder="Isi Disini" required></textarea>
										</div>
									</div>
									
									<div class="form-group text-right">
										<div class="col-sm-offset-3 col-sm-9">
											<button type="submit" class="btn btn-primary">APPROVE</button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
										</div>
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
			table_CLosingKO();
		}

		$('#Form_ClosingKO').submit(function(event){
			event.preventDefault();

			table_CLosingKO();
		})

		

	});


	function btnClose(NoKO=''){
		if (NoKO=='') {
			toastr.info('Info', 'No.Kontrak kosong');
			return false;
		}

		var VendorNm = $('#btnCloseID'+NoKO).attr('data-VendorNm');
		var JobNo = $('#btnCloseID'+NoKO).attr('data-JobNo');
		$('#myModal .modal-body .detail').text(NoKO+' - '+VendorNm);
		$('#myModal #formModal #NoKO').val(NoKO);
		$('#myModal #formModal #JobNo').val(JobNo);
		$('#myModal').modal('show');

	}

	function table_CLosingKO(){
		var JobNo = $('#JobNo option:selected').val();

		if (JobNo=='') {
			toastr.info('Info', 'Job masih kosong');
			return false;
		}

		jQuery.ajax({
			url: "<?=site_url('Entry/Procurement/FrmClosingKO_C/table_CLosingKO')?>",
			type: 'POST',
			dataType: 'html',
			data: {JobNo : JobNo},
			beforeSend : function(){
				$('#btnTampilkan').attr('disabled',true).text('LOADING...');
				$('#table_CLosingKO').html('');
			},
			complete: function(xhr, textStatus) {
				$('#btnTampilkan').attr('disabled',false).text('TAMPILKAN');
			},
			success: function(data, textStatus, xhr) {
				$('#table_CLosingKO').html(data);
				
			},
			error: function(xhr, textStatus, errorThrown) {
				toastr.error('error', 'Error');
				return false;
			}
		});


	}

</script>


